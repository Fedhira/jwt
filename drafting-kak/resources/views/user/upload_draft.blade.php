<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Drafting KAK - BAKTI</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

    <!-- Fonts and icons -->
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

    <!-- Load Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

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
                        <h3 class="fw-bold mb-3">Upload Draft KAK</h3>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-4">
                            <div class="card" style="height: 500px; overflow-y: auto;">
                                <div class="card-body">
                                    <!-- Form with bold labels and improved spacing -->
                                    <form method="POST" action="{{ route('user.draft.upload') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" readonly name="kak_id" value="{{ $draft->kak_id }}">

                                        <label for="no_doc_mak"><strong>No. MAK</strong></label>
                                        <input type="text" id="no_doc_mak" readonly name="no_doc_mak"
                                            class="form-control mb-3" value="{{ $draft->no_doc_mak }}" required>

                                        <label for="kategori_id"><strong>Kategori Program</strong></label>
                                        <input type="text" id="kategori_id_display" class="form-control mb-3"
                                            value="{{ $draft->kategori->nama_divisi ?? 'Tidak Diketahui' }}" readonly>
                                        <input type="hidden" readonly name="kategori_id"
                                            value="{{ $draft->kategori_id }}">

                                        <label for="judul"><strong>Judul KAK</strong></label>
                                        <input type="text" id="judul" readonly name="judul"
                                            class="form-control mb-3" value="{{ $draft->judul }}" required>

                                        <label for="indikator"><strong>Indikator Kinerja Kegiatan</strong></label>
                                        <input type="text" id="indikator" readonly name="indikator"
                                            class="form-control mb-3" value="{{ $draft->indikator }}" required>

                                        <label for="satuan_ukur"><strong>Satuan Ukur / Jenis Keluaran</strong></label>
                                        <input type="text" id="satuan_ukur" readonly name="satuan_ukur"
                                            class="form-control mb-3" value="{{ $draft->satuan_ukur }}" required>

                                        <label for="volume"><strong>Volume</strong></label>
                                        <input type="text" id="volume" readonly name="volume"
                                            class="form-control mb-3" value="{{ $draft->volume }}" required>

                                        <label for="latar_belakang"><strong>Latar Belakang</strong></label>
                                        <textarea class="editor form-control mb-3" id="latar_belakang" readonly name="latar_belakang">{{ $draft->latar_belakang }}</textarea>

                                        <label for="dasar_hukum"><strong>Dasar Hukum</strong></label>
                                        <textarea class="editor form-control mb-3" id="dasar_hukum" readonly name="dasar_hukum">{{ $draft->dasar_hukum }}</textarea>

                                        <label for="gambaran_umum"><strong>Gambaran Umum</strong></label>
                                        <textarea class="editor form-control mb-3" id="gambaran_umum" readonly name="gambaran_umum">{{ $draft->gambaran_umum }}</textarea>

                                        <label for="tujuan"><strong>Tujuan</strong></label>
                                        <textarea class="editor form-control mb-3" id="tujuan" readonly name="tujuan">{{ $draft->tujuan }}</textarea>

                                        <label for="target_sasaran"><strong>Target/Sasaran</strong></label>
                                        <textarea class="editor form-control mb-3" id="target_sasaran" readonly name="target_sasaran">{{ $draft->target_sasaran }}</textarea>

                                        <label for="unit_kerja"><strong>Unit Kerja Pelaksana</strong></label>
                                        <textarea class="editor form-control mb-3" id="unit_kerja" readonly name="unit_kerja">{{ $draft->unit_kerja }}</textarea>

                                        <label for="ruang_lingkup"><strong>Ruang Lingkup, Lokasi dan Fasilitas
                                                Penunjang</strong></label>
                                        <textarea class="editor form-control mb-3" id="ruang_lingkup" readonly name="ruang_lingkup">{{ $draft->ruang_lingkup }}</textarea>

                                        <label for="produk_jasa_dihasilkan"><strong>Produk/Jasa yang dihasilkan
                                                (Deliverable)</strong></label>
                                        <textarea class="editor form-control mb-3" id="produk_jasa_dihasilkan" readonly name="produk_jasa_dihasilkan">{{ $draft->produk_jasa_dihasilkan }}</textarea>

                                        <label for="waktu_pelaksanaan"><strong>Waktu Pelaksanaan</strong></label>
                                        <textarea class="editor form-control mb-3" id="waktu_pelaksanaan" readonly name="waktu_pelaksanaan">{{ $draft->waktu_pelaksanaan }}</textarea>

                                        <label for="tenaga_ahli_terampil"><strong>Tenaga Ahli</strong></label>
                                        <textarea class="editor form-control mb-3" id="tenaga_ahli_terampil" readonly name="tenaga_ahli_terampil">{{ $draft->tenaga_ahli_terampil }}</textarea>

                                        <label for="peralatan"><strong>Peralatan</strong></label>
                                        <textarea class="editor form-control mb-3" id="peralatan" readonly name="peralatan">{{ $draft->peralatan }}</textarea>

                                        <label for="metode_kerja"><strong>Metode Kerja</strong></label>
                                        <textarea class="editor form-control mb-3" id="metode_kerja" readonly name="metode_kerja">{{ $draft->metode_kerja }}</textarea>

                                        <label for="manajemen_resiko"><strong>Manajemen Resiko</strong></label>
                                        <textarea class="editor form-control mb-3" id="manajemen_resiko" readonly name="manajemen_resiko">{{ $draft->manajemen_resiko }}</textarea>

                                        <label for="laporan_pengajuan_pekerjaan"><strong>Laporan Pengajuan
                                                Pekerjaan</strong></label>
                                        <textarea class="editor form-control mb-3" id="laporan_pengajuan_pekerjaan" readonly
                                            name="laporan_pengajuan_pekerjaan">{{ $draft->laporan_pengajuan_pekerjaan }}</textarea>

                                        <label for="sumber_dana_prakiraan_biaya"><strong>Sumber Dana dan Prakiraan
                                                Biaya</strong></label>
                                        <textarea class="editor form-control mb-3" id="sumber_dana_prakiraan_biaya" readonly
                                            name="sumber_dana_prakiraan_biaya">{{ $draft->sumber_dana_prakiraan_biaya }}</textarea>

                                        <label for="penutup"><strong>Penutup</strong></label>
                                        <textarea class="editor form-control mb-3" id="penutup" readonly name="penutup">{{ $draft->penutup }}</textarea>

                                        <label for="lampiran"><strong>Lampiran</strong></label>
                                        @if ($draft->lampiran)
                                            <p>Lampiran saat ini: <a href="{{ asset('storage/' . $draft->lampiran) }}"
                                                    target="_blank">Lihat Lampiran</a></p>
                                        @endif
                                        <input type="file" id="lampiran" readonly name="lampiran"
                                            class="form-control mb-3" accept=".pdf, image/*" disabled>

                                        <div class="mt-4 text-end">
                                            <button type="submit" class="btn btn-secondary me-2" name="upload"
                                                value="Upload">Upload</button>
                                            <a href="{{ route('user.draft') }}" class="btn btn-danger">Cancel</a>
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

    <!-- Plugin JS Files -->
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>

    <!-- Kaiadmin JS -->
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>

    <!-- Custom Scripts -->
    <script>
        $(document).ready(function() {
            $("#basic-datatables").DataTable();
        });

        function logoutConfirm(event) {
            event.preventDefault();
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
                    window.location.href = '{{ route('logout') }}';
                }
            });
        }
    </script>
</body>

</html>
