-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 15, 2012 at 11:01 AM
-- Server version: 5.5.15
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `easyfinance`
--
-- CREATE DATABASE easyfinance;
USE easyfinance;

-- --------------------------------------------------------
--
-- Table structure for table `addresses`
--
CREATE TABLE IF NOT EXISTS `addresses` (
`id`  int(11) NOT NULL AUTO_INCREMENT,
`address1` varchar(200),
`address2` varchar(200),
`address3` varchar(200),
`city` varchar(100),
`province` varchar(100),
`postcode` varchar(10),
`other` text,
PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Table structure for table `company`
--
CREATE TABLE IF NOT EXISTS `company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255),
  `responsible_name` varchar(255),
  `responsible_surname` varchar(255),
  `login` varchar(255),
  `password` varchar(255),
  `telephone` varchar(100),
  `email` varchar(255),
  `site` varchar(255),
  `logo_url` varchar(100),
  `bank_account` varchar(100),
  `iban` varchar(100),
  `bic` varchar(20),
  `fax` varchar(20),
  `company_number` varchar(20),
  `address_id` int(11),
  PRIMARY KEY (`id`),
    FOREIGN KEY (`address_id`) REFERENCES `addresses`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Table structure for table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(255) ,
  `name` varchar(255) DEFAULT NULL,
  `surname` varchar(255) DEFAULT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `dni` varchar(255) DEFAULT NULL,
  `notes` text,
  `ordre` int(11) NOT NULL,
  `address_id` int(11),
  PRIMARY KEY (`id`),
    FOREIGN KEY (`address_id`) REFERENCES `addresses`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Table structure for table `autos`
--

CREATE TABLE IF NOT EXISTS `autos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `plate_number` varchar(20) ,
  `brand` varchar(255) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `motor` varchar(255) DEFAULT NULL,
  `tires` varchar(255) DEFAULT NULL,
  `chassis_number` varchar(255) DEFAULT NULL,
  `registration_year` varchar(20) DEFAULT NULL,
  `kws` varchar(20) DEFAULT NULL,
  `kilometers` varchar(20) DEFAULT NULL,
  `notes` text,
  `client_id` int(11),
  PRIMARY KEY (`id`),
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Table structure for table `itv_logs`
--

CREATE TABLE IF NOT EXISTS `itv_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `last_date` date NULL DEFAULT '0000-00-00',
  `frequency` varchar(50),
  `next_date` date,
  `status` varchar(20) DEFAULT NULL,
  `notes` text,
  `auto_id` int(11),
  PRIMARY KEY (`id`),
    FOREIGN KEY (`auto_id`) REFERENCES `autos`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Table structure for table `services`
--
CREATE TABLE  IF NOT EXISTS  `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) ,
  `description` varchar(255) ,
  `price` DECIMAL(8,2),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Table structure for table `statuses`
--
CREATE TABLE  IF NOT EXISTS  `statuses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) ,
  `description` varchar(255) ,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Table structure for table `budgets`
--
CREATE TABLE  IF NOT EXISTS  `budgets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_at` date NULL DEFAULT '0000-00-00',
  `presented_at` date NULL DEFAULT '0000-00-00',
  `paid_at` date NULL DEFAULT '0000-00-00',
  `invoice_number` varchar(255),
  `notes` text,
  `total` DECIMAL(8,2),
  `vat` DECIMAL(8,2),
  `auto_id` int(11),
  `address_id` int(11),
  `status_id` int(11),
  `client_id` int(11),
  PRIMARY KEY (`id`),
    FOREIGN KEY (`auto_id`) REFERENCES `autos`(`id`),
    FOREIGN KEY (`client_id`) REFERENCES `clients`(`id`),
    FOREIGN KEY (`address_id`) REFERENCES `addresses`(`id`),
    FOREIGN KEY (`status_id`) REFERENCES `statuses`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------
--
-- Table structure for table `budget_services`
--
CREATE TABLE  IF NOT EXISTS  `budget_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discount` DECIMAL(8,2),
  `price` DECIMAL(8,2),
  `amount` DECIMAL(8,1),
  `total` DECIMAL(8,2),
  `total_dcto` DECIMAL(8,2),   
  `service_id` int(11),
  `budget_id` int(11),
  PRIMARY KEY (`id`),
    FOREIGN KEY (`budget_id`) REFERENCES `budgets`(`id`),
    FOREIGN KEY (`service_id`) REFERENCES `services`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------
--
-- Table structure for table `facturesEntrantes`
--

-- CREATE TABLE IF NOT EXISTS `facturesEntrantes` (
--   `id` int(11) NOT NULL AUTO_INCREMENT,
--   `date` date NOT NULL DEFAULT '0000-00-00',
--   `denomination` varchar(255) NOT NULL,
--   `objet` varchar(255) NOT NULL,
--   `montant` decimal(10,2) NOT NULL,
--   `pourcent_tva` decimal(4,2) NOT NULL,
--   `montant_tva` decimal(10,2) NOT NULL,
--   `montant_tvac` decimal(10,2) NOT NULL,
--   PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `facturesSortantes`
--
--
-- CREATE TABLE IF NOT EXISTS `facturesSortantes` (
--   `id` int(11) NOT NULL AUTO_INCREMENT,
--   `id_usr` int(11) NOT NULL,
--   `id_client` int(11) NOT NULL,
--   `date` date NOT NULL DEFAULT '0000-00-00',
--   `numero` int(11) NOT NULL,
--   `objet` text NOT NULL,
--   `montant` decimal(10,2) NOT NULL,
--   `pourcent_tva` decimal(4,2) NOT NULL,
--   `montant_tva` decimal(10,2) NOT NULL,
--   `montant_tvac` decimal(10,2) NOT NULL,
--   `amount_paid` decimal(10,2) NOT NULL,
--   PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `trimestres`
--

-- CREATE TABLE IF NOT EXISTS `trimestres` (
--   `id` int(11) NOT NULL AUTO_INCREMENT,
--   `annee` int(4) NOT NULL DEFAULT '0',
--   `trimestre` int(1) NOT NULL DEFAULT '0',
--   `montant_htva` decimal(10,2) NOT NULL DEFAULT '0.00',
--   `montant_tva` decimal(10,2) NOT NULL DEFAULT '0.00',
--   `montant_tvac` decimal(10,2) NOT NULL DEFAULT '0.00',
--   `type` varchar(10) NOT NULL,
--   PRIMARY KEY (`id`)
-- ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*
					CREATE TABLE $table_name_clients (
					id mediumint(9) NOT NULL AUTO_INCREMENT,
					create_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					name varchar(200) NOT NULL,
					surname varchar(200) NOT NULL,
					telephone1 varchar(15),
					telephone2 varchar(15),
					dni varchar(15),
					email varchar(200),
					PRIMARY KEY  (id)
					) $charset_collate;

					CREATE TABLE $table_name_items (
					id mediumint(9) NOT NULL AUTO_INCREMENT,
					name varchar(200),
					description text,
					price DECIMAL(8,2),
					PRIMARY KEY  (id)
					) $charset_collate;

					CREATE TABLE $table_name_budgets (
					id mediumint(9) NOT NULL AUTO_INCREMENT,
					create_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					invoice_number varchar(20),
					notes text,
					PRIMARY KEY  (id)
					) $charset_collate;

					CREATE TABLE $table_name_temp_budgets (
					id mediumint(9) NOT NULL AUTO_INCREMENT,
					name varchar(100),
					notes text,
					PRIMARY KEY  (id)
					) $charset_collate;

					CREATE TABLE $table_name_temp_documents (
					id mediumint(9) NOT NULL AUTO_INCREMENT,
					doc_url varchar(255),
					create_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					PRIMARY KEY  (id)
					) $charset_collate;

*/;
