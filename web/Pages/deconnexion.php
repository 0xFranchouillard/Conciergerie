<?php
session_start();
$lang = $_SESSION['lang'];
$_SESSION = [];

$_SESSION['lang'] = $lang;
header('Location: ../index.php');
exit;
?>