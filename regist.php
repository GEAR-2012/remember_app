<?php
include 'templates/header.php';
include 'classes/Validate.php';
include 'classes/UserAuth.php';

// define empty variables
$name = $email = $pwd_1 = $pwd_2 = '';
$dbErr = $nameErr = $emailErr = $pwd_1Err = $pwd_2Err = '';

if (isset($_POST['submit'])) {
    // INPUT VALIDATION
    
    // name
    if (empty($_POST['name'])) {
        $nameErr = 'Username is required';
    } else {
        $name = $Validate->test_input($_POST['name']);
        $nameErr =  $Validate->userName($name);
    }

    // email
    if (empty($_POST['email'])) {
        $emailErr = 'Email is required';
    } else {
        $email = $Validate->test_input($_POST['email']);
        $emailErr =  $Validate->email($email);
    }
    // password 1
    if (empty($_POST['pwd_1'])) {
        $pwd_1Err =  'Password is required';
    } else {
        $pwd_1 = $Validate->test_input($_POST['pwd_1']);
        $pwd_1Err = $Validate->pwd($pwd_1);
    }
    // password 2
    if (empty($_POST['pwd_2'])) {
        $pwd_2Err =  'Password is required';
    } else {
        $pwd_2 = $Validate->test_input($_POST['pwd_2']);
        $pwd_2Err = $Validate->pwd($pwd_2);
    }
    // check for passwords match
    if ($pwd_1 !== $pwd_2) {
        $pwd_2Err = 'The two passwords should match';
    }

    // PUT USER INTO DB
    // if no input errors, put user into database if not exists jet
    if (empty($nameErr) && empty($emailErr) && empty($pwd_1Err) && empty($pwd_2Err)) {
        $userAuth = new UserAuth();
        // check if username is already exists in the database
        $isUserNameExists = $userAuth->isUsernameExists($name);
        if ($isUserNameExists === false) {
            // put new user into database...
            $inserNewUser = $userAuth->insertNewUser($name, $email, $pwd_1);
            if ($inserNewUser === true) {
                // GET USER FROM DB
                // if no insertion errors, get user data back from database
                // get user from database into the '$user' variable
                $user = $userAuth->getUser($name);
                // store user in session variable
                $_SESSION['user_name'] = $user['user_name'];
                $_SESSION['user_id'] = $user['id'];
                // REDIRECT TO INDEX PAGE
                header('Location: index.php');
            } elseif ($inserNewUser === false) {
                $dbErr = 'New User registration was unsuccessful';
            } else {
                $dbErr = $inserNewUser;
            }
        } elseif ($isUserNameExists === true) {
            $nameErr = "This Username is already taken";
        } else {
            $dbErr = $isUserNameExists;
        }
    }
}


?>

<div class="main-wrapper content-center">
  <div class="form__cont">
    <h2 class="form__title">Register</h2>
        <?php
          if ($dbErr) {
              echo "<span class='error'>$dbErr</span>";
          }
        ?>
    <p class="form__msg">Lets get<br />you on board</p>
    <form class="form__body" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
      <div class="form__input-group">
        <?php
          $valueName = $name ?? '';
          echo "<input type='text' name='name' value='$valueName' placeholder='Username'>";
          echo "<span class='error'>$nameErr</span>";
        ?>
      </div>  
      <div class="form__input-group">
        <?php
          $valueEmail = $email ?? '';
          echo "<input type='email' name='email' value='$valueEmail' placeholder='Email'>";
          echo "<span class='error'>$emailErr</span>";
        ?>
      </div>
      <div class="form__input-group">
        <?php
          echo "<input type='password' name='pwd_1' placeholder='Password'>";
          echo "<span class='error'>$pwd_1Err</span>";
        ?>
      </div>  
      <div class="form__input-group">
        <?php
          echo "<input type='password' name='pwd_2' placeholder='Password again'>";
          echo "<span class='error'>$pwd_2Err</span>";
        ?>
      </div>  
      <input type="submit" value="Register" name="submit" class="button">
    </form>
    <div class="form__ques">
      <p class="form__ques-text">Already have an account?</p>
      <a href="login.php" class="form__ques-link">Sing In</a>
    </div>
  </div>
</div>

<?php
include 'templates/footer.php';
