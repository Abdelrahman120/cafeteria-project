<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require "credit.php";

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
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.0/themes/smoothness/jquery-ui.min.css"
        integrity="sha512-B2s07Riaz6vCNqR1AsLDeXzzVUWrXwBO+Ffr85wHIH3oKKxXJZjyWLRB5S0Ch7L74fQHY+9L36nTpkB5oKJZcA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>
    
    <?php if ($_SESSION['login'] === true) {
        $session_email = $_SESSION['email'];
        $session_user = explode("@", $session_email); ?>
        <header>
            <?php require 'navbar_user.php'; ?>
        </header>
        <main class="main-body">
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="mb-2 col-4">
                        <label for="date_from" class="fs-4 col-4">From Date:</label>
                        <input class="form-control " type="text" placeholder="From date" id="from_date" name="from_date">
                    </div>
                    <div class="mb-2 col-4">
                        <label for="date_from" class="fs-4 col-4 ">To Date:</label>
                        <input class="form-control" type="text" placeholder="To date" id="to_date" name="to_date">
                    </div>
                    <div class="mb-2 d-flex justify-content-center align-content-center mt-2">
                        <input type="button" value="Filter" class="btn text-light p-2 w-25" id="filter_date"
                            style="background-color: #68513B;">
                    </div>
                </div>
            </div>
            <form action="" method="post">
                <div id="order_table" class="container table-responsive">
                    <?php
                    // Start of Pagination Code

                    $start = 0; // Start Point for the Query.
                    $rowsPerPage = 4; // Number of rows displayed per page.

                    $records = $db->prepare("SELECT * FROM Orders WHERE `user_id` = :user_id ORDER BY `total`");
                    $records->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
                    $records->execute();
                    $num_of_records = $records->rowCount();
                    $pages = ceil($num_of_records / $rowsPerPage);

                    // Query to get the sum of the 'total' column for the current page
                    $totalQuery = "SELECT SUM(total) AS total_amount FROM Orders WHERE user_id = :user_id";
                    $sumQuery = $db->prepare($totalQuery);
                    $sumQuery->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
                    $sumQuery->execute();
                    $sum = $sumQuery->fetch(PDO::FETCH_ASSOC);

                    if (isset($_GET['page-num'])) {
                        $page = $_GET['page-num'] - 1;
                        $start = $page * $rowsPerPage;
                    }

                    $selectQuery = "SELECT * FROM Orders WHERE `user_id` = :user_id ORDER BY `date` DESC LIMIT {$start}, {$rowsPerPage}";
                    $stmt = $db->prepare($selectQuery);
                    $stmt->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_INT);
                    $stmt->execute();


                    ?>
                    <table class="table table-striped table-bordered mt-3 text-center" id="table">
                        <thead>
                            <tr>
                                <th scope="col">Order Date</th>
                                <th scope="col">Status</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>" . ($row['date']) . "</td>";
                                echo "<td>" . ($row['status']) . "</td>";
                                echo "<td>" . ($row['total']) . " EGP</td>";
                                echo "<td><a href='controller/delete_order.php?order_id={$row['id']}' id='{$row['id']}'
                                            class='btn btn-danger'>Cancel</a></td>";
                                echo "</tr>";
                            }

                            ?>
                        </tbody>
                    </table>
                    <div class="container d-flex justify-content-end">
                        <?php
                        // Check if sum is not null
                        $totalAmount = isset($sum['total_amount']) ? $sum['total_amount'] : 0;
                        echo "<h5>Total Amount: " . "<span class='text-success'>" . number_format($totalAmount, 2) . " EGP</span>" . "</h5>";
                        ?>
                    </div>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    <ul class="pagination">
                        <li class="page-item bg-white"><a class="page-link pagination" href="?page-num=1">First</a>
                        </li>
                        <?php
                        if (isset($_GET['page-num']) && $_GET['page-num'] > 1) {
                        ?>
                            <li class='page-item'><a class='page-link pagination'
                                    href='?page-num=<?php echo $_GET['page-num'] - 1 ?> '>Previous</a></li>
                        <?php
                        } else {
                        ?>
                            <li class='page-item'><a class='page-link pagination'>Previous</a></li>
                        <?php
                        }

                        for ($counter = 1; $counter <= $pages; $counter++) {
                        ?>
                            <li class="page-item <?php echo $counter == $page + 1 ? 'active' : '' ?>">
                                <a class="page-link" href="?page-num=<?php echo $counter ?>"> <?php echo $counter ?> </a>
                            </li>
                        <?php
                        }

                        if (!isset($_GET['page-num'])) {
                        ?>
                            <li class="page-item"><a class="page-link pagination" href="?page-num=2">Next</a></li>
                            <?php
                        } else {
                            if ($_GET['page-num'] >= $pages) {
                            ?>
                                <li class="page-item"><a class="page-link pagination">Next</a></li>
                            <?php
                            } else {
                            ?>
                                <li class="page-item"><a class="page-link pagination"
                                        href="?page-num=<?php echo $_GET['page-num'] + 1 ?>">Next</a></li>
                        <?php
                            }
                        } ?>
                        <li class=" page-item bg-white"><a class="page-link pagination"
                                href="?page-num=<?php echo $pages ?>">Last</a></li>
                    </ul>
                </div>
            </form>
        </main>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIage" crossorigin="anonymous">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous" referrerpolicy="no-referrer">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.0/jquery-ui.min.js"
            integrity="sha512-MlEyuwT6VkRXExjj8CdBKNgd+e2H+aYZOCUaCrt9KRk6MlZDOs91V1yK22rwm8aCIsb5Ec1euL8f0g58RKT/Pg=="
            crossorigin="anonymous" referrerpolicy="no-referrer">
        </script>

        <script>
            $(document).ready(function() {

                $.datepicker.setDefaults({
                    dateFormat: ' yy-mm-dd'
                });
                $(function() {
                    $("#from_date").datepicker();
                    $("#to_date").datepicker();
                });
                $('#filter_date').click(function() {
                    let from_date = $('#from_date').val();
                    let to_date = $('#to_date').val();
                    if (from_date != '' && to_date != '') {
                        $.ajax({
                            url: "filtration_process.php",
                            method: "POST",
                            data: {
                                from_date: from_date,
                                to_date: to_date
                            },
                            success: function(data) {
                                $('#order_table').html(data);
                            }
                        })
                    } else {
                        alert('Please select dates first.')
                    }
                })
            });
        </script>
</body>
<?php } else {
        header("Location: login_form.php");
        exit();
    } ?>

</html>