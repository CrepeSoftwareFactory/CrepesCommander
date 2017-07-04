-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mar 04 Juillet 2017 à 16:28
-- Version du serveur :  5.6.15-log
-- Version de PHP :  5.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `cc`
--
CREATE DATABASE IF NOT EXISTS `cc` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `cc`;

-- --------------------------------------------------------

--
-- Structure de la table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `customer_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `ingredient`
--

DROP TABLE IF EXISTS `ingredient`;
CREATE TABLE IF NOT EXISTS `ingredient` (
  `ingredient_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`ingredient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `order`
--

DROP TABLE IF EXISTS `order`;
CREATE TABLE IF NOT EXISTS `order` (
  `order_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `customer_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `proco_status`
--

DROP TABLE IF EXISTS `proco_status`;
CREATE TABLE IF NOT EXISTS `proco_status` (
  `proco_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(6) NOT NULL,
  `name` varchar(6) NOT NULL,
  `priority` int(11) NOT NULL,
  PRIMARY KEY (`proco_status_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `proco_status`
--

INSERT INTO `proco_status` (`proco_status_id`, `slug`, `name`, `priority`) VALUES
(1, 'normal', 'Normal', 2),
(2, 'urgent', 'Urgent', 1),
(3, 'bloque', 'Bloqué', 3);

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `product_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` float NOT NULL,
  `time` time DEFAULT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Contenu de la table `product`
--

INSERT INTO `product` (`product_id`, `code`, `name`, `description`, `price`, `time`, `type`) VALUES
(1, 'Ga. CPL', 'Galette Complète', NULL, 6.5, NULL, 0),
(2, 'Ga. ChMiel', 'Galette Chèvre & miel', NULL, 6, NULL, 0),
(3, 'Ga. 3FR', 'Galette 3 Fromages', NULL, 6, NULL, 0),
(4, 'Cr CHOC', 'Crepe Chocolat', NULL, 3.5, NULL, 1),
(5, 'Cr CONF', 'Crêpe Confiture', NULL, 3, NULL, 1),
(6, 'Cr. SucBeur', 'Crêpe Beurre Sucre', NULL, 2.5, NULL, 1),
(8, 'Cr. KRMEL', 'Crêpe Caramel ', NULL, 3.5, NULL, 1),
(9, 'Cr. nat', 'Crêpe Nature', NULL, 2, NULL, 1),
(10, 'Ga. nat', 'Galette Nature', NULL, 3, NULL, 0),
(12, 'Ga. DIY', 'Galette Custom DIY', NULL, 0, NULL, 0),
(13, 'Cr. DIY', 'Crêpe Custom DIY', NULL, 0, NULL, 1),
(14, 'Sd Slde', 'Side : Salade', NULL, 2.5, NULL, 2),
(15, 'Cr. SucCit', 'Crêpe Sucre Citron', NULL, 2.7, NULL, 1),
(16, 'Cr. SucKnl', 'Crêpe Sucre canelle', NULL, 2.7, NULL, 1),
(17, 'Glou Pom', 'Boisson jus de pomme', NULL, 3, NULL, 2),
(18, 'Glou Obul', 'Boisson Eau Gazeuze', NULL, 3, NULL, 2),
(19, 'Glou Cig', 'Boisson Cigre Coktail ss alcool', NULL, 3, NULL, 2),
(20, 'MENU 1', 'Menu Ga + Cr + boisson', NULL, 10, NULL, 3);

-- --------------------------------------------------------

--
-- Structure de la table `product_ingredient`
--

DROP TABLE IF EXISTS `product_ingredient`;
CREATE TABLE IF NOT EXISTS `product_ingredient` (
  `product_id` bigint(20) unsigned NOT NULL,
  `ingredient_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`product_id`,`ingredient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `product_order`
--

DROP TABLE IF EXISTS `product_order`;
CREATE TABLE IF NOT EXISTS `product_order` (
  `product_order_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `priority` int(11) NOT NULL,
  `price` float NOT NULL,
  `station_id` bigint(20) unsigned DEFAULT NULL,
  `free` tinyint(1) NOT NULL DEFAULT '0',
  `comment` varchar(255) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`product_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `product_type`
--

DROP TABLE IF EXISTS `product_type`;
CREATE TABLE IF NOT EXISTS `product_type` (
  `type_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `type_label` varchar(255) NOT NULL,
  `type_couleur` varchar(10) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `product_type`
--

INSERT INTO `product_type` (`type_id`, `type_label`, `type_couleur`) VALUES
(0, 'Galettes', '#ac8960'),
(1, 'Crepes', '#fceb78'),
(2, 'Autres', '#aaecc8'),
(3, 'Menus', '#FFFFFF');

-- --------------------------------------------------------

--
-- Structure de la table `station`
--

DROP TABLE IF EXISTS `station`;
CREATE TABLE IF NOT EXISTS `station` (
  `station_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`station_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `station`
--

INSERT INTO `station` (`station_id`, `name`, `state`) VALUES
(1, 'P1', 0),
(2, 'P2', 0),
(3, 'P3', 0),
(4, 'P4', 0),
(5, 'P5', 0),
(6, 'P6', 0);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`user_id`, `login`, `password`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
