<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Log; // Import Log facade
use Illuminate\Support\Facades\Auth; // Import Auth facade
use TCPDF;

class UserController extends Controller
{
    // Metode untuk menampilkan halaman dashboard
    public function index()
    {
        Log::info('🔍 Session pada user.index:', session()->all());

        if (!Auth::check()) {
            Log::error('❌ User tidak terautentikasi saat mengakses user.index');
            return redirect()->route('login')->with('status', 'error');
        }

        $user = Auth::user();
        Log::info('✅ User terautentikasi:', ['id' => $user->id, 'role' => $user->role]);

        if ($user->role !== 'staff') {
            Log::error('❌ User bukan staff:', ['id' => $user->id, 'role' => $user->role]);
            return redirect()->route('login')->with('status', 'error');
        }

        return view('user.index', compact('user'));
    }



    // Metode untuk menampilkan halaman daftar KAK
    public function daftar(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        // Ambil semua KAK termasuk data revisi
        $drafts = \App\Models\Draft::with('revisi', 'kategori')
            ->where('user_id', $user->id)
            ->where('status', '!=', 'draft');


        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        // Tambahkan filter tanggal jika tersedia
        if ($fromDate) {
            $drafts->whereDate('created_at', '>=', $fromDate);
        }

        if ($toDate) {
            $drafts->whereDate('created_at', '<=', $toDate);
        }

        // Eksekusi query
        $drafts = $drafts->get();

        return view('user.daftar', compact('drafts', 'fromDate', 'toDate'));
    }


    // Metode untuk menampilkan halaman draft KAK
    public function draft(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        // Ambil semua draft milik user yang berstatus 'draft'
        $drafts = \App\Models\Draft::where('user_id', $user->id)
            ->where('status', 'draft');

        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        // Tambahkan filter tanggal jika tersedia
        if ($fromDate) {
            $drafts->whereDate('created_at', '>=', $fromDate);
        }

        if ($toDate) {
            $drafts->whereDate('created_at', '<=', $toDate);
        }

        // Eksekusi query
        $drafts = $drafts->get();

        return view('user.draft', compact('drafts', 'fromDate', 'toDate'));
    }


    public function add_draft()
    {
        // Ambil kategori berdasarkan user yang login
        $user = \Illuminate\Support\Facades\Auth::user();
        $kategori_programs = \App\Models\Kategori::where('id', $user->kategori_id)->first();

        return view('user.add_draft', compact('kategori_programs'));
    }


    public function edit_draft($id)
    {
        $draft = \App\Models\Draft::with('kategori')->findOrFail($id);

        return view('user.edit_draft', compact('draft'));
    }


    public function upload_draft($id)
    {
        try {
            // Ambil draft berdasarkan ID
            $draft = \App\Models\Draft::with('kategori')->findOrFail($id);

            // Return ke view dengan data draft
            return view('user.upload_draft', compact('draft'));
        } catch (\Exception $e) {
            return redirect()->route('user.draft')->with('error', 'Draft tidak ditemukan.');
        }
    }

    // Metode untuk menampilkan halaman laporan
    public function laporan(Request $request)
    {
        $userId = \Illuminate\Support\Facades\Auth::user()->id;

        // Build query to get drafts with 'disetujui' status
        $drafts = \App\Models\Draft::where('status', 'disetujui')
            ->where('user_id', $userId) // Ensure only the user's drafts are fetched
            ->with('kategori'); // Include kategori relationship


        // Apply date filters if provided
        if ($request->input('fromDate')) {
            $drafts->whereDate('created_at', '>=', $request->input('fromDate'));
        }

        if ($request->input('toDate')) {
            $drafts->whereDate('created_at', '<=', $request->input('toDate'));
        }

        // Execute the drafts
        $drafts = $drafts->get();

        // Count totals for display
        $totalPending = \App\Models\Draft::where('user_id', $userId)->where('status', 'pending')->count();
        $totalDisetujui = \App\Models\Draft::where('user_id', $userId)->where('status', 'disetujui')->count();
        $totalDitolak = \App\Models\Draft::where('user_id', $userId)->where('status', 'ditolak')->count();

        return view('user.laporan', compact('drafts', 'totalPending', 'totalDisetujui', 'totalDitolak'));
    }


    public function faq()
    {
        return view('user.faq');  // Menampilkan halaman faq.blade.php
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_doc_mak' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_program,id', // Validasi bahwa kategori_id ada di tabel kategori_program
            'judul' => 'required|string|max:255',
            'indikator' => 'required|string',
            'satuan_ukur' => 'required|string|max:100',
            'volume' => 'required|numeric|min:1', // Volume harus angka positif
            'latar_belakang' => 'required|string',
            'dasar_hukum' => 'nullable|string',
            'gambaran_umum' => 'nullable|string',
            'tujuan' => 'nullable|string',
            'target_sasaran' => 'nullable|string',
            'unit_kerja' => 'nullable|string',
            'ruang_lingkup' => 'nullable|string',
            'produk_jasa_dihasilkan' => 'nullable|string',
            'waktu_pelaksanaan' => 'nullable|string',
            'tenaga_ahli_terampil' => 'nullable|string',
            'peralatan' => 'nullable|string',
            'metode_kerja' => 'nullable|string',
            'manajemen_resiko' => 'nullable|string',
            'laporan_pengajuan_pekerjaan' => 'nullable|string',
            'sumber_dana_prakiraan_biaya' => 'nullable|string',
            'penutup' => 'nullable|string',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        try {
            $draft = new \App\Models\Draft();
            $draft->fill($validated);
            $draft->user_id = \Illuminate\Support\Facades\Auth::user()->id; // Pastikan user_id diset ke user yang login
            $draft->status = 'draft'; // Default status

            // Simpan file lampiran jika ada
            if ($request->hasFile('lampiran')) {
                $fileName = time() . '_' . $request->file('lampiran')->getClientOriginalName();
                $filePath = $request->file('lampiran')->storeAs('lampiran', $fileName, 'public');
                $draft->lampiran = $filePath;
            }

            $draft->save();

            return redirect()->route('user.draft')->with('success', 'Draft berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error saat menyimpan draft: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan draft: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'no_doc_mak' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'indikator' => 'required|string',
            'satuan_ukur' => 'required|string|max:100',
            'volume' => 'required|numeric|min:1',
            'latar_belakang' => 'nullable|string',
            'dasar_hukum' => 'nullable|string',
            'gambaran_umum' => 'nullable|string',
            'tujuan' => 'nullable|string',
            'target_sasaran' => 'nullable|string',
            'unit_kerja' => 'nullable|string',
            'ruang_lingkup' => 'nullable|string',
            'produk_jasa_dihasilkan' => 'nullable|string',
            'waktu_pelaksanaan' => 'nullable|string',
            'tenaga_ahli_terampil' => 'nullable|string',
            'peralatan' => 'nullable|string',
            'metode_kerja' => 'nullable|string',
            'manajemen_resiko' => 'nullable|string',
            'laporan_pengajuan_pekerjaan' => 'nullable|string',
            'sumber_dana_prakiraan_biaya' => 'nullable|string',
            'penutup' => 'nullable|string',
            'lampiran' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $draft = \App\Models\Draft::findOrFail($id);

        // Periksa apakah data yang diubah sama persis dengan data asli
        $isUnchanged = true;
        foreach ($validated as $key => $value) {
            if ($draft->$key != $value) {
                $isUnchanged = false;
                break;
            }
        }

        if ($isUnchanged) {
            return redirect()
                ->route('user.edit_draft', $id)
                ->with('unchanged', 'Tidak ada perubahan data yang dilakukan.');
        }

        try {
            // Perbarui draft
            $draft->fill($validated);

            // Periksa dan simpan lampiran jika ada
            if ($request->hasFile('lampiran')) {
                $fileName = time() . '_' . $request->file('lampiran')->getClientOriginalName();
                $filePath = $request->file('lampiran')->storeAs('lampiran', $fileName, 'public');
                $draft->lampiran = $filePath;
            }

            $draft->save();

            return redirect()
                ->route('user.draft')
                ->with('success', 'Draft berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui draft: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        try {
            $draft = \App\Models\Draft::findOrFail($id);

            // Hapus file lampiran jika ada
            if ($draft->lampiran) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($draft->lampiran);
            }

            $draft->delete();

            return redirect()
                ->route('user.draft')
                ->with('success', 'Draft berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->route('user.draft')
                ->with('error', 'Gagal menghapus draft: ' . $e->getMessage());
        }
    }

    public function upload(Request $request)
    {
        $validated = $request->validate([
            'kak_id' => 'required|exists:kak,kak_id', // Validasi bahwa kak_id ada di tabel kak
        ]);

        try {
            // Ambil draft berdasarkan ID
            $draft = \App\Models\Draft::findOrFail($request->kak_id);

            // Ubah status menjadi 'pending'
            $draft->status = 'pending';
            $draft->save();

            return redirect()->route('user.daftar')->with('success', 'Draft berhasil diunggah.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengunggah draft: ' . $e->getMessage());
        }
    }

    public function rejectKak(Request $request)
    {
        $request->validate([
            'kak_id' => 'required|exists:kak,kak_id',
            'alasan_penolakan' => 'required|string',
            'saran' => 'required|string',
        ]);
    }

    public function downloadWord($id)
    {
        // Ambil data draft beserta relasi kategori dan user
        $draft = \App\Models\Draft::with(['kategori', 'user'])->findOrFail($id);

        // Data user pembuat draft
        $creator = $draft->user;

        // Path ke template Word
        $templatePath = storage_path('app/public/template.docx');
        if (!file_exists($templatePath)) {
            return redirect()->back()->with('error', 'Template dokumen tidak ditemukan.');
        }

        // Load template Word
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        // Replace placeholders dengan data draft
        $templateProcessor->setValue('judul', $draft->judul ?? '');
        $templateProcessor->setValue('no_doc_mak', $draft->no_doc_mak ?? '');
        $templateProcessor->setValue('kategori_program', $draft->kategori->nama_divisi ?? 'Tidak Diketahui');
        $templateProcessor->setValue('status', $draft->status ?? '');
        $templateProcessor->setValue('indikator', $draft->indikator ?? '');
        $templateProcessor->setValue('satuan_ukur', $draft->satuan_ukur ?? '');
        $templateProcessor->setValue('volume', $draft->volume ?? '');
        $templateProcessor->setValue('latar_belakang', $draft->latar_belakang ?? '');
        $templateProcessor->setValue('dasar_hukum', $draft->dasar_hukum ?? '');
        $templateProcessor->setValue('gambaran_umum', $draft->gambaran_umum ?? '');
        $templateProcessor->setValue('tujuan', $draft->tujuan ?? '');
        $templateProcessor->setValue('target_sasaran', $draft->target_sasaran ?? '');
        $templateProcessor->setValue('unit_kerja', $draft->unit_kerja ?? '');
        $templateProcessor->setValue('ruang_lingkup', $draft->ruang_lingkup ?? '');
        $templateProcessor->setValue('produk_jasa_dihasilkan', $draft->produk_jasa_dihasilkan ?? '');
        $templateProcessor->setValue('waktu_pelaksanaan', $draft->waktu_pelaksanaan ?? '');
        $templateProcessor->setValue('tenaga_ahli_terampil', $draft->tenaga_ahli_terampil ?? '');
        $templateProcessor->setValue('peralatan', $draft->peralatan ?? '');
        $templateProcessor->setValue('metode_kerja', $draft->metode_kerja ?? '');
        $templateProcessor->setValue('manajemen_resiko', $draft->manajemen_resiko ?? '');
        $templateProcessor->setValue('laporan_pengajuan_pekerjaan', $draft->laporan_pengajuan_pekerjaan ?? '');
        $templateProcessor->setValue('sumber_dana_prakiraan_biaya', $draft->sumber_dana_prakiraan_biaya ?? '');
        $templateProcessor->setValue('penutup', $draft->penutup ?? '');

        // Replace placeholders dengan data user pembuat draft
        $templateProcessor->setValue('nama_divisi', $draft->kategori->nama_divisi ?? 'Tidak Diketahui');
        $templateProcessor->setValue('username', $creator->username ?? 'Tidak Diketahui');
        $templateProcessor->setValue('nik', $creator->nik ?? 'Tidak Diketahui');

        // Tambahkan tanggal dan tahun
        \Carbon\Carbon::setLocale('id');
        $updated_at = $draft->updated_at->translatedFormat('d F Y') ?? 'Tidak Diketahui';
        $tahun = $draft->updated_at->format('Y') ?? 'Tidak Diketahui';
        $templateProcessor->setValue('updated_at', $updated_at);
        $templateProcessor->setValue('tahun', $tahun);

        // Tambahkan lampiran jika ada
        $lampiranPath = storage_path('app/public/' . $draft->lampiran);
        if ($draft->lampiran && file_exists($lampiranPath) && exif_imagetype($lampiranPath)) {
            $templateProcessor->setImageValue('lampiran', [
                'path' => $lampiranPath,
                'width' => 500,
                'height' => 300,
                'ratio' => true,
            ]);
        } else {
            $templateProcessor->setValue('lampiran', 'Lampiran tidak tersedia');
        }

        // Simpan file Word sementara
        $fileName = 'Dokumen KAK ' . $draft->judul . '.docx';
        $tempFilePath = storage_path('app/public/' . $fileName);
        $templateProcessor->saveAs($tempFilePath);

        // Unduh file
        return response()->download($tempFilePath)->deleteFileAfterSend(true);
    }


    public function downloadPdf($id)
    {
        // Fetch the draft data by ID, including related kategori data
        $draft = \App\Models\Draft::with('kategori')->findOrFail($id);

        // Data user pembuat draft
        $creator = $draft->user;

        // Set locale to Indonesian for Carbon
        \Carbon\Carbon::setLocale('id');

        // Format tanggal lengkap dan tahun
        $updated_at = $draft->updated_at->translatedFormat('d F Y') ?? 'Tidak Diketahui'; // Format: 21 Januari 2025

        // Buat instance TCPDF
        $pdf = new \TCPDF(\PDF_PAGE_ORIENTATION, \PDF_UNIT, \PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set metadata PDF
        $pdf->SetCreator('BAKTI');
        $pdf->SetAuthor($user->username ?? 'Tidak Diketahui');
        $pdf->SetTitle($draft->judul ?? 'Dokumen KAK');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(25, 25, 25);
        $pdf->SetAutoPageBreak(TRUE, 25);

        // Halaman Sampul
        $pdf->AddPage();
        $coverHtml = '
        <style>
        .title { font-size: 20pt; text-align: center; margin-bottom: 10px; color:rgb(0, 0, 0); }
        .english-title { font-size: 12pt; text-align: center; font-style: italic; margin-bottom: 20px; color: #666; }
        .doc-title { font-size: 14pt; text-align: center; margin-bottom: 30px; font-weight: bold; color: #333; }
        .org-name { font-size: 14pt; text-align: center; font-weight: bold; margin-top: 80px; line-height: 2; color:rgb(0, 0, 0); }
        .year { font-size: 12pt; text-align: center; margin-top: 20px; color: #666; }
        .separator { border-bottom: 2px solidrgb(0, 0, 0); margin: 10px auto; width: 50%; }
        
    </style>
    
    <div class="title">KERANGKA ACUAN KERJA</div>
    <div class="english-title">(Term of Reference)</div>
    <br><br><br><br>
    <div class="doc-title">' . strtoupper($draft->judul) . '</div>
    <br><br><br><br>
    <div style="height: 300px;"></div>
    <br><br><br><br>
    <br><br><br><br>
    <br><br><br><br>
    <br><br><br><br>
    <br><br><br><br>

    <div class="org-name">
        BADAN AKSESIBILITAS TELEKOMUNIKASI DAN INFORMASI<br>
        KEMENTERIAN KOMUNIKASI DAN INFORMATIKA<br>
        REPUBLIK INDONESIA
    </div>
    <div class="year">JAKARTA, ' . date('Y') . '</div>';

        $imagePath = public_path('assets/img/kaiadmin/logo_bakti.png'); // Lokasi logo
        if (file_exists($imagePath)) {
            $pdf->Image($imagePath, 65, 120, 80);
        }
        $pdf->writeHTML($coverHtml, true, false, true, false, '');

        // Halaman Konten
        $pdf->AddPage();
        $contentHtml = '

         <style>
        .main-header { 
            font-size: 14pt; 
            text-align: center; 
            font-weight: bold; 
            margin-bottom: 20px;
            color:rgb(0, 0, 0);
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .info-table { 
            width: 100%; 
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .info-table td { 
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .info-table td:first-child { 
            width: 80px;
            font-weight: bold;
            color:rgb(0, 0, 0);
        }
        .section { 
            font-size: 12pt;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            color:rgb(0, 0, 0);
            padding: 5px 0;
            border-bottom: 2px solid #eee;
        }
        p { 
            text-align: justify; 
            line-height: 1.6;
            margin-bottom: 10px;
            color: #333;
        }
    </style>

    <div class="main-header">KERANGKA ACUAN KERJA (KAK)</div>
    <br><br>
    <table class="info-table">
        <tr>
            <td style="width: 30%;"><b>Kementerian Negara</b></td>
            <td style="width: 70%;">: Kementerian Komunikasi dan Informatika</td>
        </tr>
        <tr>
            <td style="width: 30%;"><b>Unit Eselon I</b></td>
            <td style="width: 70%;">: Badan Aksesibilitas Telekomunikasi dan Informasi</td>
        </tr>
        <tr>
            <td style="width: 30%;"><b>Nama Kegiatan</b></td>
            <td style="width: 70%;">: ' . ($draft->judul) . '</td>
        </tr>
        <tr><td style="width: 30%;"><b>Indikator Kinerja Kegiatan</b></td>
            <td style="width: 70%;">: ' . ($draft->indikator) . '</td>
        </tr>
        <tr><td style="width: 30%;"><b>Satuan Ukur / Jenis Keluaran</b></td>
            <td style="width: 70%;">: ' . ($draft->satuan_ukur) . '</td>
        </tr>
        <tr><td style="width: 30%;"><b>Volume</b></td>
            <td style="width: 70%;">: ' . ($draft->volume) . '</td>
        </tr>
    </table>
    <br><br>

    <div class="section" style="font-size:11pt;">A. LATAR BELAKANG</div>
    <p>' . nl2br($draft->latar_belakang) . '</p>
    <div class="section" style="font-size:11pt;">1. DASAR HUKUM</div>
    <p>' . nl2br($draft->dasar_hukum) . '</p>
    
    <div class="section" style="font-size:11pt;">2. GAMBARAN UMUM</div>
    <p>' . nl2br($draft->gambaran_umum) . '</p>
    
    <div class="section">B. MAKSUD DAN TUJUAN</div>
    <p>' . nl2br($draft->tujuan) . '</p>

    <div class="section">C. TARGET/SASARAN</div>
    <p>' . nl2br($draft->target_sasaran) . '</p>

    <div class="section">D. UNIT KERJA</div>
    <p>' . nl2br($draft->unit_kerja) . '</p>
    
    <div class="section">E. RUANG LINGKUP PEKERJAAN</div>
    <p>' . nl2br($draft->ruang_lingkup) . '</p>
    
    <div class="section">F. PRODUK/JASA DIHASILKAN</div>
    <p>' . nl2br($draft->produk_jasa_dihasilkan) . '</p>
    
    <div class="section">G. WAKTU PELAKSANAAN</div>
    <p>' . nl2br($draft->waktu_pelaksanaan) . '</p>
    
    <div class="section">H. TENAGA AHLI/TERAMPIL</div>
    <p>' . nl2br($draft->tenaga_ahli_terampil) . '</p>
    
    <div class="section">I. PERALATAN</div>
    <p>' . nl2br($draft->peralatan) . '</p>
    
    <div class="section">J. METODE KERJA</div>
    <p>' . nl2br($draft->metode_kerja) . '</p>
    
    <div class="section">K. MANAJEMEN RISIKO</div>
    <p>' . nl2br($draft->manajemen_resiko) . '</p>
    
    <div class="section">L. LAPORAN PENGAJUAN PEKERJAAN</div>
    <p>' . nl2br($draft->laporan_pengajuan_pekerjaan) . '</p>
    
    <div class="section">M. SUMBER DANA</div>
    <p>' . nl2br($draft->sumber_dana_prakiraan_biaya) . '</p>
    
    <div class="section">N. PENUTUP</div>
    <p>' . nl2br($draft->penutup) . '</p>

    <br><br><br><br><br>
    
    <div style="width: 100%; text-align: right;">
    <span>Jakarta, ' . ($updated_at) . '</span><br><br>
    <span><b>' . ($draft->kategori->nama_divisi ?? 'Tidak Diketahui') . '</b></span>
</div>
<br><br>
<div style="width: 100%; text-align: right;">
    <span style="text-decoration: underline; font-weight: bold;">' . ($creator->username ?? 'Tidak Diketahui') . '</span><br>
    <span>NIP. ' . ($creator->nik ?? 'Tidak Diketahui') . '</span>
</div>';

        $pdf->writeHTML($contentHtml, true, false, true, false, '');

        // Unduh PDF
        $fileName = 'Dokumen KAK ' . $draft->judul . '.pdf';
        $pdf->Output($fileName, 'D'); // 'D' untuk download langsung
    }


    public function previewPdf($id)
    {
        // Fetch the kaks data by ID, including related kategori data
        $draft = \App\Models\Draft::with('kategori')->findOrFail($id);

        // Data user pembuat draft
        $creator = $draft->user;

        // Set locale to Indonesian for Carbon
        \Carbon\Carbon::setLocale('id');

        // Format tanggal lengkap dan tahun
        $updated_at = $draft->updated_at->translatedFormat('d F Y') ?? 'Tidak Diketahui'; // Format: 21 Januari 2025

        // Buat instance TCPDF
        $pdf = new \TCPDF(\PDF_PAGE_ORIENTATION, \PDF_UNIT, \PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set metadata PDF
        $pdf->SetCreator('BAKTI');
        $pdf->SetAuthor($user->username ?? 'Tidak Diketahui');
        $pdf->SetTitle($draft->judul ?? 'Dokumen KAK');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(25, 25, 25);
        $pdf->SetAutoPageBreak(TRUE, 25);

        // Halaman Sampul
        $pdf->AddPage();
        $coverHtml = '
        <style>
        .title { font-size: 20pt; text-align: center; margin-bottom: 10px; color:rgb(0, 0, 0); }
        .english-title { font-size: 12pt; text-align: center; font-style: italic; margin-bottom: 20px; color: #666; }
        .doc-title { font-size: 14pt; text-align: center; margin-bottom: 30px; font-weight: bold; color: #333; }
        .org-name { font-size: 14pt; text-align: center; font-weight: bold; margin-top: 80px; line-height: 2; color:rgb(0, 0, 0); }
        .year { font-size: 12pt; text-align: center; margin-top: 20px; color: #666; }
        .separator { border-bottom: 2px solidrgb(0, 0, 0); margin: 10px auto; width: 50%; }
        
    </style>
    
    <div class="title">KERANGKA ACUAN KERJA</div>
    <div class="english-title">(Term of Reference)</div>
    <br><br><br><br>
    <div class="doc-title">' . strtoupper($draft->judul) . '</div>
    <br><br><br><br>
    <div style="height: 300px;"></div>
    <br><br><br><br>
    <br><br><br><br>
    <br><br><br><br>
    <br><br><br><br>
    <br><br><br><br>

    <div class="org-name">
        BADAN AKSESIBILITAS TELEKOMUNIKASI DAN INFORMASI<br>
        KEMENTERIAN KOMUNIKASI DAN INFORMATIKA<br>
        REPUBLIK INDONESIA
    </div>
    <div class="year">JAKARTA, ' . date('Y') . '</div>';

        $imagePath = public_path('assets/img/kaiadmin/logo_bakti.png'); // Lokasi logo
        if (file_exists($imagePath)) {
            $pdf->Image($imagePath, 65, 120, 80);
        }
        $pdf->writeHTML($coverHtml, true, false, true, false, '');

        // Halaman Konten
        $pdf->AddPage();
        $contentHtml = '

         <style>
        .main-header { 
            font-size: 14pt; 
            text-align: center; 
            font-weight: bold; 
            margin-bottom: 20px;
            color:rgb(0, 0, 0);
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .info-table { 
            width: 100%; 
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .info-table td { 
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .info-table td:first-child { 
            width: 80px;
            font-weight: bold;
            color:rgb(0, 0, 0);
        }
        .section { 
            font-size: 12pt;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            color:rgb(0, 0, 0);
            padding: 5px 0;
            border-bottom: 2px solid #eee;
        }
        p { 
            text-align: justify; 
            line-height: 1.6;
            margin-bottom: 10px;
            color: #333;
        }
    </style>

    <div class="main-header">KERANGKA ACUAN KERJA (KAK)</div>
    <br><br>
    <table class="info-table">
        <tr>
            <td style="width: 30%;"><b>Kementerian Negara</b></td>
            <td style="width: 70%;">: Kementerian Komunikasi dan Informatika</td>
        </tr>
        <tr>
            <td style="width: 30%;"><b>Unit Eselon I</b></td>
            <td style="width: 70%;">: Badan Aksesibilitas Telekomunikasi dan Informasi</td>
        </tr>
        <tr>
            <td style="width: 30%;"><b>Nama Kegiatan</b></td>
            <td style="width: 70%;">: ' . ($draft->judul) . '</td>
        </tr>
        <tr><td style="width: 30%;"><b>Indikator Kinerja Kegiatan</b></td>
            <td style="width: 70%;">: ' . ($draft->indikator) . '</td>
        </tr>
        <tr><td style="width: 30%;"><b>Satuan Ukur / Jenis Keluaran</b></td>
            <td style="width: 70%;">: ' . ($draft->satuan_ukur) . '</td>
        </tr>
        <tr><td style="width: 30%;"><b>Volume</b></td>
            <td style="width: 70%;">: ' . ($draft->volume) . '</td>
        </tr>
    </table>
    <br><br>

    <div class="section" style="font-size:11pt;">A. LATAR BELAKANG</div>
    <p>' . nl2br($draft->latar_belakang) . '</p>
    <div class="section" style="font-size:11pt;">1. DASAR HUKUM</div>
    <p>' . nl2br($draft->dasar_hukum) . '</p>
    
    <div class="section" style="font-size:11pt;">2. GAMBARAN UMUM</div>
    <p>' . nl2br($draft->gambaran_umum) . '</p>
    
    <div class="section">B. MAKSUD DAN TUJUAN</div>
    <p>' . nl2br($draft->tujuan) . '</p>

    <div class="section">C. TARGET/SASARAN</div>
    <p>' . nl2br($draft->target_sasaran) . '</p>

    <div class="section">D. UNIT KERJA</div>
    <p>' . nl2br($draft->unit_kerja) . '</p>
    
    <div class="section">E. RUANG LINGKUP PEKERJAAN</div>
    <p>' . nl2br($draft->ruang_lingkup) . '</p>
    
    <div class="section">F. PRODUK/JASA DIHASILKAN</div>
    <p>' . nl2br($draft->produk_jasa_dihasilkan) . '</p>
    
    <div class="section">G. WAKTU PELAKSANAAN</div>
    <p>' . nl2br($draft->waktu_pelaksanaan) . '</p>
    
    <div class="section">H. TENAGA AHLI/TERAMPIL</div>
    <p>' . nl2br($draft->tenaga_ahli_terampil) . '</p>
    
    <div class="section">I. PERALATAN</div>
    <p>' . nl2br($draft->peralatan) . '</p>
    
    <div class="section">J. METODE KERJA</div>
    <p>' . nl2br($draft->metode_kerja) . '</p>
    
    <div class="section">K. MANAJEMEN RISIKO</div>
    <p>' . nl2br($draft->manajemen_resiko) . '</p>
    
    <div class="section">L. LAPORAN PENGAJUAN PEKERJAAN</div>
    <p>' . nl2br($draft->laporan_pengajuan_pekerjaan) . '</p>
    
    <div class="section">M. SUMBER DANA</div>
    <p>' . nl2br($draft->sumber_dana_prakiraan_biaya) . '</p>
    
    <div class="section">N. PENUTUP</div>
    <p>' . nl2br($draft->penutup) . '</p>

    <br><br><br><br><br>
    
    <div style="width: 100%; text-align: right;">
    <span>Jakarta, ' . ($updated_at) . '</span><br><br>
    <span><b>' . ($draft->kategori->nama_divisi ?? 'Tidak Diketahui') . '</b></span>
</div>
<br><br>
<div style="width: 100%; text-align: right;">
    <span style="text-decoration: underline; font-weight: bold;">' . ($creator->username ?? 'Tidak Diketahui') . '</span><br>
    <span>NIP. ' . ($creator->nik ?? 'Tidak Diketahui') . '</span>
</div>';

        $pdf->writeHTML($contentHtml, true, false, true, false, '');

        // Tampilkan PDF di browser tanpa mendownload
        $pdf->Output('Preview_Dokumen_KAK.pdf', 'I'); // 'I' untuk inline preview
    }
}
