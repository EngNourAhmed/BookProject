<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SystemNotification extends Notification
{
    use Queueable;

    public $title;
    public $body;
    public $meta;

    public function __construct($title, $body, $meta = null)
    {
        $this->title = $title;
        $this->body  = $body;
        $this->meta  = $meta;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->title,
            'body'  => $this->body,
            'meta'  => $this->meta
        ];
    }
}
