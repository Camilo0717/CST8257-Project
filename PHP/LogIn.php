<?php
    session_start();
    extract($_POST);
    
    // Include classes
    
    require_once('./Common/Course.php');
    require_once('./Common/Student.php');
    
    // Select active page
    $activeHome = null;
    $activeCourse = null;
    $activeRegistration = null;
    $activeLog = "active";
    
    // Check user status
    $isLogged = (isset($_SESSION['UserData']));
    $Message = $isLogged ? 'Log Out' : 'Log In';
    $Link = $isLogged ? 'LogOut.php' : 'LogIn.php';
    
    // If the user is already logged in, redirect to course Selection
    if ($isLogged){
        header("Location: CourseSelection.php");
        exit();
    }
    
    // Initialize error messages as empty strings
    $studentIdErrorMsg = ''; $pswdErrorMsg = '';
    $dataErrorMsg = '';
    
    // Include validation functions
    include 'functions.php';
    
    // Retrieve or initialize variables
    if (isset($_SESSION["studentId"])){
        $studentId = $_SESSION["studentId"];
    } else {
        $studentId = '';
    }
    
    if (isset($_SESSION["pswd"])){
        $pswd = $_SESSION["pswd"];
    } else {
        $pswd = '';
    }
    
    // On btnClear click
    if (isset($btnClear)){
        // Clear all fields and error messages
        $studentId = ''; $pswd = '';

        $studentIdErrorMsg = '';$pswdErrorMsg = ''; $dataErrorMsg = '';
        
        // Clear relevant session variables
        $_SESSION["studentId"] = $studentId;
        $_SESSION["pswd"] = $pswd;

    }
    
    // On btnNext click
    if (isset($btnSubmit)){
        // Retrieve input data
        extract($_POST);
        
        // Validate data
        if ($studentId == ''){
            $studentIdErrorMsg = 'Student ID is required.';
        }
        
        if ($pswd == ''){
            $pswdErrorMsg = 'Password is required.';
        }
        
        $errorArray = [$studentIdErrorMsg,$pswdErrorMsg];
        
        // Save user inputs
        $_SESSION["studentId"] = $studentId;
        $_SESSION["pswd"] = $pswd;

        // If there are no errors
        if (ValidateForm($errorArray)){           
            // Check if the student information is in the DB
            $dbConnection = parse_ini_file("Lab6.ini");
            
            extract($dbConnection);
            
            $myPdo = new PDO($dsn, $user, $password);
            $hashedPswd = hash("sha256", $pswd);
            
            // Placeholder statement
            $sqlStatement = "SELECT StudentId, Name, Phone, Password FROM Student"
                    . " WHERE StudentId = :studentId AND Password = :hashedPswd";
            
            // Prepare statement
            $prepStatement = $myPdo -> prepare($sqlStatement);
            
            // Execute prepared statement
            $prepStatement -> execute(['studentId'=>$studentId, 'hashedPswd'=>$hashedPswd]);
                       
            if ($prepStatement){
                            $row = $prepStatement->fetch(PDO::FETCH_ASSOC);
            } else {
                // Query failed
                $dataErrorMsg = 'An error ocurred while logging in.';
            }
            
            if($row){
                // User exists
                // Save data into session for CourseSelection.php page
                $_SESSION['UserData'] = $row;
                
                // Redirect User to CourseSelection.php
                header("Location: CourseSelection.php");
            } else {
                // User does not exist
                $dataErrorMsg = 'Incorrect studentID and/or Password.';
            }

        }
    }      
include("./common/img/header.php");
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
        <div class="col-3 text-start"><label for="studentId" class="form-label">StudentID: </label></div>
        <div class="col-sm-5"><input type="text" name="studentId" id="studentId" class="form-control" value="<?php echo htmlspecialchars($studentId) ?>"></div>
        <?php
            echo "<div class='col-4'><span style='color: red;'>$studentIdErrorMsg</span></div>";
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
include("./common/img/footer.php"); 
?>