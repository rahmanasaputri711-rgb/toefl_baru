<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\SesiTes;

class SesiTesController extends Controller
{
    public function index()
    {
        return SesiTes::where('is_aktif', 1)->get();
    }
}