<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use Illuminate\Http\Request;

class ConcertController extends Controller
{
    public function index()
    {
        // Ambil semua konser, tambahkan placeholder jika tidak ada gambar
        $concerts = Concert::all()->map(function ($concert) {
            $concert->image_url = $concert->image_url ?? asset('assets/images/placeholders/placeholder.png');
            return $concert;
        });

        return view('concerts.index', compact('concerts')); // Kirim ke view index
    }

    public function create()
    {
        return view('concerts.create'); // Tampilkan form untuk membuat konser
    }

    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|string|max:255', // Tambahkan validasi untuk URL gambar
        ]);

        // Simpan data konser
        Concert::create($validatedData);

        return redirect()->route('concerts.index')->with('success', 'Concert created successfully.');
    }

    public function show(Concert $concert)
    {
        // Tambahkan gambar placeholder jika tidak ada gambar
        $concert->image_url = $concert->image_url ?? asset('assets/images/placeholders/placeholder.png');

        return view('concerts.show', compact('concert'));
    }

    public function edit(Concert $concert)
    {
        return view('concerts.edit', compact('concert')); // Tampilkan form edit konser
    }

    public function update(Request $request, Concert $concert)
    {
        // Validasi input
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|string|max:255', // Tambahkan validasi untuk URL gambar
        ]);

        // Update data konser
        $concert->update($validatedData);

        return redirect()->route('concerts.index')->with('success', 'Concert updated successfully.');
    }

    public function destroy(Concert $concert)
    {
        // Hapus data konser
        $concert->delete();

        return redirect()->route('concerts.index')->with('success', 'Concert deleted successfully.');
    }

}

