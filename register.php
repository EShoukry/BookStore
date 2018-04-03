<?php
ob_start();
session_start();
if (isset($_SESSION['user']) != "") {
    header("Location: home.php");
}
$database = include('config.php');

function phpAlert($msg) {
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}

// Create connection
$mysqli = new mysqli($database['host'], $database['user'], $database['pass'], $database['name']);

// Check connection
if (mysqli_connect_error()) {
    die("Database connection failed: " . mysqli_connect_error());
}

$error = false;

if (isset($_POST['regbtn'])) {

    // clean user inputs to prevent sql injections
    $username = trim($_POST['username']);
    $username = strip_tags($username);
    $username = htmlspecialchars($username);

    $firstname = trim($_POST['firstname']);
    $firstname = strip_tags($firstname);
    $firstname = htmlspecialchars($firstname);

    $lastname = trim($_POST['lastname']);
    $lastname = strip_tags($lastname);
    $lastname = htmlspecialchars($lastname);

    $nickname = trim($_POST['nickname']);
    $nickname = strip_tags($nickname);
    $nickname = htmlspecialchars($nickname);

    $email = trim($_POST['email']);
    $email = strip_tags($email);
    $email = htmlspecialchars($email);

    $password = trim($_POST['password']);
    $password = strip_tags($password);
    $password = htmlspecialchars($password);

    $confpassword = trim($_POST['confpassword']);
    $confpassword = strip_tags($confpassword);
    $confpassword = htmlspecialchars($confpassword);

	
    $address = trim($_POST['address1']);
    $address = strip_tags($address);
    $address = htmlspecialchars($address);

	$address2 = trim($_POST['address2']);
    $address2 = strip_tags($address2);
    $address2 = htmlspecialchars($address2);

    $city = trim($_POST['city']);
    $city = strip_tags($city);
    $city = htmlspecialchars($city);

    $state = trim($_POST['state']);
    $state = strip_tags($state);
    $state = htmlspecialchars($state);

    $zipcode = trim($_POST['zipcode']);
    $zipcode = strip_tags($zipcode);
    $zipcode = htmlspecialchars($zipcode);

    $country = trim($_POST['country']);
    $country = strip_tags($country);
    $country = htmlspecialchars($country);
	

    // basic username validation
    if (empty($username)) {
        $error = true;
        $usernameError = "Please enter your desired Username.";
    } else if (strlen($username) < 3) {
        $error = true;
        $usernameError = "Username must have atleat 3 characters.";
    } else if (!ctype_alnum($username)) {
        $error = true;
        $usernameError = "Username must contain alphabets and/or Numbers.";
    } else {
        // check username exist or not
        $query = "SELECT u_login_id FROM users WHERE u_login_id='$username'";
        $result = (mysqli_query($mysqli, $query));
        $count = mysqli_num_rows($result);
        if ($count != 0) {
            $error = true;
            $usernameError = "Provided Username is already in use.";
        }
    }

    if (empty($firstname)) {
        $error = true;
        $firstnameError = "Please enter your first name.";
    } else if (strlen($firstname) < 3) {
        $error = true;
        $firstnameError = "First name must have atleat 3 characters.";
    } else if (!ctype_alpha($firstname)) {
        $error = true;
        $firstnameError = "First name must contain alphabets.";
    }

    if (empty($lastname)) {
        $error = true;
        $lastnameError = "Please enter your last name.";
    } else if (strlen($lastname) < 3) {
        $error = true;
        $lastnameError = "Last name must have atleat 3 characters.";
    } else if (!ctype_alpha($lastname)) {
        $error = true;
        $firstnameError = "Last name must contain alphabets.";
    }

    if (empty($nickname)) {
        $error = true;
        $nicknameError = "Please enter your Nick name.";
    } else if (strlen($nickname) < 3) {
        $error = true;
        $nicknameError = "Nick name must have atleat 3 characters.";
    } else if (!ctype_alnum($nickname)) {
        $error = true;
        $nicknameError = "Nick name must contain alphabets and/or Numbers.";
    }

//    //basic email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $emailError = "Please enter valid email address.";
    } else {
        // check email exist or not
        $query = "SELECT u_email FROM users WHERE u_email='$email'";
        $result = (mysqli_query($mysqli, $query));
        $count = mysqli_num_rows($result);
        if ($count != 0) {
            $error = true;
            $emailError = "Provided Email is already in use.";
        }
    }

	
    if (empty($address)) {
        $error = true;
        $addressError = "Please enter your Street Address, P.O. Box, Company Name, C/O.";
    } else if (strlen($address) < 3) {
        $error = true;
        $addressError = "Address must have atleat 3 characters.";
    }


	if (strlen($address2)>0 && strlen($address2) < 3) {
        $error = true;
        $address2Error = "Apt, Suite, Unit, Floor, etc. must have atleat 3 characters.";
    }

    if (empty($city)) {
        $error = true;
        $cityError = "Please enter your City.";
    } else if (strlen($city) < 3) {
        $error = true;
        $cityError = "City must have atleat 3 characters.";
    } else if (!ctype_alpha($city)) {
        $error = true;
        $cityError = "City must contain alphabets.";
    }

    if (empty($state)) {
        $error = true;
        $stateError = "Please enter your State/Province/Region.";
    } else if (!ctype_alnum($state)) {
        $error = true;
        $stateError = "State/Province/Region must contain Alphanumericals only.";
    }
    $state = strtoupper($state);

    if (empty($zipcode)) {
        $error = true;
        $zipError = "Please enter your Zip/Postal Code.";
    } else if (strlen($zipcode) > 15) {
        $error = true;
        $zipError = "Zip/Postal Code must be less than 15 Alphanumericals.";
    } else if (!ctype_alnum($zipcode)) {
        $error = true;
        $zipError = "Zip/Postal Code must contain Alphanumericals only.";
    }

	if (empty($country)) {
        $error = true;
        $countryError = "Please enter your Country.";
    } else if (strlen($country) > 50) {
        $error = true;
        $countryError = "Country must be less than 15 Alphanumericals.";
    } 
	


    // password validation
    if (empty($password)) {
        $error = true;
        $passwordError = "Please enter password.";
    } else if (strlen($password) < 6) {
        $error = true;
        $passwordError = "Password must have atleast 6 characters.";
    } else if ($password != $confpassword) {
        $error = true;
        $passwordError = "Passwords do not match.";
    }

    // password encrypt using md5();
    $password = password_hash($password, PASSWORD_DEFAULT);

    // if there's no error, continue to signup
    if (!$error) {

        $query = "INSERT INTO users(u_fname,u_login_id,u_password,u_email,u_nick,u_lname) VALUES('$firstname','$username','$password','$email','$nickname','$lastname')";
        $res = mysqli_query($mysqli, $query);

        if ($res) {
            $errTyp = "success";
			$errMSG = "Registered Successfully!" . "\n";

            unset($username);
            unset($nickname);

			$query = "SELECT user_id_number, u_password, u_email FROM users WHERE u_email = '$email' AND u_password = '$password'";
			$res = mysqli_query($mysqli, $query);
			$row = mysqli_fetch_array($res, MYSQLI_BOTH);
			$count = mysqli_num_rows($res);
			

			unset($email);
            unset($password);

			//set session after registering
			if ($count == 1) {
				$_SESSION['user'] = $row['user_id_number'];
				$errMSG = $errMSG . "Login Successful! User ID: " . $_SESSION['user'] . "\n";

			} else {
				$error = true;
				$errTyp = "danger";
				$errMSG = $errMSG . "Error In Login, Try again...";
			}

			//add address information as primary(only) address upon session set.
			if(!$error){
				$query = "INSERT INTO address(user_id, p_address, fname, lname, line1, line2, city, state, zip, country) 
								VALUES('" . $_SESSION['user'] . "','1','$firstname','$lastname','$address','$address2', '$city', '$state', '$zipcode', '$country')";
				$res = mysqli_query($mysqli, $query);
				if ($res) {

					$errMSG = $errMSG . "Address Inserted Successfully!" . "\n";
					unset($firstname);
					unset($lastname);
					unset($address);
					unset($address2);
					unset($city);
					unset($state);
					unset($zipcode);
					unset($country);

				}else{
					$error = true;
					$errTyp = "danger";
					$errMSG = $errMSG . "Error In Address Insert, Please Enter Address Manually...";
				}

			}				
            



			
        } else {
            $errTyp = "danger";
            $errMSG = "Something went wrong, try again later...";
        }
    }
}
?>



<!doctype html>
<html>
    <head>
        <meta charset="utf-8">    
        <title>Register </title>
        <meta http-equiv="content-type" content="text/plain">
        <link rel="stylesheet" type="text/css" href="css/styles.css">
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    </head>
    <body>
        <?php
        require "header.php";
        ?>
		<div class="wrapper backAsImg">
		<div class="container userContainer">

		
		
			<div id="login-form">
				<form class="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data" autocomplete="off">
					<div class="col-lg-12 text-center">
						<div class="form-group">
							<div class=section_title>
								<h3 style="text-center">Register A New Account</h3>
							</div>
						</div>

						<div class="form-group">
							<hr />
						</div>
					
						<?php
							if ( isset($errMSG) ) {
						
								?>

								<div class="form-group">
								<div class="input-group">
            					<div class="alert alert-<?php echo ($errTyp=="success") ? "success" : $errTyp; ?>">
								<span class="glyphicon glyphicon-info-sign"></span> <?php echo nl2br ($errMSG); ?>
								</div>
            					</div>
								</div>
								<?php
							}
							?>



							<div class="form-group" style="width:100%">
							<div class="col-sm-12 text-left">
							
									<div class="input-group">
									
										<input type="text" placeholder="First Name" name="firstname" class="form-control" style="width:50%" maxlength="50" value="<?php
										if (isset($firstname)) {
											echo $firstname;
										}
										?>"  />
								
										
									
										
										<input type="text" placeholder="Last Name" name="lastname" class="form-control" style="width:50%" maxlength="50" value="<?php
										if (isset($lastname)) {
											echo $lastname;
										}
										?>"  />
										
										<br<span class="text-danger"><?php
										if (isset($firstnameError)) {
											echo $firstnameError;
										}
										?></span>
										<span class="text-danger"><?php
										if (isset($lastnameError)) {
											echo $lastnameError;
										}
										?></span>
									</div>
									
						
									<div class="input-group">
									<input type="text" placeholder="User Name" name="username" class="form-control" maxlength="50" value="<?php
									if (isset($username)) {
										echo $username;
									}
									?>"  />
									<br><span class="text-danger"><?php
									if (isset($usernameError)) {
										echo $usernameError;
									}
									?></span>
									</div>
						
									<div class="input-group">
									<input type="text" placeholder="Nick Name" name="nickname" class="form-control" maxlength="50" value="<?php
									if (isset($nickname)) {
										echo $nickname;
									}
									?>"  />
						
									<br><span class="text-danger"><?php
									if (isset($nicknameError)) {
										echo $nicknameError;
									}
									?></span>
									</div>
									<div class="input-group">
									<input type="email" placeholder="Email" name="email" class="form-control" maxlength="50" value="<?php
									if (isset($email)) {
										echo $email;
									}
									?>"  />
						
									<br><span class="text-danger"><?php
									if (isset($emailError)) {
										echo $emailError;
									}
									?></span>
									</div>
									<div class="input-group">
									<input type="password" placeholder="Password" name="password" class="form-control" maxlength="50" autocomplete="new-password" />
						
									<br><span class="text-danger"><?php
									if (isset($passwordError)) {
										echo $passwordError;
									}
									?></span>
									</div>
									<div class="input-group">
									<input type="password" placeholder="Confirm Password" name="confpassword" class="form-control" maxlength="50" autocomplete="new-password"   />
						
									</div>
									<div class="input-group">
									<hr />
									</div>
							</div>
							</div>
						<div class="form-group" style="width:100%">
						<div class="col-sm-12 text-left">
						<h4 class="text-center">Permanent Address</h4>
							<div class="input-group">
								<input type="text" placeholder="Street Address, P.O. Box, Company Name, C/O" name="address1" class="form-control" maxlength="50" />
						
								<br><span class="text-danger"><?php
								if (isset($addressError)) {
									echo $addressError;
								}
								?></span>
							</div>
							<div class="input-group">
								<input type="text" placeholder="Apt, Suite, Unit, Floor, etc." name="address2" class="form-control" maxlength="50" />
						
								<br><span class="text-danger"><?php
								if (isset($address2Error)) {
									echo $address2Error;
								}
								?></span>
							</div>
							<div class="input-group">

								<input type="text" placeholder="City" name="city" class="form-control" Style="width: 50%;" maxlength="50" value="<?php
								if (isset($city)) {
									echo $city;
								}
								?>" />

								<input type="text" placeholder="State/Province/Region" name="state" Style="width: 50%;" class="form-control" value="<?php
								if (isset($state)) {
									echo $state;
								}
								?>" />

								<br><span class="text-danger"><?php
								if (isset($cityError)) {
									echo $cityError;
								}
								?></span>
								
								<span class="text-danger"><?php
								if (isset($stateError)) {
									echo $stateError;
								}
								?></span>
							</div>
							<div class="input-group">
								<input type="text" placeholder="Zip/Poastal Code" name="zipcode" class="form-control" Style="width: 50%;" value="<?php
								if (isset($zipcode)) {
									echo $zipcode;
								}
								?>" />
								<input type="text" placeholder="Country" name="country" class="form-control" Style="width: 50%;" maxlength="50" />
								
								<br><span class="text-danger" ><?php
								if (isset($zipError)) {
									echo $zipError;
								}
								?></span>
								<span class="text-danger "><?php
								if (isset($countryError)) {
									echo $countryError;
								}
								?></span>
							</div>
						<br />
						</div>
						</div>
					

					<div class="form-group text-center">

					<div class="btn-group" Style="margin-bottom: 5px;">
					<button type="submit" name="regbtn" class="btn btn-primary " />Register</button>
					<button type="reset"  name="clear" class="btn btn-warning " />Clear</button>
					</div>
					<br>
					<div class="btn-group">
					<a href="login.php" class="btn btn-secondary btn-responsive"/>Have An Account? Sign In Now</a>
					</div>



					</div>
					<hr />

				
					</div>
					</div>
				</form>
			</div>
		</div>
		</div>

        <div id="end_body"></div>  
    </body>


    <?php
    require "footer.php";
    echo "</html>";
    mysqli_close($mysqli);
    ob_end_flush();
    ?>

