<?php
include 'templates/header.php';
include 'classes/Validate.php';
include 'classes/UserAuth.php';

// define empty variables
$name_email = $pwd = '';
$dbErr = $name_emailErr = $pwdErr = '';

if (isset($_POST['submit'])) {
    // INPUT VALIDATION
    
    // user name or email
    if (empty($_POST['name'])) {
        $name_emailErr = 'User Name or Email is required';
    } else {
        $name_email = $Validate->test_input($_POST['name']);
    }

    // password
    if (empty($_POST['pwd'])) {
        $pwdErr =  'Password is required';
    } else {
        $pwd = $Validate->test_input($_POST['pwd']);
    }

    // GET USER FROM DB
    // if no input errors, get user from database if exist
    if (empty($name_emailErr) && empty($pwdErr)) {

        // get user from database into the '$user' variable
        $userAuth = new UserAuth();
        $getUser = $userAuth->getUser($name_email);
      
        if ($getUser !== false) {
            $dbPwdHash = $getUser['user_pwd'];
            // check given password to match database hashed password
            if (password_verify($pwd, $dbPwdHash)) {
                // store user in session variable
                $_SESSION['user_name'] = $getUser['user_name'];
                $_SESSION['user_id'] = $getUser['id'];
    
                // redirect to index page
                header('Location: index.php');
            } else {
                $pwdErr = 'Password does not match';
            }
        } elseif ($getUser === false) {
            $name_emailErr = 'User Name or Email not found';
        } else {
            $dbErr = $getUser;
        }
    }
}

?>

<div class="main-wrapper content-center">
  <div class="form__cont">
    <h2 class="form__title">Login</h2>
        <?php
          if ($dbErr) {
              echo "<span class='error'>$dbErr</span>";
          }
        ?>
    <p class="form__msg">Welcome back,<br />please login to<br />Your account</p>
    <form class="form__body" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
      <div class="form__input-group">
        <?php
          $valueName = $name_email ?? '';
          echo "<input type='text' name='name' value='$valueName' placeholder='User Name or Email'>";
          echo "<span class='error'>$name_emailErr</span>";
        ?>
      </div>  
      <div class="form__input-group">
        <?php
          echo "<input type='password' name='pwd' placeholder='Password'>";
          echo "<span class='error'>$pwdErr</span>";
        ?>
      </div>  
      <input type="submit" value="Login" name="submit" class="button">
    </form>
    <div class="form__ques">
      <p class="form__ques-text">Don't have an account?</p>
      <a href="regist.php" class="form__ques-link">Sing Up</a>
    </div>
  </div>
</div>

<?php
include 'templates/footer.php';
