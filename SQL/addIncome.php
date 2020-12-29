<?php

	session_start();
	
	if(!isset($_SESSION['login']))
	{
		header('Location: index.php');
		exit();
	}
	if(isset($_POST['amount']))
	{
		//udana walidacja? Tak
		$is_OK = true;
		
		//Sprawdź amount
		$amount = $_POST['amount'];
		$amount = htmlentities($amount, ENT_QUOTES, "UTF-8");
				
		//Sprawdź czy jest to liczba?
		if(is_numeric($amount))
		{
			$amount = round($amount,2);
		}
		else
		{
			$is_OK = false;
			$_SESSION['e_amount']="Amount has to be a number ex. 123.99";
		}
		
		//czy liczba jest dodatnia
		if($amount <0)
		{
			$is_OK = false;
			$_SESSION['e_amount']="Amount has to be a positive number";
		}
		
		// zamień przecinek na kropkę
		if (strpos($amount, ",") == true)
		{
		   $amount = str_replace(",",".",$amount);
		}

		//sprawdź date
		$date = $_POST['date'];
		$date = htmlentities($date,ENT_QUOTES, "UTF-8");
		
		if($date == NULL)
		{
			$is_OK = false;
			$_SESSION['e_date'] = "Enter the correct date";
		}
		
		$currentDate = date('Y-m-d');
		
		if($date > $currentDate)
		{
			$is_OK = false;
			$_SESSION['e_date'] = "Date have to be before current date";	
		}
		
		if ($date < 2018-01-01) 
		{
			$is_OK = false;
			$_SESSION['e_date'] = "Date have to be after 2018-01-01";
		}
		
		//wybranie kategorii wpłaty
		if(isset($_POST['category'])) 
		{
			$category = $_POST['category'];
			$_SESSION['fr_category'] = $category;
		}
		else
		{
			$is_OK = false;
			$_SESSION['e_category'] = "Choose income category.";
		}
		
		// sprawdź długość comment
		$comment = $_POST['comment'];
		$comment = htmlentities($comment,ENT_QUOTES, "UTF-8");
		
		if ((strlen($comment) > 250)) 
		{
			$is_OK = false;
			$_SESSION['e_comment'] = "The comment can not exceed 250 characters";
		}
		
		// Pamiętaj wprowadzone dane
		$_SESSION['fr_amount'] = $amount;
		$_SESSION['fr_date'] = $date;
		$_SESSION['fr_category'] = $category;
		$_SESSION['fr_comment'] = $comment;
		
		if ($is_OK == true)
		{
			require_once "connect.php";
			mysqli_report(MYSQLI_REPORT_STRICT);
		
			try 
			{
				$connection = new mysqli ($host, $db_user, $db_password, $db_name);
				
				if ($connection->connect_errno!=0)
				{
					throw new Exception(mysqli_connect_errno());
				}
				
				else
				{
					$user_id = $_SESSION['id'];
					$getting_income_category_id = $connection->query("SELECT id FROM incomes_category_default WHERE user_id = '$user_id' AND name = '$category'");
					$row = $getting_income_category_id->fetch_assoc();
					$income_category_id = $row['id'];
					
					if ($connection->query("INSERT INTO incomes VALUES (NULL, '$user_id', '$category', '$amount', '$date', '$comment')"))
					{
						$_SESSION['successful_income_added']=true;
						header('location: incomeSuccess.php');
					}
					else
					{
						throw new Exception($connection->error);
					}
				}
				$connection->close();
			}
			catch(Exception $e)
			{
				echo '<span style="color:red;">Server error! Please try again later.</span>';
			}
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
			<a class="navbar-brand" href="mainMenu.html"><img src="img/piggyL.png" width="80" height="80" class="d-inline-block mr-1 align-top" alt="Logo Personal Budget"></a>
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
	
	<main>
		
		<article class="expense">
		
			<div class="container">
				
				<div class="row">
					<form method="post">
						
						<div class="bg-white text-body">
						
							<header id="addData">			
								<h4 class="text-uppercase text-center subtitle">Add details of income</h4>
							</header>
						
							<div class="labelExpense">
								<label for="amount" class="titleExpense mr-1 mr-md-0 ml-2 ml-md-1 ml-lg-5">Amount:</label>
								<input class="input mr-xl-5 text-muted" id="amount" name="amount" type="text" value="<?php 
									if(isset($_SESSION['fr_amount']))
									{
										echo $_SESSION['fr_amount'];
										unset($_SESSION['fr_amount']);
									}
								?>" placeholder="1000.00">
								<?php
									if(isset($_SESSION['e_amount']))
										{
											echo '<div id="error">'.$_SESSION['e_amount'].'</div>';
											unset($_SESSION['e_amount']);
										}
								?>			
							</div>
							
							<div class="labelExpense">
								<label for="date" class="titleExpense mr-2 mr-md-0 ml-2 ml-md-1 ml-lg-5">Date:</label>
								<input class="input ml-4 ml-lg-0 text-muted category" id="date" name="date" type="date" value="<?php 
									if(isset($_SESSION['fr_date']))
									{
										echo $_SESSION['fr_date'];
										unset($_SESSION['fr_date']);
									}
								?>" >
								<?php
									if(isset($_SESSION['e_date']))
										{
											echo '<div id="error">'.$_SESSION['e_date'].'</div>';
											unset($_SESSION['e_date']);
										}
								?>	
							</div>
						
							<div style="clear:both;"></div>
					
							<div class="paymentAndCategories ml-sm-5">
								<span class="titleExpense ml-4">Income category:</span><br/>
								<label class="paymentMetod col-11 col-md-4 col-lg-3 ml-lg-5"><input type="radio" name="category" value="Salary"> Salary</label>
								<label class="paymentMetod col-11 col-md-4 col-lg-3 ml-lg-5"><input type="radio" name="category" value="Interest" checked> Bank interest</label>
								<label class="paymentMetod col-11 col-md-4 col-lg-3 ml-lg-5"><input type="radio" name="category" value="Allegro"> Allegro Sales</label>
								<label class="paymentMetod col-11 col-md-4 col-lg-3 ml-lg-5"><input type="radio" name="category" value="Another"> Another</label><br/>
								<?php 
							
								require_once "connect.php";
								mysqli_report(MYSQLI_REPORT_STRICT);
					
								try
								{
									$connection = new mysqli ($host, $db_user, $db_password, $db_name);
									
									if ($connection->connect_errno!=0)
									{
										throw new Exception(mysqli_connect_errno());
									}
									else
									{
										$user_id = $_SESSION['id'];

										if (!$result = $connection->query(sprintf("SELECT name FROM incomes_category_assigned_to_users WHERE user_id = '%s'", 
										mysqli_real_escape_string($connection, $user_id)))) 
										{
											throw new Exception($connection->error);
										}
										
										while ($row = $result->fetch_assoc())
										{
											
										}
										
										$result->close();
										$connection->close();
									}
								}
								catch (Exception $e)
								{
									echo '<span style="color:red;">Server error. Please try again later.</span>';
									//echo '<br />Detailed information: '.$e;
								}
							?>
							</div>
							
							
							<div class="paymentAndCategories ml-sm-5">
								<span class="titleExpense ml-4">Comment (optional):<br/></span>
								<textarea id="comment" name="comment" rows="3" cols="35" value="<?php 
									if(isset($_SESSION['fr_comment']))
									{
										echo $_SESSION['fr_comment'];
										unset($_SESSION['fr_comennt']);
									}
								?>" placeholder="Add your comment">
								<?php
									if(isset($_SESSION['e_comment']))
										{
											echo '<div id="error">'.$_SESSION['e_comment'].'</div>';
											unset($_SESSION['e_comment']);
										}
								?></textarea>
							</div>
							
							<div class="expenseBtn col-8 col-md-6 col-lg-4 offset-lg-1">
								<button class="btn btn-lg btn-block btn-login text-uppercase mb-2" type="reset" onclick="window.location.href = 'mainMenu.php'">Cancel</button>
							</div>
								
							<div class="expenseBtn col-8 col-md-6 col-lg-4 offset-lg-1">
								<button class="btn btn-lg btn-block btn-login text-uppercase mb-2" type="submit">Submit</button>
							</div>
							<div style="clear:both;"></div>

						</div>
					</form>
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