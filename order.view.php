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
                                $start = 0;
                                $rowsPerPage = 4;
                                $start_date = '2000-12-30 00:00:00';
                                $end_date;
                                try {
                                    $totalRecords = "SELECT * FROM Orders WHERE `user_id` = :user_id";
                                    $records = $database->prepare($totalRecords);
                                    $records->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
                                    $records->execute();

                                    $num_of_all_records = $records->rowCount();
                                    $pages = ceil($num_of_all_records / $rowsPerPage);

                                    if (isset($_GET['page-num'])) {
                                        $page = $_GET['page-num'] - 1;
                                        $start = $page * $rowsPerPage;
                                    }

                                    $selection_query = "SELECT `id`, `date`, `status`, `total` FROM Orders WHERE `user_id` = :user_id LIMIT $start, $rowsPerPage";


                                    $select = $database->prepare($selection_query);
                                    $select->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
                                    $select->execute();

                                    //$num = $select->rowCount();
                            
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
                            <?php
                            for ($i = 0; $i < 4; $i++) {
                                echo "<img src='./images/cup.png' alt='' width='200'>";
                            }
                            ?>
                        </div>

                        <div class="d-flex justify-content-center mt-4">
                            <ul class="pagination">
                                <li class="page-item"><a class="page-link pagination" href="?page-num=1">First</a></li>
                                <?php
                                if (isset($_GET['page-num']) && $_GET['page-num'] > 1) {
                                    ?>
                                    <li class="page-item"><a class="page-link pagination"
                                            href="?page-num=<?php echo $_GET['page-num'] - 1 ?>">Previous</a></li>
                                    <?php
                                } else {
                                    ?>
                                    <li class="page-item"><a class="page-link pagination">Previous</a></li>
                                    <?php
                                }

                                ?>
                                <?php
                                for ($counter = 1; $counter <= $pages; $counter++) {
                                    ?>
                                    <li class="page-item"><a class="page-link bg-white"
                                            href="?page-num=<?php echo $counter ?>"><?php echo $counter ?></a></li>
                                    <?php
                                }
                                ?>

                                <?php
                                if (!isset($_GET['page-num'])) {
                                    ?>
                                    <li class="page-item"><a class="page-link pagination">Next</a></li>
                                    <?php
                                } else {
                                    if ($_GET['page-num'] >= $pages) {
                                        ?>
                                        <li class="page-item"><a class="page-link pagination">Next</a></li>

                                        <?php
                                    } else {
                                        ?>
                                        <li class="page-item"><a class="page-link pagination"
                                                href="?page-num=<?php echo $_GET['page-num'] + 1 ?>">Next</a>
                                        </li>


                                        <?php
                                    }
                                }
                                ?>
                                <li class="page-item"><a class="page-link pagination"
                                        href="?page-num=<?php echo $pages ?>">Last</a>
                                </li>
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