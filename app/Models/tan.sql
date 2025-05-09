CREATE TABLE users ( 
    user_id INT PRIMARY KEY AUTO_INCREMENT UNSIGNED,
    user_first_name VARCHAR(50) NOT NULL,
    user_last_name VARCHAR(50) NOT NULL,
    user_email VARCHAR(100) NOT NULL UNIQUE,
    user_password VARCHAR(50) NOT NULL,
    user_phone VARCHAR(30) NOT NULL UNIQUE,
    user_address VARCHAR(255) NOT NULL UNIQUE,
    user_type TINYINT UNSIGNED NOT NULL DEFAULT 0
)

CREATE TABLE products ( 
    prod_id INT PRIMARY KEY AUTO_INCREMENT UNSIGNED,
    user_id INT UNSIGNED NOT NULL COMMENT "Seller user id",
    prod_name VARCHAR(50) NOT NULL,
    prod_image VARCHAR(100) NOT NULL,
    prod_description VARCHAR(500) NOT NULL ,
    prod_rent_price DECIMAL(10,2),
    prod_rent_nondiscounted DECIMAL(10,2),
    prod_rent_interval INT UNSIGNED COMMENT "interval of which the rent is incurred",
    prod_buy_price DECIMAL(10,2),
    prod_buy_nondiscounted DECIMAL(10,2),
    FOREIGN KEY user_id REFERENCES users (user_id)
)

CREATE TABLE product_feedbacks (
    prodfeed_id INT PRIMARY KEY AUTO_INCREMENT UNSIGNED,
    prod_id INT UNSIGNED NOT NULL COMMENT "product id addressed by feedback",
    user_id INT UNSIGNED NOT NULL COMMENT "commenter user id",
    prodfeed_rating TINYINT NOT NULL COMMENT "1 to 5",
    prodfeed_comment VARCHAR(500),
    prodfeed_added_at DATETIME NOT NULL,
    FOREIGN KEY prod_id REFERENCES products (prod_id),
    FOREIGN KEY user_id REFERENCES users (user_id)
)


CREATE TABLE orders (
    order_id INT PRIMARY KEY AUTO_INCREMENT UNSIGNED,
    order_seller_id INT UNSIGNED NOT NULL COMMENT "user id that ordered",
    order_cust_id INT UNSIGNED NOT NULL COMMENT "user id that receives the order",
    order_status TINYINT NOT NULL COMMENT "0 - cart, 1 - sent, 2 - processing,  3 - shipped, 4 - delivered, 5 - returning, 6 - returned, 7 - cancelled",
    order_created_at DATETIME NOT NULL,
    FOREIGN KEY order_seller_id REFERENCES users (user_id),
    FOREIGN KEY order_cust_id REFERENCES users (user_id),
)

CREATE TABLE order_items (
    orditm_id INT PRIMARY KEY AUTO_INCREMENT UNSIGNED, 
    order_id INT UNSIGNED,
    prod_id INT UNSIGNED,
    orditm_type TINYINT NOT NULL COMMENT "0 - buy, 1 - rent"
    orditm_start_date DATETIME,
    orditm_end_date DATETIME,
    orditm_rent_price DECIMAL(10,2),
    orditm_rent_nondiscounted DECIMAL(10,2),
    orditm_rent_interval INT UNSIGNED COMMENT "interval of which the rent is incurred",
    orditm_buy_price DECIMAL(10,2),
    orditm_buy_nondiscounted DECIMAL(10,2),
    orditm_rent_fee DECIMAL(10,2),
    orditm_address VARCHAR(500),
    FOREIGN KEY prod_id REFERENCES products (prod_id),
    FOREIGN KEY order_id REFERENCES orders (order_id)
)

CREATE TABLE order_logs(
    ordlog_id INT PRIMARY KEY AUTO_INCREMENT UNSIGNED,
    order_id INT UNSIGNED,
    ordlog_status TINYINT NOT NULL COMMENT "0 - cart, 1 - sent, 2 - processing,  3 - shipped, 4 - delivered, 5 - returning, 6 - returned, 7 - cancelled, 8 -",
    ordlog_created_at DATETIME,
    ordlog_seller_remarks VARCHAR(500),
    ordlog_user_remarks VARCHAR(500),
    FOREIGN KEY order_id REFERENCES orders (order_id)
)


CREATE TABLE favorites(
    fav_id INT PRIMARY KEY AUTO_INCREMENT UNSIGNED,
    user_id BIGINT UNSIGNED,
    prod_id BIGINT UNSIGNED,
    fav_at DATETIME NOT NULL,
    FOREIGN KEY prod_id REFERENCES products (prod_id),
    FOREIGN KEY order_id REFERENCES orders (order_id)
)