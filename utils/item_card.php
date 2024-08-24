<?php
echo ' 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    ';





function cardItem($product_name = 'Guava', $product_price = 15, $product_quantity = 1, $product_image_path = './images/cup.png')
{
    echo "<div class='container mt-3 mb-5'>
    <div class='row justify-content-center'>
        <div class='col-12 col-md-8 col-lg-6 col-xl-4 '>
            <div class='card m-2 flex-shrink-0 h-100 d-flex justify-content-center align-items-center rounded-4 py-0 mt-1'  style='width: 18rem;'>
                <div class='card-body position-relative text-center'>
                    <div class='row justify-content-center'>
                        <img src='{$product_image_path}' class='img-fluid rounded-4' alt='cup_pic' style='max-width: 90%; background-color:#F8F4E1;'>
                        <div class='position-absolute bg-white rounded-circle d-flex align-items-center justify-content-center'
                             style='height: 6rem; width: 6rem; top: -20px; left: -20px;'>
                            <h1 style='color:#747d88; font-size: 1.5rem;'>" . number_format((float) $product_price, 2, '.', '') . " L.E</h1>
                        </div>
                    </div>
                    <div class='d-flex justify-content-between align-items-center mt-3'>
                        <h5 class='card-title p-2 fs-5 px-5 rounded-2 text-start' style='color:#5C3C1B; background-color:#AF8F6F;'>{$product_name}</h5>
                        <p class='card-title p-2 py-1 fs-5  px-3 rounded-2 text-start' style='color:#5C3C1B; background-color:#AF8F6F;'>{$product_quantity}</p>
                    </div>75px
                </div>
            </div>
        </div>
    </div>
</div>"
    ;
}