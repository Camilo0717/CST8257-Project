<?php
session_start();
require("Common/Libraries/functions.php");

// Set active Link
extract(setActiveLink('MyPictures'));

// Check user status
$isLogged = (isset($_SESSION['UserData']));
[$Message, $Link] = checkLogStatus($isLogged);

$currentUserId = $_SESSION['userId'] ?? null;

// Redirect if not logged in
if (isset($_SESSION['serializedUser'])) {
    $serializedUser = $_SESSION['serializedUser'];
    $currentUser = unserialize($serializedUser);  
} else {
    header("Location: LogIn.php");
    exit;
}


$selectedAlbumId = $_POST['albumSelection'] ?? null;
$thumbnails = $selectedAlbumId ? getThumbnails($selectedAlbumId) : [];

$selectedPictureId = $_GET['selectedPicture'] ?? null;
$selectedPictureDetails = null;
$pictureComments = null;

if ($selectedPictureId) {
    $selectedPictureDetails = getPictureDetails($selectedPictureId);
    // create getPictureComments function exists to fetch comments
    $pictureComments = getPictureComments($selectedPictureId);
}


include 'Common/PageElements/header.php';
?>

<body>
    <div class="container mt-5">
        <h2>Manage My Pictures</h2>

        <form method="post" id="albumForm">
            <!-- Album Dropdown -->
            <div class="form-group">
                <label for="albumSelection">Select an Album</label>
                <?php echo renderAlbumDropdown($currentUserId); ?>
                <button type="submit" class="btn btn-primary mt-2">Show Pictures</button>
            </div>
        </form>

        <!-- Picture Area -->
        <div id="pictureArea" class="mb-3">
            <?php if ($selectedPictureDetails): ?>
                <img src="path/to/fullsize/<?php echo htmlspecialchars($selectedPictureDetails['File_Name']); ?>" 
                     class="img-fluid" />
                <p><?php echo htmlspecialchars($selectedPictureDetails['Description']); ?></p>
            <?php endif; ?>
        </div>

        <!-- Thumbnail Bar -->
        <div class="thumbnail-bar d-flex flex-row">
            <?php foreach ($thumbnails as $thumbnail): ?>
                <a href="MyPictures.php?selectedPicture=<?php echo $thumbnail['Picture_Id']; ?>&albumSelection=<?php echo $selectedAlbumId; ?>">
                    <img src="path/to/thumbnails/<?php echo htmlspecialchars($thumbnail['File_Name']); ?>" 
                         class="img-thumbnail" />
                </a>
            <?php endforeach; ?>
        </div>


        <!-- Description and Comment Area -->
        <div id="descriptionAndComments" class="mt-3">
            <?php if ($pictureComments): ?>
                <!-- Loop through and display comments -->
            <?php else: ?>
                <p>No comments available.</p>
            <?php endif; ?>
        </div>

        <!-- Comment Form -->
        <form id="commentForm" method="post" action="comment_handler.php">
            <div class="form-group">
                <label for="commentText">Leave a Comment:</label>
                <textarea class="form-control" id="commentText" name="commentText" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Comment</button>
        </form>
    </div>


    <script>
        // JavaScript handles thumbnail clicks, displays picture and comments
        document.querySelectorAll('.thumbnail-bar img').forEach(thumbnail => {
            thumbnail.addEventListener('click', () => {
                const pictureId = thumbnail.dataset.pictureId;
                // Maybe use AJAX request to a PHP script (e.g., getPictureDetails.php) to fetch picture details
                // Update the picture area and comments section upon receiving the response
            });
        });

    </script>
</body>


<?php
include 'Common/PageElements/Footer.php';
?>
