<!DOCTYPE html>
<html>
    <head>
    <meta charset="utf-8">
	<title>reset</title>
    </head>
    <body>
        		<form action="reset.php" method="post" name="LogForm">
    				<input placeholder="Old password" type="password" name="old" required autocomplete="off"/>
    				</br>
    				<input placeholder="New Password" type="password" name="new" required autocomplete="off"/>
    				<input type="submit" name="reset" value="reset" class="button button-block"/></input>
				</form>
				<a href="gallery.php">go to my gallery</a>
    </body>
</html>
<?php 
   session_start();
if (!isset($_SESSION["login"]))
{
        header("Location: login.php");
}
if ($_POST["reset"])
{
    	$paswd = $_POST["old"];
    	$new = $_POST["new"];
    	$paswd = hash("md5", $paswd);
    	$new = hash("md5", $new);
    	$login = $_SESSION["login"];
        $servername = "127.0.0.1";
    	$username = "root";
    	$passwd = "";
    	$dbname = "camagru";
    	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $passwd);
    	$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	$checker = $conn->prepare("SELECT `password` FROM `users` WHERE `login`=:loh");
    	$checker->bindParam(':loh', $login);
    	$checker->execute();
    	foreach ($checker as $val) 
    	{
    	    if ($val["password"] === $paswd)
    	    {
    			$checker = $conn->prepare("UPDATE `users` SET `password`=:passwd WHERE `login`=:loh");
	        	$checker->bindParam(':loh', $login);
    			$checker->bindParam(':passwd', $new);
    			$checker->execute();
    			echo "done";
    		}
    	    else 
    	    {
    	        echo "old password is incorrect </br>";
    	    }
    	}
    }

?>