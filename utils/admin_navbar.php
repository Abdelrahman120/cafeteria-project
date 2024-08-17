<?php
echo ' 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    ';


//<----ADMIN NAVBAR---->
echo "
        <nav class='navbar navbar-expand-lg navigation-bar sticky-top '>
            <div class='container  '>
                <a class='navbar-brand ps-4 text-light fw-bolder fs-1' href='#'>Cafetria</a>
                <button class='navbar-toggler' type='button' data-bs-toggle='collapse'
                    data-bs-target='#navbarSupportedContent' aria-controls='navbarSupportedContent'
                    aria-expanded='false' aria-label='Toggle navigation'>
                    <span class='navbar-toggler-icon'></span>
                </button>
                <div class='collapse navbar-collapse navbar- ' id='navbarSupportedContent'>
                    <ul class='navbar-nav  me-auto mb-2 mb-lg-0 ' style='margin-left: 17%;'>
                        <li class='nav-item ms-5'>
                            <a class='nav-link active text-light  ' aria-current='page' href='#'>Home</a>
                        </li>
                        <li class='nav-item ms-3'>
                            <a class='nav-link' href='#'>Products</a>
                        </li>

                        <li class='nav-item ms-3'>
                            <a class='nav-link' href='#'>Users</a>
                        </li>
                        <li class='nav-item ms-3'>
                            <a class='nav-link' href='#'>Manual Order</a>
                        </li>
                        <li class='nav-item ms-3'>
                            <a class='nav-link' href='#'>Checks</a>
                        </li>
                    </ul>
                    <button class='btn fs-3 text-light' type='submit'><i class='fas fa-shopping-bag'></i></button>
                    <button class='btn fs-3 text-light' type='submit'><i class='fas fa-user'></i></button>
                    <span>Admin Name</span>

                </div>
            </div>


        </nav>
        ";