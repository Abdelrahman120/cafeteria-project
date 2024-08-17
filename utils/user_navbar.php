<?php
echo ' 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    ';


//<----USER NAVBAR---->
echo "
         <nav class='navbar navbar-expand-lg navigation-bar'>
      <div class='container-fluid'>
        <a class='navbar-brand bg-black text-light p-1' href='#Home'>LOGO</a>
        <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNavAltMarkup'
          aria-controls='navbarNavAltMarkup' aria-expanded='false' aria-label='Toggle navigation'>
          <span class='navbar-toggler-icon'></span>
        </button>
        <div class='collapse navbar-collapse nav-element d-flex align-item-center justify-content-lg-between'
          id='navbarNavAltMarkup'>
          <div class='navbar-nav'>
            <a class='nav-link' href='#'>Home</a>
            <a class='nav-link' href='#'>My Orders</a>
          </div>
          <div class='user-info navbar-nav'>
            <div class='nav-link bg-black text-light'>IMAGE</div>
            <a class='nav-link' href='#'>username</a>
          </div>
        </div>
      </div>
    </nav>
        ";