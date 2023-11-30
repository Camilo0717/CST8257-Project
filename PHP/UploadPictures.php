<?php
session_start();

require_once("Common/Libraries/functions.php");

// Set active Link
extract(setActiveLink('UploadPictures'));

// Check user status
$isLogged = isset($_SESSION['userId']);
[$Message, $Link] = checkLogStatus($isLogged);

include 'Common/PageElements/header.php';

// Redirect if not logged in
if (!$isLogged) {
    header("Location: LogIn.php");
    exit;
}

// Get the current user's ID from the session
$currentUserId = $_SESSION['userId'];

$uploadStatus = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $albumId = $_POST['uploadAlbum'];
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $uploadSuccess = uploadPictures($albumId, $title, $description, $_FILES['pictures'], $currentUserId);

    if ($uploadSuccess) {
        $uploadStatus = "Upload successful!";
    } else {
        $uploadStatus = "There was an error uploading your pictures.";
    }
}
?>

<body>
    <div class="container mt-5">
        <h2>Upload Pictures to Album</h2>
        <?php if ($uploadStatus): ?>
            <p><?php echo $uploadStatus; ?></p>
        <?php endif; ?>
        <form action="UploadPictures.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="uploadAlbum">Upload to Album</label>
                <?php echo renderAlbumDropdown($currentUserId); ?>
            </div>
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title">
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description"></textarea>
            </div>
            <div class="form-group">
                <label for="pictures">Select pictures:</label>
                <input type="file" class="form-control-file" id="pictures" name="pictures[]" multiple>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>
</body>

<?php
include 'Common/PageElements/Footer.php';
?>
