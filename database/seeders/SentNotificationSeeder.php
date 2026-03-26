<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SentNotification;
use App\Models\User;

class SentNotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            return;
        }

        foreach (range(1, 10) as $i) {
            $target = collect(['all', 'authors', 'readers', 'user'])->random();
            $user_id = ($target === 'user') ? $users->random()->id : null;

            SentNotification::create([
                'target'  => $target,
                'user_id' => $user_id,
                'title'   => "Test Notification $i",
                'body'    => "This is the body for test notification $i. It is used to demonstrate how notifications appear in the admin dashboard.",
                'meta'    => ['type' => 'test_notif', 'source' => 'seeder'],
                'channel' => 'database',
            ]);
        }
    }
}
