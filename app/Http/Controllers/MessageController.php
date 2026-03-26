<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $adminId = 11;

        if ($request->has('support')) {
            // Support Mode: Filter to Admin Only
            $query = Conversation::where(function($q) use ($adminId) {
                $q->where('user_one_id', auth()->id())->where('user_two_id', $adminId);
            })->orWhere(function($q) use ($adminId) {
                $q->where('user_one_id', $adminId)->where('user_two_id', auth()->id());
            });
        } else {
            // Regular Mode: Role-based filtering
            $query = Conversation::where(function($q) use ($adminId) {
                $q->where('user_one_id', auth()->id())
                  ->whereHas('userTwo', function($u) use ($adminId) {
                      if (auth()->user()->role === 'reader') {
                          $u->where('role', 'writer')->where('id', '!=', $adminId);
                      } elseif (auth()->user()->role === 'writer') {
                          $u->where('role', 'reader')->where('id', '!=', $adminId);
                      } else {
                          $u->where('id', '!=', $adminId);
                      }
                  });
            })->orWhere(function($q) use ($adminId) {
                $q->where('user_two_id', auth()->id())
                  ->whereHas('userOne', function($u) use ($adminId) {
                      if (auth()->user()->role === 'reader') {
                          $u->where('role', 'writer')->where('id', '!=', $adminId);
                      } elseif (auth()->user()->role === 'writer') {
                          $u->where('role', 'reader')->where('id', '!=', $adminId);
                      } else {
                          $u->where('id', '!=', $adminId);
                      }
                  });
            });
        }

        $conversations = $query->with(['userOne', 'userTwo', 'lastMessage'])
            ->get()
            ->sortByDesc(function($conversation) {
                return $conversation->lastMessage ? $conversation->lastMessage->created_at : $conversation->created_at;
            });

        $activeConversationId = null;
        if ($request->has('user_id')) {
            $user_one_id = min(auth()->id(), $request->user_id);
            $user_two_id = max(auth()->id(), $request->user_id);
            $conv = Conversation::firstOrCreate([
                'user_one_id' => $user_one_id,
                'user_two_id' => $user_two_id
            ]);
            $activeConversationId = $conv->id;
            
            // Re-fetch to include the new one if created
            $conversations = Conversation::where('user_one_id', auth()->id())
                ->orWhere('user_two_id', auth()->id())
                ->with(['userOne', 'userTwo', 'lastMessage'])
                ->get()
                ->sortByDesc(function($conversation) {
                    return $conversation->lastMessage ? $conversation->lastMessage->created_at : $conversation->created_at;
                });
        }

        return view('messages.index', compact('conversations', 'activeConversationId'));
    }

    public function show($id)
    {
        $conversation = Conversation::with(['userOne', 'userTwo', 'messages.sender'])
            ->findOrFail($id);

        if ($conversation->user_one_id !== auth()->id() && $conversation->user_two_id !== auth()->id()) {
            abort(403);
        }

        // Mark unread messages as read
        $conversation->messages()
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'conversation' => $conversation,
            'auth_id' => auth()->id()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'content' => 'required|string'
        ]);

        $user_one_id = min(auth()->id(), $request->recipient_id);
        $user_two_id = max(auth()->id(), $request->recipient_id);

        $conversation = Conversation::firstOrCreate([
            'user_one_id' => $user_one_id,
            'user_two_id' => $user_two_id
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'content' => $request->content
        ]);

        // Notify recipient
        $recipient = User::find($request->recipient_id);
        if ($recipient) {
            $senderName = auth()->user()->name;
            $recipient->notify(new \App\Notifications\SystemNotification(
                'New Message Received',
                "You have a new message from {$senderName}",
                ['conversation_id' => $conversation->id, 'sender_id' => auth()->id()]
            ));
        }

        return response()->json([
            'success' => true,
            'message' => $message->load('sender')
        ]);
    }
}
