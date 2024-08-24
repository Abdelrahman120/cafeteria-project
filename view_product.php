<?php
require 'credit.php';
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login_form.php");
    exit();
}

if ($_SESSION['user_type'] !== 'admin') {
    header("Location: fatoraOreder.php");
    exit();
}
try {
    $selet_q = "select * from `Product` where id =:p_id";
    $selet_stat = $db->prepare($selet_q);
    $selet_stat->bindParam(":p_id", $_GET["id"], PDO::PARAM_INT);
    $selet_stat->execute();
    $product = $selet_stat->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo  "<h2> {$e->getMessage()} </h2>";
}

try {
    $selet_q = "  SELECT  Category.name AS category_name 
              FROM Product 
              JOIN Category ON Product.cat_id = Category.id   
               WHERE Product.id = :p_id";;
    $selet_stat = $db->prepare($selet_q);
    $selet_stat->bindParam(":p_id", $_GET["id"], PDO::PARAM_INT);
    $selet_stat->execute();
    $category = $selet_stat->fetch(PDO::FETCH_ASSOC);
    // var_dump( $category['category_name'] );
} catch (PDOException $e) {
    echo  "<h2> {$e->getMessage()} </h2>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Product Details</title>
    <link rel="stylesheet" href="style_nav.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <?php require 'navbar.php'; ?>

    <div class="container mt-5">
        <h1 class="mt-5">Product Details</h1>

        <?php if ($product): ?>
            <div class="card mb-3">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="<?= htmlspecialchars($product['image']); ?>" class="img-fluid rounded-start" alt="<?= htmlspecialchars($product['name']); ?>">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h1 class="card-title"><?= htmlspecialchars($product['name']); ?></h1>
                            <p class="card-text"><strong>Price:</strong> <?= htmlspecialchars($product['price']); ?> USD</p>
                            <p class="card-text"><strong>Category:</strong> <?php echo  $category['category_name'] ?> </p>

                            <p class="card-text"><strong>Status:</strong> <?= htmlspecialchars($product['status']); ?></p>
                        </div>

                    </div>

                </div>
            </div>
            <a href='product.php' class='btn btn-success '>Return to back </a>

        <?php else: ?>
            <div class="alert alert-warning" role="alert">
                Product not found.
            </div>
        <?php endif; ?>
    </div>

    <?php require 'credit.php'; ?>
</body>

</html>