<?php

namespace App\Http\Controllers;

use App\Models\Card;

class SitemapController extends Controller
{
    public function index()
    {
        $cards = Card::where('status', 'available')->where('is_validated', true)->latest()->get();

        return response()->view('sitemap', compact('cards'))->header('Content-Type', 'text/xml');
    }
}
