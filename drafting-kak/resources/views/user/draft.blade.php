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
                urls: ["{{ asset('assets/css/fonts.min.css') }}"]
            },
            active: function() {
                sessionStorage.fonts = true;
            }
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
        @include('layouts.navbar_user')
        <!-- End Sidebar -->

        <div class="main-panel">
            @include('layouts.header_user')

            <div class="container">
                <div class="page-inner">
                    <div class="page-header">
                        <h3 class="fw-bold mb-3">Draft KAK</h3>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('user.add_draft') }}">
                                            <button class="btn btn-primary btn-round me-4" style="width: 167px;">
                                                <i class="fa fa-user-plus"></i>
                                                Tambah KAK
                                            </button>
                                        </a>

                                        <!-- Date Picker From and To -->
                                        <form method="GET" action="{{ route('user.draft') }}">
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
                                                <a href="{{ route('user.draft') }}"
                                                    class="btn btn-danger btn-round me-2"
                                                    style="width: 167px;">Clear</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <!-- START TABLE -->
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
                                                            @if ($draft->status === 'draft')
                                                                <span class="status status-draft">Draft</span>
                                                            @else
                                                                <span class="status status-unknown">Unknown</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $draft->created_at->format('d-m-Y H:i') }}</td>
                                                        <td>{{ $draft->updated_at->format('d-m-Y H:i') }}</td>
                                                        <td>
                                                            <div class="form-button-action button-group d-inline-flex">
                                                                <a href="{{ route('user.previewPdf', ['id' => $draft->kak_id]) }}"
                                                                    target="_blank" class='btn btn-dark btn-round me-2'
                                                                    style='width: 110px;'>
                                                                    <i class='fas fa-file-pdf'></i> Preview
                                                                </a>
                                                                <a href="{{ route('user.upload_draft', $draft->kak_id) }}"
                                                                    class="btn btn-secondary btn-round me-2"
                                                                    style="width: 110px;">
                                                                    <i class="fas fa-upload"></i> Upload
                                                                </a>
                                                                <a href="{{ route('user.edit_draft', $draft->kak_id) }}"
                                                                    class="btn btn-warning btn-round me-2"
                                                                    style="width: 110px;">
                                                                    <i class="fa fa-edit"></i> Ubah
                                                                </a>
                                                                <form id="deleteForm-{{ $draft->kak_id }}"
                                                                    action="{{ route('user.draft.destroy', $draft->kak_id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                                <button type="button" class="btn btn-danger btn-round"
                                                                    style="width: 110px;"
                                                                    onclick="confirmDelete({{ $draft->kak_id }})">
                                                                    <i class="fa fa-trash"></i> Hapus
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center text-muted">Tidak ada
                                                            data draft ditemukan.</td>
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

    <!--   Core JS Files   -->
    <script src="{{ asset('assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>

    <!-- jQuery Sparkline -->
    <script src="{{ asset('assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>

    <!-- Sweet Alert -->
    <script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>

    <!-- Kaiadmin JS -->
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>

    <script>
        function confirmDelete(draftId) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Draft akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm-' + draftId).submit();
                }
            });
        }
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @elseif (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif
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
</body>

</html>
