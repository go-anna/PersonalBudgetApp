<?php

	session_start();
	
	if (!($_SESSION['login'] == true))
	{
		header ('Location: index.php');
		exit();
	}
	
	else
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
				
				if ((!isset($_POST['periodsOptions']) || $_POST['periodsOptions'] == "current_month") && !isset($_POST['customize_period']))
				{
					
					if (!$_SESSION['result_incomes'] = $connection->query("SELECT cat.name, SUM(inc.amount) FROM incomes_category_assigned_to_users cat INNER JOIN incomes inc WHERE inc.income_category_assigned_to_user_id = cat.id AND date_of_income >=((EXTRACT(YEAR_MONTH FROM CURDATE())*100)+1) AND inc.user_id = '$user_id' GROUP BY cat.name ORDER BY SUM(inc.amount) DESC"))
					{
						throw new Exception($connection->error);
					}
					
					if (!$_SESSION['result_expenses'] = $connection->query("SELECT cat.name, SUM(exp.amount) FROM expenses_category_assigned_to_users cat INNER JOIN expenses exp WHERE exp.expense_category_assigned_to_user_id = cat.id AND date_of_expense >=((EXTRACT(YEAR_MONTH FROM CURDATE())*100)+1) AND exp.user_id = '$user_id' GROUP BY cat.name ORDER BY SUM(exp.amount) DESC"))
					{
						throw new Exception($connection->error);
					}
					
					if(!$_SESSION['total_income'] = $connection->query("SELECT SUM(inc.amount) FROM incomes_category_assigned_to_users cat INNER JOIN incomes inc WHERE inc.income_category_assigned_to_user_id = cat.id AND date_of_income >=((EXTRACT(YEAR_MONTH FROM CURDATE())*100)+1) AND inc.user_id = '$user_id'"))
					{
						throw new Exception($connection->error);
					}
					
					if(!$_SESSION['total_expense'] = $connection->query("SELECT SUM(exp.amount) FROM expenses_category_assigned_to_users cat INNER JOIN expenses exp WHERE exp.expense_category_assigned_to_user_id = cat.id AND date_of_expense >=((EXTRACT(YEAR_MONTH FROM CURDATE())*100)+1) AND exp.user_id = '$user_id'"))
					{
						throw new Exception($connection->error);
					}
				}
				
				if (isset($_POST['periodsOptions']) && $_POST['periodsOptions'] == "previous_month")
				{
				
					if (!$_SESSION['result_incomes'] = $connection->query("SELECT cat.name, SUM(inc.amount) FROM incomes_category_assigned_to_users cat INNER JOIN incomes inc WHERE inc.income_category_assigned_to_user_id = cat.id AND YEAR(date_of_income) = YEAR(CURDATE() - INTERVAL 1 MONTH) AND MONTH(date_of_income) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND inc.user_id = '$user_id' GROUP BY cat.name ORDER BY SUM(inc.amount) DESC"))
					{
						throw new Exception($connection->error);
					}
					
					if (!$_SESSION['result_expenses'] = $connection->query("SELECT cat.name, SUM(exp.amount) FROM expenses_category_assigned_to_users cat INNER JOIN expenses exp WHERE exp.expense_category_assigned_to_user_id = cat.id AND YEAR(date_of_expense) = YEAR(CURDATE() - INTERVAL 1 MONTH) AND MONTH(date_of_expense) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND exp.user_id = '$user_id' GROUP BY cat.name ORDER BY SUM(exp.amount) DESC"))
					{
						throw new Exception($connection->error);
					}
					
					if(!$_SESSION['total_income'] = $connection->query("SELECT SUM(inc.amount) FROM incomes_category_assigned_to_users cat INNER JOIN incomes inc WHERE inc.income_category_assigned_to_user_id = cat.id AND YEAR(date_of_income) = YEAR(CURDATE() - INTERVAL 1 MONTH) AND MONTH(date_of_income) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND inc.user_id = '$user_id'"))
					{
						throw new Exception($connection->error);
					}
					
					if(!$_SESSION['total_expense'] = $connection->query("SELECT SUM(exp.amount) FROM expenses_category_assigned_to_users cat INNER JOIN expenses exp WHERE exp.expense_category_assigned_to_user_id = cat.id AND YEAR(date_of_expense) = YEAR(CURDATE() - INTERVAL 1 MONTH) AND MONTH(date_of_expense) = MONTH(CURDATE() - INTERVAL 1 MONTH) AND exp.user_id = '$user_id'"))
					{
						throw new Exception($connection->error);
					}
				}
				
				if (isset($_POST['periodsOptions']) && $_POST['periodsOptions'] == "current_year")
				{
				
					if (!$_SESSION['result_incomes'] = $connection->query("SELECT cat.name, SUM(inc.amount) FROM incomes_category_assigned_to_users cat INNER JOIN incomes inc WHERE inc.income_category_assigned_to_user_id = cat.id AND YEAR(date_of_income) = YEAR(CURDATE()) AND inc.user_id = '$user_id' GROUP BY cat.name ORDER BY SUM(inc.amount) DESC"))
					{
						throw new Exception($connection->error);
					}
					
					if (!$_SESSION['result_expenses'] = $connection->query("SELECT cat.name, SUM(exp.amount) FROM expenses_category_assigned_to_users cat INNER JOIN expenses exp WHERE exp.expense_category_assigned_to_user_id = cat.id AND YEAR(date_of_expense) = YEAR(CURDATE()) AND exp.user_id = '$user_id' GROUP BY cat.name ORDER BY SUM(exp.amount) DESC"))
					{
						throw new Exception($connection->error);
					}
					
					if(!$_SESSION['total_income'] = $connection->query("SELECT SUM(inc.amount) FROM incomes_category_assigned_to_users cat INNER JOIN incomes inc WHERE inc.income_category_assigned_to_user_id = cat.id AND YEAR(date_of_income) = YEAR(CURDATE()) AND inc.user_id = '$user_id'"))
					{
						throw new Exception($connection->error);
					}
					
					if(!$_SESSION['total_expense'] = $connection->query("SELECT SUM(exp.amount) FROM expenses_category_assigned_to_users cat INNER JOIN expenses exp WHERE exp.expense_category_assigned_to_user_id = cat.id AND YEAR(date_of_expense) = YEAR(CURDATE()) AND exp.user_id = '$user_id'"))
					{
						throw new Exception($connection->error);
					}
				}
				
				if (isset($_POST['custom_start']) && isset($_POST['custom_end']))
				{
					$custom_start = $_POST['custom_start'];
					$custom_end = $_POST['custom_end'];
					
					if (!$_SESSION['result_incomes'] = $connection->query("SELECT cat.name, SUM(inc.amount) FROM incomes_category_assigned_to_users cat INNER JOIN incomes inc WHERE inc.income_category_assigned_to_user_id = cat.id AND date_of_income BETWEEN '$custom_start' AND '$custom_end' AND inc.user_id = '$user_id' GROUP BY cat.name ORDER BY SUM(inc.amount) DESC"))
					{
						throw new Exception($connection->error);
					}
					
					if (!$_SESSION['result_expenses'] = $connection->query("SELECT cat.name, SUM(exp.amount) FROM expenses_category_assigned_to_users cat INNER JOIN expenses exp WHERE exp.expense_category_assigned_to_user_id = cat.id AND date_of_expense BETWEEN '$custom_start' AND '$custom_end' AND exp.user_id = '$user_id' GROUP BY cat.name ORDER BY SUM(exp.amount) DESC"))
					{
						throw new Exception($connection->error);
					}
					
					if(!$_SESSION['total_income'] = $connection->query("SELECT SUM(inc.amount) FROM incomes_category_assigned_to_users cat INNER JOIN incomes inc WHERE inc.income_category_assigned_to_user_id = cat.id AND date_of_income BETWEEN '$custom_start' AND '$custom_end' AND inc.user_id = '$user_id'"))
					{
						throw new Exception($connection->error);
					}
					
					if(!$_SESSION['total_expense'] = $connection->query("SELECT SUM(exp.amount) FROM expenses_category_assigned_to_users cat INNER JOIN expenses exp WHERE exp.expense_category_assigned_to_user_id = cat.id AND date_of_expense BETWEEN '$custom_start' AND '$custom_end' AND exp.user_id = '$user_id'"))
					{
						throw new Exception($connection->error);
					}
				}
			}
			$connection->close();
		}
		
		catch(Exception $e)
		{
			echo '<span style="color:red;">Server error! Please try again later.</span>';
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

	<script>
	window.onload = function() 
	{
	var incomesChart = document.getElementById('incomeChart').getContext('2d');

	Chart.defaults.global.defaultFontColor = '#111';
	Chart.defaults.global.defaultFontFamily = "'Lora', sans-serif";

	var incomeChart = new Chart(incomesChart, {
		type: 'pie',
		data: {
			<?php
								while ($row_with_incomes = $_SESSION['result_incomes']->fetch_assoc())
								{
									$amount = $row_with_incomes['SUM(inc.amount)'];
									$name = $row_with_incomes['name'];
									echo "{y: " . $amount . ", label: \" $name \"},";
								}
								$_SESSION['result_incomes']->data_seek(0); 
							?>
				backgroundColor: [
					'rgb(0, 102, 153)',
					'rgb(0, 255, 204)',
					'rgb(0, 204, 102)',
					'rgb(0, 153, 0)'
				],
				borderColor: [
					'rgb(0, 68, 102)',
					'rgb(0, 204, 163)',
					'rgb(0, 179, 89)',
					'rgb(0, 128, 0)'
				],
				borderWidth: 1
			}]
		},
		options: {
			title:{
				display: true,
				text: 'Summary of your incomes by categoty',
				fontSize: 18,
				fontStyle: 'normal'
			},
			legend:{
				display: true,
				position: 'right',
			},
			layout:{
				padding:{
					top: 30
				}
			},
		}
	});

	expenesChart.render();
				
	var expenesChart = document.getElementById('expenseChart').getContext('2d');

	Chart.defaults.global.defaultFontColor = '#111';
	Chart.defaults.global.defaultFontFamily = "'Lora', sans-serif";

	var expenseChart = new Chart(expenesChart, {
		type: 'pie',
		data: {
			<?php
								while ($row_with_expenses = $_SESSION['result_expenses']->fetch_assoc())
								{
									$amount = $row_with_expenses['SUM(exp.amount)'];
									$name = $row_with_expenses['name'];
									echo "{y: " . $amount . ", label: \" $name \"},";
								}
								$_SESSION['result_expenses']->data_seek(0); 
							?>
				backgroundColor: [
					'rgb(230, 0, 0)',
					'rgb(255, 102, 0)',
					'rgb(204, 0, 153)',
					'rgb(255, 204, 0)',
					
					'rgb(255, 204, 204)',
					'rgb(153, 102, 255)',
					'rgb(255, 0, 102)',
					'rgb(153, 51, 51)',
					
					'rgb(204, 51, 0)',
					'rgb(255, 204, 153)',
					'rgb(153, 0, 0)',
					'rgb(153, 0, 153)',
					
					'rgb(255, 133, 102)',
					'rgb(153, 0, 51)',
					'rgb(255, 0, 85)',
					'rgb(255, 255, 51)'
				],
				borderColor: [
					'rgb(204, 0, 0)',
					'rgb(230, 92, 0)',
					'rgb(179, 0, 179)',
					'rgb(230, 184, 0)',
					
					'rgb(255, 179, 179)',
					'rgb(136, 77, 255)',
					'rgb(230, 0, 92)',
					'rgb(134, 45, 45)',
					
					'rgb(179, 45, 0)',
					'rgb(255, 191, 128)',
					'rgb(128, 0, 0)',
					'rgb(128, 0, 128)',
					
					'rgb(255, 112, 77)',
					'rgb(128, 0, 42)',
					'rgb(230, 0, 76)',
					'rgb(255, 255, 26)'
				],
				borderWidth: 1
			}]
		},
		options: {
			title:{
				display: true,
				text: 'Summary of your expanses by categoty',
				fontSize: 18,
				fontStyle: 'normal'
				
			},
			legend:{
				display: true,
				position: 'right',
			},
			layout:{
				padding:{
					top: 30
				}
			},
		}
	}); 

			expenesChart.render();
		}
	</script>
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
			<form method="post">
				<article class="expense">
				
					<div class="container">
						
						<div class="row">
							
							<div class="bg-white text-body">
							
								<header id="addData">			
									<h4 class="text-uppercase text-center mr-4 subtitle">Balance of your expences and incomes</h4>
								</header>
									<div class="dropdown-item" >
										<label for="time_period">Choose period of time:</label>
										<select class="form-control" id="form-control" name="form-control">
											<option  value="current_month">Current Month</option>
											<option  value="previous_month">Previous Month</option>
											<option  value="current_year">Current Year</option>
											<option  value="selected_period">Selected Period</option>
										</select>
									</div>
								<div class="labelExpense">
									<label for="date1" class="titleExpense mr-1 mr-md-0 ml-2 ml-md-1 ml-lg-5">Start date:</label>
									<input class="input text-muted mr-xl-5" id="date1" name="date1" type="date" value="2020-10-01" min="2018-01-01">
								</div>
								
								<div class="labelExpense">
									<label for="date2" class="titleExpense mr-2 mr-md-0 ml-2 ml-md-1 ml-lg-5">End date:</label>
									<input class="input text-muted ml-2 mr-4" id="date2" name="date2" type="date" value="2020-10-31" min="2018-01-01">
								</div>
					
								<div style="clear:both;"></div>

							<?php
			
								$_SESSION['total_income']->data_seek(0);
								$row_with_total_income = $_SESSION['total_income']->fetch_assoc();
								$_SESSION['sum_of_income'] = $row_with_total_income['SUM(inc.amount)'];
								$_SESSION['total_expense']->data_seek(0);
								$row_with_total_expense = $_SESSION['total_expense']->fetch_assoc();
								$_SESSION['sum_of_expense'] = $row_with_total_expense['SUM(exp.amount)'];
								$balance = $_SESSION['sum_of_income'] - $_SESSION['sum_of_expense'];
								
								if($balance < 0)
								{
									echo '<div class="summary" id="summary">';
									echo '<h2 class="h3">Total balance: ' . $balance . ' EUR</h2>';
									echo '<h2 class="h4 mb-4">Be careful, you run into debt!</h2>';
									echo '</div>';
								}
								
								else
								{
									echo '<div class="summary" id="summary">';
									echo '<h2 style="color:red;" class="h3">Your balance is: ' . $balance . ' PLN</h2>';
									echo '<h2 style="color:red;" class="h4 mb-4">Congratulations. You manage great your finance</h2>';
									echo '</div>';
								}
							
							?>
		
						<section>
							<h4 class="text-capitalize text-center subtitle">Revenue statement</h4>
							<div class="revenue">
								<div id="incomesTable">
									<table class="table table-sm balanceTable">
										<tr>
											<th>Category</th>
											<th>Amount in PLN</th>
										</tr>
										<tr>
											<?php
												while ($row_with_incomes = $_SESSION['result_incomes']->fetch_assoc())
												{
													echo "<tr><td>" . $row_with_incomes['name'] . "</td><td>" . $row_with_incomes['SUM(inc.amount)'] . "</td></tr>";
													$_SESSION['name']=$row_with_incomes['name'];
													$_SESSION['amount']=$row_with_incomes['SUM(inc.amount)'];
												}
												$_SESSION['result_incomes']->data_seek(0);
											?>
										</tr>
										<tr class="total">
											<td>Total</td>
											<?php										
												$sum_of_income = $_SESSION['sum_of_income'];
												echo "<td>" . $sum_of_income . "</td>";
											?>
										</tr>
									</table>
								</div>
							</div>
							<div class="revenue">
								<div class="chartContainer">
									<canvas id="incomeChart"></canvas> 
									<?php 
										if($_SESSION['result_incomes']->num_rows > 0)
										{
											echo '<div id="incomeChart">';
											echo '<script src="charts.js" defer></script>';
											echo '</div>';
										}
										else
										{
											echo '<span><br />You did not have any income</span>';
										}
										$_SESSION['result_incomes']->data_seek(0);
									?>
								</div>
							</div>				
							<div style="clear:both"></div>						
						</section>
						
						<section>
							<h4 class="text-capitalize text-center subtitle">Expense statement</h4>
							<div class="loss">
								<div id="expensesTable">
									<table class="table table-sm balanceTable">
										<tr>
											<th>Category</th>
											<th>Amount in PLN</th>
										</tr>
										<tr>
											<?php
												while ($row_with_incomes = $_SESSION['result_incomes']->fetch_assoc())
												{
													echo "<tr><td>" . $row_with_incomes['name'] . "</td><td>" . $row_with_incomes['SUM(inc.amount)'] . "</td></tr>";
													$_SESSION['name']=$row_with_incomes['name'];
													$_SESSION['amount']=$row_with_incomes['SUM(inc.amount)'];
												}
												$_SESSION['result_incomes']->data_seek(0);
											?>
										</tr>
										<tr class="total">
											<td>Total</td>
											<?php										
												$sum_of_income = $_SESSION['sum_of_income'];
												echo "<td>" . $sum_of_income . "</td>";
											?>
										</tr>
									</table>
								</div>
							</div>
							<div class="loss">
								<div class="chartContainer">
									<canvas id="expenseChart"></canvas> 
									<?php 
										if($_SESSION['result_incomes']->num_rows > 0)
										{
											echo '<div id="incomeChart">';
											echo '<script src="charts.js" defer></script>';
											echo '</div>';
										}
										else
										{
											echo '<span><br />You did not have any expense</span>';
										}
										$_SESSION['result_incomes']->data_seek(0);
									?>
								</div>
							</div>				
							<div style="clear:both"></div>						
						</section>
							</div>
						</div>
					</div>
				</article>
			</form>
		</main>	
	
		<footer class="footer">
		Copyright © 2020 Personal Budget All Rights Reserved Thank you for your visit!
		</footer>
		
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" defer></script>
		<script src="charts.js" defer></script>
		<script src="summary.js" defer></script>
	</body>
</html>
									
					