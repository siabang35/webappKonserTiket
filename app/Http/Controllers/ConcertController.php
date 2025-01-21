<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use Illuminate\Http\Request;

class ConcertController extends Controller
{
    public function index()
    {
        $concerts = Concert::all();
        return view('concerts.index', compact('concerts'));
    }

    public function create()
    {
        return view('concerts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        Concert::create($request->all());

        return redirect()->route('concerts.index');
    }

    public function show($id)
    {
        $concert = Concert::findOrFail($id);
        return view('concerts.show', compact('concert'));
    }

    public function edit($id)
    {
        $concert = Concert::findOrFail($id);
        return view('concerts.edit', compact('concert'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        $concert = Concert::findOrFail($id);
        $concert->update($request->all());

        return redirect()->route('concerts.index');
    }

    public function destroy($id)
    {
        $concert = Concert::findOrFail($id);
        $concert->delete();

        return redirect()->route('concerts.index');
    }
}
