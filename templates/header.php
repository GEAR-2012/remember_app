<?php
session_start();

  $user_name = '';

  if (isset($_SESSION['user_name'])) {
      $user_name = $_SESSION['user_name'];
  }

  if (isset($_POST['logout'])) {
      session_unset();
      session_destroy();
      header('Location: login.php');
  }

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ToDo List Pro</title>
  <!-- Fontawesome -->
  <script src="https://kit.fontawesome.com/f3fb50eddd.js" crossorigin="anonymous" defer></script>
  <!-- CSS -->
  <link rel="stylesheet" href="css/settings.css">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="css/welcome.css">
  <link rel="stylesheet" href="css/message_box.css">
  <link rel="stylesheet" href="css/main.css">
  <link rel="stylesheet" href="css/add_new_group.css">
  <link rel="stylesheet" href="css/task_list_collection.css">
  <link rel="stylesheet" href="css/task_list.css">
</head>
<body>
  <header>
    <a href="index.php" class="brand">Remember</a>
    <?php
      if ($user_name) {
          ?>

        <form class='form__body' action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method='POST'>
          <input type="hidden" name="logout">
          <input type='submit' name='submit' value='Logout' class="button--small">
        </form>
        
        <?php
      }
    ?>
  </header>