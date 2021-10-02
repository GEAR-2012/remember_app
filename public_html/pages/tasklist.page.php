<?php
session_start();
include '../classes/Lists.php';

// CHECK FOR LOGGED IN USER
if (!isset($_SESSION['user_name'])) {
    header('Location: ../pages/login.page.php');
    exit;
}

// CHECK if the user coming from somewhere else than collection page
if (!isset($_SESSION['tasklist'])) {
    // redirect back to collection process
    header('Location: ../processes/collection.proc.php');
    exit;
}

include '../templates/header.temp.php';
include '../templates/welcome.temp.php';


$taskListToJS = $_SESSION['tasklist'];

?>
<script>
  // getting data from PHP
  // and passing further down to JavaScirpt
  // const tasklistString = <?php echo $taskListToJS ?>;
  // console.log(tasklistString);
  const taskListFromPHP = <?php echo $taskListToJS ?>;

  // console.log(taskListFromPHP);
</script>

<div class="main-wrapper content-center">
  <div class="form__cont">

    <div class="form__header">
      <h1 id="task-list__title" class="task-list__title" oninput="editableEditHandler(this)" title='click to edit the tasklist name' contenteditable></h1>
      <i id="task-list__menu-btn" class="fas fa-ellipsis-v task-list__menu-btn"></i>
    </div>
      <p id="form__message" class="form__message hide">!!! Filtered list !!!</p>


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

  <div id="modal-menu" class="modal-menu content-center hide">
    <div class="modal-menu__content">
      <div class="modal-menu__item">
        <label for="search" class="search-box__label">
          <i id="search-icon" class="fas fa-search"></i>
        </label>
        <input type="text" id="search" class="search-box" placeholder="Search...">
      </div>
      <div class="modal-menu__item">
        <input type="checkbox" name="reverse" id="sort-reverse" class="sort-box">
        <label for="sort-reverse"  class="sort-label">Sort reverse</label>
      </div>
      <div class="modal-menu__item">
        <input type="radio" name="sort" id="sort-alpha" class="sort-box">
        <label for="sort-alpha" class="sort-label">Sort alphabetical</label>
      </div>
      <div class="modal-menu__item">
        <input type="radio" name="sort" id="sort-created"  class="sort-box">
        <label for="sort-created" class="sort-label">Sort time added</label>
      </div>
    </div>
  </div>

</div>

<!-- JavaScript -->
<script src="../js/tasklist.js"></script>

<?php
include '../templates/footer.temp.php';
