<?php
namespace App\Http\Controllers;

use App\Models\Card;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    private function checkAdmin()
    {
        abort_if(auth()->user()->role !== 'admin', 403);
    }

    public function cards()
{
    $this->checkAdmin();
    $cards = Card::with(['owner', 'transactions' => function($q) {
        $q->latest()->limit(1);
    }])->latest()->paginate(20);
    return view('admin.cards', compact('cards'));
}

    public function removeCard(Card $card)
    {
        $this->checkAdmin();
        $card->update(['status' => 'unavailable', 'is_validated' => false]);
        return redirect()->back()->with('success', 'Carta rimossa dal marketplace.');
    }

    public function restoreCard(Card $card)
    {
        $this->checkAdmin();
        $card->update(['status' => 'available', 'is_validated' => true]);
        return redirect()->back()->with('success', 'Carta ripristinata.');
    }
}