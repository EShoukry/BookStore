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
$query = "SELECT * FROM users WHERE user_id_number =" . $_SESSION['user'];
$res = mysqli_query($mysqli, $query);
$userRow = mysqli_fetch_array($res, MYSQLI_BOTH);


$error = false;
$changes = false;
$emflag = false;
$fnflag = false;
$lnflag = false;
$unflag = false;
$nnflag = false;

if (isset($_POST['savebtn'])) {
	
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
	

	if($username != $userRow['u_login_id']){
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
			// check username exist in db or not
			$query = "SELECT u_login_id FROM users WHERE u_login_id='$username'";
			$result = (mysqli_query($mysqli, $query));
			$count = mysqli_num_rows($result);
			if ($count != 0) {
				$error = true;
				$usernameError = "Provided Username is already in use.";
			}
		}
		$changes = true;
		$unflag = true;
	}

	if($firstname != $userRow['u_fname']){
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
		$changes = true;
		$fnflag = true;
	}

	if($lastname != $userRow['u_lname']){
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
		$changes = true;
		$lnflag = true;
	}

	if($nickname != $userRow['u_nick']){
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
		$changes = true;
		$nnflag = true;
	}

	if($email != $userRow['u_email']){
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$error = true;
			$emailError = "Please enter valid email address.";
		} else {
			// check email exist in db already or not
			$query = "SELECT u_email FROM users WHERE u_email='$email'";
			$result = (mysqli_query($mysqli, $query));
			$count = mysqli_num_rows($result);
			if ($count != 0) {
				$error = true;
				$emailError = "Provided Email is already in use.";
			}
		}
		$changes = true;
		$emflag = true;
	}
	$res = false;
	$update = "";
	//if no errors update records.
	if(!$error && $changes){
		if($emflag){
			$update = $update . "u_email = '". $email . "', ";
		} if($fnflag){
			$update = $update . "u_fname = '" . $firstname . "', ";
		} if($lnflag){
			$update = $update . "u_lname = '" . $lastname . "', ";
		} if($nnflag){
			$update = $update . "u_nick = '" . $nickname . "', ";
		} if($unflag){
			$update = $update . "u_login_id = '" . $username . "', ";
		}
		$len = strlen($update) - 2;
		
		if($len>0){
		$update = substr($update,0,$len) . " WHERE user_id_number = " . $userRow['user_id_number'];
		}
		$query = "UPDATE users SET " . $update;
		$res = mysqli_query($mysqli, $query);

		if($res){
			$errTyp = "success";
            $errMSG = "Successfully Saved! Review Saved Information Below";
			unset($firstname);
            unset($lastname);
            unset($username);
            unset($nickname);
            unset($email);
			$query = "SELECT * FROM users WHERE user_id_number =" . $_SESSION['user'];
			$res = mysqli_query($mysqli, $query);
			$userRow = mysqli_fetch_array($res, MYSQLI_BOTH);
		} else{
			$errTyp = "danger";
            $errMSG = "Something went wrong, try again later...";
			
        }
	} else if($error){
		$errTyp= "danger";
		$errMSG = "Fix Mistakes Below and Resubmit...";
	}
}


?>



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
        <div class="row">
        <div class="col-lg-12" >
        <form class="form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" enctype="multipart/form-data" style="width:80%" autocomplete="off">
		<table class="table">
            <tbody>
                <tr>
                    <th>User ID #</th>
                    <td><?php echo $userRow['user_id_number']; ?></td>
                </tr>
                <tr>
                    <th>First Name</th>
                    <td>
						<div class="input-group editinfo">
						
							<input type="text"  name="firstname" class="form-control" maxlength="50" value="<?php echo $userRow['u_fname']; ?>" />
							<br><span class="text-danger"><?php
							if (isset($firstnameError)) {
								echo $firstnameError;
							}
							?></span>
						</div>
						
					</td>
                </tr>
                <tr>
                    <th>Last Name</th>
                    <td>
					<div class="input-group editinfo">
						
							<input type="text"  name="lastname" class="form-control" maxlength="50" value="<?php echo $userRow['u_lname']; ?>" />
						<br><span class="text-danger"><?php
							if (isset($lastnameError)) {
								echo $lastnameError;
							}
							?></span>
						</div>
					</td>
                </tr>
                <tr>
                    <th>Username</th>
                    <td>
					<div class="input-group editinfo">
						
						<input type="text"  name="username" class="form-control" maxlength="50" value="<?php echo $userRow['u_login_id']; ?>" />
						<br><span class="text-danger"><?php
							if (isset($usernameError)) {
								echo $usernameError;
							}
							?></span>

						</div>
					</td>
                </tr>
                <tr>
                    <th>Nickname</th>
                    <td>
					<div class="input-group editinfo">
						
							<input type="text"  name="nickname" class="form-control" maxlength="50" value="<?php echo $userRow['u_nick']; ?>" />
						<br><span class="text-danger"><?php
							if (isset($nicknameError)) {
								echo $nicknameError;
							}
							?></span>
						</div>
					</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>
					<div class="input-group editinfo">
						<input type="text"  name="email" class="form-control" maxlength="50" value="<?php echo $userRow['u_email']; ?>" />
						
						<br><span class="text-danger"><?php
							if (isset($emailError)) {
								echo $emailError;
							}
							?></span>
						</div>
					</td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td><?php 
					$addMSG;
					$query = "SELECT `fname`, `lname`, `line1`, `line2`, `city`, `state`, `zip`, `country` FROM `address` WHERE `p_address` = 1 AND `user_id` = " . $userRow['user_id_number'];
					$res = mysqli_query($mysqli, $query);
					if($res){
						$addRow = mysqli_fetch_array($res, MYSQLI_BOTH);
						$addMSG = $addRow['fname'] . " " . $addRow['lname'] . "\n" . $addRow['line1'] . "\n";
						if($addRow['line2'] != ""){
							$addMSG = $addMSG . $addRow['line2'] ."\n";
						}

						$addMSG = $addMSG . $addRow['city'] . ", " . $addRow['state'] . "\n" . $addRow['zip'] . "\n" . $addRow['country'];

					} else{
						$addMSG = "No Primary Address on File Currently";
					}


					echo nl2br ($addMSG);
					
					
					?></td>
                </tr>
            </tbody>
        </table>
		<div>




		<div class="form-group text-center">

					<div class="btn-group">
					<button type="submit" name="savebtn" class="btn btn-primary" Style="width: 300px;"/>Save Edits</button>
					<button type="reset"  name="reset" class="btn btn-warning" Style="width: 300px;"/>Reset</button>
					</div>
		</div>
		<div class="form-group text-center">
					<div class="btn-group">
					<a class="btn btn-info" href="managePass.php" Style="width: 600px;">
							Change Password
							</a>
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

