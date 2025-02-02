<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drafting KAK - BAKTI</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon">

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}">

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

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        .accordion-item {
            margin-bottom: 10px;
        }

        .accordion-body {
            padding: 15px;
        }

        .box {
            border: 1px solid #ddd;
            padding: 30px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 1210px;
            margin: auto;
        }

        .box h5 {
            margin-bottom: 10px;
        }

        .box a {
            color: #007bff;
            text-decoration: none;
        }

        .box a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        @include('layouts.navbar_admin')
        <!-- End Sidebar -->

        <!-- Main Panel -->
        <div class="main-panel">
            <!-- Header -->
            @include('layouts.header_admin')

            <!-- FAQ Content -->
            <div class="container">
                <div class="page-inner text">
                    <h2 class="text-center">Frequently Asked Questions</h2>
                    <br>
                    <section class="faq-section">
                        <div class="faq__container">
                            <span class="text-center d-block m-auto">Selamat datang di halaman bantuan. Di sini Anda
                                akan menemukan informasi tentang cara menggunakan aplikasi.</span>
                            <br>

                            <!-- Accordion FAQ -->
                            <style>
                                .custom-font-size {
                                    font-size: 16px;
                                    /* Ubah ukuran sesuai keinginan */
                                }
                            </style>

                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button custom-font-size" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true"
                                            aria-controls="collapseOne">
                                            Bagaimana cara menambahkan pengguna baru ke sistem?
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body custom-font-size">
                                            Untuk menambahkan pengguna baru ke sistem, pergi ke bagian "Users", klik
                                            "Tambah Pengguna", dan isi data yang diperlukan seperti Nama, Email, NIK,
                                            Role, Password, dan Kategori.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed custom-font-size" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                            aria-expanded="false" aria-controls="collapseTwo">
                                            Bagaimana cara mengelola kategori pemilik program (PP)?
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse"
                                        aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                        <div class="accordion-body custom-font-size">
                                            Di bagian Kategori Pemilik Program (PP), klik Tambah Kategori, isi detail
                                            yang diperlukan, seperti Nama Kategori Divisi, dan lalu simpan
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button class="accordion-button collapsed custom-font-size" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                            aria-expanded="false" aria-controls="collapseThree">
                                            Bagaimana cara melihat laporan aktivitas pengguna?
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse"
                                        aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                        <div class="accordion-body custom-font-size">
                                            Di bagian "Laporan" pada sidebar, Anda dapat melihat aktivitas pengguna
                                            terkait pembuatan, pengajuan, dan persetujuan KAK.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingFour">
                                        <button class="accordion-button collapsed custom-font-size" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                            aria-expanded="false" aria-controls="collapseFour">
                                            Bagaimana cara mengubah informasi pengguna yang sudah ada?
                                        </button>
                                    </h2>
                                    <div id="collapseFour" class="accordion-collapse collapse"
                                        aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                        <div class="accordion-body custom-font-size">
                                            Untuk mengubah informasi pengguna yang sudah ada, buka menu "Users", cari
                                            pengguna yang ingin diubah, lalu klik "Edit" di sebelah nama pengguna
                                            tersebut. Setelah itu, Anda bisa memperbarui data yang diperlukan dan
                                            menyimpannya. </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingFive">
                                        <button class="accordion-button collapsed custom-font-size" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseFive"
                                            aria-expanded="false" aria-controls="collapseFive">
                                            Bagaimana cara menghapus pengguna dari sistem?
                                        </button>
                                    </h2>
                                    <div id="collapseFive" class="accordion-collapse collapse"
                                        aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                        <div class="accordion-body custom-font-size">
                                            Untuk menghapus pengguna, buka menu "Users", cari pengguna yang ingin
                                            dihapus, lalu klik "Hapus". Pastikan Anda mengonfirmasi tindakan ini karena
                                            penghapusan bersifat permanen. </div>
                                    </div>
                                </div>
                                <!-- End Accordion -->


                                <!-- Contact Support Box -->
                                <div class="contact-support box">
                                    <h5><strong>Contact Support</strong></h5>
                                    <h6>Jika Anda memerlukan bantuan lebih lanjut, silakan hubungi tim dukungan kami:
                                    </h6>
                                    <ul>
                                        <li>
                                            <h6><strong>Email:</strong> <a
                                                    href="mailto:halobakti@baktikominfo.id">support@baktikominfo.id</a>
                                            </h6>
                                        </li>
                                        <li>
                                            <h6><strong>Phone:</strong> <a href="tel:(021) 31936590 ">(021) 31936590
                                                </a></h6>
                                        </li>
                                    </ul>
                                </div>
                                <!-- End Contact Support -->

                                <!-- CSS untuk membuat kotak -->
                                <style>
                                    .box {
                                        border: 1px solid #ddd;
                                        /* Border dengan warna abu-abu */
                                        padding: 30px;
                                        /* Jarak antara konten dan border */
                                        background-color: #f9f9f9;
                                        /* Warna latar belakang */
                                        border-radius: 8px;
                                        /* Ujung kotak dibuat melengkung */
                                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                                        /* Memberikan efek shadow */
                                        max-width: 1210px;
                                        /* Maksimal lebar kotak */
                                        margin: auto;
                                        /* Membuat kotak berada di tengah */
                                    }

                                    .box h5 {
                                        margin-bottom: 10px;
                                        /* Jarak bawah heading */
                                    }

                                    .box a {
                                        color: #007bff;
                                        /* Warna tautan */
                                        text-decoration: none;
                                        /* Menghilangkan garis bawah pada tautan */
                                    }

                                    .box a:hover {
                                        text-decoration: underline;
                                        /* Tautan bergaris bawah saat di-hover */
                                    }
                                </style>

                            </div>
                    </section>
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
    <script src="{{ asset('assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
    <script>
        function logoutConfirm(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be logged out.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, logout!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
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
