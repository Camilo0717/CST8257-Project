<?php
session_start();
require("Common/Libraries/functions.php");

// Set active Link
extract(setActiveLink('Pictures'));

// Check user status
$isLogged = isset($_SESSION['userId']);
[$Message, $Link] = checkLogStatus($isLogged);

// Redirect if not logged in
if (!$isLogged) {
    header("Location: LogIn.php");
    exit;
}

// Get the current user's ID from the session
$currentUserId = $_SESSION['userId'];

// fetch the selected album ID and pictureID
$selectedAlbumId = $_GET['albumSelection'] ?? null;
$selectedPictureId = $_GET['selectedPicture'] ?? null;

// Check for POST request and add comment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['commentText']) && $selectedPictureId) {
    // Add a new comment
    addComment($selectedPictureId, $currentUserId, $_POST['commentText']);
    $pictureComments = getPictureComments($selectedPictureId);
    // Reload the page to show new comments for the same picture
    header("Location: MyPictures.php?selectedPicture=$selectedPictureId&albumSelection=$selectedAlbumId");
    exit;
}

$thumbnails = [];
$selectedPictureDetails = null;
$pictureComments = []; // Initialize as an empty array

if ($selectedAlbumId) {
    $thumbnails = getThumbnails($selectedAlbumId);
    // If no picture is selected, default to the first picture of the album
    if (!$selectedPictureId && !empty($thumbnails)) {
        $selectedPictureId = $thumbnails[0]['Picture_Id'];
    }
}

// Fetch details for the selected picture
if ($selectedPictureId) {
    $selectedPictureDetails = getPictureDetails($selectedPictureId);
    $pictureComments = getPictureComments($selectedPictureId);
}

include 'Common/PageElements/header.php';
?>

<body>
    <div class="container my-1">
        <h2 class="mb-5 text-center">Manage My Pictures</h2>
        <?php if ($selectedAlbumId) : ?>
            <?php $currentAlbumDetails = getAlbumDetails($selectedAlbumId); ?>    
        <?php endif; ?>

        <form method="get" id="albumForm" action="MyPictures.php" class="mb-4">
            <div class="row justify-content-center">
                <div class="col-md-6"> 
                    <div class="form-group d-flex align-items-end">
                        <div class="flex-grow-1 mr-2"> 
                            <label for="albumSelection">Select an Album:</label>
                            <div style="width: 100%;"> 
                                <?php echo renderAlbumDropdown($currentUserId, $selectedAlbumId); ?>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mx-2">Show Pictures</button>
                    </div>
                </div>
            </div>
        </form>



        <!-- Picture Area -->
        <div id="pictureArea" class="text-center my-4">
            <?php if ($selectedPictureDetails) : ?>
            <div class="text-left">
                <!-- Display Album Title -->
                <h3 class="my-3">Album Title: <?php echo htmlspecialchars($currentAlbumDetails['Title']); ?></h3>
                <h4 class="mt-3 mb-5">Picture Title: <?php echo htmlspecialchars($selectedPictureDetails['Title']); ?></h4>
                </div>
                <img src="image_serve.php?file=<?php echo urlencode($selectedPictureDetails['File_Name']); ?>" class="img-fluid rounded w-50" />
                <?php if (!empty($selectedPictureDetails['Description'])) : ?>
                    <h4 class="mt-4">Description</h4>
                    <p><?php echo htmlspecialchars($selectedPictureDetails['Description']); ?></p>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Thumbnail Bar -->
        <div class="thumbnail-bar d-flex justify-content-center flex-wrap my-5">
            <?php foreach ($thumbnails as $thumbnail) : ?>
                <div class="col-md-2 col-sm-4 col-6 my-2">
                    <a href="MyPictures.php?selectedPicture=<?php echo $thumbnail['Picture_Id']; ?>&albumSelection=<?php echo $selectedAlbumId; ?>">
                        <img src="image_serve.php?file=<?php echo urlencode($thumbnail['File_Name']); ?>" class="img-fluid rounded" style="width: 90%; height: 125px;" />
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Description and Comment Area -->
        <?php if (!empty($pictureComments)) : ?>
            <div id="descriptionAndComments" class="mt-4 text-left">
                <h4 class="mb-2 font-weight-bold">Comments</h4>
                <?php foreach ($pictureComments as $comment) : ?>
                    <div class="comment mb-3">
                        <p><strong><?php echo htmlspecialchars($comment['User_Id']); ?>:</strong> <?php echo htmlspecialchars($comment['Comment_Text']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Comment Form -->
        <form id="commentForm" method="post" action="MyPictures.php?selectedPicture=<?php echo $selectedPictureId; ?>&albumSelection=<?php echo $selectedAlbumId; ?>" class="text-left">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="form-group my-2">
                        <label for="commentText">Leave a Comment:</label>
                        <textarea class="form-control" id="commentText" name="commentText" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2 mb-5">Add Comment</button>
                </div>
            </div>
        </form>
    </div>
</body>


<?php
include 'Common/PageElements/Footer.php';
?>
