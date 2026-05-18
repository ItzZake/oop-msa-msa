<?php
// FR-06 — Document Upload
// Handles POST (multipart/form-data) from the file upload form in View/application.php
// Accepts PDF, JPG, PNG — max 5 MB
// Saves the file under uploads/documents/ and queues the path in $_SESSION["uploaded_docs"]
// so application.php (FR-05) can attach it when submitting.

$allowed_roles = ["parent"];
require_once '../Controllers/guard.php';   // FR-03 gate

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../View/application.php");
    exit;
}

// ── Define variables ──────────────────────────────────────────────────────────
$document_err = "";

// Allowed MIME types and their extensions
$allowed_mimes = [
    "application/pdf" => "pdf",
    "image/jpeg"      => "jpg",
    "image/png"       => "png",
];

$max_bytes  = 12 * 1024 * 1024;   // 12 MB
$upload_dir = __DIR__ . "/../uploads/documents/";

// ── Validate: file was sent and had no upload error ───────────────────────────
if (!isset($_FILES["document"]) || $_FILES["document"]["error"] !== UPLOAD_ERR_OK) {
    $upload_error_messages = [
        UPLOAD_ERR_INI_SIZE   => "File exceeds the server upload limit.",
        UPLOAD_ERR_FORM_SIZE  => "File exceeds the form upload limit.",
        UPLOAD_ERR_PARTIAL    => "File was only partially uploaded.",
        UPLOAD_ERR_NO_FILE    => "No file was uploaded.",
        UPLOAD_ERR_NO_TMP_DIR => "Server temporary folder is missing.",
        UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.",
        UPLOAD_ERR_EXTENSION  => "A PHP extension stopped the upload.",
    ];
    $document_err = $upload_error_messages[$_FILES["document"]["error"] ?? UPLOAD_ERR_NO_FILE]
                    ?? "An unknown upload error occurred.";
}

// ── Validate: file size ───────────────────────────────────────────────────────
if (empty($document_err) && $_FILES["document"]["size"] > $max_bytes) {
    $document_err = "File size must not exceed 5 MB.";
}

// ── Validate: MIME type (use finfo, never trust $_FILES["type"]) ──────────────
if (empty($document_err)) {
    $finfo     = new finfo(FILEINFO_MIME_TYPE);
    $mime_type = $finfo->file($_FILES["document"]["tmp_name"]);

    if (!array_key_exists($mime_type, $allowed_mimes)) {
        $document_err = "Only PDF, JPG, and PNG files are allowed.";
    }
}

// ── If no errors, move the file to the uploads directory ─────────────────────
if (empty($document_err)) {

    // Create upload directory if it does not exist
    if (!is_dir($upload_dir)) {
        if (!mkdir($upload_dir, 0750, true)) {
            $document_err = "Upload directory could not be created. Please contact support.";
        }
    }

    if (empty($document_err)) {
        $ext      = $allowed_mimes[$mime_type];
        $userId   = (int) $_SESSION["user_id"];
        $filename = sprintf("doc_%d_%s.%s", $userId, bin2hex(random_bytes(8)), $ext);
        $dest     = $upload_dir . $filename;

        if (move_uploaded_file($_FILES["document"]["tmp_name"], $dest)) {
            // Queue path for FR-05 (application submit)
            $_SESSION["uploaded_docs"][] = "uploads/documents/" . $filename;

            $_SESSION["message"] = "Document uploaded successfully.";
            header("Location: ../View/application.php");
            exit;
        } else {
            $document_err = "Failed to save the uploaded file. Please try again.";
        }
    }
}

// ── Return error to view ──────────────────────────────────────────────────────
$_SESSION["error"] = $document_err;
header("Location: ../View/application.php");
exit;
?>