<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index()   { return view('user.profil.index'); }

    public function update(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        $data = ['name' => $request->name];
        if ($request->filled('password'))
            $data['password'] = Hash::make($request->password);
        auth()->user()->update($data);
        return back()->with('success','Profil berhasil diperbarui.');
    }
}
