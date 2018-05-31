<?php
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "camagru";
    try
    {
        $conn = new PDO("mysql:host=$servername;dbnam=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $conn->exec("DROP DATABASE IF EXISTS camagru");
        
        $sql = "CREATE DATABASE $dbname";
        // use exec() because no results are returned
        $conn->exec($sql);
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // sql to create table
        $sql = "CREATE TABLE users (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                password VARCHAR(256) NOT NULL,
                login VARCHAR(40) NOT NULL,
                firstname VARCHAR(30) NOT NULL,
                lastname VARCHAR(30) NOT NULL,
                email VARCHAR(50) NOT NULL,
                reg_date TIMESTAMP, 
                active INT(1) UNSIGNED
        )";
        $conn->exec($sql);
        echo "Table users created successfully";
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "CREATE TABLE photos (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                author VARCHAR(50) NOT NULL,
                reg_date TIMESTAMP,
                path VARCHAR(50) NOT NULL
        
        )";
        $conn->exec($sql);
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "CREATE TABLE comments (
                id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                photo_id INT(6),
                login VARCHAR(30) NOT NULL,
                comment VARCHAR(255),
                reg_date TIMESTAMP
        )";
        $conn->exec($sql);
		$sql = "CREATE TABLE likes(
				id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
				image_id INT(6),
				author_login VARCHAR(30) NOT NULL
				)";
        $conn->exec($sql);
        echo "Database created successfully<br>";
    }
    catch(PDOException $e)
    {
        echo $sql . "<br>" . $e->getMessage();
    }
    session_start();
    session_destroy();
    $conn = null;
?>