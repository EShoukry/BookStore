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
	phpAlert("Adding Address *beep* *b00p*");
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


        <div id=main_image>		
            <img src="images/index.jpeg" alt="Team 7 book store" >
        </div>  

	<?php
        require "includes/navbar_user.php";
    ?>
	
	<div id="wrapper">
	<div class="container">
    
    <div class="page-header">
    <div class=section_title><h3>Add New Address</h3></div>
    </div>
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
			  <input class="form-check-input" type="checkbox" value="" id="primaryCheck">
			  <label class="form-check-label" for="primaryCheck">
				New Priamry Address?
			  <label>
			</div>

			<div class="btn-group">
			<button type="reset"  name="clear" class="btn btn-warning" Style="width: 200px;"/>Clear</button>
			<button type="submit" name="addbtn" class="btn btn-primary" Style="width: 200px;"/>Add Address</button>
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

