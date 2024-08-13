<?php

$db_name = 'PHP';
$db_host = 'localhost';
$db_user = 'jaafar';
$db_pass = 'MHJaafar@019364';
$db_port = '3306';

function connection_using_pdo($db_host, $db_user, $db_pass, $db_name)
{
    try {
        $dsn = "mysql:host={$db_host};dbname={$db_name};";
        $pdo = new PDO($dsn, $db_user, $db_pass);

    } catch (PDOException $e) {
        echo "Connection Error: " . $e->getMessage();
        return false;
    }
    return $pdo;

}

$database = connection_using_pdo($db_host, $db_user, $db_pass, $db_name);



// $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);