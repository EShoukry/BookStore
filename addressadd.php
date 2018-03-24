<?php
ob_start();
session_start();
$database = include('config.php');

function phpAlert($msg) {
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Create connection
$mysqli = new mysqli($database['host'], $database['user'], $database['pass'], $database['name']);

// Check connection
if (mysqli_connect_error()) {
    die("Database connection failed: " . mysqli_connect_error());
}



if (isset($_POST['addbtn'])) {
	$error = false;
	//Verify Input
	$firstname = trim($_POST['firstname']);
    $firstname = strip_tags($firstname);
    $firstname = htmlspecialchars($firstname);

    $lastname = trim($_POST['lastname']);
    $lastname = strip_tags($lastname);
    $lastname = htmlspecialchars($lastname);
	
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

	$primaryAdd = (isset($_POST['primaryCheck']) ? 1 : 0);


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
	//add address information as primary(only) address upon session set.
		if(!$error){
				$query = "INSERT INTO address(user_id, p_address, fname, lname, line1, line2, city, state, zip, country) 
								VALUES('" . $_SESSION['user'] . "', '$primaryAdd','$firstname','$lastname','$address','$address2', '$city', '$state', '$zipcode', '$country')";
				$res = mysqli_query($mysqli, $query);
				if ($res) {
					$errTyp = "success";
					$errMSG = "Address Inserted Successfully!" . "\n" . $primaryAdd;
					unset($primaryAdd);
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
					$errMSG = $errMSG . "Error In Address Insert, Please Enter Try Again...";
				}

        } else {
            $errTyp = "danger";
            $errMSG = "Something went wrong, try again later...";
        }
    }



?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">    
        <title>Add/Edit Addresses</title>
        <meta http-equiv="content-type" content="text/plain">
        <link rel="stylesheet" type="text/css" href="css/styles.css">
		<!-- BootStrap Import from CDN-->
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
    
	<?php
        require "includes/navbar_user.php";
    ?>

    <div class="page-header">
    <div class=section_title><h3>Add New Address</h3></div>
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

		<div class="form-group ">
		<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" autocomplete="off">

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


			<div class="input-group text-center">

			<div class="form-check text-right">
			  <input class="form-check-input" name="primaryCheck" type="checkbox" value="1" id="primaryCheck">
			  <label class="form-check-label" for="primaryCheck">
				New Priamry Address?
			  <label>
			</div>

			<div class="btn-group" Style="margin-bottom: 5px;">
			<button type="reset"  name="clear" class="btn btn-warning" Style="width: 200px;"/>Clear</button>
			<button type="submit" name="addbtn" class="btn btn-primary" Style="width: 200px;"/>Add Address</button>
			</div>

			<div class="btn-group">
				<a href="manageaddress.php" class="btn btn-secondary" Style="width: 400px;" />View Your Addresses</a>
			</div>
			
			</div>




		</form>
		</div>



    </div>
    </div>


</body>
<?php
require "footer.php";
echo "</html>";
mysqli_close($mysqli);
ob_end_flush();
?>

