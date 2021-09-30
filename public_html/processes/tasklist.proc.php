<?php
session_start();
include '../classes/Lists.php';

// CHECK FOR LOGGED IN USER
if (!isset($_SESSION['user_name'])) {
    header('Location: ../pages/login.page.php');
    exit;
}

// CHECK if the user coming from somewhere else than collection page
if (!isset($_SESSION['task_list_open'])) {
    // redirect back to collection process
    header('Location: ../processes/collection.proc.php');
    exit;
}

// when save & back clicked
// so if this file opened by tasklist.page.php
if (isset($_POST['back'])) {
    // update tasklist in the database
    // get the updated tasklist
    $taskList = json_decode($_POST['back'], true);
    // define the necessary variables
    $userId = $_SESSION['user_id'];
    $taskListId = $_SESSION['task_list_open'];
    $taskListName = $taskList['tasklist_name'];
    $taskListList = $taskList['tasklist'];
    // update tasklist in the database
    $dBLists = new Lists();
    $dBLists->updateOneTaskList($userId, $taskListId, $taskListName, $taskListList);
    unset($dBLists);

    // redirect back to collection process
    header('Location: ../processes/collection.proc.php');
    unset($_SESSION['task_list_open']);
    exit;
}

// if this file opened by collection.proc.php by click on the tasklist name
// get a specific tasklist from database based on userid & tasklist id
$userId = $_SESSION['user_id'];
$taskListId = $_SESSION['task_list_open'];
$dBLists = new Lists();
$taskList = $dBLists->getOneTaskList($userId, $taskListId);
unset($dBLists);

// conver data to passing down to JavaScript
$taskListToJS = json_encode($taskList);
$_SESSION['tasklist'] = $taskListToJS;
header('Location: ../pages/tasklist.page.php');
exit;
