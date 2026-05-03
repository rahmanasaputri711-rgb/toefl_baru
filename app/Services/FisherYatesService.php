<?php

namespace App\Services;

/**
 * Fisher-Yates Shuffle Service
 * Digunakan HANYA untuk Tes Full.
 * Tes Mini dan Simulasi tidak menggunakan pengacakan.
 */
class FisherYatesService
{
    /**
     * Acak array menggunakan algoritma Fisher-Yates (Knuth Shuffle).
     * Kompleksitas O(n), tidak bias.
     *
     * @param  array $items
     * @return array
     */
    public static function shuffle(array $items): array
    {
        $n = count($items);
        for ($i = $n - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            [$items[$i], $items[$j]] = [$items[$j], $items[$i]];
        }
        return $items;
    }

    /**
     * Acak collection IDs soal per bagian.
     * Listening: opsional diacak (menjaga urutan audio).
     * Structure & Reading: wajib diacak.
     *
     * @param  array  $soalIds   Array of soal IDs
     * @param  string $bagian    'listening' | 'structure' | 'reading'
     * @param  bool   $shuffleListening  default false (listening tidak diacak untuk menjaga alur audio)
     * @return array
     */
    public static function shuffleSoal(array $soalIds, string $bagian, bool $shuffleListening = false): array
    {
        if ($bagian === 'listening' && !$shuffleListening) {
            // Listening: urutan tetap untuk menjaga alur audio
            return $soalIds;
        }

        // Structure & Reading: wajib diacak
        return self::shuffle($soalIds);
    }
}
