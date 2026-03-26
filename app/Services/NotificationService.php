<?php

namespace App\Services;

use App\Models\User;
use App\Models\SentNotification;
use App\Notifications\SystemNotification;

class NotificationService
{
    public function sendToTarget($target, $title, $body, $meta = null)
    {
        $query = User::query();

        if ($target === 'authors') {
            $query->where('role', 'author');
        } 
        elseif ($target === 'readers') {
            $query->where('role', 'reader');
        } 
        elseif ($target === 'all') {
            // no filters
        }
        elseif (is_array($target)) {
            $query->whereIn('id', $target);
        }
        elseif (is_numeric($target)) {
            $query->where('id', $target);
        }

        $users = $query->get();

        foreach ($users as $user) {
            $user->notify(new SystemNotification($title, $body, $meta));
        }

        // حفظ السجل
        SentNotification::create([
            'target' => $target,
            'user_id' => is_numeric($target) ? $target : null,
            'title' => $title,
            'body' => $body,
            'meta' => $meta,
            'channel' => 'database',
        ]);
    }
}
