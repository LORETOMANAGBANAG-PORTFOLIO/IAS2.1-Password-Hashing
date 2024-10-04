
<?php
session_start();
	    include ("config.php");


if($_SERVER["REQUEST_METHOD"] == "POST"){
	$username =  $_POST['username'];
	$password = $_POST['password'];
	$recaptcha_response = $_POST['g-recaptcha-response'];

if(!verifyRecaptcha($recaptcha_response)){
	$_SESSION['message'] = "Invalid reCAPTCHA. Please try again.";
	header("location: register.php");
	exit();
	}
	
$sql = "SELECT id FROM users WHERE username = ?";

if($stmt = mysqli_prepare($conn, $sql)){
	mysqli_stmt_bind_param($stmt, "s", $param_username);
	$param_username = $username;
	if(mysqli_stmt_execute($stmt)){
	mysqli_stmt_store_result($stmt);
	if(mysqli_stmt_num_rows($stmt) == 1){
	$_SESSION['message'] = "This username is already taken.";
} 

else{
	$sql = "INSERT INTO users (username, password) VALUES (?, ?)";
	if($stmt = mysqli_prepare($conn, $sql)){
	mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
	$param_username = $username;
	$param_password = password_hash($password, PASSWORD_DEFAULT);
	if(mysqli_stmt_execute($stmt)){
	$_SESSION['message'] = "Registration successful. You can now log in.";
	header("location: login.php");
	exit();
} 
	else{
	$_SESSION['message'] = "Something went wrong. Please try again later.";
		
		}
	}
}

} else{
		$_SESSION['message'] = "Oops! Something went wrong. Please try again later.";
		}
	mysqli_stmt_close($stmt);
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sign Up</title>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
	<center>
	<div class="container bg-light">
		<h2>Sign Up</h2>
		<p><?php echo isset($_SESSION['message']) ? $_SESSION['message'] : '';
		unset($_SESSION['message']); ?></p>
		<form class="form-control" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		<div>
		<label>Username</label>
		<input class="form-control" type="text" name="username" required>
		</div>
		<div>
		<label>Password</label>
		<input class="form-control" type="password" name="password" required>
		</div>
		<div class="g-recaptcha" data-sitekey="<?php echo $recaptcha_site_key; ?>"></div>
		<div>
		<input type="submit" value="Submit">
		</div>
		<p>Already have an account? <a href="login.php">Login here</a>.</p>
</form>
</div>
</center>
</body>
</html>