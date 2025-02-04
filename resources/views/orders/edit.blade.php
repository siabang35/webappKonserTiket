@extends('layouts.layout')

@section('title', 'Edit Pesanan Tiket')

@section('content')
<div class="container mt-5">
    <h1>Edit Pesanan Tiket</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('order.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="concert_id" class="form-label">Konser</label>
            <select name="concert_id" id="concert_id" class="form-select" required>
                @foreach($concerts as $concert)
                    <option value="{{ $concert->id }}" {{ $concert->id == $order->concert_id ? 'selected' : '' }}>
                        {{ $concert->name }} - {{ $concert->location }} ({{ $concert->date->format('d M Y') }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="ticket_type" class="form-label">Jenis Tiket</label>
            <select name="ticket_type" id="ticket_type" class="form-select" required>
                <option value="reguler" {{ $order->ticket_type == 'reguler' ? 'selected' : '' }}>Reguler</option>
                <option value="vip" {{ $order->ticket_type == 'vip' ? 'selected' : '' }}>VIP</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="ticket_count" class="form-label">Jumlah Tiket</label>
            <input type="number" name="ticket_count" id="ticket_count" class="form-control" value="{{ old('ticket_count', $order->ticket_count) }}" min="1" max="10" required>
        </div>

        <div class="mb-3">
            <label for="total_price" class="form-label">Harga Total</label>
            <input type="text" id="total_price" class="form-control" value="Rp. {{ number_format($total_price, 0, ',', '.') }}" readonly>
        </div>

        <button type="submit" class="btn btn-primary">Perbarui Pesanan</button>
    </form>
</div>

<script>
    // Update harga tiket berdasarkan jenis tiket yang dipilih
    document.getElementById('ticket_type').addEventListener('change', function() {
        const concertId = document.getElementById('concert_id').value;
        const ticketType = this.value;
        const ticketCount = document.getElementById('ticket_count').value;

        // Mengambil harga tiket konser
        const concert = @json($concerts);
        const selectedConcert = concert.find(c => c.id == concertId);
        const price = ticketType === 'vip' ? selectedConcert.promotion_price * 2 : selectedConcert.promotion_price;
        const totalPrice = price * ticketCount;

        document.getElementById('total_price').value = `Rp. ${totalPrice.toLocaleString()}`;
    });

    // Update harga total jika jumlah tiket diubah
    document.getElementById('ticket_count').addEventListener('input', function() {
        const concertId = document.getElementById('concert_id').value;
        const ticketType = document.getElementById('ticket_type').value;
        const ticketCount = this.value;

        // Mengambil harga tiket konser
        const concert = @json($concerts);
        const selectedConcert = concert.find(c => c.id == concertId);
        const price = ticketType === 'vip' ? selectedConcert.promotion_price * 2 : selectedConcert.promotion_price;
        const totalPrice = price * ticketCount;

        document.getElementById('total_price').value = `Rp. ${totalPrice.toLocaleString()}`;
    });
</script>

@endsection
