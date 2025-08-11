<?php
// Simple Motorsports Photo Gallery - gallery.php

// Directory containing photos
gallery_dir = 'images/motorsports';

// Supported image extensions
$image_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

// Scan the directory for image files
$photos = [];
if (is_dir($gallery_dir)) {
    foreach (scandir($gallery_dir) as $file) {
        $path = "$gallery_dir/$file";
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (is_file($path) && in_array($ext, $image_extensions)) {
            $photos[] = $path;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Motorsports Gallery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background: #111;
            color: #eee;
            font-family: 'Helvetica Neue', Arial, sans-serif;
        }
        .gallery-container {
            max-width: 1000px;
            margin: 3rem auto;
        }
        .photo-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        .photo-grid img {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 16px #e91e6333, 0 1px 4px #00000033;
            border: 4px solid #e91e6333;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .photo-grid img:hover {
            transform: scale(1.07) rotate(-2deg);
            box-shadow: 0 8px 32px #e91e6388, 0 2px 8px #00000044;
            border-color: #e91e6399;
        }
        h1 {
            text-align: center;
            color: #e91e63;
            text-transform: uppercase;
            margin-top: 2rem;
        }
        @media (max-width: 700px) {
            .photo-grid {
                gap: 1rem;
            }
        }
    </style>
</head>
<body>
    <h1>Motorsports Photo Gallery</h1>
    <div class="gallery-container">
        <div class="photo-grid">
            <?php if (count($photos)): ?>
                <?php foreach ($photos as $img): ?>
                    <img src="<?php echo htmlspecialchars($img); ?>" alt="Motorsports photo">
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align:center;">No photos found in the gallery.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
