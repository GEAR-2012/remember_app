<?php
session_start();

// no session variable & no cookie
if (!isset($_SESSION['user_name']) &&  !isset($_COOKIE['user_name'])) {
    header('Location: pages/login.page.php');
    exit;
}

// if cookie username & cookie id
if (isset($_COOKIE['user_name']) && isset($_COOKIE['user_id'])) {
    $_SESSION['user_name'] = $_COOKIE['user_name'];
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    header('Location: processes/collection.proc.php');
    exit;
}
