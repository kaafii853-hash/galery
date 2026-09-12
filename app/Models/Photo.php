<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'filename',
        'path',
    ];

    /**
     * Mengambil semua nama kategori yang sudah pernah dipakai.
     */
    public static function existingCategories(): array
    {
        return static::query()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Warna bingkai/aksen khusus untuk tiap kategori.
     * Kategori yang sama akan selalu dapat warna yang sama.
     */
    public static function categoryColor(string $category): string
    {
        $palette = [
            '#b76e79', // pink dusty (default tema)
            '#9c7fb8', // ungu lembut
            '#6fa8dc', // biru langit
            '#e0a458', // oranye madu
            '#5f9ea0', // teal
            '#d97e8a', // pink cerah
            '#7c9473', // hijau sage
            '#8c6b5e', // coklat kayu
        ];

        $index = crc32(strtolower(trim($category))) % count($palette);

        return $palette[$index];
    }

    /**
     * Mengembalikan URL publik foto untuk ditampilkan di halaman.
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }
}