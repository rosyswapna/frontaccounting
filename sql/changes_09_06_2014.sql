CREATE TABLE IF NOT EXISTS `loading_types` (
  `loading_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `loading_type_name` varchar(255) NOT NULL,
  PRIMARY KEY (`loading_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;
INSERT INTO `loading_types` (`loading_type_id`, `loading_type_name`) VALUES
(1, 'PICK-UP'),
(2, 'DELIVER');

-------------------------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `shipment_times` (
  `shipment_time_id` int(11) NOT NULL AUTO_INCREMENT,
  `shipment_time_name` varchar(255) NOT NULL,
  PRIMARY KEY (`shipment_time_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;
INSERT INTO `shipment_times` (`shipment_time_id`, `shipment_time_name`) VALUES
(1, 'IMMEDIATE');


-------------------------------------------------------------------------------------------

ALTER TABLE `sales_orders`  
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
ADD `shipping_id` INT( 11 ) NOT NULL 



-------------------------------------------------------------------------------------------

ALTER TABLE `shipping_details` ADD `container_no` VARCHAR( 255 ) NOT NULL;

-------------------------------------------------------------------------------------------
