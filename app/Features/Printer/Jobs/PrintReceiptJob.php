<?php

namespace App\Features\Printer\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use App\Features\Printer\Support\PrinterFactory;

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
        $mac = strtolower($this->printer['mac_address'] ?? '');
        if (!$mac) {
            return;
        }

        $types = ['receipt', 'kitchen'];

        $queueKey = "printer:queue:{$mac}";

        foreach ($types as $type) {
            try {
                $jobToken = 'order_' . $this->order['id'] . '_' . $type . '_' . Str::uuid();
                
                $generator = PrinterFactory::make($type);
                $binary = $generator->generate($this->order);

                $cacheKey = "printer:binary:{$mac}:{$jobToken}";
                Redis::setex($cacheKey, 3600, $binary);

                Redis::rpush($queueKey, json_encode([
                    'job_token'   => $jobToken,
                    'order_id'    => $this->order['id'],
                    'printer_mac' => $mac,
                    'type'        => $type,
                    'created_at'  => time(),
                    'order_data'  => $this->order, 
                ]));
                \Log::info("Queued {$type} print job for order {$this->order['id']} to printer {$mac}");
            } catch (\Throwable $e) {
                \Log::error("Failed to generate {$type} branch for order {$this->order['id']}: " . $e->getMessage());
            }
        }
    }
}
