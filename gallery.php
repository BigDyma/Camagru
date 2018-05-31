<?php
	include ("functions.php");
	session_start();
	if (!isset($_SESSION['login']))
		   header("Location: login.php");
	if ($_POST["delete_post"])
	{
		$servername = "127.0.0.1";
		$username = "root";
		$passwd = "";
		$dbname = "camagru";
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $passwd);
		$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$checker = $conn->prepare('DELETE FROM photos WHERE id=:loh');
		$checker->bindParam(':loh', $_POST['photoID']);
		$checker->execute();
		header("Refresh:2");
	}

?>
<html>
<head>
	<meta charset="utf-8">
	<title>Camagru</title>
	<script type="text/javascript">
		function sure()
		{
			element = document.getElementById('#delete');
			var r = confirm("are you sure that you want to delete this ?")
			if (r)
			{
				document.forms[0].submit();
			}
			
		}
	</script>
	<link rel="stylesheet" href="css/main.css" type="text/css" media="all">
	<link rel="stylesheet" type="text/css" href="css/style4.css">
</head>
<body>
	<div class="top-bar">
		<ul>
			<li><a href="index.php">Home</a></li>
			<li><a href="mines.php">My gallery</a></li>
			<li><a href="reset.php">Reset my password</a></li>
			<li style="float:right"><a class="active" href="logout.php">Logout</a></li>
		</ul>
	</div>
	
	
	<div class="fmenu">
		<?PHP
			$ss = $_SESSION['fname'];
			echo '<h1 style="/*padding-left: 10vw;*/">' . "Hello, " . $ss . '</h1></br>';
		?>
		<br/>
	</div>
	<div class="feed">
		</br>
	<?PHP
		$servername = "127.0.0.1";
		$username = "root";
		$passwd = "";
		$dbname = "camagru";
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $passwd);
		$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$checker = $conn->prepare("SELECT * FROM photos ORDER BY id DESC");
		$checker->execute();
		foreach ($checker as $val)
		{
			
			$checker2 = $conn->prepare('SELECT * FROM comments');
			$checker2->execute();
			$checker3 = $conn->prepare("SELECT COUNT(*) FROM likes WHERE image_id=:img_id");
			$checker3->bindParam(':img_id', $val['id']);
			$checker3->execute();
			$pa = $val['path'];
			if ($val['author'] == $_SESSION['login'])
			{
			echo '<div class="image"> <h3>' . $val['author'] . '</h3><img class="fotka" src=' . $pa . '></img><div class="descript">';
			foreach ($checker2 as $val2) {
				if ($val2['photo_id'] == $val['id']) 
				{
					echo '<span> <span class="letter">'.$val2['login'].'</span>: '. $val2['comment'] . '</span>' . '<br>';
				}
			}
			echo '<form name="comm" method="post" action="gallery.php">
					<input type="text" name="comment">
					<input type="hidden" name="photoID" value=' . $val["id"] . '>'.'
					<input type="submit" name="scom" value="comenteaza">
					<input type="submit" value="' . $checker3->fetchColumn() . ' likes | Like"' . 'name="like_it">
					<input id="delete" onclick="sure()" type="submit" value="X" name="delete_post">
				</form>'
			.'</div>
			</br>
			</br>
			</div>';
			}
		}
	?>
	</div>
</body>
</html>
