<?php
session_start();

include("Common/Libraries/functions.php");

// Set active Link
//$activePage = setActiveLink('MyAlbums');
extract(setActiveLink('MyAlbums'));

// Check user status
$isLogged = isset($_SESSION['userId']);
[$Message, $Link] = checkLogStatus($isLogged);

if (isset($_SESSION['serializedUser'])){
    $serializedUser = isset($_SESSION['serializedUser']);
    // Get user object
    $currentUser = unserialize($serializedUser);
} else {
    header("Location: LogIn.php");
    exit;
}

$currentUserId = $_SESSION['serializedUser'];

// Include header
include 'Common/PageElements/header.php';

// Database connection using a function from functions.php
$dbConnection = parse_ini_file("./Common/Project.ini");
extract($dbConnection);
$pdo = new PDO($dsn, $user, $password);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'] ?? '';
    $accessibilityCode = $_POST['accessibility'] ?? '';
    $description = $_POST['description'] ?? '';

    insertNewAlbum($title, $description, $currentUserId, $accessibilityCode);

}

// Fetch accessibility options
$accessibilityOptions = getAccessibilityOptions();


?>


<body>
    <div class="container mt-5">
        <h2>Create New Album</h2>
        <form action="AddAlbum.php" method="post">
            <div class="form-group">
                <label for="title">Album Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
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
            <div class="form-group">
                <label for="description">Description (Optional):</label>
                <textarea class="form-control" id="description" name="description"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Create Album</button>
        </form>
    </div>
</body>


<?php
include 'Common/PageElements/Footer.php';
?>
