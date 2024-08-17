<?php

$db_name = 'cafetria';
$db_host = 'localhost';
$db_user = 'newuser';
$db_pass = 'password';

function connect_db($db_name,$db_host, $db_user, $db_pass)
{

try {

    $dsn = "mysql:host={$db_host};dbname={$db_name};";
    $pdo = new PDO($dsn, $db_user, $db_pass);

} catch (PDOException $e) {
echo  "<h2> {$e->getMessage()} </h2>" ;
           return false;
       }
        return $pdo;

}

$db=connect_db($db_name,$db_host,$db_user,$db_pass);


    