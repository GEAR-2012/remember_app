<?php
include 'classes/Lists.php';

// get a specific tasklist from database based on userid & tasklist id
$userId = $_SESSION['user_id'];
$taskListId = $_SESSION['task_list_open'];
$dBLists = new Lists();
$taskList = $dBLists->getOneTaskList($userId, $taskListId);
unset($dBLists);


if (isset($_POST['back'])) {
    // update tasklist in the database
    // get the updated tasklist
    $taskList = json_decode($_POST['task_list'], true);
    // define the necessary variables
    $userId = $_SESSION['user_id'];
    $taskListId = $_SESSION['task_list_open'];
    $taskListName = $taskList['task_list_name'];
    $taskListList = $taskList['task_list'];
    // update tasklist in the database
    $dBLists = new Lists();
    $dBLists->updateOneTaskList($userId, $taskListId, $taskListName, $taskListList);
    unset($dBLists);

    // before back to the collections page unset the task list session variable
    unset($_SESSION['task_list']);
    // before back to the collections page unset the selected task list id session variable
    unset($_SESSION['task_list_open']);
    header('Location: index.php');
}

// conver data to passing down to JavaScript
$taskListToJS = json_encode($taskList);

?>
<script>
  // getting data from PHP
  // and passing further down to JavaScirpt
  const taskListFromPHP = JSON.parse('<?php echo $taskListToJS;?>');
</script>
<div class="main-wrapper content-center">
  <div class="form__cont">
    <h1 id="task-list__title" class="task-list__title" oninput="editableEditHandler(this)" title='click to edit the tasklist name' contenteditable></h1>
    <form class="task-list__form" id="task-list__form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">

      <div id="task-list__container" class="task-list__container">
        <!-- JavaScript fill this container -->
      </div>

      <?php include 'templates/add_new_group.php'; ?>

      <div class="task-list__buttons">
        <i id="reset-task-list" title="reset the entire task list"  class="fas fa-undo-alt"></i>
        <i id="delete-task-list" title="delte the entire task list"  class="fas fa-trash-alt"></i>
      </div>

      <input class="button--medium" type="submit" name="back" value="Save & Back" title="save this task list & go back to your task list collection">
      <input id="hidden-data-holder" type="hidden" name="task_list"  value="">

    </form>

  </div>
</div>


<!-- JavaScript -->
<script src="js/task_list.js"></script>
