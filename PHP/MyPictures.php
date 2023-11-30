<?php
session_start();
require("Common/Libraries/functions.php");

// Set active Link
extract(setActiveLink('MyPictures'));

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

$selectedAlbumId = $_POST['albumSelection'] ?? null;
$thumbnails = [];
$selectedPictureId = $_GET['selectedPicture'] ?? null;
$selectedPictureDetails = null;
$pictureComments = []; // Initialize as an empty array
// Debugging: Echo the selected album ID and picture ID
echo "Selected Album ID: " . htmlspecialchars($selectedAlbumId) . "<br>";
echo "Selected Picture ID: " . htmlspecialchars($selectedPictureId) . "<br>";

if ($selectedAlbumId) {
    $thumbnails = getThumbnails($selectedAlbumId);
    echo "Thumbnails count: " . count($thumbnails) . "<br>"; // Check how many thumbnails were retrieved
    if (!$selectedPictureId && !empty($thumbnails)) {
        $selectedPictureId = $thumbnails[0]['Picture_Id']; // Set the first picture as default
    }
}

if ($selectedPictureId) {
    $selectedPictureDetails = getPictureDetails($selectedPictureId);
    if (!$selectedPictureDetails) {
        echo "No details found for Picture ID: " . htmlspecialchars($selectedPictureId) . "<br>";
    }
}


include 'Common/PageElements/header.php';
?>

<body>
    <div class="container mt-5">
        <h2>Manage My Pictures</h2>

        <form method="post" id="albumForm">
            <div class="form-group">
                <label for="albumSelection">Select an Album</label>
                <?php echo renderAlbumDropdown($currentUserId); ?>
                <button type="submit" class="btn btn-primary mt-2">Show Pictures</button>
            </div>
        </form>

        <!-- Picture Area -->
        <div id="pictureArea" class="mb-3">
            <?php if ($selectedPictureDetails): ?>
                <img src="image_serve.php?file=<?php echo urlencode($selectedPictureDetails['File_Name']); ?>" class="img-fluid" />
                <p><?php echo htmlspecialchars($selectedPictureDetails['Description']); ?></p>
            <?php endif; ?>
        </div>

        <!-- Thumbnail Bar -->
        <div class="thumbnail-bar d-flex flex-row">
            <?php foreach ($thumbnails as $thumbnail): ?>
                <a href="MyPictures.php?selectedPicture=<?php echo $thumbnail['Picture_Id']; ?>&albumSelection=<?php echo $selectedAlbumId; ?>">
                    <img src="image_serve.php?file=<?php echo urlencode($thumbnail['File_Name']); ?>" 
                         class="img-thumbnail <?php echo $selectedPictureId == $thumbnail['Picture_Id'] ? 'border border-primary' : ''; ?>" />
                </a>
            <?php endforeach; ?>
        </div>



        <!-- Description and Comment Area -->
        <div id="descriptionAndComments" class="mt-3">
            <?php if (!empty($pictureComments)): ?>
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
        // You can add JavaScript if needed for additional interactivity
    </script>
</body>


<?php
include 'Common/PageElements/Footer.php';
?>
