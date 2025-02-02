<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Draft;
use Illuminate\Support\Facades\Log;

class SupervisorController extends Controller
{
    public function index()
    {
        // Hitung total pengguna
        $totalKak = \App\Models\Draft::whereNot('status', 'draft')->count();
        $totalPending = \App\Models\Draft::where('status', 'pending')->count();
        $totalDisetujui = \App\Models\Draft::where('status', 'disetujui')->count();
        $totalDitolak = \App\Models\Draft::where('status', 'ditolak')->count();

        return view('supervisor.index', compact('totalKak', 'totalPending', 'totalDisetujui', 'totalDitolak'));
    }

    public function daftar(Request $request)
    {
        // Ambil nilai dari form
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');

        // Query data berdasarkan status (tidak termasuk 'draft')
        $kaks = \App\Models\Draft::where('status', '!=', 'draft')->with('kategori');

        // Tambahkan filter tanggal jika tersedia
        if ($fromDate) {
            $kaks->whereDate('created_at', '>=', $fromDate);
        }

        if ($toDate) {
            $kaks->whereDate('created_at', '<=', $toDate);
        }

        // Eksekusi query
        $kaks = $kaks->get();

        // Kirim data ke view
        return view('supervisor.daftar', compact('kaks', 'fromDate', 'toDate'));
    }


    public function laporan(Request $request)
    {
        $kaks = \App\Models\Draft::where('status', 'disetujui')->with('kategori'); // Hanya data dengan status disetujui

        $totalPending = \App\Models\Draft::where('status', 'pending')->count();
        $totalDisetujui = \App\Models\Draft::where('status', 'disetujui')->count();
        $totalDitolak = \App\Models\Draft::where('status', 'ditolak')->count();

        // Ambil nilai dari form
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        // Tambahkan filter tanggal jika tersedia
        if ($fromDate) {
            $kaks->whereDate('created_at', '>=', $fromDate);
        }

        if ($toDate) {
            $kaks->whereDate('created_at', '<=', $toDate);
        }

        // Eksekusi query
        $kaks = $kaks->get();
        return view('supervisor.laporan', compact('kaks', 'fromDate', 'toDate', 'totalPending', 'totalDisetujui', 'totalDitolak'));
    }

    public function faq()
    {
        return view('supervisor.faq');  // Menampilkan halaman faq.blade.php
    }

    public function rejectKak(Request $request)
    {
        $request->validate([
            'kak_id' => 'required|exists:kak,kak_id',
            'alasan_penolakan' => 'required|string',
            'saran' => 'required|string',
        ]);

        try {
            // Update status KAK
            $kak = \App\Models\Draft::findOrFail($request->kak_id);
            $kak->update(['status' => 'ditolak']);

            // Simpan ke tabel revisi
            \App\Models\Revisi::create([
                'user_id' => \Illuminate\Support\Facades\Auth::user()->id, // Ambil user ID supervisor
                'kak_id' => $request->kak_id,
                'kategori_id' => $kak->kategori_id,
                'alasan_penolakan' => $request->alasan_penolakan,
                'saran' => $request->saran,
            ]);

            return redirect()->route('supervisor.daftar')->with('success', 'KAK berhasil ditolak dan revisi dicatat!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menolak KAK: ' . $e->getMessage());
        }
    }

    public function approve($id)
    {
        // Cari KAK berdasarkan ID
        $kak = \App\Models\Draft::findOrFail($id);

        // Pastikan statusnya "Pending"
        if ($kak->status === 'pending') {
            // Ubah status menjadi "Disetujui"
            $kak->status = 'disetujui';
            $kak->save();

            // Redirect dengan pesan sukses
            return redirect()->back()->with('success', 'KAK berhasil disetujui.');
        }

        // Redirect dengan pesan error jika status bukan "Pending"
        return redirect()->back()->with('error', 'Hanya KAK dengan status Pending yang dapat disetujui.');
    }

    public function downloadWord($id)
    {
        // Ambil data draft beserta relasi kategori dan user
        $kaks = \App\Models\Draft::with(['kategori', 'user'])->findOrFail($id);

        // Data user pembuat kaks
        $creator = $kaks->user;

        // Path ke template Word
        $templatePath = storage_path('app/public/template.docx');
        if (!file_exists($templatePath)) {
            return redirect()->back()->with('error', 'Template dokumen tidak ditemukan.');
        }

        // Load template Word
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

        // Replace placeholders dengan data kaks
        $templateProcessor->setValue('judul', $kaks->judul ?? '');
        $templateProcessor->setValue('no_doc_mak', $kaks->no_doc_mak ?? '');
        $templateProcessor->setValue('kategori_program', $kaks->kategori->nama_divisi ?? 'Tidak Diketahui');
        $templateProcessor->setValue('status', $kaks->status ?? '');
        $templateProcessor->setValue('indikator', $kaks->indikator ?? '');
        $templateProcessor->setValue('satuan_ukur', $kaks->satuan_ukur ?? '');
        $templateProcessor->setValue('volume', $kaks->volume ?? '');
        $templateProcessor->setValue('latar_belakang', $kaks->latar_belakang ?? '');
        $templateProcessor->setValue('dasar_hukum', $kaks->dasar_hukum ?? '');
        $templateProcessor->setValue('gambaran_umum', $kaks->gambaran_umum ?? '');
        $templateProcessor->setValue('tujuan', $kaks->tujuan ?? '');
        $templateProcessor->setValue('target_sasaran', $kaks->target_sasaran ?? '');
        $templateProcessor->setValue('unit_kerja', $kaks->unit_kerja ?? '');
        $templateProcessor->setValue('ruang_lingkup', $kaks->ruang_lingkup ?? '');
        $templateProcessor->setValue('produk_jasa_dihasilkan', $kaks->produk_jasa_dihasilkan ?? '');
        $templateProcessor->setValue('waktu_pelaksanaan', $kaks->waktu_pelaksanaan ?? '');
        $templateProcessor->setValue('tenaga_ahli_terampil', $kaks->tenaga_ahli_terampil ?? '');
        $templateProcessor->setValue('peralatan', $kaks->peralatan ?? '');
        $templateProcessor->setValue('metode_kerja', $kaks->metode_kerja ?? '');
        $templateProcessor->setValue('manajemen_resiko', $kaks->manajemen_resiko ?? '');
        $templateProcessor->setValue('laporan_pengajuan_pekerjaan', $kaks->laporan_pengajuan_pekerjaan ?? '');
        $templateProcessor->setValue('sumber_dana_prakiraan_biaya', $kaks->sumber_dana_prakiraan_biaya ?? '');
        $templateProcessor->setValue('penutup', $kaks->penutup ?? '');

        // Replace placeholders dengan data user pembuat kaks
        $templateProcessor->setValue('nama_divisi', $kaks->kategori->nama_divisi ?? 'Tidak Diketahui');
        $templateProcessor->setValue('username', $creator->username ?? 'Tidak Diketahui');
        $templateProcessor->setValue('nik', $creator->nik ?? 'Tidak Diketahui');

        // Tambahkan tanggal dan tahun
        \Carbon\Carbon::setLocale('id');
        $updated_at = $kaks->updated_at->translatedFormat('d F Y') ?? 'Tidak Diketahui';
        $tahun = $kaks->updated_at->format('Y') ?? 'Tidak Diketahui';
        $templateProcessor->setValue('updated_at', $updated_at);
        $templateProcessor->setValue('tahun', $tahun);

        // Tambahkan lampiran jika ada
        $lampiranPath = storage_path('app/public/' . $kaks->lampiran);
        if ($kaks->lampiran && file_exists($lampiranPath) && exif_imagetype($lampiranPath)) {
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
        $fileName = 'Dokumen KAK ' . $kaks->judul . '.docx';
        $tempFilePath = storage_path('app/public/' . $fileName);
        $templateProcessor->saveAs($tempFilePath);

        // Unduh file
        return response()->download($tempFilePath)->deleteFileAfterSend(true);
    }


    public function downloadPdf($id)
    {
        // Fetch the kaks data by ID, including related kategori data
        $kaks = \App\Models\Draft::with('kategori')->findOrFail($id);

        // Data user pembuat kaks
        $creator = $kaks->user;

        // Set locale to Indonesian for Carbon
        \Carbon\Carbon::setLocale('id');

        // Format tanggal lengkap dan tahun
        $updated_at = $kaks->updated_at->translatedFormat('d F Y') ?? 'Tidak Diketahui'; // Format: 21 Januari 2025

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
    <p>' . nl2br($kaks->tujuan) . '</p>

    <div class="section">C. TARGET/SASARAN</div>
    <p>' . nl2br($kaks->target_sasaran) . '</p>

    <div class="section">D. UNIT KERJA</div>
    <p>' . nl2br($kaks->unit_kerja) . '</p>
    
    <div class="section">E. RUANG LINGKUP PEKERJAAN</div>
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
    <p>' . nl2br($kaks->manajemen_resiko) . '</p>
    
    <div class="section">L. LAPORAN PENGAJUAN PEKERJAAN</div>
    <p>' . nl2br($kaks->laporan_pengajuan_pekerjaan) . '</p>
    
    <div class="section">M. SUMBER DANA</div>
    <p>' . nl2br($kaks->sumber_dana_prakiraan_biaya) . '</p>
    
    <div class="section">N. PENUTUP</div>
    <p>' . nl2br($kaks->penutup) . '</p>

    <br><br><br><br><br>
    
    <div style="width: 100%; text-align: right;">
    <span>Jakarta, ' . ($updated_at) . '</span><br><br>
    <span><b>' . ($kaks->kategori->nama_divisi ?? 'Tidak Diketahui') . '</b></span>
</div>
<br><br>
<div style="width: 100%; text-align: right;">
    <span style="text-decoration: underline; font-weight: bold;">' . ($creator->username ?? 'Tidak Diketahui') . '</span><br>
    <span>NIP. ' . ($creator->nik ?? 'Tidak Diketahui') . '</span>
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

        // Data user pembuat kaks
        $creator = $kaks->user;

        // Set locale to Indonesian for Carbon
        \Carbon\Carbon::setLocale('id');

        // Format tanggal lengkap dan tahun
        $updated_at = $kaks->updated_at->translatedFormat('d F Y') ?? 'Tidak Diketahui'; // Format: 21 Januari 2025

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
    <p>' . nl2br($kaks->tujuan) . '</p>

    <div class="section">C. TARGET/SASARAN</div>
    <p>' . nl2br($kaks->target_sasaran) . '</p>

    <div class="section">D. UNIT KERJA</div>
    <p>' . nl2br($kaks->unit_kerja) . '</p>
    
    <div class="section">E. RUANG LINGKUP PEKERJAAN</div>
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
    <p>' . nl2br($kaks->manajemen_resiko) . '</p>
    
    <div class="section">L. LAPORAN PENGAJUAN PEKERJAAN</div>
    <p>' . nl2br($kaks->laporan_pengajuan_pekerjaan) . '</p>
    
    <div class="section">M. SUMBER DANA</div>
    <p>' . nl2br($kaks->sumber_dana_prakiraan_biaya) . '</p>
    
    <div class="section">N. PENUTUP</div>
    <p>' . nl2br($kaks->penutup) . '</p>

    <br><br><br><br><br>
    
    <div style="width: 100%; text-align: right;">
    <span>Jakarta, ' . ($updated_at) . '</span><br><br>
    <span><b>' . ($kaks->kategori->nama_divisi ?? 'Tidak Diketahui') . '</b></span>
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
