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


//select logged in user's info
$addressquery = "SELECT * FROM address WHERE user_id =" . $_SESSION['user'];
$res = mysqli_query($mysqli, $addressquery);
$addRow = mysqli_fetch_array($res, MYSQLI_BOTH);
$count = mysqli_num_rows($res);

if (isset($_POST['edit'])) {
	phpAlert("Edit Pressed for " . $_POST['add_id']);
	header("Location: addressedit.php?addid=" . $_POST['add_id']);
    exit;
} else if(isset($_POST['delete'])){
	
	$query = "DELETE FROM `address` WHERE `address_id` = " . $_POST['add_id'] . " AND user_id =" . $_SESSION['user'];
	$res = mysqli_query($mysqli, $query);
	if($res){
		$errTyp = "success";
		$errMSG = "Delete Success";
		$res = mysqli_query($mysqli, $addressquery);
		$addRow = mysqli_fetch_array($res, MYSQLI_BOTH);
		$count = mysqli_num_rows($res);
	} else {
		$errTyp = "danger";
		$errMSG = "Delete Failed \n" . $query;
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
    	<div class=section_title><h3>Add/Edit Addresses</h3></div>
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


			




		<div id="accordion">
		  
		  
		 

		<?php

			
			for($x = 0; $x < $count; $x++){
			?>
				<div class="card">
				<div class="card-header" id="heading<?php echo ($x + 1)?>">
				  <h5 class="mb-0 text-center">
					<button class="btn btn-info" data-toggle="collapse" data-target="#collapse<?php echo ($x + 1)?>" aria-expanded="true" aria-controls="collapse<?php echo ($x + 1)?>">
					  Address #<?php echo ($x + 1) ?>&nbsp;<span class="caret"></span>
					</button>
				  </h5>
				</div>

				<div id="collapse<?php echo ($x + 1)?>" class="collapse" aria-labelledby="heading<?php echo ($x + 1)?>" data-parent="#accordion">
				  <div class="card-body">
					<div class="form-group text-center">
						<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" autocomplete="off">

						<div class="input-group" >
							<div class="row">
							
							<input type="hidden" name="add_id" value="<?php echo $addRow['address_id']?>">
							<?php
								$addMSG = $addRow['fname'] . " " . $addRow['lname'] . "\n" . $addRow['line1'] . "\n";
								if($addRow['line2'] != ""){
									$addMSG = $addMSG . $addRow['line2'] ."\n";
								}

								$addMSG = $addMSG . $addRow['city'] . ", " . $addRow['state'] . "\n" . $addRow['zip'] . "\n" . $addRow['country'] . "\n";
								
								echo nl2br ($addMSG);

								echo ("Primary Address: ");
								echo ($addRow['p_address'] ? "Yes" : "No");


							?>
							</div>
							<hr/>
							<div class="row">
							<div class="btn-group-horizontal text-center">
								<button type="submit" name="edit" class="btn btn-primary"/>Edit</button>
								<?php if ($addRow['p_address'] == 0) {
									echo "<button type=\"delete\"  name=\"delete\" class=\"btn btn-warning\"/>Delete</button>";
									$addRow = mysqli_fetch_array($res, MYSQLI_BOTH);
								}else{
									$addRow = mysqli_fetch_array($res, MYSQLI_BOTH);
								}?>
							</div>
							</div>
						</div>
						
						</form>
					</div>
				  </div>
				</div>
			  </div>
			
			<?php
			}
		?>
        
		</div>
		<div class="input-group text-center">
		<div class="btn-group">
			<a href="addressadd.php" class="btn btn-secondary" />Add New Address</a>
		</div>
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

