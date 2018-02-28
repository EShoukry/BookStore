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

if (isset($_POST['login'])) {

    // clean user inputs to prevent sql injections



    $email = trim($_POST['email']);
    $email = strip_tags($email);
    $email = htmlspecialchars($email);

    $password = trim($_POST['password']);
    $password = strip_tags($password);
    $password = htmlspecialchars($password);


//    //basic email validation
    if (empty($email)) {
        $error = true;
        $emailError = "Please enter an email address.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $emailError = "Please enter valid email address.";
    }


    // password validation
    if (empty($password)) {
        $error = true;
        $passwordError = "Please enter password.";
    }


    // if there's no error, continue to signup
    if (!$error) {

        $password = md5($password);

        $query = "SELECT user_id_number, u_password, u_email FROM users WHERE u_email = '$email' AND u_password = '$password'";
        $res = mysqli_query($mysqli, $query);
        $row = mysqli_fetch_array($res, MYSQLI_BOTH);
        $count = mysqli_num_rows($res);

        if ($count == 1) {
            $_SESSION['user'] = $row['user_id_number'];
            phpAlert("Login Successful! User ID: " . $_SESSION['user']);
            header("Location: home.php");
        } else {
            phpAlert("Incorrect Credentials, Try again...");
        }
    }
}
?>



<!doctype html>
<html>
    <head>
        <meta charset="utf-8">    
        <title>Login </title>
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
		<div class="hd_container" >
        <div id=main_image>
            <img src="images/index.jpeg" alt="Team 7 book store" >
        </div>  


        <div id="login-form">

            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" autocomplete="off">

				<div class="col-md-12">
                <div class="form-group"><div class=section_title>
                        <h1>Login to Your Account</h1>
                    </div>
                </div>

                <div class="form-group">
                    <hr />
                </div>


                <div class="form-group">
                    <div class="input-group">
                        <label><b>Email</b></label>
                        <input type="email" placeholder="Email" name="email" class="form-control" maxlength="50" value="<?php
                        if (isset($email)) {
                            echo $email;
                        }
                        ?>"  />
                    </div>
                    <span class="text-danger"><?php
                        if (isset($emailError)) {
                            echo $emailError;
                        }
                        ?></span>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <label><b>Password</b></label>
                        <input type="password" placeholder="Password" name="password" class="form-control" maxlength="50" autocomplete="new-password" />
                    </div>
                    <span class="text-danger"><?php
                        if (isset($passwordError)) {
                            echo $passwordError;
                        }
                        ?></span>
                </div>

                <div class="form-group">
                    <hr />
                </div>
                <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
                <button type="reset"  name="clear" class="btn btn-warning btn-block">Clear</button>


                <div class="form-group">
                    <hr />
                </div>

                <div class="form-group">
                    <a href="register.php">Sign Up Here...</a>
                </div>


			</div>

            </form>

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

