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

    <!-- Load Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <!-- SweetAlert -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />

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
                        <h3 class="fw-bold mb-3">Daftar KAK</h3>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <!-- Date Picker From and To -->
                                        <form method="GET" action="{{ route('user.daftar') }}">
                                            <div class="d-flex">
                                                <div class="input-group me-4">
                                                    <span class="input-group-text">From</span>
                                                    <input type="date" class="form-control" name="fromDate"
                                                        value="{{ request('fromDate') }}" />
                                                </div>
                                                <div class="input-group me-4">
                                                    <span class="input-group-text">To</span>
                                                    <input type="date" class="form-control" name="toDate"
                                                        value="{{ request('toDate') }}" />
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-round me-2"
                                                    style="width: 167px;">Filter</button>
                                                <a href="{{ route('user.daftar') }}"
                                                    class="btn btn-danger btn-round me-2"
                                                    style="width: 167px;">Clear</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- Modal Tolak -->
                                    <div class="modal fade" id="tolakRowModal" tabindex="-1" role="dialog"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title">
                                                        <span class="fw-mediumbold">Form Penolakan KAK</span>
                                                    </h5>
                                                    <button type="button" class="close" data-bs-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                                                    <form method="POST" action="{{ route('user.kak.reject') }}">
                                                        @csrf
                                                        <!-- Hidden input untuk ID -->
                                                        <input type="hidden" name="kak_id" id="modal_kak_id"
                                                            value="" />

                                                        <div style="margin-bottom: 15px;">
                                                            <label>No Doc</label>
                                                            <input type="text" id="modal_no_doc" name="no_doc_mak"
                                                                class="form-control" readonly />
                                                        </div>
                                                        <div style="margin-bottom: 15px;">
                                                            <label>Judul KAK</label>
                                                            <input type="text" id="modal_judul" name="judul"
                                                                class="form-control" readonly />
                                                        </div>
                                                        <div style="margin-bottom: 15px;">
                                                            <label>Kategori Program</label>
                                                            <input type="text" id="modal_kategori" name="kategori"
                                                                class="form-control" readonly />
                                                        </div>
                                                        <div style="margin-bottom: 15px;">
                                                            <label>Alasan Penolakan</label>
                                                            <input type="text" id="modal_alasan_penolakan"
                                                                name="alasan_penolakan" class="form-control" readonly />
                                                        </div>
                                                        <div style="margin-bottom: 15px;">
                                                            <label>Saran/Revisi</label>
                                                            <input type="text" id="modal_saran" name="saran"
                                                                class="form-control" readonly />
                                                        </div>
                                                        <div class="modal-footer border-0">
                                                            <button type="button" class="btn btn-danger"
                                                                data-bs-dismiss="modal">Batal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>





                                    {{-- START TABLE --}}

                                    <div class="table-responsive">
                                        <table id="add-row" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No Doc</th>
                                                    <th>Judul KAK</th>
                                                    <th>Kategori Program</th>
                                                    <th>Status Dokumen</th>
                                                    <th>Tanggal Dibuat</th>
                                                    <th>Tanggal Diperbarui</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>No Doc</th>
                                                    <th>Judul KAK</th>
                                                    <th>Kategori Program</th>
                                                    <th>Status Dokumen</th>
                                                    <th>Tanggal Dibuat</th>
                                                    <th>Tanggal Diperbarui</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                @forelse ($drafts as $draft)
                                                    <tr>
                                                        <td>{{ $draft->no_doc_mak }}</td>
                                                        <td>{{ $draft->judul }}</td>
                                                        <td>{{ $draft->kategori->nama_divisi ?? 'Tidak Diketahui' }}
                                                        </td>
                                                        <td>
                                                            @if ($draft->status === 'disetujui')
                                                                <span class="status status-disetujui">Disetujui</span>
                                                            @elseif ($draft->status === 'pending')
                                                                <span class="status status-pending">Pending</span>
                                                            @elseif ($draft->status === 'ditolak')
                                                                <span class="status status-ditolak">Ditolak</span>
                                                            @else
                                                                <span class="status status-unknown">Unknown</span>
                                                            @endif
                                                        </td>

                                                        <td>{{ $draft->created_at->format('d-m-Y H:i') }}</td>
                                                        <td>{{ $draft->updated_at->format('d-m-Y H:i') }}</td>
                                                        <td>
                                                            <div class="form-button-action">
                                                                <a href="{{ route('user.previewPdf', ['id' => $draft->kak_id]) }}"
                                                                    target="_blank"
                                                                    class='btn btn-dark btn-round me-2'
                                                                    style='width: 110px;'>
                                                                    <i class='fas fa-file-pdf'></i> Preview
                                                                </a>
                                                                @if ($draft->status === 'ditolak')
                                                                    <button class="btn btn-danger btn-round me-2"
                                                                        style="width: 110px;" data-bs-toggle="modal"
                                                                        data-bs-target="#tolakRowModal"
                                                                        data-kak-id="{{ $draft->kak_id }}"
                                                                        data-no-doc="{{ $draft->no_doc_mak }}"
                                                                        data-judul="{{ $draft->judul }}"
                                                                        data-kategori="{{ $draft->kategori->nama_divisi ?? 'Tidak Diketahui' }}"
                                                                        data-alasan-penolakan="{{ $draft->revisi->alasan_penolakan ?? '' }}"
                                                                        data-saran="{{ $draft->revisi->saran ?? '' }}"
                                                                        onclick="populateTolakModalFromData(this)">
                                                                        <i class="fas fa-edit"></i> Revisi
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center text-muted">Tidak ada
                                                            data ditemukan.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>

    <!-- Core JS -->
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
                    window.location.href = "{{ route('logout') }}";
                }
            });
        }
    </script>
    <script>
        function populateTolakModalFromData(button) {
            const kakId = button.getAttribute('data-kak-id');
            const noDoc = button.getAttribute('data-no-doc');
            const judul = button.getAttribute('data-judul');
            const kategori = button.getAttribute('data-kategori');
            const alasanPenolakan = button.getAttribute('data-alasan-penolakan');
            const saran = button.getAttribute('data-saran');

            // Debugging: Cek data yang diterima
            console.log({
                kakId,
                noDoc,
                judul,
                kategori,
                alasanPenolakan,
                saran
            });

            // Isi form di modal
            document.getElementById('modal_kak_id').value = kakId;
            document.getElementById('modal_no_doc').value = noDoc;
            document.getElementById('modal_judul').value = judul;
            document.getElementById('modal_kategori').value = kategori;
            document.getElementById('modal_alasan_penolakan').value = alasanPenolakan || ''; // Default kosong jika null
            document.getElementById('modal_saran').value = saran || ''; // Default kosong jika null
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');
            const action = urlParams.get('action');

            if (status === 'success' && action === 'upload') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Draft berhasil diunggah dan status diubah menjadi pending.',
                    confirmButtonText: 'OK'
                });
            } else if (status === 'error' && action === 'upload') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat mengunggah draft. Mohon coba lagi.',
                    confirmButtonText: 'OK'
                });
            }
        });
    </script>

</body>

</html>
