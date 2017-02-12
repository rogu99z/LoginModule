<?php

	session_start();
	require_once(dirname(__FILE__)."/core/root.php");
	$user = new User();

	if(!$user->logged_in){
		header("Location: login.php");
		exit;
	}

	$id = $_GET["id"];
	$userPersisted = $user->getSingleUser($id);

	if( !$userPersisted ){
		die("The user does not exist...");
	}

	if(isset($_POST["password"])){
		if(empty($_POST["password"])){
			$error = "You have insert some value";
		}else{
			$user->setPassword($_POST["password"], $userPersisted["id"]);
			header("Location: users.php");
			exit;
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Change password</title>
	  	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	  	<link rel="stylesheet" type="text/css" href="common_styles.css">
	</head>
	<body>

		<h1>User: <?php echo $userPersisted["username"]; ?></h1>
		<?php if( isset($error) ): ?>
			<p>
				<?php echo $error; ?>
			</p>
		<?php endif; ?>

		<form method="post" action="">
			<p>
				<label>New password</label>
				<h5>&zwnj;</h5>
				<input type="text" name="password"/>
			</p>
			<h5>&zwnj;</h5>
			<p>
				<input type="submit" name="submit" value="Change" />
			</p>
		</form>

	</body>
</html>