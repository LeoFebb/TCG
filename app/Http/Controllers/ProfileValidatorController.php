<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileValidatorController extends Controller
{
    public function edit()
    {
        return view('validator.profile', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'profile_photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'phone'         => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:100',
            'zip'           => 'nullable|string|max:10',
            'validation_notes' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        $data = $request->only(['phone', 'address', 'city', 'zip', 'validation_notes']);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo'] = $path;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profilo aggiornato con successo!');
    }
}