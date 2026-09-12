<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Our Memories')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('styles')
</head>
<body>

    <nav class="navbar">
        <div class="navbar-inner">
            <a href="{{ route('dashboard') }}" class="navbar-brand">
                <i class="fa-solid fa-heart"></i> Our Memories
            </a>
            <div class="navbar-links">
                <a href="{{ route('dashboard') }}"><span class="nav-text">Dashboard</span></a>
                <a href="{{ route('gallery.index') }}"><span class="nav-text">Gallery</span></a>
                <a href="{{ route('photos.create') }}" class="btn-nav-upload">
                    <i class="fa-solid fa-upload"></i> <span class="nav-text">Upload</span>
                </a>
            </div>
        </div>
    </nav>

    @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        &copy; {{ date('Y') }} Our Memories. Dibuat dengan <i class="fa-solid fa-heart" style="color:#b76e79;"></i>
    </footer>

    @yield('scripts')
</body>
</html>