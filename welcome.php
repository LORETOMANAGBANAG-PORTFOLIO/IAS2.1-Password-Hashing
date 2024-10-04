<?php
// welcome.php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
	header("location: login.php");
	exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Welcome</title>
</head>
<body>
	<h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to
	our site.</h1>
	<p>
		<a href="logout.php">Sign Out of Your Account</a>
	</p>
	
</body>
</html>