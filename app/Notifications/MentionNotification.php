<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MentionNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $sender;
    protected $chatId;
    protected $isEveryone;

    public function __construct($message, $sender, $chatId = null, $isEveryone = false)
    {
        $this->message = $message;
        $this->sender = $sender;
        $this->chatId = $chatId;
        $this->isEveryone = $isEveryone;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'mention',
            'sender_id' => $this->sender->id,
            'sender_name' => $this->sender->name,
            'sender_avatar' => $this->sender->foto_profil,
            'message' => $this->isEveryone ? 'Menyebut semua orang dalam obrolan' : 'Menyebut Anda dalam obrolan',
            'content' => $this->message,
            'url' => route('chat.index') . ($this->chatId ? '#chat-message-' . $this->chatId : ''),
        ];
    }
}
