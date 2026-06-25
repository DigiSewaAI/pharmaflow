<?php

namespace App\Events;

use App\Models\Medicine;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LowStockDetected implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $medicine;
    public $userId;

    public function __construct(Medicine $medicine, $userId)
    {
        $this->medicine = $medicine;
        $this->userId = $userId;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->userId),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->medicine->id,
            'name' => $this->medicine->name,
            'quantity' => $this->medicine->quantity,
            'message' => 'Low stock alert: ' . $this->medicine->name . ' (only ' . $this->medicine->quantity . ' left)',
            'type' => 'stock',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}