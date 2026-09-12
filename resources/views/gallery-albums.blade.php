@extends('layouts.app')

@section('title', 'Gallery - Our Memories')

@section('content')

    <div class="gallery-header">
        <h1>Gallery</h1>
        <p>Pilih album untuk melihat foto-foto di dalamnya</p>
        <div class="gallery-header-actions">
            <a href="{{ route('photos.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-upload"></i> Upload Foto
            </a>
        </div>
    </div>

    @if ($albums->count() > 0)
        <div class="album-grid">
            @foreach ($albums as $album)
                <a href="{{ route('gallery.index', ['category' => $album->name]) }}"
                   class="album-card"
                   style="border-top: 5px solid {{ \App\Models\Photo::categoryColor($album->name) }};">
                    <img src="{{ asset('storage/' . $album->cover->path) }}" alt="{{ $album->name }}">
                    <div class="album-card-overlay">
                        <div class="album-card-name">{{ $album->name }}</div>
                        <div class="album-card-count">{{ $album->total }} foto</div>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <p>Belum ada album. Yuk upload kenangan pertamamu!</p>
            <div class="section-cta">
                <a href="{{ route('photos.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-upload"></i> Upload Foto
                </a>
            </div>
        </div>
    @endif

@endsection