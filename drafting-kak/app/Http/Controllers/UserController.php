<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log; // Import Log facade
use Illuminate\Support\Facades\Auth; // Import Auth facade
use TCPDF;
use Carbon\Carbon;
use App\Models\User; // Import User model

class UserController extends Controller
{
    // Metode untuk menampilkan halaman dashboard
    public function index()
    {
        $user = \Illuminate\Support\Facades\Auth::user()->id;
        // Hitung jumlah status berdasarkan user_id
        $totalPending = \App\Models\Draft::where('user_id', $user)->where('status', 'pending')->count();
        $totalDisetujui = \App\Models\Draft::where('user_id', $user)->where('status', 'disetujui')->count();
        $totalDitolak = \App\Models\Draft::where('user_id', $user)->where('status', 'ditolak')->count();
        $totalDaftar = \App\Models\Draft::where('user_id', $user)->whereNotIn('status', ['draft'])->count();
        $totalDraft = \App\Models\Draft::where('user_id', $user)->where('status', 'draft')->count();

        return view('user.index', compact('user', 'totalPending', 'totalDisetujui', 'totalDitolak', 'totalDaftar', 'totalDraft'));
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

        // Path ke template Word (pastikan template ada di storage/app/)
        $templatePath = storage_path('app/public/template.docx');
        if (!file_exists($templatePath)) {
            return redirect()->back()->with('error', 'Template dokumen tidak ditemukan.');
        }

        $supervisor = \App\Models\User::where('role', 'supervisor')
            ->whereHas('kategori', function ($query) {
                $query->where('nama_divisi', 'Direktur Utama');
            })->first();

        if (!$supervisor) {
            return redirect()->back()->with('error', 'Supervisor tidak ditemukan.');
        }


        // Load template Word
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        // Fungsi untuk membersihkan teks dari tag HTML dan menjaga bullet point serta baris baru
        function cleanText($text)
        {
            $text = html_entity_decode($text ?? 'N/A', ENT_QUOTES, 'UTF-8'); // Hilangkan encoding HTML (&nbsp;)
            $text = strip_tags($text, '<ul><ol><li><br>'); // Biarkan tag daftar tetap ada

            // Konversi <ul> dan <ol> menjadi list PhpWord
            $text = str_replace(['<ul>', '<ol>'], '', $text);
            $text = str_replace(['</ul>', '</ol>'], '', $text);

            // Konversi <li> ke format Word dengan bullet
            $text = preg_replace('/<li>(.*?)<\/li>/', "• $1\n", $text);

            // Konversi <br> ke baris baru di Word
            $text = str_replace('<br>', "\n", $text);

            return trim($text);
        }

        // Replace placeholders dengan data draft
        $templateProcessor->setValue('judul', cleanText($draft->judul));
        $templateProcessor->setValue('no_doc_mak', cleanText($draft->no_doc_mak));
        $templateProcessor->setValue('kategori_program', cleanText($draft->kategori->nama_divisi ?? 'Tidak Diketahui'));
        $templateProcessor->setValue('status', cleanText($draft->status));
        $templateProcessor->setValue('indikator', cleanText($draft->indikator));
        $templateProcessor->setValue('satuan_ukur', cleanText($draft->satuan_ukur));
        $templateProcessor->setValue('volume', cleanText($draft->volume));
        $templateProcessor->setValue('latar_belakang', cleanText($draft->latar_belakang));
        $templateProcessor->setValue('dasar_hukum', cleanText($draft->dasar_hukum));
        $templateProcessor->setValue('gambaran_umum', cleanText($draft->gambaran_umum));
        $templateProcessor->setValue('tujuan', cleanText($draft->tujuan));
        $templateProcessor->setValue('target_sasaran', cleanText($draft->target_sasaran));
        $templateProcessor->setValue('unit_kerja', cleanText($draft->unit_kerja));
        $templateProcessor->setValue('ruang_lingkup', cleanText($draft->ruang_lingkup));
        $templateProcessor->setValue('produk_jasa_dihasilkan', cleanText($draft->produk_jasa_dihasilkan));
        $templateProcessor->setValue('waktu_pelaksanaan', cleanText($draft->waktu_pelaksanaan));
        $templateProcessor->setValue('tenaga_ahli_terampil', cleanText($draft->tenaga_ahli_terampil));
        $templateProcessor->setValue('peralatan', cleanText($draft->peralatan));
        $templateProcessor->setValue('metode_kerja', cleanText($draft->metode_kerja));
        $templateProcessor->setValue('manajemen_resiko', cleanText($draft->manajemen_resiko));
        $templateProcessor->setValue('laporan_pengajuan_pekerjaan', cleanText($draft->laporan_pengajuan_pekerjaan));
        $templateProcessor->setValue('sumber_dana_prakiraan_biaya', cleanText($draft->sumber_dana_prakiraan_biaya));
        $templateProcessor->setValue('penutup', cleanText($draft->penutup));

        // Replace placeholders dengan data user pembuat draft
        $templateProcessor->setValue('nama_divisi', 'Direktur Utama');
        $templateProcessor->setValue('username', cleanText($supervisor->username ?? 'Tidak Diketahui'));
        $templateProcessor->setValue('nik', cleanText($supervisor->nik ?? 'Tidak Diketahui'));

        // Tambahkan tanggal dan tahun
        \Carbon\Carbon::setLocale('id');
        $updated_at = $draft->updated_at ? $draft->updated_at->translatedFormat('d F Y') : 'Tidak Diketahui';
        $tahun = $draft->updated_at ? $draft->updated_at->format('Y') : 'Tidak Diketahui';
        $templateProcessor->setValue('updated_at', cleanText($updated_at));
        $templateProcessor->setValue('tahun', cleanText($tahun));

        // **🔹 Menambahkan Lampiran (Gambar) ke Word**
        $lampiranPath = storage_path('app/public/' . $draft->lampiran);

        if ($draft->lampiran && file_exists($lampiranPath) && exif_imagetype($lampiranPath)) {
            $templateProcessor->setImageValue('lampiran', [
                'path' => $lampiranPath,
                'width' => 500,  // Sesuaikan ukuran gambar
                'height' => 300,
                'ratio' => true,  // Agar gambar tidak terdistorsi
            ]);
        } else {
            $templateProcessor->setValue('lampiran', 'Lampiran tidak tersedia');
        }

        // Simpan file Word sementara
        $fileName = 'Dokumen_KAK_' . preg_replace('/[^A-Za-z0-9\-]/', '_', $draft->judul) . '.docx';
        $tempFilePath = storage_path('app/' . $fileName);

        $templateProcessor->saveAs($tempFilePath);

        // Pastikan file benar-benar dibuat sebelum diunduh
        if (!file_exists($tempFilePath) || filesize($tempFilePath) < 1000) {
            return redirect()->back()->with('error', 'File Word gagal dibuat atau rusak.');
        }

        // Unduh file
        return response()->download($tempFilePath)->deleteFileAfterSend(true);
    }

    public function downloadPdf($id)
    {
        // Fetch the kaks data by ID, including related kategori data
        $kaks = \App\Models\Draft::with('kategori')->findOrFail($id);

        // **Ambil Supervisor** (karena hanya ada 1, ambil yang pertama)
        $supervisor = User::where('role', 'supervisor')->first();
        if (!$supervisor) {
            return redirect()->back()->with('error', 'Supervisor tidak ditemukan.');
        }

        // **Gunakan waktu saat ini dalam format Indonesia**
        Carbon::setLocale('id'); // Set lokal ke Indonesia
        $currentTime = Carbon::now()->translatedFormat('d F Y'); // Contoh: 15 Februari 2025

        // **Pastikan Supervisor memiliki divisi, jika kosong isi "Direktur Utama"**
        $nama_divisi = $supervisor->divisi ?? 'Direktur Utama';
        $username = $supervisor->username ?? 'Tidak Diketahui';
        $nik = $supervisor->nik ?? 'Tidak Diketahui';

        // Buat instance TCPDF
        $pdf = new \TCPDF(\PDF_PAGE_ORIENTATION, \PDF_UNIT, \PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set metadata PDF
        $pdf->SetCreator('BAKTI');
        $pdf->SetAuthor($user->username ?? 'Tidak Diketahui');
        $pdf->SetTitle($kaks->judul ?? 'Dokumen KAK');
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
    <div class="doc-title">' . strtoupper($kaks->judul) . '</div>
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
            line-height: 1.0;
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
            <td style="width: 70%;">: ' . ($kaks->judul) . '</td>
        </tr>
        <tr><td style="width: 30%;"><b>Indikator Kinerja Kegiatan</b></td>
            <td style="width: 70%;">: ' . ($kaks->indikator) . '</td>
        </tr>
        <tr><td style="width: 30%;"><b>Satuan Ukur / Jenis Keluaran</b></td>
            <td style="width: 70%;">: ' . ($kaks->satuan_ukur) . '</td>
        </tr>
        <tr><td style="width: 30%;"><b>Volume</b></td>
            <td style="width: 70%;">: ' . ($kaks->volume) . '</td>
        </tr>
    </table>
    <br><br>

    <div class="section" style="font-size:11pt;">A. LATAR BELAKANG</div>
    <p>' . nl2br($kaks->latar_belakang) . '</p>
    <div class="section" style="font-size:11pt;">1. DASAR HUKUM</div>
    <p>' . nl2br($kaks->dasar_hukum) . '</p>
    
    <div class="section" style="font-size:11pt;">2. GAMBARAN UMUM</div>
    <p>' . nl2br($kaks->gambaran_umum) . '</p>
    
    <div class="section">B. MAKSUD DAN TUJUAN</div>
    <p>Maksud dan tujuan kegiatan <b>' . ($kaks->judul ?? '${judul}') . '</b> adalah sebagai berikut:</p>
     <p>' . nl2br($kaks->tujuan) . '</p>

    <div class="section">C. TARGET/SASARAN</div>
    <p>Target/sasaran yang ingin dicapai dalam <b>' . ($kaks->judul ?? '${judul}') . '</b> adalah sebagai berikut:</p>
   <p>' . nl2br($kaks->target_sasaran) . '</p>

    <div class="section">D. UNIT KERJA</div>
    <p>' . nl2br($kaks->unit_kerja) . '</p>
    
    <div class="section">E. RUANG LINGKUP PEKERJAAN</div>
    <p>Ruang Lingkup dari pelaksanan <b>' . ($kaks->judul ?? '${judul}') . '</b> adalah sebagai berikut:</p>
    <p>' . nl2br($kaks->ruang_lingkup) . '</p>
    
    <div class="section">F. PRODUK/JASA DIHASILKAN</div>
    <p>' . nl2br($kaks->produk_jasa_dihasilkan) . '</p>
    
    <div class="section">G. WAKTU PELAKSANAAN</div>
    <p>' . nl2br($kaks->waktu_pelaksanaan) . '</p>
    
    <div class="section">H. TENAGA AHLI/TERAMPIL</div>
    <p>' . nl2br($kaks->tenaga_ahli_terampil) . '</p>
    
    <div class="section">I. PERALATAN</div>
    <p>' . nl2br($kaks->peralatan) . '</p>
    
    <div class="section">J. METODE KERJA</div>
    <p>' . nl2br($kaks->metode_kerja) . '</p>
    
    <div class="section">K. MANAJEMEN RISIKO</div>
    <p>Dalam pelaksanaan pekerjaan <b>' . ($kaks->judul ?? '${judul}') . '</b> diperlukan mitigasi risiko untuk meminimalisir kemungkinan terjadinya risiko. Risiko yang terjadi seperti:</p>
    <p>' . nl2br($kaks->manajemen_resiko) . '</p>
    
    <div class="section">L. LAPORAN PENGAJUAN PEKERJAAN</div>
    <p>' . nl2br($kaks->laporan_pengajuan_pekerjaan) . '</p>
    
    <div class="section">M. SUMBER DANA</div>
    <p>' . nl2br($kaks->sumber_dana_prakiraan_biaya) . '</p>
    
    <div class="section">N. PENUTUP</div>
    <p>' . nl2br($kaks->penutup) . '</p>

    <br><br><br><br><br>
    
    <div style="width: 100%; text-align: right;">
    <span>Jakarta, ' .  $currentTime  . '</span><br><br>
    <span><b>' . $nama_divisi .  '</b></span>
</div>
<br><br>
<div style="width: 100%; text-align: right;">
    <span style="text-decoration: underline; font-weight: bold;">'  . $username . '</span><br>
    <span>NIP. ' . $nik .  '</span>
</div>

<div class="section">O. LAMPIRAN</div>

<!-- Tambahkan judul dan nomor dokumen -->
<div style="text-align: center;">
    <span>' . ($kaks->judul ?? '') . '</span><br>
    <span>MAK. ' . ($kaks->no_doc_mak ?? '') . '</span>
</div>

<!-- Lampiran (gambar) -->
<br><br>
<div style="text-align: center;">
    ' . ($kaks->lampiran ? '<img src="' . storage_path('app/public/' . $kaks->lampiran) . '" width="200">' : '${lampiran}') . '
</div>

<br><br><br><br><br>

<!-- Bagian tanda tangan -->
<div style="width: 100%; text-align: right;">
    <span>Jakarta, ' .  $currentTime  . '</span><br><br>
    <span><b>' . $nama_divisi .  '</b></span>
</div>
<br><br>
<div style="width: 100%; text-align: right;">
    <span style="text-decoration: underline; font-weight: bold;">'  . $username . '</span><br>
    <span>NIP. ' . $nik .  '</span>
</div>';

        $pdf->writeHTML($contentHtml, true, false, true, false, '');

        // Unduh PDF
        $fileName = 'Dokumen KAK ' . $kaks->judul . '.pdf';
        $pdf->Output($fileName, 'D'); // 'D' untuk download langsung
    }


    public function previewPdf($id)
    {
        // Fetch the kaks data by ID, including related kategori data
        $kaks = \App\Models\Draft::with('kategori')->findOrFail($id);

        // **Ambil Supervisor** (karena hanya ada 1, ambil yang pertama)
        $supervisor = User::where('role', 'supervisor')->first();
        if (!$supervisor) {
            return redirect()->back()->with('error', 'Supervisor tidak ditemukan.');
        }

        // **Gunakan waktu saat ini dalam format Indonesia**
        Carbon::setLocale('id'); // Set lokal ke Indonesia
        $currentTime = Carbon::now()->translatedFormat('d F Y'); // Contoh: 15 Februari 2025

        // **Pastikan Supervisor memiliki divisi, jika kosong isi "Direktur Utama"**
        $nama_divisi = $supervisor->divisi ?? 'Direktur Utama';
        $username = $supervisor->username ?? 'Tidak Diketahui';
        $nik = $supervisor->nik ?? 'Tidak Diketahui';

        // Buat instance TCPDF
        $pdf = new \TCPDF(\PDF_PAGE_ORIENTATION, \PDF_UNIT, \PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set metadata PDF
        $pdf->SetCreator('BAKTI');
        $pdf->SetAuthor($user->username ?? 'Tidak Diketahui');
        $pdf->SetTitle($kaks->judul ?? 'Dokumen KAK');
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
    <div class="doc-title">' . strtoupper($kaks->judul) . '</div>
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
            line-height: 1.0;
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
            <td style="width: 70%;">: ' . ($kaks->judul) . '</td>
        </tr>
        <tr><td style="width: 30%;"><b>Indikator Kinerja Kegiatan</b></td>
            <td style="width: 70%;">: ' . ($kaks->indikator) . '</td>
        </tr>
        <tr><td style="width: 30%;"><b>Satuan Ukur / Jenis Keluaran</b></td>
            <td style="width: 70%;">: ' . ($kaks->satuan_ukur) . '</td>
        </tr>
        <tr><td style="width: 30%;"><b>Volume</b></td>
            <td style="width: 70%;">: ' . ($kaks->volume) . '</td>
        </tr>
    </table>
    <br><br>

    <div class="section" style="font-size:11pt;">A. LATAR BELAKANG</div>
    <p>' . nl2br($kaks->latar_belakang) . '</p>
    <div class="section" style="font-size:11pt;">1. DASAR HUKUM</div>
    <p>' . nl2br($kaks->dasar_hukum) . '</p>
    
    <div class="section" style="font-size:11pt;">2. GAMBARAN UMUM</div>
    <p>' . nl2br($kaks->gambaran_umum) . '</p>
    
    <div class="section">B. MAKSUD DAN TUJUAN</div>
    <p>Maksud dan tujuan kegiatan <b>' . ($kaks->judul ?? '${judul}') . '</b> adalah sebagai berikut:</p>
    <p>' . nl2br($kaks->tujuan) . '</p>

    <div class="section">C. TARGET/SASARAN</div>
    <p>Target/sasaran yang ingin dicapai dalam <b>' . ($kaks->judul ?? '${judul}') . '</b> adalah sebagai berikut:</p>
   <p>' . nl2br($kaks->target_sasaran) . '</p>

    <div class="section">D. UNIT KERJA</div>
    <p>' . nl2br($kaks->unit_kerja) . '</p>
    
    <div class="section">E. RUANG LINGKUP PEKERJAAN</div>
    <p>Ruang Lingkup dari pelaksanan <b>' . ($kaks->judul ?? '${judul}') . '</b> adalah sebagai berikut:</p>
    <p>' . nl2br($kaks->ruang_lingkup) . '</p>
    
    <div class="section">F. PRODUK/JASA DIHASILKAN</div>
    <p>' . nl2br($kaks->produk_jasa_dihasilkan) . '</p>
    
    <div class="section">G. WAKTU PELAKSANAAN</div>
    <p>' . nl2br($kaks->waktu_pelaksanaan) . '</p>
    
    <div class="section">H. TENAGA AHLI/TERAMPIL</div>
    <p>' . nl2br($kaks->tenaga_ahli_terampil) . '</p>
    
    <div class="section">I. PERALATAN</div>
    <p>' . nl2br($kaks->peralatan) . '</p>
    
    <div class="section">J. METODE KERJA</div>
    <p>' . nl2br($kaks->metode_kerja) . '</p>
    
    <div class="section">K. MANAJEMEN RISIKO</div>
    <p>Dalam pelaksanaan pekerjaan <b>' . ($kaks->judul ?? '${judul}') . '</b> diperlukan mitigasi risiko untuk meminimalisir kemungkinan terjadinya risiko. Risiko yang terjadi seperti:</p>
    <p>' . nl2br($kaks->manajemen_resiko) . '</p>
    
    <div class="section">L. LAPORAN PENGAJUAN PEKERJAAN</div>
    <p>' . nl2br($kaks->laporan_pengajuan_pekerjaan) . '</p>
    
    <div class="section">M. SUMBER DANA</div>
    <p>' . nl2br($kaks->sumber_dana_prakiraan_biaya) . '</p>
    
    <div class="section">N. PENUTUP</div>
    <p>' . nl2br($kaks->penutup) . '</p>

    <br><br><br><br><br>
    
    <div style="width: 100%; text-align: right;">
    <span>Jakarta, ' .  $currentTime  . '</span><br><br>
    <span><b>' . $nama_divisi .  '</b></span>
</div>
<br><br>
<div style="width: 100%; text-align: right;">
    <span style="text-decoration: underline; font-weight: bold;">'  . $username . '</span><br>
    <span>NIP. ' . $nik .  '</span>
</div>

<div class="section">O. LAMPIRAN</div>

<!-- Tambahkan judul dan nomor dokumen -->
<div style="text-align: center;">
    <span>' . ($kaks->judul ?? '') . '</span><br>
    <span>MAK. ' . ($kaks->no_doc_mak ?? '') . '</span>
</div>

<!-- Lampiran (gambar) -->
<br><br>
<div style="text-align: center;">
    ' . ($kaks->lampiran ? '<img src="' . storage_path('app/public/' . $kaks->lampiran) . '" width="350">' : '${lampiran}') . '
</div>

<br><br><br><br><br>

<!-- Bagian tanda tangan -->
<div style="width: 100%; text-align: right;">
    <span>Jakarta, ' .  $currentTime  . '</span><br><br>
    <span><b>' . $nama_divisi .  '</b></span>
</div>
<br><br>
<div style="width: 100%; text-align: right;">
    <span style="text-decoration: underline; font-weight: bold;">'  . $username . '</span><br>
    <span>NIP. ' . $nik .  '</span>
</div>';

        $pdf->writeHTML($contentHtml, true, false, true, false, '');

        // Tampilkan PDF di browser tanpa mendownload
        $pdf->Output('Preview_Dokumen_KAK.pdf', 'I'); // 'I' untuk inline preview
    }
}
