<?php

// This is the source file for all functions

// activeLink: Takes the currentPage identifier and returns a compact
// array to extract active Link
function setActiveLink($currentPage){
    
    // Initialize all flags to null
    $activeHome = null;
    $activeFriends = null;
    $activeAlbums = null;
    $activePictures = null;
    $activeUpload = null;
    $activeLog = null;
    
    // Set the active flag based on the current page
    switch ($currentPage) {
        case 'home':
            $activeHome = "active";
            break;
        case 'Friends':
            $activeFriends = "active";
            break;
        case 'Albums':
            $activeAlbums = "active";
            break;
        case 'Pictures':
            $activePictures = "active";
            break;
        case 'Upload':
            $activeUpload = "active";
            break;
        case 'Log':
            $activeLog = "active";
            break;        
        default:
            break;
        }
        
    // Return the active flags
    return compact('activeHome', 'activeFriends', 'activeAlbums',
            'activePictures', 'activeUpload', 'activeLog');
    ;
}

// checkLogStatus: receives the log in status of the user and returns
// the relevant information for display and links
function checkLogStatus($isLogged){
    if ($isLogged) {
    $result = ['Log Out', 'LogOut.php'];
    } else {
    $result = ['Log In', 'LogIn.php'];
    }
    return $result;
}

// executeQuery: receives a Query string and an array of variable
// assignment to execute the Query
function executeQuery($query, $arguments){    
    // Database Connection
    $dbConnection = parse_ini_file("./Common/Project.ini");
    extract($dbConnection);
    $myPdo = new PDO($dsn, $user, $password);
    
    // Prepare query
    $preparedQuery = $myPdo -> prepare($query);
    
    // Execute query
    $preparedQuery -> execute($arguments);
    
    return $preparedQuery;
}

function initSessionVar(&$variable){
    if (isset($_SESSION[$variable])){
        $variable = $_SESSION[$variable];
    } else {
        $variable = '';
    }
    
    return $variable;
}