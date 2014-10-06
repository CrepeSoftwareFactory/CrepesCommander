-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Jeu 28 Août 2014 à 14:19
-- Version du serveur: 5.5.38
-- Version de PHP: 5.4.4-14+deb7u14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `cc`
--

-- --------------------------------------------------------

--
-- Structure de la table `customer`
--

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

CREATE TABLE IF NOT EXISTS `order` (
  `order_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `customer_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `product_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` float NOT NULL,
  `time` time DEFAULT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Contenu de la table `product`
--

INSERT INTO `product` (`product_id`, `code`, `name`, `description`, `price`, `time`, `type`) VALUES
(1, 'Ga. CPL', 'Galette Complète', NULL, 4.5, NULL, 0),
(2, 'Ga. VG', 'Galette Végé', NULL, 4.5, NULL, 0),
(3, 'Ga. 3FR', 'Galette 3 Fromages ', NULL, 4.5, NULL, 0),
(4, 'Cr NUT', 'Crêpe Nutella', NULL, 2.5, NULL, 1),
(5, 'Cr CONF', 'Crêpe Confiture', NULL, 2.5, NULL, 1),
(6, 'Cr. BS', 'Crêpe Beurre Sucre', NULL, 2.5, NULL, 1),
(7, 'Ga. SAUC', 'Galette Saucisse', NULL, 4.5, NULL, 0),
(8, 'Cr. CRML', 'Crêpe Caramel ', NULL, 2.5, NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `product_ingredient`
--

CREATE TABLE IF NOT EXISTS `product_ingredient` (
  `product_id` bigint(20) unsigned NOT NULL,
  `ingredient_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`product_id`,`ingredient_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `product_order`
--

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
  PRIMARY KEY (`product_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `station`
--

CREATE TABLE IF NOT EXISTS `station` (
  `station_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `state` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`station_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `station`
--

INSERT INTO `station` (`station_id`, `name`, `state`) VALUES
(1, 'P1', 0),
(2, 'P2', 0),
(3, 'P3', 0),
(4, 'P4', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
