<?php
require 'credits.php';

function connection_using_pdo($db_name, $db_user, $db_pass, $db_host, $db_port)
{
    try {
        $dsn = "mysql:host={$db_host};port={$db_port};dbname={$db_name};";
        $pdo = new PDO($dsn, $db_user, $db_pass);
        // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    } catch (PDOException $e) {
        echo "Connection Error: " . $e->getMessage();
        return false;
    }
    return $pdo;
}
