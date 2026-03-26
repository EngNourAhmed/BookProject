<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function toggle(Request $request, $userId)
    {
        $userToFollow = User::findOrFail($userId);
        $me = auth()->user();

        if ($me->id == $userToFollow->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot follow yourself.'
            ], 422);
        }

        if ($me->isFollowing($userId)) {
            $me->following()->detach($userId);
            $status = 'unfollowed';
        } else {
            $me->following()->attach($userId);
            $status = 'followed';
        }

        return response()->json([
            'success' => true,
            'status' => $status,
            'followers_count' => $userToFollow->followers()->count()
        ]);
    }
}
