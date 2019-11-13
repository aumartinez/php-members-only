-- SQL statements

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET TIME_ZONE = "+00:00";

-- Create table

CREATE TABLE IF NOT EXISTS customer (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(255) NOT NULL,
  create_date DATETIME NOT NULL,
  password VARCHAR(255) NOT NULL,
  salt VARCHAR(255) NOT NULL,
  first_name VARCHAR(255),
  last_name VARCHAR(255),
  street VARCHAR(255),
  city VARCHAR(255),
  state CHAR(2),
  zip CHAR(10),
  phone VARCHAR(25),
  phone_type VARCHAR(255)  
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci AUTO_INCREMENT = 1;
