<?php
session_start();
extract($_POST);

// include libraries
foreach (glob("Common/Libraries/*.php") as $filename) {
    include $filename;
}

// Set active Link
extract(setActiveLink('Friends'));

$isLogged = isset($_SESSION['userId']);
[$Message, $Link] = checkLogStatus($isLogged);

$currentUserId = $_SESSION['userId'] ?? null;
$currentUserName = $_SESSION['userName'] ?? null;



if (!$isLogged) {
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


<div class="container">
    <h2 class="row justify-content-center">My Friends</h2>

    <form method="post" id="friendListForm">
        <!-- Friend List -->
        <div class="form-group">               
            <?php 
                $friendList = getFriendsList($currentUserId); 
                echo <<<HTML
                            <div class="row justify-content-center">
                                <div class="col-lg-4 col-sm-9">
                                    <h4>{$friendList['message']}</h4>
                                </div>
                                <div class="col-lg-2 col-sm">
                                    <a href="AddFriend.php">Add Friends</a>
                                </div>
                            </div>
                    HTML; 
                if (count($friendList['friendArray']) > 0){
                    echo '<div class="row justify-content-center">';
                    echo <<<TABLE
                        <div class="col-lg-6 col-sm">
                            <table id="friendsTable" class="table table-dark table-hover">
                            <thead>
                                <tr>
                                    <th class="col-lg-2 col-sm">Friend Name</th>
                                    <th class="col-lg-2 col-sm">Shared Albums</th>
                                    <th class="text-center col-lg-1 col-sm">Unfriend</th>
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
                                <td class="text-center">
                                    <input type='checkbox' name='friendCbl[]' value='{$friendId}'>
                                </td>
                            </tr>   
                        ROW;
                    }
                    echo "</tbody></table></div></div>";                  
                    echo "<div class='row offset-lg-3'><button type='submit' name='btnUnfriend' class='col-auto btn btn-primary mt-1 ms-lg-1 ms-sm-2' onclick='return confirmDelete()'>"
                    . "Unfriend Selected</button></div>";
                }
            ?>              
        </div>
    </form>
    <form method="post" id="requestsForm" class="mt-3">
        <!-- Friends Request List -->
        <div class="form-group">               
            <?php 
                $friendRequest = getFriendsRequests($currentUserId); 
            echo <<<HTML
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <p>{$friendRequest['message']}</p>
                        </div>
                    </div>                   
                    HTML; 
                if (count($friendRequest['requestArray']) > 0){
                    echo '<div class="row">';
                    echo <<<TABLE
                    <div class="col-lg-4 col-sm offset-lg-3">
                        <table id="requestTable" class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th class="col-lg-2 col-sm">User Name</th>
                                <th class="text-center col-lg-2 col-sm">Accept or Deny</th>
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
                                <td class="text-center">
                                    <input type='checkbox' name='requestCbl[]' value='{$userId}'>
                                </td>
                            </tr>   
                        ROW;
                    }
                    echo "</tbody></table></div></div>";
                    echo <<<BTN
                        <div class="row justify-content-center">
                            <div class="col offset-lg-3">
                                <button type='submit' name='btnAccept' class='btn btn-primary mt-2 me-2'>Accept Selected</button>
                                <button type='submit' name='btnDeny' class='btn btn-primary mt-2' onclick='return confirmDeny()'>Deny Selected</button>
                            </div>
                        </div>
                    BTN;
                }
            ?>              
        </div>
    </form>
</div>

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
