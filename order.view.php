<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require "db/db_conn.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders</title>
    <link rel="stylesheet" href="./style/customer_order_view.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php if ($_SESSION['login'] === true) {
        $session_email = $_SESSION['login-email'];
        $session_user = explode("@", $session_email); ?>
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
                        <div class="nav-link text-light"><i class="fa-regular fa-user"></i></div>
                        <?php echo "<a class='nav-link text-light' href='#'>{$session_user[0]}</a>"; ?>
                        <a class="nav-link text-light" href="./controller/logout_process.php"><i
                                class="fa-solid fa-sign-out"></i></a>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main class="main-body">
        <div class="container">
            <form action="order.view.php" method="post" class="row">
                <label for="date_from" class="fs-4 col-2">Date From :</label>
                <div class="mb-2 col-4">
                    <input class="form-control " type="date" id="date_from" name="start_date">
                </div>
                <label for="date_from" class="fs-4 col-2 ">Date To :</label>
                <div class="mb-2 col-4">
                    <input class="form-control" type="date" id="date_to" name="end_date">
                </div>
                <div class="mb-2 d-flex justify-content-center align-content-center">
                    <button type="submit" name="filter_date" class="btn text-light p-2 w-25"
                        style="background-color: #68513B;">Filter</button>
                </div>
            </form>
        </div>
        <form action="" method="post">
            <div class="container">
                <h1>My Orders</h1>
                <div class="row">

                    <?php
                        if (isset($_POST['filter_date'])) {
                            $start_date = $_POST['start_date'];
                            $end_date = $_POST['end_date'];

                            $query = "SELECT * FROM Orders WHERE `date` BETWEEN '$start_date' AND '$end_date'";
                            $stmt = $database->prepare($query);
                            $num_of_records = $stmt->fetchColumn();

                            if ($num_of_records > 0) { ?>
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
                                        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<tr>";
                                            echo "<td>" . ($data['date']) . "</td>";
                                            echo "<td>" . ($data['status']) . "</td>";
                                            echo "<td>" . ($data['total']) . "</td>";
                                            echo "<td><a href='controller/delete_order.php?order_id={$data[' id']}' id='{$data['
                                                        id']}' class='btn btn-danger'>Cancel</a></td>";
                                            echo "</tr>";
                                        } ?>
                        </tbody>
                    </table>

                    <?php


                            }
                        } else {

                            ?>
                    <?php
                            $start = 0;
                            $rowsPerPage = 4;

                            try {
                                // Query to get the total number of records to use in pagination
                                $totalRecordsQuery = "SELECT COUNT(*) FROM Orders WHERE user_id = :user_id";
                                $records = $database->prepare($totalRecordsQuery);
                                $records->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
                                $records->execute();
                                $num_of_all_records = $records->fetchColumn();
                                $pages = ceil($num_of_all_records / $rowsPerPage);

                                // Get current page number
                                $page = isset($_GET['page-num']) ? (int) $_GET['page-num'] : 1;
                                $start = ($page - 1) * $rowsPerPage;

                                // Query to get the sum of the 'total' column for the current page
                                $totalQuery = "SELECT SUM(total) AS total_amount FROM Orders WHERE user_id = :user_id";
                                $sumQuery = $database->prepare($totalQuery);
                                $sumQuery->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
                                $sumQuery->execute();
                                $sum = $sumQuery->fetch(PDO::FETCH_ASSOC);

                                // Query to get the data for the current page
                                $selectionQuery = "SELECT id, date, status, total FROM Orders WHERE user_id = :user_id LIMIT :start, :limit";
                                $select = $database->prepare($selectionQuery);
                                $select->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
                                $select->bindParam(':start', $start, PDO::PARAM_INT);
                                $select->bindParam(':limit', $rowsPerPage, PDO::PARAM_INT);
                                $select->execute();
                            } catch (PDOException $e) {
                                echo "<tr><td colspan='4' class='text-center text-danger'>Error: " . $e->getMessage() . "</td></tr>";
                            }
                            ?>

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
                                        if (!isset($errors[2])) {
                                            while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<tr>";
                                                echo "<td>" . ($row['date']) . "</td>";
                                                echo "<td>" . ($row['status']) . "</td>";
                                                echo "<td>" . ($row['total']) . "</td>";
                                                echo "<td><a href='controller/delete_order.php?order_id={$row['id']}' id='{$row['id']}' class='btn btn-danger'>Cancel</a></td>";
                                                echo "</tr>";
                                            }
                                        }
                                        ?>
                            </tbody>
                        </table>
                        <?php } ?>
                        <div class="d-flex justify-content-end">
                            <?php
                                // Check if sum is not null before formatting
                                $totalAmount = isset($sum['total_amount']) ? $sum['total_amount'] : 0;
                                echo "<h5>Total Amount: " . number_format($totalAmount, 2) . " EGP</h5>";
                                ?>
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            <ul class="pagination">
                                <li class="page-item bg-white"><a class="page-link pagination"
                                        href="?page-num=1">First</a>
                                </li>
                                <?php
                                    if ($page > 1) {
                                        echo '<li class="page-item"><a class="page-link pagination" href="?page-num=' . ($page - 1) . '">Previous</a></li>';
                                    } else {
                                        echo '<li class="page-item disabled"><a class="page-link pagination">Previous</a></li>';
                                    }

                                    for ($counter = 1; $counter <= $pages; $counter++) {
                                        echo '<li class="page-item ' . ($counter == $page ? 'active' : '') . '"><a class="page-link" href="?page-num=' . $counter . '">' . $counter . '</a></li>';
                                    }

                                    if ($page < $pages) {
                                        echo '<li class="page-item"><a class="page-link pagination" href="?page-num=' . ($page + 1) . '">Next</a></li>';
                                    } else {
                                        echo '<li class="page-item disabled"><a class="page-link pagination">Next</a></li>';
                                    }
                                    ?>
                                <li class="page-item bg-white"><a class="page-link pagination"
                                        href="?page-num=<?php echo $pages ?>">Last</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIage" crossorigin="anonymous">
    </script>
    <script>

    </script>
</body>
<?php } else {
        header("Location: login_form.php");
        exit();
    } ?>

</html>