<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Drafting KAK - BAKTI</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />

    <!-- Fonts and Icons -->
    <script src="{{ asset('assets/js/plugin/webfont/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: ["simple-line-icons"],
                urls: ["{{ asset('assets/css/fonts.min.css') }}"]
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />

    <!-- Custom Styles -->
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
        @include('layouts.navbar_admin')
        <!-- End Sidebar -->

        <div class="main-panel">
            @include('layouts.header_admin')

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
                                        <form method="GET" action="{{ route('admin.daftar') }}">
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
                                                <a href="{{ route('admin.daftar') }}"
                                                    class="btn btn-danger btn-round me-2"
                                                    style="width: 167px;">Clear</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="add-row"
                                            class="display table table-striped table-hover table-center">
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
                                                                <a href="{{ route('admin.previewPdf', ['id' => $kak->kak_id]) }}"
                                                                    target="_blank" class='btn btn-dark btn-round me-2'
                                                                    style='width: 100px;'>
                                                                    <i class='fas fa-file-pdf'></i> Preview
                                                                </a>
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

    <!-- Core JS Files -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#add-row').DataTable();
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
                    window.location.href = '{{ route('logout') }}'; // Redirect ke route logout
                }
            });
        }
    </script>
</body>

</html>
