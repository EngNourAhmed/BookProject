<?php

namespace App\Listeners;

use App\Events\PublisherApproved;
use App\Services\NotificationService;

class SendPublisherApprovalNotification
{
    protected $service;

    public function __construct(NotificationService $service)
    {
        $this->service = $service;
    }

    public function handle(PublisherApproved $event)
    {
        $this->service->sendToTarget(
            $event->user->id,
            "You have been approved",
            "Your account has been approved as a publisher and you can now publish articles.",
            ['type' => 'publisher_approved']
        );
    }
}
