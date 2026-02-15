<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - VibeNestle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2b6cb0;
            --secondary-color: #4c51bf;
            --accent-color: #ed64a6;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f6f9fc 0%, #f1f5f9 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .about-section {
            padding: 5rem 0;
            background: linear-gradient(135deg, rgba(43, 108, 176, 0.1) 0%, rgba(76, 81, 191, 0.1) 100%);
        }

        .about-image {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 100%;
            height: auto;
        }

        .footer {
            background: linear-gradient(45deg, #1a1a1a, #2d2d2d);
            color: white;
            padding: 2rem 0;
            margin-top: auto;
        }

        /* About.php-specific footer tweaks: make headings and items horizontal and compact on small screens */
        .site-footer .row.g-4 {
            display: flex;
            gap: 0.75rem;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .site-footer .col-lg-4 {
            flex: 1 1 33%;
            min-width: 120px;
        }

        .site-footer ul {
            display: flex;
            gap: 0.5rem;
            padding: 0;
            margin: 0;
            list-style: none;
            flex-wrap: nowrap;
            overflow: hidden;
        }

        .site-footer ul li {
            margin: 0;
            padding: 0;
            font-size: 0.9rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .site-footer h5 {
            font-size: 1rem;
            margin-bottom: 0.35rem;
            white-space: nowrap;
        }

        .site-footer p, .site-footer .text-white-50 {
            font-size: 0.95rem;
            margin: 0;
        }

        .site-footer .d-flex a {
            font-size: 1rem;
        }

        @media (max-width: 768px) {
            .site-footer .row.g-4 {
                flex-wrap: nowrap;
            }

            .site-footer .col-lg-4 {
                flex: 1 1 0;
                min-width: 0;
            }

            .site-footer h5 {
                font-size: 0.9rem;
            }

            .site-footer ul li {
                font-size: 0.8rem;
            }

            .site-footer p, .site-footer .text-white-50 {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .site-footer h5 {
                font-size: 0.82rem;
            }

            .site-footer ul li {
                font-size: 0.7rem;
            }

            .site-footer p, .site-footer .text-white-50 {
                font-size: 0.72rem;
            }
        }

        .dark-mode {
            background: #1a1a1a;
            color: #f1f1f1;
        }

        .dark-mode .about-section {
            background: linear-gradient(135deg, rgba(43, 108, 176, 0.05) 0%, rgba(76, 81, 191, 0.05) 100%);
        }

        .dark-mode .card {
            background: #2d2d2d;
            color: #f1f1f1;
        }

        .hover-white:hover {
            color: #ffffff !important;
            transition: color 0.3s ease;
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 0.3rem 0.5rem !important;
            }

            .navbar .container {
                display: flex;
                align-items: center;
                flex-wrap: nowrap;
                gap: 0.25rem;
            }

            .navbar-toggler {
                display: none !important;
            }

            .navbar-brand {
                font-size: 0.95rem;
                margin-right: auto !important;
                white-space: nowrap;
                flex-shrink: 0;
            }

            .navbar-collapse {
                display: flex !important;
                flex-wrap: nowrap !important;
                flex-basis: auto;
                margin-left: 0;
            }

            .navbar-nav {
                flex-direction: row !important;
                gap: 0.15rem !important;
                flex-wrap: nowrap !important;
                align-items: center;
                margin-left: auto;
            }

            .nav-link {
                font-size: 0.7rem;
                padding: 0.15rem 0.25rem !important;
                margin: 0 !important;
                white-space: nowrap;
            }

            .nav-link i {
                display: none;
            }

            .btn-outline-primary {
                padding: 0.25rem 0.4rem;
                font-size: 0.7rem;
                margin: 0 !important;
                flex-shrink: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-music me-2"></i>VibeNestle
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item me-2">
                        <button id="darkModeToggle" class="btn btn-outline-primary">
                            <i class="fas fa-moon"></i>
                        </button>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="artists.php">Artists</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="about.php">About</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- About Section -->
    <section class="about-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <img src="https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=800" alt="About Us" class="about-image">
                </div>
                <div class="col-md-6">
                    <h1 class="mb-4">About VibeNestle</h1>
                    <p class="lead mb-4">Your premier destination for discovering and downloading quality music.</p>
                    <p>At VibeNestle, we believe that music has the power to connect, inspire, and transform lives. Our platform is designed to provide music lovers with seamless access to their favorite tracks while supporting artists in sharing their creative works with the world.</p>
                    <div class="row mt-5">
                        <div class="col-6 mb-4">
                            <h4><i class="fas fa-music me-2 text-primary"></i>10K+</h4>
                            <p>Songs Available</p>
                        </div>
                        <div class="col-6 mb-4">
                            <h4><i class="fas fa-users me-2 text-primary"></i>5K+</h4>
                            <p>Active Users</p>
                        </div>
                        <div class="col-6">
                            <h4><i class="fas fa-star me-2 text-primary"></i>4.8</h4>
                            <p>User Rating</p>
                        </div>
                        <div class="col-6">
                            <h4><i class="fas fa-download me-2 text-primary"></i>1M+</h4>
                            <p>Downloads</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-bullseye fa-3x text-primary mb-3"></i>
                            <h3>Our Mission</h3>
                            <p>To make quality music accessible to everyone while supporting artists in their creative journey.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-eye fa-3x text-primary mb-3"></i>
                            <h3>Our Vision</h3>
                            <p>To become the world's leading platform for music discovery and distribution.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-heart fa-3x text-primary mb-3"></i>
                            <h3>Our Values</h3>
                            <p>Quality, Accessibility, Innovation, and Support for Artists.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer site-footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 mb-3">
                    <h5 class="text-white"><i class="fas fa-music me-2"></i>VibeNestle</h5>
                    <p class="text-white-50">Your one-stop destination for discovering and downloading quality music.</p>
                </div>
                <div class="col-lg-4 mb-3">
                    <h5 class="text-white">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="index.php" class="text-white-50 text-decoration-none hover-white">Home</a></li>
                        <li class="mb-2"><a href="artists.php" class="text-white-50 text-decoration-none hover-white">Artists</a></li>
                        <li><a href="about.php" class="text-white-50 text-decoration-none hover-white">About</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5 class="text-white">Connect With Us</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white-50 fs-4 hover-white"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-white-50 fs-4 hover-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white-50 fs-4 hover-white"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4" style="border-color: #444;">
            <div class="text-center">
                <p class="text-white-50">&copy; <?= date('Y') ?> VibeNestle. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Dark mode toggle
        document.getElementById('darkModeToggle').addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            const icon = this.querySelector('i');
            if (document.body.classList.contains('dark-mode')) {
                icon.classList.remove('fa-moon');
                icon.classList.add('fa-sun');
                localStorage.setItem('darkMode', 'enabled');
            } else {
                icon.classList.remove('fa-sun');
                icon.classList.add('fa-moon');
                localStorage.setItem('darkMode', 'disabled');
            }
        });

        // Check dark mode preference on load
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark-mode');
            const icon = document.querySelector('#darkModeToggle i');
            icon.classList.remove('fa-moon');
            icon.classList.add('fa-sun');
        }
    </script>
</body>
</html>