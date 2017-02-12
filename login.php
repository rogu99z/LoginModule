<?php
	session_start();
	require_once(dirname(__FILE__)."/core/root.php");
	$user = new User();

	if(isset($_POST["username"])){
		$res = $user->login($_POST["username"], $_POST["password"]);

		if(!$res){
			$error = "Wrong credentials!";
		}else{
			header("Location: users.php");
			exit;
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title></title>
	  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	  <link rel="stylesheet" type="text/css" href="common_styles.css">
	</head>
	<body>
		<h1>Login</h1>

		<?php if(isset($error)): ?>
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
				<input type="submit" name="submit" value="Login" />
			</p>
		</form>
	</body>
</html>