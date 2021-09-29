<?php
session_start();
include '../classes/Validate.php';
include '../classes/UserAuth.php';

// CHECK FOR FORM SUBMISSION
if (!isset($_POST['submit'])) {
    // redirect back to login page
    header('Location: ../pages/login.page.php');
    exit;
}

// define a session variable to hold
  // login data to redisplay in login form
  $_SESSION['login']['name_email'] = '';
  // & possible errors to display in login form
  $_SESSION['errors']['db'] = '';
  $_SESSION['errors']['name_email'] = '';
  $_SESSION['errors']['pwd'] = '';

// INPUT VALIDATION
  // CHECK FOR EMPTY FIELDS
    // user name or email
    if (empty($_POST['name'])) {
        $_SESSION['errors']['name_email'] = 'User Name or Email is required';
        // redirect back to login page
        header('Location: ../pages/login.page.php');
        exit;
    }
    $validate = new Validate();
    $name_email = $validate->cleanInput($_POST['name']);
    unset($validate);

    // password
    if (empty($_POST['pwd'])) {
        $_SESSION['login']['name_email'] = $name_email;
        $_SESSION['errors']['pwd'] =  'Password is required';
        // redirect back to login page
        header('Location: ../pages/login.page.php');
        exit;
    }
    $validate = new Validate();
    $pwd = $validate->cleanInput($_POST['pwd']);
    unset($validate);

// GET USER FROM DB
  // if no input errors, get user from database if exist
    // get user from database into the '$getUser' variable
    $dbUserAuth = new UserAuth();
    $getUser = $dbUserAuth->getUser($name_email);
    unset($dbUserAuth);


    // if user / email not found
    if ($getUser === false) {
        $_SESSION['login']['name_email'] = $name_email;
        $_SESSION['errors']['name_email'] = 'User Name or Email not found';
        $_SESSION['errors']['db'] = $getUser;
        // redirect back to login page
        header('Location: ../pages/login.page.php');
        exit;
    }

    // if user / email found but the password doesn't match
    $dbPwdHash = $getUser['user_pwd'];
    // check given password to match database hashed password
    if (!password_verify($pwd, $dbPwdHash)) {
        $_SESSION['login']['name_email'] = $name_email;
        $_SESSION['errors']['pwd'] = 'Password does not match';
        // redirect back to login page
        header('Location: ../pages/login.page.php');
        exit;
    }
    

// SUCCESS LOGIN CASE SCENARIO
  // store user in session variable
    $_SESSION['user_name'] = $getUser['user_name'];
    $_SESSION['user_id'] = $getUser['user_id'];

    // redirect to collection page
    header('Location: ../processes/collection.proc.php');
    exit;
