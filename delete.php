<?php
	session_start();
	require_once(dirname(__FILE__)."/core/root.php");
	$user = new User();

	if(!$user->logged_in){
		header("Location: login.php");
		exit;
	}

	$id = $_GET["id"];
	$user->delete($id);
	header("Location: users.php");
?>