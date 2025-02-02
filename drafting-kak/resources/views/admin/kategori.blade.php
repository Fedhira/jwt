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
                        <h3 class="fw-bold mb-3">Kelola Kategori Program</h3>
                    </div>
                    <div class="row">

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <button class="btn btn-primary btn-round me-4" data-bs-toggle="modal"
                                            data-bs-target="#addRowModal">
                                            <i class="fa fa-plus"></i>
                                            Tambah Kategori
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">

                                    <!-- Modal Tambah -->
                                    <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title">
                                                        <span class="fw-mediumbold"> Tambah Kategori Divisi</span>
                                                    </h5>
                                                    <button type="button" class="close" data-bs-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST" action="{{ route('admin.kategori.store') }}">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="form-group form-group-default">
                                                                    <label>Nama Kategori Divisi</label>
                                                                    <input type="text" name="nama_divisi"
                                                                        class="form-control"
                                                                        placeholder="Masukkan nama kategori" required />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group form-group-default">
                                                                    <label>Status</label>
                                                                    <select name="status" class="form-control"
                                                                        required>
                                                                        <option value="">Pilih Status</option>
                                                                        <option value="plt">PLT</option>
                                                                        <option value="definitif">Definitif</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0">
                                                            <button type="submit"
                                                                class="btn btn-primary">Tambah</button>
                                                            <button type="button" class="btn btn-danger"
                                                                data-bs-dismiss="modal">Batal</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="editRowModal" tabindex="-1" role="dialog"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title"><span class="fw-mediumbold">Edit
                                                            Kategori</span></h5>
                                                    <button type="button" class="close" data-bs-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form method="POST"
                                                        action="{{ route('admin.kategori.update', ':id') }}"
                                                        id="editKategoriForm">
                                                        @csrf
                                                        <input type="hidden" name="kategori_id" id="editKategoriId" />
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="form-group form-group-default">
                                                                    <label>Nama Kategori Divisi</label>
                                                                    <input id="editNamaDivisi" type="text"
                                                                        name="nama_divisi" class="form-control"
                                                                        placeholder="Isi nama kategori" required />
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group form-group-default">
                                                                    <label for="editStatus">Status</label>
                                                                    <select id="editStatus" name="status"
                                                                        class="form-control" required>
                                                                        <option value="plt">PLT</option>
                                                                        <option value="definitif">Definitif</option>
                                                                    </select>
                                                                </div>
                                                            </div>
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
                                                    <th>Nama Kategori Divisi</th>
                                                    <th>Status</th>
                                                    <th>Jumlah User</th>
                                                    <th style="width: 10%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Nama Kategori Divisi</th>
                                                    <th>Status</th>
                                                    <th>Jumlah User</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </tfoot>
                                            <tbody>
                                                @forelse ($kategoris as $kategori)
                                                    <tr>
                                                        <td>{{ $kategori->nama_divisi }}</td>
                                                        <td>{{ ucfirst($kategori->status) }}</td>
                                                        <td>{{ $kategori->users_count }}</td>
                                                        <td>
                                                            <div class="form-button-action">
                                                                <button class="btn btn-warning btn-round me-2"
                                                                    style="width: 100px"
                                                                    onclick="populateEditModal({{ $kategori }})"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editRowModal">
                                                                    <i class="fa fa-edit"></i> Edit
                                                                </button>

                                                                <button class="btn btn-danger btn-round"
                                                                    style="width: 100px"
                                                                    onclick="confirmDeleteKategori({{ $kategori->id }})">
                                                                    <i class="fa fa-trash"></i> Hapus
                                                                </button>

                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">Tidak ada data kategori
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

    <!-- Scripts -->
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
        function populateEditModal(kategori) {
            $('#editKategoriId').val(kategori.id);
            $('#editNamaDivisi').val(kategori.nama_divisi);
            $('#editStatus').val(kategori.status);

            // Ubah action form dengan ID kategori
            const formAction = `{{ route('admin.kategori.update', ':id') }}`.replace(':id', kategori.id);
            $('#editKategoriForm').attr('action', formAction);
        }
    </script>

    <script>
        // Jika ada flash message
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                showConfirmButton: false,
                timer: 3000
            });
        @endif
    </script>

    <script>
        function confirmDeleteKategori(kategoriId) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Kategori ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ route('admin.kategori.destroy', ':id') }}`.replace(':id', kategoriId),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}' // CSRF token untuk proteksi
                        },
                        success: function(response) {
                            Swal.fire('Berhasil!', response.message, 'success').then(() => location
                                .reload());
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', 'Gagal menghapus kategori.', 'error');
                        }
                    });
                }
            });
        }
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
