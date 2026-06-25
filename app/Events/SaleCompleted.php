<?php

namespace App\Events;

use App\Models\Sale;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SaleCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sale;
    public $userId;

    /**
     * Create a new event instance.
     */
    public function __construct(Sale $sale, $userId)
    {
        $this->sale = $sale;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->userId),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->sale->id,
            'invoice_number' => $this->sale->invoice_number,
            'grand_total' => $this->sale->grand_total,
            'message' => 'New sale #' . $this->sale->invoice_number . ' for Rs ' . number_format($this->sale->grand_total, 0),
            'type' => 'sale',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}