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
    `delivery` DATETIME DEFAULT NULL,

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
    `what` VARCHAR(20),
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
    IF NEW.amount > OLD.amount THEN
        INSERT INTO kmom05_inventoryLog (`what`, `productId`, `amount`) VALUES ("Added Invetory", NEW.product_id, NEW.amount - OLD.amount);
    ELSEIF NEW.amount < OLD.amount THEN
        INSERT INTO kmom05_inventoryLog (`what`, `productId`, `amount`) VALUES ("Removed Invetory", NEW.product_id, NEW.amount - OLD.amount);
    END IF;
END;//
DELIMITER ;
