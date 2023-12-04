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
    $_SESSION['Location'] = 'MyFriendsPictures.php';
    header("Location: LogIn.php");
    exit;
}

// Get the current user's ID from the session
$currentUserId = $_SESSION['userId'];
$friendId = $_GET['friendId'];
$friendName= $_GET['friendName'];
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
        <h2 class="mb-4"><?php echo $friendName;?>'s Pictures</h2>
        <?php
        if ($selectedAlbumId) {
            $currentAlbumDetails = getAlbumDetails($selectedAlbumId);
            echo "<h3>Album: " . htmlspecialchars($currentAlbumDetails['Title']) . "</h3>";
        }
        ?>

        <form method="get" id="albumForm" action="MyPictures.php" class="mb-4">
            <div class="form-group">
                <label for="albumSelection">Select an Album:</label>
                <?php echo renderAlbumDropdown($friendId, $selectedAlbumId, true); ?>
                <button type="submit" class="btn btn-primary mt-2">Show Pictures</button>
            </div>
        </form>

        <!-- Picture Area -->
        <div id="pictureArea" class="my-5" style="text-align: left;">
            <?php if ($selectedPictureDetails): ?>
                <img src="image_serve.php?file=<?php echo urlencode($selectedPictureDetails['File_Name']); ?>" class="img-fluid rounded" /> <!-- w-50 ideal size but reduces img quality -->
                <?php if (!empty($selectedPictureDetails['Description'])): ?>
                    <h4 class="mt-2">Description</h4>
                    <p><?php echo htmlspecialchars($selectedPictureDetails['Description']); ?></p>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- Thumbnail Bar -->
        <div class="thumbnail-bar d-flex justify-content-start flex-wrap my-5">
            <?php foreach ($thumbnails as $thumbnail): ?>
                <div class="col-2"> 
                    <a href="MyPictures.php?selectedPicture=<?php echo $thumbnail['Picture_Id']; ?>&albumSelection=<?php echo $selectedAlbumId; ?>">
                        <img src="image_serve.php?file=<?php echo urlencode($thumbnail['File_Name']); ?>" class="img-fluid rounded" style="width: 90%; height: 125px;" /> 
                    </a>
                </div>
            <?php endforeach; ?>
        </div>



        <!-- Description and Comment Area -->
        <?php if (!empty($pictureComments)): ?>
            <div id="descriptionAndComments" class="mt-4"> 
                <h4 class="mb-2 font-weight-bold">Comments</h4> 
                <?php foreach ($pictureComments as $comment): ?>
                    <div class="comment mb-"> 
                        <p><strong><?php echo htmlspecialchars($comment['User_Id']); ?>:</strong> <?php echo htmlspecialchars($comment['Comment_Text']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Comment Form -->
        <form id="commentForm" method="post" action="MyPictures.php?selectedPicture=<?php echo $selectedPictureId; ?>&albumSelection=<?php echo $selectedAlbumId; ?>">
            <input type="hidden" name="selectedPicture" value="<?php echo htmlspecialchars($selectedPictureId); ?>">
            <div class="form-group my-2"> 
                <label for="commentText">Leave a Comment:</label>
                <textarea class="form-control" id="commentText" name="commentText" rows="3"></textarea>
                <button type="submit" class="btn btn-primary mt-2 mb-5">Add Comment</button>
            </div>

        </form>
    </div>

    <?php include 'Common/PageElements/Footer.php'; ?>
</body>

<?php
include 'Common/PageElements/Footer.php';
?>
