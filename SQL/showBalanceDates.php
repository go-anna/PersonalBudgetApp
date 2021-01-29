<?php

	session_start();
	
	if(!isset($_SESSION['login']))
	{
		header ('Location: index.php');
		exit();
	}
	
	if(isset($_POST['periodOfTime']))
	{
		$is_OK = true;
		$periodOfTime = $_POST['periodOfTime'];
		$_SESSION['formPeriodOfTime'] = $periodOfTime;
	}
	
	if(isset($_POST['start_date'])) 
	{
		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date'];
		$today = date('Y-m-d');
		$start_date = htmlentities($start_date,ENT_QUOTES, "UTF-8");
		$end_date = htmlentities($end_date,ENT_QUOTES, "UTF-8");
		$is_OK = true;
		
		if($start_date == NULL)
		{
			$is_OK = false;
			$_SESSION['e_start_date'] = "Choose the start date.";
		}
				
		if($end_date == NULL)
		{
			$is_OK = false;
			$_SESSION['e_end_date'] = "Choose the end date.";
		}				
					
		if($start_date > $today)
		{
			$is_OK = false;
			$_SESSION['e_start_date'] = "The start date cannot be greater than today's date.";
		}
					
		if($end_date > $today)
		{
			$is_OK = false;
			$_SESSION['e_end_date'] = "The end date cannot be greater than today's date.";
		}
			
		if($end_date!=NULL && $start_date!=NULL)
		{
			if($end_date < $start_date)
			{
				$is_OK = false;
				$_SESSION['e_end_date'] = "The end date can not be less from the date of the beginning of the period.";
			}
		}
		
		$_SESSION['fr_start_date'] = $start_date;
		$_SESSION['fr_end_date'] = $end_date;
		
		$_SESSION['selected_start_date'] = $start_date  ;
		$_SESSION['selected_end_date'] = $end_date;
		
	
		if ((isset($_SESSION['selected_start_date'])) && isset($_SESSION['selected_end_date']) && $is_OK == true)
		{
			header ('Location: showBalance.php');
		}
		
		
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
			<button class="btn text-uppercase mb-2 text-white font-weight-bold" type="button">Sign Out</button>
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
					
					<li class="nav-item">
						<a class="nav-link" href="showBalance.php"> Show Balance </a>
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
	
	<main>
		
		<article class="expense">
		
			<div class="container">
			
				<div class="text-justify">
					<?php
				if(isset($_SESSION['formPeriodOfTime'])&& $_SESSION['formPeriodOfTime'] == "selectedPeriod")
				{
echo<<<end
						<form method = "post" >
							<div class="bg-white text-body">
								<header id="addData">			
									<h4 class="text-uppercase text-center mr-4 subtitle">Balance of your expences and incomes</h4>
								</header>
								
								<div class="labelExpense">
									<label for="start_date" class="titleExpense mr-1 mr-md-0 ml-2 ml-md-1 ml-lg-5">Start date:</label>
										<input class="input text-muted mr-xl-5" id="date1" name="start_date" type="date" value="
end;
											if (isset($_SESSION['fr_start_date']))
											{
												echo $_SESSION['fr_start_date'];
												unset($_SESSION['fr_start_date']);
											}
											echo '"class="form-control" min="2018-01-01">';
											
											if (isset($_SESSION['e_start_date']))
											{
												echo '<div id="error">'.$_SESSION['e_start_date'].'</div>';
												unset($_SESSION['e_start_date']);
											}
echo<<<end
								</div>
								
								<div class="labelExpense">
									<label for="end_date" class="titleExpense mr-1 mr-md-0 ml-2 ml-md-1 ml-lg-5">End date:</label>
										<input class="input text-muted mr-xl-5" id="date2" name="end_date" type="date" value="
end;
											if (isset($_SESSION['fr_end_date']))
											{
												echo $_SESSION['fr_end_date'];
												unset($_SESSION['fr_end_date']);
											}
											echo '"class="form-control" min="2018-01-01">';
											
											if (isset($_SESSION['e_end_date']))
											{
												echo '<div id="error">'.$_SESSION['e_end_date'].'</div>';
												unset($_SESSION['e_end_date']);
											}
echo<<<end
								</div>			
								<div style="clear:both;"></div>  
																
								<div class="col-8 col-md-6 offset-lg-1 col-lg-4 offset-lg-4">
									<button class="btn btn-lg btn-block btn-login text-uppercase mb-2" type="submit">Submit</button>
								</div>
								<br/>
							</div>	
						</form>
end;
					}
					else if ($_SESSION['formPeriodOfTime'] == "current_month" || $_SESSION['formPeriodOfTime'] == "previous_month" || $_SESSION['formPeriodOfTime'] == "current_year")
					{ 
						header ('Location: showBalance.php');
					}
					?>
				</div>
			</div>
		</article>
	</main>	
	
	<footer class="footer">
	Copyright © 2020 Personal Budget All Rights Reserved Thank you for your visit!
	</footer>
	
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>