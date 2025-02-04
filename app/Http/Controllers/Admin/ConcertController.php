<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concert;
use App\Models\Artist;
use App\Http\Requests\Admin\ConcertRequest;
use App\Services\ConcertService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ConcertController extends Controller
{
    protected $concertService;
    protected $imageService;

    public function __construct(ConcertService $concertService, ImageService $imageService)
    {
        $this->concertService = $concertService;
        $this->imageService = $imageService;
    }

    public function index(Request $request)
    {
        try {
            $concerts = Concert::with(['artist'])
                ->when($request->search, function ($query, $search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('venue', 'like', "%{$search}%");
                })
                ->when($request->status, function ($query, $status) {
                    $query->where('status', $status);
                })
                ->when($request->date_from, function ($query, $date) {
                    $query->whereDate('date', '>=', $date);
                })
                ->when($request->date_to, function ($query, $date) {
                    $query->whereDate('date', '<=', $date);
                })
                ->orderBy($request->get('sort_by', 'created_at'), $request->get('sort_order', 'desc'))
                ->paginate(10) // Paginasi untuk menghindari Collection biasa
                ->withQueryString();

            return view('admin.concerts.index', compact('concerts'));
        } catch (\Exception $e) {
            \Log::error('Error in ConcertController@index: ' . $e->getMessage());
            return back()->withError('Error loading concerts. Please try again.');
        }
    }

    public function create()
    {
        $artists = Artist::orderBy('name')->get();
        return view('admin.concerts.create', compact('artists'));
    }

    public function store(ConcertRequest $request)
    {
        // Mengambil data yang valid dari request
        $data = $request->validated();

        // Menggunakan ConcertService untuk membuat konser
        $concert = app(ConcertService::class)->createConcert($data);

        // Membuat tipe tiket jika ada
        if (isset($data['ticket_types'])) {
            app(ConcertService::class)->createTicketTypes($concert, $data['ticket_types']);
        }

        return redirect()->route('concerts.index')->with('success', 'Concert created successfully');
    }


    public function edit(Request $request, Concert $concert)
{
    try {
        // Load relasi yang diperlukan
        $concert->load(['ticketTypes', 'artist']);

        // Ambil daftar artis dengan filter seperti di fungsi index
        $artists = Artist::when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy($request->get('sort_by', 'name'), $request->get('sort_order', 'asc'))
            ->get();

        return view('admin.concerts.edit', compact('concert', 'artists'));

    } catch (\Exception $e) {
        \Log::error('Error in ConcertController@edit: ' . $e->getMessage());
        return back()->withError('Error loading concert data. Please try again.');
    }
}

public function update(ConcertRequest $request, Concert $concert)
{
    try {
        DB::beginTransaction();

        $validated = $request->validated();

        // Jika ticket_types tidak ada, beri array kosong agar tidak error
        $validated['ticket_types'] = $validated['ticket_types'] ?? [];

        // Handle image upload
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($concert->image_url) {
                $this->imageService->deleteImage($concert->image_url);
            }

            // Unggah gambar baru
            $validated['image_url'] = $this->imageService->uploadImage(
                $request->file('image'),
                'concerts',
                1920
            );
        }

        // Update data konser
        $this->concertService->updateConcert($concert, $validated);

        // Hapus tiket lama jika perlu
        $concert->ticketTypes()->delete();

        // Tambahkan tiket baru
        if (!empty($validated['ticket_types'])) {
            $this->concertService->updateTicketTypes($concert, $validated['ticket_types']);
        }

        DB::commit();

        return redirect()
            ->route('admin.concerts.index')
            ->with('success', 'Concert updated successfully');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error in ConcertController@update: ' . $e->getMessage());

        return back()
            ->withInput()
            ->withError('Failed to update concert. Please try again.');
    }
}


public function destroy(Concert $concert)
{
    try {
        // Cek apakah ada order yang terkait
        if ($concert->orders()->exists()) {
            return back()->withError('Cannot delete concert with existing orders.');
        }

        DB::beginTransaction();

        // Menghapus gambar
        if ($concert->image_url) {
            $this->imageService->deleteImage($concert->image_url);
        }

        // Menghapus konser (pastikan ini berjalan dengan baik)
        $concert->delete();

        DB::commit();

        return redirect()->route('admin.concerts.index')->with('success', 'Concert deleted successfully');
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error in ConcertController@destroy: ' . $e->getMessage());

        // Mencetak error lebih rinci
        return back()->withError('Failed to delete concert. Please try again: ' . $e->getMessage());
    }
}
}
