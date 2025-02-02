<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drafting KAK - BAKTI</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon">

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

    <!-- Load Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- SweetAlert -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/kaiadmin.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}">

    <!-- Custom CSS -->
    <style>
        .accordion-item {
            margin-bottom: 10px;
        }

        .accordion-body {
            padding: 15px;
        }

        .btn {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            display: inline-block;
            text-align: center;
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
        @include('layouts.navbar_supervisor')

        <!-- Main Panel -->
        <div class="main-panel">
            <!-- Header -->
            <div class="main-header">
                @include('layouts.header_supervisor')

                <!-- Navbar Header -->
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">
                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                                    aria-expanded="false">
                                    <div class="avatar-sm">
                                        <img src="{{ asset('assets/img/user.png') }}" alt="..."
                                            class="avatar-img rounded-circle">
                                    </div>
                                    <span class="profile-username">
                                        <span class="op-7">Welcome,</span>
                                        <span class="fw-bold">{{ Auth::user()->name ?? 'User' }}</span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-user animated fadeIn">
                                    <div class="dropdown-user-scroll scrollbar-outer">
                                        <li>
                                            <div class="user-box">
                                                <div class="u-text">
                                                    <h4>{{ Auth::user()->name ?? '' }}</h4>
                                                    <p class="text-muted">{{ Auth::user()->email ?? '' }}</p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('faq') }}">FAQ</a>
                                        </li>
                                    </div>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>

            <!-- Content -->
            <div class="container">
                <div class="page-inner text">
                    <h2 class="text-center">Frequently Asked Questions</h2>
                    <section class="faq-section">
                        <div class="faq__container">
                            <span class="text-center d-block m-auto">Selamat datang di halaman bantuan. Di sini Anda
                                akan menemukan informasi tentang cara menggunakan aplikasi.</span>

                            <!-- Accordion FAQ -->
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button custom-font-size" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true"
                                            aria-controls="collapseOne">
                                            Bagaimana cara melihat daftar KAK yang sudah dibuat?
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body custom-font-size">
                                            Buka menu Daftar KAK di sidebar untuk melihat semua KAK yang telah dibuat.
                                            Di sana, Anda akan melihat semua KAK yang tersedia beserta detailnya.
                                        </div>
                                    </div>
                                </div>
                                <!-- Additional FAQ items here -->
                            </div>

                            <!-- Contact Support -->
                            <div class="box">
                                <h5><strong>Contact Support</strong></h5>
                                <h6>Jika Anda memerlukan bantuan lebih lanjut, silakan hubungi tim dukungan kami:</h6>
                                <ul>
                                    <li><strong>Email:</strong> <a
                                            href="mailto:support@baktikominfo.id">support@baktikominfo.id</a></li>
                                    <li><strong>Phone:</strong> <a href="tel:(021) 31936590">(021) 31936590</a></li>
                                </ul>
                            </div>
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
    <script src="{{ asset('assets/js/kaiadmin.min.js') }}"></script>
    <script>
        function logoutConfirm(event) {
            event.preventDefault(); // Mencegah aksi default

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
                    // Ambil token dari localStorage
                    const token = localStorage.getItem("token");

                    if (!token) {
                        // Jika tidak ada token, langsung redirect ke login
                        window.location.href = "/login";
                        return;
                    }

                    // Panggil API logout
                    fetch("http://127.0.0.1:8000/api/auth/logout", {
                            method: "POST",
                            headers: {
                                "Authorization": "Bearer " + token,
                                "Content-Type": "application/json"
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log(data);

                            // Hapus token dari localStorage
                            localStorage.removeItem("token");

                            // Redirect ke halaman login setelah logout
                            window.location.href = "/login";
                        })
                        .catch(error => {
                            console.error("Error:", error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Failed to logout! Please try again.'
                            });
                        });
                }
            });
        }
    </script>
</body>

</html>
