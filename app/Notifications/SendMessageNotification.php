<?php

namespace App\Notifications;

use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class SendMessageNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $message;
    public $group;

    public function __construct($message, $group)
    {
        $this->message = $message;
        $this->group = $group;
    }

    public function via($notifiable)
    {
        return ['broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => $this->message,
            'group' => $this->group,
        ]);
    }

    public function broadcastOn()
    {
        return new Channel('group.' . $this->group->pk_group);
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }
}