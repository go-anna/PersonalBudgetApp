<?php

	session_start();
	
	if(!isset($_SESSION['login']))
	{
		header ('Location: index.php');
		exit();
	}
	
	if(isset($_SESSION['formPeriodOfTime']))
	{
		$allGood = true;
		$periodOfTime=$_SESSION['formPeriodOfTime'];
		$now = date('Y-m-d');
		
		if($periodOfTime == "currentMonth")
		{
			$startDate = date('Y-m-d',strtotime("first day of this month"));
			$endDate = date('Y-m-d',strtotime("now"));
		}
		else if($periodOfTime == "previousMonth")
		{
			$startDate = date('Y-m-d',strtotime("first day of previous month"));
			$endDate = date('Y-m-d',strtotime("last day of previous month"));
		}
		else if($periodOfTime == "currentYear")
		{
			$startDate = date('Y-m-d',strtotime("1 January this year"));
			$endDate = date('Y-m-d',strtotime("now"));
		}
		else if($periodOfTime == "selectedPeriod")
		{
			$startDate = $_SESSION['periodStartDate'];
			$endDate = $_SESSION['periodEndDate'];	
		}
	}
	
	
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<title>Przeglądaj bilans</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Przeglądaj bilans. Aplikacja do prowadzenie budżetu osobistego." />
	<meta name="keywords" content="budżet, osobisty, domowy" />
	<meta name="author" content="Monika Burek">
		
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">	
	<link rel="stylesheet" href="style.css" type="text/css"/>
	<link rel="stylesheet" href="css/fontello.css" type="text/css"/>
	<link href="https://fonts.googleapis.com/css?family=Lato:400,700&amp;subset=latin-ext" rel="stylesheet">

	
</head>

<body onload="createPieChart()">
	<article class="articleFourElements">
		<header>
			<div class="jumbotron">
				<div class="container text-center">
					<h2><span class="colorIcon"><i class="icon-money"></i></span>
					Aplikacja do prowadzenia budżetu domowego!</h2>   				
				</div>
			</div>
		</header>
		
		<nav class="navbar navbar-default navbarProperties">
			<div class="container text-center">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>                        
					</button>
				</div>
				
				<div class="collapse navbar-collapse" id="myNavbar">
					<ul class="nav navbar-nav">
						<li><a href="home.php">Strona główna</a></li>
						<li><a href="addIncome.php">Dodaj przychód</a></li>
						<li><a href="addExpense.php">Dodaj wydatek</a></li>
						<li class="active"><a href="viewBalance.php">Przeglądaj bilans</a></li>
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">Ustawienia <span class="caret"></span></a>
								<ul class="dropdown-menu">
									<li><a href="#">Zmień dane</a></li>
									<li><a href="#">Zmień kategorie</a></li>
									<li><a href="#">Usuń ostatnie wpisy</a></li>
								</ul>
						</li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Wyloguj się</a></li>
					</ul>
				</div>
			</div>

		</nav>
		
		
		
		<main>
			<div class="container"> 
				<div class="row text-center">
					<div class="col-md-4 col-md-offset-2 bg3">	
							
<?php
	//Connect database
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
				//Check number of income categories
				$userId = $_SESSION['id'];
				$sql ="SELECT c.name, SUM(i.amount) FROM users u INNER JOIN incomes i ON u.id = i.user_id INNER JOIN incomes_category_assigned_to_users c ON i.income_category_assigned_to_user_id = c.id WHERE u.id = $userId AND i.date_of_income >= '$startDate' AND  i.date_of_income <= '$endDate' GROUP BY c.id";
			
				$resultOfQuery=$connection->query($sql);
			
				if(!$resultOfQuery) throw new Exception($connection->error);
				
				$howCategory=$resultOfQuery->num_rows;
			
				if($howCategory>0)
				{
						echo '<article>';
							echo '<h4>Zestawienie przychodów dla poszczególnych kategorii w okresie od '.$startDate.' do '.$endDate.'</h4>';
										
							echo '<div class="table-responsive text-left">';          
								echo '<div class="table-responsive">';         
									echo '<table class="table table-striped table-bordered table-condensed">'; 
										echo '<thead>'; 
											 echo '<tr>'; 
												echo '<th>Nazwa kategorii</th>'; 
												echo '<th>Suma przychodów [zł]</th>'; 
											echo '</tr>'; 
										echo '</thead>'; 
										echo '<tbody>'; 
											while ($row = $resultOfQuery->fetch_assoc())
											{
												echo '<tr>'; 
												echo '<td>'.$row['name'].'</td>'; 
												echo '<td>'.$row['SUM(i.amount)'].'</td>'; 
												echo '</tr>'; 
											} 
											$resultOfQuery->free_result();
										echo '</tbody>'; 
									echo '</table>'; 
								echo '</div>'; 
							echo '</div>'; 							
						echo '</article>'; 	
				}
				else
				{
					echo '<h4 class="bilansHeader">Brak przychodów w okresie od '.$startDate.' do '.$endDate.'</h4>';
				}			
			}
			$connection->close();
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
			echo '<br />Informacja developerska: '.$e;
		}		
?>
					</div>
					<div class="col-md-1"></div>
					<div class="col-md-4 bg3">
					
<?php
	//Connect database
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
				//Check number of income categories
				$userId = $_SESSION['id'];
				$sql ="SELECT c.name, SUM(e.amount) FROM users u INNER JOIN expenses e ON u.id = e.user_id INNER JOIN expenses_category_assigned_to_users c ON e.expense_category_assigned_to_user_id = c.id WHERE u.id = $userId AND e.date_of_expense >= '$startDate' AND  e.date_of_expense <= '$endDate' GROUP BY c.id";
			
				$resultOfQuery=$connection->query($sql);
			
				if(!$resultOfQuery) throw new Exception($connection->error);
				
				$howCategory=$resultOfQuery->num_rows;
			
				if($howCategory>0)
				{
					$noExpenses = false;
						echo '<article>';
							echo '<h4>Zestawienie wydatków dla poszczególnych kategorii w okresie od '.$startDate.' do '.$endDate.'</h4>';
										
							echo '<div class="table-responsive text-left">';          
								echo '<div class="table-responsive">';         
									echo '<table class="table table-striped table-bordered table-condensed">'; 
										echo '<thead>'; 
											 echo '<tr>'; 
												echo '<th>Nazwa kategorii</th>'; 
												echo '<th>Suma przychodów [zł]</th>'; 
											echo '</tr>'; 
										echo '</thead>'; 
										echo '<tbody>';
										$i = 0;								
											while ($row = $resultOfQuery->fetch_assoc())
											{
												echo '<tr>'; 
												echo '<td>'.$row['name'].'</td>';
												$dataPoints[$i]["label"]= $row['name'];
												echo '<td>'.$row['SUM(e.amount)'].'</td>';
												$dataPoints[$i]["y"]= $row['SUM(e.amount)'];
												echo '</tr>'; 
												$i=$i+1;
											} 
											$resultOfQuery->free_result();
										echo '</tbody>'; 
									echo '</table>'; 
								echo '</div>'; 
							echo '</div>'; 							
						echo '</article>'; 	
				}
				else
				{
					echo '<h4 class="bilansHeader">Brak wydatków w okresie od '.$startDate.' do '.$endDate.'</h4>';
					$noExpenses = true;
					
				
				}			
			}
			$connection->close();
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
			echo '<br />Informacja developerska: '.$e;
		}		
?>					
					
					
					</div>	
					<div class="col-md-1"></div>
				</div>

				<div class="row emptyRow"></div>
								
				<div class="row ">
					<div class="col-md-6  col-sm-12 col-md-offset-3 ">
							
								<script>
function createPieChart () 
{
	var chart = new CanvasJS.Chart("chartContainer", {
				exportEnabled: true,
				animationEnabled: true,
				title:{
					text: "Zestawienie wydatków w danym okresie."
				},
				legend:{
					cursor: "pointer",
					itemclick: explodePie
				},
				data: [{
					type: "pie",
					showInLegend: "true",
					legendText: "{label}",
					indexLabelFontSize: 16,
					indexLabel: "{label} (#percent%)",
					dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
				}]
			});
			
	chart.render();
	chart.title.set("fontSize", 24);
	chart.title.set("fontColor", "#092834", false);
	chart.legend.set("fontSize", 16);
}

function explodePie (e) 
{
			if(typeof (e.dataSeries.dataPoints[e.dataPointIndex].exploded) === "undefined" || !e.dataSeries.dataPoints[e.dataPointIndex].exploded) {
				e.dataSeries.dataPoints[e.dataPointIndex].exploded = true;
			} else {
				e.dataSeries.dataPoints[e.dataPointIndex].exploded = false;
			}
			e.chart.render();

}
	</script>	
							
<?php		
						if ($noExpenses == false)
						echo '<div id="chartContainer"></div>';
?>
							
						</div>
						<div class="col-md-3"></div>
				</div>
					
                <div class="row emptyRow"></div>				
				
				<div class="row ">
						<div class="col-md-6 col-md-offset-3 bg7">
							<div class="col-md-5">Suma przychodów [zł]:</div>
							<div class="col-md-4">
							
								<div class="well well-sm wellResult">
								
<?php
	//Connect database
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
				//SUM incomes
				$userId = $_SESSION['id'];
				$sql ="SELECT SUM(i.amount) FROM users u INNER JOIN incomes i ON u.id = i.user_id WHERE u.id = $userId AND i.date_of_income >= '$startDate' AND  i.date_of_income <= '$endDate'";
			
				$resultOfQuery=$connection->query($sql);
			
				if(!$resultOfQuery) throw new Exception($connection->error);
				
				$howRecords=$resultOfQuery->num_rows;
			
				if($howRecords>0)
				{
													
					while ($row = $resultOfQuery->fetch_assoc())
					{
						echo $row['SUM(i.amount)'];	
						$sumIncomes = $row['SUM(i.amount)'];
					} 
					
					$resultOfQuery->free_result();		
				}		
			}
			$connection->close();
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
			echo '<br />Informacja developerska: '.$e;
		}		
?>	
										
								 </div>
							</div>	
							<div class="col-md-3"></div>	
						</div>
						<div class="col-md-3"></div>
					</div>
					
					<div class="row ">
						<div class="col-md-6 col-md-offset-3 bg7 ">
							<div class="col-md-5">Suma wydatków [zł]:</div>
							<div class="col-md-4">
							
								<div class="well well-sm wellResult">
<?php

	//Connect database
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
				//Sum expenses
				$userId = $_SESSION['id'];
				$sql ="SELECT SUM(e.amount) FROM users u INNER JOIN expenses e ON u.id = e.user_id WHERE u.id = $userId AND e.date_of_expense >= '$startDate' AND  e.date_of_expense <= '$endDate'";
			
				$resultOfQuery=$connection->query($sql);
			
				if(!$resultOfQuery) throw new Exception($connection->error);
				
				$howRecords=$resultOfQuery->num_rows;
			
				if($howRecords>0)
				{
													
					while ($row = $resultOfQuery->fetch_assoc())
					{
						echo $row['SUM(e.amount)'];
						$sumExpenses = $row['SUM(e.amount)'];
						
					} 
					$resultOfQuery->free_result();		
				}
				
							
			}
			$connection->close();
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
			echo '<br />Informacja developerska: '.$e;
		}		
?>												
								
								</div>
							</div>	
							<div class="col-md-3"></div>	
						</div>
						<div class="col-md-3"></div>
					</div>
					
					<div class="row ">
						<div class="col-md-6 col-md-offset-3 bg7 ">
							<div class="col-md-3 col-md-offset-2">Różnica:</div>
							<div class="col-md-3">
							
								<div class="well well-sm wellFinalResult">
									<div id="differenceNumber">
									<?php
									$difference = $sumIncomes - $sumExpenses;
									echo number_format($difference,2,'.', '');
									?>
									</div>
								</div>
							</div>
							
							<div class="col-md-4 ">
								 
							</div>	
						</div>
						<div class="col-md-3"> 
						
						</div>
					</div>
					
					<div class="row ">
						<div class="col-md-6 col-md-offset-3 bg7">
							<div class="col-md-6 col-md-offset-3 bg8">
								<div id="differenceText"></div>
								<button class="btnSave" onclick="displayText()">Sprawdź czy dobrze zarządzasz finasami?</button>
								<script src="js/functionDisplayText.js"></script>
							</div>
							<div class="col-md-3 "></div>
						</div>	
						<div class="col-md-3 "></div>	
					</div>
					
					<div class="row emptyRow"></div>	
				
				
			</div>
		</main>
		
		
		<footer class="container-fluid text-center">
			
			Wszystkie prawa zastrzeżone &copy; 2018  Dziękuję za wizytę!
		</footer>
		
	</article>	
		 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		 <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	
</body>
</html>