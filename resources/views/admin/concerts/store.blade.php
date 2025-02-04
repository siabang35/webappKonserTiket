@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded relative" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded relative" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Concert Created Successfully</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.concerts.index') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Back to List
                    </a>
                    <a href="{{ route('admin.concerts.create') }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Create Another
                    </a>
                </div>
            </div>

            @if(isset($concert))
                <div class="border-t border-gray-200 mt-6 pt-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Concert Details</h2>

                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Concert Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $concert->name }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Artist</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $concert->artist->name }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Location</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $concert->location }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Venue</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $concert->venue }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Date & Time</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $concert->date->format('F j, Y') }} at {{ $concert->time->format('g:i A') }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Genre</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $concert->genre ?? 'Not specified' }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $concert->status === 'upcoming' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($concert->status) }}
                                </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Available Tickets</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $concert->tickets_left }}</dd>
                        </div>

                        <div>
                            <dt class="text-sm font-medium text-gray-500">Regular Price</dt>
                            <dd class="mt-1 text-sm text-gray-900">${{ number_format($concert->price, 2) }}</dd>
                        </div>

                        @if($concert->is_promotion)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Promotion Price</dt>
                                <dd class="mt-1 text-sm text-gray-900">${{ number_format($concert->promotion_price, 2) }}</dd>
                            </div>
                        @endif

                        <div class="md:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $concert->description }}</dd>
                        </div>

                        @if($concert->image_url)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Concert Image</dt>
                                <dd class="mt-1">
                                    <img src="{{ asset('storage/' . str_replace('/storage/', '', $concert->image_url)) }}"
                                         alt="{{ $concert->name }}"
                                         class="h-32 w-32 object-cover rounded-lg">
                                </dd>
                            </div>
                        @endif

                        @if($concert->ticket_image)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Ticket Image</dt>
                                <dd class="mt-1">
                                    <img src="{{ asset('storage/' . str_replace('/storage/', '', $concert->ticket_image)) }}"
                                         alt="Ticket for {{ $concert->name }}"
                                         class="h-32 w-32 object-cover rounded-lg">
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.concerts.edit', $concert->id) }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Edit Concert
                    </a>
                    <a href="{{ route('admin.concerts.show', $concert->id) }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        View Details
                    </a>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500">No concert details available.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
