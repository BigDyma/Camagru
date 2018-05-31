<?php
    session_start();
    if (isset($_SESSION['login']))
        header("Location: index.php")
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
	<title>Camagru</title>
	
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
    	<div class="form">
		
		<ul class="tab-group">
			<li id="log" class="tab active"><a href="#login" onclick="doo(2)">Log In</a></li>
			<li id="sign" class="tab"><a href="#signup" onclick="doo(1)">Sign Up</a></li>
		</ul>
		
		<div class="tab-content">
			<div id="signup" class="notnow">   
				<h1>Sign Up for Free</h1>
				
				<form action="register.php" method="post" name="RegForm">
				
    				<div class="top-row">
    					<div class="field-wrap">
    						<input type="text" name="login" placeholder="Login" required autocomplete="off" />
    					</div>
    			
    					<div class="field-wrap">
    						<input type="text" name="fname" placeholder="First Name" required autocomplete="off"/>
    					</div>
    			
    					<div class="field-wrap">
    						<input type="text" name="lname" placeholder="Last Name" required autocomplete="off"/>
    					</div>
    				</div>
    
    				<div class="field-wrap">
    						<input type="email" name="email" placeholder="Email" required autocomplete="off"/>
    				</div>
    				
    				<div class="field-wrap">
    						<input type="password" name="passwd" placeholder="Password" required autocomplete="off"/>
    				</div>
    				
    				<input type="submit" name="submit" value="Sign Up" class="button button-block"/></input>
				
				</form>

			</div>
			
			<div id="login">   
				<h1>Welcome Back!</h1>
				
				<form action="login.php" method="post" name="LogForm">
				
					<div class="field-wrap">
    					<input placeholder="Login" type="text" name="login" required autocomplete="off"/>
    				</div>
    				
    				<div class="field-wrap">
    					<input placeholder="Password" type="password" name="passwd" required autocomplete="off"/>
    				</div>
    				
    				<p class="forgot"><a href="#">Forgot Password?</a></p>
    				
    				<input type="submit" name="submit" value="Log In" class="button button-block"/></input>
    				
				</form>

			</div>
			
		</div><!-- tab-content -->
		
	</div>
</body>
    <script type="text/javascript">
		function hasClass( elem, klass )
		{
	    	return (" " + elem.className + " " ).indexOf( " " + klass + " " ) > -1;
		}
		
		function doo (nr) {
			if (nr == 2) {
				if (document.getElementById("login").className == "notnow")
				{
					document.getElementById("login").classList.remove("notnow");
					document.getElementById("signup").className += "notnow";
					if (hasClass(document.getElementById("sign"), "active"))
					{
						console.log("smthg");
						document.getElementById("sign").classList.remove("active");
						document.getElementById("log").className += " active";
					}
				}
			} else if (nr == 1) {
				if (document.getElementById("signup").className == "notnow")
				{
					document.getElementById("login").className += "notnow";
					document.getElementById("signup").classList.remove("notnow");
					if (hasClass(document.getElementById("log"), "active"))
					{
						document.getElementById("log").classList.remove("active");
						document.getElementById("sign").className += " active";
					}
				}
			}
		}
		
		
	</script>
</html>
<?php
    if (!isset($_POST['submit']))
        exit();
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";           $dbname = "camagru";
    $login = $_POST['login']; $fname = $_POST['fname'];
    $lname = $_POST['lname']; $passwd = $_POST['passwd'];
    if ((strlen($passwd) < 8 && $passwd !== 'admin') || strlen($login) < 5 && $login !== 'admin')
    {
        echo "passwd or login length is less than 8 </br>";
        exit();
    }
    $email = $_POST['email']; $passwd = hash("md5", $passwd);
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $checker = $conn->prepare("SELECT * FROM users WHERE login = '$login'");
        $checker->execute();
        if ($checker->rowCount() != 1)
            echo "dat login iz not igzist"."<br/>";
        else 
        {
            foreach ($checker as $value)
            {
               
                 if ($value['password'] == $passwd)
                    {
                    if ($value['active'] != 1)
				    {
				    	echo "<center><p>Please, activate your account</p></center>";
				    }
				    else 
				    {
                        $_SESSION['login'] = $value['login'];
                        $_SESSION['fname'] = $value['firstname'];
                        $_SESSION['password'] = $passwd;
                        header("Location: index.php");
				    }
                    }
                else
                    echo "login or password is incorrect";
            }
        }
    }
    catch(PDOException $e)
    {
        echo $sql . "<br>" . $e->getMessage();
    }

?>