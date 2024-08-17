<?php
require './utils/item_card.php';
echo ' 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    ';

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
                             class='btn' style='color:white; background-color:#AF8F6F;'  onclick='redirect({$row['order_id']})' type='submit'>Update</button>
                             </td>  
     </tr>";


}

function closeTable()
{

    echo " </tbody>
                </table>
            </div>  ";
}
function getOrderItems($database, $order_id)
{
    try {
        $selection_query = "SELECT * FROM admin_order WHERE `order_id`=:ID_ORDER";
        $select = $database->prepare($selection_query);
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
function updateItem($id)
{
    // Logic to update the item in the database or perform some other action
    return "Item with ID $id has been updated.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    echo updateItem($id);  // Output the result to be captured by JavaScript
}