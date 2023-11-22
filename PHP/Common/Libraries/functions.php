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