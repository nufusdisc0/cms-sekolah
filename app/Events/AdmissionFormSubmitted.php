<?php

namespace App\Events;

use App\Models\Registrant;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithBroadcasting;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdmissionFormSubmitted
{
    use Dispatchable, InteractsWithBroadcasting, SerializesModels;

    public Registrant $registrant;

    /**
     * Create a new event instance.
     */
    public function __construct(Registrant $registrant)
    {
        $this->registrant = $registrant;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
