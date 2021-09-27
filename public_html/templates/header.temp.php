<?php

$user_name = '';

// check for logged in user
if (isset($_SESSION['user_name'])) {
    $user_name = $_SESSION['user_name'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Remember App</title>
  <!-- Fontawesome -->
  <script src="https://kit.fontawesome.com/f3fb50eddd.js" crossorigin="anonymous" defer></script>
  <!-- CSS -->
  <link rel="stylesheet" href="../css/settings.css">
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/footer.css">
  <link rel="stylesheet" href="../css/welcome.css">
  <link rel="stylesheet" href="../css/message_box.css">
  <link rel="stylesheet" href="../css/main.css">
  <link rel="stylesheet" href="../css/add_new_group.css">
  <link rel="stylesheet" href="../css/task_list_collection.css">
  <link rel="stylesheet" href="../css/task_list.css">
</head>
<body>
  <header>
    <a href="../index.php" class="brand">Remember</a>
    <?php
    // display logout button if user logged in
      if ($user_name) {
          ?>
        <form class='form__body' action="../processes/logout.proc.php" method='POST'>
          <button type='submit' name='submit' value='logout' class="button--small">Logout</button>
        </form>
        <?php
      }
    ?>
  </header>