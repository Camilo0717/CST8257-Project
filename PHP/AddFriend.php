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
<div class="container" style=" margin-left: 50px;">
    <div class="row">
        <div class="offset-1 offset-sm-2"><h1 class='mb-4'>Add Friend</h1></div>
    </div>
    <div class='row'>
        <div class='col'>
            <h5 style='line-height: 1'>Welcome <strong><?php echo $currentUserName;?></strong>! (Not you? Change user <a href="LogOut.php">here</a>).</h5>
        </div>
    </div>
    <div class='row'>
        <div class='col'>
            <h5 style='line-height: 1'>Please enter the ID of the user you want to be friends with.</h5>
        </div>
    </div>
    <div class='row'>
        <div class='col-sm col-lg-8'>
            <hr style="margin: 0;">        
        </div>
    </div>       
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
    <div class="row mb-3 mt-3 align-items-center">
        <div class="col-sm-1 text-center">
            <label for="friendId" class="form-label" >ID: </label>
        </div>
        <div class="col-sm-6 col-lg-4">
            <input type="text" name="friendId" id="friendId" class="form-control" value="<?php echo htmlspecialchars($friendId) ?>">
        </div>
        <div class="col-sm-5 col-lg-4 mt-sm-0 mt-3 ms-sm-0 ms-5">
            <button type="submit" name="btnSubmit" class="btn btn-primary" id="submit">Send Friend Request</button>
        </div>       
    </div>
    <div class="row mt-1">
        <div class="col-sm col-lg-7">
        <?php 
            if ($errorMsg!=''){
                echo <<<HTML
                    <div class='col-8 offset-2'>
                        <span style='color: red;'>$errorMsg</span>
                    </div>
                HTML;
            }
        ?>
        </div>
    </div>
    <div class="row mt-1">
        <div class="col-8">
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
