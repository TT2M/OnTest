<?php
session_start();
$_SESSION['auth'] = null;
$_SESSION['flash'] = 'Успешный выход из аккаунта';
header('Location: index.php');
die();
?>