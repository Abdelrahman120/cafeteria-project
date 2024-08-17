<?php
include "credit.php";
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: controller/login_process.php");
    exit();
}
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true || $_SESSION['user_type'] === 'admin') {
    // Redirect admin users to their dashboard
    if ($_SESSION['user_type'] === 'admin') {
        header("Location: all_users.php");
    } else {
        // Redirect non-logged-in users to login page
        header("Location: login_form.php");
    }
    exit();
}



try {
    $stm = $db->prepare("SELECT name, price, image FROM Product");
    $stm->execute();
    $products = $stm->fetchAll(PDO::FETCH_ASSOC);

      $user_id = $_SESSION['user_id'];
      $roomQuery = $db->prepare("SELECT room FROM User WHERE id = :user_id");
      $roomQuery->bindParam(':user_id', $user_id, PDO::PARAM_INT);
      $roomQuery->execute();
      $room = $roomQuery->fetch(PDO::FETCH_ASSOC)['room'];


} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders For User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="fatora.css">
</head>
<body>
    <!-- Start NavBar -->
    <?php require 'navbar_user.php'; ?>
        <!-- End Navbar -->
    <div class="container">
        <!-- Start Search -->
        <!-- <div class="d-flex flex-row-reverse mt-5">
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div> -->
        <!-- End Search -->

        <!-- Start Main Page -->
        <div class="row mt-4">
            <form method="post" action="fatora.php" class="col-sm-12 col-md-6 col-lg-5 border border-dark d-flex flex-column justify-content-between p-3">
                <div class="mb-3" id="items"></div>
                <div class="mb-1">
                    <h4>Notes</h4>
                    <textarea placeholder="Enter Your Notes" style="width: 100%;" name="notes"></textarea>
                    <div class="mt-3">
                        <label for="combo">Room</label>
                        <input type="hidden" id="totalInput" name="total" value="0">
                         <p class="form-control"> <?php echo $room ?> </p>   
                        <!-- <select class="form-select" aria-label="Default select example">
                            <option selected>Open this select menu</option>
                            <option value="1">Combobox</option>
                            <option value="2">Combobox</option>
                            <option value="3">Combobox</option>
                        </select> -->
                    </div>
                </div>
                <hr class="border border-danger border-2 opacity-50">
                <div class="mt-3 d-flex justify-content-between">
                    <p>Total: <span id="totalAmount">0 </span> EGP</p>
                    <button type="submit" class="btn btn-primary w-25">Confirm</button>
                </div>
            </form>
           
            <div class="col-sm-12 col-md-6 col-lg-7 d-flex flex-column">
                <div class="ms-4" id="lastorder">
                    <h3>Latest Order</h3>
                </div>
                <hr class="border border-danger border-2 opacity-50">
                <div>
                    <div class="row ms-4">
                        <?php foreach ($products as $product){ ?>
                            <div data-name="<?php echo htmlspecialchars($product['name']) ?>" data-price="<?php echo htmlspecialchars($product['price']) ?>" class="col-6 col-md-4 col-lg-3 mb-3 text-center click  position-relative" onclick="addToLastOrder('<?php echo htmlspecialchars($product['image']) ?>'); addToFatora('<?php echo htmlspecialchars($product['name']) ?>', <?php echo htmlspecialchars($product['price']) ?>);">
                                <img src="<?php echo htmlspecialchars($product['image']) ?>" alt="<?php echo htmlspecialchars($product['name']) ?>" class="img-fluid">
                                <p><?php echo htmlspecialchars($product['name']) ?></p>
                                <span style="width: 50px; height: 50px; font-size: 12px;" class="badge bg-primary rounded-pill d-block position-absolute top-0 start-0 d-flex justify-content-center align-items-center "><?= htmlspecialchars($product['price']) ?> LE</span>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Main Page -->
    </div>
    <script src="fatora.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

