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
                        <h3 class="fw-bold mb-3">Kelola User</h3>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center">
                                        <!-- Tombol Tambah User -->
                                        <button class="btn btn-primary btn-round me-4" data-bs-toggle="modal"
                                            data-bs-target="#addRowModal" style="width: 167px;">
                                            <i class="fa fa-user-plus"></i>
                                            Tambah User
                                        </button>

                                        <!-- Form Filter -->
                                        <form method="GET" action="{{ route('admin.users') }}"
                                            class="d-flex align-items-center">
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
                                                style="width: 167px;">
                                                Filter
                                            </button>
                                            <a href="{{ route('admin.users') }}" class="btn btn-danger btn-round me-2"
                                                style="width: 167px;">
                                                Clear
                                            </a>
                                        </form>
                                    </div>
                                </div>


                                <!-- Modal Tambah -->
                                <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title">
                                                    <span class="fw-mediumbold">Tambah User</span>
                                                </h5>
                                                <button type="button" class="close" data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('admin.users.store') }}">
                                                    @csrf <!-- Tambahkan CSRF token -->
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group form-group-default">
                                                                <label>Nama</label>
                                                                <input type="text" name="username"
                                                                    class="form-control" placeholder="Nama Lengkap"
                                                                    required />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group form-group-default">
                                                                <label>Email</label>
                                                                <input type="email" name="email"
                                                                    class="form-control" placeholder="Email" required />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group form-group-default">
                                                                <label>Role</label>
                                                                <select name="role" class="form-control" required>
                                                                    <option value="">Pilih Role</option>
                                                                    <option value="admin">Admin</option>
                                                                    <option value="staff">Staff</option>
                                                                    <option value="supervisor">Supervisor</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group form-group-default">
                                                                <label>NIK</label>
                                                                <input type="text" name="nik"
                                                                    class="form-control" placeholder="NIK" required />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group form-group-default">
                                                                <label>Password</label>
                                                                <input type="password" name="password"
                                                                    class="form-control" placeholder="Password"
                                                                    required />
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <div class="form-group form-group-default">
                                                                <label>Kategori</label>
                                                                <select name="kategori" class="form-control" required>
                                                                    <option value="">Pilih Kategori</option>
                                                                    @foreach ($kategoris as $kategori)
                                                                        <option value="{{ $kategori->id }}">
                                                                            {{ $kategori->nama_divisi }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="submit" class="btn btn-primary">Tambah</button>
                                                        <button type="button" class="btn btn-danger"
                                                            data-bs-dismiss="modal">Batal</button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Ubah -->
                                <div class="modal fade" id="editRowModal" tabindex="-1" role="dialog"
                                    aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header border-0">
                                                <h5 class="modal-title">
                                                    <span class="fw-mediumbold"> Edit User</span>
                                                </h5>
                                                <button type="button" class="close" data-bs-dismiss="modal"
                                                    aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST"
                                                    action="{{ route('admin.users.update', ':id') }}"
                                                    id="editUserForm">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group form-group-default">
                                                                <input type="hidden" name="user_id"
                                                                    id="editUserId" />
                                                                <label>Nama</label>
                                                                <input type="text" name="username"
                                                                    id="editUsername" class="form-control"
                                                                    placeholder="fill name" required />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group form-group-default">
                                                                <label>Email</label>
                                                                <input type="email" name="email" id="editEmail"
                                                                    class="form-control" placeholder="fill email"
                                                                    required />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group form-group-default">
                                                                <label for="role">Role</label>
                                                                <select id="editRole" name="role"
                                                                    class="form-control" required>
                                                                    <option value="">Pilih Role</option>
                                                                    <option value="admin">Admin</option>
                                                                    <option value="staff">Staff</option>
                                                                    <option value="supervisor">Supervisor</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <div class="form-group form-group-default">
                                                                <label>NIK</label>
                                                                <input type="text" name="nik" id="editNIK"
                                                                    class="form-control" placeholder="fill NIK"
                                                                    required />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group form-group-default">
                                                                <label>Kategori</label>
                                                                <select name="kategori_id" id="editKategori"
                                                                    class="form-control" required>
                                                                    <option value="">Select Kategori</option>
                                                                    @foreach ($kategoris as $kategori)
                                                                        <option value="{{ $kategori->id }}">
                                                                            {{ $kategori->nama_divisi }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                        <button type="button" class="btn btn-danger"
                                                            data-bs-dismiss="modal">Batal</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="user-table" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Nama</th>
                                                    <th>Email</th>
                                                    <th>NIK</th>
                                                    <th>Role</th>
                                                    <th>Kategori</th>
                                                    <th>Tanggal Dibuat</th>
                                                    <th>Tanggal Diperbarui</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($users as $user)
                                                    <tr>
                                                        <td>{{ $user->username }}</td>
                                                        <td>{{ $user->email }}</td>
                                                        <td>{{ $user->nik }}</td>
                                                        <td>{{ $user->role }}</td>
                                                        <td>{{ $user->kategori->nama_divisi ?? '-' }}</td>
                                                        <td>{{ $user->created_at ? $user->created_at->format('d-m-Y H:i:s') : '-' }}
                                                        </td>
                                                        <td>{{ $user->updated_at ? $user->updated_at->format('d-m-Y H:i:s') : '-' }}
                                                        </td>
                                                        <td>
                                                            <div class='form-button-action'>
                                                                <button class="btn btn-warning btn-round me-2"
                                                                    style="width: 100px"
                                                                    onclick="populateEditModal({{ $user }})"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editRowModal">
                                                                    <i class="fa fa-edit"></i> Edit
                                                                </button>
                                                                <button class="btn btn-danger btn-round"
                                                                    style="width: 100px"
                                                                    onclick="confirmDelete({{ $user->id }})">
                                                                    <i class="fa fa-trash"></i> Hapus
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
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
    <script src="{{ asset('assets/js/logout.js') }}"></script>

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
        $(document).ready(function() {
            $('#user-table').DataTable();
        });

        function populateEditModal(user) {
            $('#editUserId').val(user.id);
            $('#editUsername').val(user.username);
            $('#editEmail').val(user.email);
            $('#editRole').val(user.role);
            $('#editNIK').val(user.nik);
            $('#editKategori').val(user.kategori_id);
        }

        function confirmDelete(userId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `{{ route('admin.users.destroy', ':id') }}`.replace(':id',
                            userId), // URL Laravel Route
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}' // CSRF token untuk proteksi
                        },
                        success: function(response) {
                            Swal.fire('Deleted!', response.message, 'success').then(() => location
                                .reload());
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', 'Failed to delete user.', 'error');
                        }
                    });
                }
            });
        }
    </script>

    <script>
        document.getElementById('editUserForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah submit default
            const form = e.target;
            const userId = document.getElementById('editUserId').value;
            form.action = form.action.replace(':id', userId);
            form.submit(); // Submit form dengan action baru
        });
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



</body>

</html>
