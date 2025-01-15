<?php

namespace App\Jobs;

use App\Traits\MarketerTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use App\Models\MarketerTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessMarketerCommission implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private MarketerTransaction $transaction
    ) {}

    public function handle(MarketerTrait $affiliateService): void
    {
        try {
            DB::transaction(function () use ($affiliateService) {
                $affiliateService->distributeCommissions($this->transaction);
            });
        } catch (\Exception $e) {
            Log::error('Failed to process affiliate commission: ' . $e->getMessage(), [
                'transaction_id' => $this->transaction->id,
                'affiliate_id' => $this->transaction->affiliate_id
            ]);

            $this->fail($e);
        }
    }
}
