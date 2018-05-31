<?php
    session_start();
    include ("functions.php");
    
    if (isset($_SESSION['login']))
    {
        header("Location: index.php");
    }
    if (isset($_POST['submit']))
    {
        $servername = "127.0.0.1";
        $username = "root";
        $password = "";
        $dbname = "camagru";
        $login = $_POST['login'];$fname = $_POST['fname'];
        $lname = $_POST['lname'];$passwd = $_POST['passwd'];
        if ((strlen($passwd) < 8 && $passwd !== 'admin') || (strlen($login) < 5 && $login !== 'admin'))
        {
            echo "passwd or login length is less than 8 </br>";
            exit();
        }
    	$login = stripslashes($login);
    	$login = htmlspecialchars($login);
    	$passwd = stripslashes($passwd);
    	$passwd = htmlspecialchars($passwd);
    		
    		//удаляем лишние пробелы
    	$login = trim($login);
    	$passwd = trim($passwd);
        $email = $_POST['email'];
        $email = trim($email);
        $passwd = hash("md5", $passwd);
        try
        {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $checker = $conn->prepare("SELECT login FROM users WHERE login = '$login'");
            $checker->execute();
            if ($checker->rowCount() > 0) 
            {
                echo "this login is currently in use";
                
            }
            else
            {
                if (!verif($email))
                {
                        echo "not valid email </br>";
                        exit();
                }
                $checker = $conn->prepare("INSERT INTO users (password, login, firstname, lastname, email)
                VALUES ('$passwd','$login', '$fname', '$lname', '$email');");
                $checker->execute();
                 
                $checker = $conn->prepare("SELECT reg_date FROM users WHERE login = :name");
    			$checker->bindParam(':name', $_POST['login']);
    			$checker->execute();
    			foreach ($checker as $val)
    			{
    				if ($val['reg_date'])
    				{
    					$generatedhash = hash("md5", $val['reg_date']);
    				}
    			}
    			$generatedhash = $generatedhash . hash("md5", $email);
    			//$_SERVER['SERVER_NAME']
    			$addres = "/activate.php?do=act&check=" . $generatedhash;
    			$message = "<p style='padding-left: 4em;'>Dear $firstname</p><br />" . "Please verify your Camagru account. Just click on the link:<br />" . "<a href=" . $addres . ">" . $addres . "</a>" . "<br /><br /><br />" . "<p style='text-align: center'>Thank You!</p>";
    		//	mail($email, "Camagru account verification", $message);
    			echo $message;
    			
    			
                 echo "New record created successfully";
             }
        }
        catch(PDOException $e)
        {
            echo $sql . "<br>" . $e->getMessage();
        }
    }
?>