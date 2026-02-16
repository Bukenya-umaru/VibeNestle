<?php
session_start();
include 'db_connect.php';

// Load admin customization settings (stored in JSON)
$settings_file = __DIR__ . DIRECTORY_SEPARATOR . 'admin_settings.json';
$default_settings = [
    'accent' => '#4c51bf',
    // Background images are disabled for admin customization
    'bg_type' => 'none',
    'bg_value' => '',
    'page_color' => '#ffffff',
    'night_mode' => 0,
    'card_bg' => '#ffffff',
    'text_color' => '#0f1724',
    'font_size' => '16'
];
$settings = $default_settings;
if(file_exists($settings_file)) {
    $json = @file_get_contents($settings_file);
    $parsed = @json_decode($json, true);
    if(is_array($parsed)) $settings = array_merge($default_settings, $parsed);
}

// Handle customization save
if(isset($_POST['save_customization'])) {
    $accent = preg_replace('/[^#A-Fa-f0-9]/', '', $_POST['accent'] ?? $default_settings['accent']);
    // Prevent changing background images. Only allow toggling night mode and font size.
    $bg_type = 'none';
    $bg_value = '';
    $page_color = $default_settings['page_color'];
    $night_mode = isset($_POST['night_mode']) && ($_POST['night_mode'] === '1' || $_POST['night_mode'] === 'on') ? true : false;
    $card_bg = trim($_POST['card_bg'] ?? $default_settings['card_bg']);
    $text_color = trim($_POST['text_color'] ?? $default_settings['text_color']);
    $font_size = preg_replace('/[^0-9]/','', $_POST['font_size'] ?? $default_settings['font_size']);

    $new = [
        'accent' => $accent ?: $default_settings['accent'],
        'bg_type' => $bg_type,
        'bg_value' => $bg_value,
        'page_color' => $page_color,
        'card_bg' => $card_bg ?: $default_settings['card_bg'],
        'text_color' => $text_color ?: $default_settings['text_color'],
        'font_size' => $font_size ?: $default_settings['font_size'],
        'night_mode' => $night_mode ? 1 : 0
    ];

    @file_put_contents($settings_file, json_encode($new, JSON_PRETTY_PRINT));
    $_SESSION['success'] = 'Customization saved';
    header('Location: admin.php#customize');
    exit;
}

// Handle customization reset
if(isset($_POST['reset_customization'])) {
    @file_put_contents($settings_file, json_encode($default_settings, JSON_PRETTY_PRINT));
    $_SESSION['success'] = 'Customization reset to defaults';
    header('Location: admin.php#customize');
    exit;
}

// Admin credentials - you should change these
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'password'); // Change this to a secure password

// Handle login
if(isset($_POST['login'])) {
    if($_POST['username'] === ADMIN_USERNAME && $_POST['password'] === ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $error = "Invalid credentials";
    }
}

// Handle logout
if(isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// Check if user is logged in
if(!isset($_SESSION['admin_logged_in'])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Login - MusicHub</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                height: 100vh;
                margin: 0;
                background: url('https://images.pexels.com/photos/3244513/pexels-photo-3244513.jpeg?auto=compress&cs=tinysrgb&w=1600') center/cover no-repeat fixed;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .overlay {
                position: fixed;
                inset: 0;
                background: rgba(10, 15, 25, 0.45);
                backdrop-filter: blur(4px);
            }
            .login-container {
                background: rgba(255,255,255,0.96);
                padding: 2.25rem;
                border-radius: 14px;
                box-shadow: 0 12px 35px rgba(7,12,34,0.45);
                width: 100%;
                max-width: 460px;
                z-index: 2;
            }
            .login-brand {
                display:flex; align-items:center; gap:12px; margin-bottom:12px;
            }
            .brand-logo {
                width:48px; height:48px; border-radius:10px; background:linear-gradient(135deg,#6e8efb,#a777e3); display:inline-flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:20px;
            }
            .login-container h1 {
                color: #102a43;
                font-size: 1.25rem;
                margin: 0 0 0.35rem 0;
            }
            .login-sub {
                color:#475569; font-size:0.95rem; margin-bottom:1rem;
            }
            .form-control { border-radius:10px; padding:0.75rem 1rem; }
            .btn-primary { border-radius:10px; padding:0.65rem 1rem; }
            .login-note { font-size:0.85rem; color:#64748b; margin-top:0.6rem; text-align:center; }
        </style>
    </head>
    <body>
        <div class="login-container">
            <h1>Admin Login</h1>
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Handle logout
if(isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// Check if user is logged in
if(!isset($_SESSION['admin_logged_in'])) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Login - MusicHub</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                height: 100vh;
                margin: 0;
                background: url('https://images.pexels.com/photos/164879/pexels-photo-164879.jpeg?auto=compress&cs=tinysrgb&w=1600') center/cover no-repeat fixed;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .overlay {
                position: fixed;
                inset: 0;
                background: rgba(10, 15, 25, 0.45);
                backdrop-filter: blur(4px);
            }
            .login-container {
                background: rgba(255,255,255,0.96);
                padding: 2.25rem;
                border-radius: 14px;
                box-shadow: 0 12px 35px rgba(7,12,34,0.45);
                width: 100%;
                max-width: 460px;
                z-index: 2;
            }
            .login-brand {
                display:flex; align-items:center; gap:12px; margin-bottom:12px;
            }
            .brand-logo {
                width:48px; height:48px; border-radius:10px; background:linear-gradient(135deg,#4c51bf,#6e8efb); display:inline-flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:20px;
            }
            .login-container h1 {
                color: #102a43;
                font-size: 1.25rem;
                margin: 0 0 0.35rem 0;
            }
            .login-sub {
                color:#475569; font-size:0.95rem; margin-bottom:1rem;
            }
            .form-control { border-radius:10px; padding:0.75rem 1rem; }
            .btn-primary { border-radius:10px; padding:0.65rem 1rem; }
            .login-note { font-size:0.85rem; color:#64748b; margin-top:0.6rem; text-align:center; }
        </style>
    </head>
    <body>
        <div class="login-container">
            <h1>Admin Login</h1>
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Handle song deletion
if(isset($_GET['delete_song'])) {
    $song_id = $_GET['delete_song'];
    $stmt = $conn->prepare("SELECT image_path, audio_path FROM songs WHERE id = ?");
    $stmt->execute([$song_id]);
    $song = $stmt->fetch();

    // Delete associated files
    if($song['audio_path'] && file_exists($song['audio_path'])) unlink($song['audio_path']);
    if($song['image_path'] && file_exists($song['image_path'])) unlink($song['image_path']);

    $stmt = $conn->prepare("DELETE FROM songs WHERE id = ?");
    $stmt->execute([$song_id]);
    header("Location: admin.php");
    exit;
}

// Handle artist deletion
if(isset($_GET['delete_artist'])) {
    $artist_id = $_GET['delete_artist'];
    $stmt = $conn->prepare("SELECT image_path FROM artists WHERE id = ?");
    $stmt->execute([$artist_id]);
    $artist = $stmt->fetch();

    if($artist['image_path'] && file_exists($artist['image_path'])) unlink($artist['image_path']);

    $stmt = $conn->prepare("DELETE FROM artists WHERE id = ?");
    $stmt->execute([$artist_id]);
    header("Location: admin.php#artists");
    exit;
}

// Handle song edit
if(isset($_POST['edit_song'])) {
    $song_id = $_POST['song_id'];
    $title = $_POST['title'];
    $artist_id = $_POST['artist_id'];
    $genre = $_POST['genre'];

    $audio_path = $_POST['current_audio'];
    if($_FILES['audio']['name']) {
        if($audio_path && file_exists($audio_path)) unlink($audio_path);
        $audio_extension = pathinfo($_FILES['audio']['name'], PATHINFO_EXTENSION);
        $audio_path = 'uploads/audio_' . uniqid() . '.' . $audio_extension;
        move_uploaded_file($_FILES['audio']['tmp_name'], $audio_path);
    }

    $image_path = $_POST['current_image'];
    if($_FILES['image']['name']) {
        if($image_path && file_exists($image_path)) unlink($image_path);
        $image_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_path = 'uploads/image_' . uniqid() . '.' . $image_extension;
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }

    $stmt = $conn->prepare("UPDATE songs SET title = ?, artist_id = ?, genre = ?, audio_path = ?, image_path = ? WHERE id = ?");
    $stmt->execute([$title, $artist_id, $genre, $audio_path, $image_path, $song_id]);
    header("Location: admin.php");
    exit;
}

// Handle artist edit
if(isset($_POST['edit_artist'])) {
    try {
        $artist_id = isset($_POST['artist_id']) ? trim($_POST['artist_id']) : '';
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $bio = isset($_POST['bio']) ? trim($_POST['bio']) : '';
        $image_path = isset($_POST['current_image']) ? $_POST['current_image'] : '';

        // Validate input
        if(empty($name)) {
            throw new Exception("Artist name is required");
        }

        // Less strict ID validation
        if(!is_numeric($artist_id)) {
            throw new Exception("Invalid artist ID format");
        }

        $conn->beginTransaction();

        // Verify artist exists
        $check_stmt = $conn->prepare("SELECT id FROM artists WHERE id = ?");
        $check_stmt->execute([$artist_id]);
        $artist_exists = $check_stmt->fetch();

        if(!$artist_exists) {
            throw new Exception("Artist not found");
        }

        // Handle new image upload if present
        if($_FILES['artist_image']['name'] && $_FILES['artist_image']['error'] == 0) {
            if (!file_exists('uploads')) {
                mkdir('uploads', 0777, true);
            }

            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if(!in_array($_FILES['artist_image']['type'], $allowed_types)) {
                throw new Exception("Invalid image format. Only JPG, PNG and GIF allowed");
            }

            // Delete old image if exists
            if($image_path && file_exists($image_path)) {
                unlink($image_path);
            }

            // Generate new image path and upload
            $image_extension = pathinfo($_FILES['artist_image']['name'], PATHINFO_EXTENSION);
            $image_path = 'uploads/artist_' . uniqid() . '.' . $image_extension;
            if (!move_uploaded_file($_FILES['artist_image']['tmp_name'], $image_path)) {
                throw new Exception("Failed to upload image");
            }
        }

        // Update the artist record
        $stmt = $conn->prepare("UPDATE artists SET name = ?, bio = ?, image_path = ? WHERE id = ?");
        if($stmt->execute([$name, $bio, $image_path, $artist_id])) {
            $conn->commit();
            $_SESSION['success'] = "Artist updated successfully";
        } else {
            throw new Exception("Failed to update artist");
        }

        header("Location: admin.php#artists");
        exit;
    } catch (Exception $e) {
        if($conn->inTransaction()) {
            $conn->rollBack();
        }
        $_SESSION['error'] = "Update failed: " . $e->getMessage();
        header("Location: admin.php#artists");
        exit;
    }
}

// Handle form submissions
if(isset($_POST['add_song'])) {
    $title = $_POST['title'];
    $artist_id = $_POST['artist_id'];
    $genre = $_POST['genre'];
    
    $audio_path = '';
    if($_FILES['audio']['name']) {
        // Generate unique filename and store in uploads directory
        $audio_extension = pathinfo($_FILES['audio']['name'], PATHINFO_EXTENSION);
        $audio_path = 'uploads/audio_' . uniqid() . '.' . $audio_extension;
        
        // Create uploads directory if it doesn't exist
        if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true);
        }
        
        move_uploaded_file($_FILES['audio']['tmp_name'], $audio_path);
    }
    
    $image_path = '';
    if($_FILES['image']['name']) {
        // Generate unique filename for image
        $image_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_path = 'uploads/image_' . uniqid() . '.' . $image_extension;
        
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
    }
    
    $stmt = $conn->prepare("INSERT INTO songs (title, artist_id, genre, audio_path, image_path, upload_date) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$title, $artist_id, $genre, $audio_path, $image_path]);
}

if(isset($_POST['add_artist'])) {
    $name = $_POST['name'];
    $bio = $_POST['bio'];
    
    $image_path = '';
    if($_FILES['artist_image']['name']) {
        // Generate unique filename for artist image
        $image_extension = pathinfo($_FILES['artist_image']['name'], PATHINFO_EXTENSION);
        $image_path = 'uploads/artist_' . uniqid() . '.' . $image_extension;
        
        // Create uploads directory if it doesn't exist
        if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true);
        }
        
        move_uploaded_file($_FILES['artist_image']['tmp_name'], $image_path);
    }
    
    $stmt = $conn->prepare("INSERT INTO artists (name, bio, image_path) VALUES (?, ?, ?)");
    $stmt->execute([$name, $bio, $image_path]);
    header("Location: admin.php#artists");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" class="<?php echo (!empty($settings['night_mode']) ? 'night' : ''); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MusicHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root{
            --accent: <?php echo htmlspecialchars($settings['accent']); ?>;
            --card-bg: <?php echo htmlspecialchars($settings['card_bg']); ?>;
            --text-color: <?php echo ($settings['night_mode'] ? '#eef2ff' : htmlspecialchars($settings['text_color'])); ?>;
            --font-size: <?php echo htmlspecialchars($settings['font_size']); ?>px;
            --page-bg: <?php echo ($settings['night_mode'] ? '#0f1724' : (htmlspecialchars($settings['page_color'] ?? '#ffffff'))); ?>;
            --sidebar-bg: <?php echo ($settings['night_mode'] ? '#071022' : 'linear-gradient(180deg,#ffffff,#fbfbff)'); ?>;
            --nav-link-color: <?php echo ($settings['night_mode'] ? '#eef2ff' : '#334155'); ?>;
        }
        /* Force all text to use the theme text color when night mode is active */
        .night, .night * { color: var(--text-color) !important; }

        /* Dark-mode form, modal and table adjustments */
        .night .form-control, .night select.form-control, .night textarea.form-control {
            background: transparent !important;
            color: var(--text-color) !important;
            border-color: rgba(255,255,255,0.08) !important;
            box-shadow: none !important;
        }

        .night .form-control::placeholder, .night textarea::placeholder {
            color: rgba(238,242,255,0.7) !important;
        }

        .night .card, .night .modal-content, .night .dropdown-menu {
            background: var(--card-bg) !important;
            color: var(--text-color) !important;
            border-color: rgba(255,255,255,0.04) !important;
        }

        .night .table, .night table {
            color: var(--text-color) !important;
            border-color: rgba(255,255,255,0.04) !important;
        }

        .night .table thead th, .night .table tbody td {
            background: transparent !important;
            color: var(--text-color) !important;
        }

        .night .modal-header, .night .modal-body, .night .modal-footer {
            background: transparent !important;
            color: var(--text-color) !important;
        }

        .night .artist-card { background: rgba(255,255,255,0.02); }

        .night .btn-outline-primary { border-color: rgba(255,255,255,0.12); color: var(--text-color) !important; }
        .night .btn-primary { color: white !important; }
        body { font-family: 'Poppins', sans-serif; color:var(--text-color); font-size:var(--font-size); background: var(--page-bg); }
        .sidebar { background: var(--sidebar-bg); min-height:100vh; padding:2rem 1rem; border-right:1px solid rgba(15,23,36,0.04); }
        .main-content { padding:2.25rem; }
        .card { background:var(--card-bg); border:none; border-radius:12px; box-shadow:0 8px 30px rgba(15,23,42,0.06); }
        .nav-link { color: var(--nav-link-color); padding:0.7rem 1rem; border-radius:10px; margin-bottom:0.5rem; display:flex; align-items:center; gap:0.6rem; }
        .nav-link:hover, .nav-link.active { background: rgba(0,0,0,0.04); color: var(--accent); transform:translateY(-1px); }
        .form-control { border-radius:10px; padding:0.7rem 1rem; border:1px solid rgba(15,23,42,0.06); }
        .artist-card { padding:8px; transition:all 180ms ease; }
        .artist-card:hover { background: rgba(76,81,191,0.04); transform:translateX(2px); }
        #artistList img, .artist-card i { vertical-align:middle }
        #songsTable thead th { background: linear-gradient(90deg, rgba(76,81,191,0.05), rgba(76,81,191,0.02)); }
        .btn-outline-primary { border-radius:10px; }
        .accent { color:var(--accent) !important }
        .btn-accent { background:var(--accent); border-color:var(--accent); color:white; border-radius:8px; padding:.55rem .9rem; box-shadow:0 6px 18px rgba(76,81,191,0.12); }
        .btn-accent:hover { filter:brightness(.95); transform:translateY(-1px); }
        .btn-outline-secondary { border-radius:8px; padding:.5rem .85rem; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 sidebar">
                <h3 class="mb-4">Admin Panel</h3>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="#songs" data-bs-toggle="tab">
                        <i class="fas fa-music me-2"></i>Songs
                    </a>
                    <a class="nav-link" href="#artists" data-bs-toggle="tab">
                        <i class="fas fa-user me-2"></i>Artists
                    </a>
                    <a class="nav-link" href="#customize" data-bs-toggle="tab">
                        <i class="fas fa-sliders-h me-2"></i>Customize
                    </a>
                    <a class="nav-link text-danger" href="?logout=1">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                </nav>
            </div>
            
            <div class="col-md-9 col-lg-10 main-content">
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if(isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if(isset($_SESSION['info'])): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <?php echo $_SESSION['info']; unset($_SESSION['info']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="songs">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2>Manage Songs</h2>
                            <div>
                                <button id="globalAddSongBtn" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addSongModal">
                                    <i class="fas fa-plus me-2"></i>Add New Song
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <input id="artistSearch" type="text" class="form-control" placeholder="Search artists...">
                                        </div>
                                        <div id="artistList" style="max-height:420px; overflow:auto">
                                            <?php
                                            $artistStmt = $conn->query("SELECT id, name, image_path FROM artists ORDER BY name");
                                            while($a = $artistStmt->fetch()) {
                                                $img = $a['image_path'] ? "<img src='" . htmlspecialchars($a['image_path']) . "' class='rounded-circle me-2' width='40' height='40'>" : "<i class='fas fa-user-circle fa-2x me-2'></i>";
                                                echo "<div class='d-flex align-items-center mb-2 artist-card' data-artist-id='" . $a['id'] . "' style='cursor:pointer' onclick='selectArtist(" . $a['id'] . ", " . json_encode($a['name']) . ")'>";
                                                echo $img . "<div>" . htmlspecialchars($a['name']) . "</div>";
                                                echo "</div>";
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <h5 id="selectedArtistTitle">All Songs</h5>
                                                <small id="selectedArtistCount" class="text-muted"></small>
                                            </div>
                                            <div class="d-flex">
                                                <input id="songSearch" type="text" class="form-control me-2" placeholder="Search songs by title or genre...">
                                                <button id="addSongForArtistBtn" class="btn btn-outline-primary" style="display:none"><i class="fas fa-plus me-1"></i>Add for Artist</button>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table" id="songsTable">
                                                <thead>
                                                    <tr>
                                                        <th>Image</th>
                                                        <th>Title</th>
                                                        <th>Artist</th>
                                                        <th>Genre</th>
                                                        <th>Upload Date</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Populated by JS -->
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                        // Provide songs JSON for client-side filtering and operations
                        $allSongsStmt = $conn->query("SELECT s.*, a.name as artist_name, DATE_FORMAT(s.upload_date, '%Y-%m-%d %H:%i') as upload_date FROM songs s LEFT JOIN artists a ON s.artist_id = a.id ORDER BY s.upload_date DESC");
                        $allSongs = $allSongsStmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                    </div>
                    
                    <div class="tab-pane fade" id="artists">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2>Manage Artists</h2>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addArtistModal">
                                <i class="fas fa-plus me-2"></i>Add New Artist
                            </button>
                        </div>
                        
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Bio</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $stmt = $conn->query("SELECT * FROM artists");
                                            while($artist = $stmt->fetch()) {
                                                echo "<tr>";
                                                echo "<td>" . ($artist['image_path'] ? "<img src='" . htmlspecialchars($artist['image_path']) . "' width='50' height='50' class='rounded-circle'>" : "<i class='fas fa-user-circle fa-2x'></i>") . "</td>";
                                                echo "<td>" . htmlspecialchars($artist['name']) . "</td>";
                                                echo "<td>" . htmlspecialchars(substr($artist['bio'], 0, 100)) . "...</td>";
                                                echo "<td>
                                                    <button class='btn btn-sm btn-warning me-2' onclick='editArtist(" . json_encode([
                                                        'id' => $artist['id'],
                                                        'name' => $artist['name'],
                                                        'bio' => $artist['bio'],
                                                        'image_path' => $artist['image_path']
                                                    ]) . ")'><i class='fas fa-edit'></i></button>
                                                    <a href='?delete_artist=" . $artist['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this artist?\")'><i class='fas fa-trash'></i></a>
                                                </td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="customize">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h2>Customize Your Page</h2>
                                <p class="text-muted">Change colors, background and fonts to personalise the admin view.</p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <form method="post">
                                    <div class="row gy-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Accent Color</label>
                                            <input type="color" name="accent" value="<?php echo htmlspecialchars($settings['accent']); ?>" class="form-control form-control-color">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Card Background</label>
                                            <input type="color" name="card_bg" value="<?php echo htmlspecialchars($settings['card_bg']); ?>" class="form-control form-control-color">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Text Color</label>
                                            <input type="color" name="text_color" value="<?php echo htmlspecialchars($settings['text_color']); ?>" class="form-control form-control-color">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Font Size (px)</label>
                                            <input id="fontSizeInput" type="number" name="font_size" min="12" max="32" value="<?php echo htmlspecialchars($settings['font_size']); ?>" class="form-control">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Night Mode</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="nightModeToggle" name="night_mode" <?php echo (!empty($settings['night_mode']) ? 'checked' : ''); ?>>
                                                <label class="form-check-label" for="nightModeToggle"><?php echo (!empty($settings['night_mode']) ? 'Enabled' : 'Disabled'); ?></label>
                                            </div>
                                            <small class="text-muted">Toggle between dark page color and white page color. Background images are disabled.</small>
                                        </div>

                                        <div class="col-12">
                                            <div class="d-flex gap-2">
                                                <button type="submit" name="save_customization" class="btn btn-accent">Save Changes</button>
                                                <button type="submit" name="reset_customization" class="btn btn-outline-secondary">Reset Defaults</button>
                                                <div class="ms-auto">
                                                    <div class="preview p-3" style="background:var(--card-bg); border-radius:8px; box-shadow:0 6px 18px rgba(15,23,42,0.06);">
                                                        <div style="display:flex; align-items:center; gap:12px">
                                                            <div style="width:40px;height:40px;background:var(--accent);border-radius:8px"></div>
                                                            <div>
                                                                <div style="font-weight:600">Preview Title</div>
                                                                <div style="font-size:13px;color:#6b7280">Preview subtitle</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Song Modal -->
    <div class="modal fade" id="addSongModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Song</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <input type="text" name="title" class="form-control" placeholder="Song Title" required>
                        </div>
                        <div class="mb-3">
                            <select name="artist_id" class="form-control" required>
                                <option value="">Select Artist</option>
                                <?php
                                $stmt = $conn->query("SELECT id, name FROM artists");
                                while($artist = $stmt->fetch()) {
                                    echo "<option value='" . $artist['id'] . "'>" . htmlspecialchars($artist['name']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <select name="genre" class="form-control" required>
                                <option value="">Select Genre</option>
                                <option value="Secular">Secular</option>
                                <option value="Gospel">Gospel</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Audio File</label>
                            <input type="file" name="audio" class="form-control" accept="audio/*" required>
                        </div>
                        <div class="mb-3">
                            <label>Cover Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <button type="submit" name="add_song" class="btn btn-primary w-100">Add Song</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Artist Modal -->
    <div class="modal fade" id="addArtistModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Artist</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <input type="text" name="name" class="form-control" placeholder="Artist Name" required>
                        </div>
                        <div class="mb-3">
                            <textarea name="bio" class="form-control" placeholder="Artist Bio" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Artist Image</label>
                            <input type="file" name="artist_image" class="form-control" accept="image/*">
                        </div>
                        <button type="submit" name="add_artist" class="btn btn-primary w-100">Add Artist</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Song Modal -->
    <div class="modal fade" id="editSongModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Song</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="song_id" id="edit_song_id">
                        <input type="hidden" name="current_audio" id="edit_current_audio">
                        <input type="hidden" name="current_image" id="edit_current_image">
                        <div class="mb-3">
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <select name="artist_id" id="edit_song_artist_id" class="form-control" required>
                                <?php
                                $stmt = $conn->query("SELECT id, name FROM artists");
                                while($artist = $stmt->fetch()) {
                                    echo "<option value='" . $artist['id'] . "'>" . htmlspecialchars($artist['name']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <select name="genre" id="edit_genre" class="form-control" required>
                                <option value="Secular">Secular</option>
                                <option value="Gospel">Gospel</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>New Audio File (optional)</label>
                            <input type="file" name="audio" class="form-control" accept="audio/*">
                        </div>
                        <div class="mb-3">
                            <label>New Cover Image (optional)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <button type="submit" name="edit_song" class="btn btn-primary w-100">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Artist Modal -->
    <div class="modal fade" id="editArtistModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Artist</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data" id="editArtistForm">
                        <input type="hidden" name="artist_id" id="edit_artist_id">
                        <input type="hidden" name="current_image" id="edit_artist_current_image">
                        <div class="mb-3">
                            <label>Artist Name</label>
                            <input type="text" name="name" id="edit_artist_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Artist Bio</label>
                            <textarea name="bio" id="edit_artist_bio" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Current Image</label>
                            <div id="current_image_preview" class="mb-2"></div>
                            <label>New Artist Image (optional)</label>
                            <input type="file" name="artist_image" class="form-control" accept="image/*">
                        </div>
                        <button type="submit" name="edit_artist" class="btn btn-primary w-100">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editSong(song) {
            document.getElementById('edit_song_id').value = song.id;
            document.getElementById('edit_title').value = song.title;
            document.getElementById('edit_song_artist_id').value = song.artist_id;
            document.getElementById('edit_genre').value = song.genre;
            document.getElementById('edit_current_audio').value = song.audio_path;
            document.getElementById('edit_current_image').value = song.image_path;
            new bootstrap.Modal(document.getElementById('editSongModal')).show();
        }

        function editArtist(artist) {
            if (!artist || !artist.id) {
                console.error('Invalid artist data');
                return;
            }

            const artistId = parseInt(artist.id);
            if (isNaN(artistId) || artistId <= 0) {
                console.error('Invalid artist ID');
                return;
            }

            // Set form values
            document.getElementById('edit_artist_id').value = artistId;
            document.getElementById('edit_artist_name').value = artist.name || '';
            document.getElementById('edit_artist_bio').value = artist.bio || '';
            document.getElementById('edit_artist_current_image').value = artist.image_path || '';

            // Show current image if exists
            const currentImagePreview = document.getElementById('current_image_preview');
            if (artist.image_path) {
                currentImagePreview.innerHTML = `<img src="${artist.image_path}" class="img-thumbnail" width="100">`;
            } else {
                currentImagePreview.innerHTML = '<i class="fas fa-user-circle fa-3x"></i>';
            }

            // Add event listener for form submission
            const form = document.getElementById('editArtistForm');
            form.onsubmit = function(e) {
                const nameField = document.getElementById('edit_artist_name');
                if (!nameField.value.trim()) {
                    e.preventDefault();
                    alert('Artist name is required');
                    return false;
                }
                // Double check the ID is still valid
                const idField = document.getElementById('edit_artist_id');
                if (!idField.value || isNaN(parseInt(idField.value))) {
                    e.preventDefault();
                    alert('Invalid artist ID');
                    return false;
                }
                return true;
            };

            // Show the modal
            new bootstrap.Modal(document.getElementById('editArtistModal')).show();
        }

        // --- Dynamic songs & artists UI ---
        var allSongs = <?php echo json_encode($allSongs ?? []); ?>;
        var selectedArtistId = null;

        function renderSongs(list) {
            var tbody = document.querySelector('#songsTable tbody');
            tbody.innerHTML = '';
            list.forEach(function(song) {
                var tr = document.createElement('tr');

                var imgTd = document.createElement('td');
                if (song.image_path) {
                    imgTd.innerHTML = "<img src='" + song.image_path + "' width='50' height='50' class='rounded'>";
                } else {
                    imgTd.innerHTML = "<i class='fas fa-music fa-2x'></i>";
                }

                var titleTd = document.createElement('td');
                titleTd.textContent = song.title;

                var artistTd = document.createElement('td');
                artistTd.textContent = song.artist_name || '';

                var genreTd = document.createElement('td');
                genreTd.textContent = song.genre || '';

                var dateTd = document.createElement('td');
                dateTd.textContent = song.upload_date || '';

                var actionsTd = document.createElement('td');
                actionsTd.innerHTML = "<button class='btn btn-sm btn-warning me-2' onclick='editSong(" + JSON.stringify(song) + ")'><i class='fas fa-edit'></i></button>" +
                                      "<a href='?delete_song=" + song.id + "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this song?\")'><i class='fas fa-trash'></i></a>";

                tr.appendChild(imgTd);
                tr.appendChild(titleTd);
                tr.appendChild(artistTd);
                tr.appendChild(genreTd);
                tr.appendChild(dateTd);
                tr.appendChild(actionsTd);

                tbody.appendChild(tr);
            });

            var countEl = document.getElementById('selectedArtistCount');
            if (countEl) countEl.textContent = list.length + ' song(s)';
        }

        function selectArtist(id, name) {
            selectedArtistId = id;
            document.getElementById('selectedArtistTitle').textContent = name || 'Artist Songs';
            document.getElementById('addSongForArtistBtn').style.display = 'inline-block';

            // filter songs for artist
            var filtered = allSongs.filter(function(s){ return parseInt(s.artist_id) === parseInt(id); });
            var q = document.getElementById('songSearch').value.trim().toLowerCase();
            if (q) filtered = filtered.filter(function(s){ return (s.title||'').toLowerCase().includes(q) || (s.genre||'').toLowerCase().includes(q); });
            renderSongs(filtered);

            // highlight selection
            document.querySelectorAll('.artist-card').forEach(function(el){ el.classList.remove('border','border-primary'); });
            var el = document.querySelector('.artist-card[data-artist-id="' + id + '"]');
            if (el) el.classList.add('border','border-primary','rounded');
        }

        // initial render - all songs
        renderSongs(allSongs);

        // song search
        document.getElementById('songSearch').addEventListener('input', function(e){
            var q = e.target.value.trim().toLowerCase();
            var list = allSongs.slice();
            if (selectedArtistId) {
                list = list.filter(function(s){ return parseInt(s.artist_id) === parseInt(selectedArtistId); });
            }
            if (q) {
                list = list.filter(function(s){ return (s.title||'').toLowerCase().includes(q) || (s.genre||'').toLowerCase().includes(q); });
            }
            renderSongs(list);
        });

        // artist search
        document.getElementById('artistSearch').addEventListener('input', function(e){
            var q = e.target.value.trim().toLowerCase();
            document.querySelectorAll('#artistList .artist-card').forEach(function(card){
                var name = card.textContent.trim().toLowerCase();
                card.style.display = name.includes(q) ? '' : 'none';
            });
        });

        // Add song for selected artist button behavior
        document.getElementById('addSongForArtistBtn').addEventListener('click', function(){
            if (!selectedArtistId) return;
            var sel = document.querySelector('#addSongModal select[name="artist_id"]');
            if (sel) sel.value = selectedArtistId;
            new bootstrap.Modal(document.getElementById('addSongModal')).show();
        });

        // Global add song button - if an artist is selected, preselect
        document.getElementById('globalAddSongBtn').addEventListener('click', function(){
            var sel = document.querySelector('#addSongModal select[name="artist_id"]');
            if (sel && selectedArtistId) sel.value = selectedArtistId;
        });


        // Update preview when new image is selected
        document.querySelector('#editArtistModal input[name="artist_image"]').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('current_image_preview').innerHTML = 
                        `<img src="${e.target.result}" class="img-thumbnail" width="100">`;
                };
                reader.readAsDataURL(file);
            }
        });

        // Preview new image before upload
        document.querySelector('input[name="artist_image"]').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('current_image_preview').innerHTML = ` <img src="${e.target.result}" class="img-thumbnail" width="100">`;
                }
                reader.readAsDataURL(file);
            }
        });

        // Prevent form resubmission on refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }

        // Activate tab based on URL hash and keep hash in URL when switching
        (function(){
            var hash = window.location.hash || '#songs';
            var triggerEl = document.querySelector('.nav-link[href="' + hash + '"]');
            if (triggerEl) {
                var tab = new bootstrap.Tab(triggerEl);
                tab.show();
            }

            document.querySelectorAll('.nav-link[data-bs-toggle="tab"]').forEach(function(el){
                el.addEventListener('shown.bs.tab', function(e){
                    var href = e.target.getAttribute('href');
                    if(history.replaceState) history.replaceState(null, null, href);
                });
            });
        })();

        // Live preview: night mode toggle and font-size
        (function(){
            var nightToggle = document.getElementById('nightModeToggle');
            var fontInput = document.getElementById('fontSizeInput');

            function applyPreview(){
                var isNight = nightToggle && nightToggle.checked;
                if(isNight){
                    document.documentElement.style.setProperty('--page-bg', '#0f1724');
                    document.documentElement.style.setProperty('--text-color', '#eef2ff');
                    document.documentElement.style.setProperty('--sidebar-bg', '#071022');
                    document.documentElement.style.setProperty('--nav-link-color', '#eef2ff');
                    document.documentElement.style.setProperty('--card-bg', '#0b1224');
                } else {
                    document.documentElement.style.setProperty('--page-bg', 'linear-gradient(180deg,#f6f8fb,#eef2ff)');
                    document.documentElement.style.setProperty('--text-color', '<?php echo htmlspecialchars($settings['text_color']); ?>');
                    document.documentElement.style.setProperty('--sidebar-bg', 'linear-gradient(180deg,#ffffff,#fbfbff)');
                    document.documentElement.style.setProperty('--nav-link-color', '#334155');
                    document.documentElement.style.setProperty('--card-bg', '<?php echo htmlspecialchars($settings['card_bg']); ?>');
                }
                // Toggle a class so we can force all text to follow the theme color
                document.documentElement.classList.toggle('night', isNight);

                if(fontInput){
                    var v = parseInt(fontInput.value) || <?php echo intval($settings['font_size']); ?>;
                    document.documentElement.style.setProperty('--font-size', v + 'px');
                }
            }

            if(nightToggle) nightToggle.addEventListener('change', applyPreview);
            if(fontInput) fontInput.addEventListener('input', applyPreview);

            // initial apply for page load
            applyPreview();
        })();
    </script>
</body>
</html>