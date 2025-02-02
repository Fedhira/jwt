<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Drafting KAK - BAKTI</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

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
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />

    <!-- CSS Just for demo purpose -->
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
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        @include('layouts.navbar_supervisor')
        <!-- End Sidebar -->

        <div class="main-panel">
            @include('layouts.header_supervisor')

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
                                        <form method="GET" action="{{ route('supervisor.daftar') }}">
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
                                                <a href="{{ route('supervisor.daftar') }}"
                                                    class="btn btn-danger btn-round me-2"
                                                    style="width: 167px;">Clear</a>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                                <div class="card-body">

                                    <!-- Modal Ditolak -->
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

                                                <!-- Modal Body with Scroll -->
                                                <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                                                    <form method="POST" action="{{ route('supervisor.kak.reject') }}">
                                                        @csrf
                                                        <!-- Hidden input untuk ID -->
                                                        <input type="hidden" name="kak_id" id="modal_kak_id"
                                                            value="">

                                                        <div class="form-group form-group-default">
                                                            <label>No Doc</label>
                                                            <input type="text" id="modal_no_doc" name="no_doc_mak"
                                                                class="form-control" readonly />
                                                        </div>
                                                        <div class="form-group form-group-default">
                                                            <label>Judul KAK</label>
                                                            <input type="text" id="modal_judul" name="judul"
                                                                class="form-control" readonly />
                                                        </div>
                                                        <div class="form-group form-group-default">
                                                            <label>Kategori Program</label>
                                                            <input type="text" id="modal_kategori" name="kategori"
                                                                class="form-control" readonly />
                                                        </div>
                                                        <div class="form-group form-group-default">
                                                            <label>Alasan Penolakan</label>
                                                            <textarea name="alasan_penolakan" class="form-control" placeholder="Masukkan alasan penolakan" required></textarea>
                                                        </div>
                                                        <div class="form-group form-group-default">
                                                            <label>Saran/Revisi</label>
                                                            <textarea name="saran" class="form-control" placeholder="Masukkan saran atau revisi" required></textarea>
                                                        </div>
                                                        <div class="modal-footer border-0">
                                                            <button type="submit"
                                                                class="btn btn-primary">Simpan</button>
                                                            <button type="button" class="btn btn-danger"
                                                                data-bs-dismiss="modal">Batal</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="add-row" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No Doc</th>
                                                    <th>Judul KAK</th>
                                                    <th>Kategori Program</th>
                                                    <th>Status Dokumen</th>
                                                    <th>Tanggal Dibuat</th>
                                                    <th>Tanggal Diperbarui</th>
                                                    <th style="width: 10%">Aksi</th>
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
                                                @forelse ($kaks as $kak)
                                                    <tr>
                                                        <td>{{ $kak->no_doc_mak }}</td>
                                                        <td>{{ $kak->judul }}</td>
                                                        <td>{{ $kak->kategori->nama_divisi ?? 'Tidak Diketahui' }}</td>
                                                        <td>
                                                            @if ($kak->status === 'disetujui')
                                                                <span class="status status-disetujui">Disetujui</span>
                                                            @elseif ($kak->status === 'pending')
                                                                <span class="status status-pending">Pending</span>
                                                            @elseif ($kak->status === 'ditolak')
                                                                <span class="status status-ditolak">Ditolak</span>
                                                            @else
                                                                <span class="status status-unknown">Unknown</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $kak->created_at->format('d-m-Y H:i') }}</td>
                                                        <td>{{ $kak->updated_at->format('d-m-Y H:i') }}</td>
                                                        <td>
                                                            <div class="form-button-action d-inline-flex">
                                                                <a href="{{ route('supervisor.previewPdf', ['id' => $kak->kak_id]) }}"
                                                                    target="_blank"
                                                                    class="btn btn-dark btn-round me-2"
                                                                    style="width: 110px;">
                                                                    <i class="fas fa-file-pdf"></i> Preview
                                                                </a>
                                                                @if ($kak->status == 'pending')
                                                                    <form
                                                                        action="{{ route('supervisor.approve', $kak->kak_id) }}"
                                                                        method="POST" style="display: inline;">
                                                                        @csrf
                                                                        <button type="submit"
                                                                            class="btn btn-success btn-round me-2"
                                                                            style="width: 110px;">
                                                                            <i class="fas fa-check"></i> Disetujui
                                                                        </button>
                                                                    </form>
                                                                    <!-- Tombol Ditolak muncul hanya jika status adalah pending -->
                                                                    <button class="btn btn-danger btn-round me-2"
                                                                        style="width: 110px;" data-bs-toggle="modal"
                                                                        data-bs-target="#tolakRowModal"
                                                                        onclick="populateTolakModal({{ $kak->kak_id }}, '{{ $kak->no_doc_mak }}', '{{ $kak->judul }}', '{{ $kak->kategori->nama_divisi ?? 'Tidak Diketahui' }}')">
                                                                        <i class="fas fa-xmark"></i> Ditolak
                                                                    </button>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center">Tidak ada data daftar
                                                            ditemukan.</td>
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

    <!-- JS Files -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>

    <script>
        function populateTolakModal(kakId, noDoc, judul, kategori) {
            // Isi data ke input modal
            document.getElementById('modal_kak_id').value = kakId;
            document.getElementById('modal_no_doc').value = noDoc;
            document.getElementById('modal_judul').value = judul;
            document.getElementById('modal_kategori').value = kategori;
        }
    </script>


    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif

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
                    window.location.href = '{{ route('logout') }}'; // Redirect ke route logout
                }
            });
        }
    </script>



</body>

</html>
