<?php
session_start();
include 'db_connect.php';

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
                background: linear-gradient(135deg, #6e8efb, #a777e3);
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .login-container {
                background: white;
                padding: 2rem;
                border-radius: 15px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
                width: 100%;
                max-width: 400px;
            }
            .login-container h1 {
                color: #4a5568;
                font-size: 1.5rem;
                margin-bottom: 1.5rem;
                text-align: center;
            }
            .form-control {
                border-radius: 10px;
                padding: 0.75rem 1rem;
            }
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
                background: linear-gradient(135deg, #6e8efb, #a777e3);
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .login-container {
                background: white;
                padding: 2rem;
                border-radius: 15px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.1);
                width: 100%;
                max-width: 400px;
            }
            .login-container h1 {
                color: #4a5568;
                font-size: 1.5rem;
                margin-bottom: 1.5rem;
                text-align: center;
            }
            .form-control {
                border-radius: 10px;
                padding: 0.75rem 1rem;
            }
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
    header("Location: admin.php");
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MusicHub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
        }
        .sidebar {
            background: white;
            min-height: 100vh;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            padding: 2rem 1rem;
        }
        .main-content {
            padding: 2rem;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .nav-link {
            color: #4a5568;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            margin-bottom: 0.5rem;
            transition: all 0.3s;
        }
        .nav-link:hover, .nav-link.active {
            background: #f8f9fa;
            color: #4c51bf;
        }
        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
        }
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
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2>Manage Songs</h2>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSongModal">
                                <i class="fas fa-plus me-2"></i>Add New Song
                            </button>
                        </div>
                        
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
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
                                            <?php
                                            $stmt = $conn->query("SELECT s.*, a.name as artist_name, DATE_FORMAT(s.upload_date, '%Y-%m-%d %H:%i') as upload_date FROM songs s LEFT JOIN artists a ON s.artist_id = a.id");
                                            while($song = $stmt->fetch()) {
                                                echo "<tr>";
                                                echo "<td>" . ($song['image_path'] ? "<img src='" . htmlspecialchars($song['image_path']) . "' width='50' height='50' class='rounded'>" : "<i class='fas fa-music fa-2x'></i>") . "</td>";
                                                echo "<td>" . htmlspecialchars($song['title']) . "</td>";
                                                echo "<td>" . htmlspecialchars($song['artist_name']) . "</td>";
                                                echo "<td>" . htmlspecialchars($song['genre']) . "</td>";
                                                echo "<td>" . $song['upload_date'] . "</td>";
                                                echo "<td>
                                                    <button class='btn btn-sm btn-warning me-2' onclick='editSong(" . json_encode($song) . ")'><i class='fas fa-edit'></i></button>
                                                    <a href='?delete_song=" . $song['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this song?\")'><i class='fas fa-trash'></i></a>
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
                            <select name="artist_id" id="edit_artist_id" class="form-control" required>
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
            document.getElementById('edit_artist_id').value = song.artist_id;
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
    </script>
</body>
</html>