<?php
	session_start();
	require_once(dirname(__FILE__)."/core/root.php");
	$user = new User();

	if(!$user->logged_in){
		header("Location: login.php");
		exit;
	}

	$users = $user->getUsers();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="common_styles.css">
	</head>
	<body>
		<h1>User Management</h1>
		<table cellpadding="0" cellspacing="0" border="1">
			<thead>
				<tr>
					<th>Username</th>
					<th>Creation time</th>
					<th>Most recent activity</th>
					<th></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="4">
						&zwnj;
						<a href="create.php"><h4>Create user</h4></a> 
						&zwnj;
						<a href="logout.php"><h4>Logout</h4></a>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php foreach( $users as $userField ): ?>
				<tr>
					<td><h5><?php echo $userField["username"]; ?></h5></td>					
					<td><h5><?php echo $userField["creationTime"]; ?></h5></td>
					<td><h5><?php echo $userField["mostRecentActivity"]; ?></h5></td>
					<td ><a href="delete.php?id=<?php echo $userField["id"]; ?>"><h5>Delete</h5></a> <a href="password.php?id=<?php echo $userField["id"]; ?>"><h5>Change password</h5></a></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

	</body>
</html>