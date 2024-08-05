<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class SendMessageNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $message;

    public $group;

    public $user;

    public function __construct($message, $group, $user)
    {
        $this->message = $message;
        $this->group = $group;
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        $dataMessage = Message::create([
            'fk_group' => $this->group->pk_group,
            'st_message' => $this->message['st_message'] ?? null,
            'url_file_audio' => $this->message['url_file_audio'] ?? null,
            'st_file_type' => $this->message['file_type'] ?? null,
            'st_name_file' => $this->message['name_file'] ?? null,
            'int_message_type' => 1,
            'fk_user_send_message' => $this->user,
        ]);

        return new BroadcastMessage([
            'name_file' => $this->message['name_file'] ?? null,
            'file_type' => $this->message['file_type'] ?? null,
            'message' => $dataMessage,
            'group' => $this->group,
            'user' => $dataMessage->userSendMessage,
        ]);
    }

    public function broadcastOn()
    {
        return new Channel('group.'.$this->group->pk_group);
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }
}
