<?php
session_start();

// CHECK FOR LOGGED IN USER
if (isset($_SESSION['user_name'])) {
    header('Location: ../processes/collection.proc.php');
    exit;
}

// define empty variables
$dbErr = $name_emailErr = $pwdErr = '';
$name_email = '';

// get possible errors from session variable
if (isset($_SESSION['errors'])) {
    $dbErr = $_SESSION['errors']['db'];
    $name_emailErr = $_SESSION['errors']['name_email'];
    $pwdErr = $_SESSION['errors']['pwd'];
}
  
// get possible login data from session variable
if (isset($_SESSION['login'])) {
    $name_email = $_SESSION['login']['name_email'];
}

include '../templates/header.temp.php';
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
    <form class="form__body" action="../processes/login.proc.php" method="POST">
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
      <button type="submit" name="submit" class="button">Login</button>
    </form>
    <div class="form__ques">
      <p class="form__ques-text">Don't have an account?</p>
      <a href="regist.page.php" class="form__ques-link">Sing Up</a>
    </div>
  </div>
</div>

<?php
unset($_SESSION['errors']);
unset($_SESSION['login']);
include '../templates/footer.temp.php';
