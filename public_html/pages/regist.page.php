<?php
session_start();

// CHECK FOR LOGGED IN USER
if (isset($_SESSION['user_name'])) {
    header('Location: ../processes/collection.proc.php');
    exit;
}

// define empty variables
$dbErr = $nameErr = $emailErr = $pwd_1Err = $pwd_2Err = '';
$name = $email = $pwd_1 = $pwd_2 = '';

// get possible errors from session variable
if (isset($_SESSION['errors'])) {
    $dbErr = $_SESSION['errors']['db'];
    $nameErr = $_SESSION['errors']['name'];
    $emailErr = $_SESSION['errors']['email'];
    $pwd_1Err = $_SESSION['errors']['pwd_1'];
    $pwd_2Err = $_SESSION['errors']['pwd_2'];
}
  
// get possible login data from session variable
if (isset($_SESSION['regist'])) {
    $name = $_SESSION['regist']['name'];
    $email = $_SESSION['regist']['email'];
}

include '../templates/header.temp.php';
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
    <form class="form__body" action="../processes/regist.proc.php" method="POST">
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
      <button type="submit" name="submit" class="button">Register</button>
    </form>
    <div class="form__ques">
      <p class="form__ques-text">Already have an account?</p>
      <a href="login.page.php" class="form__ques-link">Sing In</a>
    </div>
  </div>
</div>

<?php
unset($_SESSION['errors']);
unset($_SESSION['regist']);
include '../templates/footer.temp.php';
