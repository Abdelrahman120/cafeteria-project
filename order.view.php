<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require "db/db_conn.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cafe - Login</title>
    <link rel="stylesheet" href="./style/customer_order_view.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navigation-bar">
            <div class="container-fluid">
                <a class="navbar-brand text-light p-1" href="#Home">Cafeteria</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse nav-element d-flex align-item-center justify-content-between"
                    id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-link text-light" href="#">Home</a>
                        <a class="nav-link text-light" href="#">My Orders</a>
                    </div>
                    <div class="user-info navbar-nav">
                        <div class="nav-link bg-black text-light">IMAGE</div>
                        <a class="nav-link" href="#">username</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main class="main-body">
        <form action="" method="post">
            <div class="container">
                <h1>My Orders</h1>
                <div class="row">
                    <label for="date_from" class="fs-4 col-2">Date From :</label>
                    <div class="mb-2 col-4">
                        <input class="form-control " type="date" id="date_from" name="date_from">
                    </div>
                    <label for="date_from" class="fs-4 col-2 ">Date To :</label>
                    <div class="mb-2 col-4">
                        <input class="form-control" type="date" id="date_to" name="date_from">
                    </div>
                </div>
                <div class="table-responsive w-100">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr class="text-center">
                                <th>Order Date</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            <?php
                            try {
                                $selection_query = "SELECT * FROM Orders";
                                $select = $database->prepare($selection_query);
                                $select->execute();

                                $errors = $select->errorInfo();
                                if (isset($errors[2])) {
                                    throw new PDOException($errors);
                                }
                            } catch (PDOException $e) {
                                echo "<tr><td colspan='4' class='text-center text-danger'>Error: " . $e->getMessage() . "</td></tr>";
                            }
                            if (!isset($errors[2])) {
                                while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['date'] . "</td>";
                                    echo "<td>" . $row['status'] . "</td>";
                                    echo "<td>" . $row['total'] . "</td>";
                                    echo "<td><a href='controller/delete_order.php?order_id={$row['id']}' id={$row['id']} class='btn btn-danger'>Cancel</a></td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center">
                        <img src="./images/cup.png" alt="" width="200">
                        <img src="./images/cup.png" alt="" width="200">
                        <img src="./images/cup.png" alt="" width="200">
                        <img src="./images/cup.png" alt="" width="200">
                    </div>
                    <div class="d-flex justify-content-center data-tabs">
                        <div class="pagination">
                            <a href="#" class="page-link">1</a>
                            <a href="#" class="page-link">2</a>
                            <a href="#" class="page-link">3</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script>

    </script>
</body>

</html>