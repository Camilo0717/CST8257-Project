<?php
session_start();
extract($_POST);

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

// Unfriend Method
if (isset($btnUnfriend)){
    extract($_POST);
    $currentUserId = $_SESSION['userId'] ?? null;
    if (isset($friendCbl)){
        foreach ($friendCbl as $friend){
            // Delete record from friendship
            deleteFriend($friend, $currentUserId);
        }
    } 
}

if (isset($btnDeny)){
    extract($_POST);
    $currentUserId = $_SESSION['userId'] ?? null;
    if (isset($requestCbl)){
        foreach ($requestCbl as $userId){
            // Delete record from friendship
            deleteRequest($userId, $currentUserId);
        }
    }
}


if (isset($btnAccept)){
    extract($_POST);
    $currentUserId = $_SESSION['userId'] ?? null;
    if (isset($requestCbl)){
        foreach ($requestCbl as $userId){
            // Change status from reuqest to accept in friendship
            acceptRequest($userId, $currentUserId);
        }
    }
}

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
                            <table id="friendsTable" class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th>Friend Name</th>
                                    <th>Shared Albums</th>
                                    <th>Defriend</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                        TABLE;
                        foreach ($friendList['friendArray'] as $row){
                            $friendId = htmlspecialchars($row['friendId']);
                            $friendName = htmlspecialchars($row['friendName']);
                            $sharedAlbums = htmlspecialchars($row['sharedAlbums']);
                            echo <<<ROW
                                <tr>
                                    <td>{$friendName}</td>
                                    <td>{$sharedAlbums}</td>
                                    <td>
                                        <input type='checkbox' name='friendCbl[]' value='{$friendId}'>
                                    </td>
                                </tr>   
                            ROW;
                        }
                        echo "</tbody></table>";                  
                        echo "<button type='submit' name='btnUnfriend' class='btn btn-primary mt-2' onclick='return confirmDelete()'>Unfriend Selected</button>";
                    }
                ?>              
            </div>
        </form>
        <form method="post" id="requestsForm">
            <!-- Friends Request List -->
            <div class="form-group">               
                <?php 
                    $friendRequest = getFriendsRequests($currentUser -> getUserId()); 
                    echo <<<HTML
                                <div class=row>
                                    <div class=col>
                                        <p>{$friendRequest['message']}</p>
                                    </div>
                                </div>
                        HTML; 
                    if (count($friendRequest['requestArray']) > 0){
                        echo <<<TABLE
                            <table id="requestTable" class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th>User Name</th>
                                    <th>Accept or Deny</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">
                        TABLE;
                        foreach ($friendRequest['requestArray'] as $row){
                            $userId = htmlspecialchars($row['userId']);
                            $userName = htmlspecialchars($row['userName']);
                            echo <<<ROW
                                <tr>
                                    <td>{$userName}</td>
                                    <td>
                                        <input type='checkbox' name='requestCbl[]' value='{$userId}'>
                                    </td>
                                </tr>   
                            ROW;
                        }
                        echo "</tbody></table>"; 
                        echo "<button type='submit' name='btnAccept' class='btn btn-primary mt-2'>Accept Selected</button>";
                        echo "<button type='submit' name='btnDeny' class='btn btn-primary mt-2' onclick='return confirmDeny()'>Deny Selected</button>";
                    }
                ?>              
            </div>
        </form>
    </div>
</body>
<script>
    function confirmDelete(){
        let result = confirm("Are you sure you want to delete the selected friends?");
        return result;  
    }
    
    function confirmDeny(){
        let result = confirm("Are you sure you want to deny the selected friend requests?");
        return result;  
    }
</script>
<?php
include 'Common/PageElements/Footer.php';
?>
