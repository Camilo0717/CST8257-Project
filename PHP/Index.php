<?php
session_start();

// include libraries
foreach (glob("Common/Libraries/*.php") as $filename) {
    include $filename;
}

// Set active Link
extract(setActiveLink('home'));

// Check user status
$isLogged = isset($_SESSION['userId']);
[$Message, $Link] = checkLogStatus($isLogged);

if (!$isLogged) {
    header("Location: LogIn.php");
    exit;
}

include("./Common/PageElements/header.php");
?>
<div class="container" style="width: 50%;">
    <?php
// If the user is logged in display custom message
    if (isset($_SESSION['serializedUser'])) {
    $name = $currentUser->getName();
        echo <<<DOC
    <h1 class='text-center mb-4'>Welcome back to Algonquin Social Media Site, $name</h1>
    <div class='row'>
        <div class='col offset-1'>
            <h5 style='line-height: 1.5'>You are already logged in. If you wish to log out proceed to <a href="LogOut.php">Log Out.</a></h5>
        </div>
    </div>
    <br/>
    </div>
    DOC;
    } else {
        echo <<<DOC
    <h1 class='text-center mb-4'>Welcome to Algonquin Social Media Site </h1>
    <div class='row'>
        <div class='col offset-1'>
            <h5 style='line-height: 1.5'>If you have never used this application before, you must <a href="NewUser.php">Sign In</a> first.</h5>
        </div>
    </div>
    <div class='row'>
        <div class='col offset-1'>
            <h5 style='line-height: 1.5'>If you have already signed up, you can <a href="LogIn.php">Log In</a> now.</h5>
        </div>
    </div>
    <br/>
    </div>
    DOC;
    }
    ?>       
    <?php
    include("./Common/PageElements/footer.php");
    ?>