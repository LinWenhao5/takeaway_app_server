<?php

namespace App\Features\Printer\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use App\Features\Printer\Services\ReceiptImageGenerator;

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

        $jobToken = 'order_' . $this->order['id'] . '_' . Str::uuid();

        $generator = new ReceiptImageGenerator();
        $binary = $generator->generate($this->order);

        $cacheKey = "printer:binary:{$mac}:{$jobToken}";
        Redis::setex($cacheKey, 3600, $binary);

        $queueKey = "printer:queue:{$mac}";

        Redis::rpush($queueKey, json_encode([
            'job_token' => $jobToken,
            'order_id' => $this->order['id'],
            'printer_mac' => $mac,
            'created_at' => time(),
            'order_data' => $this->order,
        ]));
    }

}
