@extends('layouts.app')

@section('title', 'Gallery - Our Memories')

@section('content')

    <div class="gallery-header">
        <a href="{{ route('gallery.index') }}" class="back-to-albums">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Album
        </a>
        <h1>{{ $selectedCategory }}</h1>
        <p>{{ $photos->count() }} foto dalam album ini</p>
        <div class="gallery-header-actions">
            <a href="{{ route('photos.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-upload"></i> Upload Foto
            </a>
        </div>
    </div>

    @if ($photos->count() > 0)
        <div class="gallery-grid">
            @foreach ($photos as $photo)
                <div class="gallery-item"
                     style="border-top: 4px solid {{ \App\Models\Photo::categoryColor($photo->category) }};"
                     onclick="openLightbox({{ $loop->index }})">

                    <img src="{{ asset('storage/' . $photo->path) }}" alt="{{ $photo->title }}">

                    <div class="gallery-item-category" style="background: {{ \App\Models\Photo::categoryColor($photo->category) }}; color: #fff;">
                        {{ $photo->category }}
                    </div>

                    <div class="gallery-item-overlay">
                        <div class="gallery-item-title">{{ $photo->title }}</div>
                        <div class="gallery-item-date">{{ $photo->created_at->format('d M Y') }}</div>
                    </div>

                    <form action="{{ route('photos.destroy', $photo->id) }}" method="POST"
                          onclick="event.stopPropagation()"
                          onsubmit="return confirm('Yakin ingin menghapus foto ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="gallery-item-delete" title="Hapus foto">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>

                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <p>Belum ada foto di album ini. Yuk upload kenangan pertamamu!</p>
            <div class="section-cta">
                <a href="{{ route('photos.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-upload"></i> Upload Foto
                </a>
            </div>
        </div>
    @endif

    <!-- Lightbox Modal -->
    <div class="lightbox" id="lightbox" onclick="closeLightbox(event)">
        <button class="lightbox-close" onclick="closeLightbox(event)">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <button class="lightbox-nav lightbox-prev" onclick="prevPhoto(event)">
            <i class="fa-solid fa-chevron-left"></i>
        </button>

        <div class="lightbox-content" onclick="event.stopPropagation()">
            <img id="lightbox-img" src="" alt="Preview foto" style="border: 4px solid var(--lightbox-frame-color, #b76e79);">
            <div class="lightbox-caption">
                <div class="lightbox-caption-title" id="lightbox-title"></div>
                <div class="lightbox-caption-date" id="lightbox-date"></div>
            </div>
        </div>

        <button class="lightbox-nav lightbox-next" onclick="nextPhoto(event)">
            <i class="fa-solid fa-chevron-right"></i>
        </button>
    </div>

@endsection

@php
    $galleryPhotosData = $photos->map(function ($p) {
        return [
            'url'   => asset('storage/' . $p->path),
            'title' => $p->title,
            'date'  => $p->created_at->format('d M Y'),
            'color' => \App\Models\Photo::categoryColor($p->category),
        ];
    });
@endphp

@section('scripts')
<script>
    const galleryPhotos = {!! json_encode($galleryPhotosData) !!};

    let currentIndex = 0;

    function openLightbox(index) {
        currentIndex = index;
        showLightboxPhoto();
        document.getElementById('lightbox').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function showLightboxPhoto() {
        const photo = galleryPhotos[currentIndex];
        document.getElementById('lightbox-img').src = photo.url;
        document.getElementById('lightbox-img').style.borderColor = photo.color;
        document.getElementById('lightbox-title').textContent = photo.title;
        document.getElementById('lightbox-date').textContent = photo.date;
    }

    function nextPhoto(event) {
        event.stopPropagation();
        currentIndex = (currentIndex + 1) % galleryPhotos.length;
        showLightboxPhoto();
    }

    function prevPhoto(event) {
        event.stopPropagation();
        currentIndex = (currentIndex - 1 + galleryPhotos.length) % galleryPhotos.length;
        showLightboxPhoto();
    }

    function closeLightbox(event) {
        document.getElementById('lightbox').classList.remove('active');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function (e) {
        if (!document.getElementById('lightbox').classList.contains('active')) return;
        if (e.key === 'Escape') closeLightbox(e);
        if (e.key === 'ArrowRight') nextPhoto(e);
        if (e.key === 'ArrowLeft') prevPhoto(e);
    });
</script>
@endsection