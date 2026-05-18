<?php
// FR-36: Event Archive & Gallery
// Admin uploads post-event photos; stored on server and saved to MySQL event_gallery table;
// gallery page visible to parents.

session_start();

// Admin only
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403);
    header("location: ../index.php");
    exit("Access denied. Admins only.");
}

$event_id = $caption = "";
$event_id_err = $photo_err = $caption_err = "";
$uploadedFiles = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate event_id
    $input_event_id = trim($_POST["event_id"]);
    if (empty($input_event_id)) {
        $event_id_err = "Please provide the event ID.";
    } elseif (!ctype_digit($input_event_id)) {
        $event_id_err = "Event ID must be a positive integer.";
    } else {
        $event_id = $input_event_id;
    }

    // Validate caption (optional but sanitize if provided)
    $input_caption = trim($_POST["caption"] ?? "");
    if (strlen($input_caption) > 255) {
        $caption_err = "Caption must not exceed 255 characters.";
    } else {
        $caption = htmlspecialchars($input_caption);
    }

    // Validate uploaded photo(s)
    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxFileSizeBytes = 5 * 1024 * 1024; // 5MB per file
    $uploadDir = '../uploads/gallery/';

    if (!isset($_FILES["photos"]) || empty($_FILES["photos"]["name"][0])) {
        $photo_err = "Please upload at least one photo.";
    } else {
        $fileCount = count($_FILES["photos"]["name"]);

        for ($i = 0; $i < $fileCount; $i++) {
            $fileName  = $_FILES["photos"]["name"][$i];
            $fileTmp   = $_FILES["photos"]["tmp_name"][$i];
            $fileSize  = $_FILES["photos"]["size"][$i];
            $fileError = $_FILES["photos"]["error"][$i];

            if ($fileError !== UPLOAD_ERR_OK) {
                $photo_err = "Upload error for file: " . htmlspecialchars($fileName);
                break;
            }

            if ($fileSize > $maxFileSizeBytes) {
                $photo_err = "File " . htmlspecialchars($fileName) . " exceeds the 5MB size limit.";
                break;
            }

            // Verify MIME type from file content (not just extension)
            $finfo    = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $fileTmp);
            finfo_close($finfo);

            if (!in_array($mimeType, $allowedMimeTypes)) {
                $photo_err = "File " . htmlspecialchars($fileName) . " is not an allowed image type.";
                break;
            }

            // Generate a safe unique filename
            $ext            = pathinfo($fileName, PATHINFO_EXTENSION);
            $safeFileName   = uniqid('gallery_', true) . '.' . strtolower($ext);
            $destination    = $uploadDir . $safeFileName;

            $uploadedFiles[] = [
                'tmp'       => $fileTmp,
                'dest'      => $destination,
                'safe_name' => $safeFileName
            ];
        }
    }

    // If no errors, move files and save records to DB
    if (empty($event_id_err) && empty($photo_err) && empty($caption_err) && !empty($uploadedFiles)) {
        include_once '../Model/GalleryModel.php';
        $galleryModel = new GalleryModel();

        foreach ($uploadedFiles as $file) {
            if (!move_uploaded_file($file['tmp'], $file['dest'])) {
                echo "Failed to save file: " . htmlspecialchars($file['safe_name']) . ". Please try again.";
                exit();
            }

            $inserted = $galleryModel->insertPhoto($event_id, $file['safe_name'], $caption);
            if (!$inserted) {
                echo "Photo uploaded but database record failed for: " . htmlspecialchars($file['safe_name']);
                exit();
            }
        }

        header("location: ../index.php");
        exit();
    }
}
?>