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

ALTER TABLE `debtor_trans` CHANGE `loading` `loading_type_id` INT( 11 ) NOT NULL;
ALTER TABLE `debtor_trans` CHANGE `lc_details` `lc_no` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `debtor_trans` ADD `lc_date` DATE NOT NULL AFTER `lc_no`; 
ALTER TABLE `debtor_trans` CHANGE `final_delivery_point` `destination` VARCHAR( 255 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `debtor_trans` ADD `shipment_time_id` INT( 11 ) NOT NULL 
ALTER TABLE `debtor_trans` ADD `shipping_id` INT( 11 ) NOT NULL 

-------------------------------------------------------------------------------------------

ALTER TABLE `shipping_details` ADD `container_no` VARCHAR( 255 ) NOT NULL;

-------------------------------------------------------------------------------------------

