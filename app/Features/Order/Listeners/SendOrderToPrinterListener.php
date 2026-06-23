<?php
namespace App\Features\Order\Listeners;

use App\Features\Order\Events\OrderCreated;
use App\Features\Printer\Jobs\PrintReceiptJob;
use App\Features\Printer\Models\Printer;

class SendOrderToPrinterListener
{
    public function handle(OrderCreated $event): void
    {
        $printers = Printer::where('is_online', true)->get();
        foreach ($printers as $printer) {
            PrintReceiptJob::dispatch($event->order, $printer->toArray());
        }
    }
}