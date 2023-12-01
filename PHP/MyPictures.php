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

// Check if album is selected
$selectedAlbumId = $_GET['albumSelection'] ?? null;
// Check if picture is selected
$selectedPictureId = $_GET['selectedPicture'] ?? null;

// Check for POST request and add comment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['commentText']) && $selectedPictureId) {
    // Add a new comment
    addComment($selectedPictureId, $currentUserId, $_POST['commentText']);
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
    <div class="container mt-5">
        <h2>Manage My Pictures</h2>
        <?php
        if ($selectedAlbumId) {
            $currentAlbumDetails = getAlbumDetails($selectedAlbumId);
            echo "<h3>Album: " . htmlspecialchars($currentAlbumDetails['Title']) . "</h3>";
        }
        ?>

        <form method="get" id="albumForm" action="MyPictures.php" class="mb-5">
            <div class="form-group">
                <label for="albumSelection">Select an Album:</label>
                <?php echo renderAlbumDropdown($currentUserId, $selectedAlbumId); ?>
                <button type="submit" class="btn btn-primary mt-2">Show Pictures</button>
            </div>
        </form>

        <!-- Picture Area -->
        <div id="pictureArea" class="mb-5" style="text-align: left;">
            <?php if ($selectedPictureDetails): ?>
                <img src="image_serve.php?file=<?php echo urlencode($selectedPictureDetails['File_Name']); ?>" class="img-fluid" style="max-width: 900px;" />
                <?php if (!empty($selectedPictureDetails['Description'])): ?>
                    <h4 class="mt-3">Description</h4>
                    <p><?php echo htmlspecialchars($selectedPictureDetails['Description']); ?></p>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Thumbnail Bar -->
        <div class="thumbnail-bar d-flex justify-content-start flex-wrap">
            <?php foreach ($thumbnails as $thumbnail): ?>
                <div class="col-3 mb-5"> 
                    <a href="MyPictures.php?selectedPicture=<?php echo $thumbnail['Picture_Id']; ?>&albumSelection=<?php echo $selectedAlbumId; ?>">
                        <img src="image_serve.php?file=<?php echo urlencode($thumbnail['File_Name']); ?>" class="img-fluid" style="width: 100%; height: 225px;" />
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Description and Comment Area -->
        <?php if (!empty($pictureComments)): ?>
            <div id="descriptionAndComments" class="mb-4">
                <h4>Comments</h4>
                <?php foreach ($pictureComments as $comment): ?>
                    <div class="comment mb-3">
                        <p><strong><?php echo htmlspecialchars($comment['User_Id']); ?>:</strong> <?php echo htmlspecialchars($comment['Comment_Text']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Comment Form -->
        <form id="commentForm" method="post" action="MyPictures.php?selectedPicture=<?php echo $selectedPictureId; ?>&albumSelection=<?php echo $selectedAlbumId; ?>">
            <input type="hidden" name="selectedPicture" value="<?php echo htmlspecialchars($selectedPictureId); ?>">
            <div class="form-group mb-4">
                <label for="commentText">Leave a Comment:</label>
                <textarea class="form-control" id="commentText" name="commentText" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Comment</button>
        </form>
    </div>

    <?php include 'Common/PageElements/Footer.php'; ?>
</body>

<?php
include 'Common/PageElements/Footer.php';
?>
