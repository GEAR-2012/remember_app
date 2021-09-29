<?php
session_start();
include '../classes/Lists.php';

// CHECK FOR LOGGED IN USER
if (!isset($_SESSION['user_name'])) {
    header('Location: ../pages/login.page.php');
    exit;
}

// define a session variable to hold possible errors
$_SESSION['messages'] = [];

// get the logged users tasklist collection from database
$dBLists = new Lists();
$taskListCollection = $dBLists->getUserCollection($_SESSION['user_id']);
unset($dBLists);

// when the form request to open a tasklist
if (isset($_POST['task_list_open'])) {
    if (!empty($_POST['task_list_open'])) {
        // get the selected tasklist id
        $_SESSION['task_list_open'] = htmlspecialchars($_POST['task_list_open']);
        // redirect to index page
        header('Location: ../processes/tasklist.proc.php');
        exit;
    }
}

// when the form request to create a new taklist
if (isset($_POST['task_list_new'])) {
    if (!empty($_POST['task_list_new'])) {
        // get the new tasklist name
        $newTaskListName = htmlspecialchars($_POST['task_list_new']);
        // create a new tasklist into the database
        // and display the new taklist collection
        $dBLists = new Lists();
        if ($dBLists->createNewTasklist($_SESSION['user_id'], $newTaskListName)) {
            $taskListCollection = $dBLists->getUserCollection($_SESSION['user_id']);
        } else {
            $_SESSION['messages'][] = "try again later";
        }
        unset($dBLists);
    }
}

// when the form request to delete a tasklist
if (isset($_POST['task_list_delete'])) {
    if (!empty($_POST['task_list_delete'])) {
        // get the tasklist id to delete from database
        $taskListId = htmlspecialchars($_POST['task_list_delete']);
        $dBLists = new Lists();
        // try to delete the selected tasklist from the database
        $isDeleted = $dBLists->deleteOneTaskList($_SESSION['user_id'], $taskListId);
        switch ($isDeleted) {
            case -1:
                // check if the tasklist empty or not exists
                $_SESSION['messages'][] = 'The selected tasklist not empty';
                break;
            case 0:
                // check for other deleting issues
                $_SESSION['messages'][] = 'Nothing to delete';
                break;
            case 1:
                // check for success deleting
                $_SESSION['messages'][] = 'Tasklist deleted';
                // redisplay the tasklist collection
                $taskListCollection = $dBLists->getUserCollection($_SESSION['user_id']);
                break;
            default:
                // in any other case
                $_SESSION['messages'][] = 'Try again later';
        }
        unset($dBLists);
    }
}

// encode the tasklist collection to json to pass JavaScript
$taskListCollectionToJs = json_encode($taskListCollection);

$_SESSION['collection'] = $taskListCollectionToJs;
$_SESSION['collection-count'] = count($taskListCollection);

// redirect back to collection page
header('Location: ../pages/collection.page.php');
exit;
