--view to frap data 
 
 Create view admin_order as 
 Select 
 Orders.date,Orders.total,Orders.status,
 User.id,User.name,User.ext,User.room
 ,Order_product.product_id,Order_product.order_id,Order_product.quantity,
 Product.name as product_name
  from Orders
   Join User On Orders.user_id=User.id
    join Order_product on Order_product.order_id=Orders.id
  join Product on Order_product.product_id=Product.id ;