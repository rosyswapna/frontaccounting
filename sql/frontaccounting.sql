-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 17, 2014 at 07:27 AM
-- Server version: 5.5.20
-- PHP Version: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `frontaccounting`
--

-- --------------------------------------------------------

--
-- Table structure for table `amortisation`
--

CREATE TABLE IF NOT EXISTS `amortisation` (
  `amortisation_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) DEFAULT NULL,
  `amortisation_year` int(11) DEFAULT NULL,
  `asset_value` double DEFAULT NULL,
  `amount` double DEFAULT NULL,
  `posted` int(11) NOT NULL DEFAULT '0',
  `inactive` tinyint(4) NOT NULL DEFAULT '0',
  `details` text,
  PRIMARY KEY (`amortisation_id`),
  UNIQUE KEY `amortisation_id` (`amortisation_id`),
  KEY `amortisation_asset_id` (`asset_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `areas`
--

CREATE TABLE IF NOT EXISTS `areas` (
  `area_code` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`area_code`),
  UNIQUE KEY `description` (`description`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `areas`
--

INSERT INTO `areas` (`area_code`, `description`, `inactive`) VALUES
(1, 'Global', 0);

-- --------------------------------------------------------

--
-- Table structure for table `assets`
--

CREATE TABLE IF NOT EXISTS `assets` (
  `asset_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `asset_type_id` int(11) DEFAULT NULL,
  `item_id` int(11) DEFAULT NULL,
  `asset_name` varchar(50) DEFAULT NULL,
  `asset_serial` varchar(50) DEFAULT NULL,
  `purchase_date` date NOT NULL,
  `purchase_value` double NOT NULL,
  `disposal_amount` double DEFAULT NULL,
  `disposal_date` date DEFAULT NULL,
  `disposal_posting` int(11) DEFAULT '0',
  `tag_number` varchar(50) DEFAULT NULL,
  `asset_location` varchar(50) DEFAULT NULL,
  `asset_condition` varchar(50) DEFAULT NULL,
  `asset_acquisition` varchar(50) DEFAULT NULL,
  `inactive` tinyint(4) NOT NULL DEFAULT '0',
  `details` text,
  PRIMARY KEY (`asset_id`),
  UNIQUE KEY `asset_id` (`asset_id`),
  KEY `assets_asset_type_id` (`asset_type_id`),
  KEY `assets_item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `asset_types`
--

CREATE TABLE IF NOT EXISTS `asset_types` (
  `asset_type_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `asset_type_name` varchar(50) NOT NULL,
  `depreciation_type` int(11) NOT NULL DEFAULT '1',
  `depreciation_rate` double NOT NULL DEFAULT '10',
  `asset_account` varchar(15) DEFAULT NULL,
  `depreciation_account` varchar(15) DEFAULT NULL,
  `accumulated_account` varchar(15) DEFAULT NULL,
  `valuation_account` varchar(15) DEFAULT NULL,
  `disposal_account` varchar(15) DEFAULT NULL,
  `inactive` tinyint(4) NOT NULL DEFAULT '0',
  `details` text,
  PRIMARY KEY (`asset_type_id`),
  UNIQUE KEY `asset_type_id` (`asset_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `asset_valuations`
--

CREATE TABLE IF NOT EXISTS `asset_valuations` (
  `asset_valuation_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` int(11) DEFAULT NULL,
  `valuation_year` int(11) DEFAULT NULL,
  `asset_value` double NOT NULL DEFAULT '0',
  `value_change` double NOT NULL DEFAULT '0',
  `posted` int(11) NOT NULL DEFAULT '0',
  `inactive` tinyint(4) NOT NULL DEFAULT '0',
  `details` text,
  PRIMARY KEY (`asset_valuation_id`),
  UNIQUE KEY `asset_valuation_id` (`asset_valuation_id`),
  UNIQUE KEY `asset_id` (`asset_id`,`valuation_year`),
  KEY `asset_valuations_asset_id` (`asset_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE IF NOT EXISTS `attachments` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL DEFAULT '',
  `type_no` int(11) NOT NULL DEFAULT '0',
  `trans_no` int(11) NOT NULL DEFAULT '0',
  `unique_name` varchar(60) NOT NULL DEFAULT '',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `filename` varchar(60) NOT NULL DEFAULT '',
  `filesize` int(11) NOT NULL DEFAULT '0',
  `filetype` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `type_no` (`type_no`,`trans_no`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `audit_trail`
--

CREATE TABLE IF NOT EXISTS `audit_trail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) unsigned NOT NULL DEFAULT '0',
  `trans_no` int(11) unsigned NOT NULL DEFAULT '0',
  `user` smallint(6) unsigned NOT NULL DEFAULT '0',
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `description` varchar(60) DEFAULT NULL,
  `fiscal_year` int(11) NOT NULL,
  `gl_date` date NOT NULL DEFAULT '0000-00-00',
  `gl_seq` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `Seq` (`fiscal_year`,`gl_date`,`gl_seq`),
  KEY `Type_and_Number` (`type`,`trans_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

--
-- Dumping data for table `audit_trail`
--

INSERT INTO `audit_trail` (`id`, `type`, `trans_no`, `user`, `stamp`, `description`, `fiscal_year`, `gl_date`, `gl_seq`) VALUES
(1, 30, 6, 1, '2014-05-06 07:42:36', '', 6, '2013-12-31', 0),
(2, 30, 7, 1, '2014-05-06 07:48:31', '', 6, '2013-12-31', 0),
(3, 30, 8, 1, '2014-05-06 07:51:24', '', 7, '2014-05-06', 0),
(4, 30, 9, 1, '2014-05-07 05:29:53', '', 7, '2014-05-07', 0),
(5, 32, 1, 1, '2014-05-07 05:32:44', '', 7, '2014-05-07', 0),
(6, 32, 2, 1, '2014-05-07 05:34:51', '', 7, '2014-05-07', 0),
(7, 32, 0, 1, '2014-05-07 06:13:30', 'Deleted.', 7, '2014-05-07', 0),
(8, 32, 3, 1, '2014-05-07 06:21:09', '', 7, '2014-05-07', 0),
(9, 32, 4, 1, '2014-05-07 06:46:26', '', 7, '2014-05-07', NULL),
(10, 32, 5, 1, '2014-05-07 06:24:37', '', 7, '2014-05-07', 0),
(11, 32, 4, 1, '2014-05-07 06:46:26', 'Updated.', 7, '2014-05-07', 0),
(12, 30, 10, 1, '2014-05-07 06:53:57', '', 7, '2014-05-07', 0),
(13, 32, 6, 1, '2014-05-29 04:27:09', '', 7, '2014-05-29', 0),
(14, 32, 7, 1, '2014-05-30 07:31:51', '', 7, '2014-05-30', 0),
(15, 32, 8, 1, '2014-05-30 07:32:06', '', 7, '2014-05-30', 0),
(16, 30, 11, 1, '2014-06-12 06:59:51', '', 7, '2014-06-12', 0),
(17, 13, 6, 1, '2014-06-12 06:59:51', '', 7, '2014-06-12', 0),
(18, 10, 20, 1, '2014-06-12 06:59:51', '', 7, '2014-06-12', 0),
(19, 12, 1, 1, '2014-06-12 06:59:51', '', 7, '2014-06-12', 0),
(20, 30, 12, 1, '2014-06-12 09:16:02', '', 7, '2014-06-12', 0),
(21, 13, 7, 1, '2014-06-12 09:16:02', '', 7, '2014-06-12', 0),
(22, 10, 21, 1, '2014-06-12 09:16:02', '', 7, '2014-06-12', 0),
(23, 12, 2, 1, '2014-06-12 09:16:02', '', 7, '2014-06-12', 0),
(24, 30, 13, 1, '2014-06-12 09:20:52', '', 7, '2014-06-12', 0),
(25, 13, 8, 1, '2014-06-12 09:20:52', '', 7, '2014-06-12', 0),
(26, 10, 22, 1, '2014-06-12 09:20:52', '', 7, '2014-06-12', 0),
(27, 12, 3, 1, '2014-06-12 09:20:52', '', 7, '2014-06-12', 0);

-- --------------------------------------------------------

--
-- Table structure for table `bank_accounts`
--

CREATE TABLE IF NOT EXISTS `bank_accounts` (
  `account_code` varchar(15) NOT NULL DEFAULT '',
  `account_type` smallint(6) NOT NULL DEFAULT '0',
  `bank_account_name` varchar(60) NOT NULL DEFAULT '',
  `bank_account_number` varchar(100) NOT NULL DEFAULT '',
  `bank_name` varchar(60) NOT NULL DEFAULT '',
  `bank_address` tinytext,
  `bank_curr_code` char(3) NOT NULL DEFAULT '',
  `dflt_curr_act` tinyint(1) NOT NULL DEFAULT '0',
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `last_reconciled_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ending_reconcile_balance` double NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  `bank_iban` varchar(255) NOT NULL,
  `bank_swift_code` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `bank_account_name` (`bank_account_name`),
  KEY `bank_account_number` (`bank_account_number`),
  KEY `account_code` (`account_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `bank_accounts`
--

INSERT INTO `bank_accounts` (`account_code`, `account_type`, `bank_account_name`, `bank_account_number`, `bank_name`, `bank_address`, `bank_curr_code`, `dflt_curr_act`, `id`, `last_reconciled_date`, `ending_reconcile_balance`, `inactive`, `bank_iban`, `bank_swift_code`) VALUES
('1060', 0, 'Current account', '9999999999', 'Wachovia Bank', '', 'USD', 1, 1, '0000-00-00 00:00:00', 0, 0, '', ''),
('1065', 3, 'Petty Cash account', 'N/A', 'N/A', '', 'USD', 0, 2, '0000-00-00 00:00:00', 0, 0, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `bank_trans`
--

CREATE TABLE IF NOT EXISTS `bank_trans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) DEFAULT NULL,
  `trans_no` int(11) DEFAULT NULL,
  `bank_act` varchar(15) NOT NULL DEFAULT '',
  `ref` varchar(40) DEFAULT NULL,
  `trans_date` date NOT NULL DEFAULT '0000-00-00',
  `amount` double DEFAULT NULL,
  `dimension_id` int(11) NOT NULL DEFAULT '0',
  `dimension2_id` int(11) NOT NULL DEFAULT '0',
  `person_type_id` int(11) NOT NULL DEFAULT '0',
  `person_id` tinyblob,
  `reconciled` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bank_act` (`bank_act`,`ref`),
  KEY `type` (`type`,`trans_no`),
  KEY `bank_act_2` (`bank_act`,`reconciled`),
  KEY `bank_act_3` (`bank_act`,`trans_date`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `bank_trans`
--

INSERT INTO `bank_trans` (`id`, `type`, `trans_no`, `bank_act`, `ref`, `trans_date`, `amount`, `dimension_id`, `dimension2_id`, `person_type_id`, `person_id`, `reconciled`) VALUES
(1, 22, 4, '1', '1', '2009-06-21', -3465, 0, 0, 3, 0x32, NULL),
(2, 26, 1, '1', '', '2009-06-21', -10, 0, 0, 1, 0x31, NULL),
(3, 26, 1, '1', '', '2009-06-21', -20, 0, 0, 1, 0x30, NULL),
(4, 0, 18, '1', '1', '2009-02-20', 1000, 0, 0, 0, '', NULL),
(5, 0, 19, '1', '2', '2009-02-21', 4000, 0, 0, 0, '', NULL),
(6, 2, 5, '1', '1', '2009-06-21', 100, 0, 0, 4, 0x33, NULL),
(7, 1, 8, '1', '1', '2009-06-21', -50, 0, 0, 4, 0x31, NULL),
(8, 26, 5, '1', '', '2009-06-21', -10, 0, 0, 1, 0x31, NULL),
(9, 26, 5, '1', '', '2009-06-21', -20, 0, 0, 1, 0x30, NULL),
(10, 26, 7, '1', '', '2009-06-21', -10, 0, 0, 1, 0x31, NULL),
(11, 26, 7, '1', '', '2009-06-21', -20, 0, 0, 1, 0x30, NULL),
(12, 12, 1, '2', '1', '2014-06-12', 50, 0, 0, 2, 0x34, NULL),
(13, 12, 2, '2', '2', '2014-06-12', 200, 0, 0, 2, 0x34, NULL),
(14, 12, 3, '2', '3', '2014-06-12', 200, 0, 0, 2, 0x34, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `bom`
--

CREATE TABLE IF NOT EXISTS `bom` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent` char(20) NOT NULL DEFAULT '',
  `component` char(20) NOT NULL DEFAULT '',
  `workcentre_added` int(11) NOT NULL DEFAULT '0',
  `loc_code` char(5) NOT NULL DEFAULT '',
  `quantity` double NOT NULL DEFAULT '1',
  PRIMARY KEY (`parent`,`component`,`workcentre_added`,`loc_code`),
  KEY `component` (`component`),
  KEY `id` (`id`),
  KEY `loc_code` (`loc_code`),
  KEY `parent` (`parent`,`loc_code`),
  KEY `workcentre_added` (`workcentre_added`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `bom`
--

INSERT INTO `bom` (`id`, `parent`, `component`, `workcentre_added`, `loc_code`, `quantity`) VALUES
(1, '3400', '102', 1, 'DEF', 1),
(2, '3400', '103', 1, 'DEF', 1),
(3, '3400', '104', 1, 'DEF', 1);

-- --------------------------------------------------------

--
-- Table structure for table `budget_trans`
--

CREATE TABLE IF NOT EXISTS `budget_trans` (
  `counter` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) NOT NULL DEFAULT '0',
  `type_no` bigint(16) NOT NULL DEFAULT '1',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `account` varchar(15) NOT NULL DEFAULT '',
  `memo_` tinytext NOT NULL,
  `amount` double NOT NULL DEFAULT '0',
  `dimension_id` int(11) DEFAULT '0',
  `dimension2_id` int(11) DEFAULT '0',
  `person_type_id` int(11) DEFAULT NULL,
  `person_id` tinyblob,
  PRIMARY KEY (`counter`),
  KEY `Type_and_Number` (`type`,`type_no`),
  KEY `Account` (`account`,`tran_date`,`dimension_id`,`dimension2_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `chart_class`
--

CREATE TABLE IF NOT EXISTS `chart_class` (
  `cid` varchar(3) NOT NULL,
  `class_name` varchar(60) NOT NULL DEFAULT '',
  `ctype` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chart_class`
--

INSERT INTO `chart_class` (`cid`, `class_name`, `ctype`, `inactive`) VALUES
('1', 'Assets', 1, 0),
('2', 'Liabilities', 2, 0),
('3', 'Income', 4, 0),
('4', 'Costs', 6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `chart_master`
--

CREATE TABLE IF NOT EXISTS `chart_master` (
  `account_code` varchar(15) NOT NULL DEFAULT '',
  `account_code2` varchar(15) NOT NULL DEFAULT '',
  `account_name` varchar(60) NOT NULL DEFAULT '',
  `account_type` varchar(10) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`account_code`),
  KEY `account_name` (`account_name`),
  KEY `accounts_by_type` (`account_type`,`account_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chart_master`
--

INSERT INTO `chart_master` (`account_code`, `account_code2`, `account_name`, `account_type`, `inactive`) VALUES
('1060', '', 'Checking Account', '1', 0),
('1065', '', 'Petty Cash', '1', 0),
('1200', '', 'Accounts Receivables', '1', 0),
('1205', '', 'Allowance for doubtful accounts', '1', 0),
('1510', '', 'Inventory', '2', 0),
('1520', '', 'Stocks of Raw Materials', '2', 0),
('1530', '', 'Stocks of Work In Progress', '2', 0),
('1540', '', 'Stocks of Finsihed Goods', '2', 0),
('1550', '', 'Goods Received Clearing account', '2', 0),
('1820', '', 'Office Furniture &amp; Equipment', '3', 0),
('1825', '', 'Accum. Amort. -Furn. &amp; Equip.', '3', 0),
('1840', '', 'Vehicle', '3', 0),
('1845', '', 'Accum. Amort. -Vehicle', '3', 0),
('2100', '', 'Accounts Payable', '4', 0),
('2110', '', 'Accrued Income Tax - Federal', '4', 0),
('2120', '', 'Accrued Income Tax - State', '4', 0),
('2130', '', 'Accrued Franchise Tax', '4', 0),
('2140', '', 'Accrued Real &amp; Personal Prop Tax', '4', 0),
('2150', '', 'Sales Tax', '4', 0),
('2160', '', 'Accrued Use Tax Payable', '4', 0),
('2210', '', 'Accrued Wages', '4', 0),
('2220', '', 'Accrued Comp Time', '4', 0),
('2230', '', 'Accrued Holiday Pay', '4', 0),
('2240', '', 'Accrued Vacation Pay', '4', 0),
('2310', '', 'Accr. Benefits - 401K', '4', 0),
('2320', '', 'Accr. Benefits - Stock Purchase', '4', 0),
('2330', '', 'Accr. Benefits - Med, Den', '4', 0),
('2340', '', 'Accr. Benefits - Payroll Taxes', '4', 0),
('2350', '', 'Accr. Benefits - Credit Union', '4', 0),
('2360', '', 'Accr. Benefits - Savings Bond', '4', 0),
('2370', '', 'Accr. Benefits - Garnish', '4', 0),
('2380', '', 'Accr. Benefits - Charity Cont.', '4', 0),
('2620', '', 'Bank Loans', '5', 0),
('2680', '', 'Loans from Shareholders', '5', 0),
('3350', '', 'Common Shares', '6', 0),
('3590', '', 'Retained Earnings - prior years', '7', 0),
('4010', '', 'Sales', '8', 0),
('4430', '', 'Shipping &amp; Handling', '9', 0),
('4440', '', 'Interest', '9', 0),
('4450', '', 'Foreign Exchange Gain', '9', 0),
('4500', '', 'Prompt Payment Discounts', '9', 0),
('4510', '', 'Discounts Given', '9', 0),
('5010', '', 'Cost of Goods Sold - Retail', '10', 0),
('5020', '', 'Material Usage Varaiance', '10', 0),
('5030', '', 'Consumable Materials', '10', 0),
('5040', '', 'Purchase price Variance', '10', 0),
('5050', '', 'Purchases of materials', '10', 0),
('5060', '', 'Discounts Received', '10', 0),
('5100', '', 'Freight', '10', 0),
('5410', '', 'Wages &amp; Salaries', '11', 0),
('5420', '', 'Wages - Overtime', '11', 0),
('5430', '', 'Benefits - Comp Time', '11', 0),
('5440', '', 'Benefits - Payroll Taxes', '11', 0),
('5450', '', 'Benefits - Workers Comp', '11', 0),
('5460', '', 'Benefits - Pension', '11', 0),
('5470', '', 'Benefits - General Benefits', '11', 0),
('5510', '', 'Inc Tax Exp - Federal', '11', 0),
('5520', '', 'Inc Tax Exp - State', '11', 0),
('5530', '', 'Taxes - Real Estate', '11', 0),
('5540', '', 'Taxes - Personal Property', '11', 0),
('5550', '', 'Taxes - Franchise', '11', 0),
('5560', '', 'Taxes - Foreign Withholding', '11', 0),
('5610', '', 'Accounting &amp; Legal', '12', 0),
('5615', '', 'Advertising &amp; Promotions', '12', 0),
('5620', '', 'Bad Debts', '12', 0),
('5660', '', 'Amortization Expense', '12', 0),
('5685', '', 'Insurance', '12', 0),
('5690', '', 'Interest &amp; Bank Charges', '12', 0),
('5700', '', 'Office Supplies', '12', 0),
('5760', '', 'Rent', '12', 0),
('5765', '', 'Repair &amp; Maintenance', '12', 0),
('5780', '', 'Telephone', '12', 0),
('5785', '', 'Travel &amp; Entertainment', '12', 0),
('5790', '', 'Utilities', '12', 0),
('5795', '', 'Registrations', '12', 0),
('5800', '', 'Licenses', '12', 0),
('5810', '', 'Foreign Exchange Loss', '12', 0),
('9990', '', 'Year Profit/Loss', '12', 0);

-- --------------------------------------------------------

--
-- Table structure for table `chart_types`
--

CREATE TABLE IF NOT EXISTS `chart_types` (
  `id` varchar(10) NOT NULL,
  `name` varchar(60) NOT NULL DEFAULT '',
  `class_id` varchar(3) NOT NULL DEFAULT '',
  `parent` varchar(10) NOT NULL DEFAULT '-1',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `class_id` (`class_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chart_types`
--

INSERT INTO `chart_types` (`id`, `name`, `class_id`, `parent`, `inactive`) VALUES
('1', 'Current Assets', '1', '', 0),
('2', 'Inventory Assets', '1', '', 0),
('3', 'Capital Assets', '1', '', 0),
('4', 'Current Liabilities', '2', '', 0),
('5', 'Long Term Liabilities', '2', '', 0),
('6', 'Share Capital', '2', '', 0),
('7', 'Retained Earnings', '2', '', 0),
('8', 'Sales Revenue', '3', '', 0),
('9', 'Other Revenue', '3', '', 0),
('10', 'Cost of Goods Sold', '4', '', 0),
('11', 'Payroll Expenses', '4', '', 0),
('12', 'General &amp; Administrative expenses', '4', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `type` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL DEFAULT '0',
  `date_` date DEFAULT '0000-00-00',
  `memo_` tinytext,
  KEY `type_and_id` (`type`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`type`, `id`, `date_`, `memo_`) VALUES
(12, 1, '2014-06-12', 'Cash invoice 20'),
(12, 2, '2014-06-12', 'Cash invoice 21'),
(12, 3, '2014-06-12', 'Cash invoice 22');

-- --------------------------------------------------------

--
-- Table structure for table `credit_status`
--

CREATE TABLE IF NOT EXISTS `credit_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reason_description` char(100) NOT NULL DEFAULT '',
  `dissallow_invoices` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `reason_description` (`reason_description`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `credit_status`
--

INSERT INTO `credit_status` (`id`, `reason_description`, `dissallow_invoices`, `inactive`) VALUES
(1, 'Good History', 0, 0),
(3, 'No more work until payment received', 1, 0),
(4, 'In liquidation', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `crm_categories`
--

CREATE TABLE IF NOT EXISTS `crm_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'pure technical key',
  `type` varchar(20) NOT NULL COMMENT 'contact type e.g. customer',
  `action` varchar(20) NOT NULL COMMENT 'detailed usage e.g. department',
  `name` varchar(30) NOT NULL COMMENT 'for category selector',
  `description` tinytext NOT NULL COMMENT 'usage description',
  `system` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'nonzero for core system usage',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`,`action`),
  UNIQUE KEY `type_2` (`type`,`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `crm_categories`
--

INSERT INTO `crm_categories` (`id`, `type`, `action`, `name`, `description`, `system`, `inactive`) VALUES
(1, 'cust_branch', 'general', 'General', 'General contact data for customer branch (overrides company setting)', 1, 0),
(2, 'cust_branch', 'invoice', 'Invoices', 'Invoice posting (overrides company setting)', 1, 0),
(3, 'cust_branch', 'order', 'Orders', 'Order confirmation (overrides company setting)', 1, 0),
(4, 'cust_branch', 'delivery', 'Deliveries', 'Delivery coordination (overrides company setting)', 1, 0),
(5, 'customer', 'general', 'General', 'General contact data for customer', 1, 0),
(6, 'customer', 'order', 'Orders', 'Order confirmation', 1, 0),
(7, 'customer', 'delivery', 'Deliveries', 'Delivery coordination', 1, 0),
(8, 'customer', 'invoice', 'Invoices', 'Invoice posting', 1, 0),
(9, 'supplier', 'general', 'General', 'General contact data for supplier', 1, 0),
(10, 'supplier', 'order', 'Orders', 'Order confirmation', 1, 0),
(11, 'supplier', 'delivery', 'Deliveries', 'Delivery coordination', 1, 0),
(12, 'supplier', 'invoice', 'Invoices', 'Invoice posting', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `crm_contacts`
--

CREATE TABLE IF NOT EXISTS `crm_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `person_id` int(11) NOT NULL DEFAULT '0' COMMENT 'foreign key to crm_contacts',
  `type` varchar(20) NOT NULL COMMENT 'foreign key to crm_categories',
  `action` varchar(20) NOT NULL COMMENT 'foreign key to crm_categories',
  `entity_id` varchar(11) DEFAULT NULL COMMENT 'entity id in related class table',
  PRIMARY KEY (`id`),
  KEY `type` (`type`,`action`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `crm_contacts`
--

INSERT INTO `crm_contacts` (`id`, `person_id`, `type`, `action`, `entity_id`) VALUES
(1, 1, 'customer', 'general', '1'),
(2, 2, 'customer', 'general', '2'),
(3, 3, 'customer', 'general', '3'),
(4, 4, 'cust_branch', 'general', '1'),
(5, 5, 'cust_branch', 'general', '2'),
(6, 6, 'cust_branch', 'general', '3'),
(7, 7, 'supplier', 'general', '1'),
(8, 8, 'supplier', 'general', '2'),
(9, 9, 'supplier', 'general', '3'),
(10, 10, 'cust_branch', 'general', '4'),
(11, 10, 'customer', 'general', '4'),
(12, 11, 'cust_branch', 'general', '5'),
(13, 11, 'customer', 'general', '5');

-- --------------------------------------------------------

--
-- Table structure for table `crm_persons`
--

CREATE TABLE IF NOT EXISTS `crm_persons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ref` varchar(30) NOT NULL,
  `name` varchar(60) NOT NULL,
  `name2` varchar(60) DEFAULT NULL,
  `address` tinytext,
  `phone` varchar(30) DEFAULT NULL,
  `phone2` varchar(30) DEFAULT NULL,
  `fax` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `lang` char(5) DEFAULT NULL,
  `notes` tinytext NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ref` (`ref`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `crm_persons`
--

INSERT INTO `crm_persons` (`id`, `ref`, `name`, `name2`, `address`, `phone`, `phone2`, `fax`, `email`, `lang`, `notes`, `inactive`) VALUES
(1, 'Beefeater', '', NULL, NULL, NULL, NULL, NULL, '', '', '', 0),
(2, 'Ghostbusters', '', NULL, NULL, NULL, NULL, NULL, '', NULL, '', 0),
(3, 'Brezan', '', NULL, NULL, NULL, NULL, NULL, '', '', '', 0),
(4, 'Beefeater', 'Main Branch', NULL, '', '', '', '', '', NULL, '', 0),
(5, 'Ghostbusters', 'Main Branch', NULL, 'Address 1\nAddress 2\nAddress 3', '', '', '', '', NULL, '', 0),
(6, 'Brezan', 'Main Branch', NULL, 'Address 1\nAddress 2\nAddress 3', '', '', '', '', NULL, '', 0),
(7, 'Junk Beer', 'Contact', NULL, 'Address 1\nAddress 2\nAddress 3', '+45 55667788', '', '', '', '', '', 0),
(8, 'Lucky Luke', 'Luke', NULL, 'Address 1\nAddress 2\nAddress 3', '(111) 222.333.444', '', '', '', NULL, '', 0),
(9, 'Money Makers', 'Makers', NULL, 'Address 1\nAddress 2\nAddress 3', '+44 444 555 666', '', '', '', '', '', 0),
(10, 'Rosy Swapna', 'rosy', '', 'ernakulam', '123456', '', '', 'rosy.swapna@gmail.com', '', '', 0),
(11, 'df', 'df', '', 'sdf', '', '', '', '', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE IF NOT EXISTS `currencies` (
  `currency` varchar(60) NOT NULL DEFAULT '',
  `curr_abrev` char(3) NOT NULL DEFAULT '',
  `curr_symbol` varchar(10) NOT NULL DEFAULT '',
  `country` varchar(100) NOT NULL DEFAULT '',
  `hundreds_name` varchar(15) NOT NULL DEFAULT '',
  `auto_update` tinyint(1) NOT NULL DEFAULT '1',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`curr_abrev`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`currency`, `curr_abrev`, `curr_symbol`, `country`, `hundreds_name`, `auto_update`, `inactive`) VALUES
('US Dollars', 'USD', '$', 'United States', 'Cents', 1, 0),
('CA Dollars', 'CAD', '$', 'Canada', 'Cents', 1, 0),
('Euro', 'EUR', '?', 'Europe', 'Cents', 1, 0),
('Pounds', 'GBP', '?', 'England', 'Pence', 1, 0),
('DK Kroner', 'DKK', '?', 'Denmark', 'Ore', 1, 0),
('UAE Dirham', 'AED', 'DH', 'United Arab Emirates', 'Fils', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `cust_allocations`
--

CREATE TABLE IF NOT EXISTS `cust_allocations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amt` double unsigned DEFAULT NULL,
  `date_alloc` date NOT NULL DEFAULT '0000-00-00',
  `trans_no_from` int(11) DEFAULT NULL,
  `trans_type_from` int(11) DEFAULT NULL,
  `trans_no_to` int(11) DEFAULT NULL,
  `trans_type_to` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `From` (`trans_type_from`,`trans_no_from`),
  KEY `To` (`trans_type_to`,`trans_no_to`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `cust_allocations`
--

INSERT INTO `cust_allocations` (`id`, `amt`, `date_alloc`, `trans_no_from`, `trans_type_from`, `trans_no_to`, `trans_type_to`) VALUES
(1, 37.68, '2009-06-21', 3, 11, 18, 10),
(2, 50, '2014-06-12', 1, 12, 20, 10),
(3, 200, '2014-06-12', 2, 12, 21, 10),
(4, 200, '2014-06-12', 3, 12, 22, 10);

-- --------------------------------------------------------

--
-- Table structure for table `cust_branch`
--

CREATE TABLE IF NOT EXISTS `cust_branch` (
  `branch_code` int(11) NOT NULL AUTO_INCREMENT,
  `debtor_no` int(11) NOT NULL DEFAULT '0',
  `br_name` varchar(60) NOT NULL DEFAULT '',
  `branch_ref` varchar(30) NOT NULL DEFAULT '',
  `br_address` tinytext NOT NULL,
  `area` int(11) DEFAULT NULL,
  `salesman` int(11) NOT NULL DEFAULT '0',
  `contact_name` varchar(60) NOT NULL DEFAULT '',
  `default_location` varchar(5) NOT NULL DEFAULT '',
  `tax_group_id` int(11) DEFAULT NULL,
  `sales_account` varchar(15) NOT NULL DEFAULT '',
  `sales_discount_account` varchar(15) NOT NULL DEFAULT '',
  `receivables_account` varchar(15) NOT NULL DEFAULT '',
  `payment_discount_account` varchar(15) NOT NULL DEFAULT '',
  `default_ship_via` int(11) NOT NULL DEFAULT '1',
  `disable_trans` tinyint(4) NOT NULL DEFAULT '0',
  `br_post_address` tinytext NOT NULL,
  `group_no` int(11) NOT NULL DEFAULT '0',
  `notes` tinytext NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`branch_code`,`debtor_no`),
  KEY `branch_code` (`branch_code`),
  KEY `branch_ref` (`branch_ref`),
  KEY `group_no` (`group_no`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `cust_branch`
--

INSERT INTO `cust_branch` (`branch_code`, `debtor_no`, `br_name`, `branch_ref`, `br_address`, `area`, `salesman`, `contact_name`, `default_location`, `tax_group_id`, `sales_account`, `sales_discount_account`, `receivables_account`, `payment_discount_account`, `default_ship_via`, `disable_trans`, `br_post_address`, `group_no`, `notes`, `inactive`) VALUES
(1, 1, 'Beefeater Ltd.', 'Beefeater', '', 1, 1, 'Main Branch', 'DEF', 2, '', '4510', '1200', '4500', 1, 0, 'Address 1\nAddress 2\nAddress 3', 0, '', 0),
(2, 2, 'Ghostbusters Corp.', 'Ghostbusters', 'Address 1\nAddress 2\nAddress 3', 1, 1, 'Main Branch', 'DEF', 1, '', '4510', '1200', '4500', 1, 0, 'Address 1\nAddress 2\nAddress 3', 0, '', 0),
(3, 3, 'Brezan', 'Brezan', 'Address 1\nAddress 2\nAddress 3', 1, 1, 'Main Branch', 'DEF', 1, '', '4510', '1200', '4500', 1, 0, 'Address 1\nAddress 2\nAddress 3', 0, '', 0),
(4, 4, 'Rosy Swapna', 'rosy', 'ernakulam', 1, 1, '', 'DEF', 1, '', '4510', '1200', '4500', 1, 0, 'ernakulam', 0, '', 0),
(5, 5, 'df', 'df', 'sdf', 1, 1, '', 'DEF', 1, '', '4510', '1200', '4500', 1, 0, 'sdf', 0, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `debtors_master`
--

CREATE TABLE IF NOT EXISTS `debtors_master` (
  `debtor_no` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `debtor_ref` varchar(30) NOT NULL,
  `address` tinytext,
  `tax_id` varchar(55) NOT NULL DEFAULT '',
  `curr_code` char(3) NOT NULL DEFAULT '',
  `sales_type` int(11) NOT NULL DEFAULT '1',
  `dimension_id` int(11) NOT NULL DEFAULT '0',
  `dimension2_id` int(11) NOT NULL DEFAULT '0',
  `credit_status` int(11) NOT NULL DEFAULT '0',
  `payment_terms` int(11) DEFAULT NULL,
  `discount` double NOT NULL DEFAULT '0',
  `pymt_discount` double NOT NULL DEFAULT '0',
  `credit_limit` float NOT NULL DEFAULT '1000',
  `notes` tinytext NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`debtor_no`),
  UNIQUE KEY `debtor_ref` (`debtor_ref`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `debtors_master`
--

INSERT INTO `debtors_master` (`debtor_no`, `name`, `debtor_ref`, `address`, `tax_id`, `curr_code`, `sales_type`, `dimension_id`, `dimension2_id`, `credit_status`, `payment_terms`, `discount`, `pymt_discount`, `credit_limit`, `notes`, `inactive`) VALUES
(1, 'Beefeater Ltd.', 'Beefeater', 'Addr 1\nAddr 2\nAddr 3', '345678', 'GBP', 2, 0, 0, 1, 3, 0, 0, 1000, '', 0),
(2, 'Ghostbusters Corp.', 'Ghostbusters', 'Address 1\nAddress 2\nAddress 3', '2222222', 'USD', 1, 0, 0, 1, 4, 0, 0, 1000, '', 0),
(3, 'Brezan', 'Brezan', 'Address 1\nAddress 2\nAddress 3', '7777777', 'EUR', 2, 0, 0, 1, 3, 0, 0, 1000, '', 0),
(4, 'Rosy Swapna', 'rosy', 'ernakulam', '', 'USD', 1, 0, 0, 1, 4, 0, 0, 1000, '', 0),
(5, 'df', 'df', 'sdf', '', 'USD', 1, 0, 0, 1, 4, 0, 0, 1000, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `debtor_trans`
--

CREATE TABLE IF NOT EXISTS `debtor_trans` (
  `trans_no` int(11) unsigned NOT NULL DEFAULT '0',
  `type` smallint(6) unsigned NOT NULL DEFAULT '0',
  `version` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `debtor_no` int(11) unsigned DEFAULT NULL,
  `branch_code` int(11) NOT NULL DEFAULT '-1',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `due_date` date NOT NULL DEFAULT '0000-00-00',
  `reference` varchar(60) NOT NULL DEFAULT '',
  `tpe` int(11) NOT NULL DEFAULT '0',
  `order_` int(11) NOT NULL DEFAULT '0',
  `ov_amount` double NOT NULL DEFAULT '0',
  `ov_gst` double NOT NULL DEFAULT '0',
  `ov_freight` double NOT NULL DEFAULT '0',
  `ov_freight_tax` double NOT NULL DEFAULT '0',
  `ov_discount` double NOT NULL DEFAULT '0',
  `alloc` double NOT NULL DEFAULT '0',
  `rate` double NOT NULL DEFAULT '1',
  `ship_via` int(11) DEFAULT NULL,
  `dimension_id` int(11) NOT NULL DEFAULT '0',
  `dimension2_id` int(11) NOT NULL DEFAULT '0',
  `payment_terms` int(11) DEFAULT NULL,
  PRIMARY KEY (`type`,`trans_no`),
  KEY `debtor_no` (`debtor_no`,`branch_code`),
  KEY `tran_date` (`tran_date`),
  KEY `order_` (`order_`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `debtor_trans`
--

INSERT INTO `debtor_trans` (`trans_no`, `type`, `version`, `debtor_no`, `branch_code`, `tran_date`, `due_date`, `reference`, `tpe`, `order_`, `ov_amount`, `ov_gst`, `ov_freight`, `ov_freight_tax`, `ov_discount`, `alloc`, `rate`, `ship_via`, `dimension_id`, `dimension2_id`, `payment_terms`) VALUES
(17, 10, 0, 2, 2, '2009-06-21', '2009-06-22', '1', 1, 2, 50, 2.5, 0, 0, 0, 0, 1, 1, 0, 0, 4),
(18, 10, 1, 3, 3, '2009-06-21', '2009-07-01', '2', 2, 3, 35.89, 1.79, 0, 0, 0, 37.68, 1.3932, 1, 2, 0, 3),
(19, 10, 0, 2, 2, '2009-06-21', '2009-06-22', '3', 1, 5, 50, 0, 5, 0, 0, 0, 1, 1, 0, 0, 4),
(20, 10, 0, 4, 4, '2014-06-12', '2014-06-12', '4', 1, 11, 50, 0, 0, 0, 0, 50, 1, 1, 0, 0, 4),
(21, 10, 0, 4, 4, '2014-06-12', '2014-06-12', '5', 1, 12, 200, 0, 0, 0, 0, 200, 1, 1, 0, 0, 4),
(22, 10, 0, 4, 4, '2014-06-12', '2014-06-12', '6', 1, 13, 200, 0, 0, 0, 0, 200, 1, 1, 0, 0, 4),
(3, 11, 0, 3, 3, '2009-06-21', '0000-00-00', '1', 2, 3, 35.89, 1.79, 0, 0, 0, 37.68, 1.3932, 1, 2, 0, 3),
(1, 12, 0, 4, 4, '2014-06-12', '0000-00-00', '1', 0, 0, 50, 0, 0, 0, 0, 50, 1, 0, 0, 0, NULL),
(2, 12, 0, 4, 4, '2014-06-12', '0000-00-00', '2', 0, 0, 200, 0, 0, 0, 0, 200, 1, 0, 0, 0, NULL),
(3, 12, 0, 4, 4, '2014-06-12', '0000-00-00', '3', 0, 0, 200, 0, 0, 0, 0, 200, 1, 0, 0, 0, NULL),
(2, 13, 0, 1, 1, '2009-06-21', '2009-06-22', '1', 2, 1, 60.8, 0, 10, 0, 0, 0, 1.6445729799917, 1, 0, 0, 3),
(3, 13, 1, 2, 2, '2009-06-21', '2009-06-22', 'auto', 1, 2, 50, 2.5, 0, 0, 0, 0, 1, 1, 0, 0, 4),
(4, 13, 1, 3, 3, '2009-06-21', '2009-07-01', 'auto', 2, 3, 35.89, 1.79, 0, 0, 0, 0, 1.3932, 1, 2, 0, 3),
(5, 13, 1, 2, 2, '2009-06-21', '2009-06-22', 'auto', 1, 5, 50, 0, 5, 0, 0, 0, 1, 1, 0, 0, 4),
(6, 13, 1, 4, 4, '2014-06-12', '2014-06-12', 'auto', 1, 11, 50, 0, 0, 0, 0, 0, 1, 1, 0, 0, 4),
(7, 13, 1, 4, 4, '2014-06-12', '2014-06-12', 'auto', 1, 12, 200, 0, 0, 0, 0, 0, 1, 1, 0, 0, 4),
(8, 13, 1, 4, 4, '2014-06-12', '2014-06-12', 'auto', 1, 13, 200, 0, 0, 0, 0, 0, 1, 1, 0, 0, 4);

-- --------------------------------------------------------

--
-- Table structure for table `debtor_trans_details`
--

CREATE TABLE IF NOT EXISTS `debtor_trans_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `debtor_trans_no` int(11) DEFAULT NULL,
  `debtor_trans_type` int(11) DEFAULT NULL,
  `stock_id` varchar(20) NOT NULL DEFAULT '',
  `description` tinytext,
  `unit_price` double NOT NULL DEFAULT '0',
  `unit_tax` double NOT NULL DEFAULT '0',
  `quantity` double NOT NULL DEFAULT '0',
  `discount_percent` double NOT NULL DEFAULT '0',
  `standard_cost` double NOT NULL DEFAULT '0',
  `qty_done` double NOT NULL DEFAULT '0',
  `src_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `Transaction` (`debtor_trans_type`,`debtor_trans_no`),
  KEY `src_id` (`src_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `debtor_trans_details`
--

INSERT INTO `debtor_trans_details` (`id`, `debtor_trans_no`, `debtor_trans_type`, `stock_id`, `description`, `unit_price`, `unit_tax`, `quantity`, `discount_percent`, `standard_cost`, `qty_done`, `src_id`) VALUES
(1, 2, 13, '102', '17inch VGA Monitor', 30.4, 0, 2, 0, 10, 0, 1),
(2, 3, 13, '102', '17inch VGA Monitor', 50, 2.5, 1, 0, 10, 1, 2),
(3, 17, 10, '102', '17inch VGA Monitor', 50, 2.5, 1, 0, 10, 2, 2),
(4, 4, 13, '102', '17inch VGA Monitor', 35.89, 1.79, 1, 0, 10, 1, 3),
(5, 18, 10, '102', '17inch VGA Monitor', 35.89, 1.79, 1, 0, 10, 1, 4),
(6, 5, 13, '102', '17inch VGA Monitor', 50, 2.38, 1, 0, 10, 1, 5),
(7, 19, 10, '102', '17inch VGA Monitor', 50, 2.38, 1, 0, 10, 0, 6),
(8, 3, 11, '102', '17inch VGA Monitor', 35.89, 1.79, 1, 0, 10, 0, 5),
(9, 6, 13, '102', '17inch VGA Monitor', 50, 2.38, 1, 0, 10, 1, 20),
(10, 20, 10, '102', '17inch VGA Monitor', 50, 2.38, 1, 0, 10, 0, 9),
(11, 7, 13, '102', '17inch VGA Monitor', 50, 2.38, 4, 0, 10, 4, 21),
(12, 21, 10, '102', '17inch VGA Monitor', 50, 2.38, 4, 0, 10, 0, 11),
(13, 8, 13, '102', '17inch VGA Monitor', 50, 2.38, 4, 0, 10, 4, 22),
(14, 22, 10, '102', '17inch VGA Monitor', 50, 2.38, 4, 0, 10, 0, 13);

-- --------------------------------------------------------

--
-- Table structure for table `dimensions`
--

CREATE TABLE IF NOT EXISTS `dimensions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(60) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `type_` tinyint(1) NOT NULL DEFAULT '1',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `date_` date NOT NULL DEFAULT '0000-00-00',
  `due_date` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `reference` (`reference`),
  KEY `date_` (`date_`),
  KEY `due_date` (`due_date`),
  KEY `type_` (`type_`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `dimensions`
--

INSERT INTO `dimensions` (`id`, `reference`, `name`, `type_`, `closed`, `date_`, `due_date`) VALUES
(1, '1', 'Support', 1, 0, '2009-06-21', '2015-07-11'),
(2, '2', 'Development', 1, 0, '2009-06-21', '2015-07-11');

-- --------------------------------------------------------

--
-- Table structure for table `exchange_rates`
--

CREATE TABLE IF NOT EXISTS `exchange_rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `curr_code` char(3) NOT NULL DEFAULT '',
  `rate_buy` double NOT NULL DEFAULT '0',
  `rate_sell` double NOT NULL DEFAULT '0',
  `date_` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `curr_code` (`curr_code`,`date_`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `exchange_rates`
--

INSERT INTO `exchange_rates` (`id`, `curr_code`, `rate_buy`, `rate_sell`, `date_`) VALUES
(1, 'DKK', 0.18717252868313, 0.18717252868313, '2009-06-21'),
(2, 'GBP', 1.6445729799917, 1.6445729799917, '2009-06-21'),
(3, 'EUR', 1.3932, 1.3932, '2009-06-21');

-- --------------------------------------------------------

--
-- Table structure for table `fiscal_year`
--

CREATE TABLE IF NOT EXISTS `fiscal_year` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `begin` date DEFAULT '0000-00-00',
  `end` date DEFAULT '0000-00-00',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `begin` (`begin`),
  UNIQUE KEY `end` (`end`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `fiscal_year`
--

INSERT INTO `fiscal_year` (`id`, `begin`, `end`, `closed`) VALUES
(1, '2008-01-01', '2008-12-31', 1),
(2, '2009-01-01', '2009-12-31', 1),
(3, '2010-01-01', '2010-12-31', 1),
(4, '2011-01-01', '2011-12-31', 1),
(5, '2012-01-01', '2012-12-31', 1),
(6, '2013-01-01', '2013-12-31', 1),
(7, '2014-01-01', '2014-12-31', 0);

-- --------------------------------------------------------

--
-- Table structure for table `gl_trans`
--

CREATE TABLE IF NOT EXISTS `gl_trans` (
  `counter` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) NOT NULL DEFAULT '0',
  `type_no` bigint(16) NOT NULL DEFAULT '1',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `account` varchar(15) NOT NULL DEFAULT '',
  `memo_` tinytext NOT NULL,
  `amount` double NOT NULL DEFAULT '0',
  `dimension_id` int(11) NOT NULL DEFAULT '0',
  `dimension2_id` int(11) NOT NULL DEFAULT '0',
  `person_type_id` int(11) DEFAULT NULL,
  `person_id` tinyblob,
  PRIMARY KEY (`counter`),
  KEY `Type_and_Number` (`type`,`type_no`),
  KEY `dimension_id` (`dimension_id`),
  KEY `dimension2_id` (`dimension2_id`),
  KEY `tran_date` (`tran_date`),
  KEY `account_and_tran_date` (`account`,`tran_date`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=107 ;

--
-- Dumping data for table `gl_trans`
--

INSERT INTO `gl_trans` (`counter`, `type`, `type_no`, `tran_date`, `account`, `memo_`, `amount`, `dimension_id`, `dimension2_id`, `person_type_id`, `person_id`) VALUES
(1, 20, 7, '2009-06-21', '2100', '', -3465, 0, 0, 3, 0x32),
(2, 20, 7, '2009-06-21', '1510', '', 1000, 0, 0, 3, 0x32),
(3, 20, 7, '2009-06-21', '1510', '', 1100, 0, 0, 3, 0x32),
(4, 20, 7, '2009-06-21', '1510', '', 1200, 0, 0, 3, 0x32),
(5, 20, 7, '2009-06-21', '2150', '', 165, 0, 0, 3, 0x32),
(6, 22, 4, '2009-06-21', '2100', '', 3465, 0, 0, 3, 0x32),
(7, 22, 4, '2009-06-21', '1060', '', -3465, 0, 0, 3, 0x32),
(8, 26, 1, '2009-06-21', '1510', '', -100, 0, 0, NULL, ''),
(9, 26, 1, '2009-06-21', '1510', '', -110, 0, 0, NULL, ''),
(10, 26, 1, '2009-06-21', '1510', '', -120, 0, 0, NULL, ''),
(11, 26, 1, '2009-06-21', '1060', 'Overhead Cost', -10, 0, 0, 1, 0x31),
(12, 26, 1, '2009-06-21', '1530', 'Overhead Cost', 10, 0, 0, 1, 0x31),
(13, 26, 1, '2009-06-21', '1060', 'Labour Cost', -20, 0, 0, 1, 0x30),
(14, 26, 1, '2009-06-21', '1530', 'Labour Cost', 20, 0, 0, 1, 0x30),
(15, 26, 1, '2009-06-21', '1510', '', 330, 0, 0, NULL, ''),
(16, 13, 2, '2009-06-21', '5010', '', 20, 0, 0, 2, 0x31),
(17, 13, 2, '2009-06-21', '1510', '', -20, 0, 0, 2, 0x31),
(18, 13, 3, '2009-06-21', '5010', '', 10, 0, 0, 2, 0x32),
(19, 13, 3, '2009-06-21', '1510', '', -10, 0, 0, 2, 0x32),
(20, 10, 17, '2009-06-21', '4010', '', -50, 0, 0, 2, 0x32),
(21, 10, 17, '2009-06-21', '1200', '', 52.5, 0, 0, 2, 0x32),
(22, 10, 17, '2009-06-21', '2150', '', -2.5, 0, 0, 2, 0x32),
(23, 13, 4, '2009-06-21', '5010', '', 10, 2, 0, 2, 0x33),
(24, 13, 4, '2009-06-21', '1510', '', -10, 0, 0, 2, 0x33),
(25, 10, 18, '2009-06-21', '4010', '', -50, 2, 0, 2, 0x33),
(26, 10, 18, '2009-06-21', '1200', '', 52.5, 0, 0, 2, 0x33),
(27, 10, 18, '2009-06-21', '2150', '', -2.5, 0, 0, 2, 0x33),
(28, 0, 18, '2009-02-20', '1060', '', 1000, 0, 0, NULL, ''),
(29, 0, 18, '2009-02-20', '3350', '', -1000, 0, 0, NULL, ''),
(30, 0, 19, '2009-02-21', '1060', '', 4000, 0, 0, NULL, ''),
(31, 0, 19, '2009-02-21', '3350', '', -4000, 0, 0, NULL, ''),
(32, 26, 3, '2009-06-21', '1510', '', -20, 0, 0, NULL, ''),
(33, 26, 3, '2009-06-21', '1510', '', -22, 0, 0, NULL, ''),
(34, 26, 3, '2009-06-21', '1510', '', -24, 0, 0, NULL, ''),
(35, 26, 3, '2009-06-21', '1540', '', 66, 0, 0, NULL, ''),
(36, 2, 5, '2009-06-21', '2150', 'Cash Sales', -4.76, 0, 0, 4, 0x33),
(37, 2, 5, '2009-06-21', '4010', 'Cash Sales', -95.24, 0, 0, 4, 0x33),
(38, 2, 5, '2009-06-21', '1060', '', 100, 0, 0, 4, 0x33),
(39, 1, 8, '2009-06-21', '2150', 'Maintenance', 2.38, 0, 0, 4, 0x31),
(40, 1, 8, '2009-06-21', '5765', 'Maintenance', 47.62, 0, 0, 4, 0x31),
(41, 1, 8, '2009-06-21', '1060', '', -50, 0, 0, 4, 0x31),
(42, 20, 8, '2009-06-21', '2100', '', -20, 0, 0, 3, 0x32),
(43, 20, 8, '2009-06-21', '2150', '', 0.95, 0, 0, 3, 0x32),
(44, 20, 8, '2009-06-21', '5780', '', 19.05, 0, 0, 3, 0x32),
(45, 26, 4, '2009-06-21', '1510', '', -40, 0, 0, NULL, ''),
(46, 26, 4, '2009-06-21', '1510', '', -44, 0, 0, NULL, ''),
(47, 26, 4, '2009-06-21', '1510', '', -48, 0, 0, NULL, ''),
(48, 26, 4, '2009-06-21', '1540', '', 132, 0, 0, NULL, ''),
(49, 26, 2, '2009-06-21', '1510', '', -20, 0, 0, NULL, ''),
(50, 26, 2, '2009-06-21', '1510', '', -22, 0, 0, NULL, ''),
(51, 26, 2, '2009-06-21', '1510', '', -24, 0, 0, NULL, ''),
(52, 26, 2, '2009-06-21', '1540', '', 66, 0, 0, NULL, ''),
(53, 26, 5, '2009-06-21', '1510', '', -50, 0, 0, NULL, ''),
(54, 26, 5, '2009-06-21', '1510', '', -55, 0, 0, NULL, ''),
(55, 26, 5, '2009-06-21', '1510', '', -60, 0, 0, NULL, ''),
(56, 26, 5, '2009-06-21', '1060', 'Overhead Cost', -10, 0, 0, 1, 0x31),
(57, 26, 5, '2009-06-21', '1530', 'Overhead Cost', 10, 0, 0, 1, 0x31),
(58, 26, 5, '2009-06-21', '1060', 'Labour Cost', -20, 0, 0, 1, 0x30),
(59, 26, 5, '2009-06-21', '1530', 'Labour Cost', 20, 0, 0, 1, 0x30),
(60, 26, 5, '2009-06-21', '1540', '', 165, 0, 0, NULL, ''),
(61, 26, 6, '2009-06-21', '1510', '', 50, 0, 0, NULL, ''),
(62, 26, 6, '2009-06-21', '1510', '', 55, 0, 0, NULL, ''),
(63, 26, 6, '2009-06-21', '1510', '', 60, 0, 0, NULL, ''),
(64, 26, 6, '2009-06-21', '1540', '', -165, 0, 0, NULL, ''),
(65, 26, 7, '2009-06-21', '1510', '', 20, 0, 0, NULL, ''),
(66, 26, 7, '2009-06-21', '1510', '', 22, 0, 0, NULL, ''),
(67, 26, 7, '2009-06-21', '1510', '', 24, 0, 0, NULL, ''),
(68, 26, 7, '2009-06-21', '1060', 'Overhead Cost', -10, 0, 0, 1, 0x31),
(69, 26, 7, '2009-06-21', '1530', 'Overhead Cost', 10, 0, 0, 1, 0x31),
(70, 26, 7, '2009-06-21', '1060', 'Labour Cost', -20, 0, 0, 1, 0x30),
(71, 26, 7, '2009-06-21', '1530', 'Labour Cost', 20, 0, 0, 1, 0x30),
(72, 26, 7, '2009-06-21', '1540', '', -66, 0, 0, NULL, ''),
(73, 13, 5, '2009-06-21', '5010', '', 10, 0, 0, 2, 0x32),
(74, 13, 5, '2009-06-21', '1510', '', -10, 0, 0, 2, 0x32),
(75, 10, 19, '2009-06-21', '4010', '', -47.62, 0, 0, 2, 0x32),
(76, 10, 19, '2009-06-21', '1200', '', 55, 0, 0, 2, 0x32),
(77, 10, 19, '2009-06-21', '4430', '', -5, 0, 0, 2, 0x32),
(78, 10, 19, '2009-06-21', '2150', '', -2.38, 0, 0, 2, 0x32),
(79, 11, 3, '2009-06-21', '5010', '', -10, 2, 0, 2, 0x33),
(80, 11, 3, '2009-06-21', '1510', '', 10, 0, 0, 2, 0x33),
(81, 11, 3, '2009-06-21', '4010', '', 50, 2, 0, 2, 0x33),
(82, 11, 3, '2009-06-21', '1200', '', -52.5, 0, 0, 2, 0x33),
(83, 11, 3, '2009-06-21', '2150', '', 2.5, 0, 0, 2, 0x33),
(84, 0, 20, '2009-12-31', '3590', 'Closing Year', -91.19, 0, 0, NULL, NULL),
(85, 0, 20, '2009-12-31', '9990', 'Closing Year', 91.19, 0, 0, NULL, NULL),
(86, 13, 6, '2014-06-12', '5010', '', 10, 0, 0, 2, 0x34),
(87, 13, 6, '2014-06-12', '1510', '', -10, 0, 0, 2, 0x34),
(88, 10, 20, '2014-06-12', '4010', '', -47.62, 0, 0, 2, 0x34),
(89, 10, 20, '2014-06-12', '1200', '', 50, 0, 0, 2, 0x34),
(90, 10, 20, '2014-06-12', '2150', '', -2.38, 0, 0, 2, 0x34),
(91, 12, 1, '2014-06-12', '1065', '', 50, 0, 0, 2, 0x34),
(92, 12, 1, '2014-06-12', '1200', '', -50, 0, 0, 2, 0x34),
(93, 13, 7, '2014-06-12', '5010', '', 40, 0, 0, 2, 0x34),
(94, 13, 7, '2014-06-12', '1510', '', -40, 0, 0, 2, 0x34),
(95, 10, 21, '2014-06-12', '4010', '', -190.48, 0, 0, 2, 0x34),
(96, 10, 21, '2014-06-12', '1200', '', 200, 0, 0, 2, 0x34),
(97, 10, 21, '2014-06-12', '2150', '', -9.52, 0, 0, 2, 0x34),
(98, 12, 2, '2014-06-12', '1065', '', 200, 0, 0, 2, 0x34),
(99, 12, 2, '2014-06-12', '1200', '', -200, 0, 0, 2, 0x34),
(100, 13, 8, '2014-06-12', '5010', '', 40, 0, 0, 2, 0x34),
(101, 13, 8, '2014-06-12', '1510', '', -40, 0, 0, 2, 0x34),
(102, 10, 22, '2014-06-12', '4010', '', -190.48, 0, 0, 2, 0x34),
(103, 10, 22, '2014-06-12', '1200', '', 200, 0, 0, 2, 0x34),
(104, 10, 22, '2014-06-12', '2150', '', -9.52, 0, 0, 2, 0x34),
(105, 12, 3, '2014-06-12', '1065', '', 200, 0, 0, 2, 0x34),
(106, 12, 3, '2014-06-12', '1200', '', -200, 0, 0, 2, 0x34);

-- --------------------------------------------------------

--
-- Table structure for table `grn_batch`
--

CREATE TABLE IF NOT EXISTS `grn_batch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL DEFAULT '0',
  `purch_order_no` int(11) DEFAULT NULL,
  `reference` varchar(60) NOT NULL DEFAULT '',
  `delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `loc_code` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `delivery_date` (`delivery_date`),
  KEY `purch_order_no` (`purch_order_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `grn_batch`
--

INSERT INTO `grn_batch` (`id`, `supplier_id`, `purch_order_no`, `reference`, `delivery_date`, `loc_code`) VALUES
(1, 2, 1, '1', '2009-06-21', 'DEF');

-- --------------------------------------------------------

--
-- Table structure for table `grn_items`
--

CREATE TABLE IF NOT EXISTS `grn_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grn_batch_id` int(11) DEFAULT NULL,
  `po_detail_item` int(11) NOT NULL DEFAULT '0',
  `item_code` varchar(20) NOT NULL DEFAULT '',
  `description` tinytext,
  `qty_recd` double NOT NULL DEFAULT '0',
  `quantity_inv` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `grn_batch_id` (`grn_batch_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `grn_items`
--

INSERT INTO `grn_items` (`id`, `grn_batch_id`, `po_detail_item`, `item_code`, `description`, `qty_recd`, `quantity_inv`) VALUES
(1, 1, 1, '102', '17inch VGA Monitor', 100, 100),
(2, 1, 2, '103', '32MB VGA Card', 100, 100),
(3, 1, 3, '104', '52x CD Drive', 100, 100);

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `description` (`description`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `description`, `inactive`) VALUES
(1, 'Small', 0),
(2, 'Medium', 0),
(3, 'Large', 0);

-- --------------------------------------------------------

--
-- Table structure for table `item_codes`
--

CREATE TABLE IF NOT EXISTS `item_codes` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `item_code` varchar(20) NOT NULL,
  `stock_id` varchar(20) NOT NULL,
  `description` varchar(200) NOT NULL DEFAULT '',
  `category_id` smallint(6) unsigned NOT NULL,
  `quantity` double NOT NULL DEFAULT '1',
  `is_foreign` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `stock_id` (`stock_id`,`item_code`),
  KEY `item_code` (`item_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `item_codes`
--

INSERT INTO `item_codes` (`id`, `item_code`, `stock_id`, `description`, `category_id`, `quantity`, `is_foreign`, `inactive`) VALUES
(1, '102', '102', '17inch VGA Monitor', 1, 1, 0, 0),
(2, '103', '103', '32MB VGA Card', 1, 1, 0, 0),
(3, '104', '104', '52x CD Drive', 1, 1, 0, 0),
(4, '3400', '3400', 'P4 Business System', 3, 1, 0, 0),
(5, '201', '201', 'Assembly Labour', 4, 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `item_tax_types`
--

CREATE TABLE IF NOT EXISTS `item_tax_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '',
  `exempt` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `item_tax_types`
--

INSERT INTO `item_tax_types` (`id`, `name`, `exempt`, `inactive`) VALUES
(1, 'Regular', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `item_tax_type_exemptions`
--

CREATE TABLE IF NOT EXISTS `item_tax_type_exemptions` (
  `item_tax_type_id` int(11) NOT NULL DEFAULT '0',
  `tax_type_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_tax_type_id`,`tax_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item_units`
--

CREATE TABLE IF NOT EXISTS `item_units` (
  `abbr` varchar(20) NOT NULL,
  `name` varchar(40) NOT NULL,
  `decimals` tinyint(2) NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`abbr`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item_units`
--

INSERT INTO `item_units` (`abbr`, `name`, `decimals`, `inactive`) VALUES
('ea.', 'Each', 0, 0),
('hrs', 'Hours', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `loading_types`
--

CREATE TABLE IF NOT EXISTS `loading_types` (
  `loading_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `loading_type_name` varchar(255) NOT NULL,
  PRIMARY KEY (`loading_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `loading_types`
--

INSERT INTO `loading_types` (`loading_type_id`, `loading_type_name`) VALUES
(1, 'PICK-UP'),
(2, 'DELIVER');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE IF NOT EXISTS `locations` (
  `loc_code` varchar(5) NOT NULL DEFAULT '',
  `location_name` varchar(60) NOT NULL DEFAULT '',
  `delivery_address` tinytext NOT NULL,
  `phone` varchar(30) NOT NULL DEFAULT '',
  `phone2` varchar(30) NOT NULL DEFAULT '',
  `fax` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `contact` varchar(30) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`loc_code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`loc_code`, `location_name`, `delivery_address`, `phone`, `phone2`, `fax`, `email`, `contact`, `inactive`) VALUES
('DEF', 'Default', 'Delivery 1\nDelivery 2\nDelivery 3', '', '', '', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `loc_stock`
--

CREATE TABLE IF NOT EXISTS `loc_stock` (
  `loc_code` char(5) NOT NULL DEFAULT '',
  `stock_id` char(20) NOT NULL DEFAULT '',
  `reorder_level` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`loc_code`,`stock_id`),
  KEY `stock_id` (`stock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `loc_stock`
--

INSERT INTO `loc_stock` (`loc_code`, `stock_id`, `reorder_level`) VALUES
('DEF', '102', 0),
('DEF', '103', 0),
('DEF', '104', 0),
('DEF', '201', 0),
('DEF', '3400', 0);

-- --------------------------------------------------------

--
-- Table structure for table `movement_types`
--

CREATE TABLE IF NOT EXISTS `movement_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `movement_types`
--

INSERT INTO `movement_types` (`id`, `name`, `inactive`) VALUES
(1, 'Adjustment', 0);

-- --------------------------------------------------------

--
-- Table structure for table `payment_terms`
--

CREATE TABLE IF NOT EXISTS `payment_terms` (
  `terms_indicator` int(11) NOT NULL AUTO_INCREMENT,
  `terms` char(80) NOT NULL DEFAULT '',
  `days_before_due` smallint(6) NOT NULL DEFAULT '0',
  `day_in_following_month` smallint(6) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`terms_indicator`),
  UNIQUE KEY `terms` (`terms`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `payment_terms`
--

INSERT INTO `payment_terms` (`terms_indicator`, `terms`, `days_before_due`, `day_in_following_month`, `inactive`) VALUES
(1, 'Due 15th Of the Following Month', 0, 17, 0),
(2, 'Due By End Of The Following Month', 0, 30, 0),
(3, 'Payment due within 10 days', 10, 0, 0),
(4, 'Cash Only', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `prices`
--

CREATE TABLE IF NOT EXISTS `prices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_id` varchar(20) NOT NULL DEFAULT '',
  `sales_type_id` int(11) NOT NULL DEFAULT '0',
  `curr_abrev` char(3) NOT NULL DEFAULT '',
  `price` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `price` (`stock_id`,`sales_type_id`,`curr_abrev`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `prices`
--

INSERT INTO `prices` (`id`, `stock_id`, `sales_type_id`, `curr_abrev`, `price`) VALUES
(1, '3400', 1, 'USD', 100),
(2, '102', 1, 'USD', 50),
(3, '103', 1, 'USD', 55),
(4, '104', 1, 'USD', 60);

-- --------------------------------------------------------

--
-- Table structure for table `printers`
--

CREATE TABLE IF NOT EXISTS `printers` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(60) NOT NULL,
  `queue` varchar(20) NOT NULL,
  `host` varchar(40) NOT NULL,
  `port` smallint(11) unsigned NOT NULL,
  `timeout` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `printers`
--

INSERT INTO `printers` (`id`, `name`, `description`, `queue`, `host`, `port`, `timeout`) VALUES
(1, 'QL500', 'Label printer', 'QL500', 'server', 127, 20),
(2, 'Samsung', 'Main network printer', 'scx4521F', 'server', 515, 5),
(3, 'Local', 'Local print server at user IP', 'lp', '', 515, 10);

-- --------------------------------------------------------

--
-- Table structure for table `print_profiles`
--

CREATE TABLE IF NOT EXISTS `print_profiles` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `profile` varchar(30) NOT NULL,
  `report` varchar(5) DEFAULT NULL,
  `printer` tinyint(3) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `profile` (`profile`,`report`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `print_profiles`
--

INSERT INTO `print_profiles` (`id`, `profile`, `report`, `printer`) VALUES
(1, 'Out of office', '', 0),
(2, 'Sales Department', '', 0),
(3, 'Central', '', 2),
(4, 'Sales Department', '104', 2),
(5, 'Sales Department', '105', 2),
(6, 'Sales Department', '107', 2),
(7, 'Sales Department', '109', 2),
(8, 'Sales Department', '110', 2),
(9, 'Sales Department', '201', 2);

-- --------------------------------------------------------

--
-- Table structure for table `purch_data`
--

CREATE TABLE IF NOT EXISTS `purch_data` (
  `supplier_id` int(11) NOT NULL DEFAULT '0',
  `stock_id` char(20) NOT NULL DEFAULT '',
  `price` double NOT NULL DEFAULT '0',
  `suppliers_uom` char(50) NOT NULL DEFAULT '',
  `conversion_factor` double NOT NULL DEFAULT '1',
  `supplier_description` char(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`supplier_id`,`stock_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `purch_data`
--

INSERT INTO `purch_data` (`supplier_id`, `stock_id`, `price`, `suppliers_uom`, `conversion_factor`, `supplier_description`) VALUES
(2, '102', 10, '', 1, '17inch VGA Monitor'),
(2, '103', 11, '', 1, '32MB VGA Card'),
(2, '104', 12, '', 1, '52x CD Drive');

-- --------------------------------------------------------

--
-- Table structure for table `purch_orders`
--

CREATE TABLE IF NOT EXISTS `purch_orders` (
  `order_no` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) NOT NULL DEFAULT '0',
  `comments` tinytext,
  `ord_date` date NOT NULL DEFAULT '0000-00-00',
  `reference` tinytext NOT NULL,
  `requisition_no` tinytext,
  `into_stock_location` varchar(5) NOT NULL DEFAULT '',
  `delivery_address` tinytext NOT NULL,
  `total` double NOT NULL DEFAULT '0',
  `tax_included` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`order_no`),
  KEY `ord_date` (`ord_date`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `purch_orders`
--

INSERT INTO `purch_orders` (`order_no`, `supplier_id`, `comments`, `ord_date`, `reference`, `requisition_no`, `into_stock_location`, `delivery_address`, `total`, `tax_included`) VALUES
(1, 2, '', '2009-06-01', '1', '', 'DEF', 'Delivery 1\nDelivery 2\nDelivery 3', 0, 0),
(2, 3, '', '2009-06-21', '2', '', 'DEF', 'Delivery 1\nDelivery 2\nDelivery 3', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `purch_order_details`
--

CREATE TABLE IF NOT EXISTS `purch_order_details` (
  `po_detail_item` int(11) NOT NULL AUTO_INCREMENT,
  `order_no` int(11) NOT NULL DEFAULT '0',
  `item_code` varchar(20) NOT NULL DEFAULT '',
  `description` tinytext,
  `delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `qty_invoiced` double NOT NULL DEFAULT '0',
  `unit_price` double NOT NULL DEFAULT '0',
  `act_price` double NOT NULL DEFAULT '0',
  `std_cost_unit` double NOT NULL DEFAULT '0',
  `quantity_ordered` double NOT NULL DEFAULT '0',
  `quantity_received` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`po_detail_item`),
  KEY `order` (`order_no`,`po_detail_item`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `purch_order_details`
--

INSERT INTO `purch_order_details` (`po_detail_item`, `order_no`, `item_code`, `description`, `delivery_date`, `qty_invoiced`, `unit_price`, `act_price`, `std_cost_unit`, `quantity_ordered`, `quantity_received`) VALUES
(1, 1, '102', '17inch VGA Monitor', '2009-07-01', 100, 10, 10, 10, 100, 100),
(2, 1, '103', '32MB VGA Card', '2009-07-01', 100, 11, 11, 11, 100, 100),
(3, 1, '104', '52x CD Drive', '2009-07-01', 100, 12, 12, 12, 100, 100),
(4, 2, '102', '17inch VGA Monitor', '2009-07-01', 0, 5, 0, 0, 1, 0),
(5, 2, '103', '32MB VGA Card', '2009-07-01', 0, 5, 0, 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `quick_entries`
--

CREATE TABLE IF NOT EXISTS `quick_entries` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `description` varchar(60) NOT NULL,
  `base_amount` double NOT NULL DEFAULT '0',
  `base_desc` varchar(60) DEFAULT NULL,
  `bal_type` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `description` (`description`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `quick_entries`
--

INSERT INTO `quick_entries` (`id`, `type`, `description`, `base_amount`, `base_desc`, `bal_type`) VALUES
(1, 1, 'Maintenance', 0, 'Amount', 0),
(2, 4, 'Phone', 0, 'Amount', 0),
(3, 2, 'Cash Sales', 0, 'Amount', 0);

-- --------------------------------------------------------

--
-- Table structure for table `quick_entry_lines`
--

CREATE TABLE IF NOT EXISTS `quick_entry_lines` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `qid` smallint(6) unsigned NOT NULL,
  `amount` double DEFAULT '0',
  `action` varchar(2) NOT NULL,
  `dest_id` varchar(15) NOT NULL DEFAULT '',
  `dimension_id` smallint(6) unsigned DEFAULT NULL,
  `dimension2_id` smallint(6) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `qid` (`qid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `quick_entry_lines`
--

INSERT INTO `quick_entry_lines` (`id`, `qid`, `amount`, `action`, `dest_id`, `dimension_id`, `dimension2_id`) VALUES
(1, 1, 0, 't-', '1', 0, 0),
(2, 2, 0, 't-', '1', 0, 0),
(3, 3, 0, 't-', '1', 0, 0),
(4, 3, 0, '=', '4010', 0, 0),
(5, 1, 0, '=', '5765', 0, 0),
(6, 2, 0, '=', '5780', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `recurrent_invoices`
--

CREATE TABLE IF NOT EXISTS `recurrent_invoices` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL DEFAULT '',
  `order_no` int(11) unsigned NOT NULL,
  `debtor_no` int(11) unsigned DEFAULT NULL,
  `group_no` smallint(6) unsigned DEFAULT NULL,
  `days` int(11) NOT NULL DEFAULT '0',
  `monthly` int(11) NOT NULL DEFAULT '0',
  `begin` date NOT NULL DEFAULT '0000-00-00',
  `end` date NOT NULL DEFAULT '0000-00-00',
  `last_sent` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `description` (`description`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `refs`
--

CREATE TABLE IF NOT EXISTS `refs` (
  `id` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `reference` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`,`type`),
  KEY `Type_and_Reference` (`type`,`reference`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `refs`
--

INSERT INTO `refs` (`id`, `type`, `reference`) VALUES
(18, 0, '1'),
(19, 0, '2'),
(20, 10, '4'),
(21, 10, '5'),
(22, 10, '6'),
(1, 12, '1'),
(2, 12, '2'),
(3, 12, '3'),
(6, 13, 'auto'),
(7, 13, 'auto'),
(8, 13, 'auto'),
(10, 30, '10'),
(6, 30, '6'),
(7, 30, '7'),
(8, 30, '8'),
(9, 30, '9'),
(11, 30, 'auto'),
(12, 30, 'auto'),
(13, 30, 'auto'),
(1, 32, '1'),
(2, 32, '2'),
(3, 32, '3'),
(4, 32, '4'),
(5, 32, '5'),
(6, 32, '6'),
(7, 32, '7'),
(8, 32, '8');

-- --------------------------------------------------------

--
-- Table structure for table `salesman`
--

CREATE TABLE IF NOT EXISTS `salesman` (
  `salesman_code` int(11) NOT NULL AUTO_INCREMENT,
  `salesman_name` char(60) NOT NULL DEFAULT '',
  `salesman_phone` char(30) NOT NULL DEFAULT '',
  `salesman_fax` char(30) NOT NULL DEFAULT '',
  `salesman_email` varchar(100) NOT NULL DEFAULT '',
  `provision` double NOT NULL DEFAULT '0',
  `break_pt` double NOT NULL DEFAULT '0',
  `provision2` double NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`salesman_code`),
  UNIQUE KEY `salesman_name` (`salesman_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `salesman`
--

INSERT INTO `salesman` (`salesman_code`, `salesman_name`, `salesman_phone`, `salesman_fax`, `salesman_email`, `provision`, `break_pt`, `provision2`, `inactive`) VALUES
(1, 'Sales Person', '', '', '', 5, 1000, 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sales_orders`
--

CREATE TABLE IF NOT EXISTS `sales_orders` (
  `order_no` int(11) NOT NULL,
  `trans_type` smallint(6) NOT NULL DEFAULT '30',
  `version` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `debtor_no` int(11) NOT NULL DEFAULT '0',
  `branch_code` int(11) NOT NULL DEFAULT '0',
  `reference` varchar(100) NOT NULL DEFAULT '',
  `customer_ref` tinytext NOT NULL,
  `comments` tinytext,
  `ord_date` date NOT NULL DEFAULT '0000-00-00',
  `order_type` int(11) NOT NULL DEFAULT '0',
  `ship_via` int(11) NOT NULL DEFAULT '0',
  `delivery_address` tinytext NOT NULL,
  `contact_phone` varchar(30) DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `deliver_to` tinytext NOT NULL,
  `freight_cost` double NOT NULL DEFAULT '0',
  `from_stk_loc` varchar(5) NOT NULL DEFAULT '',
  `delivery_date` date NOT NULL DEFAULT '0000-00-00',
  `payment_terms` int(11) DEFAULT NULL,
  `total` double NOT NULL DEFAULT '0',
  `contract_no` varchar(255) NOT NULL,
  `loading_type_id` int(11) NOT NULL,
  `origin` varchar(255) NOT NULL,
  `lc_no` varchar(255) NOT NULL,
  `lc_date` date NOT NULL,
  `bank_account_id` int(11) NOT NULL,
  `shipment_terms` text NOT NULL,
  `shipment_validity_date` date NOT NULL,
  `port_of_loading` varchar(255) NOT NULL,
  `port_of_discharge` varchar(255) NOT NULL,
  `destination` varchar(255) NOT NULL,
  `remarks` text NOT NULL,
  `shipment_time_id` int(11) NOT NULL,
  `shipping_id` int(11) NOT NULL,
  `export` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 for export sale,0 for local sale',
  PRIMARY KEY (`trans_type`,`order_no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sales_orders`
--

INSERT INTO `sales_orders` (`order_no`, `trans_type`, `version`, `type`, `debtor_no`, `branch_code`, `reference`, `customer_ref`, `comments`, `ord_date`, `order_type`, `ship_via`, `delivery_address`, `contact_phone`, `contact_email`, `deliver_to`, `freight_cost`, `from_stk_loc`, `delivery_date`, `payment_terms`, `total`, `contract_no`, `loading_type_id`, `origin`, `lc_no`, `lc_date`, `bank_account_id`, `shipment_terms`, `shipment_validity_date`, `port_of_loading`, `port_of_discharge`, `destination`, `remarks`, `shipment_time_id`, `shipping_id`, `export`) VALUES
(1, 30, 1, 0, 1, 1, '1', '', '', '2009-06-21', 2, 1, 'Address 1\nAddress 2\nAddress 3', '', '', 'Beefeater Ltd.', 10, 'DEF', '2009-06-22', 3, 0, '', 0, '', '', '0000-00-00', 0, '', '0000-00-00', '', '', '', '', 0, 0, 0),
(2, 30, 1, 0, 2, 2, '2', '', '', '2009-06-21', 1, 1, 'Address 1\nAddress 2\nAddress 3', '', '', 'Ghostbusters Corp.', 0, 'DEF', '2009-06-22', 4, 0, '', 0, '', '', '0000-00-00', 0, '', '0000-00-00', '', '', '', '', 0, 0, 0),
(3, 30, 1, 0, 3, 3, '3', '', '', '2009-06-21', 2, 1, 'Address 1\nAddress 2\nAddress 3', '', '', 'Brezan', 0, 'DEF', '2009-07-01', 3, 0, '', 0, '', '', '0000-00-00', 0, '', '0000-00-00', '', '', '', '', 0, 0, 0),
(4, 30, 0, 0, 1, 1, '4', '', '', '2009-06-21', 2, 1, 'Address 1\nAddress 2\nAddress 3', '', '', 'Beefeater Ltd.', 0, 'DEF', '2009-06-22', 3, 0, '', 0, '', '', '0000-00-00', 0, '', '0000-00-00', '', '', '', '', 0, 0, 0),
(5, 30, 1, 0, 2, 2, '5', '', '', '2009-06-21', 1, 1, 'Address 1\nAddress 2\nAddress 3', '', '', 'Ghostbusters Corp.', 5, 'DEF', '2009-06-22', 4, 0, '', 0, '', '', '0000-00-00', 0, '', '0000-00-00', '', '', '', '', 0, 0, 0),
(6, 30, 0, 0, 1, 1, '6', '', '', '2013-12-31', 2, 1, 'Address 1\nAddress 2\nAddress 3', '', NULL, 'Beefeater Ltd.', 0, 'DEF', '2014-01-01', 3, 68.1, '', 0, '', '', '0000-00-00', 0, '', '0000-00-00', '', '', '', '', 0, 0, 0),
(7, 30, 0, 0, 4, 4, '7', '', '', '2013-12-31', 1, 1, 'ernakulam', '123456', NULL, 'Rosy Swapna', 0, 'DEF', '2014-01-01', 4, 50, '', 0, '', '', '0000-00-00', 0, '', '0000-00-00', '', '', '', '', 0, 0, 0),
(8, 30, 0, 0, 4, 4, '8', '', '', '2014-05-06', 1, 1, 'ernakulam', '123456', NULL, 'Rosy Swapna', 0, 'DEF', '2014-05-07', 4, 50, '', 0, '', '', '0000-00-00', 0, '', '0000-00-00', '', '', '', '', 0, 0, 0),
(9, 30, 0, 0, 4, 4, '9', '', '', '2014-05-07', 1, 1, 'ernakulam', '123456', NULL, 'Rosy Swapna', 0, 'DEF', '2014-05-08', 4, 50, '', 0, '', '', '0000-00-00', 0, '', '0000-00-00', '', '', '', '', 0, 0, 0),
(10, 30, 0, 0, 4, 4, '10', '', 'Sales Quotation # 4', '2014-05-07', 1, 1, 'ernakulam', '123456', NULL, 'Rosy Swapna', 0, 'DEF', '2014-05-08', 4, 0, '', 0, '', '', '0000-00-00', 0, '', '0000-00-00', '', '', '', '', 0, 0, 0),
(11, 30, 1, 0, 4, 4, 'auto', '', '', '2014-06-12', 1, 1, 'ernakulam', '123456', NULL, 'Rosy Swapna', 0, 'DEF', '2014-06-12', 4, 0, '', 0, '', '', '0000-00-00', 0, '', '0000-00-00', '', '', '', '', 0, 0, 0),
(12, 30, 1, 0, 4, 4, 'auto', '', '', '2014-06-12', 1, 1, 'ernakulam', '123456', NULL, 'Rosy Swapna', 0, 'DEF', '2014-06-12', 4, 0, 'cnt124344', 1, 'oringsdfsd', 'lc number', '2014-06-12', 1, 'sdf', '2014-06-13', 'portoifdsloadfd sdfsd', 'porto fddischarsdfds', 'destinationkasjlkdfj osdf', 'sdf', 1, 1, 0),
(13, 30, 1, 0, 4, 4, 'auto', '', '', '2014-06-12', 1, 1, 'ernakulam', '123456', NULL, 'Rosy Swapna', 0, 'DEF', '2014-06-12', 4, 0, '', 1, '', '', '2014-06-12', 1, '', '0000-00-00', '', '', '', '', 1, 1, 0),
(1, 32, 0, 0, 4, 4, '1', '', '', '2014-05-07', 1, 1, 'ernakulam', '123456', NULL, 'Rosy Swapna', 0, 'DEF', '2014-05-08', 4, 50, '', 0, '', '', '0000-00-00', 0, '', '0000-00-00', '', '', '', '', 0, 0, 0),
(2, 32, 0, 0, 4, 4, '2', '', '', '2014-05-07', 1, 1, 'ernakulam', '123456', NULL, 'Rosy Swapna', 0, 'DEF', '2014-05-08', 4, 50, '', 0, '', '', '0000-00-00', 0, '', '0000-00-00', '', '', '', '', 0, 0, 0),
(3, 32, 0, 0, 4, 4, '3', '', '', '2014-05-07', 1, 1, 'ernakulam', '123456', NULL, 'Rosy Swapna', 0, 'DEF', '2014-05-08', 4, 0, '', 0, '', '', '0000-00-00', 0, '', '0000-00-00', '', '', '', '', 0, 0, 0),
(4, 32, 1, 0, 4, 4, '4', '', '', '2014-05-07', 1, 1, 'ernakulam', '123456', NULL, 'Rosy Swapna', 0, 'DEF', '2014-05-08', 4, 250, '', 0, '', '', '0000-00-00', 0, '', '0000-00-00', '', '', '', '', 0, 0, 0),
(5, 32, 0, 0, 1, 1, '5', '', '', '2014-05-07', 2, 1, 'Address 1\r\nAddress 2\r\nAddress 3', '', NULL, 'Beefeater Ltd.', 0, 'DEF', '2014-05-08', 3, 0, '', 0, '', '', '0000-00-00', 0, '', '0000-00-00', '', '', '', '', 0, 0, 0),
(6, 32, 0, 0, 1, 1, '6', '', '', '2014-05-29', 2, 1, 'Address 1\nAddress 2\nAddress 3', '', NULL, 'Beefeater Ltd.', 0, 'DEF', '2014-05-30', 3, 0, '', 0, '', '', '0000-00-00', 0, '', '0000-00-00', '', '', '', '', 0, 0, 0),
(7, 32, 0, 0, 1, 1, '7', '', '', '2014-05-30', 2, 1, 'Address 1\nAddress 2\nAddress 3', '', NULL, 'Beefeater Ltd.', 0, 'DEF', '2014-05-31', 3, 0, '', 0, '', '', '0000-00-00', 0, '', '0000-00-00', '', '', '', '', 0, 0, 0),
(8, 32, 0, 0, 4, 4, '8', '', '', '2014-05-30', 1, 1, 'ernakulam', '123456', NULL, 'Rosy Swapna', 0, 'DEF', '2014-05-31', 4, 0, '', 0, '', '', '0000-00-00', 0, '', '0000-00-00', '', '', '', '', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sales_order_details`
--

CREATE TABLE IF NOT EXISTS `sales_order_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_no` int(11) NOT NULL DEFAULT '0',
  `trans_type` smallint(6) NOT NULL DEFAULT '30',
  `stk_code` varchar(20) NOT NULL DEFAULT '',
  `description` tinytext,
  `qty_sent` double NOT NULL DEFAULT '0',
  `unit_price` double NOT NULL DEFAULT '0',
  `quantity` double NOT NULL DEFAULT '0',
  `discount_percent` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sorder` (`trans_type`,`order_no`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `sales_order_details`
--

INSERT INTO `sales_order_details` (`id`, `order_no`, `trans_type`, `stk_code`, `description`, `qty_sent`, `unit_price`, `quantity`, `discount_percent`) VALUES
(1, 1, 30, '102', '17inch VGA Monitor', 2, 30.4, 2, 0),
(2, 2, 30, '102', '17inch VGA Monitor', 1, 50, 1, 0),
(3, 3, 30, '102', '17inch VGA Monitor', 1, 35.89, 1, 0),
(4, 4, 30, '102', '17inch VGA Monitor', 0, 21.28, 2, 0),
(5, 5, 30, '102', '17inch VGA Monitor', 1, 50, 1, 0),
(6, 6, 30, '102', '17inch VGA Monitor', 0, 21.28, 1, 0),
(7, 6, 30, '103', '32MB VGA Card', 0, 23.41, 2, 0),
(8, 7, 30, '102', '17inch VGA Monitor', 0, 50, 1, 0),
(9, 8, 30, '102', '17inch VGA Monitor', 0, 50, 1, 0),
(10, 9, 30, '102', '17inch VGA Monitor', 0, 50, 1, 0),
(11, 1, 32, '102', '17inch VGA Monitor', 0, 50, 1, 0),
(12, 2, 32, '102', '17inch VGA Monitor', 0, 50, 1, 0),
(13, 3, 32, '102', '17inch VGA Monitor', 0, 50, 1, 0),
(14, 4, 32, '102', '17inch VGA Monitor', 5, 50, 5, 0),
(15, 5, 32, '102', '17inch VGA Monitor', 0, 21.28, 1, 0),
(16, 10, 30, '102', '17inch VGA Monitor', 0, 50, 5, 0),
(17, 6, 32, '102', '17inch VGA Monitor', 0, 21.28, 1, 0),
(18, 7, 32, '102', '17inch VGA Monitor', 0, 21.28, 1, 0),
(19, 8, 32, '102', '17inch VGA Monitor', 0, 50, 1, 0),
(20, 11, 30, '102', '17inch VGA Monitor', 1, 50, 1, 0),
(21, 12, 30, '102', '17inch VGA Monitor', 4, 50, 4, 0),
(22, 13, 30, '102', '17inch VGA Monitor', 4, 50, 4, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sales_pos`
--

CREATE TABLE IF NOT EXISTS `sales_pos` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `pos_name` varchar(30) NOT NULL,
  `cash_sale` tinyint(1) NOT NULL,
  `credit_sale` tinyint(1) NOT NULL,
  `pos_location` varchar(5) NOT NULL,
  `pos_account` smallint(6) unsigned NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pos_name` (`pos_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `sales_pos`
--

INSERT INTO `sales_pos` (`id`, `pos_name`, `cash_sale`, `credit_sale`, `pos_location`, `pos_account`, `inactive`) VALUES
(1, 'Default', 1, 1, 'DEF', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sales_types`
--

CREATE TABLE IF NOT EXISTS `sales_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_type` char(50) NOT NULL DEFAULT '',
  `tax_included` int(1) NOT NULL DEFAULT '0',
  `factor` double NOT NULL DEFAULT '1',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sales_type` (`sales_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `sales_types`
--

INSERT INTO `sales_types` (`id`, `sales_type`, `tax_included`, `factor`, `inactive`) VALUES
(1, 'Retail', 1, 1, 0),
(2, 'Wholesale', 0, 0.7, 0);

-- --------------------------------------------------------

--
-- Table structure for table `security_roles`
--

CREATE TABLE IF NOT EXISTS `security_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role` varchar(30) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `sections` text,
  `areas` text,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `role` (`role`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `security_roles`
--

INSERT INTO `security_roles` (`id`, `role`, `description`, `sections`, `areas`, `inactive`) VALUES
(1, 'Inquiries', 'Inquiries', '768;2816;3072;3328;5632;5888;8192;8448;10752;11008;13312;15872;16128', '257;258;259;260;513;514;515;516;517;518;519;520;521;522;523;524;525;773;774;2822;3073;3075;3076;3077;3329;3330;3331;3332;3333;3334;3335;5377;5633;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8450;8451;10497;10753;11009;11010;11012;13313;13315;15617;15618;15619;15620;15621;15622;15623;15624;15625;15626;15873;15882;16129;16130;16131;16132', 0),
(2, 'System Administrator', 'System Administrator', '256;512;768;2816;3072;3328;5376;5632;5888;7936;8192;8448;15616;15872;16128;287744', '257;258;259;260;513;514;515;516;517;518;519;520;521;522;523;525;526;769;770;771;772;773;774;2817;2818;2819;2820;2821;2822;2823;3073;3074;3082;3075;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5634;5635;5636;5637;5641;5638;5639;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8195;8196;8197;8449;8450;8451;10497;10753;10754;10755;10756;10757;11009;11010;11011;11012;13057;13313;13314;13315;15617;15618;15619;15620;15621;15622;15623;15624;15628;15625;15626;15627;15873;15874;15875;15876;15877;15878;15879;15880;15883;15881;15882;16129;16130;16131;16132;287844;287845', 0),
(3, 'Salesman', 'Salesman', '768;3072;5632;8192;15872', '773;774;3073;3075;3081;5633;8194;15873', 0),
(4, 'Stock Manager', 'Stock Manager', '2816;3072;3328;5632;5888;8192;8448;10752;11008;13312;15872;16128', '2818;2822;3073;3076;3077;3329;3330;3330;3330;3331;3331;3332;3333;3334;3335;5633;5640;5889;5890;5891;8193;8194;8450;8451;10753;11009;11010;11012;13313;13315;15882;16129;16130;16131;16132', 0),
(5, 'Production Manager', 'Production Manager', '512;2816;3072;3328;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '521;523;524;2818;2819;2820;2821;2822;2823;3073;3074;3076;3077;3078;3079;3080;3081;3329;3330;3330;3330;3331;3331;3332;3333;3334;3335;5633;5640;5640;5889;5890;5891;8193;8194;8196;8197;8450;8451;10753;10755;11009;11010;11012;13313;13315;15617;15619;15620;15621;15624;15624;15876;15877;15880;15882;16129;16130;16131;16132', 0),
(6, 'Purchase Officer', 'Purchase Officer', '512;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '521;523;524;2818;2819;2820;2821;2822;2823;3073;3074;3076;3077;3078;3079;3080;3081;3329;3330;3330;3330;3331;3331;3332;3333;3334;3335;5377;5633;5635;5640;5640;5889;5890;5891;8193;8194;8196;8197;8449;8450;8451;10753;10755;11009;11010;11012;13313;13315;15617;15619;15620;15621;15624;15624;15876;15877;15880;15882;16129;16130;16131;16132', 0),
(7, 'AR Officer', 'AR Officer', '512;768;2816;3072;3328;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '521;523;524;771;773;774;2818;2819;2820;2821;2822;2823;3073;3073;3074;3075;3076;3077;3078;3079;3080;3081;3081;3329;3330;3330;3330;3331;3331;3332;3333;3334;3335;5633;5633;5634;5637;5638;5639;5640;5640;5889;5890;5891;8193;8194;8194;8196;8197;8450;8451;10753;10755;11009;11010;11012;13313;13315;15617;15619;15620;15621;15624;15624;15873;15876;15877;15878;15880;15882;16129;16130;16131;16132', 0),
(8, 'AP Officer', 'AP Officer', '512;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '257;258;259;260;521;523;524;769;770;771;772;773;774;2818;2819;2820;2821;2822;2823;3073;3074;3082;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5635;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8196;8197;8449;8450;8451;10497;10753;10755;11009;11010;11012;13057;13313;13315;15617;15619;15620;15621;15624;15876;15877;15880;15882;16129;16130;16131;16132', 0),
(9, 'Accountant', 'New Accountant', '512;768;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '257;258;259;260;521;523;524;771;772;773;774;2818;2819;2820;2821;2822;2823;3073;3074;3075;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5634;5635;5637;5638;5639;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8196;8197;8449;8450;8451;10497;10753;10755;11009;11010;11012;13313;13315;15617;15618;15619;15620;15621;15624;15873;15876;15877;15878;15880;15882;16129;16130;16131;16132', 0),
(10, 'Sub Admin', 'Sub Admin', '512;768;2816;3072;3328;5376;5632;5888;8192;8448;10752;11008;13312;15616;15872;16128', '257;258;259;260;521;523;524;771;772;773;774;2818;2819;2820;2821;2822;2823;3073;3074;3082;3075;3076;3077;3078;3079;3080;3081;3329;3330;3331;3332;3333;3334;3335;5377;5633;5634;5635;5637;5638;5639;5640;5889;5890;5891;7937;7938;7939;7940;8193;8194;8196;8197;8449;8450;8451;10497;10753;10755;11009;11010;11012;13057;13313;13315;15617;15619;15620;15621;15624;15873;15874;15876;15877;15878;15879;15880;15882;16129;16130;16131;16132', 0);

-- --------------------------------------------------------

--
-- Table structure for table `shipment_status`
--

CREATE TABLE IF NOT EXISTS `shipment_status` (
  `status_id` int(11) NOT NULL AUTO_INCREMENT,
  `status_description` varchar(255) NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `shipment_status`
--

INSERT INTO `shipment_status` (`status_id`, `status_description`) VALUES
(1, 'Open'),
(2, 'Close');

-- --------------------------------------------------------

--
-- Table structure for table `shipment_times`
--

CREATE TABLE IF NOT EXISTS `shipment_times` (
  `shipment_time_id` int(11) NOT NULL AUTO_INCREMENT,
  `shipment_time_name` varchar(255) NOT NULL,
  PRIMARY KEY (`shipment_time_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `shipment_times`
--

INSERT INTO `shipment_times` (`shipment_time_id`, `shipment_time_name`) VALUES
(1, 'IMMEDIATE');

-- --------------------------------------------------------

--
-- Table structure for table `shippers`
--

CREATE TABLE IF NOT EXISTS `shippers` (
  `shipper_id` int(11) NOT NULL AUTO_INCREMENT,
  `shipper_name` varchar(60) NOT NULL DEFAULT '',
  `phone` varchar(30) NOT NULL DEFAULT '',
  `phone2` varchar(30) NOT NULL DEFAULT '',
  `contact` tinytext NOT NULL,
  `address` tinytext NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`shipper_id`),
  UNIQUE KEY `name` (`shipper_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `shippers`
--

INSERT INTO `shippers` (`shipper_id`, `shipper_name`, `phone`, `phone2`, `contact`, `address`, `inactive`) VALUES
(1, 'Default', '', '', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `shipping_details`
--

CREATE TABLE IF NOT EXISTS `shipping_details` (
  `shipping_id` int(11) NOT NULL AUTO_INCREMENT,
  `debtor_no` int(11) NOT NULL,
  `vehicle_details` varchar(255) NOT NULL,
  `first_weight` float NOT NULL,
  `first_weight_date` datetime NOT NULL,
  `second_weight` float NOT NULL,
  `second_weight_date` datetime NOT NULL,
  `shipment_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1 for open, 2 for close',
  `container_no` varchar(255) NOT NULL,
  PRIMARY KEY (`shipping_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `shipping_details`
--

INSERT INTO `shipping_details` (`shipping_id`, `debtor_no`, `vehicle_details`, `first_weight`, `first_weight_date`, `second_weight`, `second_weight_date`, `shipment_status`, `container_no`) VALUES
(1, 1, 'kl1234', 1254, '0000-00-00 00:00:00', 1250, '0000-00-00 00:00:00', 2, 'container1'),
(2, 1, 'J 36853', 1000, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, ''),
(3, 1, 'GZI 112515 B', 124000, '0000-00-00 00:00:00', 0, '0000-00-00 00:00:00', 0, ''),
(4, 4, 'GZI 112515 B', 124000, '2014-06-02 00:00:00', 14500, '0000-00-00 00:00:00', 2, '');

-- --------------------------------------------------------

--
-- Table structure for table `sql_trail`
--

CREATE TABLE IF NOT EXISTS `sql_trail` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sql` text NOT NULL,
  `result` tinyint(1) NOT NULL,
  `msg` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stock_category`
--

CREATE TABLE IF NOT EXISTS `stock_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(60) NOT NULL DEFAULT '',
  `dflt_tax_type` int(11) NOT NULL DEFAULT '1',
  `dflt_units` varchar(20) NOT NULL DEFAULT 'each',
  `dflt_mb_flag` char(1) NOT NULL DEFAULT 'B',
  `dflt_sales_act` varchar(15) NOT NULL DEFAULT '',
  `dflt_cogs_act` varchar(15) NOT NULL DEFAULT '',
  `dflt_inventory_act` varchar(15) NOT NULL DEFAULT '',
  `dflt_adjustment_act` varchar(15) NOT NULL DEFAULT '',
  `dflt_assembly_act` varchar(15) NOT NULL DEFAULT '',
  `dflt_dim1` int(11) DEFAULT NULL,
  `dflt_dim2` int(11) DEFAULT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  `dflt_no_sale` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `description` (`description`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `stock_category`
--

INSERT INTO `stock_category` (`category_id`, `description`, `dflt_tax_type`, `dflt_units`, `dflt_mb_flag`, `dflt_sales_act`, `dflt_cogs_act`, `dflt_inventory_act`, `dflt_adjustment_act`, `dflt_assembly_act`, `dflt_dim1`, `dflt_dim2`, `inactive`, `dflt_no_sale`) VALUES
(1, 'Components', 1, 'each', 'B', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0),
(2, 'Charges', 1, 'each', 'D', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0),
(3, 'Systems', 1, 'each', 'M', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0),
(4, 'Services', 1, 'hrs', 'D', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `stock_master`
--

CREATE TABLE IF NOT EXISTS `stock_master` (
  `stock_id` varchar(20) NOT NULL DEFAULT '',
  `category_id` int(11) NOT NULL DEFAULT '0',
  `tax_type_id` int(11) NOT NULL DEFAULT '0',
  `description` varchar(200) NOT NULL DEFAULT '',
  `long_description` tinytext NOT NULL,
  `units` varchar(20) NOT NULL DEFAULT 'each',
  `mb_flag` char(1) NOT NULL DEFAULT 'B',
  `sales_account` varchar(15) NOT NULL DEFAULT '',
  `cogs_account` varchar(15) NOT NULL DEFAULT '',
  `inventory_account` varchar(15) NOT NULL DEFAULT '',
  `adjustment_account` varchar(15) NOT NULL DEFAULT '',
  `assembly_account` varchar(15) NOT NULL DEFAULT '',
  `dimension_id` int(11) DEFAULT NULL,
  `dimension2_id` int(11) DEFAULT NULL,
  `actual_cost` double NOT NULL DEFAULT '0',
  `last_cost` double NOT NULL DEFAULT '0',
  `material_cost` double NOT NULL DEFAULT '0',
  `labour_cost` double NOT NULL DEFAULT '0',
  `overhead_cost` double NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  `no_sale` tinyint(1) NOT NULL DEFAULT '0',
  `editable` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`stock_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `stock_master`
--

INSERT INTO `stock_master` (`stock_id`, `category_id`, `tax_type_id`, `description`, `long_description`, `units`, `mb_flag`, `sales_account`, `cogs_account`, `inventory_account`, `adjustment_account`, `assembly_account`, `dimension_id`, `dimension2_id`, `actual_cost`, `last_cost`, `material_cost`, `labour_cost`, `overhead_cost`, `inactive`, `no_sale`, `editable`) VALUES
('102', 1, 1, '17inch VGA Monitor', '', 'ea.', 'B', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0, 10, 0, 0, 0, 0, 0),
('103', 1, 1, '32MB VGA Card', '', 'ea.', 'B', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0, 11, 0, 0, 0, 0, 0),
('104', 1, 1, '52x CD Drive', '', 'ea.', 'B', '4010', '5010', '1510', '5040', '1530', 0, 0, 0, 0, 12, 0, 0, 0, 0, 0),
('201', 4, 1, 'Assembly Labour', '', 'hrs', 'D', '4010', '5010', '5010', '5040', '1530', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
('3400', 3, 1, 'P4 Business System', '', 'ea.', 'M', '4010', '5010', '1540', '5040', '1530', 0, 0, 0, 0, 33, 3.9999999999999, 2, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `stock_moves`
--

CREATE TABLE IF NOT EXISTS `stock_moves` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_no` int(11) NOT NULL DEFAULT '0',
  `stock_id` char(20) NOT NULL DEFAULT '',
  `type` smallint(6) NOT NULL DEFAULT '0',
  `loc_code` char(5) NOT NULL DEFAULT '',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `person_id` int(11) DEFAULT NULL,
  `price` double NOT NULL DEFAULT '0',
  `reference` char(40) NOT NULL DEFAULT '',
  `qty` double NOT NULL DEFAULT '1',
  `discount_percent` double NOT NULL DEFAULT '0',
  `standard_cost` double NOT NULL DEFAULT '0',
  `visible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`trans_id`),
  KEY `type` (`type`,`trans_no`),
  KEY `Move` (`stock_id`,`loc_code`,`tran_date`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `stock_moves`
--

INSERT INTO `stock_moves` (`trans_id`, `trans_no`, `stock_id`, `type`, `loc_code`, `tran_date`, `person_id`, `price`, `reference`, `qty`, `discount_percent`, `standard_cost`, `visible`) VALUES
(1, 1, '102', 25, 'DEF', '2009-06-21', 2, 10, '', 100, 0, 10, 1),
(2, 1, '103', 25, 'DEF', '2009-06-21', 2, 11, '', 100, 0, 11, 1),
(3, 1, '104', 25, 'DEF', '2009-06-21', 2, 12, '', 100, 0, 12, 1),
(4, 1, '102', 26, 'DEF', '2009-06-21', 0, 0, '1', -10, 0, 0, 1),
(5, 1, '103', 26, 'DEF', '2009-06-21', 0, 0, '1', -10, 0, 0, 1),
(6, 1, '104', 26, 'DEF', '2009-06-21', 0, 0, '1', -10, 0, 0, 1),
(7, 1, '3400', 26, 'DEF', '2009-06-21', 0, 0, '1', 10, 0, 0, 1),
(8, 2, '102', 13, 'DEF', '2009-06-21', 0, 30.4, '1', -2, 0, 10, 1),
(9, 3, '102', 13, 'DEF', '2009-06-21', 0, 50, 'auto', -1, 0, 10, 1),
(10, 4, '102', 13, 'DEF', '2009-06-21', 0, 35.89, 'auto', -1, 0, 10, 1),
(11, 3, '102', 26, 'DEF', '2009-06-21', 0, 0, '3', -2, 0, 0, 1),
(12, 3, '103', 26, 'DEF', '2009-06-21', 0, 0, '3', -2, 0, 0, 1),
(13, 3, '104', 26, 'DEF', '2009-06-21', 0, 0, '3', -2, 0, 0, 1),
(14, 3, '3400', 26, 'DEF', '2009-06-21', 0, 0, '3', 2, 0, 0, 1),
(15, 4, '102', 26, 'DEF', '2009-06-21', 0, 0, '4', -4, 0, 0, 1),
(16, 4, '103', 26, 'DEF', '2009-06-21', 0, 0, '4', -4, 0, 0, 1),
(17, 4, '104', 26, 'DEF', '2009-06-21', 0, 0, '4', -4, 0, 0, 1),
(18, 4, '3400', 26, 'DEF', '2009-06-21', 0, 0, '4', 4, 0, 0, 1),
(19, 2, '102', 26, 'DEF', '2009-06-21', 0, 0, '', -2, 0, 0, 1),
(20, 2, '103', 26, 'DEF', '2009-06-21', 0, 0, '', -2, 0, 0, 1),
(21, 2, '104', 26, 'DEF', '2009-06-21', 0, 0, '', -2, 0, 0, 1),
(22, 1, '3400', 29, 'DEF', '2009-06-21', 0, 0, '', 2, 0, 0, 1),
(23, 5, '102', 26, 'DEF', '2009-06-21', 0, 0, '5', -5, 0, 0, 1),
(24, 5, '103', 26, 'DEF', '2009-06-21', 0, 0, '5', -5, 0, 0, 1),
(25, 5, '104', 26, 'DEF', '2009-06-21', 0, 0, '5', -5, 0, 0, 1),
(26, 5, '3400', 26, 'DEF', '2009-06-21', 0, 0, '5', 5, 0, 0, 1),
(27, 6, '102', 26, 'DEF', '2009-06-21', 0, 0, '6', 5, 0, 0, 1),
(28, 6, '103', 26, 'DEF', '2009-06-21', 0, 0, '6', 5, 0, 0, 1),
(29, 6, '104', 26, 'DEF', '2009-06-21', 0, 0, '6', 5, 0, 0, 1),
(30, 6, '3400', 26, 'DEF', '2009-06-21', 0, 0, '6', -5, 0, 0, 1),
(31, 7, '102', 26, 'DEF', '2009-06-21', 0, 0, '7', 2, 0, 0, 1),
(32, 7, '103', 26, 'DEF', '2009-06-21', 0, 0, '7', 2, 0, 0, 1),
(33, 7, '104', 26, 'DEF', '2009-06-21', 0, 0, '7', 2, 0, 0, 1),
(34, 7, '3400', 26, 'DEF', '2009-06-21', 0, 0, '7', -2, 0, 0, 1),
(35, 5, '102', 13, 'DEF', '2009-06-21', 0, 50, 'auto', -1, 0, 10, 1),
(36, 3, '102', 11, 'DEF', '2009-06-21', 0, 37.68, 'Return Ex Inv: 18', 1, 0, 10, 0),
(37, 6, '102', 13, 'DEF', '2014-06-12', 0, 50, 'auto', -1, 0, 10, 1),
(38, 7, '102', 13, 'DEF', '2014-06-12', 0, 50, 'auto', -4, 0, 10, 1),
(39, 8, '102', 13, 'DEF', '2014-06-12', 0, 50, 'auto', -4, 0, 10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE IF NOT EXISTS `suppliers` (
  `supplier_id` int(11) NOT NULL AUTO_INCREMENT,
  `supp_name` varchar(60) NOT NULL DEFAULT '',
  `supp_ref` varchar(30) NOT NULL DEFAULT '',
  `address` tinytext NOT NULL,
  `supp_address` tinytext NOT NULL,
  `gst_no` varchar(25) NOT NULL DEFAULT '',
  `contact` varchar(60) NOT NULL DEFAULT '',
  `supp_account_no` varchar(40) NOT NULL DEFAULT '',
  `website` varchar(100) NOT NULL DEFAULT '',
  `bank_account` varchar(60) NOT NULL DEFAULT '',
  `curr_code` char(3) DEFAULT NULL,
  `payment_terms` int(11) DEFAULT NULL,
  `tax_included` tinyint(1) NOT NULL DEFAULT '0',
  `dimension_id` int(11) DEFAULT '0',
  `dimension2_id` int(11) DEFAULT '0',
  `tax_group_id` int(11) DEFAULT NULL,
  `credit_limit` double NOT NULL DEFAULT '0',
  `purchase_account` varchar(15) NOT NULL DEFAULT '',
  `payable_account` varchar(15) NOT NULL DEFAULT '',
  `payment_discount_account` varchar(15) NOT NULL DEFAULT '',
  `notes` tinytext NOT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`supplier_id`),
  KEY `supp_ref` (`supp_ref`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `supp_name`, `supp_ref`, `address`, `supp_address`, `gst_no`, `contact`, `supp_account_no`, `website`, `bank_account`, `curr_code`, `payment_terms`, `tax_included`, `dimension_id`, `dimension2_id`, `tax_group_id`, `credit_limit`, `purchase_account`, `payable_account`, `payment_discount_account`, `notes`, `inactive`) VALUES
(1, 'Junk Beer ApS', 'Junk Beer', 'Mailing 1\nMailing 2\nMailing 3', 'Address 1\nAddress 2\nAddress 3', '123456', 'Contact', '111', '', '', 'DKK', 3, 0, 1, 0, 2, 1000, '', '2100', '5060', 'A supplier with junk beers.', 0),
(2, 'Lucky Luke Inc.', 'Lucky Luke', 'Mailing 1\nMailing 2\nMailing 3', 'Address 1\nAddress 2\nAddress 3', '654321', 'Luke', '333', '', '', 'USD', 3, 0, 0, 0, 1, 500, '', '2100', '5060', '', 0),
(3, 'Money Makers Ltd.', 'Money Makers', 'Mailing 1\nMailing 2\nMailing 3', 'Address 1\nAddress 2\nAddress 3', '987654', 'Makers', '222', '', '', 'GBP', 3, 0, 0, 0, 2, 300, '', '2100', '5060', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `supp_allocations`
--

CREATE TABLE IF NOT EXISTS `supp_allocations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amt` double unsigned DEFAULT NULL,
  `date_alloc` date NOT NULL DEFAULT '0000-00-00',
  `trans_no_from` int(11) DEFAULT NULL,
  `trans_type_from` int(11) DEFAULT NULL,
  `trans_no_to` int(11) DEFAULT NULL,
  `trans_type_to` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `From` (`trans_type_from`,`trans_no_from`),
  KEY `To` (`trans_type_to`,`trans_no_to`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `supp_allocations`
--

INSERT INTO `supp_allocations` (`id`, `amt`, `date_alloc`, `trans_no_from`, `trans_type_from`, `trans_no_to`, `trans_type_to`) VALUES
(1, 3465, '2009-06-21', 4, 22, 7, 20);

-- --------------------------------------------------------

--
-- Table structure for table `supp_invoice_items`
--

CREATE TABLE IF NOT EXISTS `supp_invoice_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `supp_trans_no` int(11) DEFAULT NULL,
  `supp_trans_type` int(11) DEFAULT NULL,
  `gl_code` varchar(15) NOT NULL DEFAULT '',
  `grn_item_id` int(11) DEFAULT NULL,
  `po_detail_item_id` int(11) DEFAULT NULL,
  `stock_id` varchar(20) NOT NULL DEFAULT '',
  `description` tinytext,
  `quantity` double NOT NULL DEFAULT '0',
  `unit_price` double NOT NULL DEFAULT '0',
  `unit_tax` double NOT NULL DEFAULT '0',
  `memo_` tinytext,
  PRIMARY KEY (`id`),
  KEY `Transaction` (`supp_trans_type`,`supp_trans_no`,`stock_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `supp_invoice_items`
--

INSERT INTO `supp_invoice_items` (`id`, `supp_trans_no`, `supp_trans_type`, `gl_code`, `grn_item_id`, `po_detail_item_id`, `stock_id`, `description`, `quantity`, `unit_price`, `unit_tax`, `memo_`) VALUES
(1, 7, 20, '0', 1, 1, '102', '17inch VGA Monitor', 100, 10, 0.5, ''),
(2, 7, 20, '0', 2, 2, '103', '32MB VGA Card', 100, 11, 0.55, ''),
(3, 7, 20, '0', 3, 3, '104', '52x CD Drive', 100, 12, 0.6, ''),
(4, 8, 20, '2150', 0, 0, '', '', 0, 0.95, 0, 'Phone'),
(5, 8, 20, '5780', 0, 0, '', '', 0, 19.05, 0, 'Phone');

-- --------------------------------------------------------

--
-- Table structure for table `supp_trans`
--

CREATE TABLE IF NOT EXISTS `supp_trans` (
  `trans_no` int(11) unsigned NOT NULL DEFAULT '0',
  `type` smallint(6) unsigned NOT NULL DEFAULT '0',
  `supplier_id` int(11) unsigned DEFAULT NULL,
  `reference` tinytext NOT NULL,
  `supp_reference` varchar(60) NOT NULL DEFAULT '',
  `tran_date` date NOT NULL DEFAULT '0000-00-00',
  `due_date` date NOT NULL DEFAULT '0000-00-00',
  `ov_amount` double NOT NULL DEFAULT '0',
  `ov_discount` double NOT NULL DEFAULT '0',
  `ov_gst` double NOT NULL DEFAULT '0',
  `rate` double NOT NULL DEFAULT '1',
  `alloc` double NOT NULL DEFAULT '0',
  `tax_included` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`type`,`trans_no`),
  KEY `supplier_id` (`supplier_id`),
  KEY `SupplierID_2` (`supplier_id`,`supp_reference`),
  KEY `type` (`type`),
  KEY `tran_date` (`tran_date`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `supp_trans`
--

INSERT INTO `supp_trans` (`trans_no`, `type`, `supplier_id`, `reference`, `supp_reference`, `tran_date`, `due_date`, `ov_amount`, `ov_discount`, `ov_gst`, `rate`, `alloc`, `tax_included`) VALUES
(7, 20, 2, '1', '5t', '2009-06-21', '2009-07-01', 3300, 0, 165, 1, 3465, 0),
(8, 20, 2, '2', 'cc', '2009-06-21', '2009-07-01', 20, 0, 0, 1, 0, 0),
(4, 22, 2, '1', '', '2009-06-21', '2009-06-21', -3465, 0, 0, 1, 3465, 0);

-- --------------------------------------------------------

--
-- Table structure for table `systype_attachments`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `sys_prefs`
--

CREATE TABLE IF NOT EXISTS `sys_prefs` (
  `name` varchar(35) NOT NULL DEFAULT '',
  `category` varchar(30) DEFAULT NULL,
  `type` varchar(20) NOT NULL DEFAULT '',
  `length` smallint(6) DEFAULT NULL,
  `value` tinytext,
  PRIMARY KEY (`name`),
  KEY `category` (`category`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sys_prefs`
--

INSERT INTO `sys_prefs` (`name`, `category`, `type`, `length`, `value`) VALUES
('coy_name', 'setup.company', 'varchar', 60, 'METALS AND MINERALS FZE'),
('gst_no', 'setup.company', 'varchar', 25, '9876543'),
('coy_no', 'setup.company', 'varchar', 25, '123456789'),
('tax_prd', 'setup.company', 'int', 11, '1'),
('tax_last', 'setup.company', 'int', 11, '1'),
('postal_address', 'setup.company', 'tinytext', 0, 'P.O. Box:50708,\r\nHamriyah Free Zone,\r\nSharjah,\r\nU.A.E.'),
('phone', 'setup.company', 'varchar', 30, '+971 6 5262768'),
('fax', 'setup.company', 'varchar', 30, '+971 6 5262769'),
('email', 'setup.company', 'varchar', 100, 'trading@metalsnminerals.com'),
('coy_logo', 'setup.company', 'varchar', 100, 'logo.PNG'),
('domicile', 'setup.company', 'varchar', 55, ''),
('curr_default', 'setup.company', 'char', 3, 'USD'),
('use_dimension', 'setup.company', 'tinyint', 1, '1'),
('f_year', 'setup.company', 'int', 11, '7'),
('no_item_list', 'setup.company', 'tinyint', 1, '0'),
('no_customer_list', 'setup.company', 'tinyint', 1, '0'),
('no_supplier_list', 'setup.company', 'tinyint', 1, '0'),
('base_sales', 'setup.company', 'int', 11, '1'),
('time_zone', 'setup.company', 'tinyint', 1, '0'),
('add_pct', 'setup.company', 'int', 5, '-1'),
('round_to', 'setup.company', 'int', 5, '1'),
('login_tout', 'setup.company', 'smallint', 6, '600'),
('past_due_days', 'glsetup.general', 'int', 11, '30'),
('profit_loss_year_act', 'glsetup.general', 'varchar', 15, '9990'),
('retained_earnings_act', 'glsetup.general', 'varchar', 15, '3590'),
('bank_charge_act', 'glsetup.general', 'varchar', 15, '5690'),
('exchange_diff_act', 'glsetup.general', 'varchar', 15, '4450'),
('default_credit_limit', 'glsetup.customer', 'int', 11, '1000'),
('accumulate_shipping', 'glsetup.customer', 'tinyint', 1, '0'),
('legal_text', 'glsetup.customer', 'tinytext', 0, ''),
('freight_act', 'glsetup.customer', 'varchar', 15, '4430'),
('debtors_act', 'glsetup.sales', 'varchar', 15, '1200'),
('default_sales_act', 'glsetup.sales', 'varchar', 15, '4010'),
('default_sales_discount_act', 'glsetup.sales', 'varchar', 15, '4510'),
('default_prompt_payment_act', 'glsetup.sales', 'varchar', 15, '4500'),
('default_delivery_required', 'glsetup.sales', 'smallint', 6, '1'),
('default_dim_required', 'glsetup.dims', 'int', 11, '20'),
('pyt_discount_act', 'glsetup.purchase', 'varchar', 15, '5060'),
('creditors_act', 'glsetup.purchase', 'varchar', 15, '2100'),
('po_over_receive', 'glsetup.purchase', 'int', 11, '10'),
('po_over_charge', 'glsetup.purchase', 'int', 11, '10'),
('allow_negative_stock', 'glsetup.inventory', 'tinyint', 1, '0'),
('default_inventory_act', 'glsetup.items', 'varchar', 15, '1510'),
('default_cogs_act', 'glsetup.items', 'varchar', 15, '5010'),
('default_adj_act', 'glsetup.items', 'varchar', 15, '5040'),
('default_inv_sales_act', 'glsetup.items', 'varchar', 15, '4010'),
('default_assembly_act', 'glsetup.items', 'varchar', 15, '1530'),
('default_workorder_required', 'glsetup.manuf', 'int', 11, '20'),
('version_id', 'system', 'varchar', 11, '2.3rc'),
('auto_curr_reval', 'setup.company', 'smallint', 6, '1'),
('grn_clearing_act', 'glsetup.purchase', 'varchar', 15, '1550'),
('bcc_email', 'setup.company', 'varchar', 100, '');

-- --------------------------------------------------------

--
-- Table structure for table `sys_types`
--

CREATE TABLE IF NOT EXISTS `sys_types` (
  `type_id` smallint(6) NOT NULL DEFAULT '0',
  `type_no` int(11) NOT NULL DEFAULT '1',
  `next_reference` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sys_types`
--

INSERT INTO `sys_types` (`type_id`, `type_no`, `next_reference`) VALUES
(0, 19, '3'),
(1, 8, '2'),
(2, 5, '2'),
(4, 3, '1'),
(10, 19, '7'),
(11, 3, '2'),
(12, 6, '4'),
(13, 5, '2'),
(16, 2, '1'),
(17, 2, '1'),
(18, 1, '3'),
(20, 8, '3'),
(21, 1, '1'),
(22, 4, '2'),
(25, 1, '2'),
(26, 1, '8'),
(28, 1, '1'),
(29, 1, '2'),
(30, 5, '11'),
(32, 0, '9'),
(35, 1, '1'),
(40, 1, '3');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` smallint(6) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` varchar(60) DEFAULT NULL,
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tag_associations`
--

CREATE TABLE IF NOT EXISTS `tag_associations` (
  `record_id` varchar(15) NOT NULL,
  `tag_id` int(11) NOT NULL,
  UNIQUE KEY `record_id` (`record_id`,`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tax_groups`
--

CREATE TABLE IF NOT EXISTS `tax_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL DEFAULT '',
  `tax_shipping` tinyint(1) NOT NULL DEFAULT '0',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tax_groups`
--

INSERT INTO `tax_groups` (`id`, `name`, `tax_shipping`, `inactive`) VALUES
(1, 'Tax', 0, 0),
(2, 'Tax Exempt', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tax_group_items`
--

CREATE TABLE IF NOT EXISTS `tax_group_items` (
  `tax_group_id` int(11) NOT NULL DEFAULT '0',
  `tax_type_id` int(11) NOT NULL DEFAULT '0',
  `rate` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`tax_group_id`,`tax_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tax_group_items`
--

INSERT INTO `tax_group_items` (`tax_group_id`, `tax_type_id`, `rate`) VALUES
(1, 1, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tax_types`
--

CREATE TABLE IF NOT EXISTS `tax_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rate` double NOT NULL DEFAULT '0',
  `sales_gl_code` varchar(15) NOT NULL DEFAULT '',
  `purchasing_gl_code` varchar(15) NOT NULL DEFAULT '',
  `name` varchar(60) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tax_types`
--

INSERT INTO `tax_types` (`id`, `rate`, `sales_gl_code`, `purchasing_gl_code`, `name`, `inactive`) VALUES
(1, 5, '2150', '2150', 'Tax', 0);

-- --------------------------------------------------------

--
-- Table structure for table `trans_tax_details`
--

CREATE TABLE IF NOT EXISTS `trans_tax_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_type` smallint(6) DEFAULT NULL,
  `trans_no` int(11) DEFAULT NULL,
  `tran_date` date NOT NULL,
  `tax_type_id` int(11) NOT NULL DEFAULT '0',
  `rate` double NOT NULL DEFAULT '0',
  `ex_rate` double NOT NULL DEFAULT '1',
  `included_in_price` tinyint(1) NOT NULL DEFAULT '0',
  `net_amount` double NOT NULL DEFAULT '0',
  `amount` double NOT NULL DEFAULT '0',
  `memo` tinytext,
  PRIMARY KEY (`id`),
  KEY `Type_and_Number` (`trans_type`,`trans_no`),
  KEY `tran_date` (`tran_date`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `trans_tax_details`
--

INSERT INTO `trans_tax_details` (`id`, `trans_type`, `trans_no`, `tran_date`, `tax_type_id`, `rate`, `ex_rate`, `included_in_price`, `net_amount`, `amount`, `memo`) VALUES
(1, 20, 7, '2009-06-21', 1, 5, 1, 0, 3300, 165, '5t'),
(2, 13, 3, '2009-06-21', 1, 5, 1, 0, 50, 2.5, 'auto'),
(3, 10, 17, '2009-06-21', 1, 5, 1, 0, 50, 2.5, '1'),
(4, 13, 4, '2009-06-21', 1, 5, 1.3932, 0, 35.89, 1.7945, 'auto'),
(5, 10, 18, '2009-06-21', 1, 5, 1.3932, 0, 35.89, 1.7945, '2'),
(6, 2, 5, '2009-06-21', 1, 5, 1, 0, 95.2, 4.76, ''),
(7, 1, 8, '2009-06-21', 1, 5, 1, 0, -47.6, -2.38, ''),
(8, 20, 8, '2009-06-21', 1, 5, 1, 0, -19, -0.95, 'cc'),
(9, 13, 5, '2009-06-21', 1, 5, 1, 1, 47.619047619048, 2.3809523809524, 'auto'),
(10, 10, 19, '2009-06-21', 1, 5, 1, 1, 47.619047619048, 2.3809523809524, '3'),
(11, 11, 3, '2009-06-21', 1, 5, 1.3932, 0, 35.89, 1.7945, '1'),
(12, 13, 6, '2014-06-12', 1, 5, 1, 1, 47.62, 2.38, 'auto'),
(13, 10, 20, '2014-06-12', 1, 5, 1, 1, 47.62, 2.38, '4'),
(14, 13, 7, '2014-06-12', 1, 5, 1, 1, 190.48, 9.52, 'auto'),
(15, 10, 21, '2014-06-12', 1, 5, 1, 1, 190.48, 9.52, '5'),
(16, 13, 8, '2014-06-12', 1, 5, 1, 1, 190.48, 9.52, 'auto'),
(17, 10, 22, '2014-06-12', 1, 5, 1, 1, 190.48, 9.52, '6');

-- --------------------------------------------------------

--
-- Table structure for table `useronline`
--

CREATE TABLE IF NOT EXISTS `useronline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` int(15) NOT NULL DEFAULT '0',
  `ip` varchar(40) NOT NULL DEFAULT '',
  `file` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `timestamp` (`timestamp`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(60) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `real_name` varchar(100) NOT NULL DEFAULT '',
  `role_id` int(11) NOT NULL DEFAULT '1',
  `phone` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(100) DEFAULT NULL,
  `language` varchar(20) DEFAULT NULL,
  `date_format` tinyint(1) NOT NULL DEFAULT '0',
  `date_sep` tinyint(1) NOT NULL DEFAULT '0',
  `tho_sep` tinyint(1) NOT NULL DEFAULT '0',
  `dec_sep` tinyint(1) NOT NULL DEFAULT '0',
  `theme` varchar(20) NOT NULL DEFAULT 'default',
  `page_size` varchar(20) NOT NULL DEFAULT 'A4',
  `prices_dec` smallint(6) NOT NULL DEFAULT '2',
  `qty_dec` smallint(6) NOT NULL DEFAULT '2',
  `rates_dec` smallint(6) NOT NULL DEFAULT '4',
  `percent_dec` smallint(6) NOT NULL DEFAULT '1',
  `show_gl` tinyint(1) NOT NULL DEFAULT '1',
  `show_codes` tinyint(1) NOT NULL DEFAULT '0',
  `show_hints` tinyint(1) NOT NULL DEFAULT '0',
  `last_visit_date` datetime DEFAULT NULL,
  `query_size` tinyint(1) DEFAULT '10',
  `graphic_links` tinyint(1) DEFAULT '1',
  `pos` smallint(6) DEFAULT '1',
  `print_profile` varchar(30) NOT NULL DEFAULT '1',
  `rep_popup` tinyint(1) DEFAULT '1',
  `sticky_doc_date` tinyint(1) DEFAULT '0',
  `startup_tab` varchar(20) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_id`, `password`, `real_name`, `role_id`, `phone`, `email`, `language`, `date_format`, `date_sep`, `tho_sep`, `dec_sep`, `theme`, `page_size`, `prices_dec`, `qty_dec`, `rates_dec`, `percent_dec`, `show_gl`, `show_codes`, `show_hints`, `last_visit_date`, `query_size`, `graphic_links`, `pos`, `print_profile`, `rep_popup`, `sticky_doc_date`, `startup_tab`, `inactive`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrator', 2, '', 'adm@adm.com', 'C', 0, 0, 0, 0, 'default', 'Letter', 2, 2, 4, 1, 1, 0, 0, '2014-06-12 11:56:20', 10, 1, 1, '', 1, 0, 'orders', 0),
(2, 'demouser', '5f4dcc3b5aa765d61d8327deb882cf99', 'Demo User', 9, '999-999-999', 'demo@demo.nu', 'en_US', 0, 0, 0, 0, 'default', 'Letter', 2, 2, 3, 1, 1, 0, 0, '2008-02-06 19:02:35', 10, 1, 1, '1', 1, 0, 'orders', 0);

-- --------------------------------------------------------

--
-- Table structure for table `voided`
--

CREATE TABLE IF NOT EXISTS `voided` (
  `type` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL DEFAULT '0',
  `date_` date NOT NULL DEFAULT '0000-00-00',
  `memo_` tinytext NOT NULL,
  UNIQUE KEY `id` (`type`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `workcentres`
--

CREATE TABLE IF NOT EXISTS `workcentres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(40) NOT NULL DEFAULT '',
  `description` char(50) NOT NULL DEFAULT '',
  `inactive` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `workcentres`
--

INSERT INTO `workcentres` (`id`, `name`, `description`, `inactive`) VALUES
(1, 'Workshop', 'Workshop in Alabama', 0);

-- --------------------------------------------------------

--
-- Table structure for table `workorders`
--

CREATE TABLE IF NOT EXISTS `workorders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `wo_ref` varchar(60) NOT NULL DEFAULT '',
  `loc_code` varchar(5) NOT NULL DEFAULT '',
  `units_reqd` double NOT NULL DEFAULT '1',
  `stock_id` varchar(20) NOT NULL DEFAULT '',
  `date_` date NOT NULL DEFAULT '0000-00-00',
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `required_by` date NOT NULL DEFAULT '0000-00-00',
  `released_date` date NOT NULL DEFAULT '0000-00-00',
  `units_issued` double NOT NULL DEFAULT '0',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `released` tinyint(1) NOT NULL DEFAULT '0',
  `additional_costs` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `wo_ref` (`wo_ref`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `workorders`
--

INSERT INTO `workorders` (`id`, `wo_ref`, `loc_code`, `units_reqd`, `stock_id`, `date_`, `type`, `required_by`, `released_date`, `units_issued`, `closed`, `released`, `additional_costs`) VALUES
(1, '1', 'DEF', 10, '3400', '2009-06-21', 0, '2009-06-21', '2009-06-21', 10, 1, 1, 10),
(2, '2', 'DEF', 3, '3400', '2009-06-21', 2, '2009-07-11', '2009-06-21', 2, 0, 1, 0),
(3, '3', 'DEF', 2, '3400', '2009-06-21', 0, '2009-06-21', '2009-06-21', 2, 1, 1, 0),
(4, '4', 'DEF', 4, '3400', '2009-06-21', 0, '2009-06-21', '2009-06-21', 4, 1, 1, 0),
(5, '5', 'DEF', 5, '3400', '2009-06-21', 0, '2009-06-21', '2009-06-21', 5, 1, 1, 10),
(6, '6', 'DEF', -5, '3400', '2009-06-21', 1, '2009-06-21', '2009-06-21', -5, 1, 1, 0),
(7, '7', 'DEF', -2, '3400', '2009-06-21', 1, '2009-06-21', '2009-06-21', -2, 1, 1, 10);

-- --------------------------------------------------------

--
-- Table structure for table `wo_issues`
--

CREATE TABLE IF NOT EXISTS `wo_issues` (
  `issue_no` int(11) NOT NULL AUTO_INCREMENT,
  `workorder_id` int(11) NOT NULL DEFAULT '0',
  `reference` varchar(100) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `loc_code` varchar(5) DEFAULT NULL,
  `workcentre_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`issue_no`),
  KEY `workorder_id` (`workorder_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wo_issue_items`
--

CREATE TABLE IF NOT EXISTS `wo_issue_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_id` varchar(40) DEFAULT NULL,
  `issue_id` int(11) DEFAULT NULL,
  `qty_issued` double DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `wo_manufacture`
--

CREATE TABLE IF NOT EXISTS `wo_manufacture` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(100) DEFAULT NULL,
  `workorder_id` int(11) NOT NULL DEFAULT '0',
  `quantity` double NOT NULL DEFAULT '0',
  `date_` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`),
  KEY `workorder_id` (`workorder_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `wo_manufacture`
--

INSERT INTO `wo_manufacture` (`id`, `reference`, `workorder_id`, `quantity`, `date_`) VALUES
(1, '1', 2, 2, '2009-06-21');

-- --------------------------------------------------------

--
-- Table structure for table `wo_requirements`
--

CREATE TABLE IF NOT EXISTS `wo_requirements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `workorder_id` int(11) NOT NULL DEFAULT '0',
  `stock_id` char(20) NOT NULL DEFAULT '',
  `workcentre` int(11) NOT NULL DEFAULT '0',
  `units_req` double NOT NULL DEFAULT '1',
  `std_cost` double NOT NULL DEFAULT '0',
  `loc_code` char(5) NOT NULL DEFAULT '',
  `units_issued` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `workorder_id` (`workorder_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

--
-- Dumping data for table `wo_requirements`
--

INSERT INTO `wo_requirements` (`id`, `workorder_id`, `stock_id`, `workcentre`, `units_req`, `std_cost`, `loc_code`, `units_issued`) VALUES
(1, 1, '102', 1, 1, 0, 'DEF', 10),
(2, 1, '103', 1, 1, 0, 'DEF', 10),
(3, 1, '104', 1, 1, 0, 'DEF', 10),
(4, 2, '102', 1, 1, 0, 'DEF', 0),
(5, 2, '103', 1, 1, 0, 'DEF', 0),
(6, 2, '104', 1, 1, 0, 'DEF', 0),
(7, 3, '102', 1, 1, 0, 'DEF', 2),
(8, 3, '103', 1, 1, 0, 'DEF', 2),
(9, 3, '104', 1, 1, 0, 'DEF', 2),
(10, 4, '102', 1, 1, 0, 'DEF', 4),
(11, 4, '103', 1, 1, 0, 'DEF', 4),
(12, 4, '104', 1, 1, 0, 'DEF', 4),
(13, 5, '102', 1, 1, 0, 'DEF', 5),
(14, 5, '103', 1, 1, 0, 'DEF', 5),
(15, 5, '104', 1, 1, 0, 'DEF', 5),
(16, 6, '102', 1, 1, 0, 'DEF', -5),
(17, 6, '103', 1, 1, 0, 'DEF', -5),
(18, 6, '104', 1, 1, 0, 'DEF', -5),
(19, 7, '102', 1, 1, 0, 'DEF', -2),
(20, 7, '103', 1, 1, 0, 'DEF', -2),
(21, 7, '104', 1, 1, 0, 'DEF', -2);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
