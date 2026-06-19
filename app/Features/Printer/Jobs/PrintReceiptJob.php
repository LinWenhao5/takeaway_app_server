<?php

namespace App\Features\Printer\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PrintReceiptJob implements ShouldQueue
{
    use Queueable;

    protected array $order;
    protected array $printer;

    /**
     * Create a new job instance.
     */
    public function __construct(array $order, array $printer)
    {
        $this->order = $order;
        $this->printer = $printer;
        $this->queue = 'printing';
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $mac = strtolower($this->printer['mac_address']);
        if (!$mac) return;

        $markup = $this->generateMarkup($this->order);

        $jobToken = 'order_' . $this->order['id'] . '_' . Str::uuid();

        $queueKey = "printer:queue:{$mac}";
        
        Redis::rpush($queueKey, json_encode([
            'job_token' => $jobToken,
            'order_id' => $this->order['id'],
            'printer_mac' => $mac,
            'created_at' => time(),
            'markup'   => $markup,
        ]));
    }


   /**
     * Generates a structured layout string for 80mm thermal printers.
     * Handles wrapping, text alignment, and dynamic label translations.
     *
     * @param array $orderData
     * @return string Formatted template output with ESC/POS-like markers.
     */
    private function generateMarkup(array $orderData): string
    {
        // -----------------------------------------------------------------
        // 1. DATA EXTRACTION & INITIALIZATION
        // -----------------------------------------------------------------
        $orderId      = $orderData['id'];
        $orderType    = strtoupper($orderData['order_type'] ?? 'PICKUP'); 
        $totalPrice   = number_format((float)$orderData['total_price'], 2, '.', '');
        $deliveryFee  = isset($orderData['delivery_fee']) ? (float)$orderData['delivery_fee'] : 0.00;
        $note         = $orderData['note'] ?? null;
        $createdAt    = Carbon::parse($orderData['created_at'])->setTimezone('Europe/Amsterdam')->format('d-m-Y H:i');

        // -----------------------------------------------------------------
        // 2. RECEIPT HEADER & METADATA
        // -----------------------------------------------------------------
        $markup  = "[align: center][magnify: width 2; height 2][bold: on]Zen Sushi[bold: off][magnify]\n";
        $markup .= "[align: left]------------------------------------------------\n";
        $markup .= "[bold: on]BESTELLING: #{$orderId} ({$orderType})[bold: off]\n";
        $markup .= "Datum: {$createdAt}\n";

        // Handle scheduled time contextually based on Pickup vs Delivery
        if (!empty($orderData['reserve_time'])) {
            $reserveTime = Carbon::parse($orderData['reserve_time'])->format('d-m-Y H:i');
            $timeLabel   = ($orderType === 'DELIVERY') 
                ? "BEZORG TIJD" 
                : "AFHAAL TIJD";

            $markup .= "[bold: on]{$timeLabel}: {$reserveTime}[bold: off]\n";
        }
        $markup .= "------------------------------------------------\n";

        // -----------------------------------------------------------------
        // 3. PRODUCT ITEMS LOOP
        // -----------------------------------------------------------------
        if (isset($orderData['products_snapshot']) && is_array($orderData['products_snapshot'])) {
            foreach ($orderData['products_snapshot'] as $product) {
                $qtyName  = "{$product['quantity']}x {$product['name']}";
                $priceStr = "EUR " . number_format((float)$product['final_price'], 2, '.', '');

                // Calculate visual padding. mb_strlen avoids layout breaks caused by Dutch multi-byte chars (e.g. ë, é)
                $qtyLength    = mb_strlen($qtyName, 'UTF-8');
                $priceLength  = mb_strlen($priceStr, 'UTF-8');
                $spacesNeeded = 48 - $qtyLength - $priceLength;

                if ($spacesNeeded > 0) {
                    $markup .= $qtyName . str_repeat(" ", $spacesNeeded) . $priceStr . "\n";
                } else {
                    // Push price to a new line right-aligned if product name is exceptionally long
                    $markup .= $qtyName . "\n" . str_pad($priceStr, 48, " ", STR_PAD_LEFT) . "\n";
                }

            }
        }
        $markup .= "------------------------------------------------\n";

        // -----------------------------------------------------------------
        // 4. CUSTOMER NOTES
        // -----------------------------------------------------------------
        if ($note) {
            $markup .= "[bold: on]Opmerking:[bold: off] {$note}\n";
            $markup .= "------------------------------------------------\n";
        }

        // -----------------------------------------------------------------
        // 5. FEES & TOTAL PRICE (Double Width Context)
        // -----------------------------------------------------------------
        // Explicitly inject delivery fee if present to guarantee itemization balances out perfectly
        if ($orderType === 'DELIVERY' && $deliveryFee > 0) {
            $feeStr = "EUR " . number_format($deliveryFee, 2, '.', '');
            $markup .= str_pad("Bezorgkosten (Delivery Fee):", 34, " ", STR_PAD_RIGHT) . str_pad($feeStr, 14, " ", STR_PAD_LEFT) . "\n";
            $markup .= "------------------------------------------------\n";
        }

        // When [magnify: width 2] is active, row constraints drop from 48 characters down to exactly 24 characters
        $totalTextLeft   = "TOTAAL:";
        $totalTextRight  = "EUR {$totalPrice}";
        $totalLeftPadded = str_pad($totalTextLeft, 12, " ", STR_PAD_RIGHT); 
        $totalRightPadded= str_pad($totalTextRight, 12, " ", STR_PAD_LEFT); 
        
        $markup .= "[magnify: width 2; height 1]" . $totalLeftPadded . $totalRightPadded . "[magnify]\n";
        $markup .= "------------------------------------------------\n";

        // -----------------------------------------------------------------
        // 6. TAX (BTW) BREAKDOWN
        // -----------------------------------------------------------------
        if (isset($orderData['vat_snapshot']) && is_array($orderData['vat_snapshot'])) {
            $vatRates = array_filter($orderData['vat_snapshot'], function($key) {
                return $key !== 'total_vat_amount';
            }, ARRAY_FILTER_USE_KEY);

            if (count($vatRates) > 0) {
                $markup .= "[upperline: on]BTW Overzicht:\n";
                
                foreach ($vatRates as $vatName => $vatInfo) {
                    if (!isset($vatInfo['product_total']) || !isset($vatInfo['vat_total'])) {
                        continue;
                    }
                    
                    $leftLabel    = " {$vatName} (Op EUR " . number_format((float)$vatInfo['product_total'], 2, '.', '') . "):";
                    $vatAmountStr = "EUR " . number_format((float)$vatInfo['vat_total'], 2, '.', '');
                    
                    $leftLength   = mb_strlen($leftLabel, 'UTF-8');
                    $rightSpace   = 48 - $leftLength;
                    
                    if ($rightSpace > 0) {
                        $markup .= $leftLabel . str_pad($vatAmountStr, $rightSpace, " ", STR_PAD_LEFT) . "\n";
                    } else {
                        $markup .= $leftLabel . "\n" . str_pad($vatAmountStr, 48, " ", STR_PAD_LEFT) . "\n";
                    }
                }
                
                if (isset($orderData['total_vat_amount'])) {
                    $totalVatStr = "EUR " . number_format((float)$orderData['total_vat_amount'], 2, '.', '');
                    $markup .= str_pad("Totale BTW: ", 34, " ", STR_PAD_RIGHT) . str_pad($totalVatStr, 14, " ", STR_PAD_LEFT) . "\n";
                }
                
                $markup .= "[upperline: off]------------------------------------------------\n";
            }
        }

        // -----------------------------------------------------------------
        // 7. DELIVERY SHIPPING ADDRESS
        // -----------------------------------------------------------------
        if ($orderType === 'DELIVERY' && !empty($orderData['address_snapshot'])) {
            $address = $orderData['address_snapshot'];
            
            $markup .= "[bold: on]BEZORGADRES:[bold: off]\n";
            $markup .= "Klant (Name):   {$address['name']}\n";
            $markup .= "Telefoon:       {$address['phone']}\n";
            $markup .= "Adres:          {$address['street']} {$address['house_number']}\n";
            $markup .= "Postcode/Stad:  {$address['postcode']} {$address['city']}\n";
            
            $markup .= "------------------------------------------------\n";
        }

        // -----------------------------------------------------------------
        // 8. FOOTER & HARDWARE BUFFER MARGINS
        // -----------------------------------------------------------------
        $markup .= "[align: center]Bedankt voor uw bestelling!\n";
        $markup .= "Eet smakelijk!\n\n\n\n"; // Padding lines force the text clear of the physical cutting mechanism
        $markup .= "[cut]\n";

        return $markup;
    }
}
