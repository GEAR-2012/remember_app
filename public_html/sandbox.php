<?php
include 'classes/Lists.php';

$userId = 6;
$taskListId = 24;

$dbLists = new Lists();

// print_r($dbLists->getUserCollection($userId));

// print_r($dbLists->getOneTaskList($userId, $taskListId));

$result = $dbLists->deleteOneTaskList($userId, $taskListId);

if ($result === 0) {
    echo '0';
} elseif ($result === -1) {
    echo '-1';
} elseif ($result === 1) {
    echo '1';
} elseif ($result === false) {
    echo 'false';
} elseif ($result === true) {
    echo 'true';
} else {
    echo 'somethig else';
    echo $result;
}
