<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 6);

        $users = User::select(['id', 'name', 'email', 'avatar', 'role', 'status', 'created_at'])
            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('name', 'LIKE', '%' . $search . '%')
                        ->orWhere('email', 'LIKE', '%' . $search . '%')
                        ->orWhere('role', 'LIKE', '%' . $search . '%')
                        ->orWhere('status', 'LIKE', '%' . $search . '%');
                });
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($perPage)
            ->appends($request->query());

        return view('dashboard', compact('users', 'search'));
    }

    public function register(Request $request)
    {
        // التحقق من البيانات
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role'     => 'required|in:reader,writer',
            'avatar'   => 'nullable|image|max:2048',
        ]);

        // رفع الصورة لو موجودة
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatar', 'public');
        }

        // إنشاء المستخدم
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => $request->role,   // هنا الدور القادم من Flutter
            'avatar'   => $avatarPath,
        ]);

        // API
        if ($request->wantsJson()) {
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status'  => true,
                'message' => 'User registered successfully',
                'user'    => $user,
                'token'   => $token
            ], 201);
        }

        // Web
        auth()->login($user);
        
        if ($user->role === 'admin') {
            return redirect()->route('dashboard')->with('success', 'Registration successful');
        } elseif ($user->role === 'writer') {
            return redirect()->route('writer.dashboard')->with('success', 'Registration successful');
        } else {
            return redirect()->route('reader.dashboard')->with('success', 'Registration successful');
        }
    }


    // تسجيل الدخول
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid email or password'
                ], 401);
            }
            return back()->with('error', 'Invalid email or password');
        }

        // Check user status
        if ($user->status !== 'active') {
            $message = 'Your account is not active';
            if ($user->status === 'suspended') {
                $message = 'Your account is suspended. Please contact support.';
            } elseif ($user->status === 'banned') {
                $message = 'Your account has been permanently banned for policy violations.';
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => $message
                ], 403);
            }
            return back()->with('error', $message);
        }

        // تحقق من الحظر القديم (لو عندك عمود is_banned)
        if (isset($user->banned) && $user->banned) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'This account is banned',
                    'ban_until' => $user->ban_until
                ], 403);
            }
            return back()->with('error', 'This account is banned');
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        if ($request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token
            ], 200);
        }

        // Web: Session login
        auth()->login($user);
        
        if ($user->role === 'admin') {
            return redirect()->route('dashboard')->with('success', 'Login successful');
        } elseif ($user->role === 'writer') {
            return redirect()->route('writer.dashboard')->with('success', 'Welcome back, Writer!');
        } else {
            return redirect()->route('reader.dashboard')->with('success', 'Welcome back, Reader!');
        }
    }


    // تسجيل الخروج
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete(); // للموبايل/API
        auth()->logout(); // للويب

        if ($request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Logged out successfully'
            ]);
        }

        return redirect()->route('login')->with('success', 'Logged out successfully');
    }

    // تحديث حالة المستخدم (admin فقط)
    public function updateStatus(Request $request, User $user)
    {
        // تحقق الصلاحيات
        if ($request->user()->role !== 'admin') {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized: Only admin can update status.'
                ], 403);
            }
            return back()->withErrors(['unauthorized' => 'Only admin can update status.']);
        }

        // تحقق من القيمة
        $request->validate([
            'status' => 'required|in:active,suspended,banned',
        ]);

        // تعديل العمود الوحيد
        $user->status = $request->status;

        // إذا محظور أو موقوف مؤقتًا، احذف الـ tokens
        if ($request->status === 'banned' || $request->status === 'suspended') {
            $user->tokens()->delete();
        }

        $user->save();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'User status updated successfully',
                'user' => $user
            ]);
        }

        return back()->with('success', 'User status updated successfully');
    }







    // تحديث الدور (role) (admin فقط)
    public function updateRole(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized: Only admin can change roles.'
                ], 403);
            }
            return back()->withErrors(['unauthorized' => 'Only admin can change roles.']);
        }

        $request->validate([
            'role' => 'required|in:reader,writer,admin,admin_assistant',
        ]);

        $user = User::findOrFail($id);

        if ($user->id === $request->user()->id) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'You cannot change your own role.'
                ], 422);
            }
            return back()->withErrors(['role' => 'You cannot change your own role.']);
        }

        $newRole = $request->role;

        if ($user->role === 'writer' && $newRole === 'reader') {
            $user->writing_restricted = true;
        }

        if ($user->role === 'reader' && $newRole === 'writer') {
            $user->writing_restricted = false;
        }

        $user->role = $newRole;
        $user->save();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Role updated successfully',
                'user' => $user
            ]);
        }

        return back()->with('success', 'Role updated successfully');
    }
}
