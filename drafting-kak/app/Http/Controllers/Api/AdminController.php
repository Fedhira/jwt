<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
{
    try {
        // Hitung total pengguna
        $totalUsers = \App\Models\User::count();
        // Hitung total draft berdasarkan status
        $totalKak = \App\Models\Draft::where('status', '!=', 'draft')->count(); // Perbaiki kondisi whereNot menjadi where('status', '!=', 'draft')
        $totalKategoriProgram = \App\Models\Kategori::count();
        $totalPending = \App\Models\Draft::where('status', 'pending')->count();
        $totalDisetujui = \App\Models\Draft::where('status', 'disetujui')->count();
        $totalDitolak = \App\Models\Draft::where('status', 'ditolak')->count();

        // Mengembalikan response JSON dengan semua data yang dihitung
        return response()->json([
            'message' => 'Succes Fetch Data Dashboard.',
            'data' => [
                'totalUsers' => $totalUsers,
                'totalKak' => $totalKak,
                'totalKategoriProgram' => $totalKategoriProgram,
                'totalPending' => $totalPending,
                'totalDisetujui' => $totalDisetujui,
                'totalDitolak' => $totalDitolak
            ],
        ], 200); // HTTP Status Code 200 (OK)
    } catch (\Exception $e) {
        // Menangani error jika terjadi exception
        return response()->json([
            'message' => 'Failed to fetch data.',
            'error' => $e->getMessage()
        ], 500); // HTTP Status Code 500 (Internal Server Error)
    }
}
}