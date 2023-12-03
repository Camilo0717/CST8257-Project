<?php
session_start();

require_once("Common/Libraries/functions.php");
require_once("Common/Libraries/validation.php");

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
$errors = ["album" => "", "title" => "", "pictures" => ""];
$albumId = '';
$title = '';
$description = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $albumId = $_POST['albumSelection'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';

    // Perform validation
    ValidateAlbumSelection($albumId, $errors['album']);
    ValidateTitle($title, $errors['title']);
    ValidatePictures($_FILES['pictures'], $errors['pictures']);

    // Check if there are no errors
    if (!array_filter($errors)) {
        $uploadSuccess = uploadPictures($albumId, $title, $description, $_FILES['pictures'], $currentUserId);
        if ($uploadSuccess) {
            $uploadStatus = "<span class='text-success'>Upload successful!</span>";
            // Clear form data on successful upload
            $albumId = '';
            $title = '';
            $description = '';
        } else {
            $uploadStatus = "<span class='text-danger'>There was an error uploading your pictures.</span>";
        }
    } else {
        $uploadStatus = "<span class=''>Please correct the errors and try again.</span>";
    }
}
?>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8"> 
                <h2 class="text-center mb-4">Upload Pictures to Album</h2> 
                <?php if ($uploadStatus): ?>
                    <p class="text-center mb-3"><?php echo $uploadStatus; ?></p> 
                <?php endif; ?>
                <form action="UploadPictures.php" method="post" enctype="multipart/form-data">
                    <div class="form-group mb-3"> 
                        <label for="uploadAlbum">Upload to Album</label>
                        <?php echo renderAlbumDropdown($currentUserId); ?>
                        <?php if ($errors['album']): ?>
                            <div class="error text-danger"><?php echo $errors['album']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group mb-3"> 
                        <label for="title">Title:</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($title) ?>">
                        <?php if ($errors['title']): ?>
                            <div class="error text-danger"><?php echo $errors['title']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group mb-3"> 
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" name="description"><?= htmlspecialchars($description) ?></textarea>
                    </div>
                    <div class="form-group mb-2"> 
                        <label for="pictures">Select pictures:</label>
                        <input type="file" class="form-control-file" id="pictures" name="pictures[]" multiple>
                        <?php if ($errors['pictures']): ?>
                            <div class="error error text-danger"><?php echo $errors['pictures']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="text-left">
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>



<?php
include 'Common/PageElements/Footer.php';
?>
