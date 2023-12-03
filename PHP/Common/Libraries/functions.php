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

function renderAlbumDropdown($currentUserId, $selectedAlbumId = null) {
    $dropdownHTML = '<select class="form-control" id="albumSelection" name="albumSelection">';

    // Database Connection
    $dbConnection = parse_ini_file("./Common/Project.ini");
    extract($dbConnection);
    $pdo = new PDO($dsn, $user, $password);

    $query = "SELECT * FROM album WHERE Owner_Id = :currentUserId";
    $stmt = $pdo->prepare($query);

    // Bind parameter
    $stmt->bindParam(':currentUserId', $currentUserId, PDO::PARAM_STR);

    if ($stmt->execute()) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Check if the current album is the selected album
            $selectedAttribute = ($row['Album_Id'] == $selectedAlbumId) ? ' selected' : '';
            $escapedAlbumId = htmlspecialchars($row['Album_Id']);
            $escapedTitle = htmlspecialchars($row['Title']);
            $dropdownHTML .= "<option value=\"{$escapedAlbumId}\"{$selectedAttribute}>{$escapedTitle}</option>";
        }
    } else {
        // Handle error when execute fails
        $dropdownHTML .= "<option value=\"\">Failed to load albums</option>";
    }

    $dropdownHTML .= '</select>';
    return $dropdownHTML;
}


function getFriendsList($currentUserId) {
    // Query to select accepted friends
    $query = "SELECT u.Name friendName, u.UserId friendId, coalesce(temp.numShared, 0) sharedAlbums FROM user u "
            . "JOIN friendship f on f.Friend_RequesteeId = u.UserId "
            . "LEFT JOIN (SELECT Owner_Id, count(*) numShared from album "
            . "WHERE Accessibility_Code = 'shared' GROUP BY Owner_Id) temp on temp.Owner_Id = u.userId "
            . "WHERE f.Friend_RequesterId = :currentUserId "
            . "AND f.Status = 'accepted' "
            . "UNION (SELECT u.Name friendName, u.UserId friendId, coalesce(temp.numShared, 0) sharedAlbums FROM user u "
            . "JOIN friendship f on f.Friend_RequesterId = u.UserId "
            . "LEFT JOIN (SELECT Owner_Id, count(*) numShared from album "
            . "WHERE Accessibility_Code = 'shared' GROUP BY Owner_Id) temp on temp.Owner_Id = u.userId "
            . "WHERE f.Friend_RequesteeId = :currentUserId "
            . "AND f.Status = 'accepted');";

    $prepQuery = executeQuery($query, ['currentUserId' => $currentUserId]);

    $friendArray = [];
    $friendData = [];

    if ($prepQuery) {
        if ($prepQuery->rowCount() == 0) {
            $message = 'You don\'t have any friends at the moment.';
        } else {
            $message = 'Friend List';
            foreach ($prepQuery as $row) {
                $friendData['friendId'] = $row['friendId'];
                $friendData['friendName'] = $row['friendName'];
                $friendData['sharedAlbums'] = $row['sharedAlbums'];
                $friendArray[] = $friendData;
                $friendData = [];
            }
        }
    } else {
        $message = 'An error ocurred when trying to fetch your friend list.';
    }

    return ['message' => $message, 'friendArray' => $friendArray];
}

function getFriendsRequests($currentUserId) {
    // Query to select requests
    $query = "SELECT u.Name friendName, u.UserId friendId FROM user u "
            . "JOIN friendship f on f.Friend_RequesterId = u.UserId "
            . "WHERE f.Friend_RequesteeId = :currentUserId "
            . "AND f.Status = 'request' ";          

    $prepQuery = executeQuery($query, ['currentUserId' => $currentUserId]);

    $requestArray = [];
    $requestData = [];

    if ($prepQuery) {
        if ($prepQuery->rowCount() == 0) {
            $message = 'You don\'t have any friends request at the moment.';
        } else {
            $message = 'Friend Requests';
            foreach ($prepQuery as $row) {
                $requestData['userId'] = $row['friendId'];
                $requestData['userName'] = $row['friendName'];
                $requestArray[] = $requestData;
                $requestData = [];
            }
        }
    } else {
        $message = 'An error ocurred when trying to fetch your friend request list.';
    }

    return ['message' => $message, 'requestArray' => $requestArray];
}

function deleteFriend($friendId, $currentUserId) {
    // Query from requestee
    $query1 = "DELETE FROM friendship WHERE Friend_RequesterId = :friendId AND "
            . "Friend_RequesteeId = :currentUserId AND status = 'accepted'";

    // Query from requester
    $query2 = "DELETE FROM friendship WHERE Friend_RequesteeId = :friendId AND "
            . "Friend_RequesterId = :currentUserId AND status = 'accepted'";

    executeQuery($query1, ['currentUserId' => $currentUserId, 'friendId' => $friendId]);
    executeQuery($query2, ['currentUserId' => $currentUserId, 'friendId' => $friendId]);
}

function deleteRequest($userId, $currentUserId) {
    // Query from requestee
    $query1 = "DELETE FROM friendship WHERE Friend_RequesterId = :userId"
            . " AND Friend_RequesteeId = :currentUserId AND status = 'request'";

    // Query from requester
    $query2 = "DELETE FROM friendship WHERE Friend_RequesteeId = :userId"
            . " AND Friend_RequesterId = :currentUserId AND status = 'request'";

    executeQuery($query1, ['currentUserId' => $currentUserId, 'userId' => $userId]);
    executeQuery($query2, ['currentUserId' => $currentUserId, 'userId' => $userId]);
}

function acceptRequest($userId, $currentUserId) {
    // Query from requestee
    $query1 = "UPDATE friendship SET status = 'accepted' WHERE "
            . "Friend_RequesterId = :userId AND Friend_RequesteeId = :currentUserId ";

    // Query from requester
    $query2 = "UPDATE friendship SET status = 'accepted' WHERE "
            . "Friend_RequesteeId = :userId AND Friend_RequesterId = :currentUserId ";

    executeQuery($query1, ['currentUserId' => $currentUserId, 'userId' => $userId]);
    executeQuery($query2, ['currentUserId' => $currentUserId, 'userId' => $userId]);
}

function sendFriendRequest($userId, $friendId, &$errorMsg, &$confirmationMsg){
    // check if friend Id exists
    $query1 = "SELECT UserId, Name FROM user WHERE "
            . "UserId =:friendId";
    $prep1 = executeQuery($query1, ['friendId'=>$friendId]);
    $row1 = $prep1 ? $prep1->fetch(PDO::FETCH_ASSOC) : null;
    if ($row1){
        $friendName = $row1['Name'];
        // The user exists
        // Check if the user is already a friend
        $query2 = "SELECT * FROM friendship WHERE "
                . "Friend_RequesterId =:userId AND "
                . "Friend_RequesteeId =:friendId AND "
                . "Status = 'accepted'"
                . "UNION (SELECT * FROM friendship WHERE "
                . "Friend_RequesteeId =:userId AND "
                . "Friend_RequesterId =:friendId AND "
                . "Status = 'accepted')";
        $prep2 = executeQuery($query2, ['userId'=>$userId, 'friendId'=>$friendId]);
        $row2 = $prep2 ? $prep2->fetch(PDO::FETCH_ASSOC) : null;
        if ($row2){
            // The users are friends
            $confirmationMsg = $friendName. ' (ID: '.$friendId.' ) and you are already friends!';
        } else {
            // The users are not friends
            // Check if there is a pending invitation from the friendId
            $query3 = "SELECT * FROM friendship WHERE "
                    . "Friend_RequesterId =:friendId AND "
                    . "Friend_RequesteeId =:userId AND "
                    . "Status = 'request'";
            $prep3 = executeQuery($query3, ['userId'=>$userId, 'friendId'=>$friendId]);
            $row3 = $prep3 ? $prep3->fetch(PDO::FETCH_ASSOC) : null;
            if ($row3) {
                // There was a pending request
                $confirmationMsg = $friendName. ' (ID: '.$friendId.' ) had already sent you a friend request. You are now friends!';
            } else {
                // Check if the current user already sent a requester to the other user                
                $query4 = "SELECT * FROM friendship WHERE "
                        . "Friend_RequesterId =:userId AND "
                        . "Friend_RequesteeId =:friendId AND "
                        . "Status = 'request'";
                $prep4 = executeQuery($query4, ['userId'=>$userId, 'friendId'=>$friendId]);
                $row4 = $prep4 ? $prep4->fetch(PDO::FETCH_ASSOC) : null;
                if ($row4){
                    // The user has already sent a friend request
                    $confirmationMsg = 'A friend request was already sent to '.$friendName. ' (ID: '.$friendId.' )! Please wait until they accept their request.';
                } else {
                   // No pending request -> send request
                $confirmationMsg = 'A friend request was sent to '.$friendName. ' (ID: '.$friendId.' )!';
                $query5 = "INSERT INTO friendship VALUES "
                        . "(:userId, :friendId, 'request')";
                executeQuery($query5,  ['userId'=>$userId, 'friendId'=>$friendId]); 
                }              
            }
        }
    } else {
        // User does not exists
        $errorMsg = 'The Id you entered is not registered with us.';
    }
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
        return "<div class='text-danger text-end'>Error: " . implode(", ", $stmt->errorInfo()); // This will show the error details
    }
     return "<div class='text-success text-start'>Album added successfully</div>";
}

// Uploads pictures to local file 
function uploadPictures($albumId, $title, $description, $files, $currentUserId) {
    $targetDirectory = 'C:\Program Files\Ampps\www\CST8257-Project\PHP\Common\Images\\';

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
        foreach ($errorMessages as $errorMsg) {
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

// Fetches comments
function getPictureComments($pictureId) {
    $dbConnection = parse_ini_file("./Common/Project.ini");
    extract($dbConnection);
    $pdo = new PDO($dsn, $user, $password);

    // Enable exceptions for error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        // Adjusted query without Comment_Date
        $query = "SELECT Comment_Text, Author_Id as User_Id FROM comment WHERE Picture_Id = :pictureId ORDER BY Comment_Id DESC";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':pictureId', $pictureId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}

function getAlbumDetails($albumId) {
    $dbConnection = parse_ini_file("./Common/Project.ini");
    extract($dbConnection);
    $pdo = new PDO($dsn, $user, $password);

    try {
        $query = "SELECT * FROM album WHERE Album_Id = :albumId";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':albumId', $albumId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}

//Adds/inserts comment into database

function addComment($pictureId, $authorId, $commentText) {
    echo "addComment function called"; // Debugging line
    echo "Picture ID: $pictureId, Author ID: $authorId, Comment Text: $commentText<br>";

    $dbConnection = parse_ini_file("./Common/Project.ini");
    extract($dbConnection);
    $pdo = new PDO($dsn, $user, $password);

    // Enable exceptions for error handling
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    try {
        // Prepare and execute statement...
        echo "Comment added successfully";
    } catch (PDOException $e) {
        echo "Error adding comment: " . $e->getMessage();
    }

    try {
        $query = "INSERT INTO comment (Author_Id, Picture_Id, Comment_Text) VALUES (:authorId, :pictureId, :commentText)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':authorId', $authorId, PDO::PARAM_STR);
        $stmt->bindParam(':pictureId', $pictureId, PDO::PARAM_INT);
        $stmt->bindParam(':commentText', $commentText, PDO::PARAM_STR);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        // Optionally, handle the error more gracefully than just outputting it
    }
}
function getAlbumsList($currentUserId){

   $dbConnection = parse_ini_file("./Common/Project.ini");
    extract($dbConnection);
    $pdo = new PDO($dsn, $user, $password);

    $query = "SELECT a.Album_Id as albumId, a.Title as albumTitle, a.Accessibility_Code as accessibilityCode, COUNT(p.Picture_Id) AS pictureCount FROM album a " 
            . "LEFT JOIN picture p ON a.Album_Id = p.Album_Id WHERE Owner_Id = :currentUserId "
            . "GROUP BY a.Album_Id, a.Title;";
    $prepQuery = $pdo->prepare($query);
    
    $prepQuery->execute(['currentUserId'=>$currentUserId]);

    $albumArray = [];
    $albumData = [];
    
    if ($prepQuery) {
        if ($prepQuery->rowCount() == 0) {
            $message = 'You don\'t have any Albums at the moment.';
        } else {
            $message = 'Your Albums';
            foreach ($prepQuery as $row){
                $albumData["albumId"]= $row["albumId"];
                $albumData["albumTitle"]= $row["albumTitle"];
                $albumData["accessibilityCode"]= $row["accessibilityCode"];
                $albumData["pictureCount"]= $row["pictureCount"];
                
                $albumArray[] = $albumData;
                $albumData = [];
            }
        }
    } else {
            $message = 'An error ocurred when trying to fetch your Albums.';
    }
   
        return ['message' => $message, 'albumArray'=>$albumArray];

}
