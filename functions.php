<?php 
function verif($email)
{
    $ar2 = Array();
    $arr = explode("@", $email);
    if (count($arr) != 2)
        return (0);
    if (strlen($arr[0]) > 4)
    {
        $ar2 = explode(".", $arr[1]);
        if (strlen($ar2[0]) > 3 && count($ar2) == 2)
            return (1);
    }
    return (0);
}
function logVerif($login, $passwd)
{
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";           $dbname = "camagru";
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $checker = $conn->prepare("SELECT * FROM users WHERE login = '$login'");
    $checker->execute();
    if ($checker->rowCount() != 1)
       return (1);
    else
    {
        foreach ($checker as $value)
        {
            if ($value['password'] == $passwd)
                 return (0);
        }
    }
    return (1);
}
?>