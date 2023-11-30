<?php

// This is the source file for all functions
// activeLink: Takes the currentPage identifier and returns a compact
// array to extract active Link
function setActiveLink($currentPage) {

    // Initialize all flags to null
    $activeHome = null;
    $activeFriends = null;
    $activeAlbums = null;
    $activePictures = null;
    $activeUpload = null;
    $activeLog = null;

    // Set the active flag based on the current page
    switch ($currentPage) {
        case 'home':
            $activeHome = "active";
            break;
        case 'Friends':
            $activeFriends = "active";
            break;
        case 'Albums':
            $activeAlbums = "active";
            break;
        case 'Pictures':
            $activePictures = "active";
            break;
        case 'Upload':
            $activeUpload = "active";
            break;
        case 'Log':
            $activeLog = "active";
            break;
        default:
            break;
    }

    // Return the active flags
    return compact('activeHome', 'activeFriends', 'activeAlbums',
            'activePictures', 'activeUpload', 'activeLog');
    ;
}

// checkLogStatus: receives the log in status of the user and returns
// the relevant information for display and links
function checkLogStatus($isLogged) {
    if ($isLogged) {
        $result = ['Log Out', 'LogOut.php'];
    } else {
        $result = ['Log In', 'LogIn.php'];
    }
    return $result;
}

// executeQuery: receives a Query string and an array of variable
// assignment to execute the Query
function executeQuery($query, $arguments) {
    // Database Connection
    $dbConnection = parse_ini_file("./Common/Project.ini");
    extract($dbConnection);
    $myPdo = new PDO($dsn, $user, $password);

    // Prepare query
    $preparedQuery = $myPdo->prepare($query);

    // Execute query
    $preparedQuery->execute($arguments);

    return $preparedQuery;
}

function renderAlbumDropdown($currentUserId) {
    $dropdownHTML = '<select class="form-control" id="uploadAlbum" name="uploadAlbum">';

    // Database Connection
    $dbConnection = parse_ini_file("./Common/Project.ini");
    extract($dbConnection);
    $pdo = new PDO($dsn, $user, $password);

    $query = "SELECT * FROM album WHERE Owner_Id = :currentUserId";
    $stmt = $pdo->prepare($query);

    // Bind parameter
    $stmt->bindParam(':currentUserId', $currentUserId, PDO::PARAM_STR); // Use PDO::PARAM_STR for string
    // Execute query
    if ($stmt->execute()) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $escapedAlbumId = htmlspecialchars($row['Album_Id']);
            $escapedTitle = htmlspecialchars($row['Title']);
            $dropdownHTML .= "<option value=\"{$escapedAlbumId}\">{$escapedTitle}</option>";
        }
    } else {
        // Handle error when execute fails
        $dropdownHTML .= "<option value=\"\">Failed to load albums</option>";
    }

    $dropdownHTML .= '</select>';
    return $dropdownHTML;
}

function initSessionVar(&$variable) {
    if (isset($_SESSION[$variable])) {
        $variable = $_SESSION[$variable];
    } else {
        $variable = '';
    }

    return $variable;
}

// Function to fetch accessibility options
function getAccessibilityOptions() {
    $dbConnection = parse_ini_file("./Common/Project.ini");
    extract($dbConnection);
    $pdo = new PDO($dsn, $user, $password);

    $query = "SELECT * FROM Accessibility";
    return executeQuery($query, [])->fetchAll(PDO::FETCH_ASSOC);
}

// Function to insert a new album
function insertNewAlbum($title, $description, $currentUserId, $accessibilityCode) {
    // Ensure Owner_Id is not longer than 16 characters
    $currentUserId = substr($currentUserId, 0, 16);

    $dbConnection = parse_ini_file("./Common/Project.ini");
    extract($dbConnection);
    $pdo = new PDO($dsn, $user, $password);

    $insertQuery = "INSERT INTO album (Title, Description, Owner_Id, Accessibility_Code) VALUES (:title, :description, :ownerId, :accessibilityCode)";
    $stmt = $pdo->prepare($insertQuery);

    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':ownerId', $currentUserId, PDO::PARAM_STR);
    $stmt->bindParam(':accessibilityCode', $accessibilityCode, PDO::PARAM_STR);

    if (!$stmt->execute()) {
        return "Error: " . implode(", ", $stmt->errorInfo()); // This will show the error details
    }
    return "Album added successfully";
}

// Uploads ipctures to local file 
function uploadPictures($albumId, $title, $description, $files, $currentUserId) {
    $targetDirectory = "C:/Users/migue_usbrqse/OneDrive/Pictures/Temp_PHP_Project/";
    $uploadSuccess = true;
    $errorMessages = [];

    foreach ($files['name'] as $key => $name) {
        if ($files['error'][$key] == 0) {
            $newFileName = uniqid() . "-" . basename($name);
            $targetFilePath = $targetDirectory . $newFileName;

            if (move_uploaded_file($files['tmp_name'][$key], $targetFilePath)) {
                if (!insertPicture($albumId, $newFileName, $title, $description)) {
                    $uploadSuccess = false;
                    $errorMessages[] = "Failed to insert $newFileName into the database.";
                    break;
                }
            } else {
                $uploadSuccess = false;
                $errorMessages[] = "Failed to move uploaded file $newFileName.";
                break;
            }
        } else {
            $uploadSuccess = false;
            $errorMessages[] = "Error uploading file $name.";
            break;
        }
    }

    if (!$uploadSuccess) {
        // Handle errors, e.g., log them or return them
        foreach ($errorMessages as $errorMsg) {
            // Log or echo the error messages
            echo $errorMsg . "<br>";
        }
    }

    return $uploadSuccess;
}

// Adds picture 
function insertPicture($albumId, $fileName, $title, $description) {
    // Database Connection
    $dbConnection = parse_ini_file("./Common/Project.ini");
    extract($dbConnection);
    $pdo = new PDO($dsn, $user, $password);

    $insertQuery = "INSERT INTO picture (Album_Id, File_Name, Title, Description) VALUES (:albumId, :fileName, :title, :description)";
    $stmt = $pdo->prepare($insertQuery);
    $stmt->bindParam(':albumId', $albumId, PDO::PARAM_INT);
    $stmt->bindParam(':fileName', $fileName, PDO::PARAM_STR);
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);

    if (!$stmt->execute()) {
        // Log or handle the error
        error_log("Database error: " . implode(", ", $stmt->errorInfo()));
        return false;
    }
    return true;
}

// Function to fetch thumbnails for a given album
function getThumbnails($albumId) {
    $dbConnection = parse_ini_file("./Common/Project.ini");
    extract($dbConnection);
    $pdo = new PDO($dsn, $user, $password);

    $query = "SELECT Picture_Id, File_Name FROM picture WHERE Album_Id = :albumId ORDER BY Picture_Id ASC";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':albumId', $albumId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Function to fetch details of a single picture
function getPictureDetails($pictureId) {
    $dbConnection = parse_ini_file("./Common/Project.ini");
    extract($dbConnection);
    $pdo = new PDO($dsn, $user, $password);

    $query = "SELECT File_Name, Title, Description FROM picture WHERE Picture_Id = :pictureId";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':pictureId', $pictureId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
