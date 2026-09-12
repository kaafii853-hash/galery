@extends('layouts.app')

@section('title', 'Upload Foto - Our Memories')

@section('content')

    <div class="upload-page">
        <div class="upload-card">
            <div class="upload-header">
                <h1>Upload Foto Baru</h1>
                <p>Simpan momen berharga ke dalam galeri kenangan kamu</p>
            </div>

            @if ($errors->any())
                <div class="upload-errors">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('photos.store') }}" method="POST" enctype="multipart/form-data" class="upload-form">
                @csrf

                <div class="form-group">
                    <label for="title">Judul</label>
                    <input type="text" name="title" id="title" placeholder="Contoh: Liburan di Bali"
                           value="{{ old('title') }}" required>
                </div>

                <div class="form-group">
                    <label for="category">Kategori / Album</label>
                    <input type="text" name="category" id="category" list="category-suggestions"
                           placeholder="Contoh: Ngedate, Liburan, Random, Keluarga..."
                           value="{{ old('category') }}" required>
                    <datalist id="category-suggestions">
                        @foreach ($existingCategories as $cat)
                            <option value="{{ $cat }}">
                        @endforeach
                    </datalist>
                    <small class="form-hint">
                        Ketik nama kategori baru untuk membuat album baru, atau pilih dari yang sudah ada.
                    </small>
                </div>

                <div class="form-group">
                    <label for="photos">Pilih Foto (bisa lebih dari satu)</label>

                    <label for="photos" class="file-drop" id="file-drop-label">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <span id="file-drop-text">Klik untuk pilih satu atau banyak foto sekaligus</span>
                        <small>Format: JPG, JPEG, PNG, WEBP &middot; Maks 5MB per foto</small>
                    </label>
                    <input type="file" name="photos[]" id="photos" accept=".jpg,.jpeg,.png,.webp"
                           multiple required onchange="previewImages(event)">

                    <div id="image-preview-grid" class="image-preview-grid"></div>
                </div>

                <button type="submit" class="btn btn-primary btn-upload-submit">
                    <i class="fa-solid fa-upload"></i> Upload Foto
                </button>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    function previewImages(event) {
        const files = event.target.files;
        const grid = document.getElementById('image-preview-grid');
        const dropText = document.getElementById('file-drop-text');

        grid.innerHTML = '';

        if (files.length > 0) {
            dropText.textContent = files.length > 1
                ? files.length + ' foto dipilih'
                : files[0].name;

            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    grid.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }
    }
</script>
@endsection