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
            "تمت الموافقة عليك",
            "تمت الموافقة على حسابك كناشر ويمكنك الآن نشر المقالات.",
            ['type' => 'publisher_approved']
        );
    }
}
