<?php
include "credit.php";
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login_form.php");
    exit();
}

if ($_SESSION['user_type'] !== 'admin') {
    header("Location: fatoraOreder.php");
    exit();
}

if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
    $_SESSION['selected_user_id'] = $_POST['user_id'];
}

$selected_user_id = isset($_SESSION['selected_user_id']) ? $_SESSION['selected_user_id'] : null;

try {
    $stm = $db->prepare("SELECT name, price, image FROM Product WHERE status = 'available'");
    $stm->execute();
    $products = $stm->fetchAll(PDO::FETCH_ASSOC);

    $userQuery = $db->prepare("SELECT id, name FROM User WHERE type = 'user'");
    $userQuery->execute();
    $users = $userQuery->fetchAll(PDO::FETCH_ASSOC);

    if ($selected_user_id) {
        $roomQuery = $db->prepare("SELECT room FROM User WHERE id = :user_id");
        $roomQuery->bindParam(':user_id', $selected_user_id);
        $roomQuery->execute();
        $room = $roomQuery->fetch(PDO::FETCH_ASSOC);
        $room_number = $room ? $room['room'] : 'No room assigned';
    } else {
        $room_number = 'Room will be auto-filled';
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders For Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="fatora.css">
</head>
<body>
    
    
    <?php require "navbar.php";
    session_start();
    if (isset($_SESSION['error_messages']) && !empty($_SESSION['error_messages'])) {
        foreach ($_SESSION['error_messages'] as $error) {
            echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
        }
        // Clear the error messages
        $_SESSION['error_messages'] = [];
    }
    ?>
    
    <div class="container">
        <form method="post" action="" class="mt-3 w-50 ">
            <label for="userSelect">Choose User:</label>
            <select name="user_id" id="userSelect" class="form-select mb-3" onchange="this.form.submit()" required>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user['id'] ?>" <?= isset($_SESSION['selected_user_id']) && $_SESSION['selected_user_id'] == $user['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($user['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <!-- Start Main Page -->
        <div class="row mt-4">
            <form method="post" action="adminFatora.php" class="col-sm-12 col-md-6 col-lg-5 border border-dark d-flex flex-column justify-content-between p-3">
                <div class="mb-3" id="items"></div>
                <div class="mb-1">
                    <h4>Notes</h4>
                    <textarea placeholder="Enter Your Notes" style="width: 100%;" name="notes"></textarea>
                    <div class="mt-3">
                        <label for="roomNumber">Room</label>
                        <p id="roomNumber" class="form-control"><?= htmlspecialchars($room_number) ?></p>   
                        <input type="hidden" id="totalInput" name="total" value="0">
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
                                <p class="fw-bold fs-3"><?php echo htmlspecialchars($product['name']) ?></p>
                                <span style="width: 70px; height: 70px; font-size: 15px;" class="badge bg-white text-dark rounded-pill d-block position-absolute top-0 start-0 d-flex justify-content-center align-items-center "><?= htmlspecialchars($product['price']) ?> LE</span>
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