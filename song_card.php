<?php
echo "<div class='col-6 col-sm-6 col-md-4 col-lg-3 mb-2 audio-card'>";
echo "<div class='card h-100 shadow-sm'>";
if($song['image_path']) {
    echo "<img src='" . htmlspecialchars($song['image_path']) . "' class='card-img-top' alt='Album Art' style='height: 130px; object-fit: cover;'>";
} else {
    echo "<img src='https://images.unsplash.com/photo-1470225620780-dba8ba36b745?q=80&w=2070&auto=format&fit=crop' class='card-img-top' alt='Album Art' style='height: 130px; object-fit: cover;'>";
}
echo "<div class='card-body p-2'>";
echo "<h5 class='card-title'>" . htmlspecialchars($song['title']) . "</h5>";
echo "<h6 class='card-subtitle mb-2 text-muted'><i class='fas fa-user me-1'></i>" . htmlspecialchars($song['artist_name'] ?? $song['artist']) . "</h6>";
echo "<span class='genre-badge'><i class='fas fa-tag me-1'></i>" . htmlspecialchars($song['genre']) . "</span>";
echo "<div class='upload-date mt-2 mb-2'><small class='text-muted'><i class='fas fa-calendar me-1'></i>Uploaded: " . date('M d, Y', strtotime($song['upload_date'])) . "</small></div>";

if($song['audio_path']) {
    echo "<audio class='audio-player' controls><source src='" . htmlspecialchars($song['audio_path']) . "' type='audio/mpeg'></audio>";
    echo "<a href='" . htmlspecialchars($song['audio_path']) . "' download class='btn btn-primary mt-3'><i class='fas fa-download me-2'></i>Download Audio</a>";
}
echo "</div></div></div>";
?>