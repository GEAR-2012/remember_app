<?php
session_start();

// CHECK FOR LOGGED IN USER
if (!isset($_SESSION['user_name'])) {
    header('Location: ../pages/login.page.php');
    exit;
}

// CHECK if this file requested not by clicking logout button
if (!isset($_POST['submit'])) {
    // redirect back to collection process
    header('Location: ../processes/collection.proc.php');
    exit;
}

if (empty($_POST['submit'])) {
    // redirect back to collection process
    header('Location: ../processes/collection.proc.php');
    exit;
}

if ($_POST['submit'] === 'logout') {
    session_unset();
    session_destroy();
    header('Location: ../pages/login.page.php');
    exit;
}
