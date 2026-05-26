@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="bi bi-book"></i>
        Daftar Buku
    </h1>
    <a href="{{ route('buku.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Buku
    </a>
</div>

{{-- Statistik Cards --}}
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Buku</h6>
                        <h2 class="mb-0">{{ $totalBuku }}</h2>
                    </div>
                    <div class="text-primary">
                        <i class="bi bi-book-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Buku Tersedia</h6>
                        <h2 class="mb-0">{{ $bukuTersedia }}</h2>
                    </div>
                    <div class="text-success">
                        <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card border-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Buku Habis</h6>
                        <h2 class="mb-0">{{ $bukuHabis }}</h2>
                    </div>
                    <div class="text-danger">
                        <i class="bi bi-x-circle-fill" style="font-size: 3rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Filter Kategori --}}
<div class="card mb-4">
    <div class="card-body">
        <h6 class="card-title">
            <i class="bi bi-funnel"></i> Filter Kategori:
        </h6>
        <div class="btn-group" role="group">
            <a href="{{ route('buku.index') }}" class="btn btn-sm {{ !isset($kategori) ? 'btn-primary' : 'btn-outline-primary' }}">
                Semua
            </a>
            <a href="{{ route('buku.kategori', 'Programming') }}" class="btn btn-sm {{ isset($kategori) && $kategori == 'Programming' ? 'btn-primary' : 'btn-outline-primary' }}">
                Programming
            </a>
            <a href="{{ route('buku.kategori', 'Database') }}" class="btn btn-sm {{ isset($kategori) && $kategori == 'Database' ? 'btn-primary' : 'btn-outline-primary' }}">
                Database
            </a>
            <a href="{{ route('buku.kategori', 'Web Design') }}" class="btn btn-sm {{ isset($kategori) && $kategori == 'Web Design' ? 'btn-primary' : 'btn-outline-primary' }}">
                Web Design
            </a>
            <a href="{{ route('buku.kategori', 'Networking') }}" class="btn btn-sm {{ isset($kategori) && $kategori == 'Networking' ? 'btn-primary' : 'btn-outline-primary' }}">
                Networking
            </a>
            <a href="{{ route('buku.kategori', 'Data Science') }}" class="btn btn-sm {{ isset($kategori) && $kategori == 'Data Science' ? 'btn-primary' : 'btn-outline-primary' }}">
                Data Science
            </a>
        </div>
    </div>
</div>

{{-- Daftar Buku --}}
@forelse ($bukus as $buku)
<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-2 text-center">
                <i class="bi bi-book text-primary" style="font-size: 4rem;"></i>
                <div class="mt-2">
                    <span class="badge bg-{{ $buku->kategori == 'Programming' ? 'primary' : ($buku->kategori == 'Database' ? 'success' : ($buku->kategori == 'Web Design' ? 'info' : ($buku->kategori == 'Networking' ? 'warning' : 'danger'))) }}">
                        {{ $buku->kategori }}
                    </span>
                </div>
            </div>

            <div class="col-md-7">
                <h5 class="card-title">
                    <a href="{{ route('buku.show', $buku->id) }}" class="text-decoration-none">
                        {{ $buku->judul }}
                    </a>
                </h5>

                <p class="card-text text-muted mb-2">
                    <i class="bi bi-person"></i> {{ $buku->pengarang }} |
                    <i class="bi bi-building"></i> {{ $buku->penerbit }} |
                    <i class="bi bi-calendar"></i> {{ $buku->tahun_terbit }}
                </p>

                @if ($buku->isbn)
                <p class="card-text small text-muted mb-1">
                    <i class="bi bi-upc"></i> ISBN: {{ $buku->isbn }}
                </p>
                @endif

                @if ($buku->deskripsi)
                <p class="card-text">
                    {{ Str::limit($buku->deskripsi, 150) }}
                </p>
                @endif
            </div>

            <div class="col-md-3 text-end">
                <h4 class="text-primary mb-2">
                    {{ $buku->harga_format }}
                </h4>

                <div class="mb-3">
                    @if ($buku->stok > 0)
                    <span class="badge bg-success">
                        <i class="bi bi-check-circle"></i> Tersedia
                    </span>
                    <div class="text-muted small mt-1">
                        Stok: {{ $buku->stok }} buku
                    </div>
                    @else
                    <span class="badge bg-danger">
                        <i class="bi bi-x-circle"></i> Habis
                    </span>
                    @endif
                </div>

                <div class="btn-group-vertical d-grid gap-2">
                    <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-sm btn-info text-white">
                        <i class="bi bi-eye"></i> Detail
                    </a>
                    <a href="{{ route('buku.edit', $buku->id) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@empty
<div class="alert alert-info">
    <i class="bi bi-info-circle"></i>
    Tidak ada data buku
    @isset($kategori)
    dengan kategori <strong>{{ $kategori }}</strong>
    @endisset
</div>
@endforelse

@if ($bukus->count() > 0)
<div class="text-center mt-4">
    <p class="text-muted">
        Menampilkan {{ $bukus->count() }} buku
        @isset($kategori)
        dari kategori <strong>{{ $kategori }}</strong>
        @endisset
    </p>
</div>
@endif
@endsection