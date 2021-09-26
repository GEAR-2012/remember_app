<?php
include 'classes/Lists.php';
// define a session variable to hold possible errors
$_SESSION['messages'] = [];

// get all post data into session variable & redirect page to itself
// to get the $_POST variable empty
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['postdata'] = $_POST;
    unset($_POST);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

// get the logged users tasklist collection from database
$dBLists = new Lists();
$taskListCollection = $dBLists->getUsersTaskLists($_SESSION['user_id']);
unset($dBLists);

// when the form request to open a tasklist
if (isset($_SESSION['postdata']['task_list_open'])) {
    if (!empty($_SESSION['postdata']['task_list_open'])) {
        // get the selected tasklist id
        $_SESSION['task_list_open'] = htmlspecialchars($_SESSION['postdata']['task_list_open']);
        unset($_SESSION['postdata']['task_list_open']);
        // redirect back to index page
        header('Location: index.php');
        exit;
    }
}

// when the form request to create a new taklist
if (isset($_SESSION['postdata']['task_list_new'])) {
    if (!empty($_SESSION['postdata']['task_list_new'])) {
        // get the new tasklist name
        $newTaskListName = htmlspecialchars($_SESSION['postdata']['task_list_new']);
        unset($_SESSION['postdata']['task_list_new']);
        // create a new tasklist into the database
        // and display the new taklist collection
        $dBLists = new Lists();
        if ($dBLists->createNewTasklist($_SESSION['user_id'], $newTaskListName)) {
            $taskListCollection = $dBLists->getUsersTaskLists($_SESSION['user_id']);
        } else {
            $_SESSION['messages'][] = "try again later";
        }
        unset($dBLists);
    }
}

// when the form request to delete a tasklist
if (isset($_SESSION['postdata']['task_list_delete'])) {
    if (!empty($_SESSION['postdata']['task_list_delete'])) {
        // get the tasklist id to delete from database
        $taskListId = htmlspecialchars($_SESSION['postdata']['task_list_delete']);
        unset($_SESSION['postdata']['task_list_delete']);

        $dBLists = new Lists();
        // check the list of the selected tasklist if empty
        $userTaskList = $dBLists->getOneTaskList($_SESSION['user_id'], $taskListId);
        $userTaskListList = $userTaskList['task_list'];
        // if the tasklist's list not exist or empty
        if (!$userTaskListList) {
            // delete the selected tasklist from the database
            // and redisplay the tasklist collection
            if ($dBLists->deleteOneTaskList($_SESSION['user_id'], $taskListId)) {
                $taskListCollection = $dBLists->getUsersTaskLists($_SESSION['user_id']);
                $_SESSION['messages'][] = 'Tasklist deleted';
            } else {
                $_SESSION['messages'][] = 'Try again later';
            }
        } else {
            $_SESSION['messages'][] = 'The selected tasklist not empty';
        }

        unset($dBLists);
    }
}

// encode the tasklist collection to json to pass JavaScript
$taskListCollectionToJs = json_encode($taskListCollection);

include 'templates/message_box.php';
unset($_SESSION['messages']);

?>
<script>
  // define an object to hold the takslist collection data passed by PHP
  const taskListCollectionFromPHP = JSON.parse('<?php echo $taskListCollectionToJs ?>');
</script>

<div class="main-wrapper content-center">
  <div class="form__cont">
    <h1 id="collection__title" class="collection__title">Task List Collection</h1>
    <form id="collection__form" class="collection__form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
      <div id="collection-container" class="collection-container">
        <!-- JavaScript fill up this place -->
      </div>

      <?php include 'templates/add_new_group.php'; ?>

      <input id="hidden-task_list_new" type="hidden" name="task_list_new"  value="">
      <input id="hidden-task_list_open" type="hidden" name="task_list_open"  value="">
      <input id="hidden-task_list_delete" type="hidden" name="task_list_delete"  value="">

    </form>
  </div>
</div>

<!-- JavaScript -->
<script src="js/task_list_collection.js"></script>
