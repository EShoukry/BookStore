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
} else{
	$user_id = $_SESSION['user'];
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
	$add_id = $_POST['add_id'];
	$p_CC = (isset($_POST['primaryCheck']) ? 1 : 0);

    $cc_title = trim($_POST['cc_title']);
    $cc_title = strip_tags($cc_title);
    $cc_title = htmlspecialchars($cc_title);
	
    $cc_cvv = trim($_POST['cc_cvv']);
    $cc_cvv = strip_tags($cc_cvv);
    $cc_cvv = htmlspecialchars($cc_cvv);

	$cc_expmm = trim($_POST['expmm']);
    $cc_expmm = strip_tags($cc_expmm);
    $cc_expmm = htmlspecialchars($cc_expmm);

    $cc_expyy = trim($_POST['expyy']);
    $cc_expyy = strip_tags($cc_expyy);
    $cc_expyy = htmlspecialchars($cc_expyy);

    $cc_num = trim($_POST['cc_num']);
    $cc_num = strip_tags($cc_num);
    $cc_num = htmlspecialchars($cc_num);

	$cc_four = substr($cc_num, -4);


	if (empty($cc_title)) {
        $error = true;
        $cc_titleError = "Please enter a title for this card.";
    } else if (strlen($cc_title) < 3) {
        $error = true;
        $cc_titleError = "Title name must have atleat 3 characters.";
    }

	if (empty($cc_num)) {
        $error = true;
        $cc_numError = "Please enter your CCV.";
    } else if (strlen($cc_num) < 16) {
        $error = true;
        $cc_numError = "CC must have atleast 16 characters.";
    }

	if (empty($cc_cvv)) {
        $error = true;
        $cc_cvvError = "Please enter your CC Number.";
    } else if (strlen($cc_cvv) != 3) {
        $error = true;
        $cc_cvvError = "CVV name must have 3 characters.";
    }





	//add address information as primary(only) address upon session set.
		if(!$error){
				$query = "INSERT INTO credit_card(user_id, add_id, p_CC, CC_title, CC_secure_code, CC_expmm, CC_expyy, CC_number, CC_four) 
								VALUES('$user_id', '$add_id', '$p_CC','$cc_title','$cc_cvv','$cc_expmm','$cc_expyy', '$cc_num', '$cc_four')";
				$res = mysqli_query($mysqli, $query);
				if ($res) {
					$errTyp = "success";
					$errMSG = "Credit Card Inserted Successfully!" . "\n";
					unset($add_id);
					unset($p_CC);
					unset($cc_title);
					unset($cc_cvv);
					unset($cc_expmm);
					unset($cc_expyy);
					unset($cc_num);
					unset($cc_four);

				}else{
					$error = true;
					$errTyp = "danger";
					$errMSG = $errMSG . "Error In CC Insert, Please Try Again...";
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
        <title>Add Credit Card</title>
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
	
	<div id="wrapper">
	<div class="container">
    
	<?php
        require "includes/navbar_user.php";
    ?>

    <div class="page-header">
    <div class=section_title><h3>Add New Credit Card</h3></div>
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
						<input type="text" placeholder="Payment Title" name="cc_title" class="form-control" maxlength="50" />
						
						<br><span class="text-danger"><?php
						if (isset($cc_titleError)) {
							echo $cc_titleError;
						}
						?></span>
					</div>

					<div class="input-group">
						<input type="text" placeholder="Credit Card Number" name="cc_num" class="form-control" maxlength="20" />
						
						<br><span class="text-danger"><?php
						if (isset($cc_numError)) {
							echo $cc_numError;
						}
						?></span>
					</div>

					<div class="input-group">
									<div class="col-sm-3" style="padding-right:0px">
									<label for="mm">Month</label>
									<select class="form-control" id="mm" name="expmm">
									<?php
										for ($i=1; $i<=12; $i++)
										{
											?>
												<option value="<?php echo $i;?>"><?php echo $i;?></option>
											<?php
										}
									?>
									</select>


									</div>
									<div class="col-sm-4" style="padding-left:0px">
									<label for="yy">Year</label>
									<select class="form-control" id= "yy" name="expyy" placeholder="Year">
									<?php
										for ($i=2018; $i<=2030; $i++)
										{
											?>
												<option value="<?php echo $i;?>"><?php echo $i;?></option>
											<?php
										}
									?>
									</select>

									</div>	

								

									
										<div class="col-sm-3">
										<input type="text" placeholder="CVV" name="cc_cvv" class="form-control" maxlength="3" />
										<span class="text-danger"><?php
										if (isset($cc_cvvError)) {
											echo $cc_cvvError;
										}
										?></span>
										</div>

					</div>
					<hr/>
					<div class="input-group">
					<h4 class="text-center">Billing Address</h4>
					<h5 class="text-center"><em>Choose one from the dropdown or add a new one first</em></h5>
					
					<select class="form-control" name="add_id">
						<?php 
						$addressquery = "SELECT * FROM address WHERE user_id =" . $_SESSION['user'];
						$res = mysqli_query($mysqli, $addressquery);
						$addRow = mysqli_fetch_array($res, MYSQLI_BOTH);
						$count = mysqli_num_rows($res);
							for($i = 0; $i < $count; $i++){
								?>
								<option value="<?php echo $addRow['address_id'];?>">  <?php 
								$addmsg = $addRow['fname'] . " " . $addRow['lname'] . ", " . $addRow['line1'] . ", " . $addRow['zip'];
								echo $addmsg;
								?> 
								</option>
								<?php
								$addRow = mysqli_fetch_array($res, MYSQLI_BOTH);
							}

						?>
					  </select>
					</div>
				<br />
				</div>


			<div class="input-group text-center">

			<div class="form-check text-right">
			  <input class="form-check-input" name="primaryCheck" type="checkbox" value="1" id="primaryCheck">
			  <label class="form-check-label" for="primaryCheck">
				New Primary Payment Method?
			  <label>
			</div>

			<div class="btn-group" Style="margin-bottom: 5px;">
			<button type="reset"  name="clear" class="btn btn-warning" Style="width: 200px;"/>Clear</button>
			<button type="submit" name="addbtn" class="btn btn-primary" Style="width: 200px;"/>Add Credit Card</button>
			</div>

			<div class="btn-group">
				<a href="manageCC.php" class="btn btn-secondary" Style="width: 400px;" />View Your Credit Cards</a>
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

