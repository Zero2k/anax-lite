-- ------------------------------------------------------------------------
--
-- Kmom05 - Webshop database
--
-- DROP DATABASE kmom05;
-- CREATE DATABASE kmom05;
USE kmom05;
SET NAMES utf8;



-- ------------------------------------------------------------------------
--
-- Setup tables
--

DROP TABLE IF EXISTS kmom05_orderRow;
DROP TABLE IF EXISTS kmom05_inventory;
DROP TABLE IF EXISTS kmom05_order;
DROP TABLE IF EXISTS kmom05_cart;
DROP TABLE IF EXISTS kmom05_product_to_category;
DROP TABLE IF EXISTS kmom05_product;
DROP TABLE IF EXISTS kmom05_category;
DROP TABLE IF EXISTS kmom05_customer;


-- ------------------------------------------------------------------------
--
-- Products
--
DROP TABLE IF EXISTS kmom05_product;

CREATE TABLE kmom05_product (
	`id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `image` VARCHAR(200),
    `title` VARCHAR(100),
    `description` VARCHAR(1000),
    `price` DOUBLE(6, 2)
) ENGINE = INNODB CHARACTER SET utf8;

INSERT INTO kmom05_product(`image`, `title`, `description`, `price`) VALUES
("260594/400/320/asus-geforce-gtx-1080-ti-strix-gaming-oc-11gb.png", "Asus GeForce GTX 1080ti", "TBD", 6595),
("284904/400/320/gigabyte-aorus-gtx-1080-ti-waterforce-xtreme-11gb.png", "Gigabyte AORUS GTX 1080 Ti WaterForce", "TBD", 7799),
("217627/400/320/in-win-904-plus-atx-case-silver-svart-silver.jpg", "In Win 904 Silver", "TBD", 1899),
("283195/400/320/cooltek-mod1-mini-svart-rod.jpg", "Cooltek MOD1 mini", "TBD", 2295),
("297201/400/320/corsair-vengeance-rgb-32gb-3200mhz-ddr4-ej-ecc.png", "Corsair Vengeance RGB 32GB", "TBD", 3339),
("279914/400/320/gskill-trident-z-rgb-series-16gb-4133mhz-ddr4-ej-ecc.jpg", "G.Skill Trident Z RGB 16GB", "TBD", 2399),
("242312/400/320/intel-core-i7-7700k-42-ghz-processor-s-1151.jpg", "Intel Core i7 7700K / 4.2 GHz", "TBD", 2859),
("291068/400/320/amd-ryzen-threadripper-1950x-s-tr4.png", "AMD Ryzen Threadripper 1950X ", "TBD", 9039),
("241329/400/320/asus-rog-maximus-ix-formula-s-1151-atx.png", "ASUS ROG MAXIMUS IX FORMULA", "TBD", 2999),
("285934/400/320/asus-rog-zenith-extreme-x399-socket-tr4-e-atx.png", "ASUS ROG Zenith Extreme X399", "TBD", 5039);

SELECT * FROM kmom05_product;

-- ------------------------------------------------------------------------
--
-- Category
--
DROP TABLE IF EXISTS kmom05_category;

CREATE TABLE kmom05_category (
	`id` INT AUTO_INCREMENT,
	`category` CHAR(20) UNIQUE,

	PRIMARY KEY (`id`)
) ENGINE = INNODB CHARACTER SET utf8;

INSERT INTO kmom05_category(`category`) VALUES
("grafikkort"),
("datorchassi"),
("minne"),
("processor"),
("moderkort");

-- ------------------------------------------------------------------------
--
-- Product to Category
--
DROP TABLE IF EXISTS kmom05_product_to_category;

CREATE TABLE kmom05_product_to_category (
	`id` INT AUTO_INCREMENT,
	`product_id` INT,
	`category_id` INT,

	PRIMARY KEY (`id`),
    FOREIGN KEY (`product_id`) REFERENCES `kmom05_product` (`id`), 
    FOREIGN KEY (`category_id`) REFERENCES `kmom05_category` (`id`) 
);

INSERT INTO kmom05_product_to_category(`product_id`, `category_id`) VALUES
(1, 1), (2, 1), 
(3, 2), (4, 2), 
(5, 3), (6, 3),
(7, 4), (8, 4),
(9, 5), (10, 5);


-- ------------------------------------------------------------------------
--
-- Inventory
--
DROP TABLE IF EXISTS kmom05_inventory;

CREATE TABLE kmom05_inventory (
	-- `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `product_id` INT,
    `amount` INT
) ENGINE = INNODB CHARACTER SET utf8;

INSERT INTO kmom05_inventory(`product_id`, `amount`) VALUES
(1, 50),
(2, 45),
(3, 35),
(4, 40),
(5, 50),
(6, 25),
(7, 35),
(8, 30),
(9, 40),
(10, 35);

-- ------------------------------------------------------------------------
--
-- Customers
--
DROP TABLE IF EXISTS kmom05_customer;

CREATE TABLE kmom05_customer (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(100)
) ENGINE = INNODB CHARACTER SET utf8;

INSERT INTO kmom05_customer(`username`) VALUES
("Admin"),
("doe");

-- ------------------------------------------------------------------------
--
-- Cart
--
DROP TABLE IF EXISTS kmom05_cart;

CREATE TABLE kmom05_cart (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `customer_id` INT,
    `product_id` INT,
    `amount` INT,

    FOREIGN KEY (`customer_id`) REFERENCES `kmom05_customer` (`id`),
    FOREIGN KEY (`product_id`) REFERENCES `kmom05_product` (`id`)

) ENGINE = INNODB CHARACTER SET utf8;

-- ------------------------------------------------------------------------
--
-- Order
--
DROP TABLE IF EXISTS kmom05_order;

CREATE TABLE kmom05_order (
    `id` INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    `customer_id` INT,
    `total` INT,
    `createdAt` DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (`customer_id`) REFERENCES `kmom05_customer` (`id`)

);

-- ------------------------------------------------------------------------
--
-- OrderRow
--
DROP TABLE IF EXISTS kmom05_orderRow;

CREATE TABLE kmom05_orderRow (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `order_id` INT,
    `product_id` INT,
    `amount` INT,

    FOREIGN KEY (`order_id`) REFERENCES `kmom05_order` (`id`),
    FOREIGN KEY (`product_id`) REFERENCES `kmom05_product` (`id`)

) ENGINE = INNODB CHARACTER SET utf8;

-- ------------------------------------------------------------------------
--
-- Inventory Log
--
DROP TABLE IF EXISTS kmom05_inventoryLog;

CREATE TABLE kmom05_inventoryLog (
	`id` INTEGER PRIMARY KEY AUTO_INCREMENT,
    `when` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `what` VARCHAR(40),
    `productId` INT,
    `amount` DECIMAL(4, 2)
);

SELECT * FROM kmom05_inventoryLog;

-- VIEWS

-- ------------------------------------------------------------------------
--
-- Inventory VIEW
--
DROP VIEW IF EXISTS ViewInventory;
CREATE VIEW ViewInventory
AS
SELECT 
	Product.id AS id,
    Product.title AS title,
    Product.image AS image,
    Product.description AS description,
    GROUP_CONCAT(Category.category) AS category,
    Product.price AS price,
    Inventory.amount AS amount
FROM
	`kmom05_product` AS Product
	LEFT JOIN `kmom05_product_to_category` AS P2C
		ON Product.id = P2C.product_id
	LEFT JOIN `kmom05_category` AS Category
		ON Category.id = P2C.category_id
	INNER JOIN `kmom05_inventory` AS Inventory
		ON Inventory.product_id = Product.id
	GROUP BY Product.id, Inventory.amount
    ORDER BY Product.id
;

SELECT * FROM ViewInventory;

-- ------------------------------------------------------------------------
--
-- Product VIEW
--
DROP VIEW IF EXISTS ViewProduct;
CREATE VIEW ViewProduct
AS
SELECT 
	Product.id AS id,
    Product.title AS title,
    Product.image AS image,
    Product.description AS description,
    GROUP_CONCAT(Category.category) AS category,
    Product.price AS price
FROM
	`kmom05_product` AS Product
	LEFT JOIN `kmom05_product_to_category` AS P2C
		ON Product.id = P2C.product_id
	LEFT JOIN `kmom05_category` AS Category
		ON Category.id = P2C.category_id
	GROUP BY Product.id
;

SELECT * FROM ViewProduct;

-- ------------------------------------------------------------------------
--
-- Cart VIEW
--
DROP VIEW IF EXISTS ViewCart;
CREATE VIEW ViewCart
AS
SELECT
	Customer.id AS CustomerId,
    Customer.username AS CustomerName,
    Cart.product_id as ProductId,
    SUM(Cart.amount) AS Amount,
    Product.price AS Price
FROM
	`kmom05_customer` AS Customer
    INNER JOIN `kmom05_cart` AS Cart
		ON Customer.id = Cart.customer_id
	INNER JOIN `kmom05_product` AS Product
		ON Cart.product_id = Product.id
	GROUP BY Cart.id;

-- PROCEDURE

-- ------------------------------------------------------------------------
--
-- Delete Product
--

DROP PROCEDURE IF EXISTS deleteProduct;

DELIMITER //

CREATE PROCEDURE deleteProduct(
    productId INT
)
BEGIN
	DECLARE productCategoryId INT;
    
    START TRANSACTION;

	SET productCategoryId = (SELECT id FROM kmom05_product_to_category WHERE product_id = productId);

	IF (productCategoryId IS NULL) THEN
		DELETE FROM kmom05_product
		WHERE
			id = productId;

	ELSE
    
		DELETE FROM kmom05_product_to_category 
		WHERE
			product_id = productId;

		DELETE FROM kmom05_product 
		WHERE
			id = productId;
			
		COMMIT;

    END IF;
END
//

DELIMITER ;

-- ------------------------------------------------------------------------
--
-- Delete Category
--

DROP PROCEDURE IF EXISTS deleteCategory;

DELIMITER //

CREATE PROCEDURE deleteCategory(
    categoryId INT
)
BEGIN

	UPDATE kmom05_product_to_category
	SET
		category_id = NULL
	WHERE
		category_id = categoryId;

	DELETE FROM kmom05_category 
	WHERE
		id = categoryId;
		
	COMMIT;

END
//

DELIMITER ;

-- ------------------------------------------------------------------------
--
-- View Products in Order
--
DROP PROCEDURE IF EXISTS viewOrder;

DELIMITER //

CREATE PROCEDURE viewOrder(
    orderId INT
)
BEGIN

SELECT
	Orders.id AS orderNr,
	Product.id AS productId,
    Product.title AS title,
    Product.price AS price,
    OrderRow.amount AS amount,
    Customer.username AS customerName
FROM

	`kmom05_order` AS Orders
		LEFT JOIN `kmom05_orderRow` AS OrderRow
			ON Orders.id = OrderRow.order_id
		LEFT JOIN `kmom05_product` AS Product
			ON OrderRow.product_id = Product.id
		INNER JOIN `kmom05_customer` AS Customer
			ON Orders.customer_id = Customer.id
		WHERE Orders.id = orderId;

END;
//

DELIMITER ;

-- ------------------------------------------------------------------------
--
-- View Single Customers Cart
--
DROP PROCEDURE IF EXISTS viewCustomerCart;

DELIMITER //

CREATE PROCEDURE viewCustomerCart(
	customerId INT
)
BEGIN 

SELECT
	Customer.id AS CustomerId,
    Customer.username AS CustomerName,
    Cart.product_id as ProductId,
    SUM(Cart.amount) AS Amount,
    Product.price AS Price
FROM
	`kmom05_customer` AS Customer
    INNER JOIN `kmom05_cart` AS Cart
		ON Customer.id = Cart.customer_id
	INNER JOIN `kmom05_product` AS Product
		ON Cart.product_id = Product.id
    WHERE Customer.id = customerId GROUP BY Cart.id;
    
END;
//

DELIMITER ;

-- ------------------------------------------------------------------------
--
-- Add product to cart
--
DROP PROCEDURE IF EXISTS addToCart;

DELIMITER //

CREATE PROCEDURE addToCart(
	customerId INT,
    productId INT,
    amount INT
)
BEGIN
	DECLARE availableInventory INT;
    
    START TRANSACTION;
    
    SET availableInventory = (SELECT amount FROM kmom05_inventory WHERE product_id = productId);
    
    IF availableInventory - amount < 0 THEN
		ROLLBACK;
		SELECT "Not enought inventory";
	ELSE
		INSERT INTO kmom05_cart(`customer_id`, `product_id`, `amount`) VALUES (customerId, productId, amount);
        
        COMMIT;
    END IF;
END;
//

DELIMITER ;

-- ------------------------------------------------------------------------
--
-- Empty cart
--
DROP PROCEDURE IF EXISTS removeFromCart;

DELIMITER //

CREATE PROCEDURE removeFromCart(
	customerId INT,
    productId INT,
    amountToRemove INT
)
BEGIN

	DECLARE productsInCart INT;
    
    START TRANSACTION;
    
    SET productsInCart = (SELECT amount FROM kmom05_cart WHERE product_id = productId);
    
    IF productsInCart - amountToRemove < 0 THEN
		ROLLBACK;
		SELECT "You do not have that many products in your cart.";
		ELSE
		IF productsInCart - amountToRemove = 0 THEN
            DELETE FROM kmom05_cart WHERE product_id = productId;
		ELSE
			UPDATE kmom05_cart
            SET amount = amount - amountToRemove
            WHERE product_id = productId;
        
        COMMIT;
        END IF;
    END IF;

END;
//

DELIMITER ;

-- ------------------------------------------------------------------------
--
-- Create order
--
DROP PROCEDURE IF EXISTS createOrder;

DELIMITER //

CREATE PROCEDURE createOrder(
	customerId INT
)
BEGIN

	DECLARE amountInInventory INT;
    
    START TRANSACTION;
    
    UPDATE kmom05_inventory
    INNER JOIN ViewCart
		ON kmom05_inventory.product_id = ViewCart.productId
	SET kmom05_inventory.amount = kmom05_inventory.amount - ViewCart.Amount
    WHERE ViewCart.CustomerId = customerId;
    
    INSERT INTO kmom05_order(`customer_id`)
		SELECT 
			Customer.id AS customerId
		FROM kmom05_customer as Customer
			INNER JOIN kmom05_cart as Cart
				ON Customer.id = Cart.customer_id;
                
	INSERT INTO kmom05_orderRow(`order_id`, `product_id`, `amount`)
		SELECT
			Orders.id AS orderNr,
            Cart.product_id AS productId,
            Cart.amount AS Amount
		FROM kmom05_order AS Orders
			INNER JOIN kmom05_cart AS Cart
				ON Orders.customer_id = Cart.customer_id;
			
	SET amountInInventory = (SELECT MIN(amount) FROM kmom05_inventory);
    
    IF amountInInventory < 0 THEN
		ROLLBACK;
        SELECT "Not enought inventory";
	ELSE
		DELETE FROM kmom05_cart WHERE customer_id = customerId;
        COMMIT;
	
    END IF;

END;
//

DELIMITER ;

-- ------------------------------------------------------------------------
--
-- Delete order
--
DROP PROCEDURE IF EXISTS deleteOrder;

DELIMITER //

CREATE PROCEDURE deleteOrder(
	customerId INT
)
BEGIN

	UPDATE kmom05_inventory
    INNER JOIN kmom05_orderRow
		ON kmom05_inventory.product_id = kmom05_orderRow.product_id
	INNER JOIN kmom05_order
		ON kmom05_order.id = kmom05_orderRow.order_id
	SET kmom05_inventory.amount = kmom05_inventory.amount + kmom05_orderRow.amount
    WHERE kmom05_order.id = customerId;
    
        
    DELETE FROM kmom05_orderRow WHERE order_id = customerId;
    DELETE FROM kmom05_order WHERE id = customerId;

END;
//

DELIMITER ;

-- TRIGGER

-- ------------------------------------------------------------------------
--
-- Trigger inventory change
--
DROP TRIGGER IF EXISTS inventoryUpdate;

DELIMITER //

CREATE TRIGGER inventoryUpdate AFTER UPDATE ON kmom05_inventory
FOR EACH ROW
BEGIN
    IF NEW.amount < 5 THEN
        INSERT INTO kmom05_inventoryLog(`what`, `productId`, `amount`) VALUES ("IMPORTANT - LOW STOCK", NEW.product_id, NEW.amount);
    ELSEIF  NEW.amount > OLD.amount THEN
        INSERT INTO kmom05_inventoryLog(`what`, `productId`, `amount`) VALUES ("Added Invetory", NEW.product_id, NEW.amount - OLD.amount);
    ELSE
        INSERT INTO kmom05_inventoryLog(`what`, `productId`, `amount`) VALUES ("Removed Invetory", NEW.product_id, NEW.amount - OLD.amount);
    END IF;
END;
//

DELIMITER ;
