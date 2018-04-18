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
} else {
	$userid = $_SESSION['user'];
}

if (!isset($_GET['CCid'])){
	header("Location: manageCC.php");
	exit;
}else{
	$CCid = $_GET['CCid'];
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
	$addressid = $_POST['add_id'];
	$p_CC = (isset($_POST['primaryCheck']) ? 1 : 0);

    $cc_title = trim($_POST['cc_title']);
    $cc_title = strip_tags($cc_title);
    $cc_title = htmlspecialchars($cc_title);


	$month = trim($_POST['expmm']);
    $month = strip_tags($month);
    $month = htmlspecialchars($month);

    $year = trim($_POST['expyy']);
    $year = strip_tags($year);
    $year = htmlspecialchars($year);


	

	//add address information as primary(only) address upon session set.
		if(!$error){
			$res = True;
			if ($p_CC){
				$query = "UPDATE credit_card SET p_CC='0' WHERE user_id='" . $_SESSION['user'] . "' AND p_CC='1';";
				$res = mysqli_query($mysqli, $query);
			}
			if ($res){	
				$query = "UPDATE credit_card SET add_id = '$addressid', P_CC='$p_CC', CC_title='$cc_title', CC_expmm='$month', CC_expyy='$year' WHERE CC_id= '$CCid' AND user_id= '$userid'";
				$res = mysqli_query($mysqli, $query);
				if ($res) {
					$errTyp = "success";
					$errMSG = "CC Updated Successfully!";
					unset($p_CC);
					unset($cc_title);
					unset($month);
					unset($year);
					unset($addressid);



				}else{
					$error = true;
					$errTyp = "danger";
					$errMSG = $errMSG . "Error In CC Insert, Please Enter Try Again...";
				}
			}else{
					$error = true;
					$errTyp = "danger";
					$errMSG = $errMSG . "Error In CC Primary Unsetting, Please Try Again...";
			}
        } else {
            $errTyp = "danger";
            $errMSG = "Something went wrong, try again later...";
        }
}

$query = "SELECT * FROM credit_card WHERE user_id ='$userid' AND CC_id='$CCid'";
$res = mysqli_query($mysqli, $query);
$count = mysqli_num_rows($res);
if($count == 1){
	$ccRow = mysqli_fetch_array($res, MYSQLI_BOTH);
	
	$addid = $ccRow['add_id'];

    $title = $ccRow['CC_title'];

	$expmm = $ccRow['CC_expmm'];

    $expyy = $ccRow['CC_expyy'];

    $four = $ccRow['CC_four'];

	$primaryCC = $ccRow['p_CC'];

} else{
		header("Location: manageCC.php");
		exit;
}

?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">    
        <title>Edit Address</title>
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
    <div class=section_title><h3>Edit Credit Card</h3></div>
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
		<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>?CCid=<?php echo $CCid; ?>" method="post" autocomplete="off">

	<div class="col-sm-12 text-left">

		<div class="input-group">
			<input type="text" placeholder="Payment Title" name="cc_title" class="form-control" maxlength="50" value="<?php echo $title?>"/>
		
			<br><span class="text-danger"><?php
			if (isset($cc_titleError)) {
				echo $cc_titleError;
			}
			?></span>
		</div>

		<div class="input-group">
		<div class="col-sm-4">
			<input type="text" placeholder="last four" name="cc_num" class="form-control" maxlength="20" value="<?php echo "x" . $four; ?>" disabled/>
						
			<br><span class="text-danger"><?php
			if (isset($cc_numError)) {
				echo $cc_numError;
			}
			?></span>
		</div>
		</div>

		<div class="input-group">
							<div class="col-sm-4" style="padding-right:0px">
							<label for="mm">Month</label>
							<select class="form-control" id="mm" name="expmm">
							<?php
								for ($i=1; $i<=12; $i++)
								{
									?>
										<option value="<?php echo $i;?>" <?php if ($expmm == $i) {echo " " . "selected";}?>><?php echo $i;?></option>
									<?php
								}
							?>
							</select>


							</div>
							<div class="col-sm-6" style="padding-left:0px">
							<label for="yy">Year</label>
							<select class="form-control" id= "yy" name="expyy" placeholder="Year">
							<?php
								for ($i=2018; $i<=2030; $i++)
								{
									?>
										<option value="<?php echo $i;?>" <?php if ($expyy == $i) {echo " " . "selected";}?>><?php echo $i;?></option>
									<?php
								}
							?>
							</select>

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
								<option value="<?php echo $addRow['address_id'];?>" <?php if ($addid == $addRow['address_id']) {echo " " . "selected";}?>>  <?php 
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

			<div class="input-group text-center">

			<div class="form-check text-right">
			  <input class="form-check-input" name="primaryCheck" type="checkbox" value="1" id="primaryCheck" <?php if($primaryCC) {echo "checked";}?>>
			  <label class="form-check-label" for="primaryCheck">
				Priamry Credit Card?
			  <label>
			</div>

			<div class="btn-group" Style="margin-bottom: 5px;">
			<button type="reset"  name="clear" class="btn btn-warning" Style="width: 200px;"/>Clear</button>
			<button type="submit" name="addbtn" class="btn btn-primary" Style="width: 200px;"/>Update Card</button>
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

