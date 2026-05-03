<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\GrafikProgress;

class ProfileController extends Controller
{
    public function grafik()
    {
        $userId = auth()->id() ?? 1; // fallback untuk testing

        return GrafikProgress::where('user_id', $userId)
            ->orderBy('tanggal', 'asc')
            ->get();
    }
}