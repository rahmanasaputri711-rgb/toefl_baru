<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

/**
 * AudioService
 * Mengelola resolusi URL audio dari bank_soal dan materi.
 */
class AudioService
{
    /**
     * Resolve URL audio menjadi URL yang bisa diakses browser.
     * Mendukung: path relatif storage, URL absolut (http/https), path legacy.
     *
     * @param  string|null $audioPath  Path yang tersimpan di database
     * @return string|null             Full URL untuk browser
     */
    public static function resolveUrl(?string $audioPath): ?string
    {
        if (empty($audioPath)) return null;

        // Sudah URL absolut
        if (str_starts_with($audioPath, 'http://') || str_starts_with($audioPath, 'https://')) {
            return $audioPath;
        }

        // Path storage public (hasil store('audio','public'))
        if (Storage::disk('public')->exists($audioPath)) {
            return asset('storage/' . $audioPath);
        }

        // Fallback: coba asset langsung
        return asset('storage/' . $audioPath);
    }

    /**
     * Pastikan file audio ada di storage.
     */
    public static function exists(?string $audioPath): bool
    {
        if (empty($audioPath)) return false;
        if (str_starts_with($audioPath, 'http')) return true;
        return Storage::disk('public')->exists($audioPath);
    }

    /**
     * Hapus file audio dari storage.
     */
    public static function delete(?string $audioPath): void
    {
        if (empty($audioPath)) return;
        if (str_starts_with($audioPath, 'http')) return;
        Storage::disk('public')->delete($audioPath);
    }
}
