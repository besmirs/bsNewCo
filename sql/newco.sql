-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 23, 2020 at 11:23 AM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `newco`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `Customers` (`act` VARCHAR(20), `fname` VARCHAR(20), `lname` VARCHAR(20), `address` VARCHAR(45), `phone` VARCHAR(20), `customerId` INT)  BEGIN
	IF act = 'insert' THEN
		INSERT INTO tbl_clients (cli_fname, cli_lname, cli_address, cli_phone) 
        						VALUES (fname, lname, address, phone);
    ELSEIF act = 'update' THEN
    	UPDATE 
        	tbl_clients 
        SET 
        	cli_fname = fname, 
            cli_lname = lname, 
            cli_address = address,
            cli_phone = phone
        WHERE cli_id = customerId;
        
    ELSEIF act = 'selectall' THEN
    	SELECT * FROM tbl_clients;
        
    ELSEIF act = 'selectCurrent' THEN
    	SELECT * FROM tbl_clients WHERE cli_id = customerId;
    ELSE 
    	DELETE FROM tbl_clients WHERE cli_id = customerId;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteProducts` (IN `productId` INT)  BEGIN
	DELETE FROM tbl_product_services WHERE pro_id = productId;
	DELETE FROM tbl_products WHERE pro_id = productId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteService` (`id` INT)  BEGIN
DELETE FROM tbl_services WHERE ser_id = id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteServicesOnProduct` (IN `productId` INT)  BEGIN
	DELETE FROM tbl_product_services WHERE pro_id = productId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getCities` ()  BEGIN
SELECT * FROM tbl_cities ORDER By cit_id ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getCity` (`city` INT)  BEGIN
SELECT * FROM tbl_cities WHERE cit_id = city;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getCurrentProduct` (IN `id` INT)  BEGIN
SELECT * FROM tbl_products WHERE pro_id = id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getCurrentService` (`serviceId` INT)  BEGIN
	SELECT * FROM tbl_services WHERE ser_id = serviceId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getCurrentShop` (`shopId` INT)  BEGIN
	SELECT 
    	s.sho_id AS shopId, 
        s.sho_name AS shopName, 
        c.cit_id AS cityId, 
        c.cit_name AS cityName 
    FROM tbl_shops AS s 
    	INNER JOIN tbl_cities AS c 
        ON s.cit_id = c.cit_id
    WHERE s.sho_id = shopId;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getLoginData` (`user` VARCHAR(25))  BEGIN
SELECT * FROM tbl_users WHERE use_username = user;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getProducts` ()  BEGIN 
SELECT * FROM tbl_products ORDER BY pro_id DESC; 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getProductServices` (IN `param` VARCHAR(25))  BEGIN
   IF param = -1 THEN      
      SELECT DISTINCT
      	s.ser_id AS id,
      	s.ser_desc AS description
      FROM tbl_product_services AS ps
      	LEFT JOIN tbl_services AS s ON
      		ps.ser_id = s.ser_id 
      ORDER BY s.ser_id DESC;
      
   ELSE
      SELECT 
      	s.ser_id AS id,
      	s.ser_desc AS description
      FROM tbl_product_services AS ps
      	LEFT JOIN tbl_services AS s ON
      		ps.ser_id = s.ser_id 
      WHERE ps.pro_id = param;
   END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getServices` ()  BEGIN
	SELECT * FROM tbl_services ORDER BY ser_id DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getShopAssistantsFromCurrentShop` (`shop` INT)  BEGIN
	SELECT * FROM tbl_shopassistants WHERE sho_id = shop;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getShops` ()  BEGIN
	SELECT * FROM tbl_shops ORDER BY sho_id DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertProducts` (`description` TEXT, `dt` DATE, `state` INT, OUT `rid` INT)  BEGIN
INSERT INTO tbl_products (pro_desc, pro_validity, pro_state) VALUES (description, dt, state);

SELECT LAST_INSERT_ID() INTO rid;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertServices` (`description` TEXT, `price` DECIMAL(6,2), `active` ENUM('0','1'))  BEGIN
INSERT INTO tbl_services (ser_desc, ser_price, ser_active) VALUES (description, price, active);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `insertServicesToProducts` (`pid` INT, `sid` INT)  BEGIN
	INSERT INTO tbl_product_services(pro_id, ser_id) VALUES (pid, sid);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SellProduct` (`service` INT, `product` INT, `assistant` INT, `shop` INT, `customer` INT)  BEGIN
	INSERT INTO tbl_sales (ser_id, pro_id, sas_id, sho_id, cli_id) VALUES
    	(service, product, assistant, shop, customer);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Shop` (IN `act` VARCHAR(20), IN `name` VARCHAR(20), IN `city` INT, IN `shopId` INT)  BEGIN
	IF act = 'insert' THEN
		INSERT INTO tbl_shops (sho_name, cit_id) VALUES (name, city);
    ELSEIF act = 'update' THEN
		UPDATE tbl_shops SET sho_name = name, 
        					 cit_id = city 
        WHERE sho_id = shopId;
    ELSE
		DELETE FROM tbl_shops WHERE sho_id = shopId;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ShopAssistant` (IN `act` VARCHAR(20), IN `fullname` VARCHAR(60), IN `username` VARCHAR(45), IN `pass` VARCHAR(45), IN `shopId` INT, IN `assistantId` INT)  BEGIN
	IF act = 'insert' THEN
		INSERT INTO tbl_shopassistants (sas_fullname, sas_username, sas_password, sho_id) 
        						VALUES (fullname, username, pass, shopId);
    ELSEIF act = 'update' THEN
    	UPDATE 
        	tbl_shopassistants 
        SET 
        	sas_fullname = fullname, 
            sas_username = username, 
            sas_password = pass,
            sho_id = shopId
        WHERE sas_id = assistantId;
        
    ELSEIF act = 'selectall' THEN
    	SELECT * FROM tbl_shopassistants;
        
    ELSEIF act = 'selectCurrent' THEN
    	SELECT * FROM tbl_shopassistants WHERE sas_id = assistantId;
    ELSE 
    	DELETE FROM tbl_shopassistants WHERE sas_id = assistantId;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateProduct` (IN `description` TEXT, IN `validity` DATE, IN `state` INT, IN `id` INT)  BEGIN
UPDATE tbl_products SET pro_desc = description, pro_validity = validity, pro_state = state WHERE pro_id = id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `updateService` (`description` TEXT, `price` DECIMAL(6,2), `active` ENUM('0','1'), `id` INT)  BEGIN
	UPDATE tbl_services SET ser_desc = description, ser_price = price, ser_active = active WHERE ser_id = id;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_cities`
--

CREATE TABLE `tbl_cities` (
  `cit_id` int(11) NOT NULL,
  `cit_name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_cities`
--

INSERT INTO `tbl_cities` (`cit_id`, `cit_name`) VALUES
(1, 'Prishtina'),
(2, 'Mitrovica'),
(3, 'Peja'),
(4, 'Prizreni'),
(5, 'Gjakova'),
(6, 'Gjilan'),
(7, 'Ferizaj');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_clients`
--

CREATE TABLE `tbl_clients` (
  `cli_id` int(11) NOT NULL,
  `cli_fname` varchar(20) NOT NULL,
  `cli_lname` varchar(20) NOT NULL,
  `cli_address` varchar(255) NOT NULL,
  `cli_phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_clients`
--

INSERT INTO `tbl_clients` (`cli_id`, `cli_fname`, `cli_lname`, `cli_address`, `cli_phone`) VALUES
(1, 'Berat', 'Shala', 'Rr. Avni Shabani, Pn', '045-254-748'),
(2, 'Avni', 'Asllani', 'Str. Skenderbeu', '049-744-123');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products`
--

CREATE TABLE `tbl_products` (
  `pro_id` int(11) NOT NULL,
  `pro_desc` text NOT NULL,
  `pro_validity` date NOT NULL,
  `pro_state` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_products`
--

INSERT INTO `tbl_products` (`pro_id`, `pro_desc`, `pro_validity`, `pro_state`) VALUES
(12, 'Product 1', '2020-03-31', 49),
(13, 'Product 2', '2020-03-02', 0),
(14, 'Product 3', '2020-03-31', 50),
(15, 'Product 4', '2020-03-31', 70);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_services`
--

CREATE TABLE `tbl_product_services` (
  `pse_id` int(11) NOT NULL,
  `pro_id` int(11) NOT NULL,
  `ser_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_product_services`
--

INSERT INTO `tbl_product_services` (`pse_id`, `pro_id`, `ser_id`) VALUES
(25, 12, 11),
(26, 12, 6),
(27, 13, 11),
(28, 13, 6),
(29, 13, 5),
(30, 14, 11),
(31, 14, 6),
(32, 14, 5),
(33, 15, 13);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sales`
--

CREATE TABLE `tbl_sales` (
  `sal_id` int(11) NOT NULL,
  `ser_id` int(11) NOT NULL,
  `pro_id` int(11) NOT NULL,
  `sas_id` int(11) NOT NULL,
  `sho_id` int(11) NOT NULL,
  `cli_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_sales`
--

INSERT INTO `tbl_sales` (`sal_id`, `ser_id`, `pro_id`, `sas_id`, `sho_id`, `cli_id`) VALUES
(1, 13, 15, 4, 5, 1),
(2, 6, 12, 4, 5, 2),
(3, 11, 12, 1, 2, 2),
(4, 6, 12, 3, 2, 1),
(8, 6, 13, 1, 2, 1),
(9, 5, 13, 3, 2, 1),
(10, 5, 14, 2, 3, 2),
(11, 6, 13, 1, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_services`
--

CREATE TABLE `tbl_services` (
  `ser_id` int(11) NOT NULL,
  `ser_desc` text NOT NULL,
  `ser_price` decimal(6,2) NOT NULL,
  `ser_active` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_services`
--

INSERT INTO `tbl_services` (`ser_id`, `ser_desc`, `ser_price`, `ser_active`) VALUES
(5, 'Service 1', '14.23', '1'),
(6, 'Service 2', '14.99', '1'),
(11, 'Service 3', '4.99', '1'),
(12, 'Service 4', '9.99', '1'),
(13, 'Service 5', '15.99', '1');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_shopassistants`
--

CREATE TABLE `tbl_shopassistants` (
  `sas_id` int(11) NOT NULL,
  `sas_fullname` varchar(60) NOT NULL,
  `sas_username` varchar(45) NOT NULL,
  `sas_password` varchar(255) NOT NULL,
  `sho_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_shopassistants`
--

INSERT INTO `tbl_shopassistants` (`sas_id`, `sas_fullname`, `sas_username`, `sas_password`, `sho_id`) VALUES
(1, 'Besmir Sadiku', 'besmir1315', '$2y$10$J.ZeWo44VFZimxZqpGBjsOWsWjjjL8Y5wZoXkE', 2),
(2, 'Filan Fisteku', 'filan4768', '$2y$10$Oi7Es5o02JnrSMpiReUDWeAJTTuqFd7dUfDhPF', 3),
(3, 'Blerina Dika', 'blerina2020', '$2y$10$tSZFiPvI0DcX1y9n4crtvOPzhkca67svh4ulF8', 2),
(4, 'Fuad Halimi', 'filan9160', '$2y$10$3TujTixI5Sce16hPxDqYuO1f6bwSBeMzz4whrs', 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_shops`
--

CREATE TABLE `tbl_shops` (
  `sho_id` int(11) NOT NULL,
  `sho_name` varchar(20) NOT NULL,
  `cit_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_shops`
--

INSERT INTO `tbl_shops` (`sho_id`, `sho_name`, `cit_id`) VALUES
(1, 'Sample shop', 1),
(2, 'Top shop', 2),
(3, 'Test shop', 5),
(4, 'Mitro Shop', 2),
(5, 'Rion Shop', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `use_id` int(11) NOT NULL,
  `use_fullName` varchar(100) NOT NULL,
  `use_username` varchar(45) NOT NULL,
  `use_password` varchar(255) NOT NULL,
  `use_active` enum('1','0') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`use_id`, `use_fullName`, `use_username`, `use_password`, `use_active`) VALUES
(1, 'Besmir Sadiku', 'besi', '$2y$10$EeggRx41PRi8lf/fJNQ2XuOGbuaE4rHr/UP3Y1HC01fO2O/rDLT12', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_cities`
--
ALTER TABLE `tbl_cities`
  ADD PRIMARY KEY (`cit_id`);

--
-- Indexes for table `tbl_clients`
--
ALTER TABLE `tbl_clients`
  ADD PRIMARY KEY (`cli_id`);

--
-- Indexes for table `tbl_products`
--
ALTER TABLE `tbl_products`
  ADD PRIMARY KEY (`pro_id`);

--
-- Indexes for table `tbl_product_services`
--
ALTER TABLE `tbl_product_services`
  ADD PRIMARY KEY (`pse_id`),
  ADD KEY `fk_tbl_product_services_tbl_products1` (`pro_id`),
  ADD KEY `fk_tbl_product_services_tbl_services1` (`ser_id`);

--
-- Indexes for table `tbl_sales`
--
ALTER TABLE `tbl_sales`
  ADD PRIMARY KEY (`sal_id`),
  ADD KEY `fk_tbl_sales_tbl_servcies1` (`ser_id`),
  ADD KEY `fk_tbl_sales_tbl_products1` (`pro_id`),
  ADD KEY `fk_tbl_sales_tbl_shopAssistants1` (`sas_id`),
  ADD KEY `fk_tbl_sales_tbl_shops1` (`sho_id`),
  ADD KEY `fk_tbl_sales_tbl_clients1` (`cli_id`);

--
-- Indexes for table `tbl_services`
--
ALTER TABLE `tbl_services`
  ADD PRIMARY KEY (`ser_id`);

--
-- Indexes for table `tbl_shopassistants`
--
ALTER TABLE `tbl_shopassistants`
  ADD PRIMARY KEY (`sas_id`),
  ADD KEY `fk_tbl_shopAssistants_tbl_shops1` (`sho_id`);

--
-- Indexes for table `tbl_shops`
--
ALTER TABLE `tbl_shops`
  ADD PRIMARY KEY (`sho_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`use_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_cities`
--
ALTER TABLE `tbl_cities`
  MODIFY `cit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_clients`
--
ALTER TABLE `tbl_clients`
  MODIFY `cli_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_products`
--
ALTER TABLE `tbl_products`
  MODIFY `pro_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_product_services`
--
ALTER TABLE `tbl_product_services`
  MODIFY `pse_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `tbl_sales`
--
ALTER TABLE `tbl_sales`
  MODIFY `sal_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tbl_services`
--
ALTER TABLE `tbl_services`
  MODIFY `ser_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tbl_shopassistants`
--
ALTER TABLE `tbl_shopassistants`
  MODIFY `sas_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tbl_shops`
--
ALTER TABLE `tbl_shops`
  MODIFY `sho_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `use_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_product_services`
--
ALTER TABLE `tbl_product_services`
  ADD CONSTRAINT `fk_tbl_product_services_tbl_products1` FOREIGN KEY (`pro_id`) REFERENCES `tbl_products` (`pro_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tbl_product_services_tbl_services1` FOREIGN KEY (`ser_id`) REFERENCES `tbl_services` (`ser_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tbl_sales`
--
ALTER TABLE `tbl_sales`
  ADD CONSTRAINT `fk_tbl_sales_tbl_clients1` FOREIGN KEY (`cli_id`) REFERENCES `tbl_clients` (`cli_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tbl_sales_tbl_products1` FOREIGN KEY (`pro_id`) REFERENCES `tbl_products` (`pro_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tbl_sales_tbl_servcies1` FOREIGN KEY (`ser_id`) REFERENCES `tbl_services` (`ser_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tbl_sales_tbl_shopAssistants1` FOREIGN KEY (`sas_id`) REFERENCES `tbl_shopassistants` (`sas_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tbl_sales_tbl_shops1` FOREIGN KEY (`sho_id`) REFERENCES `tbl_shops` (`sho_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tbl_shopassistants`
--
ALTER TABLE `tbl_shopassistants`
  ADD CONSTRAINT `fk_tbl_shopAssistants_tbl_shops1` FOREIGN KEY (`sho_id`) REFERENCES `tbl_shops` (`sho_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
