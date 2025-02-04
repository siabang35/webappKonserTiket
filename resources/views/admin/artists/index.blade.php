@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Artists List</h1>
            <a href="{{ route('admin.artists.create') }}"
               class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                Add New Artist
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-4 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        <table class="min-w-full table-auto">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Name</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($artists as $artist)
                <tr class="border-b">
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $artist->name }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ ucfirst($artist->status) }}</td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <a href="{{ route('admin.artists.show', $artist) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                        <a href="{{ route('admin.artists.edit', $artist) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">Edit</a>
                        <form action="{{ route('admin.artists.destroy', $artist) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
