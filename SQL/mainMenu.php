<?php

	session_start();
	
	if(!isset($_SESSION['login']))
	{
		header('Location: index.php');
		exit();
	}
	
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
			<a class="navbar-text">
				<h1 class="text-uppercase text-center text-md-left d-none d-md-block">Welcome to Personal Budget app</h1>
			</a>
			<button class="btn text-uppercase mb-2 text-white font-weight-bold" type="button"><a href="logout.php" style="color:inherit">Sign Out</a></button>
		</nav>
	</header>	
	
	<div class="menu">	
		
		<nav class="navbar navbar-dark navbar-expand-md">
		
			<button class="navbar-toggler ml-auto" type="button" data-toggle="collapse" data-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Przełącznik nawigacji">
				<span class="navbar-toggler-icon"></span>
			</button>
			
			<div class="collapse navbar-collapse" id="mainmenu">
				<ul class="navbar-nav mr-auto">
				
					<li class="nav-item">
						<a class="nav-link" href="addIncome.php"> Add income </a>
					</li>
					
					<li class="nav-item">
						<a class="nav-link" href="addExpense.php"> Add expense </a>
					</li>
					
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="showBalance.php" data-toggle="dropdown" role="button" aria-expanded="false" id="submenu" aria-haspopup="true"> Show balance </a>
						
						<div class="dropdown-menu" aria-labelledby="submenu">
						
							<a class="dropdown-item" href="#"> Current month </a>
							<a class="dropdown-item" href="#"> Previous month </a>	
												
							<a class="dropdown-item" href="#"> Current year </a>
							<a class="dropdown-item" href="#"> Custom date </a>
						
						</div>
						
					</li>
					
					<li class="nav-item">
						<a class="nav-link" href="#"> Settings </a>
					</li>
							
				</ul>
				
				<form class="form-inline d-none d-xl-block">
				
					<input class="form-control mr-1" type="search" placeholder="Search" aria-label="Search">
					<button class="btn btn-sm" type="submit">Search</button>
				
				</form>
								
			</div>
		
		</nav>
	</div>
	
	<?php
	echo "<p>Witaj ".$_SESSION['username']."</p>";
	?>
	
	<footer class="footer">
	Copyright © 2020 Personal Budget All Rights Reserved Thank you for your visit!
	</footer>
	
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>