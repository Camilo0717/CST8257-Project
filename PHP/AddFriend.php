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
$isLogged = (isset($_SESSION['userId']));
[$Message, $Link] = checkLogStatus($isLogged);

if (!$isLogged) {
    $_SESSION['Location'] = 'AddFriend.php';
    header("Location: LogIn.php");
    exit;
}

$currentUserId = $_SESSION['userId'] ?? null;
$currentUserName = $_SESSION['userName'] ?? null;
$friendId = $_SESSION['friendId'] ?? null;

$errorMsg = '';
$confirmationMsg = '';

if (isset($btnSubmit)){
    extract($_POST);
    $currentUserId = $_SESSION['userId'] ?? null;
    if (isset($friendId)){
        if ($friendId == '' || $friendId == null){
            $errorMsg = 'You did not submit any friend Id.';
        } else if ($friendId == $currentUserId){
            $errorMsg = 'You cannot send a friend request to yourself!';
        } else {
            sendFriendRequest($currentUserId, $friendId, $errorMsg, $confirmationMsg);
        }
    }
}

include("./Common/PageElements/header.php");
?>
<div class="container text-center">
    <div class="row">
        <div class="col"><h1 class='mb-4'>Add Friend</h1></div>
    </div>
    <div class='row'>
        <div class='col-auto offset-lg-3'>
            <h5 style='line-height: 1'>Welcome <strong><?php echo $currentUserName;?></strong>! (Not you? Change user <a href="LogOut.php">here</a>).</h5>
        </div>
    </div>
    <div class='row'>
        <div class='col-auto offset-lg-3'>
            <h5 style='line-height: 1'>Please enter the ID of the user you want to be friends with.</h5>
        </div>
    </div>
    <div class='row justify-content-center'>
        <div class='col-sm col-lg-8'>
            <hr style="margin: 0;">        
        </div>
    </div>       
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
    <div class="row mb-3 mt-3 justify-content-center">
        <div class="col-sm-1 col-lg-1">
            <label for="friendId" class="form-label" >ID: </label>
        </div>
        <div class="col-sm-6 col-lg-3">
            <input type="text" name="friendId" id="friendId" class="form-control" value="<?php echo htmlspecialchars($friendId) ?>">
        </div>
        <div class="col-sm-5 col-lg-3">
            <button type="submit" name="btnSubmit" class="btn btn-primary" id="submit">Send Friend Request</button>
        </div>       
    </div>
    <div class="row mt-1 justify-content-center">
        <div class="col-8">
        <?php 
            if ($errorMsg!=''){
                echo <<<HTML
                    <div class='col'>
                        <span style='color: red;'>$errorMsg</span>
                    </div>
                HTML;
            }
        ?>
        </div>
    </div>
    <div class="row mt-1 justify-content-center">
        <div class="col-lg-8 col-sm">
        <?php 
        if ($confirmationMsg!=''){
            echo <<<HTML
                <div class="alert alert-success" role="alert">
                    {$confirmationMsg}
                </div>
            HTML;
        }
        ?>
        </div>
    </div>
    </form>
</div>
<?php
include("./Common/PageElements/footer.php"); 
?>
