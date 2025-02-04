@extends('layouts.layout')

@section('title', 'Edit Profil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Card Edit Profil -->
            <div class="card shadow" style="border-radius: 1rem; overflow: hidden;">
                <!-- Header dengan Gradasi -->
                <div class="card-header" style="background: linear-gradient(135deg, rgba(65, 88, 208, 0.8), rgba(200, 80, 192, 0.8)); color: white; padding: 1.5rem;">
                    <h3 class="mb-0 text-center" style="font-weight: bold; text-transform: uppercase;">Edit Profil</h3>
                </div>
                <!-- Konten Card -->
                <div class="card-body" style="background: rgba(255, 255, 255, 0.9);">
                    <!-- Formulir Edit -->
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        <!-- Nama -->
                        <div class="mb-4">
                            <label for="name" class="form-label" style="font-weight: bold;">Nama</label>
                            <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}" required style="border-radius: 0.5rem; border: 1px solid #ced4da; padding: 0.75rem; background-color: #f8f9fa;">
                        </div>
                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label" style="font-weight: bold;">Email</label>
                            <input type="email" id="email" name="email" class="form-control" value="{{ $user->email }}" required style="border-radius: 0.5rem; border: 1px solid #ced4da; padding: 0.75rem; background-color: #f8f9fa;">
                        </div>
                        <!-- Tombol Simpan -->
                        <div class="text-center">
                            <button type="submit" class="btn btn-gradient" style="background: linear-gradient(135deg, rgba(255, 105, 180, 0.8), rgba(65, 88, 208, 0.8)); color: white; padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-weight: bold; text-transform: uppercase; transition: all 0.3s ease;">
                                <i class="bi bi-check-circle"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
