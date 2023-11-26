<?php
    session_start();
    extract($_POST);
    
    // include libraries
    foreach (glob("Common/Libraries/*.php") as $filename)
    {
        include $filename;
    }

    // Set active Link
    extract(setActiveLink('Log'));
    
    // Check user status
    $isLogged = (isset($_SESSION['serializedUser']));
    [$Message, $Link] = checkLogStatus($isLogged);
    
    // If the user is already logged in, redirect to Home page
    if ($isLogged){
        header("Location: Index.php");
        exit();
    }
    
    // Initialize error messages
    $errorArray = [$userIdErrorMsg = '', $userNameErrorMsg = '', 
        $userPhoneErrorMsg = '', $pswdErrorMsg = '', $pswd2ErrorMsg = ''];
    
    // Initialize user data
    $userData = [$userId = '', $userName = '', $userPhone = '',
        $pswd = '', $pswd2 = ''];
    
    // Extract user data from Session
    foreach($userData as $variable){
        initSessionVar($variable);
    }
 
//    // On btnClear click
//    if (isset($btnClear)){
//        // Clear all fields and error messages
//        $name = ''; $studentId = ''; $phone = ''; $pswd = ''; $pswd2 = '';
//
//        $nameErrorMsg = ''; $studentIdErrorMsg = '';
//        $phoneErrorMsg = ''; $pswdErrorMsg = ''; $pswd2ErrorMsg = '';
//        
//        // Clear relevant session variables
//        $_SESSION["name"] = $name;
//        $_SESSION["studentId"] = $studentId;
//        $_SESSION["phone"] = $phone;
//        $_SESSION["pswd"] = $pswd;
//        $_SESSION["pswd2"] = $pswd2;
//
//    }
//    
    // On btnNext click
    if (isset($btnSubmit)){
        // Retrieve input data
        extract($_POST);
        
        // Validate data
        ValidateStudentId($userId, $userIdErrorMsg);
        ValidateName($userName, $userNameErrorMsg);
        ValidatePhone($userPhone, $userPhoneErrorMsg);
        ValidatePswd($pswd, $pswdErrorMsg);
        ComparePswd($pswd, $pswd2, $pswd2ErrorMsg);
        
        // Save user inputs in session
        $_SESSION["userName"] = $userName;
        $_SESSION["userId"] = $userId;
        $_SESSION["userPhone"] = $userPhone;
        $_SESSION["pswd"] = $pswd;
        $_SESSION["pswd2"] = $pswd2;
        
        $errorArray = [$userIdErrorMsg, $userNameErrorMsg, 
        $userPhoneErrorMsg, $pswdErrorMsg, $pswd2ErrorMsg];
        
        // If the form is valid
        if (ValidateForm($errorArray)){ 
            
            // Hash user password
            $hashedPswd = hash("sha256", $pswd);

            // Create placeholder statement
            $sqlStatement = 'SELECT * FROM user'
                    . ' WHERE UserId = :userId';
            
            $prepStatement = executeQuery($sqlStatement, ['userId'=>$userId]);
            
            if($prepStatement){
                // Fetch resulting row
                $row = $prepStatement->fetch(PDO::FETCH_ASSOC);
            } else {
                // Query failed
                $userIdErrorMsg = 'An error ocurred while accessing the database.';
            }
            
            if($row){
                // User already exists
                $userIdErrorMsg = 'An user with this ID has already signed up.';
            } else {
                // User does not exist -> Insert in the table
                $insertStmt = 'INSERT INTO user VALUES(:UserId, :Name, :Phone, :Password)';
                
                executeQuery($insertStmt, ['UserId'=>$userId, 'Name'=>$userName, 'Phone'=>$userPhone, 'Password'=>$hashedPswd]);
                
                // Create user object
                $currentUser = new User($userId, $userName);
                
                // Save object to session
                $_SESSION['currentUser'] = $currentUser;
                $serializedUser = serialize($currentUser);
                $_SESSION['serializedUser'] = $serializedUser;
                
                // Redirect User to *.php
                header("Location: Index.php");

                // Redirect user to previous page
            }
        }
    }      
include("./Common/PageElements/header.php");
?>
<div class="container" style=" margin-left: 50px;">
    <div class="row">
        <div class="col offset-3"><h1 class='mb-2'>Sign Up</h1></div>
    </div>
    <div class="row mb-3 mt-3">
        <div class="col"><h5>All fields are required</h5></div>
    </div> 
    <div class='row'>
        <div class='col-md-8 col'>
            <hr style="margin: 0;">        
        </div>
    </div> 
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
        
    <div class="row mb-4 mt-4 row-cols-auto align-items-center">
        <div class="col-md-2 col-3 text-start">
            <label for="userId" class="form-label" >User ID: </label>
        </div>
        <div class="col-md-4 col-9">
            <input type="text" name="userId" id="userId" class="form-control" value="<?php echo htmlspecialchars($userId) ?>">
        </div>
        <div class="col-md-6">            
            <?php
                echo "<span style='color: red;'>$userIdErrorMsg</span>";
            ?>
        </div>
    </div>
    
    <div class="row mb-3 mt-3 row-cols-auto align-items-center">
        <div class="col-md-2 col-3 text-start">
            <label for="userName" class="form-label" >User Name: </label>
        </div>
        <div class="col-md-4 col-9">
            <input type="text" name="userName" id="userName" class="form-control" value="<?php echo htmlspecialchars($userName) ?>">
        </div>
        <div class="col-md-6">            
            <?php
                echo "<span style='color: red;'>$userNameErrorMsg</span>";
            ?>
        </div>
    </div>
    
    <div class="row mb-3 mt-3 row-cols-auto align-items-center">
        <div class="col-md-2 col-3 text-start">
            <label for="userPhone" class="form-label" >Phone Number: <br/><small class="text-body-secondary">(nnn-nnn-nnnn)</small> </label>
        </div>
        <div class="col-md-4 col-9">
            <input type="text" name="userPhone" id="userPhone" class="form-control" value="<?php echo htmlspecialchars($userPhone) ?>">
        </div>
        <div class="col-md-6">            
            <?php
                echo "<span style='color: red;'>$userPhoneErrorMsg</span>";
            ?>
        </div>
    </div>
        
    <div class="row mb-3 mt-3 row-cols-auto align-items-center">
        <div class="col-md-2 col-3 text-start">
            <label for="pswd" class="form-label" >Password: </label>
        </div>
        <div class="col-md-4 col-9">
            <input type="password" name="pswd" id="pswd" class="form-control" value="<?php echo htmlspecialchars($pswd) ?>">
        </div>
        <div class="col-md-6">            
            <?php
                echo "<span style='color: red;'>$pswdErrorMsg</span>";
            ?>
        </div>
    </div>
        
    <div class="row mb-4 mt-md-4 mt-3 row-cols-auto align-items-center">
        <div class="col-md-2 col-3 text-start">
            <label for="pswd2" class="form-label" >Confirm Password: </label>
        </div>
        <div class="col-md-4 col-9">
            <input type="password" name="pswd2" id="pswd2" class="form-control" value="<?php echo htmlspecialchars($pswd2) ?>">
        </div>
        <div class="col-md-6">            
            <?php
                echo "<span style='color: red;'>$pswd2ErrorMsg</span>";
            ?>
        </div>
    </div>
        
    <div class="row align-items-center">
        <div class="d-grid col-md-2 col-4 offset-md-2 offset-3">
            <button type="submit" name="btnSubmit" class="btn btn-primary" id="submit">Submit</button>
        </div>
        <div class="d-grid col-md-2 col-4">
            <button type="submit" name="btnClear" class="btn btn-primary" id="clear">Clear</button>
        </div>
    </div>
    </form>
</div>
<?php
include("./Common/PageElements/footer.php"); 
?>