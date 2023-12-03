<?php

if (isset($_GET['file'])) {
    // Prevent directory traversal attack
    if (strpos($_GET['file'], '..') !== false) {
        header("HTTP/1.0 400 Bad Request");
        exit;
    }


    // Correct the file path
    $filePath = 'C:\Program Files\Ampps\www\CST8257-Project\PHP\Common\Images\\' . DIRECTORY_SEPARATOR . basename($_GET['file']);

    if (file_exists($filePath)) {
        // Determine the content type
        $fileExtension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        switch ($fileExtension) {
            case 'jpeg':
            case 'jpg':
                header('Content-Type: image/jpeg');
                break;
            case 'png':
                header('Content-Type: image/png');
                break;
            case 'gif': // Add this case for GIF files
                header('Content-Type: image/gif');
                break;
            case 'webp': // Add this case for WEBP files
                header('Content-Type: image/webp');
                break;
            default:
                header("HTTP/1.0 415 Unsupported Media Type");
                exit;
        }

        readfile($filePath);
        exit;
    }
}

header("HTTP/1.0 404 Not Found");
?>