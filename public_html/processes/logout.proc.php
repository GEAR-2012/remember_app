<?php
session_start();

if (isset($_POST['submit'])) {
    if (!empty($_POST['submit'])) {
        if ($_POST['submit'] === 'logout') {
            session_unset();
            session_destroy();
            header('Location: ../pages/login.page.php');
            exit;
        }
    }
}
