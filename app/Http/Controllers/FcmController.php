<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FcmController extends Controller
{
    /** Simpan FCM token dari browser */
    public function saveToken(Request $request)
    {
        $request->validate(['token' => 'required|string|min:10']);
        auth()->user()->update(['fcm_token' => $request->token]);
        return response()->json(['ok' => true]);
    }
}
