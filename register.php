<?php
// Include config file
require 'config.php';

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
$email = "";
$email_err = "";


function emailQuery(){
	
	function test_input($data){
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	
	if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a email.";
	}
	
	if(empty($email_err) && $_POST['email'] != null){
		$emailAdress = $_POST['email'];
		$email = test_input($emailAdress);
		$res = mysqli_connect('localhost', 'root', '','data');
		$sql = "INSERT INTO users (email) VALUES({$email})";
		var_dump($res);
		var_dump($sql);
		var_dump($email);
		if(mysqli_query($res,$sql)){
			echo "EVERYTHING IS OK BUT I DONT BELIEVE IN MIRACLE";
		}		
	}
}

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
	}else if(strlen(trim($_POST['username'])) < 6 || strlen(trim($_POST['username'])) > 12){
		$username_err = "Username must be between 6-12 characters long.";
	}else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Validate password
    if(empty(trim($_POST['password']))){
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST['password'])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST['password']);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = 'Please confirm password.';
    } else{
        $confirm_password = trim($_POST['confirm_password']);
        if($password != $confirm_password){
            $confirm_password_err = 'Password did not match.';
        }
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
	
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
				emailQuery();
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
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
    <title>Sign Up</title>
	<link type="text/css" rel="stylesheet" href="styles/styles.css">
</head>
<body>
<div class="main">
   <header>
		<div class="scoreButton" style="box-shadow: 2px 4px;">Score board</div>
		<div class="gameName">Memory game</div>
		<div class="buttons">
		<?php if(!isset($_SESSION['username']) || (empty($_SESSION['username']))){ ?>
			<button><span onclick="location.href='login.php';" class="login" style="box-shadow: 2px 4px;">Login</span></button>
			<button><span onclick="location.href='register.php';" class="register" style="box-shadow: 2px 4px;background-color:lightblue;">Register</span></button>	
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
		<div <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
            <label>Email: </label>
            <input type="text" name="email" value="">
            <span class="errors"><?php echo $email_err; ?></span>
        </div>
        <div <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>Password: </label>
            <input type="password" name="password" value="<?php echo $password; ?>">
            <span class="errors"><?php echo $password_err; ?></span>
        </div>
        <div <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
            <label>Repeat: </label>
            <input type="password" name="confirm_password" value="<?php echo $confirm_password; ?>">
            <span class="errors"><?php echo $confirm_password_err; ?></span>
        </div>
        <div>
            <input type="submit" value="Register" class="formButton">
        </div>
    </form>
</div>
</body>
</html>