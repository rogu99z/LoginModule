<?php
	require_once(dirname(__FILE__)."/core/root.php");
	$mysqli = new mysqli($GLOBALS["hostname"], $GLOBALS["username"], $GLOBALS["password"], $GLOBALS["database"]);

	if($mysqli->connect_error){
		$error = true;
		$mysql_text = $mysqli->connect_error;
	}

	if(isset($_POST["step"]))
		$notInstalling = true;

	$schema["users"] = "CREATE TABLE IF NOT EXISTS `users` (
		`id` int(11) NOT NULL auto_increment,
		`username` varchar(128) NOT NULL,
		`password` varchar(40) NOT NULL,
		`mostRecentActivity` datetime NOT NULL,
		`creationTime` datetime NOT NULL,
		PRIMARY KEY  (`id`),
		UNIQUE KEY `username` (`username`)
	) ENGINE=MyISAM AUTO_INCREMENT=1 ;";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Install</title>
	  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	  <link rel="stylesheet" type="text/css" href="common_styles.css">
	</head>
	<body>
		<h1>Installation</h1>
		<span>Don't forget to modify database configuration in file /core/root.php</span>
		<br/>
		<?php if(isset($error)): ?>
			<p>
				<strong>MySQL Connection error</strong>
			</p>
			<p>
				<?php echo $mysql_text; ?>
			</p>
		<?php else: ?>
			<form method="post" action="">
				<?php if(!isset($notInstalling)): ?>
					<br/>
					<p>
						<input type="hidden" name="step" value="1" />
						<input type="submit" name="submit" value="Create tables" />
					</p>
				<?php else: ?>
					<br/>
					<p>
						<strong>Creating database tables</strong>
					</p>
					<br/>
					<?php foreach($schema as $table => $query): ?>
						<p class="faded">
							<?php			
								if($mysqli->query($query) === TRUE){
									$status = "created";
								}else{
									$status = "error ".$mysqli->error;
								}
							?>
							Table <?php echo $table; ?> <?php echo $status; ?>
						</p>
						<br/>
					<?php endforeach; ?>

				<?php endif; ?>
			</form>
		<?php endif; ?>
	</body>
</html>