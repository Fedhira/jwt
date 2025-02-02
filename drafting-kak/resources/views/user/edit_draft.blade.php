<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Drafting KAK - BAKTI</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- Fonts and Icons -->
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: ["simple-line-icons"],
                urls: ["{{ asset('assets/css/fonts.min.css') }}"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- SweetAlert -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .btn {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            display: inline-block;
            text-align: center;
        }

        .btn-ubah {
            background-color: #FFA726;
            color: black;
        }

        .btn-hapus {
            background-color: #E57373;
            color: white;
        }

        .ck-editor__editable {
            min-height: 250px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        @include('layouts.navbar_user')
        <!-- End Sidebar -->

        <div class="main-panel">
            @include('layouts.header_user')

            <div class="container">
                <div class="page-inner">
                    <div class="page-header">
                        <h3 class="fw-bold mb-3">Ubah Draft KAK</h3>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-4">
                            <div class="card" style="height: 500px; overflow-y: auto;">
                                <div class="card-body">
                                    <form method="POST" action="{{ route('user.draft.update', $draft->kak_id) }}"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <label for="no_doc_mak"><strong>No. MAK</strong></label>
                                        <input type="text" id="no_doc_mak" name="no_doc_mak"
                                            class="form-control mb-3" value="{{ $draft->no_doc_mak }}" required>

                                        <label for="kategori_id"><strong>Kategori Program</strong></label>
                                        <input type="text" id="kategori_id_display" class="form-control mb-3"
                                            value="{{ $draft->kategori->nama_divisi ?? 'Tidak Diketahui' }}" readonly>
                                        <input type="hidden" name="kategori_id" value="{{ $draft->kategori_id }}">

                                        <label for="judul"><strong>Judul KAK</strong></label>
                                        <input type="text" id="judul" name="judul" class="form-control mb-3"
                                            value="{{ $draft->judul }}" required>

                                        <label for="indikator"><strong>Indikator Kinerja Kegiatan</strong></label>
                                        <input type="text" id="indikator" name="indikator" class="form-control mb-3"
                                            value="{{ $draft->indikator }}" required>

                                        <label for="satuan_ukur"><strong>Satuan Ukur / Jenis Keluaran</strong></label>
                                        <input type="text" id="satuan_ukur" name="satuan_ukur"
                                            class="form-control mb-3" value="{{ $draft->satuan_ukur }}" required>

                                        <label for="volume"><strong>Volume</strong></label>
                                        <input type="text" id="volume" name="volume" class="form-control mb-3"
                                            value="{{ $draft->volume }}" required>

                                        <label for="latar_belakang"><strong>Latar Belakang</strong></label>
                                        <textarea class="editor form-control mb-3" id="latar_belakang" name="latar_belakang">{{ $draft->latar_belakang }}</textarea>

                                        <label for="dasar_hukum"><strong>Dasar Hukum</strong></label>
                                        <textarea class="editor form-control mb-3" id="dasar_hukum" name="dasar_hukum">{{ $draft->dasar_hukum }}</textarea>

                                        <label for="gambaran_umum"><strong>Gambaran Umum</strong></label>
                                        <textarea class="editor form-control mb-3" id="gambaran_umum" name="gambaran_umum">{{ $draft->gambaran_umum }}</textarea>

                                        <label for="tujuan"><strong>Tujuan</strong></label>
                                        <textarea class="editor form-control mb-3" id="tujuan" name="tujuan">{{ $draft->tujuan }}</textarea>

                                        <label for="target_sasaran"><strong>Target/Sasaran</strong></label>
                                        <textarea class="editor form-control mb-3" id="target_sasaran" name="target_sasaran">{{ $draft->target_sasaran }}</textarea>

                                        <label for="unit_kerja"><strong>Unit Kerja Pelaksana</strong></label>
                                        <textarea class="editor form-control mb-3" id="unit_kerja" name="unit_kerja">{{ $draft->unit_kerja }}</textarea>

                                        <label for="ruang_lingkup"><strong>Ruang Lingkup, Lokasi dan Fasilitas
                                                Penunjang</strong></label>
                                        <textarea class="editor form-control mb-3" id="ruang_lingkup" name="ruang_lingkup">{{ $draft->ruang_lingkup }}</textarea>

                                        <label for="produk_jasa_dihasilkan"><strong>Produk/Jasa yang dihasilkan
                                                (Deliverable)</strong></label>
                                        <textarea class="editor form-control mb-3" id="produk_jasa_dihasilkan" name="produk_jasa_dihasilkan">{{ $draft->produk_jasa_dihasilkan }}</textarea>

                                        <label for="waktu_pelaksanaan"><strong>Waktu Pelaksanaan</strong></label>
                                        <textarea class="editor form-control mb-3" id="waktu_pelaksanaan" name="waktu_pelaksanaan">{{ $draft->waktu_pelaksanaan }}</textarea>

                                        <label for="tenaga_ahli_terampil"><strong>Tenaga Ahli</strong></label>
                                        <textarea class="editor form-control mb-3" id="tenaga_ahli_terampil" name="tenaga_ahli_terampil">{{ $draft->tenaga_ahli_terampil }}</textarea>

                                        <label for="peralatan"><strong>Peralatan</strong></label>
                                        <textarea class="editor form-control mb-3" id="peralatan" name="peralatan">{{ $draft->peralatan }}</textarea>

                                        <label for="metode_kerja"><strong>Metode Kerja</strong></label>
                                        <textarea class="editor form-control mb-3" id="metode_kerja" name="metode_kerja">{{ $draft->metode_kerja }}</textarea>

                                        <label for="manajemen_resiko"><strong>Manajemen Resiko</strong></label>
                                        <textarea class="editor form-control mb-3" id="manajemen_resiko" name="manajemen_resiko">{{ $draft->manajemen_resiko }}</textarea>

                                        <label for="laporan_pengajuan_pekerjaan"><strong>Laporan Pengajuan
                                                Pekerjaan</strong></label>
                                        <textarea class="editor form-control mb-3" id="laporan_pengajuan_pekerjaan" name="laporan_pengajuan_pekerjaan">{{ $draft->laporan_pengajuan_pekerjaan }}</textarea>

                                        <label for="sumber_dana_prakiraan_biaya"><strong>Sumber Dana dan Prakiraan
                                                Biaya</strong></label>
                                        <textarea class="editor form-control mb-3" id="sumber_dana_prakiraan_biaya" name="sumber_dana_prakiraan_biaya">{{ $draft->sumber_dana_prakiraan_biaya }}</textarea>

                                        <label for="penutup"><strong>Penutup</strong></label>
                                        <textarea class="editor form-control mb-3" id="penutup" name="penutup">{{ $draft->penutup }}</textarea>

                                        <label for="lampiran"><strong>Lampiran</strong></label>
                                        @if ($draft->lampiran)
                                            <p>Lampiran saat ini: <a href="{{ asset('storage/' . $draft->lampiran) }}"
                                                    target="_blank">Lihat Lampiran</a></p>
                                        @endif
                                        <input type="file" id="lampiran" name="lampiran"
                                            class="form-control mb-3" accept=".pdf, image/*">

                                        <div class="mt-4 text-end">
                                            <button type="submit" class="btn btn-primary me-2">Simpan</button>
                                            <a href="{{ route('user.draft') }}" class="btn btn-danger">Batal</a>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('layouts.footer')
        </div>
    </div>

    <!-- Core JS Files -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <!-- Kaiadmin JS -->
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>

    <script src="https://cdn.ckeditor.com/ckeditor5/35.4.0/classic/ckeditor.js"></script>
    <script>
        document.querySelectorAll('.editor').forEach(editorElement => {
            ClassicEditor.create(editorElement).catch(error => console.error(error));
        });
    </script>

    <script>
        function logoutConfirm(event) {
            event.preventDefault(); // Prevents the default link action

            Swal.fire({
                title: 'Are you sure you want to logout?',
                text: "You will be logged out of the system.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, logout'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to logout.php if confirmed
                    window.location.href = '{{ route('logout') }}';
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('unchanged'))
        <script>
            Swal.fire({
                icon: 'info',
                title: 'Tidak Ada Perubahan',
                text: '{{ session('unchanged') }}',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                confirmButtonText: 'OK'
            });
        </script>
    @endif

</body>

</html>
