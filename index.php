<?php
  include 'templates/header.php';


  if (!isset($_SESSION['user_name'])) {
      header('Location: login.php');
  }

  include 'templates/welcome.php';

  if (isset($_SESSION['task_list_open'])) {
      include 'templates/task_list.php';
  } else {
      include 'templates/task_list_collection.php';
  }

include 'templates/footer.php';
