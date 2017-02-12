<?php
	session_start();
	require_once(dirname(__FILE__)."/core/root.php");
	$user = new User();
	
	if(empty($_POST["username"]) || empty($_POST["password"])){
		$error = "You have to complete both fields.";
	}else{
		$res = $user->create($_POST["username"], $_POST["password"]);
		if(!$res){
			$error = "Username already registered.";
		}else{
			header("Location: users.php");
			exit;
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>User registration</title>
	  	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" type="text/css" href="common_styles.css">
	</head>
	<body>

		<h1>Register user</h1>
		<?php if( isset($error) ): ?>
		<p>
			<?php echo $error; ?>
			<h5>&zwnj;</h5>
		</p>
		<?php endif; ?>

		<form method="post" action="">
			<p>
				<label for="username">Username</label>
				<br/>
				<input type="text" name="username" id="username" />
				<h5>&zwnj;</h5>
			</p>
			<p>
				<label for="password">Password</label>
				<br/>
				<input type="text" name="password" id="password" />
				<h5>&zwnj;</h5>
			</p>
			<p>
				<input type="submit" name="submit" value="Register" />
			</p>
		</form>

	</body>
</html>