<?php
session_start();

include("Common/Libraries/functions.php");
require_once("Common/Libraries/validation.php");

// Set active Link
extract(setActiveLink('MyAlbums'));

// Check user status
$isLogged = isset($_SESSION['userId']);
[$Message, $Link] = checkLogStatus($isLogged);

// Redirect if not logged in
if (!$isLogged) {
    $_SESSION['Location'] = 'AddAlbum.php';
    header("Location: LogIn.php");
    exit;
}

$currentUserId = $_SESSION['userId'];

// Include header
include 'Common/PageElements/header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'] ?? '';
    $accessibilityCode = $_POST['accessibility'] ?? '';
    $description = $_POST['description'] ?? '';

     // Call insertNewAlbum function
    insertNewAlbum($title, $description, $currentUserId, $accessibilityCode);
}

// Fetch accessibility options
$accessibilityOptions = getAccessibilityOptions();
?>


<body>
    <div class="container mt-5">
        <h2 class="text-center">Create New Album</h2>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php
                // Display success message if it exists
                if (isset($_SESSION['successMessage'])) {
                    echo $_SESSION['successMessage'];
                    // Clear the message after displaying
                    unset($_SESSION['successMessage']);
                }
                ?>
                <form action="AddAlbum.php" method="post">
                    <div class="form-group mt-4 mb-3">
                        <label for="title">Album Title:</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="form-group my-3">
                        <label for="accessibility">Accessibility:</label>
                        <select class="form-control" id="accessibility" name="accessibility">
                            <?php
                            // Display accessibility options
                            foreach ($accessibilityOptions as $option) {
                                echo '<option value="' . htmlspecialchars($option['Accessibility_Code']) . '">' . htmlspecialchars($option['Description']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group my-2">
                        <label for="description">Description (Optional):</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Create Album</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>



<?php
include 'Common/PageElements/Footer.php';
?>
