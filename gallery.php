<?php
// Simple Motorsports Photo Gallery - gallery.php

// Directory containing photos
$gallery_dir = 'images/motorsports';

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
            cursor: pointer;
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
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100vw;
            height: 100vh;
            overflow: auto;
            background: rgba(0,0,0,0.88);
            justify-content: center;
            align-items: center;
        }
        .modal.active {
            display: flex;
        }
        .modal-img {
            max-width: 90vw;
            max-height: 90vh;
            border-radius: 16px;
            box-shadow: 0 8px 32px #e91e6388, 0 2px 8px #00000044;
            border: 4px solid #e91e6399;
            background: #222;
            display: block;
        }
        .modal-close {
            position: absolute;
            top: 2.5rem;
            right: 3rem;
            font-size: 2.5rem;
            color: #e91e63;
            cursor: pointer;
            background: none;
            border: none;
            z-index: 10000;
            transition: color 0.2s;
        }
        .modal-close:hover {
            color: #fff;
        }
        .modal-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            font-size: 3rem;
            color: #e91e63;
            background: none;
            border: none;
            cursor: pointer;
            padding: 0 1rem;
            z-index: 10001;
            opacity: 0.8;
            transition: color 0.2s, opacity 0.2s;
            user-select: none;
        }
        .modal-arrow:hover {
            color: #fff;
            opacity: 1;
        }
        .modal-arrow.left {
            left: 2rem;
        }
        .modal-arrow.right {
            right: 2rem;
        }
        @media (max-width: 700px) {
            .modal-arrow {
                font-size: 2rem;
                left: 0.5rem;
                right: 0.5rem;
            }
            .modal-close {
                top: 1rem;
                right: 1.2rem;
                font-size: 1.7rem;
            }
        }
    </style>
</head>
<body>
    <h1>Motorsports Photo Gallery</h1>
    <div class="gallery-container">
        <div class="photo-grid">
            <?php foreach ($photos as $idx => $img): ?>
                <img src="<?php echo htmlspecialchars($img); ?>"
                     alt="Motorsports photo"
                     class="gallery-photo"
                     data-index="<?php echo $idx; ?>">
            <?php endforeach; ?>
            <?php if (!count($photos)): ?>
                <p style="text-align:center;">No photos found in the gallery.</p>
            <?php endif; ?>
        </div>
    </div>
    <!-- Modal Popup -->
    <div class="modal" id="photo-modal">
        <button class="modal-arrow left" id="modal-prev" aria-label="Previous">&#8592;</button>
        <button class="modal-arrow right" id="modal-next" aria-label="Next">&#8594;</button>
        <button class="modal-close" id="modal-close" aria-label="Close">&times;</button>
        <img src="" alt="Full view" class="modal-img" id="modal-img">
    </div>
    <script>
        const photos = Array.from(document.querySelectorAll('.gallery-photo')).map(img => img.src);
        let currentIdx = null;

        function openModal(idx) {
            const modal = document.getElementById('photo-modal');
            const modalImg = document.getElementById('modal-img');
            modalImg.src = photos[idx];
            currentIdx = idx;
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
            updateArrows();
        }

        function closeModal() {
            const modal = document.getElementById('photo-modal');
            modal.classList.remove('active');
            document.body.style.overflow = '';
            document.getElementById('modal-img').src = '';
            currentIdx = null;
        }

        function showPrev() {
            if (currentIdx > 0) {
                openModal(currentIdx - 1);
            }
        }

        function showNext() {
            if (currentIdx < photos.length - 1) {
                openModal(currentIdx + 1);
            }
        }

        function updateArrows() {
            document.getElementById('modal-prev').style.display = currentIdx > 0 ? 'block' : 'none';
            document.getElementById('modal-next').style.display = currentIdx < photos.length - 1 ? 'block' : 'none';
        }

        document.querySelectorAll('.gallery-photo').forEach(function(img) {
            img.addEventListener('click', function() {
                openModal(parseInt(this.dataset.index, 10));
            });
        });

        document.getElementById('modal-close').onclick = closeModal;
        document.getElementById('modal-prev').onclick = showPrev;
        document.getElementById('modal-next').onclick = showNext;

        document.getElementById('photo-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (currentIdx !== null) {
                if (e.key === "Escape") closeModal();
                else if (e.key === "ArrowLeft") showPrev();
                else if (e.key === "ArrowRight") showNext();
            }
        });
    </script>
</body>
</html>
