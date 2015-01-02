<?php
header("Content-Type: text/html; charset=utf-8");
	require('config.php');
	
	session_start();
	session_destroy();
	require("common.php");

	unset($_SESSION['uname']);
	header("Location: index.php");
	die("Redirecting to: index.php");
?>
