<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AnnouncementNotification extends Notification
{
    use Queueable;

    protected $pengumuman;
    protected $author;

    public function __construct($pengumuman, $author)
    {
        $this->pengumuman = $pengumuman;
        $this->author = $author;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'announcement',
            'id' => $this->pengumuman->id,
            'title' => $this->pengumuman->judul,
            'author_name' => $this->author->name,
            'message' => 'Membuat pengumuman baru: ' . $this->pengumuman->judul,
            'url' => route('pengumuman.show', $this->pengumuman->id),
        ];
    }
}
