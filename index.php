<?php
// index.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Search & Download - VibeNestle</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2b6cb0;
            --secondary-color: #4c51bf;
            --accent-color: #ed64a6;
            --light-color: #f7fafc;
            --dark-color: #1a202c;
            --card-bg: #ffffff;
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f6f9fc 0%, #f1f5f9 100%);
            color: var(--dark-color);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar Styling */
        .navbar {
            background: url('https://images.unsplash.com/photo-1511379936541-1b2e13e306d8?q=80&w=2070&auto=format&fit=crop') no-repeat center center;
            background-size: cover;
            background-color: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 0.5rem 1rem;
        }

        .navbar-toggler {
            display: none !important;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color);
            transition: var(--transition);
        }

        .navbar-brand:hover {
            color: var(--secondary-color);
        }

        .nav-link {
            font-weight: 500;
            color: var(--dark-color);
            margin: 0 0.25rem;
            transition: var(--transition);
            position: relative;
            padding: 0.3rem 0.6rem;
            border-radius: 5px;
            font-size: 0.95rem;
            white-space: nowrap;
        }

        .navbar-nav {
            flex-direction: row !important;
            align-items: center;
            gap: 0.25rem;
            flex-wrap: nowrap !important;
        }

        .collapse {
            display: flex !important;
        }

        .navbar-collapse {
            display: flex !important;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary-color);
            background: rgba(43, 108, 176, 0.1);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--primary-color);
            transition: var(--transition);
        }

        .nav-link:hover::after, .nav-link.active::after {
            width: 100%;
        }

        .dark-mode .nav-link {
            color: #f1f1f1;
        }

        .dark-mode .nav-link:hover, .dark-mode .nav-link.active {
            color: #4CAF50;
            background: rgba(76, 175, 80, 0.1);
        }

        .dark-mode .nav-link::after {
            background-color: #4CAF50;
        }

        /* Search Container */
        .search-container {
            padding: 1rem 0;
            max-width: 700px;
            margin: 0 auto;
        }

        .search-form {
            max-width: 500px;
            margin: 0 auto 15px;
            padding: 0 15px;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        h2 {
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            position: relative;
            display: inline-block;
            padding-bottom: 0.5rem;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50%;
            height: 3px;
            background-color: var(--accent-color);
            border-radius: 2px;
        }

        .song-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            background: #f8f9fa;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            margin-bottom: 15px;
            max-width: 100%;
        }

        .song-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .song-card img {
            height: 130px;
            object-fit: cover;
            width: 100%;
            cursor: pointer;
        }

        .song-card .card-body {
            padding: 8px;
            background: linear-gradient(145deg, #ffffff, #e6e6e6);
        }

        .song-card .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .song-card .card-title:hover {
            color: var(--primary-color);
        }

        .song-card .card-subtitle {
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .genre-badge {
            display: inline-block;
            background: #3498db;
            color: #fff;
            padding: 4px 8px;
            border-radius: 10px;
            font-size: 0.8rem;
            margin-bottom: 5px;
        }

        .upload-date {
            font-size: 0.8rem;
        }

        .audio-player {
            width: 100%;
            margin-top: 10px;
            border-radius: 5px;
        }

        .category-card {
            cursor: pointer;
            transition: transform 0.3s ease;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            height: 350px !important;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }

        .category-card:hover {
            transform: translateY(-5px);
        }

        .category-card img {
            transition: transform 0.3s ease;
            height: 350px !important;
        }

        .category-card:hover img {
            transform: scale(1.05);
        }

        .category-card h3 {
            font-size: 2.5rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 0;
            color: #718096;
        }

        .hero-image {
            margin-bottom: 2rem;
        }

        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
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

            .song-card img {
                height: 100px;
            }

            .song-card .card-title {
                font-size: 0.9rem;
            }

            .song-card .card-body {
                padding: 6px;
            }

            .genre-badge {
                font-size: 0.7rem;
            }

            h2 {
                font-size: 1.5rem;
            }

            .search-container {
                padding: 0.5rem 0;
            }

            /* Mobile Category Cards - One Line with Horizontal Scroll */
            .row.g-4 {
                display: flex !important;
                flex-wrap: nowrap;
                overflow-x: auto;
                scroll-behavior: smooth;
                padding-bottom: 10px;
                -webkit-overflow-scrolling: touch;
            }

            .row.g-4 > div {
                flex: 0 0 auto;
                width: 200px !important;
                min-width: 200px;
            }

            .category-card {
                height: 200px !important;
            }

            .category-card img {
                height: 200px !important;
            }

            .category-card h3 {
                font-size: 1.2rem;
                letter-spacing: 1px;
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .song-card {
            animation: fadeIn 0.5s ease forwards;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .song-detail-container {
            max-width: 900px;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }

        .song-detail-header {
            display: grid;
            grid-template-columns: minmax(200px, 1fr) 2fr;
            gap: 2rem;
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .song-detail-header img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }

        .song-detail-info {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .song-detail-info h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 0.25rem;
        }

        .song-detail-info p {
            font-size: 1rem;
            color: #4a5568;
            margin: 0.25rem 0;
        }

        .song-detail-info .genre-badge {
            font-size: 0.9rem;
            padding: 0.4rem 0.8rem;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
        }

        .counts {
            display: flex;
            gap: 1.5rem;
            margin: 0.75rem 0;
        }

        .counts small {
            font-size: 0.9rem;
            color: #4a5568;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .btn-download {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: transform 0.2s;
            border: none;
            margin-top: 0.75rem;
            max-width: fit-content;
            font-size: 0.95rem;
        }

        .btn-download:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
        }

        .related-songs {
            margin-top: 2rem;
            background: white;
            padding: 1.5rem;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .related-songs h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #1a202c;
        }

        .related-song-card {
            display: grid;
            grid-template-columns: 60px 1fr auto;
            gap: 1rem;
            padding: 0.75rem;
            background: #f8fafc;
            border-radius: 10px;
            margin-bottom: 0.75rem;
            align-items: center;
            transition: transform 0.2s;
        }

        .related-song-card:hover {
            transform: translateX(5px);
            background: #f1f5f9;
        }

        .related-song-card img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
        }

        
        .related-song-info h5 {
            font-size: 1rem;
            margin-bottom: 0.4rem;
            color: #1a202c;
            cursor: pointer;
        }

        .related-song-info small {
            color: #64748b;
            font-size: 0.85rem;
        }

        .audio-player {
            width: 100%;
            height: 35px;
            border-radius: 15px;
            margin: 0.75rem 0;
        }
        .metrics {
            margin-top: 5px;
        }
        @media (max-width: 768px) {
            .song-detail-header {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            .song-detail-header img {
                height: 150px;
            }
            .related-song-card {
                grid-template-columns: 50px 1fr;
            }
            .related-song-card audio {
                grid-column: 1 / -1;
            }
        }

        body.dark-mode {
            background: #1a1a1a;
            color: #f1f1f1;
        }

        .dark-mode .navbar {
            background-color: rgba(0, 0, 0, 0.95) !important;
        }

        .dark-mode .nav-link {
            color: #f1f1f1;
        }

        .dark-mode .card {
            background: #2d2d2d;
            color: #f1f1f1;
        }

        .dark-mode .card-body {
            background: #2d2d2d;
            color: #f1f1f1;
        }

        .dark-mode .text-muted {
            color: #d1d5db !important;
        }

        .dark-mode .song-card {
            background: #2d2d2d;
        }

        .dark-mode .card-title {
            color: #f1f1f1;
        }

        .dark-mode .feature-card {
            background: #2d2d2d !important;
            color: #f1f1f1;
        }

        .dark-mode .empty-state {
            color: #d1d5db;
        }

        .dark-mode .song-detail-header {
            background: #2d2d2d;
        }

        .dark-mode .song-detail-info p,
        .dark-mode .song-detail-info h1,
        .dark-mode .related-songs h3 {
            color: #f1f1f1 !important;
        }

        .dark-mode .related-songs {
            background: #2d2d2d;
        }

        .dark-mode .related-song-card {
            background: #1a1a1a;
        }

        .dark-mode .related-song-info h5 {
            color: #f1f1f1 !important;
        }

        .dark-mode .loading-overlay {
            background: rgba(0, 0, 0, 0.8);
        }

        .dark-mode .song-detail-info .counts small {
            color: #f1f1f1;
        }

        /* Footer Styling */
        .footer {
            background: linear-gradient(45deg, #1a1a1a, #2d2d2d);
            color: white;
            padding: 2rem 0;
            width: 100%;
            position: relative;
            bottom: 0;
            margin-top: auto;
        }

        .footer a {
            color: #ffffff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: #4CAF50;
            text-decoration: none;
        }

        .footer hr {
            border-color: #444;
            margin: 1.5rem 0;
        }

        .footer .text-muted {
            color: #ffffff !important;
            opacity: 0.8;
        }

        .dark-mode .footer {
            background: linear-gradient(45deg, #000000, #1a1a1a);
        }

        .trending-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .rank-number {
            font-size: 2rem;
            font-weight: bold;
            color: #28a745;
            margin-right: 1rem;
            text-align: center;
            width: 50px;
        }

        .trending-card img {
            max-width: 100px;
            max-height: 100px;
            object-fit: cover;
            border-radius: 8px;
        }

        .trending-card .card-title, .trending-card .card-text {
            color: #333;
        }

        .trending-card .metrics small {
            font-size: 0.9rem;
        }

        .dark-mode .trending-card {
            background: #2d2d2d;
            border-color: #444;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .dark-mode .trending-card .card-title,
        .dark-mode .trending-card .metrics small,
        .dark-mode .trending-card .card-text {
            color: #ffffff !important;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .dark-mode .trending-card .metrics {
            background: rgba(0, 0, 0, 0.2);
            padding: 8px;
            border-radius: 6px;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .trending-card {
                flex-direction: column;
                align-items: center;
            }
            .trending-card img {
                margin-bottom: 1rem;
            }
            .rank-number {
                font-size: 1.5rem;
                width: auto;
            }
        }
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            margin: 0.25rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-outline-primary:hover {
            transform: translateY(-2px);
        }

        .btn-outline-primary.active {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            transform: translateY(-2px);
        }

        .dark-mode .btn-outline-primary.active {
            background-color: #4CAF50;
            border-color: #4CAF50;
            color: white;
        }

        .dark-mode .btn-outline-primary {
            color: #4CAF50;
            border-color: #4CAF50;
        }

        .dark-mode .btn-outline-primary:hover {
            background-color: rgba(76, 175, 80, 0.1);
        }
        /* Mobile-only: keep the three feature cards on one line for phone screens */
        @media only screen and (max-width: 767px) {
            .features-inline-mobile {
                display: flex !important;
                flex-wrap: nowrap !important;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                gap: 0.5rem;
                padding-bottom: 6px;
            }

            .features-inline-mobile > div {
                flex: 0 0 33.3333% !important;
                max-width: 33.3333% !important;
                min-width: 33.3333% !important;
                box-sizing: border-box;
                padding-left: 0.35rem;
                padding-right: 0.35rem;
            }

            .features-inline-mobile .feature-card {
                padding: 0.8rem !important;
                height: 100% !important;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }

            .features-inline-mobile .feature-card h3 {
                font-size: 0.95rem !important;
                margin: 0.25rem 0 !important;
            }

            .features-inline-mobile .feature-card p {
                font-size: 0.75rem !important;
                margin: 0 !important;
            }
        }

    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="?page=home">
                <i class="fas fa-music me-2"></i>VibeNestle
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item me-2">
                        <button id="darkModeToggle" class="btn btn-outline-primary mt-2">
                            <i class="fas fa-moon"></i>
                        </button>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (!isset($_GET['page']) || $_GET['page'] === 'home') ? 'active' : ''; ?>" href="?page=home">
                            <i class="fas fa-home me-1"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo (isset($_GET['page']) && $_GET['page'] === 'trending') ? 'active' : ''; ?>" href="?page=trending">
                            <i class="fas fa-fire me-1"></i> Trending
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="artists.php">
                            <i class="fas fa-user me-1"></i> Artists
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">
                            <i class="fas fa-info-circle me-1"></i> About
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <?php
    // Database connection
    try {
        $conn = new PDO("mysql:host=127.0.0.1:3308;dbname=music_db", "username", "password");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        exit;
    }

    $page = isset($_GET['page']) ? $_GET['page'] : 'home';
    ?>

    <?php if ($page === 'home'): ?>
        <!-- Genre Filter -->
        <div class="container mt-4">
            <div class="d-flex justify-content-center gap-2 flex-wrap" id="genreFilters">
                <?php
                $stmt = $conn->prepare("SELECT DISTINCT genre FROM songs");
                $stmt->execute();
                $genres = $stmt->fetchAll(PDO::FETCH_COLUMN);

                echo '<button class="btn btn-outline-primary active" onclick="filterByGenre(\'all\')">All</button>';
                foreach($genres as $genre) {
                    echo '<button class="btn btn-outline-primary" onclick="filterByGenre(\'' . htmlspecialchars($genre) . '\')">' . htmlspecialchars($genre) . '</button>';
                }
                ?>
            </div>
        </div>

        <!-- Genre Categories -->
        <div class="container mt-4">
            <h3 class="text-center mb-4">Music Categories</h3>
            <div class="row g-4 mb-5">
                <?php
                $genres = [
                    'Secular' => 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?q=80&w=2070',
                    'Gospel' => 'https://images.unsplash.com/photo-1511994714008-b6d68a8b32a2?q=80&w=2070'
                ];

                foreach($genres as $genre => $image) {
                    echo "<div class='col-md-6 mb-4'>";
                    echo "<div class='card category-card h-100' onclick='filterByGenre(\"$genre\")'>";
                    echo "<div class='position-relative'>";
                    echo "<img src='$image' class='card-img-top' alt='$genre' style='height: 350px; object-fit: cover;'>";
                    echo "<div class='position-absolute top-0 start-0 w-100 h-100' style='background: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.6))'></div>";
                    echo "<h3 class='position-absolute bottom-0 start-0 text-white p-3 mb-0'>$genre</h3>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <!-- Search Container -->
        <div class="container search-container">
            <div class="hero-image mb-4" style="background: url('https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?q=80&w=2070&auto=format&fit=crop') no-repeat center center; background-size: cover; height: 400px; border-radius: 12px; display: flex; align-items: center; justify-content: center; position: relative;">
                <div style="background: rgba(0,0,0,0.5); position: absolute; width: 100%; height: 100%; border-radius: 12px;"></div>
                <div style="position: relative; text-align: center; padding: 2rem;">
                    <h1 style="color: white; text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); font-size: 3rem; margin-bottom: 1rem;">Discover Your Favorite Music</h1>
                    <p style="color: white; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5); font-size: 1.2rem;">Explore millions of songs in high quality</p>
                </div>
            </div>

            <!-- Search Form -->
            <form class="search-form" method="GET" action="?page=home" id="searchForm">
                <div class="input-group">
                    <input type="text" name="search" id="searchInput" class="form-control" placeholder="Search for songs, artists, genres..." value="<?= htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : '') ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                </div>
            </form>
        </div>

        <!-- Features Section -->
        <div class="container">
            <div class="row mb-5 text-center features-inline-mobile">
                <div class="col-md-4 mb-4">
                    <div class="p-4 bg-white rounded-lg shadow-sm feature-card">
                        <i class="fas fa-music fa-3x mb-3" style="color: var(--primary-color);"></i>
                        <h3 class="h5 mb-2">High Quality Audio</h3>
                        <p class="text-muted">Crystal clear sound quality for the best experience</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="p-4 bg-white rounded-lg shadow-sm feature-card">
                        <i class="fas fa-headphones fa-3x mb-3" style="color: var(--accent-color);"></i>
                        <h3 class="h5 mb-2">Listen Anywhere</h3>
                        <p class="text-muted">Stream your favorite tracks anytime</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="p-4 bg-white rounded-lg shadow-sm feature-card">
                        <i class="fas fa-music fa-3x mb-3" style="color: var(--secondary-color);"></i>
                        <h3 class="h5 mb-2">More Music</h3>
                        <p class="text-muted">Access exclusive music content</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Songs Section -->
        <div class="container" id="newSongsSection">
            <h2 class="mt-4 mb-4">
                <i class="fas fa-star me-2"></i>New Releases
            </h2>
            <?php
            $search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
            $search_param = '%' . $search_term . '%';
            $genre_filter = isset($_GET['genre']) && $_GET['genre'] !== 'all' ? $_GET['genre'] : null;
            $sql = "SELECT s.*, a.name as artist_name 
                    FROM songs s 
                    LEFT JOIN artists a ON s.artist_id = a.id 
                    WHERE s.upload_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";

            if (!empty($search_term)) {
                $sql .= " AND (s.title LIKE :search OR a.name LIKE :search OR s.genre LIKE :search)";
            }
            if ($genre_filter) {
                $sql .= " AND s.genre = :genre";
            }

            $sql .= " ORDER BY s.upload_date DESC LIMIT 4";

            $stmt = $conn->prepare($sql);
            if (!empty($search_term)) {
                $stmt->bindValue(':search', '%' . $search_term .'%', PDO::PARAM_STR);
            }
            if ($genre_filter) {
                $stmt->bindValue(':genre', $genre_filter, PDO::PARAM_STR);
            }
            $stmt->execute();
            $newSongs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <?php if ($search_term): ?>
                <p class="text-center text-muted">Showing results for: "<?= htmlspecialchars($search_term) ?>"</p>
            <?php endif;?>

            <div class="row g-4" id="newSongsList">
                <?php
                if (count($newSongs) > 0) {
                    foreach($newSongs as $song) {
                        ?>
                        <div class="col-sm-6 col-md-4 col-lg-3 mb-3">
                            <div class="song-card" data-song-id="<?= $song['id'] ?>">
                                <?php if($song['image_path']): ?>
                                    <img src="<?= htmlspecialchars($song['image_path']) ?>" class="card-img-top" alt="Album Art" onclick="window.location.href='?page=song_detail&song_id=<?= $song['id'] ?>'">
                                <?php else: ?>
                                    <div style="height: 130px; background: #e9ecef; display: flex; align-items: center; justify-content: center; cursor: pointer;" onclick="window.location.href='?page=song_detail&song_id=<?= $song['id'] ?>'">
                                        <<i class="fas fa-music fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title" onclick="window.location.href='?page=song_detail&song_id=<?= $song['id'] ?>'"><?= htmlspecialchars($song['title']) ?></h5>
                                    <h6 class="card-subtitle mb-2 text-muted"><i class="fas fa-user me-1"></i><?= htmlspecialchars($song['artist_name'] ?? $song['artist']) ?></h6>
                                    <span class="genre-badge">
                                        <i class="fas fa-tag me-1"></i><?= htmlspecialchars($song['genre']) ?>
                                    </span>
                                    <div class="upload-date mt-2 mb-2">
                                        <small class="text-muted"><i class="fas fa-calendar me-1"></i>Uploaded: <?= date('M d, Y', strtotime($song['upload_date'])) ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<div class='col-12 empty-state'>";
                    echo "<i class='fas fa-music fa-3x mb-3'></i>";
                    echo "<h4>No new releases found</h4>";
                    echo "<p>Check back soon for new music.</p>";
                    echo "</div>";
                }
                ?>
            </div>
            <div class="text-center mt-4">
                <a href="?page=all_new<?= $search_term ? '&search=' . urlencode($search_term) : '' ?>" class="btn btn-outline-primary">
                    <i class="fas fa-plus-circle me-2"></i>See More New Releases
                </a>
            </div>
        </div>

        <!-- Trending Section -->
        <div class="container" id="trendingSection">
            <h2 class="mt-4 mb-4">
                <i class="fas fa-fire me-2"></i>Trending Now
            </h2>
            <?php
            $search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
            $search_param = '%' . $search_term . '%';

            $min_plays = 5; // Minimum plays required to be considered trending
            $min_downloads = 2; // Minimum downloads required to be considered trending

            $genre_filter = isset($_GET['genre']) && $_GET['genre'] !== 'all' ? $_GET['genre'] : null;
            // Calculate total records for pagination
            $count_sql = "SELECT COUNT(*) FROM songs s 
                         LEFT JOIN artists a ON s.artist_id = a.id 
                         WHERE s.upload_date >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
                         AND (COALESCE(s.play_count, 0) > 0 OR COALESCE(s.download_count, 0) > 0)";
            if (!empty($search_term)) {
                $count_sql .= " AND (s.title LIKE :search OR a.name LIKE :search OR s.genre LIKE :search)";
            }
            if ($genre_filter) {
                $count_sql .= " AND s.genre = :genre";
            }

            $count_stmt = $conn->prepare($count_sql);
            if (!empty($search_term)) {
                $count_stmt->bindValue(':search', '%' . $search_term . '%', PDO::PARAM_STR);
            }
            if ($genre_filter) {
                $count_stmt->bindValue(':genre', $genre_filter, PDO::PARAM_STR);
            }
            $count_stmt->execute();
            $total_records = $count_stmt->fetchColumn();

            // Pagination settings
            $records_per_page = 10;
            $page_num = isset($_GET['p']) ? (int)$_GET['p'] : 1;
            $page_num = max(1, $page_num);
            $offset = ($page_num - 1) * $records_per_page;
            $total_pages = ceil($total_records / $records_per_page);

            $sql = "SELECT s.*, a.name as artist_name, 
                                             (COALESCE(s.play_count, 0) * 1.5 + COALESCE(s.download_count, 0) * 2) as trending_score 
                                      FROM songs s 
                                      LEFT JOIN artists a ON s.artist_id = a.id 
                                      WHERE s.upload_date >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
                                      AND (COALESCE(s.play_count, 0) > 0 OR COALESCE(s.download_count, 0) > 0)";
            if (!empty($search_term)) {
                $sql .= " AND (s.title LIKE :search OR a.name LIKE :search OR s.genre LIKE :search)";
            }
            if ($genre_filter) {
                $sql .= " AND s.genre = :genre";
            }
            $sql .= " ORDER BY trending_score DESC, s.upload_date DESC 
                                      LIMIT :limit OFFSET :offset";


            $stmt = $conn->prepare($sql);
            if (!empty($search_term)) {
                $stmt->bindValue(':search', '%' . $search_term . '%', PDO::PARAM_STR);
            }
            if ($genre_filter) {
                $stmt->bindValue(':genre', $genre_filter, PDO::PARAM_STR);
            }
            $stmt->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $trending = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <?php if ($search_term): ?>
                <p class="text-center text-muted">Showing results for: "<?= htmlspecialchars($search_term) ?>"</p>
            <?php endif; ?>

            <div class="row g-4" id="trendingList">
                <?php
                if (count($trending) > 0) {
                    foreach($trending as $song) {
                        ?>
                        <div class="col-sm-6 col-md-4 col-lg-3 mb-3">
                            <div class="song-card" data-song-id="<?= $song['id'] ?>">
                                <a href="?page=song_detail&song_id=<?= $song['id'] ?>" class="text-decoration-none">
                                    <?php if($song['image_path']): ?>
                                        <img src="<?= htmlspecialchars($song['image_path']) ?>" alt="Album Art" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                                    <?php else: ?>
                                        <div style="width: 100px; height: 100px; background: #e9ecef; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                            <i class="fas fa-music fa-2x text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </a>
                                <div class="card-body">
                                    <div class="ms-3">
                                        <a href="?page=song_detail&song_id=<?= $song['id'] ?>" class="text-decoration-none">
                                            <h5 class="card-title mb-1 text-dark"><?= htmlspecialchars($song['title']) ?></h5>
                                        </a>
                                        <p class="card-text mb-1"><i class="fas fa-user me-1"></i><?= htmlspecialchars($song['artist_name'] ?? $song['artist']) ?></p>
                                        <p class="card-text mb-1"><i class="fas fa-calendar me-1"></i><?= date('M d, Y', strtotime($song['upload_date'])) ?></p>
                                        <div class="metrics">
                                            <small class="text-primary me-2"><i class="fas fa-play me-1"></i><?= number_format($song['play_count']) ?> plays</small>
                                            <small class="text-success"><i class="fas fa-download me-1"></i><?= number_format($song['download_count']) ?> downloads</small>
                                        </div>
                                    </div>
                                    <div class="ms-auto">
                                        <a href="?page=song_detail&song_id=<?= $song['id'] ?>" class="text-decoration-none">
                                            <i class="fas fa-info-circle me-1"></i>View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<div class='col-12 empty-state'>";
                    echo "<i class='fas fa-fire fa-3x mb-3'></i>";
                    echo "<h4>No trending songs found</h4>";
                    echo "<p>Check back later for trending content.</p>";
                    echo "</div>";
                }
                ?>
            </div>

            <?php if($total_pages > 1): ?>
            <nav aria-label="Trending songs pagination" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $page_num <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=trending&period=<?= $period ?>&p=<?= $page_num - 1 ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['genre']) ? '&genre=' . urlencode($_GET['genre']) : '' ?>" aria-label="Previous">
                            <span aria-hidden="true">«</span>
                        </a>
                    </li>
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i === $page_num ? 'active' : '' ?>">
                            <a class="page-link" href="?page=trending&period=<?= $period ?>&p=<?= $i ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['genre']) ? '&genre=' . urlencode($_GET['genre']) : '' ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page_num >= $total_pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=trending&period=<?= $period ?>&p=<?= $page_num + 1 ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['genre']) ? '&genre=' . urlencode($_GET['genre']) : '' ?>" aria-label="Next">
                            <span aria-hidden="true">»</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        </div>

        <!-- Audio Section -->
        <div class="container" id="audioSection">
            <h2 class="mt-4 mb-4">
                <i class="fas fa-headphones me-2"></i>Audio Tracks
            </h2>
            <?php
            $search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
            $search_param = '%' . $search_term . '%';
            $genre_filter = isset($_GET['genre']) && $_GET['genre'] !== 'all' ? $_GET['genre'] : null;
            $sql = "SELECT s.*, a.name as artist_name 
                    FROM songs s 
                    LEFT JOIN artists a ON s.artist_id = a.id 
                    WHERE s.audio_path IS NOT NULL";
            if (!empty($search_term)) {
                $sql .= " AND (s.title LIKE :search OR a.name LIKE :search OR s.genre LIKE :search)";
            }
            if ($genre_filter) {
                $sql .= " AND s.genre = :genre";
            }
            $sql .= " ORDER BY s.upload_date DESC LIMIT 4";

            $stmt = $conn->prepare($sql);
            if (!empty($search_term)) {
                $stmt->bindValue(':search', '%' . $search_term . '%', PDO::PARAM_STR);
            }
            if ($genre_filter) {
                $stmt->bindValue(':genre', $genre_filter, PDO::PARAM_STR);
            }
            $stmt->execute();
            $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <?php if ($search_term): ?>
                <p class="text-center text-muted">Showing results for: "<?= htmlspecialchars($search_term) ?>"</p>
            <?php endif; ?>

            <div class="row g-4" id="audioList">
                <?php
                if (count($songs) > 0) {
                    foreach($songs as $song) {
                        ?>
                        <div class="col-sm-6 col-md-4 col-lg-3 mb-3">
                            <div class="song-card" data-song-id="<?= $song['id'] ?>">
                                <?php if($song['image_path']): ?>
                                    <img src="<?= htmlspecialchars($song['image_path']) ?>" class="card-img-top" alt="Album Art" onclick="window.location.href='?page=song_detail&song_id=<?= $song['id'] ?>'">
                                <?php else: ?>
                                    <div style="height: 130px; background: #e9ecef; display: flex; align-items: center; justify-content: center; cursor: pointer;" onclick="window.location.href='?page=song_detail&song_id=<?= $song['id'] ?>'">
                                        <i class="fas fa-music fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title" onclick="window.location.href='?page=song_detail&song_id=<?= $song['id'] ?>'"><?= htmlspecialchars($song['title']) ?></h5>
                                    <h6 class="card-subtitle mb-2 text-muted"><i class="fas fa-user me-1"></i><?= htmlspecialchars($song['artist_name'] ?? $song['artist']) ?></h6>
                                    <span class="genre-badge">
                                        <i class="fas fa-tag me-1"></i><?= htmlspecialchars($song['genre']) ?>
                                    </span>
                                    <div class="upload-date mt-2 mb-2">
                                        <small class="text-muted"><i class="fas fa-calendar me-1"></i>Uploaded: <?= date('M d, Y', strtotime($song['upload_date'])) ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<div class='col-12 empty-state'>";
                    echo "<i class='fas fa-music fa-3x mb-3'></i>";
                    echo "<h4>No audio tracks found</h4>";
                    echo "<p>Try adding some audio content to your collection.</p>";
                    echo "</div>";
                }
                ?>
            </div>
            <div class="text-center mt-4">
                <a href="?page=all_audio<?= $search_term ? '&search=' . urlencode($search_term) : '' ?>" class="btn btn-outline-primary">
                    <i class="fas fa-plus-circle me-2"></i>See More Audio Tracks
                </a>
            </div>
        </div>

    <?php elseif ($page === 'all_new'): ?>
        <!-- All New Songs Section -->
        <div class="container mt-5" id="allNewSongsSection">
            <form class="search-form" method="GET" action="?page=all_new" id="searchForm">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search for a song..." value="<?= htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : '') ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                </div>
            </form>

            <h2 class="text-center mb-4">
                <i class="fas fa-star me-2"></i>All New Releases
            </h2>
            <?php
            $songs_per_page = 12;
            $page_num = isset($_GET['p']) ? (int)$_GET['p'] : 1;
            $page_num = max(1, $page_num);
            $offset = ($page_num - 1) * $songs_per_page;

            $search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
            $search_param = '%' . $search_term . '%';

            $genre_filter = isset($_GET['genre']) && $_GET['genre'] !== 'all' ? $_GET['genre'] : null;
            $sql = "SELECT COUNT(*) FROM songs s 
                    LEFT JOIN artists a ON s.artist_id = a.id 
                    WHERE s.upload_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
            if (!empty($search_term)) {
                $sql .= " AND (s.title LIKE :search OR a.name LIKE :search OR s.genre LIKE :search)";
            }
            if ($genre_filter) {
                $sql .= " AND s.genre = :genre";
            }
            $total_stmt = $conn->prepare($sql);
            if (!empty($search_term)) {
                $total_stmt->bindValue(':search', '%' . $search_term . '%', PDO::PARAM_STR);
            }
            if ($genre_filter) {
                $total_stmt->bindValue(':genre', $genre_filter, PDO::PARAM_STR);
            }
            $total_stmt->execute();
            $total_songs = $total_stmt->fetchColumn();
            $total_pages = ceil($total_songs / $songs_per_page);

            $sql = "SELECT s.*, a.name as artist_name 
                    FROM songs s 
                    LEFT JOIN artists a ON s.artist_id = a.id 
                    WHERE s.upload_date >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
            if (!empty($search_term)) {
                $sql .= " AND (s.title LIKE :search OR a.name LIKE :search OR s.genre LIKE :search)";
            }
            if ($genre_filter) {
                $sql .= " AND s.genre = :genre";
            }
            $sql .= " ORDER BY s.upload_date DESC LIMIT :limit OFFSET :offset";

            $stmt = $conn->prepare($sql);
            if (!empty($search_term)) {
                $stmt->bindValue(':search', '%' . $search_term . '%', PDO::PARAM_STR);
            }
            if ($genre_filter) {
                $stmt->bindValue(':genre', $genre_filter, PDO::PARAM_STR);
            }
            $stmt->bindValue(':limit', (int)$songs_per_page, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            $allNewSongs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <?php if ($search_term): ?>
                <p class="text-center text-muted">Showing results for: "<?= htmlspecialchars($search_term) ?>"</p>
            <?php endif; ?>

            <div class="row g-4">
                <?php
                if (count($allNewSongs) > 0) {
                    foreach($allNewSongs as $song) {
                        ?>
                        <div class="col-sm-6 col-md-4 col-lg-3 mb-3">
                            <div class="song-card" data-song-id="<?= $song['id'] ?>">
                                <?php if($song['image_path']): ?>
                                    <img src="<?= htmlspecialchars($song['image_path']) ?>" class="card-img-top" alt="Album Art" onclick="window.location.href='?page=song_detail&song_id=<?= $song['id'] ?>'">
                                <?php else: ?>
                                    <div style="height: 130px; background: #e9ecef; display: flex; align-items: center; justify-content: center; cursor: pointer;" onclick="window.location.href='?page=song_detail&song_id=<?= $song['id'] ?>'">
                                        <i class="fas fa-music fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title" onclick="window.location.href='?page=song_detail&song_id=<?= $song['id'] ?>'"><?= htmlspecialchars($song['title']) ?></h5>
                                    <h6 class="card-subtitle mb-2 text-muted"><i class="fas fa-user me-1"></i><?= htmlspecialchars($song['artist_name'] ?? $song['artist']) ?></h6>
                                    <span class="genre-badge">
                                        <i class="fas fa-tag me-1"></i><?= htmlspecialchars($song['genre']) ?>
                                    </span>
                                    <div class="upload-date mt-2 mb-2">
                                        <small class="text-muted"><i class="fas fa-calendar me-1"></i>Uploaded: <?= date('M d, Y', strtotime($song['upload_date'])) ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<div class='col-12 empty-state'>";
                    echo "<i class='fas fa-music fa-3x mb-3'></i>";
                    echo "<h4>No new releases found</h4>";
                    echo "<p>Check back soon for new music.</p>";
                    echo "</div>";
                }
                ?>
            </div>

            <?php if($total_pages > 1): ?>
            <nav aria-label="Songs pagination" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $page_num <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=all_new&p=<?= $page_num - 1 ?>&search=<?= urlencode($search_term) ?>&genre=<?= urlencode($_GET['genre'] ?? '') ?>" aria-label="Previous">
                            <span aria-hidden="true">«</span>
                        </a>
                    </li>
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i === $page_num ? 'active' : '' ?>">
                            <a class="page-link" href="?page=all_new&p=<?= $i ?>&search=<?= urlencode($search_term) ?>&genre=<?= urlencode($_GET['genre'] ?? '') ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page_num >= $total_pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=all_new&p=<?= $page_num + 1 ?>&search=<?= urlencode($search_term) ?>&genre=<?= urlencode($_GET['genre'] ?? '') ?>" aria-label="Next">
                            <span aria-hidden="true">»</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>

            <div class="text-center mt-4">
                <a href="?page=home" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Home
                </a>
            </div>
        </div>

    <?php elseif ($page === 'all_trending'): ?>
        <!-- All Trending Songs Section -->
        <div class="container mt-5" id="allTrendingSection">
            <form class="search-form" method="GET" action="?page=all_trending" id="searchForm">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search for a song..." value="<?= htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : '') ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                </div>
            </form>

            <h2 class="text-center mb-4">
                <i class="fas fa-fire me-2"></i>All Trending Songs
            </h2>
            <?php
            $songs_per_page = 12;
            $page_num = isset($_GET['p']) ? (int)$_GET['p'] : 1;
            $page_num = max(1, $page_num);
            $offset = ($page_num - 1) * $songs_per_page;

            $search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
            $search_param = '%' . $search_term . '%';

            $min_plays = 5;
            $min_downloads = 2;
            $genre_filter = isset($_GET['genre']) && $_GET['genre'] !== 'all' ? $_GET['genre'] : null;

            $sql = "SELECT COUNT(*) FROM songs s 
                    LEFT JOIN artists a ON s.artist_id = a.id";
            if (!empty($search_term)) {
                $sql .= " WHERE (s.title LIKE :search OR a.name LIKE :search OR s.genre LIKE :search)";
            } elseif ($genre_filter) {
                $sql .= " WHERE s.genre = :genre";
            }
            $total_stmt = $conn->prepare($sql);
            if (!empty($search_term)) {
                $total_stmt->bindValue(':search', '%' . $search_term . '%', PDO::PARAM_STR);
            }
            if ($genre_filter) {
                $total_stmt->bindValue(':genre', $genre_filter, PDO::PARAM_STR);
            }
            $total_stmt->execute();
            $total_songs = $total_stmt->fetchColumn();
            $total_pages = ceil($total_songs / $songs_per_page);

            $sql = "SELECT s.*, a.name as artist_name, (s.play_count * 1.5 + s.download_count * 2) as trending_score 
                    FROM songs s 
                    LEFT JOIN artists a ON s.artist_id = a.id 
                    WHERE (s.play_count >= :min_plays OR s.download_count >= :min_downloads)";
            if (!empty($search_term)) {
                $sql .= " AND (s.title LIKE :search OR a.name LIKE :search OR s.genre LIKE :search)";
            }
            if ($genre_filter) {
                $sql .= " AND s.genre = :genre";
            }
            $sql .= " ORDER BY trending_score DESC, s.upload_date DESC LIMIT :limit OFFSET :offset";

            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':min_plays', $min_plays, PDO::PARAM_INT);
            $stmt->bindValue(':min_downloads', $min_downloads, PDO::PARAM_INT);
            if (!empty($search_term)) {
                $stmt->bindValue(':search', '%' . $search_term . '%', PDO::PARAM_STR);
            }
            if ($genre_filter) {
                $stmt->bindValue(':genre', $genre_filter, PDO::PARAM_STR);
            }
            $stmt->bindValue(':limit', (int)$songs_per_page, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            $allTrendingSongs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <?php if ($search_term): ?>
                <p class="text-center text-muted">Showing results for: "<?= htmlspecialchars($search_term) ?>"</p>
            <?php endif; ?>

            <div class="row g-4">
                <?php
                if (count($allTrendingSongs) > 0) {
                    foreach($allTrendingSongs as $song) {
                        ?>
                        <div class="col-sm-6 col-md-4 col-lg-3 mb-3">
                            <div class="song-card" data-song-id="<?= $song['id'] ?>">
                                <?php if($song['image_path']): ?>
                                    <img src="<?= htmlspecialchars($song['image_path']) ?>" class="card-img-top" alt="Album Art" onclick="window.location.href='?page=song_detail&song_id=<?= $song['id'] ?>'">
                                <?php else: ?>
                                    <div style="height: 130px; background: #e9ecef; display: flex; align-items: center; justify-content: center; cursor: pointer;" onclick="window.location.href='?page=song_detail&song_id=<?= $song['id'] ?>'">
                                        <i class="fas fa-music fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title" onclick="window.location.href='?page=song_detail&song_id=<?= $song['id'] ?>'"><?= htmlspecialchars($song['title']) ?></h5>
                                    <h6 class="card-subtitle mb-2 text-muted"><i class="fas fa-user me-1"></i><?= htmlspecialchars($song['artist_name'] ?? $song['artist']) ?></h6>
                                    <span class="genre-badge">
                                        <i class="fas fa-tag me-1"></i><?= htmlspecialchars($song['genre']) ?>
                                    </span>
                                    <div class="upload-date mt-2 mb-2">
                                        <small class="text-muted"><i class="fas fa-calendar me-1"></i>Uploaded: <?= date('M d, Y', strtotime($song['upload_date'])) ?></small>
                                    </div>
                                    <div class="metrics">
                                        <small class="text-primary"><i class="fas fa-play me-1"></i><?= number_format($song['play_count']) ?> plays</small>
                                        <small class="text-success"><i class="fas fa-download me-1"></i><?= number_format($song['download_count']) ?> downloads</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<div class='col-12 empty-state'>";
                    echo "<i class='fas fa-fire fa-3x mb-3'></i>";
                    echo "<h4>No trending songs found</h4>";
                    echo "<p>Check back later for trending content.</p>";
                    echo "</div>";
                }
                ?>
            </div>

            <?php if($total_pages > 1): ?>
            <nav aria-label="Songs pagination" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $page_num <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=all_trending&p=<?= $page_num - 1 ?>&search=<?= urlencode($search_term) ?>&genre=<?= urlencode($_GET['genre'] ?? '') ?>" aria-label="Previous">
                            <span aria-hidden="true">«</span>
                        </a>
                    </li>
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i === $page_num ? 'active' : '' ?>">
                            <a class="page-link" href="?page=all_trending&p=<?= $i ?>&search=<?= urlencode($search_term) ?>&genre=<?= urlencode($_GET['genre'] ?? '') ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page_num >= $total_pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=all_trending&p=<?= $page_num + 1 ?>&search=<?= urlencode($search_term) ?>&genre=<?= urlencode($_GET['genre'] ?? '') ?>" aria-label="Next">
                            <span aria-hidden="true">»</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>

            <div class="text-center mt-4">
                <a href="?page=home" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Home
                </a>
            </div>
        </div>

    <?php elseif ($page === 'all_audio'): ?>
        <!-- All Audio Tracks Section -->
        <div class="container mt-5" id="allAudioSection">
            <form class="search-form" method="GET" action="?page=all_audio" id="searchForm">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search for a song..." value="<?= htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : '') ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                </div>
            </form>

            <h2 class="text-center mb-4">
                <i class="fas fa-headphones me-2"></i>All Audio Tracks
            </h2>
            <?php
            $songs_per_page = 12;
            $page_num = isset($_GET['p']) ? (int)$_GET['p'] : 1;
            $page_num = max(1, $page_num);
            $offset = ($page_num - 1) * $songs_per_page;

            $search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
            $search_param = '%' . $search_term . '%';

            $genre_filter = isset($_GET['genre']) && $_GET['genre'] !== 'all' ? $_GET['genre'] : null;
            $sql = "SELECT COUNT(*) FROM songs s 
                    LEFT JOIN artists a ON s.artist_id = a.id 
                    WHERE s.audio_path IS NOT NULL";
            if (!empty($search_term)) {
                $sql .= " AND (s.title LIKE :search OR a.name LIKE :search OR s.genre LIKE :search)";
            }
            if ($genre_filter) {
                $sql .= " AND s.genre = :genre";
            }
            $total_stmt = $conn->prepare($sql);
            if (!empty($search_term)) {
                $total_stmt->bindValue(':search', '%' . $search_term . '%', PDO::PARAM_STR);
            }
            if ($genre_filter) {
                $total_stmt->bindValue(':genre', $genre_filter, PDO::PARAM_STR);
            }
            $total_stmt->execute();
            $total_songs = $total_stmt->fetchColumn();
            $total_pages = ceil($total_songs / $songs_per_page);

            $sql = "SELECT s.*, a.name as artist_name 
                    FROM songs s 
                    LEFT JOIN artists a ON s.artist_id = a.id 
                    WHERE s.audio_path IS NOT NULL";
            if (!empty($search_term)) {
                $sql .= " AND (s.title LIKE :search OR a.name LIKE :search OR s.genre LIKE :search)";
            }
            if ($genre_filter) {
                $sql .= " AND s.genre = :genre";
            }
            $sql .= " ORDER BY s.upload_date DESC LIMIT :limit OFFSET :offset";

            $stmt = $conn->prepare($sql);
            if (!empty($search_term)) {
                $stmt->bindValue(':search', '%' . $search_term . '%', PDO::PARAM_STR);
            }
            if ($genre_filter) {
                $stmt->bindValue(':genre', $genre_filter, PDO::PARAM_STR);
            }
            $stmt->bindValue(':limit', (int)$songs_per_page, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            $allAudioSongs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <?php if ($search_term): ?>
                <p class="text-center text-muted">Showing results for: "<?= htmlspecialchars($search_term) ?>"</p>
            <?php endif; ?>

            <div class="row g-4">
                <?php
                if (count($allAudioSongs) > 0) {
                    foreach($allAudioSongs as $song) {
                        ?>
                        <div class="col-sm-6 col-md-4 col-lg-3 mb-3">
                            <div class="song-card" data-song-id="<?= $song['id'] ?>">
                                <?php if($song['image_path']): ?>
                                    <img src="<?= htmlspecialchars($song['image_path']) ?>" class="card-img-top" alt="Album Art" onclick="window.location.href='?page=song_detail&song_id=<?= $song['id'] ?>'">
                                <?php else: ?>
                                    <div style="height: 130px; background: #e9ecef; display: flex; align-items: center; justify-content: center; cursor: pointer;" onclick="window.location.href='?page=song_detail&song_id=<?= $song['id'] ?>'">
                                        <i class="fas fa-music fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title" onclick="window.location.href='?page=song_detail&song_id=<?= $song['id'] ?>'"><?= htmlspecialchars($song['title']) ?></h5>
                                    <h6 class="card-subtitle mb-2 text-muted"><i class="fas fa-user me-1"></i><?= htmlspecialchars($song['artist_name'] ?? $song['artist']) ?></h6>
                                    <span class="genre-badge">
                                        <i class="fas fa-tag me-1"></i><?= htmlspecialchars($song['genre']) ?>
                                    </span>
                                    <div class="upload-date mt-2 mb-2">
                                        <small class="text-muted"><i class="fas fa-calendar me-1"></i>Uploaded: <?= date('M d, Y', strtotime($song['upload_date'])) ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<div class='col-12 empty-state'>";
                    echo "<i class='fas fa-music fa-3x mb-3'></i>";                    
                    echo "<h4>No audio tracks found</h4>";
                    echo "<p>Try adding some audio content to your collection.</p>";                    
                    echo "</div>";
                }
                ?>
            </div>

            <?php if($total_pages > 1): ?>
            <nav aria-label="Songs pagination" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $page_num <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=all_audio&p=<?= $page_num - 1 ?>&search=<?= urlencode($search_term) ?>&genre=<?= urlencode($_GET['genre'] ?? '') ?>" aria-label="Previous">
                            <span aria-hidden="true">«</span>
                        </a>
                    </li>
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i === $page_num ? 'active' : '' ?>">
                            <a class="page-link" href="?page=all_audio&p=<?= $i ?>&search=<?= urlencode($search_term) ?>&genre=<?= urlencode($_GET['genre'] ?? '') ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page_num >= $total_pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=all_audio&p=<?= $page_num + 1 ?>&search=<?= urlencode($search_term) ?>&genre=<?= urlencode($_GET['genre'] ?? '') ?>" aria-label="Next">
                            <span aria-hidden="true">»</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>

            <div class="text-center mt-4">
                <a href="?page=home" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Home
                </a>
            </div>
        </div>

    <?php elseif ($page === 'trending'): ?>
        <!-- Period Navigation and Search Form for Trending -->
        <div class="container mb-4">
            <div class="row mb-3">
                <div class="col-12">
                    <ul class="nav nav-pills justify-content-center">
                        <li class="nav-item">
                            <a class="nav-link <?= (!isset($_GET['period']) || $_GET['period'] === 'weekly') ? 'active' : '' ?>" href="?page=trending&period=weekly<?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['genre']) ? '&genre=' . urlencode($_GET['genre']) : '' ?>">
                                <i class="fas fa-calendar-week me-1"></i>Weekly
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= (isset($_GET['period']) && $_GET['period'] === 'monthly') ? 'active' : '' ?>" href="?page=trending&period=monthly<?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['genre']) ? '&genre=' . urlencode($_GET['genre']) : '' ?>">
                                <i class="fas fa-calendar-alt me-1"></i>Monthly
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= (isset($_GET['period']) && $_GET['period'] === 'yearly') ? 'active' : '' ?>" href="?page=trending&period=yearly<?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['genre']) ? '&genre=' . urlencode($_GET['genre']) : '' ?>">
                                <i class="fas fa-calendar me-1"></i>Yearly
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <form class="search-form" method="GET" action="">
                <input type="hidden" name="page" value="trending">
                <input type="hidden" name="period" value="<?= htmlspecialchars($period ?? 'weekly') ?>">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search trending songs..." value="<?= htmlspecialchars(isset($_GET['search']) ? $_GET['search'] : '') ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                    <?php if(isset($_GET['search']) && !empty($_GET['search'])): ?>
                        <a href="?page=trending&period=<?= htmlspecialchars($period ?? 'weekly') ?><?= isset($_GET['genre']) ? '&genre=' . urlencode($_GET['genre']) : '' ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Trending Section -->
        <div class="container" id="trendingSection">
            <?php
            $period = isset($_GET['period']) ? $_GET['period'] : 'weekly';
            $interval = '';
            $title = '';

            switch($period) {
                case 'weekly':
                    $interval = 'INTERVAL 7 DAY';
                    $title = 'Weekly';
                    break;
                case 'monthly':
                    $interval = 'INTERVAL 30 DAY';
                    $title = 'Monthly';
                    break;
                case 'yearly':
                    $interval = 'INTERVAL 365 DAY';
                    $title = 'Yearly';
                    break;
            }

            $search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
            $search_param = '%' . $search_term . '%';
            $genre_filter = isset($_GET['genre']) && $_GET['genre'] !== 'all' ? $_GET['genre'] : null;

            // Calculate total records for pagination
            $count_sql = "SELECT COUNT(*) FROM songs s 
                         LEFT JOIN artists a ON s.artist_id = a.id 
                         WHERE s.upload_date >= DATE_SUB(CURRENT_DATE, " . $interval . ")
                         AND (COALESCE(s.play_count, 0) > 0 OR COALESCE(s.download_count, 0) > 0)";
            if (!empty($search_term)) {
                $count_sql .= " AND (s.title LIKE :search OR a.name LIKE :search OR s.genre LIKE :search)";
            }
            if ($genre_filter) {
                $count_sql .= " AND s.genre = :genre";
            }

            $count_stmt = $conn->prepare($count_sql);
            if (!empty($search_term)) {
                $count_stmt->bindValue(':search', '%' . $search_term . '%', PDO::PARAM_STR);
            }
            if ($genre_filter) {
                $count_stmt->bindValue(':genre', $genre_filter, PDO::PARAM_STR);
            }
            $count_stmt->execute();
            $total_records = $count_stmt->fetchColumn();

            // Pagination settings
            $records_per_page = 10;
            $page_num = isset($_GET['p']) ? (int)$_GET['p'] : 1;
            $page_num = max(1, $page_num);
            $offset = ($page_num - 1) * $records_per_page;
            $total_pages = ceil($total_records / $records_per_page);

            $sql = "SELECT s.*, a.name as artist_name, 
                           (COALESCE(s.play_count, 0) * 1.5 + COALESCE(s.download_count, 0) * 2) as trending_score,
                           ROW_NUMBER() OVER (ORDER BY (COALESCE(s.play_count, 0) * 1.5 + COALESCE(s.download_count, 0) * 2) DESC) as rank
                    FROM songs s 
                    LEFT JOIN artists a ON s.artist_id = a.id 
                    WHERE s.upload_date >= DATE_SUB(CURRENT_DATE, " . $interval . ")
                    AND (COALESCE(s.play_count, 0) > 0 OR COALESCE(s.download_count, 0) > 0)";
            if (!empty($search_term)) {
                $sql .= " AND (s.title LIKE :search OR a.name LIKE :search OR s.genre LIKE :search)";
            }
            if ($genre_filter) {
                $sql .= " AND s.genre = :genre";
            }
            $sql .= " ORDER BY trending_score DESC LIMIT :limit OFFSET :offset";

            $stmt = $conn->prepare($sql);
            if (!empty($search_term)) {
                $stmt->bindValue(':search', '%' . $search_term . '%', PDO::PARAM_STR);
            }
            if ($genre_filter) {
                $stmt->bindValue(':genre', $genre_filter, PDO::PARAM_STR);
            }
            $stmt->bindValue(':limit', $records_per_page, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $trending = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <h2 class="mb-4 text-center">
                <i class="fas fa-trophy me-2"></i><?php echo $title; ?> Top Charts
            </h2>

            <div class="row">
                <?php
                if (count($trending) > 0) {
                    foreach($trending as $song) {
                        ?>
                        <div class="col-12 mb-3">
                            <div class="card trending-card">
                                <div class="card-body d-flex align-items-center">
                                    <div class="rank-number me-3">
                                        <span class="h3"><?php echo $song['rank']; ?></span>
                                    </div>
                                    <a href="?page=song_detail&song_id=<?= $song['id'] ?>" class="text-decoration-none">
                                        <?php if($song['image_path']): ?>
                                            <img src="<?= htmlspecialchars($song['image_path']) ?>" alt="Album Art" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                                        <?php else: ?>
                                            <div style="width: 100px; height: 100px; background: #e9ecef; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
                                                <i class="fas fa-music fa-2x text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </a>
                                    <div class="ms-3">
                                        <a href="?page=song_detail&song_id=<?= $song['id'] ?>" class="text-decoration-none">
                                            <h5 class="card-title mb-1 text-dark"><?= htmlspecialchars($song['title']) ?></h5>
                                        </a>
                                        <p class="card-text mb-1"><i class="fas fa-user me-1"></i><?= htmlspecialchars($song['artist_name'] ?? $song['artist']) ?></p>
                                        <p class="card-text mb-1"><i class="fas fa-calendar me-1"></i><?= date('M d, Y', strtotime($song['upload_date'])) ?></p>
                                        <div class="metrics">
                                            <small class="text-primary me-2"><i class="fas fa-play me-1"></i><?= number_format($song['play_count']) ?> plays</small>
                                            <small class="text-success"><i class="fas fa-download me-1"></i><?= number_format($song['download_count']) ?> downloads</small>
                                        </div>
                                    </div>
                                    <div class="ms-auto">
                                        <a href="?page=song_detail&song_id=<?= $song['id'] ?>" class="text-decoration-none">
                                            <i class="fas fa-info-circle me-1"></i>View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<div class='col-12 text-center'>";
                    echo "<div class='empty-state'>";
                    echo "<i class='fas fa-chart-line fa-3x mb-3'></i>";
                    echo "<h4>No trending songs found</h4>";
                    echo "<p>Check back later for trending content.</p>";
                    echo "</div>";
                    echo "</div>";
                }
                ?>
            </div>

            <?php if($total_pages > 1): ?>
            <nav aria-label="Trending songs pagination" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $page_num <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=trending&period=<?= $period ?>&p=<?= $page_num - 1 ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['genre']) ? '&genre=' . urlencode($_GET['genre']) : '' ?>" aria-label="Previous">
                            <span aria-hidden="true">«</span>
                        </a>
                    </li>
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i === $page_num ? 'active' : '' ?>">
                            <a class="page-link" href="?page=trending&period=<?= $period ?>&p=<?= $i ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['genre']) ? '&genre=' . urlencode($_GET['genre']) : '' ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page_num >= $total_pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=trending&period=<?= $period ?>&p=<?= $page_num + 1 ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['genre']) ? '&genre=' . urlencode($_GET['genre']) : '' ?>" aria-label="Next">
                            <span aria-hidden="true">»</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        </div>

    <?php elseif ($page === 'song_detail' && isset($_GET['song_id'])): ?>
        <!-- Song Detail Page -->
        <?php
        $song_id = (int)$_GET['song_id'];
        $stmt = $conn->prepare("SELECT s.*, a.name as artist_name 
                                FROM songs s 
                                LEFT JOIN artists a ON s.artist_id = a.id 
                                WHERE s.id = :song_id");
        $stmt->bindValue(':song_id', $song_id, PDO::PARAM_INT);
        $stmt->execute();
        $song = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($song) {
            $artist_id = $song['artist_id'];
            $artist_name = $song['artist_name'] ?? $song['artist'];
            $related_stmt = $conn->prepare("SELECT s.* 
                                          FROM songs s 
                                          WHERE s.artist_id = :artist_id AND s.id != :song_id 
                                          ORDER BY s.upload_date DESC");
            $related_stmt->bindValue(':artist_id', $artist_id, PDO::PARAM_INT);
            $related_stmt->bindValue(':song_id', $song_id, PDO::PARAM_INT);
            $related_stmt->execute();
            $related_songs = $related_stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get the current URL for sharing
            $current_url = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $share_text = "Check out '" . htmlspecialchars($song['title']) . "' by " . htmlspecialchars($artist_name) . " on MusicHub!";
        ?>
        <div class="container song-detail-container">
            <div class="song-detail-header">
                <?php if($song['image_path']): ?>
                    <img src="<?= htmlspecialchars($song['image_path']) ?>" alt="Album Art">
                <?php else: ?>
                    <div style="width: 100%; height: 200px; background: #e9ecef; display: flex; align-items: center; justify-content: center; border-radius: 10px;">
                        <i class="fas fa-music fa-2x text-muted"></i>
                    </div>
                <?php endif; ?>
                <div class="song-detail-info">
                    <h1><?= htmlspecialchars($song['title']) ?></h1>
                    <p class="artist"><i class="fas fa-user me-1"></i><?= htmlspecialchars($artist_name) ?></p>
                    <p><i class="fas fa-tag me-1"></i><?= htmlspecialchars($song['genre']) ?></p>
                    <p><i class="fas fa-calendar me-1"></i>Uploaded: <?= date('M d, Y', strtotime($song['upload_date'])) ?></p>
                    <div class="counts">
                        <small><i class="fas fa-play me-1"></i><?= $song['play_count'] ?? 0 ?></small>
                        <small><i class="fas fa-download me-1"></i><?= $song['download_count'] ?? 0 ?></small>
                    </div>
                    <?php if($song['audio_path']): ?>
                        <audio class="audio-player" controls data-song-id="<?= $song['id'] ?>">
                            <source src="<?= htmlspecialchars($song['audio_path']) ?>" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                        <a href="<?= htmlspecialchars($song['audio_path']) ?>" download class="btn btn-download" data-song-id="<?= $song['id'] ?>">
                            <i class="fas fa-download"></i>Download
                        </a>
                    <?php else: ?>
                        <p class="text-muted small mt-2">Audio not available</p>
                    <?php endif; ?>
                    
                    <!-- Social Sharing Buttons -->
                    <div class="social-share mt-3">
                        <p class="small mb-2">Share this song:</p>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="https://wa.me/?text=<?= urlencode($share_text . ' ' . $current_url) ?>" 
                               class="btn btn-sm btn-success" 
                               target="_blank" 
                               title="Share on WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="https://www.tiktok.com/sharing?url=<?= urlencode($current_url) ?>&text=<?= urlencode($share_text) ?>" 
                               class="btn btn-sm btn-dark" 
                               target="_blank" 
                               title="Share on TikTok">
                                <i class="fab fa-tiktok"></i>
                            </a>
                            <a href="https://t.me/share/url?url=<?= urlencode($current_url) ?>&text=<?= urlencode($share_text) ?>" 
                               class="btn btn-sm btn-primary" 
                               target="_blank" 
                               title="Share on Telegram">
                                <i class="fab fa-telegram"></i>
                            </a>
                            <a href="https://www.instagram.com/?url=<?= urlencode($current_url) ?>" 
                               class="btn btn-sm btn-danger" 
                               target="_blank" 
                               title="Share on Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($current_url) ?>&quote=<?= urlencode($share_text) ?>" 
                               class="btn btn-sm btn-primary" 
                               target="_blank" 
                               title="Share on Facebook">
                                <i class="fab fa-facebook"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Songs by the Same Artist -->
            <div class="related-songs">
                <h3>More by <?= htmlspecialchars($artist_name) ?></h3>
                <?php if (count($related_songs) > 0): ?>
                    <?php foreach($related_songs as $related_song): ?>
                        <div class="related-song-card">
                            <?php if($related_song['image_path']): ?>
                                <img src="<?= htmlspecialchars($related_song['image_path']) ?>" alt="Album Art">
                            <?php else: ?>
                                <div style="width: 60px; height: 60px; background: #e9ecef; display: flex; align-items: center; justify-content: center; border-radius: 6px;">
                                    <i class="fas fa-music text-muted"></i>
                                </div>
                            <?php endif; ?>
                            <div class="related-song-info">
                                <h5 onclick="window.location.href='?page=song_detail&song_id=<?= $related_song['id'] ?>'"><?= htmlspecialchars($related_song['title']) ?></h5>
                                <small><i class="fas fa-calendar me-1"></i><?= date('M d, Y', strtotime($related_song['upload_date'])) ?></small>
                            </div>
                            <?php if($related_song['audio_path']): ?>
                                <audio class="audio-player" controls data-song-id="<?= $related_song['id'] ?>">
                                    <source src="<?= htmlspecialchars($related_song['audio_path']) ?>" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">No other songs by this artist.</p>
                <?php endif; ?>
            </div>

            <div class="text-center mt-3">
                <a href="?page=home" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Home
                </a>
            </div>
        </div>
        <?php
        } else {
            echo "<div class='container text-center py-4'>";
            echo "<div class='empty-state'>";
            echo "<i class='fas fa-music fa-3x mb-3'></i>";
            echo "<h4>Song not found</h4>";
            echo "<p>The song you're looking for doesn't exist.</p>";
            echo "<a href='?page=home' class='btn btn-outline-primary'>Back to Home</a>";
            echo "</div>";
            echo "</div>";
        }
        ?>
    <?php endif; ?>

    <!-- Bootstrap JS Bundle with Popper -->
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

    document.addEventListener('play', function(e) {
        if(e.target.tagName.toLowerCase() === 'audio' || e.target.tagName.toLowerCase() === 'video') {
            const allMedia = document.querySelectorAll('audio, video');
            allMedia.forEach(media => {
                if(media !== e.target) {
                    media.pause();
                }
            });
        }
    }, true);

    const searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function() {
            document.getElementById('loadingOverlay').style.display = 'flex';
        });
    }

    document.querySelectorAll('.audio-player').forEach(audio => {
        audio.addEventListener('play', function() {
            const songId = this.getAttribute('data-song-id');
            updateCount(songId, 'play');
        });
    });

    document.querySelectorAll('.btn-download').forEach(button => {
        button.addEventListener('click', function() {
            const songId = this.getAttribute('data-song-id');
            updateCount(songId, 'download');
        });
    });

    function updateCount(songId, action) {
        fetch('update_counts.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `song_id=${songId}&action=${action}`
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                console.error('Failed to update count:', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function filterByGenre(genre) {
        let buttons = document.querySelectorAll('#genreFilters button');
        if (buttons.length > 0) {
            buttons.forEach(btn => {
                if (btn.textContent.trim() === genre || (btn.textContent.trim() === 'All' && genre === 'all')) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }

        const searchParams = new URLSearchParams(window.location.search);
        searchParams.set('page', 'home');
        searchParams.set('genre', genre);
        if(document.getElementById('searchInput')?.value) {
            searchParams.set('search', document.getElementById('searchInput').value);
        }

        // Show loading overlay
        document.getElementById('loadingOverlay').style.display = 'flex';
        window.location.href = '?' + searchParams.toString();
    }

    //    // Set active genre on page load
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const genre = urlParams.get('genre');
        if(genre) {
            let buttons = document.querySelectorAll('#genreFilters button');
            buttons.forEach(btn => {
                if (btn.textContent.trim() === genre || (btn.textContent.trim() === 'All' && genre === 'all')) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }
    });
    </script>

    <!-- Footer -->
    <footer class="footer mt-5 py-4" style="background: linear-gradient(45deg, #1a1a1a, #2d2d2d);">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 mb-3">
                    <h5 class="text-white mb-3"><i class="fas fa-music me-2"></i>VibeNestle</h5>
                    <p class="text-muted">Your one-stop destination for discovering and downloading quality music. Stream and download your favorite tracks anytime, anywhere.</p>
                </div>
                <div class="col-lg-4 mb-3">
                    <h5 class="text-white mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="?page=home" class="text-muted text-decoration-none"><i class="fas fa-home me-2"></i>Home</a></li>
                        <li class="mb-2"><a href="?page=trending" class="text-muted text-decoration-none"><i class="fas fa-fire me-2"></i>Trending</a></li>
                        <li class="mb-2"><a href="artists.php" class="text-muted text-decoration-none"><i class="fas fa-user me-2"></i>Artists</a></li>
                        <li><a href="about.php" class="text-muted text-decoration-none"><i class="fas fa-info-circle me-2"></i>About</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5 class="text-white mb-3">Connect With Us</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-muted fs-4"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-muted fs-4"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-muted fs-4"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-muted fs-4"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <hr class="mt-4 mb-3" style="border-color: #444;">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="text-muted mb-2 mb-md-0">&copy; <?= date('Y') ?> VibeNestle. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-muted text-decoration-none me-3">Privacy Policy</a>
                    <a href="#" class="text-muted text-decoration-none">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
