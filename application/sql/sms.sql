-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 20, 2014 at 10:06 PM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` text NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `department_id` int(11) NOT NULL AUTO_INCREMENT,
  `department_name` text NOT NULL,
  `department_desc` text NOT NULL,
  PRIMARY KEY (`department_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` text NOT NULL,
  `item_qty` int(11) NOT NULL,
  `item_unit` text NOT NULL,
  `item_min_qty` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=125 ;

-- --------------------------------------------------------

--
-- Table structure for table `ro`
--

DROP TABLE IF EXISTS `ro`;
CREATE TABLE IF NOT EXISTS `ro` (
  `roID` int(11) NOT NULL AUTO_INCREMENT,
  `roDesc` text NOT NULL,
  `roJustification` text NOT NULL,
  `roDate` datetime DEFAULT CURRENT_TIMESTAMP,
  `userID` int(11) NOT NULL,
  `departmentID` int(11) NOT NULL,
  `status` int(11) DEFAULT '1',
  `fileUrl` text,
  `remark` text,
  PRIMARY KEY (`roID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `roitems`
--

DROP TABLE IF EXISTS `roitems`;
CREATE TABLE IF NOT EXISTS `roitems` (
  `roItemsID` int(11) NOT NULL AUTO_INCREMENT,
  `roID` int(11) NOT NULL,
  `itemID` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  PRIMARY KEY (`roItemsID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Table structure for table `ro_log`
--

DROP TABLE IF EXISTS `ro_log`;
CREATE TABLE IF NOT EXISTS `ro_log` (
  `ro_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `roID` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `remark` text NOT NULL,
  `reminder_attempt` int(11) NOT NULL,
  PRIMARY KEY (`ro_log_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

DROP TABLE IF EXISTS `vendor`;
CREATE TABLE IF NOT EXISTS `vendor` (
  `vendorID` int(11) NOT NULL AUTO_INCREMENT,
  `vendorCategory` text NOT NULL,
  `vendorDescription` text NOT NULL,
  `vendorName` text NOT NULL,
  `vendorAddress` longtext NOT NULL,
  `vendorTelephone` text NOT NULL,
  `vendorFax` text NOT NULL,
  `vendorEmail` text NOT NULL,
  PRIMARY KEY (`vendorID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
