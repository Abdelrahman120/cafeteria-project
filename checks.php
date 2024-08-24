<?php
require "credit.php";
session_start();

// to check if the user is logged in or the session is not set
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login_form.php");
    exit();
}

// to check if the user is an admin
if ($_SESSION['user_type'] !== 'admin') {
    header("Location: fatoraOreder.php");
    exit();
}

// check if the database is connected
if ($db) {
    try {
        // make select query to get all users who made orders and their total amount
        $select_user_query = "
            SELECT u.id, u.name, COALESCE(SUM(o.total), 0) AS totalAmount
            FROM User u 
            LEFT JOIN Orders o ON u.id = o.user_id
            WHERE u.type = 'user' and o.total != 0
            GROUP BY u.id, u.name
            ;
        ";
        $stmt = $db->prepare($select_user_query);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // format the total amount to 2 decimal after the .
        foreach ($users as &$user) {
            $user['totalAmount'] = number_format((float)$user['totalAmount'], 2, '.', '');
        }

        // make select query to get all orders
        $select_order_query = "SELECT id, user_id, date, total FROM Orders;";
        $stmt = $db->prepare($select_order_query);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // format the total to 2 decimal after the .
        foreach ($orders as &$order) {
            $order['total'] = number_format((float)$order['total'], 2, '.', '');
        }

        // make select query to get
        $select_product_query = "
            SELECT op.order_id, p.name, p.image, op.quantity, p.price
            FROM Order_product op
            JOIN Product p ON op.product_id = p.id;
        ";
        $stmt = $db->prepare($select_product_query);
        $stmt->execute();
        $orderProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $usersJson = json_encode($users);
        $ordersJson = json_encode($orders);
        $orderProductsJson = json_encode($orderProducts);
    } catch (PDOException $e) {
        echo "{$e->getMessage()}";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="checks.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Checks</title>
    <style>
        .table {
            margin-top: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
        }
        
        th {
            background-color: #5C3C1B;
            color: white;
        }

        #filter_button {
            background-color: #5C3C1B;
            color: white;
        }

        #filter_button:hover {
            background-color: #3e2b14;
        }

        .btn-custom {
            background-color: #5C3C1B;
            color: white;
            border-radius: 5px;
        }

        .btn-custom:hover {
            background-color: #3e2b14;
        }
    </style>
</head>

<body>
    <!-- require navbar from the file -->
    <?php require 'navbar.php'; ?>
    <div class="container">
        <h1 class="text-center mt-4 mb-4">Checks</h1>
        
        <div class="row my-3">
            <label for="date_from" class="fs-4 col-md-2 col-form-label">Date From:</label>
            <div class="col-md-4 mb-3">
                <input class="form-control" type="date" id="date_from" name="date_from">
            </div>
            <label for="date_to" class="fs-4 col-md-2 col-form-label">Date To:</label>
            <div class="col-md-4 mb-3">
                <input class="form-control" type="date" id="date_to" name="date_to">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <button id="filter_button" class="btn btn-custom w-100">Filter</button>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">
                <label class="fs-4" for="users">User :</label>
                <select class="form-control" name="users" id="users">
                    <option value=""></option>
                </select>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <table class="table table-striped table-bordered" id="f_table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Total Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <table class="table table-striped table-bordered" id="s_table" style="display:none;">
                    <thead>
                        <tr>
                            <th>Order Date</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <div class=  'container mt-3 mb-5  '>
        <div class="d-grid gap-3 " id="images_container" style="display:none;grid-auto-flow: column; overflow-x: auto; white-space: nowrap;"></div>
        </div> 
    </div>

    <script src="checks.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // short echo tags for give the php data to javascript
        const usersData = <?= $usersJson ?>;
        const ordersData = <?= $ordersJson ?>;
        const orderProductsData = <?= $orderProductsJson ?>;
    </script>
</body>

</html>
