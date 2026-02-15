<?php
header('Content-Type: application/json');
include 'db_connect.php';

if (isset($_POST['song_id']) && isset($_POST['action'])) {
    $song_id = $_POST['song_id'];
    $action = $_POST['action'];

    try {
        if ($action === 'play') {
            $stmt = $conn->prepare("UPDATE songs SET play_count = play_count + 1 WHERE id = ?");
        } elseif ($action === 'download') {
            $stmt = $conn->prepare("UPDATE songs SET download_count = download_count + 1 WHERE id = ?");
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            exit;
        }

        $stmt->execute([$song_id]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing parameters']);
}
?>