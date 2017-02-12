<?php
	session_start();
	require_once(dirname(__FILE__)."/core/root.php");
	$user = new User();
	$user->logout();
	header("Location: login.php");
?>