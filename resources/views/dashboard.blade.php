@extends('layouts.app')

@section('title', 'Dashboard - Our Memories')

@section('content')

    <section class="hero">
        <img src="{{ asset('images/hero.jpg') }}" alt="Our Memories" class="hero-image">
    </section>

    <section class="hero-content">
        <h1 class="hero-title">Our Memories</h1>
        <p class="hero-subtitle">Setiap foto menyimpan cerita, setiap momen layak untuk dikenang selamanya.</p>
        <div class="hero-actions">
            <a href="{{ route('gallery.index') }}" class="btn btn-primary">
                <i class="fa-solid fa-images"></i> Lihat Galeri
            </a>
            <a href="{{ route('photos.create') }}" class="btn btn-outline">
                <i class="fa-solid fa-upload"></i> Upload Foto
            </a>
        </div>
    </section>

    <div class="stats">
        <div class="stat-card">
            <div class="stat-number">{{ $totalPhotos }}</div>
            <div class="stat-label">Total Foto</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">1</div>
            <div class="stat-label">Album</div>
        </div>
    </div>

    <section class="section">
        <div class="container">
            <div class="section-heading">
                <h2>Latest Memories</h2>
                <p>Momen-momen terbaru yang baru saja ditambahkan</p>
            </div>

            @if ($latestPhotos->count() > 0)
                <div class="latest-grid">
                    @foreach ($latestPhotos as $photo)
                        <div class="photo-card">
                            <img src="{{ asset('storage/' . $photo->path) }}" alt="{{ $photo->title }}">
                            <div class="photo-card-info">
                                <div class="photo-card-title">{{ $photo->title }}</div>
                                <div class="photo-card-date">{{ $photo->created_at->format('d M Y') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="section-cta">
                    <a href="{{ route('gallery.index') }}" class="btn btn-primary">
                        <i class="fa-solid fa-images"></i> View All Photos
                    </a>
                </div>
            @else
                <div class="empty-state">
                    <p>Belum ada foto. Yuk mulai upload kenangan pertamamu!</p>
                    <div class="section-cta">
                        <a href="{{ route('photos.create') }}" class="btn btn-primary">
                            <i class="fa-solid fa-upload"></i> Upload Foto Pertama
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>

@endsection