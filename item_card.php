<?php
echo ' 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    ';



function getOrderItems($db, $order_id)
{




    try {
        $selection_query = "SELECT * FROM admin_order WHERE `order_id`=:ID_ORDER";
        $select = $db->prepare($selection_query);
        $select->bindParam(":ID_ORDER", $order_id, PDO::PARAM_INT);
        $select->execute();

    } catch (PDOException $e) {
        echo "<tr><td colspan='4' class='text-center text-danger'>Error: " . $e->getMessage() . "</td></tr>";
    }
    $allRows = $select->fetchAll();
    $total = 0;
    foreach ($allRows as $row) {
        $total = $row['total'];
        cardItem($row['product_name'], $row['total'] / $row['quantity'], $row['quantity']);

    }
    total($total);
}
function total($total)
{

    echo "  </div>
                <div class=  'container text-end  '>
                <span style=  'color:#7a624a; font-size: 3rem;  '>Total:</span>
                 <span style=  'color:#747d88; font-size: 2.5rem;  '>EGP {$total}</span>
                 </div>
                 <hr><br>";

}
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
                             style='height: 75px; width: 75px; top: -20px; left: -20px;'>
                            <h1 style='color:#747d88; font-size: 1.5rem;'>{$product_price}</h1>
                        </div>
                    </div>
                    <div class='d-flex justify-content-between align-items-center mt-3'>
                        <h5 class='card-title p-2 fs-5 px-5 rounded-2 text-start' style='color:#5C3C1B; background-color:#AF8F6F;'>{$product_name}</h5>
                        <p class='card-title p-2 py-1 fs-5  px-3 rounded-2 text-start' style='color:#5C3C1B; background-color:#AF8F6F;'>{$product_quantity}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>"
    ;
}
function orderDetails($row)
{


    echo "
        
      <tr>
          <td>   {$row['id']}   </td>  
          <td>   {$row['date']}   </td>  
          <td>  {$row['name']}  </td>  
          <td>   {$row['room']}   </td>  
          <td>   {$row['ext']} </td>  
          <td> <div class='mb-3'>
                                     <select class='form-control status' selected-value='{$row['status']}'  name='status' id='status'>
                                        
                                        <option value='completed'> Completed</option>
                                        <option value='pending'>Pending</option>
                                        <option value='canceled'>Canceled</option>
                                    </select>
                                    
                                </div> </td>  
       <td>
                            <button 
                             class='btn btn-warning'  onclick='redirect({$row['order_id']})' type='submit'>Update</button>
                             </td>  
     </tr>";


}

function test()
{

    echo "fff";
}

function OpenTable($row)
{


    echo "<div class=  'container table-responsive w-100  '>
                <table class=  'table table-striped table-bordered table-hover  '>
                    <thead>
                        <tr class=  'text-center  '>
                            <th scope=  'col  '>User ID</th>

                            <th scope=  'col  '>Order Date</th>
                            <th scope=  'col  '>Name</th>
                            <th scope=  'col  '>Room</th>
                            <th scope=  'col  '>Ext.</th>
                            <th scope=  'col  '>Status</th>

                            <th scope=  'col  '>Action</th>
                        </tr>
                    </thead>
                    <tbody class=  'text-center  '>

 
                     
";







}

function closeTable()
{

    echo " </tbody>
                </table>
            </div>  ";
}
function updateItem($id)
{
    // Logic to update the item in the database or perform some other action
    return "Item with ID $id has been updated.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    echo updateItem($id);  // Output the result to be captured by JavaScript
}