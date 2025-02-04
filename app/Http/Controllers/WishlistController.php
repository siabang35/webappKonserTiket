<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Concert;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = auth()->user()->wishlists()->with('concert')->latest()->get();

        return view('wishlists.wishlist', compact('wishlists'));
    }

    public function store(Concert $concert)
    {
        auth()->user()->wishlists()->firstOrCreate([
            'concert_id' => $concert->id
        ]);

        return back()->with('success', 'Konser berhasil ditambahkan ke wishlist');
    }

    public function destroy(Wishlist $wishlist)
    {
        $this->authorize('delete', $wishlist);

        $wishlist->delete();

        return back()->with('success', 'Konser berhasil dihapus dari wishlist');
    }
}
