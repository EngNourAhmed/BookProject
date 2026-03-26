<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Article;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show($id)
    {
        $user = User::findOrFail($id);

        // Only allow viewing profiles of writers (or admins if they have articles)
        if ($user->role !== 'writer' && $user->role !== 'admin') {
             // return abort(404);
        }

        $articles = Article::where('user_id', $user->id)
                           ->where('status', 'active')
                           ->latest()
                           ->paginate(12);

        return view('writer.profile', compact('user', 'articles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if (auth()->id() !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'bio' => $request->bio,
        ]);

        return redirect()->route('writer.profile', $user->id)->with('success', 'Profile updated successfully.');
    }
}
