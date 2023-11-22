<?php
session_start();

// Select active page
$activeHome = "active";
$activeCourse = null;
$activeRegistration = null;
$activeLog = null;

// Check user status
$isLogged = (isset($_SESSION['UserData']));
$Message = $isLogged ? 'Log Out' : 'Log In';
$Link = $isLogged ? 'LogOut.php' : 'LogIn.php';

include("./Common/PageElements/header.php");
?>
<div class="container" style="width: 50%;">
<?php 
// If the user is logged in display custom message
if (isset($_SESSION['UserData'])){
    $name = $_SESSION['UserData']['Name'];
    echo <<<DOC
    <h1 class='text-center mb-4'>Welcome back $name</h1>
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
    <h1 class='text-center mb-4'>Welcome to Online Registration </h1>
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
//include("./common/img/footer.php"); 
?>