-- phpMyAdmin SQL Dump
-- version 2.9.0.2
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Mar 20, 2007 at 11:10 AM
-- Server version: 4.1.21
-- PHP Version: 4.4.2
-- 
-- Database: `frontacc_frontacc`
-- Author : Swapna (Acube innovations PVT PTD)

-- --------------------------------------------------------

--
-- Table structure for table `0_loading_types`
--

DROP TABLE IF EXISTS `0_loading_types`; 

CREATE TABLE IF NOT EXISTS `0_loading_types` (
  `loading_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `loading_type_name` varchar(255) NOT NULL,
  PRIMARY KEY (`loading_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `0_loading_types`
--
INSERT INTO `0_loading_types` (`loading_type_id`, `loading_type_name`) VALUES
(1, 'PICK-UP'),
(2, 'DELIVER');

--
-- Table structure for table `0_shipment_times`
--

DROP TABLE IF EXISTS `0_shipment_times`; 

CREATE TABLE IF NOT EXISTS `0_shipment_times` (
  `shipment_time_id` int(11) NOT NULL AUTO_INCREMENT,
  `shipment_time_name` varchar(255) NOT NULL,
  PRIMARY KEY (`shipment_time_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;
--
-- Dumping data for table `0_shipment_times`
--
INSERT INTO `0_shipment_times` (`shipment_time_id`, `shipment_time_name`) VALUES
(1, 'IMMEDIATE');


--
-- Table structure for table `0_systype_attachments`
--
DROP TABLE IF EXISTS `0_systype_attachments`; 

CREATE TABLE IF NOT EXISTS `0_systype_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL,
  `type_no` int(11) NOT NULL,
  `unique_name` varchar(60) NOT NULL,
  `filename` varchar(60) NOT NULL,
  `filesize` int(11) NOT NULL,
  `filetype` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `0_sys_types` (EXPORT INVOICE)
--
INSERT INTO `0_sys_types` (`type_id`, `type_no`, `next_reference`) VALUES ('41', '19', '1');

ALTER TABLE `0_sales_orders`  
ADD `contract_no` VARCHAR(255) NOT NULL,  
ADD `loading_type_id` INT( 11 ) NOT NULL,  
ADD `origin` VARCHAR(255) NOT NULL,  
ADD `lc_no` VARCHAR(255) NOT NULL, 
ADD `lc_date` DATE NOT NULL,  
ADD `bank_account_id` INT(11) NOT NULL, 
ADD `shipment_terms` TEXT NOT NULL, 
ADD `shipment_validity_date` DATE NOT NULL, 
ADD `port_of_loading` VARCHAR(255) NOT NULL, 
ADD `port_of_discharge` VARCHAR(255) NOT NULL, 
ADD `destination` VARCHAR(255) NOT NULL, 
ADD `remarks` TEXT NOT NULL, 
ADD `shipment_time_id` INT( 11 ) NOT NULL, 
ADD `shipping_id` INT( 11 ) NOT NULL ;



ALTER TABLE `0_shipping_details` ADD `container_no` VARCHAR( 255 ) NOT NULL;


ALTER TABLE `0_bank_accounts` ADD `bank_iban` VARCHAR( 255 ) NOT NULL ,
ADD `bank_swift_code` VARCHAR( 255 ) NOT NULL; 



ALTER TABLE `0_sales_orders` 
ADD `export` TINYINT NOT NULL DEFAULT '0' COMMENT '1 for export sale,0 for local sale';



ALTER TABLE `0_sales_orders` ADD `packing` VARCHAR( 255 ) NOT NULL ,
ADD `merchandise_tolerance` VARCHAR( 255 ) NOT NULL ,
ADD `loading_details` VARCHAR( 255 ) NOT NULL;


ALTER TABLE `0_purch_orders` ADD `shipping_id` INT( 11 ) NOT NULL;



ALTER TABLE `0_bank_trans` ADD `cheque` BOOLEAN NOT NULL COMMENT '0 cash, 1 cheque',
ADD `cheque_date` DATE NOT NULL;


ALTER TABLE `0_shipping_details` CHANGE `first_weight_date` `first_weight_date` DATE NOT NULL ,
CHANGE `second_weight_date` `second_weight_date` DATE NOT NULL; 





