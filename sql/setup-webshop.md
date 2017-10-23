-- HOW TO USE THE DATABASE

CALL addToCart(1, 1, 5); -- Add 5 products with product_id = 1 (Asus GeForce GTX 1080ti) to Cart where customer_id = 1 (Admin)
CALL addToCart(1, 2, 3); -- Add 3 products with product_id = 2 (Gigabyte AORUS GTX 1080 Ti WaterForce) to Cart where customer_id = 1 (Admin)
CALL addToCart(2, 2, 3); -- Add 3 products with product_id = 2 (Gigabyte AORUS GTX 1080 Ti WaterForce) to Cart where customer_id = 2 (doe)

CALL removeFromCart(1, 1, 3); -- Customer with ID = 1 changed his mind, he only wants 2x Asus GeForce GTX 1080ti

SELECT * FROM ViewCart; -- Retrun all products which are added to the Cart

CALL viewCustomerCart(1); -- Return Cart for customer_id = 1 (2x Asus GeForce GTX 1080ti & 3x Gigabyte AORUS GTX 1080 Ti WaterForce)

CALL createOrder(1); -- Create order for Customer with ID = 1

CALL viewOrder(1); -- Return order for Customer with ID = 1. Order contains 7x Asus GeForce GTX 1080ti and 3x Gigabyte AORUS GTX 1080 Ti WaterForce

SELECT * FROM ViewInventory; -- Inventory has been changed. Only 43x Asus GeForce GTX 1080ti and 42x Gigabyte AORUS GTX 1080 Ti WaterForce left. 

CALL deleteOrder(1); -- Delete the order for Customer with ID = 1

SELECT * FROM ViewInventory; -- Returns the whole inventory and the amount of Asus GeForce GTX 1080ti is back to 50x and Gigabyte AORUS GTX 1080 Ti WaterForce is 45x

CALL viewOrder(1); -- Returns nothing as the order has been deleted
