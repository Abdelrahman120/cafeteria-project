<?php

session_start();
// echo $_SESSION['user_id'];
// exit;

include "db/db_conn.php";

if (isset($_POST['from_date'], $_POST['to_date'])) {
    $filterQuery = "SELECT * FROM Orders WHERE `date` BETWEEN :from_date AND :to_date AND `user_id` = :user_id";
    $fetchByDate = $db->prepare($filterQuery);
    $fetchByDate->bindParam(":from_date", $_POST['from_date']);
    $fetchByDate->bindParam(":to_date", $_POST['to_date']);
    $fetchByDate->bindParam("user_id", $_SESSION['user_id'], PDO::PARAM_INT);
    $fetchByDate->execute();
    // echo "Hello World";
    // exit;
    $num_of_records = $fetchByDate->rowCount();

    $output .= "
        <table class='table table-striped table-bordered text-center'>
            <thead>
                <tr>
                    <td>Order Date</td>
                    <td>Status</td>   
                    <td>Amount</td>   
                    <td>Action</td>   
                </tr>
            </thead>
            <tbody>
    ";

    if ($num_of_records > 0) {
        while ($row = $fetchByDate->fetch(PDO::FETCH_ASSOC)) {
            $output .= "
                <tr>
                    <td>" . $row['date'] . "</td>
                    <td>" . $row['status'] . "</td>
                    <td>" . $row['total'] . " EGP</td>
                    <td><a href='controller/delete_order.php?order_id={$row['id']}' id='{$row['id']}' class='btn btn-danger'>Cancel</a></td>
                </tr>
        ";
        }
    } else {
        $output .= "
            <tr>
                <td colspan='5' class='text-center text-danger'>No order found</td>
            </tr>
        ";
    }
    $output .= "
            </tbody>
        </table>";
    echo $output;
}
