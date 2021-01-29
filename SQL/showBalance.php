<?php

	session_start();
	
	if(!isset($_SESSION['login']))
	{
		header ('Location: index.php');
		exit();
	}
	
	if(isset($_SESSION['formPeriodOfTime']))
	{
		$is_OK = true;
		$period_of_time=$_SESSION['formPeriodOfTime'];
		$today = date('Y-m-d');
		
		if($period_of_time == "current_month")
		{
			$start_date = date('Y-m-d',strtotime("first day of this month"));
			$end_date = date('Y-m-d',strtotime("today"));
		}
		else if($period_of_time == "previous_month")
		{
			$start_date = date('Y-m-d',strtotime("first day of previous month"));
			$end_date = date('Y-m-d',strtotime("last day of previous month"));
		}
		else if($period_of_time == "current_year")
		{
			$start_date = date('Y-m-d',strtotime("1 January this year"));
			$end_date = date('Y-m-d',strtotime("today"));
		}
		else if($period_of_time == "selectedPeriod")
		{
			$start_date = $_SESSION['selected_start_date'];
			$end_date = $_SESSION['selected_end_date'];	
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
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
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
				
				<div class="row">
					
					<div class="bg-white text-body">
					
						<header id="addData">			
							<h4 class="text-uppercase text-center mr-4 subtitle">Balance of your expences and incomes</h4>
						</header>
		
						<section>
					<h4 class="text-capitalize text-center subtitle">Revenue statement</h4>
					<div class="revenue">
						<div id="incomesTable">
						<table class="table table-sm balanceTable">
								<tr>
									<th>Category</th>
									<th>Amount in PLN</th>
								</tr>
							<?php
	
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		
	
		try
		{
			$connection = new mysqli($host, $db_user, $db_password, $db_name);
			$connection->set_charset("utf8");
			if ($connection->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				
				$user_id = $_SESSION['id'];
				$sql ="SELECT c.name, SUM(i.amount) FROM users u INNER JOIN incomes i ON u.id = i.user_id INNER JOIN incomes_category_assigned_to_users c ON i.income_category_assigned_to_user_id = c.id WHERE u.id = $user_id AND i.date_of_income >= '$start_date' AND  i.date_of_income <= '$end_date' GROUP BY c.id";
			
				$result=$connection->query($sql);
			
				if(!$result) throw new Exception($connection->error);
				
				$categories=$result->num_rows;
			
				if($categories>0)
				{
						
										echo '<tbody>'; 
											while ($row = $result->fetch_assoc())
											{
												echo '<tr>';
												$incomes_name = $row['name'];
												echo '<td>'.$incomes_name.'</td>'; 
												$incomes_category = $row['SUM(i.amount)'];
												echo '<td>'.$incomes_category.'</td>'; 
												echo '</tr>'; 
												$incomes_names = $incomes_names.'"'.$incomes_name.'",';
												$incomes_categories = $incomes_categories.'"'.$incomes_category.'",';	
												
											} 
											$result->free_result();
										echo '</tbody>'; 
		$incomes_names = trim($incomes_names, ",");
				$incomes_categories = trim($incomes_categories, ",");
				}

				else
				{
					echo '<h5>You have no incomes in selected period of time '.$start_date.' do '.$end_date.'</h5>';
				}			
			}
			$connection->close();

		}

		catch(Exception $e)
		{
			echo '<span style="color:red;">Server error! Please try again later.</span>';
		}		
?>
					<tr class="total">
								<td>Total</td>
								<?php
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		
		try
		{
			$connection = new mysqli($host, $db_user, $db_password, $db_name);
			$connection->set_charset("utf8");
			if ($connection->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				$user_id = $_SESSION['id'];
				$sql ="SELECT SUM(i.amount) FROM users u INNER JOIN incomes i ON u.id = i.user_id WHERE u.id = $user_id AND i.date_of_income >= '$start_date' AND  i.date_of_income <= '$end_date'";
			
				$result=$connection->query($sql);
			
				if(!$result) throw new Exception($connection->error);
				
				$how_many_rows=$result->num_rows;
			
				if($how_many_rows>0)
				{
													
					while ($row = $result->fetch_assoc())
					{
						$incomes_sum = $row['SUM(i.amount)'];
						echo "<td>" . $incomes_sum . "</td>";
					} 
					
					$result->free_result();		
				}		
			}
			$connection->close();
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Server error! Please try again later.</span>';
		}		
?>			
							</tr>
						</thead>
						</tbody>
					</table>
					

						</div>
					</div>
					<div class="revenue">
						<div class="chartContainer">
							<canvas id="incomeChart"></canvas> 
						</div>
						</div>
						<script>
						let incomesChart = document.getElementById('incomeChart').getContext('2d');

Chart.defaults.global.defaultFontColor = '#111';
Chart.defaults.global.defaultFontFamily = "'Lora', sans-serif";

let incomeChart = new Chart(incomesChart, {
    type: 'pie',
    data: {
        labels: [<?php echo $incomes_names; ?>],
        datasets: [{
            label: 'Incomes',
            data: [<?php echo $incomes_categories; ?>],
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
						</script>
									
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
							<?php
	
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		
		try
		{
			$connection = new mysqli($host, $db_user, $db_password, $db_name);
			$connection->set_charset("utf8");
			if ($connection->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				
				$user_id = $_SESSION['id'];
				$sql ="SELECT c.name, SUM(e.amount) FROM users u INNER JOIN expenses e ON u.id = e.user_id INNER JOIN expenses_category_assigned_to_users c ON e.expense_category_assigned_to_user_id = c.id WHERE u.id = $user_id AND e.date_of_expense >= '$start_date' AND  e.date_of_expense <= '$end_date' GROUP BY c.id";
			
				$result=$connection->query($sql);
			
				if(!$result) throw new Exception($connection->error);
				
				$categories=$result->num_rows;
			
				if($categories>0)
				{
						
										echo '<tbody>'; 
											while ($row = $result->fetch_assoc())
											{
												echo '<tr>'; 
												echo '<td>'.$row['name'].'</td>'; 
												echo '<td>'.$row['SUM(e.amount)'].'</td>'; 
												echo '</tr>'; 
											} 
											$result->free_result();
										echo '</tbody>'; 
									 	
				}
				else
				{
					echo '<h5>You have no incomes in selected period of time '.$start_date.' do '.$end_date.'</h5>';
				}			
			}
			$connection->close();
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Server error! Please try again later.</span>';
		}		
?>
					<tr class="total">
								<td>Total</td>
								<?php
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		
		try
		{
			$connection = new mysqli($host, $db_user, $db_password, $db_name);
			$connection->set_charset("utf8");
			if ($connection->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				$user_id = $_SESSION['id'];
				$sql ="SELECT SUM(e.amount) FROM users u INNER JOIN expenses e ON u.id = e.user_id WHERE u.id = $user_id AND e.date_of_expense >= '$start_date' AND  e.date_of_expense <= '$end_date'";
			
				$result=$connection->query($sql);
			
				if(!$result) throw new Exception($connection->error);
				
				$how_many_rows=$result->num_rows;
			
				if($how_many_rows>0)
				{
													
					while ($row = $result->fetch_assoc())
					{
						$expenses_sum = $row['SUM(e.amount)'];
						echo "<td>" . $expenses_sum . "</td>";
					} 
					
					$result->free_result();		
				}		
			}
			$connection->close();
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Server error! Please try again later.</span>';
		}		
?>			
							</tr>
						</thead>
						</tbody>
					</table>
						</div>
					</div>
					<div class="loss">
						<div class="chartContainer">
							<canvas id="expenseChart"></canvas>
						</div>
					</div>	
<script>
let expenesChart = document.getElementById('expenseChart').getContext('2d');

Chart.defaults.global.defaultFontColor = '#111';
Chart.defaults.global.defaultFontFamily = "'Lora', sans-serif";
let expenseChart = new Chart(expenesChart, {
    type: 'pie',
    data: {
        labels: ['Food', 'Housing costs', 'Transportation', 'Telecommunication', 'Hygiene', 'Travel', 'Education', 'Books', 'Another'],
        datasets: [{
            label: 'Expanses',
            data: [340.00, 650.00, 23.50, 23.65, 13.20, 120.00, 152.00, 54.00, 22.00],
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

</script>

					
					<div style="clear:both"></div>						
				</section>
				<br/>



				<?php
				$balance = $incomes_sum - $expenses_sum;
				
				if($balance < 0)
				{

					echo '<div class="bg-danger summary">';
					echo '<div id="summary">';
					echo '<h2 class="h3" style="color: red">Your balance is' . $balance . ' PLN</h2>';
echo<<<end
					<h2 class="h4 mb-4" style="color: red">Be careful, you run into debt!</h2>
					</div>
					</div>
end;
				}
				
				else
				{
					echo '<div class="bg-success summary">';
					echo '<div id="summary">';
					echo '<h2 class="h3">Your balance is: ' . $balance . ' PLN</h2>';
echo<<<end
					<h2 class="h4 mb-4">Congratulations. You manage great your finance</h2>
					</div>
					</div>
					
end;
				}
			
			?>
				</div>
			</div>
		</div>
	</article>
</main>		
	<br/>
	<footer class="footer">
	Copyright © 2020 Personal Budget All Rights Reserved Thank you for your visit!
	</footer>
	
	
	
</body>
</html>