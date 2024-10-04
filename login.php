
<?php
// login.php
	    include ("config.php");
 

session_start();
if($_SERVER["REQUEST_METHOD"] == "POST"){
		$username = $_POST['username'];
		$password = $_POST['password'];
		$recaptcha_response = $_POST['g-recaptcha-response'];
if(!verifyRecaptcha($recaptcha_response)){
		$_SESSION['message'] = "Invalid reCAPTCHA. Please try again.";
		header("location: login.php");
		exit();
}

$sql = "SELECT id, username, password FROM users WHERE username = ?";

if($stmt = mysqli_prepare($conn, $sql)){
			mysqli_stmt_bind_param($stmt, "s", $param_username);
			$param_username = $username;
	if(mysqli_stmt_execute($stmt)){
		mysqli_stmt_store_result($stmt);
		if(mysqli_stmt_num_rows($stmt) == 1){
				mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
			if(mysqli_stmt_fetch($stmt)){
		if(password_verify($password, $hashed_password)){

			$_SESSION["loggedin"] = true;
			$_SESSION["id"] = $id;
			$_SESSION["username"] = $username;
			header("location: welcome.php");
			} else{
					$_SESSION['message'] = "Invalid username or password.";
				}
		}
		} else{
			$_SESSION['message'] = "Invalid username or password.";
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
<title>Login</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</script>
</head>
<body>
	<div class="container bg-light ">
	<center>	
	<h2>Login</h2>
	<p><?php echo isset($_SESSION['message']) ? $_SESSION['message'] : '';
	unset($_SESSION['message']); ?></p>
	
		<form class="form-control" action=" <?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
				<input  type="submit" value="Login">
			</div>
				<p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
		</form>
	</center>
	</div>
	</body>
</html>
