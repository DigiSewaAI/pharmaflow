<?php

namespace App\Listeners;

use App\Events\SaleCompleted;
use App\Events\LowStockDetected;
use App\Events\ExpiryApproaching;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUser implements ShouldQueue
{
    use InteractsWithQueue;

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle the event based on type.
     */
    public function handle($event)
    {
        $data = [];
        $type = 'system';

        if ($event instanceof SaleCompleted) {
            $data = [
                'title' => 'Sale Completed',
                'message' => 'Invoice #' . $event->sale->invoice_number . ' - Rs ' . number_format($event->sale->grand_total, 0),
                'url' => route('sales.show', $event->sale->id),
            ];
            $type = 'sale';
        } elseif ($event instanceof LowStockDetected) {
            $data = [
                'title' => 'Low Stock Alert',
                'message' => $event->medicine->name . ' has only ' . $event->medicine->quantity . ' units left.',
                'url' => route('medicines.edit', $event->medicine->id),
            ];
            $type = 'stock';
        } elseif ($event instanceof ExpiryApproaching) {
            $data = [
                'title' => 'Expiry Alert',
                'message' => $event->medicine->name . ' expires on ' . $event->medicine->expiry_date . '. ' . $event->medicine->quantity . ' units in stock.',
                'url' => route('medicines.edit', $event->medicine->id),
            ];
            $type = 'expiry';
        }

        // Send notification to user
        $user = \App\Models\User::find($event->userId);
        if ($user) {
            $this->notificationService->send($user, $data, $type);
        }
    }
}