<?php

	session_start();
	
	if (!isset($_SESSION['successful_registration']))
	{
		header('Location: index.php');
		exit();
	}
	else
	{
		unset($_SESSION['successful_registration']);
	}
	
	//Usuwanie wszystkich zmiennych
	if (isset($_SESSION['fr_name'])) unset($_SESSION['fr_name']);
	if (isset($_SESSION['fr_email'])) unset($_SESSION['fr_email']);
	if (isset($_SESSION['fr_password1'])) unset($_SESSION['fr_password1']);
	if (isset($_SESSION['fr_password2'])) unset($_SESSION['fr_password2']);
	
	// Usuwanie błędów rejestracji
	if (isset($_SESSION['e_name'])) unset($_SESSION['e_name']);
	if (isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
	if (isset($_SESSION['e_password'])) unset($_SESSION['e_password']);
	if(isset($_SESSION['e_bot'])) unset($_SESSION['e_bot']);

?>
<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Your personal budget</title>
	<meta name="description" content="Front page">
	<meta name="keywords" content="form, front page, budget, finances, managing">
	
	<meta http-equiv="X-Ua-Compatible" content="IE=edge">
	
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css">
			
	<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500&display=swap" rel="stylesheet">
	
	
	<!--[if lt IE 9]>
	<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
	<![endif]-->

</head>

<body>
	<header>
		<nav class="navbar navbar-dark">
			<a class="navbar-brand" href="mainMenu.php"><img src="img/piggyL.png" width="80" height="80" class="d-inline-block mr-1 align-top" alt="Logo Personal Budget"></a>
			<a class="navbar-text order-last order-sm-last">
					<h1 class="text-uppercase text-center">Welcome to Personal Budget app</h1>
			</a>
			<button class="btn text-uppercase mb-2 text-white font-weight-bold order-md-last" type="button"><a href="index.php" style="color:inherit">Sign In</a></button>
		</nav>
	</header>	
	
		<div class="offset-md-2 col-lg-8 offset-md-2 text-body wpis">

			<h3 class="h2 text-md-left text-center">Thank you for your registration.<br><br>
			<a href="index.php">Now you can sign in!!</a> 
			</h3>

		</div>
	
			
	<footer class="footer">
	Copyright © 2020 Personal Budget All Rights Reserved Thank you for your visit!
	</footer>
	
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>