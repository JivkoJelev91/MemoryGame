<?php
// Include config file
require_once 'config.php';

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = 'Please enter username.';
    } else{
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if(empty(trim($_POST['password']))){
        $password_err = 'Please enter your password.';
    } else{
        $password = trim($_POST['password']);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT username, password FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            /* Password is correct, so start a new session and
                            save the username to the session */
                            session_start();
                            $_SESSION['username'] = $username;
                            header("location: welcome.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = 'The password you entered was not valid.';
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = 'No account found with that username.';
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
	<link type="text/css" rel="stylesheet" href="styles/styles.css">
</head>
<body>
<div class="main">
    <header>
		<div class="scoreButton" style="box-shadow: 2px 4px;">Score board</div>
		<div class="gameName">Memory game</div>
		<div class="buttons">
		<?php if(!isset($_SESSION['username']) || (empty($_SESSION['username']))){ ?>
			<button><span onclick="location.href='login.php';" class="login" style="box-shadow: 2px 4px;background-color:lightblue;">Login</span></button>
			<button><span onclick="location.href='register.php';" class="register" style="box-shadow: 2px 4px;">Register</span></button>	
		<?php } else { echo "<span>Hello <strong>". $_SESSION['username'] ."</strong></span>"; 
					   echo "<button><span onclick=\"location.href='logout.php';\" style='box-shadow: 2px 4px;' class='login'>Logout</span></button>"; } ?>
		</div>
	</header>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
            <label>Username: </label>
            <input type="text" name="username" value="<?php echo $username; ?>">
            <span class="errors"><?php echo $username_err; ?></span>
        </div>
        <div <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>Password: </label>
            <input type="password" name="password" >
            <span class="errors"><?php echo $password_err; ?></span>
        </div>
        <div>
            <input type="submit" value="Login">
        </div>
    </form>
</div>
</body>
</html>