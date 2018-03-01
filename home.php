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
?>



<!doctype html>
<html>
    <head>
        <meta charset="utf-8">    
        <title>Welcome <?php echo $userRow['u_login_id']; ?></title>
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
    	<div class=section_title><h3>User Information</h3></div>
    	</div>
        
        <div class="row">
        <div class="col-lg-12">
        
		<table class="table">
            <tbody>
                <tr>
                    <th>User ID #</th>
                    <td><?php echo $userRow['user_id_number']; ?></td>
                </tr>
                <tr>
                    <th>First Name</th>
                    <td><?php echo $userRow['u_fname']; ?></td>
                </tr>
                <tr>
                    <th>Last Name</th>
                    <td><?php echo $userRow['u_lname']; ?></td>
                </tr>
                <tr>
                    <th>Username</th>
                    <td><?php echo $userRow['u_login_id']; ?></td>
                </tr>
                <tr>
                    <th>Nickname</th>
                    <td><?php echo $userRow['u_nick']; ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo $userRow['u_email']; ?></td>
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

