<?php
ob_start();
session_start();
$database = include('config.php');

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
} else {
	$userid = $_SESSION['user'];
}

// Create connection
$mysqli = new mysqli($database['host'], $database['user'], $database['pass'], $database['name']);

// Check connection
if (mysqli_connect_error()) {
    die("Database connection failed: " . mysqli_connect_error());
}

if(isset($_POST['savebtn'])){
	$query = "SELECT * FROM users WHERE user_id_number =" . $_SESSION['user'];
	$res = mysqli_query($mysqli, $query);
	$userRow = mysqli_fetch_array($res, MYSQLI_BOTH);

	$error = false;

	$password = trim($_POST['password']);
    $password = strip_tags($password);
    $password = htmlspecialchars($password);

	$newpassword = trim($_POST['newpassword']);
    $newpassword = strip_tags($newpassword);
    $newpassword = htmlspecialchars($newpassword);

	$cnewpassword = trim($_POST['cnewpassword']);
    $cnewpassword = strip_tags($cnewpassword);
    $cnewpassword = htmlspecialchars($cnewpassword);

	if (empty($password)) {
        $error = true;
        $passwordError = "Please enter password.";
    }

	if (empty($newpassword)) {
        $error = true;
        $newpasswordError = "Please enter new password.";
    }

	if (empty($cnewpassword)) {
        $error = true;
        $cnewpasswordError = "Please enter new password again.";
    }

	if ($newpassword != $cnewpassword){
		$error = true;
		$newpasswordError = "Paswords do not match.";
		$cnewpasswordError = "Paswords do not match.";
	}

	if(!$error){
		if (password_verify($password, $userRow['u_password'])){
			$password = password_hash($newpassword, PASSWORD_DEFAULT);
			$query = "UPDATE users SET u_password='$password' WHERE user_id_number =" . $_SESSION['user'];
			$res = mysqli_query($mysqli, $query);
			if($res){
				$errTyp = "success";
				$errMSG = "Successfully updated password.";
			}else{
				$errTyp = "danger";
				$errMSG = "Failed to update password. Please try again...";
			}
		} else{
			$error = true;
			$passwordError = "Password entered incorrectly.";
		}
	}

}
?>
<!doctype html>
<html>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">    
        <title>Edit Profile</title>
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
	<div class="container userContainer" >
    	<?php
        require "includes/navbar_user.php";
    ?>
    	<div class="page-header">
    	<div class=section_title><h3>Edit User Information</h3></div>
    	</div>
        <div class="row">
        <div class="col-lg-12" >

        <form class="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data" style="width:80%" autocomplete="off">
		<div class="col-sm-11 text-left">
		<?php
			if ( isset($errMSG) ) {
				
		?>
			<div class="form-group">
			
           	<div class="alert alert-<?php echo ($errTyp=="success") ? "success" : $errTyp; ?>">
			<span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
			
           	</div>
			</div>
		<?php
			}
		?>
		</div>
		<div class="col-sm-4 text-left">

			<input type="password" placeholder="Current Password" name="password" class="form-control ">
			<span class="text-danger"><?php
				if (isset($passwordError)) {
					echo $passwordError;
				}
			?></span>
			<input type="password" placeholder="New Password" name="newpassword" class="form-control">
			<span class="text-danger"><?php
				if (isset($newpasswordError)) {
					echo $newpasswordError;
				}
			?></span>
			<input type="password" placeholder="Confirm Password" name="cnewpassword" class="form-control">
			<span class="text-danger"><?php
				if (isset($cnewpasswordError)) {
					echo $cnewpasswordError;
				}
			?></span>
		</div>


		<div class="form-group text-center">

					<div class="btn-group">
					<button type="submit" name="savebtn" class="btn btn-primary" Style="width: 200px;"/>Change Password</button>
					<button type="reset"  name="reset" class="btn btn-warning" Style="width: 200px;"/>Clear</button>
					</div>
		</div>


		</form>
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
