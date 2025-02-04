@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4">Dashboard</h1>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="bi bi-currency-dollar text-primary fs-4"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Total Sales</h6>
                            <h2 class="card-title mb-0">Rp{{ number_format($stats['total_sales'], 0, ',', '.') }}</h2>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-primary" style="width: 70%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="bi bi-cart text-success fs-4"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Total Orders</h6>
                            <h2 class="card-title mb-0">{{ $stats['total_orders'] }}</h2>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-success" style="width: 60%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="bi bi-people text-info fs-4"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Total Users</h6>
                            <h2 class="card-title mb-0">{{ $stats['total_users'] }}</h2>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-info" style="width: 50%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                            <i class="bi bi-music-note text-warning fs-4"></i>
                        </div>
                        <div>
                            <h6 class="card-subtitle text-muted mb-1">Total Concerts</h6>
                            <h2 class="card-title mb-0">{{ $stats['total_concerts'] }}</h2>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-warning" style="width: 40%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sales Chart -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">Monthly Sales</h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Recent Orders</h5>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($recentOrders as $order)
                        <a href="{{ route('admin.orders.show', $order) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $order->user->name }}</h6>
                                <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1">{{ $order->concert->name }}</p>
                            <small class="text-muted">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</small>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Concerts -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Upcoming Concerts</h5>
                    <a href="{{ route('admin.concerts.create') }}" class="btn btn-sm btn-primary">Add New Concert</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Concert</th>
                                    <th>Date</th>
                                    <th>Venue</th>
                                    <th>Price</th>
                                    <th>Tickets Left</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upcomingConcerts as $concert)
                                <tr>
                                    <td>{{ $concert->name }}</td>
                                    <td>{{ $concert->date->format('d M Y') }}</td>
                                    <td>{{ $concert->venue }}</td>
                                    <td>Rp{{ number_format($concert->price, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-success" style="width: {{ $concert->total_tickets > 0 ? (($concert->total_tickets - $concert->tickets_sold) / $concert->total_tickets * 100) : 0 }}%">

                                        </div>
                                        <small class="text-muted">{{ $concert->total_tickets - $concert->tickets_sold }} left</small>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.concerts.edit', $concert) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('salesChart').getContext('2d');

    const salesData = @json($monthlySales);
    const labels = salesData.map(data => {
        const date = new Date(data.year, data.month - 1);
        return date.toLocaleString('default', { month: 'short', year: 'numeric' });
    }).reverse();
    const values = salesData.map(data => data.total).reverse();

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Sales',
                data: values,
                borderColor: '#0d6efd',
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
