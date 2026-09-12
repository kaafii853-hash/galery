<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PhotoController extends Controller
{
    public function dashboard()
    {
        $totalPhotos = Photo::count();
        $latestPhotos = Photo::latest()->take(6)->get();

        return view('dashboard', [
            'totalPhotos'  => $totalPhotos,
            'latestPhotos' => $latestPhotos,
        ]);
    }

    /**
     * Kalau belum pilih album, tampilkan daftar album.
     * Kalau sudah pilih album, tampilkan foto-foto di dalamnya.
     */
    public function index(Request $request)
    {
        $selectedCategory = $request->query('category');

        if (!$selectedCategory) {
            $albums = Photo::query()
                ->select('category')
                ->selectRaw('COUNT(*) as total')
                ->selectRaw('MAX(id) as cover_id')
                ->groupBy('category')
                ->orderBy('category')
                ->get()
                ->map(function ($row) {
                    $cover = Photo::find($row->cover_id);
                    return (object) [
                        'name'  => $row->category,
                        'total' => $row->total,
                        'cover' => $cover,
                    ];
                });

            return view('gallery-albums', [
                'albums' => $albums,
            ]);
        }

        $photos = Photo::where('category', $selectedCategory)->latest()->get();

        return view('gallery', [
            'photos'           => $photos,
            'selectedCategory' => $selectedCategory,
        ]);
    }

    public function create()
    {
        return view('photos.create', [
            'existingCategories' => Photo::existingCategories(),
        ]);
    }

    /**
     * Menyimpan satu atau beberapa foto sekaligus,
     * lalu memberi pesan sukses yang menyesuaikan kategori.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'category'   => 'required|string|max:100',
            'photos'     => 'required|array|min:1',
            'photos.*'   => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ], [
            'photos.required' => 'Pilih minimal 1 foto untuk diupload.',
            'photos.*.image'  => 'Salah satu file bukan gambar yang valid.',
            'photos.*.mimes'  => 'Format foto harus JPG, JPEG, PNG, atau WEBP.',
            'photos.*.max'    => 'Ukuran setiap foto maksimal 5MB.',
        ]);

        $title    = $request->input('title');
        $category = trim($request->input('category'));

        foreach ($request->file('photos') as $file) {
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path     = $file->storeAs('photos', $filename, 'public');

            Photo::create([
                'title'    => $title,
                'category' => $category,
                'filename' => $filename,
                'path'     => $path,
            ]);
        }

        $count = count($request->file('photos'));

        return redirect()
            ->route('gallery.index')
            ->with('success', $this->cuteMessage($category, $count));
    }

    /**
     * Bikin pesan sukses yang beda-beda tergantung nama kategori.
     */
    private function cuteMessage(string $category, int $count): string
    {
        $lower = strtolower($category);
        $foto  = $count > 1 ? "{$count} foto" : 'Foto';

        if (str_contains($lower, 'random')) {
            return "🎲 {$foto} random baru masuk galeri~ makin seru isinya!";
        }

        if (str_contains($lower, 'bareng')) {
            return "🥰 {$foto} momen bareng berhasil disimpan, gemesin banget!";
        }

        if (str_contains($lower, 'ngedate') || str_contains($lower, 'date')) {
            return "💕 {$foto} kenangan ngedate tersimpan rapi, so sweet!";
        }

        if (str_contains($lower, 'keluarga')) {
            return "👨‍👩‍👧 {$foto} momen keluarga tersimpan hangat di galeri!";
        }

        return "✨ {$foto} berhasil masuk album \"{$category}\", makin penuh kenangannya!";
    }

    public function destroy(Photo $photo)
    {
        if (Storage::disk('public')->exists($photo->path)) {
            Storage::disk('public')->delete($photo->path);
        }

        $photo->delete();

        return redirect()
            ->route('gallery.index')
            ->with('success', 'Foto berhasil dihapus.');
    }
}