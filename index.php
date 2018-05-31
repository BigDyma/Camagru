<?php
	include ("functions.php");
	session_start();
	if (!isset($_SESSION['login']))
		   header("Location: login.php");
?>
<html>
<head>
	<meta charset="utf-8">
	<title>Camagru</title>
	<link rel="stylesheet" href="css/main.css" type="text/css" media="all">
	<link rel="stylesheet" type="text/css" href="css/style13.css">
</head>
<body>
	<div class="top-bar">
		<ul>
			<li><a href="index.php">Home</a></li>
			<li><a href="gallery.php">My gallery</a></li>
			<li style="float:right"><a class="active" href="logout.php">Logout</a></li>
		</ul>
	</div>
	
	
	<div class="content">
		<div class="camera">
			<video id="video">Video stream not available.</video>
			<button id="startbutton">Take Photo</button>
		</div>
		<canvas id="canvas"></canvas>
		<img style="display: none;" id="photo" alt="The screen capture will appear in this box.">
		<br>
		<div>
			<img onclick="set_effect(0)" class="effect_img" src="effects/img0.png"></img>
			<img onclick="set_effect(1)" class="effect_img" src="effects/cat.png"></img>
			<img onclick="set_effect(2)" class="effect_img" src="effects/img1.png"></img>
			<img onclick="set_effect(3)" class="effect_img" src="effects/img2.png"></img>
		</div>
		<br>
		<br>
		<form id="photo_go" name="fooorm" action="upload.php" method="post" enctype="multipart/form-data">
			<p>Upload your photo</p>
			<input type="file" name="fileToUpload" id="fileToUpload">
			<input id="f" type="hidden" name="f">
			<input id="eff" type="hidden" name="eff" value="0">
			<input type="submit" value="Upload Image" name="on" class="btn btn-primary btn-block btn-large">
		</form>
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
				echo '<div class="image"> <h3>' . $val['author'] . '</h3><img class="fotka" src=' . $pa . '></img><div class="descript">';
				foreach ($checker2 as $val2) {
					if ($val2['photo_id'] == $val['id']) {
						echo '<span> <span class="letter">'.$val2['login'].'</span>: '. $val2['comment'] . '</span>' . '<br>';
					}
				}
				echo '<form name="comm" method="post" action="index.php">
				<input type="text" name="comment">
				<input type="hidden" name="photoID" value=' . $val["id"] . '>'.'
				<input type="submit" name="scom" value="comenteaza">
				<input type="submit" value="' . $checker3->fetchColumn() . ' likes | Like"' . 'name="like_it">
				</form>'
				.'</div>
				</br>
				</br>
				</div>';
			}
		?>
	</div>
</body>
<script id="jsbin-javascript">
(
	function() {
		var streaming	= false,
			video		= document.querySelector('#video'),
			canvas		= document.querySelector('#canvas'),
			f			= document.querySelector('#f'),
			photo		= document.querySelector('#photo'),
			startbutton	= document.querySelector('#startbutton'),
			width = 460,
			height = 0;
		navigator.getMedia = ( navigator.getUserMedia ||
							 navigator.webkitGetUserMedia ||
							 navigator.mozGetUserMedia ||
							 navigator.msGetUserMedia);
		navigator.getMedia(
			{
				video: true,
				audio: false
			},
		function(stream) {
			if (navigator.mozGetUserMedia) {
			video.mozSrcObject = stream;
			} else {
			var vendorURL = window.URL || window.webkitURL;
			video.src = vendorURL.createObjectURL(stream);
			}
			video.play();
		},
		function(err) {
			console.log("An error occured! " + err);
		}
	);
	video.addEventListener('canplay', function(ev){
		if (!streaming) {
			height = video.videoHeight / (video.videoWidth/width);
			video.setAttribute('width', width);
			video.setAttribute('height', height);
			canvas.setAttribute('width', width);
			canvas.setAttribute('height', height);
			streaming = true;
		}
	}, false);
	function takepicture() {
		canvas.width = width;
		canvas.height = height;
		canvas.getContext('2d').drawImage(video, 0, 0, width, height);
		var data = canvas.toDataURL('image/png');
		photo.setAttribute('src', data);
		f.setAttribute('value',data);
		document.forms[0].submit();
	}
	startbutton.addEventListener('click', function(ev){
		takepicture();
	}, false);
})();
</script>
<script type="text/javascript">
	function set_effect(img_nr) 
	{
		console.log("lol");
		eff	= document.querySelector('#eff'),
		eff.setAttribute('value', img_nr);
	}
</script>
</html>
<?php 
	if (isset($_POST['scom']))
	{
		$servname = '127.0.0.1';
		$username = "root";
		$passwd = "";
		$dbname = "camagru";
		$conn = new PDO("mysql:host=$servname;dbname=$dbname", $username, $passwd);
		$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$login = $_SESSION['login'];
		$cvalue = $_POST['comment'];
		$checker = $conn->prepare("	INSERT INTO comments (photo_id, login, comment)
	            					VALUES (:photo_id, :login, :commu);");
	    $checker->bindParam(':photo_id', $_POST['photoID']);
	    $checker->bindParam(':login', $login);
	    $checker->bindParam(':commu', $cvalue);
	    $checker->execute();	    
		header("Refresh: 2; url=./index.php");
	}
	if (isset($_POST['like_it']))
	{
		$servname = '127.0.0.1';
		$username = "root";
		$passwd = "";
		$dbname = "camagru";
		$conn = new PDO("mysql:host=$servname;dbname=$dbname", $username, $passwd);
		$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$is_here = 0;
		$sql = 'SELECT * FROM `likes` WHERE author_login=:id';
		$checker = $conn->prepare($sql);
		$checker->bindParam(':id', $_SESSION['login']);
		$checker->execute();
		foreach ($checker as $val)
		{
			if ($val['image_id'] == $_POST['photoID'])
			{
				$is_here = 1;
			}
		}
		if ($is_here == 1)
		{
			$sql = 'DELETE FROM `likes` WHERE author_login=:id AND image_id=:img';
			$checker = $conn->prepare($sql);
			$checker->bindParam(':id', $_SESSION['login']);
			$checker->bindParam(':img', $_POST['photoID']);
			$checker->execute();
		}
		else
		{
			$checker = $conn->prepare("INSERT INTO likes (image_id, author_login)
										VALUES(:id, :login)");
			$checker->bindParam(':id', $_POST['photoID']);
			$checker->bindParam(':login', $_SESSION['login']);
			$checker->execute();
			echo "Liked!";
		}
		header("Refresh: 2; url=./index.php");
	}
?>