<?php

namespace App\Events;

use App\Models\Medicine;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExpiryApproaching implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $medicine;
    public $userId;

    public function __construct(Medicine $medicine, $userId)
    {
        $this->medicine = $medicine;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->userId),
        ];
    }

    /**
     * The event's broadcast name.
     * This must match the name used in Echo.listen()
     */
    public function broadcastAs(): string
    {
        return 'ExpiryApproaching';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        $daysLeft = now()->diffInDays($this->medicine->expiry_date, false);
        
        return [
            'id' => $this->medicine->id,
            'name' => $this->medicine->name,
            'expiry_date' => $this->medicine->expiry_date->format('Y-m-d'),
            'days_left' => max(0, $daysLeft), // ensure non-negative
            'message' => '📅 Expiry approaching: ' . $this->medicine->name . ' (expires in ' . max(0, $daysLeft) . ' days)',
            'type' => 'expiry',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}