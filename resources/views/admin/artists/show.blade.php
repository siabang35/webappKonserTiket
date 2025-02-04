@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Artist Details</h1>
            <a href="{{ route('admin.artists.index') }}"
               class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 hover:text-gray-900 bg-white rounded-md border border-gray-300 hover:bg-gray-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back
            </a>
        </div>

        <div class="bg-white shadow-sm rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Artist Information</h2>
            <p><strong>Name:</strong> {{ $artist->name }}</p>
            <p><strong>Biography:</strong> {{ $artist->biography ?? 'N/A' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($artist->status) }}</p>
        </div>

        <div class="mt-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Artist Image</h2>
            @if($artist->image)
                <img src="{{ asset('storage/'.$artist->image) }}" alt="{{ $artist->name }}" class="w-full rounded-md">
            @else
                <p>No image available</p>
            @endif
        </div>
    </div>
</div>
@endsection
