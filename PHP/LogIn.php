<?php
session_start();
extract($_POST);

// include libraries
foreach (glob("Common/Libraries/*.php") as $filename) {
    include $filename;
}

// Set active Link
extract(setActiveLink('Log'));

// Check if the user is already logged in
if (isset($_SESSION['userId'])) {
    // User is already logged in, redirect to home page
    header("Location: Index.php");
    exit();
}

// Initialize error messages
$errorArray = [$userIdErrorMsg = '', $pswdErrorMsg = '',
    $dataErrorMsg = ''];

// Initialize user data
$userData = [$userId = '', $pswd = ''];

// Extract user data from Session
foreach ($userData as $variable) {
    initSessionVar($variable);
}

// On btnClear click
//    if (isset($btnClear)){
//        // Clear all fields and error messages
//        $studentId = ''; $pswd = '';
//
//        $studentIdErrorMsg = '';$pswdErrorMsg = ''; $dataErrorMsg = '';
//        
//        // Clear relevant session variables
//        $_SESSION["studentId"] = $studentId;
//        $_SESSION["pswd"] = $pswd;
//
//    }
// On btnNext click
if (isset($btnSubmit)) {
    // Retrieve input data
    extract($_POST);

    // Validate data
    if ($userId == '') {
        $userIdErrorMsg = 'Student ID is required.';
    }

    if ($pswd == '') {
        $pswdErrorMsg = 'Password is required.';
    }

    $errorArray = [$userIdErrorMsg, $pswdErrorMsg];

    // Save user inputs
    $_SESSION["userId"] = $userId;
    $_SESSION["pswd"] = $pswd;

    // If the form is valid
    if (ValidateForm($errorArray)) {
        // Check if the user information is in the DB
        $hashedPswd = hash("sha256", $pswd);
        $sqlStatement = "SELECT * FROM user WHERE UserId = :userId AND Password = :hashedPswd";
        $prepStatement = executeQuery($sqlStatement, ['userId' => $userId, 'hashedPswd' => $hashedPswd]);

        if ($prepStatement && $row = $prepStatement->fetch(PDO::FETCH_ASSOC)) {
            // User exists, save userId and name to the session
            $_SESSION['userId'] = $row['UserId'];
            $_SESSION['userName'] = $row['Name'];

            // Redirect to the home page
            header("Location: Index.php");
            exit();
        } else {
            // User does not exist or query failed
            $dataErrorMsg = 'Incorrect UserId and/or Password.';
        }
    }
}
include("./Common/PageElements/header.php");
?>
<div class="container" style="width:60%; margin-left: 50px;">
    <div class="row">
        <div class="col offset-3"><h1 class='mb-4'>Log In</h1></div>
    </div>
    <div class="row mb-3 mt-3">
        <div class="col"><h5>If you are a new user, you must <a href="NewUser.php">Sign In</a> first.</h5></div>
    </div> 

    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        <hr style="width: 66%; margin: 0;">
        <div class="row mb-3 mt-3 justify-content-center align-items-center">
<?php
echo "<div class='col-6'><span style='color: red;'>$dataErrorMsg</span></div>";
?>
        </div>

        <div class="row mb-3 mt-3 justify-content-center align-items-center">
            <div class="col-3 text-start"><label for="userId" class="form-label">User ID: </label></div>
            <div class="col-sm-5"><input type="text" name="userId" id="userId" class="form-control" value="<?php echo htmlspecialchars($userId) ?>"></div>
<?php
echo "<div class='col-4'><span style='color: red;'>$userIdErrorMsg</span></div>";
?>
        </div>

        <div class="row mb-5 mt-3 justify-content-center align-items-center">
            <div class="col-3 text-start"><label for="pswd" class="form-label">Password: </label></div>
            <div class="col-sm-5"><input type="password" name="pswd" id="pswd" class="form-control" value="<?php echo htmlspecialchars($pswd) ?>"></div>
<?php
echo "<div class='col-4'><span style='color: red;'>$pswdErrorMsg</span></div>";
?>
        </div>

        <div class="row align-items-center gap-sm-2">
            <div class="d-grid col-sm-2 offset-sm-2 mb-1">
                <button type="submit" name="btnSubmit" class="btn btn-primary" id="submit">Submit</button>
            </div>
            <div class="d-grid col-sm-2">
                <button type="submit" name="btnClear" class="btn btn-primary" id="clear">Clear</button>
            </div>
        </div>
    </form>
</div>
<?php
include("./Common/PageElements/footer.php");
?>