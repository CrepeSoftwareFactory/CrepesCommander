-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mar 18 Avril 2017 à 14:25
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=74 ;

--
-- Contenu de la table `customer`
--

INSERT INTO `customer` (`customer_id`, `lastname`, `firstname`, `email`, `zip`) VALUES
(2, 'ajax test', NULL, NULL, NULL),
(3, 'ajaja', NULL, NULL, NULL),
(4, 'polop', NULL, NULL, NULL),
(5, 'Triomphe', NULL, NULL, NULL),
(6, 'truc', NULL, NULL, NULL),
(7, 'poutre', NULL, NULL, NULL),
(8, 'youpi', NULL, NULL, NULL),
(9, 'jojo', NULL, NULL, NULL),
(10, 'bobol', NULL, NULL, NULL),
(11, 'blues', NULL, NULL, NULL),
(12, 'grot', NULL, NULL, NULL),
(13, 'lulu', NULL, NULL, NULL),
(14, 'sdf', NULL, NULL, NULL),
(15, 'roberto', NULL, NULL, NULL),
(16, 'sg', NULL, NULL, NULL),
(17, 'bol', NULL, NULL, NULL),
(18, 'youyou', NULL, NULL, NULL),
(19, 'you', NULL, NULL, NULL),
(20, 'tourner', NULL, NULL, NULL),
(21, 'juju', NULL, NULL, NULL),
(22, 'troup', NULL, NULL, NULL),
(23, 'tutu', NULL, NULL, NULL),
(24, 'roger', NULL, NULL, NULL),
(25, 'ok johnny', NULL, NULL, NULL),
(26, 'polop', NULL, NULL, NULL),
(27, 'popol', NULL, NULL, NULL),
(28, 'fq', NULL, NULL, NULL),
(29, 'sdf', NULL, NULL, NULL),
(30, 'panier', NULL, NULL, NULL),
(31, 'cmd', NULL, NULL, NULL),
(32, 'bebel', NULL, NULL, NULL),
(33, 'bordel', NULL, NULL, NULL),
(34, 'tt', NULL, NULL, NULL),
(35, 'qsfd', NULL, NULL, NULL),
(36, 'qsd', NULL, NULL, NULL),
(37, 'a annuler', NULL, NULL, NULL),
(38, 'zerez', NULL, NULL, NULL),
(39, 'roger', NULL, NULL, NULL),
(40, 'toto', NULL, NULL, NULL),
(41, 'tutu', NULL, NULL, NULL),
(42, 'first', NULL, NULL, NULL),
(43, 'second', NULL, NULL, NULL),
(44, 'third', NULL, NULL, NULL),
(45, 'toSend', NULL, NULL, NULL),
(46, 'polop', NULL, NULL, NULL),
(47, 'peter', NULL, NULL, NULL),
(48, 'bibi', NULL, NULL, NULL),
(49, 'deuxieme', NULL, NULL, NULL),
(50, 'youpi', NULL, NULL, NULL),
(51, 'first one', NULL, NULL, NULL),
(52, 'second one', NULL, NULL, NULL),
(53, 'bebert', NULL, NULL, NULL),
(54, 'bebert', NULL, NULL, NULL),
(55, 'truc', NULL, NULL, NULL),
(56, 'bidule', NULL, NULL, NULL),
(57, 'bidule', NULL, NULL, NULL),
(58, 'bidule', NULL, NULL, NULL),
(59, 'bidule', NULL, NULL, NULL),
(60, 'bidule', NULL, NULL, NULL),
(61, 'bebel', NULL, NULL, NULL),
(62, 'bebel', NULL, NULL, NULL),
(63, 'tutu', NULL, NULL, NULL),
(64, 'truc', NULL, NULL, NULL),
(65, 'dudule', NULL, NULL, NULL),
(66, 'greg', NULL, NULL, NULL),
(67, 'greed', NULL, NULL, NULL),
(68, 'tru', NULL, NULL, NULL),
(69, 'baby', NULL, NULL, NULL),
(70, 'gret', NULL, NULL, NULL),
(71, 'bebel', NULL, NULL, NULL),
(72, 'bebert', NULL, NULL, NULL),
(73, 'budul', NULL, NULL, NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `order`
--

INSERT INTO `order` (`order_id`, `date`, `status`, `customer_id`) VALUES
(1, '2017-04-12 11:56:40', 4, 71),
(2, '2017-04-12 14:22:18', 2, 72),
(3, '2017-04-18 12:51:45', 2, 73);

-- --------------------------------------------------------

--
-- Structure de la table `proco_status`
--

DROP TABLE IF EXISTS `proco_status`;
CREATE TABLE IF NOT EXISTS `proco_status` (
  `proco_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `slug` varchar(6) NOT NULL,
  `name` varchar(6) NOT NULL,
  PRIMARY KEY (`proco_status_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `proco_status`
--

INSERT INTO `proco_status` (`proco_status_id`, `slug`, `name`) VALUES
(1, 'normal', 'Normal'),
(2, 'urgent', 'Urgent'),
(3, 'bloque', 'Bloqué');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Contenu de la table `product_order`
--

INSERT INTO `product_order` (`product_order_id`, `product_id`, `order_id`, `start`, `end`, `priority`, `price`, `station_id`, `free`, `comment`, `status`) VALUES
(1, 6, 1, '2017-04-12 11:57:53', '2017-04-12 11:58:32', 0, 2.5, 2, 0, NULL, 1),
(2, 8, 1, '2017-04-12 11:58:08', '2017-04-12 14:00:36', 0, 2.5, 3, 0, NULL, 1),
(3, 4, 1, '2017-04-12 11:58:09', '2017-04-12 13:54:21', 0, 2.5, 1, 0, NULL, 1),
(4, 4, 1, '2017-04-12 11:58:10', '2017-04-12 13:54:23', 0, 2.5, 1, 0, NULL, 1),
(5, 7, 1, '2017-04-12 11:58:32', '2017-04-12 11:58:37', 0, 4.5, 4, 0, NULL, 1),
(6, 1, 1, '2017-04-12 11:58:37', '2017-04-12 11:58:41', 0, 4.5, 4, 0, NULL, 1),
(7, 1, 1, '2017-04-12 11:58:41', '2017-04-12 14:00:40', 0, 4.5, 1, 0, NULL, 1),
(8, 2, 1, '2017-04-12 13:54:20', '2017-04-12 14:00:50', 0, 4.5, 1, 0, NULL, 1),
(9, 3, 1, '2017-04-12 13:54:23', '2017-04-12 13:54:27', 0, 4.5, 1, 0, NULL, 1),
(10, 3, 1, '2017-04-12 13:54:21', '2017-04-12 13:54:28', 0, 4.5, 1, 0, NULL, 1),
(11, 5, 1, '2017-04-12 13:54:24', '2017-04-12 13:54:38', 0, 2.5, 1, 0, NULL, 1),
(12, 1, 2, '2017-04-12 14:23:38', '2017-04-12 14:28:55', 0, 4.5, 1, 0, NULL, 1),
(13, 7, 2, '2017-04-12 14:29:20', '2017-04-12 14:29:23', 0, 4.5, 2, 0, NULL, 1),
(14, 7, 2, '2017-04-12 14:28:55', '2017-04-12 14:29:33', 0, 4.5, 1, 0, NULL, 1),
(15, 8, 2, '2017-04-12 15:01:26', '2017-04-12 15:01:29', 0, 2.5, 3, 0, NULL, 1),
(16, 8, 2, '2017-04-12 15:01:25', NULL, 0, 2.5, 4, 0, NULL, 1),
(17, 2, 3, NULL, NULL, 0, 4.5, NULL, 0, NULL, 1),
(18, 2, 3, NULL, NULL, 0, 4.5, NULL, 0, NULL, 2),
(19, 4, 3, NULL, NULL, 0, 2.5, NULL, 0, NULL, 1),
(20, 7, 3, NULL, NULL, 0, 4.5, NULL, 0, NULL, 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `station`
--

INSERT INTO `station` (`station_id`, `name`, `state`) VALUES
(1, 'P1', 0),
(2, 'P2', 0),
(3, 'P3', 0),
(4, 'P4', 0);

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
