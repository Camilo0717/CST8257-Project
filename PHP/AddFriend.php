<?php
session_start();

// simulate log in
// extract user data
// Retrieve user data

// Common function: If the user is not logged in and tries to access any private page
// Redirect to log in
// After login, redirect to the page he/she was trying to access

$userId = 'id1';
$userName = 'user1';

// include libraries
foreach (glob("Common/Libraries/*.php") as $filename)
{
    include $filename;
}

// Set active Link
extract(setActiveLink('Friends'));

// Check user status
$isLogged = (isset($_SESSION['UserData']));
[$Message, $Link] = checkLogStatus($isLogged);

// Extract data
$friendId = null;
$errorMsg = '';
$confirmationMsg = '';

// Database Connection
$dbConnection = parse_ini_file("./Common/Project.ini");

extract($dbConnection);

$myPdo = new PDO($dsn, $user, $password);

// Test connection

$sqlLFriends = 'SELECT Friend_RequesteeId, Status FROM friendship'
        . ' WHERE Friend_RequesterId=:userId';

$prepFriends = executeQuery($sqlLFriends, ['userId'=>$userId]);

//if ($prepFriends){
//    foreach ($prepFriends as $request){
//        echo "Requested id:" .$request['Friend_RequesteeId'];
//        echo "<br/>";
//        echo "Status:" .$request['Status'];
//        echo "<br/>";
//    }
//} else {
//    $errorMsg = 'Error in the database connection';
//}
// On post: Check if friendID exists
// friendID != self.id
// request rules

if (isset($btnSubmit)){
    extract($_POST);
    $errorMsg = '';
    $confirmationMsg = '';
    $userId = 'id1';
    $userName = 'user1';
    
    // validate friendID
}

include("./Common/PageElements/header.php");
?>
<div class="container" style=" margin-left: 50px;">
    <div class="row">
        <div class="col offset-3"><h1 class='mb-4'>Add Friend</h1></div>
    </div>
    <div class='row'>
        <div class='col-8'>
            <h5 style='line-height: 1'>Welcome <strong><?php echo $userName;?></strong>! (Not you? Change user <a href="LogOut.php">here</a>).</h5>
        </div>
    </div>
    <div class='row'>
        <div class='col-8'>
            <h5 style='line-height: 1'>Please enter the ID of the user you want to be friends with.</h5>
        </div>
    </div>
    <div class='row'>
        <div class='col-8'>
            <hr style="margin: 0;">        
        </div>
    </div>       
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
    <div class="row mb-3 mt-3 row-cols-auto align-items-center">
        <div class="col-sm-1 text-center">
            <label for="friendId" class="form-label" >ID: </label>
        </div>
        <div class="col-sm-3">
            <input type="text" name="friendId" id="friendId" class="form-control" value="<?php echo htmlspecialchars($friendId) ?>">
        </div>
        <div class="col-sm-4 mt-sm-0 mt-3 ms-sm-0 ms-5">
            <button type="submit" name="btnSubmit" class="btn btn-primary" id="submit">Send Friend Request</button>
        </div>       
    </div>
    <div class="row mt-1">
        <div class="col-8">
        <?php 
            if ($errorMsg!=''){
                echo <<<HTML
                    <div class="alert alert-danger" role="alert">
                        {$errorMsg}
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
