<?php
session_start(); // Start session at the top

include 'db_connect.php';

// Simple admin credentials (replace with database-driven auth in production)
$admin_username = 'admin';
$admin_password = 'password123';

// Handle login
if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    if($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: upload.php"); // Redirect to clear POST data
        exit;
    } else {
        $login_error = "Invalid username or password.";
    }
}

// Handle logout
if(isset($_GET['logout'])) {
    session_destroy();
    header("Location: upload.php");
    exit;
}

// Function to check if user is admin
function isAdmin() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Upload Music</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">MusicHub</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="upload.php">Upload</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <?php if(isAdmin()): ?>
                        <li class="nav-item"><a class="nav-link" href="upload.php?logout=1">Logout</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <?php if(!isAdmin()): ?>
            <!-- Login Form -->
            <div class="upload-form">
                <h2 class="mb-4">Admin Login</h2>
                <?php if(isset($login_error)): ?>
                    <div class="alert alert-danger"><?php echo $login_error; ?></div>
                <?php endif; ?>
                <form action="" method="post">
                    <div class="mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary">Login</button>
                </form>
            </div>
        <?php else: ?>
            <!-- Admin Section -->
            <div class="upload-form">
                <h2 class="mb-4">Upload Song</h2>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <input type="text" name="title" class="form-control" placeholder="Song Title" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="artist" class="form-control" placeholder="Artist" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="genre" class="form-control" placeholder="Genre" required>
                    </div>
                    <div class="mb-3">
                        <input type="file" name="audio" class="form-control" accept="audio/*">
                    </div>
                    <div class="mb-3">
                        <input type="file" name="video" class="form-control" accept="video/*">
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Upload</button>
                </form>

                <?php
                // Handle song upload
                if(isset($_POST['submit'])) {
                    $title = $_POST['title'];
                    $artist = $_POST['artist'];
                    $genre = $_POST['genre'];
                    
                    $audio_path = '';
                    $video_path = '';
                    
                    $audio_dir = 'uploads/audio/';
                    $video_dir = 'uploads/video/';
                    
                    if (!file_exists($audio_dir)) {
                        mkdir($audio_dir, 0777, true);
                    }
                    if (!file_exists($video_dir)) {
                        mkdir($video_dir, 0777, true);
                    }
                    
                    if($_FILES['audio']['name']) {
                        $audio_path = $audio_dir . basename($_FILES['audio']['name']);
                        if (!move_uploaded_file($_FILES['audio']['tmp_name'], $audio_path)) {
                            echo "<div class='alert alert-danger mt-3'>Failed to upload audio file.</div>";
                        }
                    }
                    
                    if($_FILES['video']['name']) {
                        $video_path = $video_dir . basename($_FILES['video']['name']);
                        if (!move_uploaded_file($_FILES['video']['tmp_name'], $video_path)) {
                            echo "<div class='alert alert-danger mt-3'>Failed to upload video file.</div>";
                        }
                    }
                    
                    try {
                        $stmt = $conn->prepare("INSERT INTO songs (title, artist, genre, audio_path, video_path) VALUES (?, ?, ?, ?, ?)");
                        $stmt->execute([$title, $artist, $genre, $audio_path ?: NULL, $video_path ?: NULL]);
                        echo "<div class='alert alert-success mt-3'>Song uploaded successfully!</div>";
                    } catch (PDOException $e) {
                        echo "<div class='alert alert-danger mt-3'>Database error: " . $e->getMessage() . "</div>";
                    }
                }

                // Handle song removal
                if(isset($_GET['remove'])) {
                    $song_id = $_GET['remove'];
                    try {
                        $stmt = $conn->prepare("SELECT audio_path, video_path FROM songs WHERE id = ?");
                        $stmt->execute([$song_id]);
                        $song = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                        if($song['audio_path'] && file_exists($song['audio_path'])) {
                            unlink($song['audio_path']);
                        }
                        if($song['video_path'] && file_exists($song['video_path'])) {
                            unlink($song['video_path']);
                        }
                        
                        $stmt = $conn->prepare("DELETE FROM songs WHERE id = ?");
                        $stmt->execute([$song_id]);
                        header("Location: upload.php"); // Redirect to refresh page
                        exit;
                    } catch (PDOException $e) {
                        echo "<div class='alert alert-danger mt-3'>Error removing song: " . $e->getMessage() . "</div>";
                    }
                }

                // Handle song edit with file uploads
                if(isset($_POST['update'])) {
                    $song_id = $_POST['song_id'];
                    $title = $_POST['title'];
                    $artist = $_POST['artist'];
                    $genre = $_POST['genre'];

                    // Fetch existing paths to update or keep them
                    $stmt = $conn->prepare("SELECT audio_path, video_path FROM songs WHERE id = ?");
                    $stmt->execute([$song_id]);
                    $song = $stmt->fetch(PDO::FETCH_ASSOC);
                    $audio_path = $song['audio_path'];
                    $video_path = $song['video_path'];

                    $audio_dir = 'uploads/audio/';
                    $video_dir = 'uploads/video/';
                    
                    if (!file_exists($audio_dir)) {
                        mkdir($audio_dir, 0777, true);
                    }
                    if (!file_exists($video_dir)) {
                        mkdir($video_dir, 0777, true);
                    }

                    // Handle new audio upload
                    if($_FILES['audio']['name']) {
                        // Delete old audio file if it exists
                        if($audio_path && file_exists($audio_path)) {
                            unlink($audio_path);
                        }
                        $audio_path = $audio_dir . basename($_FILES['audio']['name']);
                        if (!move_uploaded_file($_FILES['audio']['tmp_name'], $audio_path)) {
                            echo "<div class='alert alert-danger mt-3'>Failed to upload new audio file.</div>";
                        }
                    }

                    // Handle new video upload
                    if($_FILES['video']['name']) {
                        // Delete old video file if it exists
                        if($video_path && file_exists($video_path)) {
                            unlink($video_path);
                        }
                        $video_path = $video_dir . basename($_FILES['video']['name']);
                        if (!move_uploaded_file($_FILES['video']['tmp_name'], $video_path)) {
                            echo "<div class='alert alert-danger mt-3'>Failed to upload new video file.</div>";
                        }
                    }

                    try {
                        $stmt = $conn->prepare("UPDATE songs SET title = ?, artist = ?, genre = ?, audio_path = ?, video_path = ? WHERE id = ?");
                        $stmt->execute([$title, $artist, $genre, $audio_path ?: NULL, $video_path ?: NULL, $song_id]);
                        header("Location: upload.php"); // Redirect to refresh page and show Edit/Delete buttons
                        exit;
                    } catch (PDOException $e) {
                        echo "<div class='alert alert-danger mt-3'>Error updating song: " . $e->getMessage() . "</div>";
                    }
                }

                // Display Existing Songs in Table
                echo "<h3 class='mt-5'>Existing Songs</h3>";
                $stmt = $conn->prepare("SELECT * FROM songs");
                $stmt->execute();
                $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if($songs) {
                    echo "<div class='table-responsive'>";
                    echo "<table class='table table-striped table-hover'>";
                    echo "<thead><tr><th>Title</th><th>Artist</th><th>Genre</th><th>Audio</th><th>Video</th><th>Actions</th></tr></thead><tbody>";
                    
                    foreach($songs as $song) {
                        if(isset($_GET['edit']) && $_GET['edit'] == $song['id']) {
                            // Edit form inline in the table with file uploads
                            echo "<tr>";
                            echo "<form method='post' action='' enctype='multipart/form-data'>";
                            echo "<input type='hidden' name='song_id' value='" . $song['id'] . "'>";
                            echo "<td><input type='text' name='title' class='form-control' value='" . htmlspecialchars($song['title']) . "' required></td>";
                            echo "<td><input type='text' name='artist' class='form-control' value='" . htmlspecialchars($song['artist']) . "' required></td>";
                            echo "<td><input type='text' name='genre' class='form-control' value='" . htmlspecialchars($song['genre']) . "' required></td>";
                            echo "<td>";
                            echo ($song['audio_path'] ? "<audio controls class='w-100'><source src='" . htmlspecialchars($song['audio_path']) . "' type='audio/mpeg'></audio>" : "N/A");
                            echo "<input type='file' name='audio' class='form-control mt-2' accept='audio/*'>";
                            echo "</td>";
                            echo "<td>";
                            echo ($song['video_path'] ? "<video controls class='w-100' style='max-height:100px'><source src='" . htmlspecialchars($song['video_path']) . "' type='video/mp4'></video>" : "N/A");
                            echo "<input type='file' name='video' class='form-control mt-2' accept='video/*'>";
                            echo "</td>";
                            echo "<td>";
                            echo "<button type='submit' name='update' class='btn btn-success btn-sm me-2'>Save</button>";
                            echo "<a href='upload.php' class='btn btn-secondary btn-sm'>Cancel</a>";
                            echo "</td>";
                            echo "</form>";
                            echo "</tr>";
                        } else {
                            // Normal table row
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($song['title']) . "</td>";
                            echo "<td>" . htmlspecialchars($song['artist']) . "</td>";
                            echo "<td>" . htmlspecialchars($song['genre']) . "</td>";
                            echo "<td>" . ($song['audio_path'] ? "<audio controls class='w-100'><source src='" . htmlspecialchars($song['audio_path']) . "' type='audio/mpeg'></audio>" : "N/A") . "</td>";
                            echo "<td>" . ($song['video_path'] ? "<video controls class='w-100' style='max-height:100px'><source src='" . htmlspecialchars($song['video_path']) . "' type='video/mp4'></video>" : "N/A") . "</td>";
                            echo "<td>";
                            echo "<a href='upload.php?edit=" . $song['id'] . "' class='btn btn-warning btn-sm me-2'>Edit</a>";
                            echo "<a href='upload.php?remove=" . $song['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this song?\")'>Delete</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    }
                    echo "</tbody></table></div>";
                } else {
                    echo "<p>No songs available.</p>";
                }
                ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>