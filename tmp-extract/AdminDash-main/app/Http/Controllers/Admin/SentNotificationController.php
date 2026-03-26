<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\NotificationService;
use App\Models\SentNotification;
use App\Http\Controllers\Controller;

class SentNotificationController extends Controller
{
    protected $service;

    public function __construct(NotificationService $service)
    {
        $this->service = $service;
    }

    private function isApi(Request $request)
    {
        return $request->expectsJson() || $request->is('api/*');
    }

    public function index(Request $request)
    {
        $notifications = SentNotification::latest()->paginate(6);

        // ⭐ نجلب المستخدمين لعرضهم في الجدول ⭐
        $users = \App\Models\User::latest()->paginate(6);

        if ($this->isApi($request)) {
            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'users' => $users
            ]);
        }

        return view('notifications.index', compact('notifications', 'users'));
    }


    public function create(Request $request)
    {
        if ($this->isApi($request)) {
            return response()->json([
                'success' => false,
                'message' => 'هذه الواجهة غير متاحة عبر API'
            ], 403);
        }

        return view('notifications.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'target' => 'required',
            'title'  => 'required|string',
            'body'   => 'required|string'
        ]);

        $this->service->sendToTarget(
            $request->target,
            $request->title,
            $request->body,
            $request->meta
        );

        if ($this->isApi($request)) {
            return response()->json([
                'success' => true,
                'message' => 'تم إرسال الإشعار بنجاح'
            ]);
        }

        return redirect()->route('notifications.index')
            ->with('success', 'تم إرسال الإشعار بنجاح');
    }
}
