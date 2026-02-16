
<?php
header('Content-Type: application/json');
include 'db_connect.php';

if (isset($_GET['artist_id'])) {
    $artist_id = $_GET['artist_id'];
    
    try {
        $stmt = $conn->prepare("SELECT id, title, genre, audio_path FROM songs WHERE artist_id = ?");
        $stmt->execute([$artist_id]);
        $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Include file size (bytes and MB) for each song if file exists on server
        foreach ($songs as &$s) {
            $s['size_bytes'] = null;
            $s['size_mb'] = null;
            if (!empty($s['audio_path']) && file_exists($s['audio_path'])) {
                $bytes = filesize($s['audio_path']);
                $s['size_bytes'] = $bytes;
                // two decimal megabytes
                $s['size_mb'] = round($bytes / 1048576, 2);
            }
        }

        echo json_encode($songs);
    } catch(PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Artist ID parameter is required']);
}
?>
