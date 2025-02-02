<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}" />
    <title>Drafting KAK - BAKTI</title>
    <link rel="icon" href="{{ asset('assets/img/kaiadmin/favicon.ico') }}" type="image/x-icon" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />

    <!-- SweetAlert -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }

        .form-container {
            width: 100%;
            max-width: 400px;
        }

        .toggle-container {
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>

<body>
    <div class="container" id="container">
        <div class="form-container sign-up">
            <form></form>
        </div>
        <div class="form-container sign-in">
            <form action="controllers/usercontroller.php" method="POST">
                <h1>Sign In</h1>
                <div class="social-icons"></div>

                <div class="input-container">
                    <i class="fa fa-envelope fa-icon"></i>
                    <input type="email" name="email" id="inputEmail" placeholder="Email" required />
                </div>

                <div class="input-container">
                    <i class="fa fa-lock fa-icon"></i>
                    <input type="password" name="password" placeholder="Password" id="inputPassword" required />
                    <i class="fas fa-eye-slash eye-icon" id="togglePassword"></i>
                </div>

                <a href="#" style="font-weight: bold; text-decoration: underline; float: right"
                    data-toggle="modal" data-target="#lupaRowModal">Lupa Password</a>
                <button type="submit" name="login">Login</button>
            </form>
        </div>

        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left"></div>
                <div class="toggle-panel toggle-right">
                    <img src="{{ asset('assets/img/kaiadmin/logo_bakti.png') }}" alt="Logo BAKTI" class="logo-image" />
                    <h1>Drafting KAK</h1>
                    <h3>(Kerangka Acuan Kerja)</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Lupa Password -->
    <div class="modal fade" id="lupaRowModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">
                        <span class="fw-mediumbold">Lupa Password</span>
                    </h5>
                    <button type="button" class="close" data-dismiss=" modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                    <form method="POST" action="/login">
                        @csrf
                        <div class="form-group form-group-default">
                            <label for="email">Masukkan email anda</label>
                            <input type="email" class="form-control" name="email" id="email"
                                placeholder="example@gmail.com" required />
                        </div>
                        <div class="form-group form-group-default">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" id="password" required />
                        </div>
                    </form>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-primary">Kirim</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        const togglePassword = document.querySelector("#togglePassword");
        const password = document.querySelector("#inputPassword");

        togglePassword.addEventListener("click", function() {
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash");
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get('status');

            if (status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Login Berhasil!',
                    text: 'Anda berhasil masuk.',
                    confirmButtonText: 'OK'
                });
            } else if (status === 'error') {
                Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal!',
                    text: 'Email atau password salah. Silakan coba lagi.',
                    confirmButtonText: 'OK'
                });
            }
        });
    </script>
</body>

</html>
