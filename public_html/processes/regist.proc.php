<?php
session_start();
include '../classes/Validate.php';
include '../classes/UserAuth.php';

// CHECK FOR FORM SUBMISSION
if (!isset($_POST['submit'])) {
    // redirect back to regist page
    header('Location: ../pages/regist.page.php');
    exit;
}

// define a session variable to hold
  // regist data to redisplay in regist form
  $_SESSION['regist']['name'] = '';
  $_SESSION['regist']['email'] = '';
  // & possible errors to display in regist form
  $_SESSION['errors']['db'] = '';
  $_SESSION['errors']['name'] = '';
  $_SESSION['errors']['email'] = '';
  $_SESSION['errors']['pwd_1'] = '';
  $_SESSION['errors']['pwd_2'] = '';

// INPUT VALIDATION
  // CHECK FOR EMPTY FIELDS
    // user name & email
    if (empty($_POST['name']) && empty($_POST['email'])) {
        $_SESSION['errors']['name'] = 'Username is required';
        $_SESSION['errors']['email'] = 'Email is required';
        // redirect back to regist page
        header('Location: ../pages/regist.page.php');
        exit;
    }
    
    // user name
    if (empty($_POST['name'])) {
        $validate = new Validate();
        $email = $validate->cleanInput($_POST['email']);
        unset($validate);
        $_SESSION['regist']['email'] = $email;
        $_SESSION['errors']['name'] = 'Username is required';
        // redirect back to regist page
        header('Location: ../pages/regist.page.php');
        exit;
    }
    // email
    if (empty($_POST['email']) && !empty($_POST['name'])) {
        $validate = new Validate();
        $name = $validate->cleanInput($_POST['name']);
        unset($validate);
        $_SESSION['regist']['name'] = $name;
        $_SESSION['errors']['email'] = 'Email is required';
        // redirect back to regist page
        header('Location: ../pages/regist.page.php');
        exit;
    }
    $validate = new Validate();
    $name = $validate->cleanInput($_POST['name']);
    $email = $validate->cleanInput($_POST['email']);
    unset($validate);
    
    // password 1
    if (empty($_POST['pwd_1'])) {
        $_SESSION['regist']['name'] = $name;
        $_SESSION['regist']['email'] = $email;
        $_SESSION['errors']['pwd_1'] = 'Password is required';
        // redirect back to regist page
        header('Location: ../pages/regist.page.php');
        exit;
    }
    $validate = new Validate();
    $pwd_1 = $validate->cleanInput($_POST['pwd_1']);
    $isPasswordCorrect = $validate->pwd($pwd_1);
    $_SESSION['errors']['pwd_1'] = $isPasswordCorrect;
    unset($validate);
    
    // password 2
    if (empty($_POST['pwd_2'])) {
        $_SESSION['regist']['name'] = $name;
        $_SESSION['regist']['email'] = $email;
        $_SESSION['errors']['pwd_2'] = 'Password is required';
        // redirect back to regist page
        header('Location: ../pages/regist.page.php');
        exit;
    }
    $validate = new Validate();
    $pwd_2 = $validate->cleanInput($_POST['pwd_2']);
    unset($validate);

    // check for passwords match
    if ($pwd_1 !== $pwd_2) {
        $_SESSION['regist']['name'] = $name;
        $_SESSION['regist']['email'] = $email;
        $_SESSION['errors']['pwd_2'] = 'The two passwords should match';
        // redirect back to regist page
        header('Location: ../pages/regist.page.php');
        exit;
    }

// PUT USER INTO DB
  // if no input errors, put user into database if not exists jet
      // check if username is already exists in the database
      $dbUserAuth = new UserAuth();
      $isUserNameExists = $dbUserAuth->isUsernameExists($name);
      unset($dbUserAuth);
      if ($isUserNameExists === true) {
          $_SESSION['regist']['name'] = $name;
          $_SESSION['regist']['email'] = $email;
          $_SESSION['errors']['name'] = 'This Username is already taken';
          // redirect back to regist page
          header('Location: ../pages/regist.page.php');
          exit;
      }
      // ...if not exists yet put new user into database...
      $dbUserAuth = new UserAuth();
      $inserNewUser = $dbUserAuth->insertNewUser($name, $email, $pwd_1);
      unset($dbUserAuth);
      if ($inserNewUser === false) {
          $_SESSION['regist']['name'] = $name;
          $_SESSION['regist']['email'] = $email;
          $_SESSION['errors']['db'] = 'New User registration was unsuccessful';
          // redirect back to regist page
          header('Location: ../pages/regist.page.php');
          exit;
      }
      
// SUCCESS REGISTRATION CASE SCENARIO
  // GET USER FROM DB
    // if no insertion errors, get user data back from database
      // get user from database into the '$getUser' variable
      $dbUserAuth = new UserAuth();
      $getUser = $dbUserAuth->getUser($name);
      unset($dbUserAuth);
      // store user in session variable
      $_SESSION['user_name'] = $getUser['user_name'];
      $_SESSION['user_id'] = $getUser['user_id'];
        // store user in cookies
        $expireDate = time() + 60 * 60 * 24 * 10;
        setcookie('user_name', $getUser['user_name'], $expireDate, '/');
        setcookie('user_id', $getUser['user_id'], $expireDate, '/');

      // redirect to collection page
      header('Location: ../processes/collection.proc.php');
      exit;
