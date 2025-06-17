<?php

namespace App\Events;

use App\Models\JobPost;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class JobStatusUpdated implements ShouldBroadcastNow
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $jobPost;

    /**
     * Create a new event instance.
     */
    public function __construct(JobPost $jobPost)
    {
        //
        $this->jobPost = $jobPost;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('jobs-status-updates'),
        ];
    }

    public function broadcastWith(): array
    {
        return ['jobPost' => $this->jobPost->toArray()];
    }
}
