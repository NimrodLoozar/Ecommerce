-- Initialization SQL for the local MySQL container
-- Ensures the database exists with utf8mb4 and creates/grants a user

CREATE DATABASE IF NOT EXISTS `ecommerce` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- Create a user matching docker-compose and grant privileges (idempotent)
CREATE USER IF NOT EXISTS 'ecommerce'@'%' IDENTIFIED BY 'ecommerce_pass';
GRANT ALL PRIVILEGES ON `ecommerce`.* TO 'ecommerce'@'%';
FLUSH PRIVILEGES;
