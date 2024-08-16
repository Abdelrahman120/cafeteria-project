<?php

$db_name = 'PHP';
$db_host = 'localhost';
$db_user = 'jaafar';
$db_pass = 'MHJaafar@019364';
$db_port = '3306';

function connection_using_pdo($db_host, $db_user, $db_pass, $db_name, $db_port)
{
    try {
        $DSN = "mysql:host={$db_host};port={$db_port};dbname={$db_name};";
        $PDO = new PDO($DSN, $db_user, $db_pass);
        $PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Connection Error: " . $e->getMessage();
        return false;
    }
    return $PDO;
}

$database = connection_using_pdo($db_host, $db_user, $db_pass, $db_name, $db_port);