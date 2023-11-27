<?php
session_start();

// include libraries
foreach (glob("Common/Libraries/*.php") as $filename) {
    include $filename;
}

// Set active Link
extract(setActiveLink('Friends'));

// Check user status
$isLogged = (isset($_SESSION['serializedUser']));
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


//$selectedAlbumId = $_POST['albumSelection'] ?? null;
//$thumbnails = $selectedAlbumId ? getThumbnails($selectedAlbumId) : [];
//
//$selectedPictureId = $_GET['selectedPicture'] ?? null;
//$selectedPictureDetails = null;
//$pictureComments = null;
//
//if ($selectedPictureId) {
//    $selectedPictureDetails = getPictureDetails($selectedPictureId);
//    // create getPictureComments function exists to fetch comments
//    $pictureComments = getPictureComments($selectedPictureId);
//}


include 'Common/PageElements/header.php';
?>

<body>
    <div class="container mt-5">
        <h2>My Friends</h2>

        <form method="post" id="friendListForm">
            <!-- Friend List -->
            <div class="form-group">               
                <?php 
                    $friendList = getFriendsList($currentUser -> getUserId()); 
                    echo <<<HTML
                                <div class=row>
                                    <div class=col>
                                        <p>{$friendList['message']}</p>
                                    </div>
                                    <div class=col>
                                        <a href="AddFriend.php">Add Friends</a>
                                    </div>
                                </div>
                        HTML; 
                    if (count($friendList['friendArray']) > 0){
                        echo <<<TABLE
                            <table id="friendsTable" class="table">
                            <thead>
                                <tr>
                                    <th>Friend Name</th>
                                    <th>Shared Albums</th>
                                    <th>Defriend</th>
                                </tr>
                            </thead>
                            <tbody>
                        TABLE;
                        foreach ($friendList['friendArray'] as $row){
                            $friendName = htmlspecialchars($row);
                            echo <<<ROW
                                <tr>
                                    <td>{$friendName}</td>
                                    <td>0</td>
                                    <td>
                                        <input type='checkbox' name='courseCbl[]' value=''>
                                    </td>
                                </tr>   
                            ROW;
                        }
                        echo "</tbody></table>";                  
                        echo "<button type='submit' class='btn btn-primary mt-2'>Defriend Selected</button>";
                    }
                ?>              
            </div>
        </form>
</body>


<?php
include 'Common/PageElements/Footer.php';
?>
