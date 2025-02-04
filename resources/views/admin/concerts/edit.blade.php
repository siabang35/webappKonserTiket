@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Concert</h1>
            <a href="{{ route('admin.concerts.index') }}" class="text-gray-600 hover:text-gray-900">
                <span class="text-sm">Back to Concerts</span>
            </a>
        </div>

        <form action="{{ route('admin.concerts.update', $concert->id) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Concert Name</label>
                        <input type="text" name="name" id="name"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('name', $concert->name) }}" required>
                    </div>

                    <div>
    <label for="artist_id" class="block text-sm font-medium text-gray-700">Artist</label>
    <select name="artist_id" id="artist_id"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
        <option value="" disabled {{ is_null($concert->artist_id) ? 'selected' : '' }}>-- Pilih Artis --</option>
        @foreach ($artists as $artist)
            <option value="{{ $artist->id }}" {{ $concert->artist_id == $artist->id ? 'selected' : '' }}>
                {{ $artist->name }}
            </option>
        @endforeach
    </select>
</div>



                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  required>{{ old('description', $concert->description) }}</textarea>
                    </div>
                </div>

                <!-- Date, Time & Location -->
                <div class="space-y-6">
                    <div>
                        <label for="venue" class="block text-sm font-medium text-gray-700">Venue</label>
                        <input type="text" name="venue" id="venue"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               value="{{ old('venue', $concert->venue) }}" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" name="date" id="date"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   value="{{ old('date', $concert->date instanceof \Carbon\Carbon ? $concert->date->format('Y-m-d') : $concert->date) }}"
                                   required>
                        </div>

                        <div>
                            <label for="time" class="block text-sm font-medium text-gray-700">Time</label>
                            <input type="time" name="time" id="time"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   value="{{ old('time', $concert->time instanceof \Carbon\Carbon ? $concert->time->format('H:i') : $concert->time) }}"
                                   required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ticket Types Section -->
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Ticket Types</h3>
                <div id="ticket-types" class="space-y-4">
                    @foreach($concert->ticketTypes as $index => $ticketType)
                    <div class="ticket-type-row grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-gray-50 rounded-lg">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="ticket_types[{{ $index }}][name]"
                                   value="{{ old("ticket_types.$index.name", $ticketType->name) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Price</label>
                            <input type="number" name="ticket_types[{{ $index }}][price]"
                                   value="{{ old("ticket_types.$index.price", $ticketType->price) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   min="0" step="0.01" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Quantity</label>
                            <input type="number" name="ticket_types[{{ $index }}][quantity]"
                                   value="{{ old("ticket_types.$index.quantity", $ticketType->quantity) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                   min="1" required>
                        </div>
                        <div class="flex items-end">
                            <button type="button" class="remove-ticket-type text-red-600 hover:text-red-800">
                                Remove
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" id="add-ticket-type"
                        class="mt-4 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    Add Ticket Type
                </button>
            </div>

            <!-- Image Upload -->
            <div class="mt-8">
                <label for="image" class="block text-sm font-medium text-gray-700">Concert Image</label>
                <div class="mt-2 flex items-center space-x-4">
                    @if ($concert->image_url)
                        <div class="relative">
                            <img src="{{ asset('storage/' . str_replace('/storage/', '', $concert->image_url)) }}"
                                 alt="Current concert image"
                                 class="h-24 w-24 object-cover rounded-lg">
                            <span class="text-xs text-gray-500 mt-1 block">Current image</span>
                        </div>
                    @endif
                    <div class="flex-1">
                        <input type="file" name="image" id="image" accept="image/*"
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-1 text-xs text-gray-500">PNG, JPG up to 2MB</p>
                    </div>
                </div>
            </div>

            <!-- Promotion Settings -->
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Promotion Settings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_promotion" value="1"
                                   {{ old('is_promotion', $concert->is_promotion) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Enable Promotion</span>
                        </label>
                    </div>
                    <div>
                        <label for="promotion_price" class="block text-sm font-medium text-gray-700">Promotion Price</label>
                        <input type="number" name="promotion_price" id="promotion_price"
                               value="{{ old('promotion_price', $concert->promotion_price) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                               min="0" step="0.01">
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex justify-end space-x-3">
                <a href="{{ route('admin.concerts.index') }}"
                   class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Concert
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ticketTypesContainer = document.getElementById('ticket-types');
    const addButton = document.getElementById('add-ticket-type');

    // Add new ticket type
    addButton.addEventListener('click', function() {
        const index = ticketTypesContainer.children.length;
        const template = `
            <div class="ticket-type-row grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-gray-50 rounded-lg">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="ticket_types[${index}][name]"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Price</label>
                    <input type="number" name="ticket_types[${index}][price]"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           min="0" step="0.01" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Quantity</label>
                    <input type="number" name="ticket_types[${index}][quantity]"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                           min="1" required>
                </div>
                <div class="flex items-end">
                    <button type="button" class="remove-ticket-type text-red-600 hover:text-red-800">
                        Remove
                    </button>
                </div>
            </div>
        `;
        ticketTypesContainer.insertAdjacentHTML('beforeend', template);
    });

    // Remove ticket type
    ticketTypesContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-ticket-type')) {
            if (ticketTypesContainer.children.length > 1) {
                e.target.closest('.ticket-type-row').remove();
            } else {
                alert('At least one ticket type is required.');
            }
        }
    });
});
</script>
@endpush
@endsection
