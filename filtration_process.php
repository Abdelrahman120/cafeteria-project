<?php

session_start();
// echo $_SESSION['user_id'];
// exit;

include "credit.php";

if (isset($_POST['from_date'], $_POST['to_date'])) {
    $filterQuery = "SELECT * FROM Orders WHERE `date` BETWEEN :from_date AND :to_date AND `user_id` = :user_id ORDER BY `date` desc";
    $fetchByDate = $db->prepare($filterQuery);
    $fetchByDate->bindParam(":from_date", $_POST['from_date']);
    $fetchByDate->bindParam(":to_date", $_POST['to_date']);
    $fetchByDate->bindParam("user_id", $_SESSION['user_id'], PDO::PARAM_INT);
    $fetchByDate->execute();

    $totalQuery = "SELECT SUM(total) AS total_amount FROM Orders WHERE `date` BETWEEN :from_date AND :to_date AND `user_id` = :user_id";
    $sumQuery = $db->prepare($totalQuery);
    $sumQuery->bindParam(":from_date", $_POST['from_date']);
    $sumQuery->bindParam(":to_date", $_POST['to_date']);
    $sumQuery->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $sumQuery->execute();
    $sum = $sumQuery->fetch(PDO::FETCH_ASSOC);

    $num_of_records = $fetchByDate->rowCount();

    // echo "Hello World";
    // exit;

    $amount = isset($sum['total_amount']) ? $sum['total_amount'] : 0;
    $amount = number_format($amount, 2); // Shows 2 numbers after decimal.

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
        </table>
        ";
    $output .= "<div class='container d-flex justify-content-end'>
    <h5>Total Amount: <span class='text-success'> $amount EGP</span></h5>
    </div>";
    echo $output;
}