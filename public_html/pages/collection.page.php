<?php
session_start();
include '../templates/header.temp.php';
include '../templates/welcome.temp.php';
include '../templates/message_box.temp.php';

// CHECK FOR LOGGED IN USER
if (!isset($_SESSION['user_name'])) {
    header('Location: ../pages/login.page.php');
    exit;
}

// CHECK if the user coming from somewhere else than collection.proc
if (!isset($_SESSION['collection'])) {
    // redirect back to collection process
    header('Location: ../processes/collection.proc.php');
    exit;
}


// it comes from collection.proc.php
$taskListCollectionToJs = $_SESSION['collection'];
$collectionCount = $_SESSION['collection-count']

?>
<script>
  // define an object to hold the takslist collection data passed by PHP
  const taskListCollectionFromPHP = JSON.parse('<?php echo $taskListCollectionToJs ?>');
</script>

<div class="main-wrapper content-center">
  <div class="form__cont">
    <div>
        <h1 id="collection__title" class="collection__title">Task List Collection</h1>

      <?php if ($collectionCount): ?>
        <p class="collection__sub-title">Open one to add tasks to it.</p>
      <?php endif; ?>

    </div>
    <form id="collection__form" class="collection__form" action="../processes/collection.proc.php" method="POST">
      <div id="collection-container" class="collection-container">
        <!-- JavaScript fill up this place -->
      </div>
      <?php include '../templates/add_new.temp.php'; ?>
      <input id="hidden-task_list_new" type="hidden" name="task_list_new"  value="">
      <input id="hidden-task_list_open" type="hidden" name="task_list_open"  value="">
      <input id="hidden-task_list_delete" type="hidden" name="task_list_delete"  value="">
    </form>
  </div>
</div>

<!-- JavaScript -->
<script src="../js/collection.js"></script>

<?php
include '../templates/footer.temp.php';
unset($_SESSION['messages']);
unset($_SESSION['collection']);
unset($_SESSION['collection-count']);
