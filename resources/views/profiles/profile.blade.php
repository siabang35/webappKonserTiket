@extends('layouts.layout')

@section('title', 'Profil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Card Profil -->
            <div class="card shadow" style="border-radius: 1rem; overflow: hidden;">
                <!-- Header dengan Gradasi -->
                <div class="card-header" style="background: linear-gradient(135deg, rgba(65, 88, 208, 0.8), rgba(200, 80, 192, 0.8)); color: white; padding: 1.5rem;">
                    <h3 class="mb-0 text-center" style="font-weight: bold; text-transform: uppercase;">Profil Pengguna</h3>
                </div>
                <!-- Konten Card -->
                <div class="card-body" style="background: rgba(255, 255, 255, 0.9);">
                    <!-- Informasi Profil -->
                    <h5 class="text-muted" style="font-weight: bold; margin-bottom: 1rem;">Informasi Dasar</h5>
                    <table class="table table-borderless" style="background: rgba(255, 255, 255, 0.8);">
                        <tbody>
                            <tr>
                                <th scope="row" style="width: 40%; font-weight: bold;">ID</th>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <th scope="row" style="font-weight: bold;">Nama</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th scope="row" style="font-weight: bold;">Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th scope="row" style="font-weight: bold;">Tanggal Dibuat</th>
                                <td>{{ $user->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                            <tr>
                                <th scope="row" style="font-weight: bold;">Terakhir Diperbarui</th>
                                <td>{{ $user->updated_at->format('d M Y, H:i') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Tombol Aksi -->
                    <div class="mt-4 text-center">
                        <a href="{{ route('profile.edit') }}" class="btn btn-gradient me-2" style="background: linear-gradient(135deg, rgba(255, 105, 180, 0.8), rgba(65, 88, 208, 0.8)); color: white; padding: 0.75rem 1.5rem; border-radius: 0.5rem; font-weight: bold; text-transform: uppercase; transition: all 0.3s ease;">
                            <i class="bi bi-pencil"></i> Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
