<?php

	session_start();
	
	if (isset($_POST['email']))
	{
		//udana walidacja? Tak
		$is_OK=true;
		
		//Sprawdź username
		$name=$_POST['name'];
		
		
		// sprawdzanie długości username
		if((strlen($name)<3)||(strlen($name)>20))
		{
			$is_OK=false;
			$_SESSION['e_name']="Username has to consist of 3 to 20 characters";
		}
		
		if(ctype_alnum($name)==false)
		{
			$is_OK=false;
			$_SESSION['e_name']="Username has to consist only of alphanumeric characters";
		}
		
		//Sprawdź poprawność e-mail
		$email=$_POST['email'];
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		if((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false)||($emailB!=$email))
		{
			$is_OK=false;
			$_SESSION['e_email']="Please enter the correct e-mail address";
		}
		
		//Sprawdź poprawność hasła
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];
		
		if((strlen($password1)<8)||(strlen($password1)>20))
		{
			$is_OK=false;
			$_SESSION['e_password']="Password has to consist of 8 to 20 characters";
		}
		
		if($password1!=$password2)
		{
			$is_OK=false;
			$_SESSION['e_password']="Passwords are not the same";
		}
		
		$password_hash = password_hash($password1, PASSWORD_DEFAULT);
		
				
		//Bot or not?
		$secret = "6Lft0gQaAAAAAJc_X_r8WQ4uFSn5iS6QXHVIHoS3";
		$response = $_POST['g-recaptcha-response'];
		$remoteip = $_SERVER['REMOTE_ADDR'];
		$check=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response&remoteip=$remoteip");
		
		$response=json_decode($check);
		
		if($response->success==false)
		{
			$is_OK=false;
			$_SESSION['e_bot']="Please confirm that you are not a bot";
		}
		
		//Zapamiętaj wprowadzone dane
		$_SESSION['fr_name'] =$name;
		$_SESSION['fr_email'] =$email;
		$_SESSION['fr_password1'] =$password1;
		$_SESSION['fr_password2'] =$password2;
		
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		try
		{
			$connection = new mysqli($host, $db_user, $db_password, $db_name);
			if($connection->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{
				//Czy email juz istnieje
				$result = $connection->query("SELECT id FROM users WHERE email='$email'");
				
				if(!$result) throw new Exception($connection->error);
				
				$how_many_mails=$result->num_rows;
				if($how_many_mails>0)
				{
					$is_OK=false;
					$_SESSION['e_email']="The account registered with this e-mail address already exists";
				}
				
				//Czy name juz istnieje
				$result = $connection->query("SELECT id FROM users WHERE username='$name'");
				
				if(!$result) throw new Exception($connection->error);
				
				$how_many_names=$result->num_rows;
				if($how_many_names>0)
				{
					$is_OK=false;
					$_SESSION['e_name']="The account registered with this username already exists. Please choose another one";
				}
				
				if($is_OK==true)
				{
					//Wszystkie testy zaliczone dodajemy gracza do bazy
					if($connection->query("INSERT INTO users VALUES (NULL, '$name', '$password_hash', '$email')"))
					{
						if($connection->query("INSERT INTO expenses_category_assigned_to_users(user_id, name) SELECT u.id AS user_id, d.name FROM users AS u CROSS JOIN expenses_category_default AS d WHERE u.email='$email'"))
						{
							if($connection->query("INSERT INTO incomes_category_assigned_to_users(user_id, name) SELECT u.id AS user_id, d.name FROM users AS u CROSS JOIN incomes_category_default AS d WHERE u.email='$email'"))
							{
								if($connection->query("INSERT INTO payment_methods_assigned_to_users(user_id, name) SELECT u.id AS user_id, d.name FROM users AS u CROSS JOIN payment_methods_default AS d WHERE u.email='$email'"))
								{
									$_SESSION['successful_registration']= true;
									header('Location: welcome.php');
								}
								else
								{
									throw new Exception($connection->error);
								}	
							}
							else
							{
								throw new Exception($connection->error);
							}
							
						}
						else
						{
							throw new Exception($connection->error);
						}	
					}
					else
					{
						throw new Exception($connection->error);
					}
					
				}
				
				$connection->close();
				
			}
		}
		catch(Exception $e)
		{
			echo '<span class="error">Server error. Please try again later.</span>';
			//echo '<br />Detailed information: '.$e;
		}

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
	<script src='https://www.google.com/recaptcha/api.js'></script>
	
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
			<div id="login-column" class="offset-md-2 col-md-8 offset-md-2">
				<div id="login-box" class="col-md-12">
					<form id="login-form" class="form" method="post">
						<h2 class="text-center text-dark">Create Account</h2>
						<div class="form-group">
							<label for="username" class="text-dark">Username:</label><br>
							<input type="text" name="name" id="username" class="form-control text-muted" value="<?php
								if(isset($_SESSION['fr_nane']))
								{
									echo $_SESSION['fr_name'];
									unset($_SESSION['fr_name']);
									
								}
								?>" placeholder="Jonh.D">
								
								<?php
									if(isset($_SESSION['e_name']))
									{
										echo '<div id="error">'.$_SESSION['e_name'].'</div>';
										unset($_SESSION['e_name']);
									}
								?>
						</div>
						<div class="form-group">
							<label for="email" class="text-dark">E-mail:</label><br>
							<input type="text" name="email" id="email" class="form-control text-muted" value="<?php
								if(isset($_SESSION['fr_email']))
								{
									echo $_SESSION['fr_email'];
									unset($_SESSION['fr_email']);
									
								}
								?>" placeholder="john.doe@gmail.com">
								
								<?php
									if(isset($_SESSION['e_email']))
									{
										echo '<div id="error">'.$_SESSION['e_email'].'</div>';
										unset($_SESSION['e_email']);
									}
								?>
						</div>
						<div class="form-group">
							<label for="password" class="text-dark">Password:</label><br>
							<input type="password" name="password1" id="password" class="form-control text-muted" value="<?php
								if(isset($_SESSION['fr_password1']))
								{
									echo $_SESSION['fr_password1'];
									unset($_SESSION['fr_password1']);
									
								}
								?>" placeholder="At least 8 characters">
								
								<?php
									if(isset($_SESSION['e_password']))
									{
										echo '<div id="error">'.$_SESSION['e_password'].'</div>';
										unset($_SESSION['e_password']);
									}
								?>
						</div>
						<div class="form-group">
							<label for="password" class="text-dark">Confirm password:</label><br>
							<input type="password" name="password2" id="password" class="form-control text-muted" value="<?php
								if(isset($_SESSION['fr_password2']))
								{
									echo $_SESSION['fr_password2'];
									unset($_SESSION['fr_password2']);
									
								}
								?>" placeholder="At least 8 characters">
						</div>
						
						<div class="g-recaptcha" data-sitekey="6Lft0gQaAAAAAHCfBDi0AUmM5BtLwKJNSw-z8G_q"></div>
		
						<?php
							if(isset($_SESSION['e_bot']))
							{
								echo '<div id="error">'.$_SESSION['e_bot'].'</div>';
								unset($_SESSION['e_bot']);
							}
						?>
						<br/>
						<div class="form-group">
						<button class="btn btn-lg btn-block btn-login text-uppercase mb-2" type="submit">Register</button>
						<div class="text-center">
							<a class="small text-dark" href="index.php">Already have an account? Sign in</a>
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