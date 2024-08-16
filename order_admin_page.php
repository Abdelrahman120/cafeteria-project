<?php
require 'db/db_controllers.php';
require './utils/item_card.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Page</title>
    <link rel="stylesheet" href="./style/customer_order_view.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .topbar {
            border-top-left-radius: 50px;
            border-bottom-right-radius: 50px;
            border-top-right-radius: 20px;
            border-bottom-left-radius: 20px;
        }
    </style>
</head>

<body>

    <header>
        <!----NAVBAR---->
        <nav class="navbar navbar-expand-lg navigation-bar sticky-top ">
            <div class="container  ">
                <a class="navbar-brand ps-4 text-light fw-bolder fs-1" href="#">Cafetria</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse navbar- " id="navbarSupportedContent">
                    <ul class="navbar-nav  me-auto mb-2 mb-lg-0 " style="margin-left: 17%;">
                        <li class="nav-item ms-5">
                            <a class="nav-link active text-light  " aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item ms-3">
                            <a class="nav-link" href="#">Products</a>
                        </li>

                        <li class="nav-item ms-3">
                            <a class="nav-link" href="#">Users</a>
                        </li>
                        <li class="nav-item ms-3">
                            <a class="nav-link" href="#">Manual Order</a>
                        </li>
                        <li class="nav-item ms-3">
                            <a class="nav-link" href="#">Checks</a>
                        </li>
                    </ul>
                    <button class="btn fs-3 text-light" type="submit"><i class="fas fa-shopping-bag"></i></button>
                    <button class="btn fs-3 text-light" type="submit"><i class="fas fa-user"></i></button>
                    <span>Admin Name</span>

                </div>
            </div>


        </nav>
    </header>
    <main class="main-body">
        <div class="container">
            <h1 style="font-size: 3.5rem;">Orders</h1>
        </div>


        <?php
        try {
            $selection_query2 = "SELECT distinct order_id,room,ext,name,id,status,total,date   FROM admin_order;";
            $select2 = $database->prepare($selection_query2);
            $select2->execute();

        } catch (PDOException $e) {
            echo "<tr><td colspan='4' class='text-center text-danger'>Error: " . $e->getMessage() . "</td></tr>";
        }
        $allRows = $select2->fetchAll();
        foreach ($allRows as $row) {


            OpenTable($row);
            orderDetails($row);
            closeTable();
            echo " <div class=  'container mt-3 mb-5  '>
                <div class=  'd-grid gap-3  ' style=  'grid-auto-flow: column; overflow-x: auto; white-space: nowrap;'>  ";


            getOrderItems($database, $row['order_id']);

        }
        ?>


        <hr><br>

    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script>
        var selectedValue = "pending";
        var selection = document.getElementsByClassName('status');
        Array.prototype.forEach.call(selection, function (entry) {
            setSelectedValue(entry);

            entry.addEventListener('change', function () {
                selectedValue = this.value;

                console.log(selectedValue);


            });

        });





        function setSelectedValue(selectObj) {
            console.log(selectObj.options);

            for (var i = 0; i < selectObj.options.length; i++) {

                if (selectObj.options[i].value == selectObj.getAttribute("selected-value")) {
                    selectObj.options[i].selected = true;
                    return;
                }
            }
        }

        function redirect(id) {
            window.location.href = `./db/db_update.php?id=${id}&status=${selectedValue}`
        }


    </script>
</body>

</html>