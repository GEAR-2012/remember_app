<?php
session_start();
include '../classes/Lists.php';

// CHECK FOR LOGGED IN USER
if (!isset($_SESSION['user_name'])) {
    header('Location: ../pages/login.page.php');
    exit;
}

include '../templates/header.temp.php';
include '../templates/welcome.temp.php';


$taskListToJS = $_SESSION['tasklist'];
?>
<script>
  // getting data from PHP
  // and passing further down to JavaScirpt
  const taskListFromPHP = JSON.parse('<?php echo $taskListToJS;?>');
</script>

<div class="main-wrapper content-center">
  <div class="form__cont">
    <h1 id="task-list__title" class="task-list__title" oninput="editableEditHandler(this)" title='click to edit the tasklist name' contenteditable></h1>
    <form class="task-list__form" id="task-list__form" action="../processes/tasklist.proc.php" method="POST">
      <div id="task-list__container" class="task-list__container">
        <!-- JavaScript fill this container -->
      </div>
      <?php include '../templates/add_new.temp.php'; ?>
      <div class="task-list__buttons">
        <i id="reset-task-list" title="reset the entire task list"  class="fas fa-undo-alt"></i>
        <i id="delete-task-list" title="delte the entire task list"  class="fas fa-trash-alt"></i>
      </div>
      <button id="back-btn" class="button--medium" type="submit" name="back" value="" title="save this task list & go back to your task list collection">Save & Back</button>
    </form>
  </div>
</div>

<!-- JavaScript -->
<script src="../js/tasklist.js"></script>

<?php
include '../templates/footer.temp.php';
