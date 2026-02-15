
<?php
header('Content-Type: application/json');
include 'db_connect.php';

if (isset($_GET['artist_id'])) {
    $artist_id = $_GET['artist_id'];
    
    try {
        $stmt = $conn->prepare("SELECT title, genre, audio_path FROM songs WHERE artist_id = ?");
        $stmt->execute([$artist_id]);
        $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($songs);
    } catch(PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Artist ID parameter is required']);
}
?>
