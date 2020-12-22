<?php

	session_start();
	
	if((isset($_SESSION['login']))&&($_SESSION['login']==true))
	{
		header('Location: mainMenu.php');
		exit();
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Your personal budget</title>
	<meta name="description" content="Login form">
	<meta name="keywords" content="form, login, name, password">
	
	<meta http-equiv="X-Ua-Compatible" content="IE=edge">
	
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css">
	
		
	<!--[if lt IE 9]>
	<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
	<![endif]-->

</head>

<body>
	<div id="login">
	<div class="container">
		<div id="login-row" class="row justify-content-center">
			<div id="login-column" class="offset-md-2 col-md-8 offset-md-2 ">
				<div id="login-box" class="col-md-12">
					<form id="login-form" class="form" action="login.php" method="post">
						<h2 class="text-center text-dark">User Login</h2>
						<?php
						if(isset($_SESSION['error']))		echo $_SESSION['error'];
						?>
						<div class="form-group">
						
							<label for="username" class="text-dark">Username:</label><br/>
							<input type="text" name="login" id="username" class="form-control text-muted" placeholder="John.D">
						</div>
						<div class="form-group">
							<label for="password" class="text-dark">Password:</label><br/>
							<input type="password" name="password" id="password" class="form-control text-muted" placeholder="At least 6 characters">
						</div>
						<div class="form-group">
						 <button class="btn btn-lg btn-block btn-login text-uppercase mb-2" type="submit">Sign in</button>
						<div class="text-center">
							<a class="small text-dark" href="registration.php">Don't have an account? Create account</a>
						</div>
						</div>
						
					</form>
					
				</div>
			</div>    
			
		</div>
	</div>
	</div>
	<footer class="footer">
	Copyright © 2020 Personal Budget All Rights Reserved Thank you for your visit!
	</footer>
	
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	<script src="js/bootstrap.min.js"></script>
	
</body>
</html>