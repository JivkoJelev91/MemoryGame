<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
	 <link type="text/css" rel="stylesheet" href="styles/styles.css">
	<script src="game.js"></script>
</head>
<body>
<div class="main">
    <header>
		<button id="scoreBoard"><div class="scoreButton" style="box-shadow: 2px 4px;">Score board</div></button>
		<div class="gameName">Memory game</div>
		<div class="buttons">
		<?php if(!isset($_SESSION['username']) || (empty($_SESSION['username']))){ ?>
			<button><span onclick="location.href='login.php';" class="login" style="box-shadow: 2px 4px;">Login</span></button>
			<button><span onclick="location.href='register.php';" class="register" style="box-shadow: 2px 4px;">Register</span></button>	
		<?php } else { echo "<span>Hello <strong>". $_SESSION['username'] ."</strong></span>"; 
					   echo "<button><span onclick=\"location.href='logout.php';\" style='box-shadow: 2px 4px;' class='login'>Logout</span></button>"; } ?>
		</div>
	</header>
	<div class="playButton">
		<div id="scoreResult" style='display:none'>
			<div class="headerOfScore">Score Board Result</div>
			<div class="mainOfScore">
				<div class="topResult"></div>
			</div>
		</div>
		<div class="playBoard">
			<button><div class="play">PLAY</div></button>
			<div class="difficults" style='display:none'>
			<?php if(!isset($_SESSION['username'])){?>
				<div class="challenges"><div><a href="login.php">Easy</a></div><li>this level contains 8 boxes</li></div>
				<div class="challenges"><div><a href="login.php">Medium</a></div><li>this level contains 10 boxes</li></div>
				<div class="challenges"><div><a href="login.php">Hard</a></div><li>this level contains 12 boxes</li></div>
			<?php } else { ?>
				<div class="challenges"><div><a href="#" onclick="easy(8)" class='challenge'>Easy</a></div><li>this level contains 8 boxes</li></div>
				<div class="challenges"><div><a href="#" onclick="easy(10)">Medium</a></div><li>this level contains 10 boxes</li></div>
				<div class="challenges"><div><a href="#" onclick="easy(12)">Hard</a></div><li>this level contains 12 boxes</li></div>
			<?php } ?>
			</div>
		</div>
	</div>
</div>
	<script src="toggleButton.js"></script>
	<script src="game.js"></script>
</body>
</html>