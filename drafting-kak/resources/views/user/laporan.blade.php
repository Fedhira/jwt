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
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
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
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <div>
                            <h3 class="fw-bold mb-3">Laporan</h3>
                        </div>
                    </div>

                    <!-- New row for the 3 cards -->
                    <div class="row mt-4">
                        <div class="col-sm-4 col-md-4">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-warning bubble-shadow-small">
                                                <i class="fas fa-hourglass-half"></i>
                                            </div>
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="numbers">
                                                <p class="card-category">Pending</p>
                                                <h4 class="card-title">{{ $totalPending }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-md-4">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-success bubble-shadow-small">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="numbers">
                                                <p class="card-category">Disetujui</p>
                                                <h4 class="card-title">{{ $totalDisetujui }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-4 col-md-4">
                            <div class="card card-stats card-round">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-icon">
                                            <div class="icon-big text-center icon-danger bubble-shadow-small">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </div>
                                        </div>
                                        <div class="col col-stats ms-3 ms-sm-0">
                                            <div class="numbers">
                                                <p class="card-category">Ditolak</p>
                                                <h4 class="card-title">{{ $totalDitolak }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- New section for the table -->
                    <div class="col-md-12 mt-4">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-flex align-items-center">
                                    <!-- Date Picker From and To -->
                                    <form method="GET" action="{{ route('user.laporan') }}">
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
                                            <a href="{{ route('user.laporan') }}" class="btn btn-danger btn-round me-2"
                                                style="width: 167px;">Clear</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- START TABLE -->
                                <div class="table-responsive">
                                    <table id="add-row" class="display table table-striped table-hover table-center">
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
                                            @forelse ($drafts as $draft)
                                                <tr>
                                                    <td>{{ $draft->no_doc_mak }}</td>
                                                    <td>{{ $draft->judul }}</td>
                                                    <td>{{ $draft->kategori->nama_divisi ?? 'Tidak Diketahui' }}</td>
                                                    <td>
                                                        @if ($draft->status === 'disetujui')
                                                            <span class="status status-disetujui">Disetujui</span>
                                                        @elseif ($draft->status === 'pending')
                                                            <span class="status status-pending">Pending</span>
                                                        @elseif ($draft->status === 'ditolak')
                                                            <span class="status status-ditolak">Ditolak</span>
                                                        @elseif ($draft->status === 'draft')
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
                                                                style='width:130px;'>
                                                                <i class='fas fa-file-pdf'></i> Preview
                                                            </a>
                                                            <a href="{{ route('user.downloadWord', ['id' => $draft->kak_id]) }}"
                                                                class="btn btn-dark btn-round me-2"
                                                                style="width:130px;">
                                                                <i class="fas fa-download"></i> WORD
                                                            </a>

                                                            <a href="{{ route('user.downloadPdf', ['id' => $draft->kak_id]) }}"
                                                                target="_blank" class="btn btn-warning btn-round me-2"
                                                                style="width:130px;">
                                                                <i class="fa fa-download"></i> PDF
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

    <!-- Datatables -->
    <script src="{{ asset('assets/js/plugin/datatables/datatables.min.js') }}"></script>

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
        });

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
                    window.location.href = '{{ route('logout') }}';
                }
            });
        }
    </script>
</body>

</html>
