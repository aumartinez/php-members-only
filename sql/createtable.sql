-- Create tables

CREATE TABLE IF NOT EXISTS customer (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(255) NOT NULL,
  create_date DATETIME NOT NULL,
  password VARCHAR(255) NOT NULL,  
  salt VARCHAR(255) NOT NULL,  
  first_name VARCHAR(255) NOT NULL,
  last_name VARCHAR(255) NOT NULL,
  street VARCHAR(255),
  city VARCHAR(255),
  state CHAR(2),
  zip CHAR(10),
  phone VARCHAR(25),
  phone_type VARCHAR(255)  
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci AUTO_INCREMENT = 1;

CREATE TABLE IF NOT EXISTS resetPassword (
  id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
  email_id INT,
  pass_key VARCHAR(255),
  create_date DATETIME,
  status VARCHAR(255)
) ENGINE = MyISAM DEFAULT CHARSET = utf8 COLLATE = utf8_unicode_ci AUTO_INCREMENT = 1;
