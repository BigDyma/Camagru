<?PHP
session_start();
if (isset($_SESSION['login']))
    header("Location: index.php");
	session_start();
	$servername = "127.0.0.1";
	$username = "root";
	$passwd = "";
	$dbname = "camagru";
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $passwd);
	$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	if ($_POST['on'])
	{
		$count = 1;
		$filesize = $_FILES["fileToUpload"]['size'];
		$filename = $_FILES["fileToUpload"]["name"];
		$file_basename = substr($filename, 0, strripos($filename, '.'));
		$file_ext = substr($filename, strripos($filename, '.'));
		$allowed_file_types = array('.png','.jpg','.jpeg');
		if (in_array($file_ext, $allowed_file_types) && ($filesize < 500000) && ($filesize > 0))
		{	
			// Rename file
			$checker = $conn->prepare("SELECT * FROM photos");
			$checker->execute();
			foreach ($checker as $val) {
				if ($val['id'])
				{
					$count++;
				}
			}
			$newfilename = $count . '.png';
			move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "./photos/" . $newfilename);
			$checker = $conn->prepare("INSERT INTO photos (path, author)
										VALUES(:path, :login)");
			$full_path = './photos/' . $newfilename;
			$checker->bindParam(':path', $full_path);
			$checker->bindParam(':login', $_SESSION['login']);
			$checker->execute();
			
			$do_it = 1;
			if ($_POST['eff'] == '0')
			{
				$do_it = 0;
			}
			else if ($_POST['eff'] == '1')
			{
				$effect = imagecreatefrompng("./effects/cat.png");
			}
			else if ($_POST['eff'] == '2')
			{
				$effect = imagecreatefrompng('./effects/img1.png');
			}
			else if ($_POST['eff'] == '3')
			{
				$effect = imagecreatefrompng("./effects/img2.png");
			}
			if ($do_it == 1) 
			{
				imagecopy($next_pic, $effect, 10, 10, 0, 0, imagesx($effect), imagesy($effect));
			}
			imagepng($next_pic, "./images/" . $newfilename);
	
			if ($file_ext == '.png')
			{
				$next_pic = imagecreatefrompng("./photos/" . $newfilename);
			}
			else
			{
				$next_pic = imagecreatefromjpeg("./photos/" . $newfilename);
			}
			}
			else if (empty($file_basename))
			{	
				// file selection error
				echo "Please select a file to upload.";
			} 
			else if ($filesize > 500000)
			{	
				// file size error
				echo "The file you are trying to upload is too large.";
			}
			else
			{
				// file type error
				echo "Only these file typs are allowed for upload: " . implode(', ',$allowed_file_types);
				unlink($_FILES["fileToUpload"]["tmp_name"]);
			}
	
			header("Refresh: 2; url=./index.php");
		}
		else {
			$count = 1;
			$filename = "upload.png";
			$file_ext = '.png';
			$data = $_POST['f'];
	
			$checker = $conn->prepare("SELECT * FROM photos");
			$checker->execute();
			foreach ($checker as $val) {
				if ($val['id'])
				{
					$count++;
				}
			}
			$newfilename = $count . $file_ext;
			list($type, $data) = explode(';', $data);
			list(, $data) = explode(',', $data);
			$data = base64_decode($data);
			file_put_contents("./photos/" . $newfilename, $data);
	
			$abs_path = "./photos/" . $newfilename;
	
			$checker = $conn->prepare("INSERT INTO photos (path, author)
										VALUES(:path, :login)");
			$checker->bindParam(':path', $abs_path);
			$checker->bindParam(':login', $_SESSION['login']);
			$checker->execute();
	
			$next_pic = imagecreatefrompng($abs_path);
			$do_it = 1;
			if ($_POST['eff'] == '0')
			{
				$do_it = 0;
			}
		else if ($_POST['eff'] == '1')
		{
			$effect = imagecreatefrompng("./effects/cat.png");
		}
		else if ($_POST['eff'] == '2')
		{
			$effect = imagecreatefrompng('./effects/img1.png');
		}
		else if ($_POST['eff'] == '3')
		{
			$effect = imagecreatefrompng("./effects/img2.png");
		}
		if ($do_it == 1) 
		{
			imagecopy($next_pic, $effect, 10, 10, 0, 0, imagesx($effect), imagesy($effect));
		}
		imagepng($next_pic, $abs_path);
		echo "File uploaded successfully.";
		
		header("Refresh: 2; url=./index.php");
	}
?>