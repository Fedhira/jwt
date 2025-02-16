<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    // Menampilkan daftar semua pengguna
    public function index()
    {
        try {
            $users = User::all();
            return response()->json([
                'message' => 'Success Fetch Users',
                'data' => $users
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch users',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Menampilkan detail pengguna berdasarkan ID
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json([
                'message' => 'Success Fetch User',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch user',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    // Membuat pengguna baru
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,staff,supervisor',
            'nik' => 'required|string|unique:users,nik',
            'kategori' => 'required|exists:kategori_program,id',
        ]);

        try {
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role,
                'nik' => $request->nik,
                'kategori_id' => $request->kategori,
            ]);

            return response()->json([
                'message' => 'User successfully created',
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Mengupdate data pengguna
    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,staff,supervisor',
            'nik' => 'required|string|unique:users,nik,' . $id,
            'kategori' => 'required|exists:kategori_program,id',
        ]);

        try {
            $user = User::findOrFail($id);

            // Update user data
            $user->update([
                'username' => $request->username,
                'email' => $request->email,
                'password' => $request->password ? bcrypt($request->password) : $user->password,
                'role' => $request->role,
                'nik' => $request->nik,
                'kategori_id' => $request->kategori,
            ]);

            return response()->json([
                'message' => 'User successfully updated',
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Menghapus pengguna
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'message' => 'User successfully deleted'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete user',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}