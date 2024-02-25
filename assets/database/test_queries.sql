-- SEARCH &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&			  
-- main search
SELECT wd_product.name AS product_name, product_id, YEAR, wd_product.man_id AS man_id, manufacturer, wd_type.name AS type_name, price, image
FROM wd_product, wd_manufacturer, wd_type
WHERE wd_product.typ_id = wd_type.type_id
AND wd_product.man_id = wd_manufacturer.manufacturer_id
AND (wd_product.name LIKE '%%' OR wd_manufacturer.manufacturer LIKE '%%');


-- TYPE &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
-- select all products that match the type selected (main type query)
SELECT wd_product.name AS product_name, product_id, YEAR, wd_product.man_id AS man_id, manufacturer, wd_type.name AS type_name, price, image 
FROM wd_type
LEFT JOIN wd_product ON wd_type.type_id = wd_product.typ_id
LEFT JOIN wd_manufacturer ON wd_product.man_id = wd_manufacturer.manufacturer_id
WHERE wd_type.name = 'Snowboards';

-- select only the first image for each product returned from the above query
SELECT NAME, location FROM wd_product, wd_product_image
WHERE wd_product.product_id = wd_product_image.prod_id AND wd_product.man_id = wd_product_image.man_id AND wd_product.product_id = 'Fuz_01' AND wd_product.man_id = 3
LIMIT 1;


			  
-- MANUFACTURER &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
-- Show all manufacturers

-- Show all products from specific manufacturer
SELECT wd_product.name AS product_name, product_id, YEAR, manufacturer_id, manufacturer, wd_type.name AS type_name, size, price, image
FROM wd_product, wd_manufacturer, wd_type
WHERE wd_product.typ_id = wd_type.type_id AND wd_product.man_id = wd_manufacturer.manufacturer_id AND (wd_product.name LIKE 'burton' OR wd_manufacturer.manufacturer LIKE 'burton');

-- PRODUCT &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
-- Show product details for one product
SELECT `product_id`, `wd_product`.`name` AS `product_name`, `manufacturer`, `image` AS `manufacturer_image`, `size`, `price`, `year`, `wd_product`.`description` AS `product_description`, `wd_type`.`name` AS `type_name` FROM wd_product
LEFT JOIN wd_manufacturer ON wd_product.man_id = wd_manufacturer.manufacturer_id
LEFT JOIN wd_type ON wd_product.typ_id = wd_type.type_id
WHERE wd_product.product_id = 'fuz_01' AND wd_product.man_id = 3;

-- Show all comments for one product
SELECT `name`, `comment`
FROM `wd_post`, `wd_product`
WHERE `wd_post`.`prod_id` = `wd_product`.`product_id`
AND `wd_post`.`man_id` = `wd_product`.`man_id`
AND `wd_product`.`name` = "Ghost" ;

-- Show all image locations for one product
SELECT NAME, location FROM wd_product, wd_product_image
WHERE wd_product.product_id = wd_product_image.prod_id AND wd_product.man_id = wd_product_image.man_id AND wd_product.product_id = 'Fuz_01' AND wd_product.man_id = 3;

-- TEST STUFF &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&

-- query using concat to concatenate two fields as one
SELECT concat(`man_id`, '-',`product_id`) AS total FROM `wd_product`; 

-- create a test table for a concat experiment
CREATE TABLE `wd_test` (
`unique` VARCHAR(10),
PRIMARY KEY(`unique`)
);

-- create a concat value created from two other database values and insert that into the test table
INSERT INTO `wd_test` (`unique`)
SELECT concat(`man_id`, '-', `product_id`) AS test FROM wd_product;