<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        $stats = [
            'carte'    => Card::where('user_id', $user->id)->count(),
            'vendite'  => Transaction::where('seller_id', $user->id)->where('status', 'completed')->count(),
            'acquisti' => Transaction::where('buyer_id', $user->id)->where('status', 'completed')->count(),
            'guadagni' => Transaction::where('seller_id', $user->id)->where('status', 'completed')->sum('amount'),
        ];

        return view('profile.show', compact('user', 'stats'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $user->id,
            'phone'         => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:255',
            'city'          => 'nullable|string|max:100',
            'zip'           => 'nullable|string|max:10',
            'profile_photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'password'      => 'nullable|min:8|confirmed',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'address', 'city', 'zip']);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo'] = $path;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profilo aggiornato con successo!');
    }
}