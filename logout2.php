<?php
session_start();
if (!isset($_SESSION['tuvastamine'])) {
    header('Location: ab_login2.php');
    exit();
}

if(isSet($_POST['logout'])){
    session_destroy();
    header('Location: ab_login2.php');
    exit();
}