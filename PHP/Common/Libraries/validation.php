<?php

/*
 * Function ValidateUserId:
 * Takes two strings $userd and $errorMsg as input
 * Checks if $userId is not null
 */
function ValidateStudentId($userId, &$errorMsg) {
    if ($userId == ""){
        $errorMsg = "User ID is required.";
    }
}

/*
 * Function ValidateName:
 * Takes two strings $name and $errorMsg as input
 * Checks if $name is not an empty string and
 * if its composed as UpperCase letter followed by 
 * a series of lowecased letters, repeated for as many different
 * names the person has
 */
function ValidateName($name, &$errorMsg) {
    if ($name != ""){
        $nameRegex = "/^([A-Z]{1}[a-z]{1,}(\s){0,1}){1,5}$/";
        $array = [];
        if (preg_match($nameRegex, $name, $array)){
            $errorMsg = "";
        } else {
            $errorMsg = "Invalid Name.";
        }
    } else {
        $errorMsg = "Name is required.";
    }
}


/*
 * Function ValidatePhone
 * Takes two strings $phone and $errorMsg as input
 * Checks if $phone is not an empty string and
 * if its in the  in the form nnn-nnn-nnnn, where n 
 * is a digit. The first n in the first
 * and second 3 digit groups cannot be 0 or 1.
 */
function ValidatePhone($phone, &$errorMsg) {
    if ($phone != ""){
        $phoneRegex = "/^([2-9][0-9]{2})(\-)([2-9][0-9]{2})(\-)([0-9]{4})$/";
        $array = [];
        if (preg_match($phoneRegex, $phone, $array)){
            $errorMsg = "";
        } else {
            $errorMsg = "Invalid Phone Number.";
        }
    } else {
       $errorMsg = "Phone Number is required."; 
    }
}

/*
 * Function ValidatePassword
 * Takes two strings $pswd and $errorMsg as input
 * Checks if $pswd is not blank and if it
 * has at least 6 characters, at least one
 * upper case, at least one lower case and 
 * a digit
 */
function ValidatePswd($pswd, &$errorMsg) {
    if ($pswd != ""){
        $pswdRegex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).+$/";
        $array = [];
        if (strlen($pswd) >= 6 && preg_match($pswdRegex, $pswd, $array)){
            $errorMsg = "";
        } else {
            $errorMsg = "Password must be at least 6 characters long and include at "
                    . "least one digit, upper character and lower character.";
        }
    } else {
       $errorMsg = "Password is required."; 
    }
}

/*
 * Function comparePassword
 * Takes three strings $pswd, $pswd2 and $errorMsg as input
 * Checks if $pswd and $pswd2 match
 */
function ComparePswd($pswd, $pswd2, &$errorMsg) {
    if ($pswd2 != ""){
        if (strcmp($pswd, $pswd2) == 0){
            $errorMsg = "";
        } else {
            $errorMsg = "Passwords must match.";
        }
    } else {
       $errorMsg = "Password confirmation is required."; 
    }
}

/*
 * Function ValidateForm:
 * Takes an array of strings as argument
 * If all strings are empty, the form has no validation issues
 * and returns true, otherwise, return false
 */
function ValidateForm($array){
    foreach($array as $error){
        if ($error != ""){
            return false;
        }
    }
    return true;
}
