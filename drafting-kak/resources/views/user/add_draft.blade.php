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
                        <h3 class="fw-bold mb-3">Tambah Draft KAK</h3>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mt-4">
                            <div class="card" style="height: 500px; overflow-y: auto;">
                                <!-- Scrollable card with fixed height -->
                                <div class="card-body">
                                    <!-- Form with bold labels and improved spacing -->
                                    <form action="{{ route('user.draft.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ Auth::id() }}">

                                        <label for="no_doc_mak"><strong>No. MAK</strong></label>
                                        <input type="text" id="no_doc_mak" name="no_doc_mak"
                                            class="form-control mb-3" autofocus required>

                                        <label for="kategori_id"><strong>Kategori Program</strong></label>
                                        <input type="text" id="kategori_id_display" class="form-control mb-3"
                                            value="{{ $kategori_programs->nama_divisi }}" readonly>

                                        <!-- Hidden field untuk mengirimkan kategori_id ke server -->
                                        <input type="hidden" id="kategori_id" name="kategori_id"
                                            value="{{ $kategori_programs->id }}">

                                        <label for="judul"><strong>Judul KAK</strong></label>
                                        <input type="text" id="judul" name="judul" class="form-control mb-3"
                                            autofocus required>

                                        <label for="indikator"><strong>Indikator Kinerja Kegiatan</strong></label>
                                        <input type="text" id="indikator" name="indikator" class="form-control mb-3"
                                            autofocus required>

                                        <label for="satuan_ukur"><strong>Satuan Ukur / Jenis Keluaran</strong></label>
                                        <input type="text" id="satuan_ukur" name="satuan_ukur"
                                            class="form-control mb-3" autofocus required>

                                        <label for="volume"><strong>Volume</strong></label>
                                        <input type="text" id="volume" name="volume" class="form-control mb-3"
                                            autofocus required>

                                        <label for="latar_belakang"><strong>Latar Belakang</strong></label>
                                        <textarea class="editor form-control mb-3" id="latar_belakang" name="latar_belakang" autofocus></textarea>
                                        <br>

                                        <label for="dasar_hukum"><strong>Dasar Hukum</strong></label>
                                        <textarea class="editor form-control mb-3" id="dasar_hukum" name="dasar_hukum" autofocus></textarea>
                                        <br>

                                        <label for="gambaran_umum"><strong>Gambaran Umum</strong></label>
                                        <textarea class="editor form-control mb-3" id="gambaran_umum" name="gambaran_umum" autofocus></textarea>
                                        <br>

                                        <label for="tujuan"><strong>Tujuan</strong></label>
                                        <textarea class="editor form-control mb-3" id="tujuan" name="tujuan" autofocus></textarea>
                                        <br>

                                        <label for="target_sasaran"><strong>Target/Sasaran</strong></label>
                                        <textarea class="editor form-control mb-3" id="target_sasaran" name="target_sasaran" autofocus></textarea>
                                        <br>

                                        <label for="unit_kerja"><strong>Unit Kerja Pelaksana</strong></label>
                                        <textarea class="editor form-control mb-3" id="unit_kerja" name="unit_kerja" autofocus></textarea>
                                        <br>

                                        <label for="ruang_lingkup"><strong>Ruang Lingkup, Lokasi dan Fasilitas
                                                Penunjang</strong></label>
                                        <textarea class="editor form-control mb-3" id="ruang_lingkup" name="ruang_lingkup" autofocus></textarea>
                                        <br>

                                        <label for="produk_jasa_dihasilkan"><strong>Produk/Jasa yang dihasilkan
                                                (Deliverable)</strong></label>
                                        <textarea class="editor form-control mb-3" id="produk_jasa_dihasilkan" name="produk_jasa_dihasilkan" autofocus></textarea>
                                        <br>

                                        <label for="waktu_pelaksanaan"><strong>Waktu Pelaksanaan</strong></label>
                                        <textarea class="editor form-control mb-3" id="waktu_pelaksanaan" name="waktu_pelaksanaan" autofocus></textarea>
                                        <br>

                                        <label for="tenaga_ahli_terampil"><strong>Tenaga Ahli</strong></label>
                                        <textarea class="editor form-control mb-3" id="tenaga_ahli_terampil" name="tenaga_ahli_terampil" autofocus></textarea>
                                        <br>

                                        <label for="peralatan"><strong>Peralatan</strong></label>
                                        <textarea class="editor form-control mb-3" id="peralatan" name="peralatan" autofocus></textarea>
                                        <br>

                                        <label for="metode_kerja"><strong>Metode Kerja</strong></label>
                                        <textarea class="editor form-control mb-3" id="metode_kerja" name="metode_kerja" autofocus></textarea>
                                        <br>

                                        <label for="manajemen_resiko"><strong>Manajemen Resiko</strong></label>
                                        <textarea class="editor form-control mb-3" id="manajemen_resiko" name="manajemen_resiko" autofocus></textarea>
                                        <br>

                                        <label for="laporan_pengajuan_pekerjaan"><strong>Laporan Pengajuan
                                                Pekerjaan</strong></label>
                                        <textarea class="editor form-control mb-3" id="laporan_pengajuan_pekerjaan" name="laporan_pengajuan_pekerjaan"
                                            autofocus></textarea>
                                        <br>

                                        <label for="sumber_dana_prakiraan_biaya"><strong>Sumber Dana dan Prakiraan
                                                Biaya</strong></label>
                                        <textarea class="editor form-control mb-3" id="sumber_dana_prakiraan_biaya" name="sumber_dana_prakiraan_biaya"
                                            autofocus></textarea>
                                        <br>

                                        <label for="penutup"><strong>Penutup</strong></label>
                                        <textarea class="editor form-control mb-3" id="penutup" name="penutup" autofocus></textarea>
                                        <br>

                                        <label for="lampiran"><strong>Lampiran</strong></label>
                                        <input type="file" id="lampiran" name="lampiran"
                                            class="form-control mb-3" accept=".pdf, image/*" autofocus>

                                        <div class="mt-4 text-end">
                                            <button type="submit" class="btn btn-primary me-2"
                                                value="Submit">Simpan</button>
                                            <button type="button" class="btn btn-danger"
                                                onclick="window.location.href='{{ route('user.draft') }}';">Batal</button>
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
    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <!-- Datatables -->
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <!-- Kaiadmin JS -->
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#basic-datatables").DataTable({});

            $("#multi-filter-select").DataTable({
                pageLength: 5,
                initComplete: function() {
                    this.api()
                        .columns()
                        .every(function() {
                            var column = this;
                            var select = $(
                                    '<select class="form-select"><option value=""></option></select>'
                                )
                                .appendTo($(column.footer()).empty())
                                .on("change", function() {
                                    var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                    column
                                        .search(val ? "^" + val + "$" : "", true, false)
                                        .draw();
                                });

                            column
                                .data()
                                .unique()
                                .sort()
                                .each(function(d, j) {
                                    select.append(
                                        '<option value="' + d + '">' + d + "</option>"
                                    );
                                });
                        });
                },
            });

            // Add Row
            $("#add-row").DataTable({
                pageLength: 5,
            });

            var action =
                '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

            $("#addRowButton").click(function() {
                $("#add-row")
                    .dataTable()
                    .fnAddData([
                        $("#addName").val(),
                        $("#addPosition").val(),
                        $("#addOffice").val(),
                        action,
                    ]);
                $("#addRowModal").modal("hide");
            });
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

    <script src="https://cdn.ckeditor.com/ckeditor5/35.4.0/classic/ckeditor.js"></script>
    <script type="text/javascript">
        document.querySelectorAll('.editor').forEach(editorElement => {
            ClassicEditor
                .create(editorElement, {
                    ckfinder: {
                        uploadUrl: "",
                    }
                })
                .then(editor => {
                    console.log(editor);
                })
                .catch(error => {
                    console.error(error);
                });
        });
    </script>

    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            const metodeField = document.getElementById('metode');
            if (metodeField && !metodeField.checkValidity()) {
                metodeField.scrollIntoView(); // Make sure it's visible
                metodeField.focus(); // Focus on it
                event.preventDefault(); // Prevent form submission until valid
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan!',
                    html: '{!! implode('<br>', $errors->all()) !!}',
                    showConfirmButton: true
                });
            @endif
        });
    </script>



</body>

</html>
