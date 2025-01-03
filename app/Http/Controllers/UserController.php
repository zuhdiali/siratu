<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $totalUser = Pegawai::count();
        $pegwais = Pegawai::all();
        return view('user.index', ['pegawais' => $pegwais, 'totalUser' => $totalUser]);
    }

    public function create()
    {
        return view('user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:255',
            'username' => 'required|unique:pegawais',
            'role' => 'required',
            'password' => 'required',
            'confirm-password' => 'required|same:password',
        ]);
        $user = Pegawai::create(
            [
                'username' => $request->username,
                'nama' => $request->nama,
                'role' => $request->role,
                'password' => Hash::make($request->password),
            ]
        );

        if (!$user->wasRecentlyCreated) {
            return redirect()->route('user.create')->with('error', 'Gagal.');
        }

        return redirect()->route('user.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = Pegawai::find($id);
        return view('user.edit', ['user' => $user]);
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'nama' => 'required|max:255',
            'role' => 'required',
            'password' => 'nullable',
            'confirm-password' => 'same:password',
        ]);

        $user = Pegawai::find($id);

        if ($user->username != $request->username) {
            $request->validate([
                'username' => 'required|unique:pegawais',
            ]);
        } else {
            $request->validate([
                'username' => 'required',
            ]);
        }

        $user->nama = $request->nama;
        if ($id != 0) {
            $user->username = $request->username;
        }

        if ($request->password != null) {
            $user->password = Hash::make($request->password);
        }

        $user->role = $request->role;
        $user->save();
        return redirect()->route('user.index')->with('success', 'Pengguna berhasil diubah.');
    }

    public function destroy($id)
    {
        if (($id == 0) || ($id == 1)) {
            return redirect()->route('user.index')->with('error', 'Akun ini tidak bisa dihapus.');
        }

        $user = Pegawai::find($id);
        $user->delete();
        return redirect()->route('user.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
