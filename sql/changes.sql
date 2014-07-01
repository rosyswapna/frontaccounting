___________________________________________________________________________________________________________
June 9 2014
___________________________________________________________________________________________________________

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


___________________________________________________________________________________________________________
June 13 2014___________________________________________________________________________________________________________

ALTER TABLE `bank_accounts` ADD `bank_iban` VARCHAR( 255 ) NOT NULL ,
ADD `bank_swift_code` VARCHAR( 255 ) NOT NULL 

------------------------------------------------------------------------------------------

ALTER TABLE `sales_orders` 
ADD `export` TINYINT NOT NULL DEFAULT '0' COMMENT '1 for export sale,0 for local sale'
------------------------------------------------------------------------------------------

___________________________________________________________________________________________________________
June 16 2014___________________________________________________________________________________________________________

CREATE TABLE IF NOT EXISTS `systype_attachments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL,
  `type_no` int(11) NOT NULL,
  `unique_name` varchar(60) NOT NULL,
  `filename` varchar(60) NOT NULL,
  `filesize` int(11) NOT NULL,
  `filetype` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

___________________________________________________________________________________________________________
June 17 2014___________________________________________________________________________________________________________

INSERT INTO `sys_types` (`type_id`, `type_no`, `next_reference`) VALUES ('41', '19', '1');

___________________________________________________________________________________________________________
June 26 2014___________________________________________________________________________________________________________

ALTER TABLE `sales_orders` ADD `packing` VARCHAR( 255 ) NOT NULL ,
ADD `merchandise_tolerance` VARCHAR( 255 ) NOT NULL ,
ADD `loading_details` VARCHAR( 255 ) NOT NULL 


___________________________________________________________________________________________________________
June 27 2014___________________________________________________________________________________________________________

ALTER TABLE `purch_orders` ADD `shipping_id` INT( 11 ) NOT NULL 


-------------------------------------------------------------------------------------------
-----------------server not updated with following queries---------------------------------
-------------------------------------------------------------------------------------------
___________________________________________________________________________________________________________
July 01 2014___________________________________________________________________________________________________________
ALTER TABLE `bank_trans` ADD `cheque` BOOLEAN NOT NULL COMMENT '0 cash, 1 cheque',
ADD `cheque_date` DATE NOT NULL 



