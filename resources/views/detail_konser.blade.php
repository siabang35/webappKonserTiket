@if (session('success'))
    <div>{{ session('success') }}</div>
@endif

@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form method="POST" action="{{ route('order.store') }}">
    @csrf
    <input type="text" name="concert_name" placeholder="Nama Konser" value="{{ old('concert_name') }}" required>
    <input type="number" name="ticket_count" placeholder="Jumlah Tiket" value="{{ old('ticket_count') }}" required>
    <button type="submit">Pesan Tiket</button>
</form>

<!-- Tampilkan total harga jika ada -->
@if($order ?? false)
    <div>
        <strong>Total Harga:</strong> {{ number_format($order->total_price, 0, ',', '.') }}
    </div>
@endif

