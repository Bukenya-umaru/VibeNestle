<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artists - VibeNestle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <style>
        /* Loading spinner styles */
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

        /* Search form styling */
        .search-form {
            max-width: 500px;
            margin: 0 auto 20px;
        }

        /* Artist card hover effect */
        .artist-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
            margin-bottom: 10px;
        }
        .artist-card img {
            height: 160px;
            object-fit: cover;
            width: 100%;
            display: block;
        }
        .artist-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Song card styling (smaller and more compact) */
        .song-card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            background: #f8f9fa;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 15px;
            cursor: pointer;
        }
        .song-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .song-card img {
            height: 120px;
            object-fit: cover;
            width: 100%;
        }
        .song-card .card-body {
            padding: 10px;
            background: linear-gradient(145deg, #ffffff, #e6e6e6);
        }
        .song-card .card-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .song-card .card-subtitle {
            font-size: 0.85rem;
            margin-bottom: 5px;
        }
        .genre-badge {
            display: inline-block;
            background: #3498db;
            color: #fff;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 0.75rem;
        }
        .upload-date small {
            font-size: 0.75rem;
        }

        /* Responsive adjustments */
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
                padding: 8px;
            }
            .song-card .card-subtitle {
                font-size: 0.75rem;
            }
            .genre-badge {
                font-size: 0.65rem;
                padding: 2px 6px;
            }
            .upload-date small {
                font-size: 0.65rem;
            }
        }
        /* Mobile compact search: keep input and button inline and small on phones */
        @media (max-width: 767.98px) {
            .search-form {
                max-width: 100% !important;
                margin: 0 8px 12px !important;
            }
            .search-form .input-group {
                display: flex !important;
                flex-wrap: nowrap !important;
                gap: 6px;
                align-items: center;
            }
            .search-form .form-control {
                padding: 6px 8px !important;
                font-size: 0.95rem !important;
                height: 36px !important;
                line-height: 1 !important;
                border-radius: 6px !important;
                flex: 1 1 auto !important;
                min-width: 0 !important;
            }
            .search-form .input-group > .btn {
                padding: 6px 10px !important;
                font-size: 0.95rem !important;
                height: 36px !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                border-radius: 6px !important;
                min-width: 40px !important;
            }
            .search-form .input-group .fas {
                margin-right: 6px !important;
            }
        }
        @media (max-width: 576px) {
            /* Slightly shorter image and allow the artist name to wrap so full name is visible on phones */
            .artist-card img {
                height: 1400px !important;
                object-fit: cover !important;
            }

            .artist-card > .card-body {
                height: auto !important;
                padding: 8px;
            }

            .artist-card .card-title {
                white-space: normal !important;
                overflow: visible !important;
                text-overflow: unset !important;
                font-size: 1rem !important;
                line-height: 1.1;
            }

            /* Keep layout padding on small screens */
            .container {
                padding: 0 10px;
            }
        }

        .nav-link {
            font-weight: 500;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
            position: relative;
            padding: 0.5rem 1rem;
            border-radius: 5px;
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
            transition: all 0.3s ease;
        }

        .nav-link:hover::after, .nav-link.active::after {
            width: 100%;
        }

        body.dark-mode {
            background: #1a1a1a;
            color: #f1f1f1;
        }

        .dark-mode .navbar {
            background-color: rgba(0, 0, 0, 0.95) !important;
        }

        .dark-mode .navbar-brand {
            color: #4CAF50;
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

        .dark-mode .card {
            background: #2d2d2d;
            color: #f1f1f1;
        }

        .dark-mode .card-body {
            background: #2d2d2d !important;
            color: #f1f1f1 !important;
        }

        .dark-mode .text-muted {
            color: #d1d5db !important;
        }

        .dark-mode .song-card {
            background: #2d2d2d;
        }

        .dark-mode .card-title {
            color: #f1f1f1 !important;
        }

        .dark-mode .loading-overlay {
            background: rgba(0, 0, 0, 0.8);
        }

        .dark-mode h2, 
        .dark-mode h3, 
        .dark-mode h5, 
        .dark-mode p {
            color: #f1f1f1 !important;
        }

        .dark-mode .artist-card .card-body {
            background: #2d2d2d !important;
        }

        .dark-mode .artist-card h5 {
            color: #f1f1f1 !important;
        }

        .hover-white:hover {
            color: #ffffff !important;
            transition: color 0.3s ease;
        }

        /* Compact song cards specifically when viewing an artist on small/mobile screens */
        @media (max-width: 600px) {
            /* Show two song cards per row and reduce gutters */
            #songsList > .col-md-6, #songsList > .col-lg-4 {
                flex: 0 0 calc(50% - 8px) !important;
                max-width: calc(50% - 8px) !important;
                padding-left: 4px !important;
                padding-right: 4px !important;
                margin-bottom: 8px !important;
            }

            /* Compact song card visuals */
            .song-card {
                margin-bottom: 6px !important;
                border-radius: 8px !important;
                background: transparent !important;
                box-shadow: none !important;
            }
            .song-card img, .song-card .card-img-top {
                height: 72px !important;
                object-fit: cover !important;
                display: block;
            }
            .song-card .card-body {
                padding: 6px 6px !important;
                background: rgba(255,255,255,0.95) !important;
            }
            .song-card .card-title {
                font-size: 0.82rem !important;
                margin-bottom: 2px !important;
                white-space: nowrap !important;
            }
            .song-card .card-subtitle {
                font-size: 0.68rem !important;
                margin-bottom: 2px !important;
            }
            .genre-badge {
                font-size: 0.6rem !important;
                padding: 2px 6px !important;
            }
            .upload-date small {
                font-size: 0.6rem !important;
            }
        }
    </style>
</head>
<body>
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
                        <button id="darkModeToggle" class="btn btn-outline-primary mt-2">
                            <i class="fas fa-moon"></i>
                        </button>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="artists.php">Artists</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
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
    if (isset($_GET['artist_id'])) {
        // Display individual artist page
        $artist_id = $_GET['artist_id'];
        $stmt = $conn->prepare("SELECT * FROM artists WHERE id = ?");
        $stmt->execute([$artist_id]);
        $artist = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($artist) {
            ?>
            <div class="container mt-5">
                <div class="row">
                    <div class="col-md-4 text-center">
                        <?php if ($artist['image_path']): ?>
                            <img src="<?= htmlspecialchars($artist['image_path']) ?>" class="mb-3" style="width: 250px; height: 250px; object-fit: cover; border-radius: 10px;" alt="Artist">
                        <?php else: ?>
                            <i class="fas fa-user-circle" style="font-size: 250px; color: var(--primary-color);"></i>
                        <?php endif; ?>
                        <h2 class="mt-3"><?= htmlspecialchars($artist['name']) ?></h2>
                        <p class="text-muted"><?= htmlspecialchars($artist['bio']) ?></p>
                    </div>
                    <div class="col-md-8">
                        <h3 class="mb-4">Songs by <?= htmlspecialchars($artist['name']) ?></h3>
                        <div class="row" id="songsList">
                            <?php
                            $stmt = $conn->prepare("SELECT * FROM songs WHERE artist_id = ?");
                            $stmt->execute([$artist_id]);
                            $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            if (count($songs) > 0) {
                                foreach ($songs as $song) {
                                    ?>
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="song-card" onclick="window.location.href='index.php?page=song_detail&song_id=<?= $song['id'] ?>'">
                                            <?php if ($song['image_path']): ?>
                                                <img src="<?= htmlspecialchars($song['image_path']) ?>" class="card-img-top" alt="Album Art">
                                            <?php else: ?>
                                                <div style="height: 120px; background: #e9ecef; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-music fa-2x text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div class="card-body">
                                                <h5 class="card-title"><?= htmlspecialchars($song['title']) ?></h5>
                                                <h6 class="card-subtitle mb-2 text-muted"><i class="fas fa-user me-1"></i><?= htmlspecialchars($artist['name']) ?></h6>
                                                <span class="genre-badge">
                                                    <i class="fas fa-tag me-1"></i><?= htmlspecialchars($song['genre']) ?>
                                                </span>
                                                <div class="upload-date mt-1">
                                                    <small class="text-muted"><i class="fas fa-calendar me-1"></i>Uploaded: <?= date('M d, Y', strtotime($song['upload_date'])) ?></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo "<div class='col text-center'>";
                                echo "<p class='text-muted'>No songs available for this artist.</p>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                        <div class="text-center mt-4">
                            <a href="artists.php" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Artists
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        // Display artists list with pagination and search
        $artists_per_page = 20;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page); // Ensure page is at least 1
        $offset = ($page - 1) * $artists_per_page;

        // Handle search
        $search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
        $search_param = '%' . $search_term . '%';

        // Get total number of artists (with search filter if applicable)
        if ($search_term) {
            $total_stmt = $conn->prepare("SELECT COUNT(*) FROM artists WHERE name LIKE :search");
            $total_stmt->bindValue(':search', $search_param, PDO::PARAM_STR);
        } else {
            $total_stmt = $conn->prepare("SELECT COUNT(*) FROM artists");
        }
        $total_stmt->execute();
        $total_artists = $total_stmt->fetchColumn();
        $total_pages = ceil($total_artists / $artists_per_page);

        // Get artists for current page (with search filter if applicable)
        if ($search_term) {
            $stmt = $conn->prepare("SELECT * FROM artists WHERE name LIKE :search LIMIT :limit OFFSET :offset");
            $stmt->bindValue(':search', $search_param, PDO::PARAM_STR);
        } else {
            $stmt = $conn->prepare("SELECT * FROM artists LIMIT :limit OFFSET :offset");
        }
        $stmt->bindValue(':limit', (int)$artists_per_page, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        $artists = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <div class="container mt-5">
            <!-- Search Form -->
            <form class="search-form" method="GET" action="artists.php" id="searchForm">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search for an artist..." value="<?= htmlspecialchars($search_term) ?>">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                </div>
            </form>

            <h2 class="text-center mb-4">Browse Artists</h2>
            <?php if ($search_term): ?>
                <p class="text-center text-muted">Showing results for: "<?= htmlspecialchars($search_term) ?>"</p>
            <?php endif; ?>
            <div class="row" id="artistsList">
                <?php
                if (count($artists) > 0) {
                    foreach ($artists as $artist) {
                        echo "<div class='col-sm-6 col-md-4 col-lg-3 mb-3'>";
                        echo "<a href='artists.php?artist_id=" . $artist['id'] . "' class='text-decoration-none'>";
                        echo "<div class='card h-100 artist-card'>";
                        if ($artist['image_path']) {
                            echo "<img src='" . htmlspecialchars($artist['image_path']) . "' style='width: 100%; height: 160px; object-fit: cover;' alt='Artist'>";
                        } else {
                            echo "<div style='height: 160px; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa;'>";
                            echo "<i class='fas fa-user-circle fa-5x' style='color: var(--primary-color);'></i>";
                            echo "</div>";
                        }
                        echo "<div class='card-body text-center' style='background: rgba(255,255,255,0.95);'>";
                        echo "<h5 class='card-title mb-2' style='font-size: 1.25rem; font-weight: 600;'>" . htmlspecialchars($artist['name']) . "</h5>";
                        if ($artist['bio']) {
                            echo "<p class='card-text text-muted' style='font-size: 0.9rem;'>" . htmlspecialchars(substr($artist['bio'], 0, 100)) . "...</p>";
                        }
                        echo "</div></div></a></div>";
                    }
                } else {
                    echo "<div class='col text-center'>";
                    echo "<p class='text-muted'>No artists found.</p>";
                    echo "</div>";
                }
                ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
            <nav aria-label="Artists pagination" class="mt-4">
                <ul class="pagination justify-content-center">
                    <!-- Previous Button -->
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="artists.php?page=<?= $page - 1 ?>&search=<?= urlencode($search_term) ?>" aria-label="Previous">
                            <span aria-hidden="true">«</span>
                        </a>
                    </li>

                    <!-- Page Numbers -->
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                            <a class="page-link" href="artists.php?page=<?= $i ?>&search=<?= urlencode($search_term) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Next Button -->
                    <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                        <a class="page-link" href="artists.php?page=<?= $page + 1 ?>&search=<?= urlencode($search_term) ?>" aria-label="Next">
                            <span aria-hidden="true">»</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
        <?php
    }
    ?>

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

    // Show loading spinner when form is submitted
    document.getElementById('searchForm').addEventListener('submit', function() {
        document.getElementById('loadingOverlay').style.display = 'flex';
    });
    </script>

    <!-- Footer -->
    <footer class="footer mt-5 py-4" style="background: linear-gradient(45deg, #1a1a1a, #2d2d2d); color: #ffffff;">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 mb-3">
                    <h5 class="text-white mb-3"><i class="fas fa-music me-2"></i>VibeNestle</h5>
                    <p class="text-white-50">Your one-stop destination for discovering and downloading quality music. Stream and download your favorite tracks anytime, anywhere.</p>
                </div>
                <div class="col-lg-4 mb-3">
                    <h5 class="text-white mb-3">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="index.php" class="text-white text-decoration-none"><i class="fas fa-home me-2"></i>Home</a></li>
                        <li class="mb-2"><a href="index.php?page=trending" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-fire me-2"></i>Trending</a></li>
                        <li class="mb-2"><a href="artists.php" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-user me-2"></i>Artists</a></li>
                        <li><a href="about.php" class="text-white-50 text-decoration-none hover-white"><i class="fas fa-info-circle me-2"></i>About</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5 class="text-white mb-3">Connect With Us</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white-50 fs-4 hover-white"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-white-50 fs-4 hover-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white-50 fs-4 hover-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white-50 fs-4 hover-white"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <hr class="mt-4 mb-3" style="border-color: #ffffff33;">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="text-white-50 mb-2 mb-md-0">&copy; <?= date('Y') ?> VibeNestle. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="text-white-50 text-decoration-none hover-white me-3">Privacy Policy</a>
                    <a href="#" class="text-white-50 text-decoration-none hover-white">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>