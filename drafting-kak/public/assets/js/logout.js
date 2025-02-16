
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