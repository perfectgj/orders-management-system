<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        if (!auth()->user()->isStaff())
            abort(403);
        $user = Auth::user();
        return view('admin.profile.show', compact('user')); // re-use same view
    }

    public function update(Request $request)
    {
        if (!auth()->user()->isStaff())
            abort(403);

        $user = Auth::user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|max:2048',
            'password' => 'nullable|confirmed|min:6',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar))
                Storage::disk('public')->delete($user->avatar);
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        if (empty($data['password']))
            unset($data['password']);
        else
            $data['password'] = bcrypt($data['password']);

        $user->update($data);
        return back()->with('success', 'Profile updated');
    }
}
