<?php
require "credit.php";
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    // Redirect non-logged-in users to login page
    header("Location: login_form.php");
    exit();
}

if ($_SESSION['user_type'] !== 'admin') {
    // Redirect non-admin users to a different page, for example, user dashboard
    header("Location: fatoraOreder.php");
    exit();
}
if ($db) {
    try {
        $select_user_query = "
            SELECT u.id, u.name, COALESCE(SUM(o.total), 0) AS totalAmount
            FROM User u 
            LEFT JOIN Orders o ON u.id = o.user_id
            WHERE u.type = 'user'
            GROUP BY u.id, u.name
            ;

        ";
        $stmt = $db->prepare($select_user_query);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($users as &$user) {
            $user['totalAmount'] = number_format((float)$user['totalAmount'], 2, '.', '');
        }

        $select_order_query = "SELECT id, user_id, date, total FROM Orders;";
        $stmt = $db->prepare($select_order_query);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($orders as &$order) {
            $order['total'] = number_format((float)$order['total'], 2, '.', '');
        }

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
</head>

<body>
    <?php require 'navbar.php'; ?>
    <div class="container">
        <h1 class="text-center mt-2">Checks</h1>
        <!-- <div class="row">
            <div class="mb-2">
                <label for="date_from">Date From :</label>
                <input class="form-control" type="date" id="date_from" name="date_from">
            </div>
            <div class="mb-2">
                <label for="date_to">Date To :</label>
                <input class="form-control" type="date" id="date_to" name="date_to">
            </div>
        </div> -->
        <div class="row my-3">
                        <label for="date_from" class="fs-4 col-2">Date From :</label>
                        <div class="mb-2 col-4">
                            <input class="form-control " type="date" id="date_from" name="date_from">
                        </div>
                        <label for="date_from" class="fs-4 col-2 ">Date To :</label>
                        <div class="mb-2 col-4">
                            <input class="form-control" type="date" id="date_to" name="date_from">
                        </div>
                    </div>
        <div class="mb-2">
            <label class="fs-4" for="users">User :</label>
            <select class="form-control" name="users" id="users">
                <option value="None">None</option>
            </select>
        </div>
        <div class="d-flex flex-row justify-content-between">
            <div>
                <table class="table" id="f_table">
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
            <div>
                <table class="table" id="s_table" style="display:none;">
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
        <div class="row d-flex flex-column justify-content-center align-items-center" id="images_container" style="display:none;"></div>
    </div>

  <div class="container">
  
  </div>
    <script src="checks.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const usersData = <?= $usersJson ?>;
        const ordersData = <?= $ordersJson ?>;
        const orderProductsData = <?= $orderProductsJson ?>;
    </script>
</body>

</html>