@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-between items-center mb-4">
        <h1 class="h3 mb-0">Add New Concert</h1>
        <div class="btn-group">
            <a href="{{ route('admin.artists.create') }}" class="btn btn-success btn-sm">
                <i class="bi bi-plus-lg"></i> Add New Artist
            </a>
            <a href="{{ route('admin.concerts.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Back to Concerts
            </a>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Error!</strong>
        <ul class="mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.concerts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Concert Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="artist_id" class="form-label">Artist</label>
                            <select class="form-select @error('artist_id') is-invalid @enderror"
                                    id="artist_id" name="artist_id">
                                <option value="">Select Artist</option>
                                @foreach($artists as $artist)
                                    <option value="{{ $artist->id }}" {{ old('artist_id') == $artist->id ? 'selected' : '' }}>
                                        {{ $artist->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('artist_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="genre" class="form-label">Genre</label>
                            <input type="text" class="form-control @error('genre') is-invalid @enderror"
                                   id="genre" name="genre" value="{{ old('genre') }}">
                            @error('genre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror"
                                   id="location" name="location" value="{{ old('location') }}" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="venue" class="form-label">Venue</label>
                            <input type="text" class="form-control @error('venue') is-invalid @enderror"
                                   id="venue" name="venue" value="{{ old('venue') }}">
                            @error('venue')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tickets_left" class="form-label">Available Tickets</label>
                            <input type="number" class="form-control @error('tickets_left') is-invalid @enderror"
                                   id="tickets_left" name="tickets_left" value="{{ old('tickets_left', 100) }}" required>
                            @error('tickets_left')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror"
                                   id="date" name="date" value="{{ old('date') }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="time" class="form-label">Time</label>
                            <input type="time" class="form-control @error('time') is-invalid @enderror"
                                   id="time" name="time" value="{{ old('time') }}">
                            @error('time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror"
                                    id="status" name="status" required>
                                <option value="upcoming" {{ old('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="limited" {{ old('status') == 'limited' ? 'selected' : '' }}>Limited</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="ticket_type" class="form-label">Ticket Type</label>
                            <select class="form-select @error('ticket_type') is-invalid @enderror"
                                    id="ticket_type" name="ticket_type" required>
                                <option value="">Select Ticket Type</option>
                                <option value="reguler" {{ old('ticket_type') == 'reguler' ? 'selected' : '' }}>Regular</option>
                                <option value="vip" {{ old('ticket_type') == 'vip' ? 'selected' : '' }}>VIP</option>
                            </select>
                            @error('ticket_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                       id="price" name="price" value="{{ old('price') }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" id="is_promotion" name="is_promotion"
                                       value="1" {{ old('is_promotion') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_promotion">Enable Promotion</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="promotion_price" class="form-label">Promotion Price</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control @error('promotion_price') is-invalid @enderror"
                                       id="promotion_price" name="promotion_price" value="{{ old('promotion_price') }}">
                                @error('promotion_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="image" class="form-label">Concert Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="ticket_image" class="form-label">Ticket Image</label>
                            <input type="file" class="form-control @error('ticket_image') is-invalid @enderror"
                                   id="ticket_image" name="ticket_image" accept="image/*">
                            @error('ticket_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <a href="{{ route('admin.concerts.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Create Concert</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
