<?php
$errors = [];
$old_data = [];

if (isset($_GET['errors'])) {
    $errors = json_decode($_GET['errors'], true);
}

if (isset($_GET['old_data'])) {
    $old_data = json_decode($_GET['old_data'], true);
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>

<!doctype html>
<html lang="en">

<head>
    <title>Cafeteria Login</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="style/login_form.css">
</head>

<body>
    <div class="login-container h-100">
        <form action="controller/login_process.php" method="post" class="d-flex justify-content-between">
            <div class="login-header-text">Cafeteria</div>
            <div class="container">
                <div class="mt-3">
                    <label for="inputEmail" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control emailInput" id="inputEmail"
                        placeholder="Enter Your Email..." aria-describedby="emailHelp" value="<?php $userEmail = isset($old_data['email']) ? $old_data['email'] : "";
                        echo $userEmail ?>">
                    <span class="text-danger float-end">
                        <?php
                        $emailError = isset($errors['email']) ? $errors['email'] : '';
                        echo $emailError;
                        ?>
                    </span>
                    <!-- <span class="text-danger float-end"> -->
                    <?php
                    // $emailSessionError = isset($_GET['errors']) ? $_GET['errors'] : '';
                    // echo $emailSessionError;
                    ?>
                    <!-- </span> -->
                </div>
                <div class="my-4">
                    <label for="inputPassword" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control passwordInput" id="inputPassword"
                        placeholder="Enter Your Password...">
                    <div id="forget" class="form-text">Forget Password?
                        <span class="text-danger float-end">
                            <?php
                            $passwordError = isset($errors['password']) ? $errors['password'] : '';
                            echo $passwordError;
                            ?>
                        </span>
                        <span class="text-danger float-end">
                            <?php
                            $passwordSessionError = isset($_GET['session_errors']) ? $_GET['session_errors'] : '';
                            echo $passwordSessionError;
                            ?>
                        </span>

                    </div>

                </div>
                <button type="submit" class="btn login-btn my-2 w-100">Login</button>
                <div>
                    <span class="d-flex justify-content-between">
                        Don't have an account?
                        <a href="registration_form.php">
                            Create an Account
                        </a>
                    </span>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
        </script>
</body>

</html>