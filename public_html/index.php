<?php
session_start();

if (!isset($_SESSION['user_name'])) {
    header('Location: pages/login.page.php');
    exit;
} else {
    header('Location: processes/collection.proc.php');
}
