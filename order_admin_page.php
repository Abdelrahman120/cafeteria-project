<?php
require 'db/db_controllers.php';
require './utils/draw_table.php';

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
        <?php include "./utils/admin_navbar.php"; ?>
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

                // console.log(selectedValue);
            });

        });

        function setSelectedValue(selectObj) {
            // console.log(selectObj.options);
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