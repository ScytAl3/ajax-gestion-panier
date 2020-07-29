-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 29 juil. 2020 à 09:37
-- Version du serveur :  10.4.10-MariaDB
-- Version de PHP : 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `db_ajax_panier_dev`
--

-- --------------------------------------------------------

--
-- Structure de la table `lignes_commande`
--

DROP TABLE IF EXISTS `lignes_commande`;
CREATE TABLE IF NOT EXISTS `lignes_commande` (
  `ligneCmdId` int(11) NOT NULL AUTO_INCREMENT,
  `paniersId` int(11) NOT NULL,
  `produitsId` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  PRIMARY KEY (`ligneCmdId`),
  KEY `paniersId` (`paniersId`),
  KEY `produitsId` (`produitsId`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `lignes_commande`
--

INSERT INTO `lignes_commande` (`ligneCmdId`, `paniersId`, `produitsId`, `quantite`) VALUES
(1, 2, 1, 3),
(2, 2, 8, 1),
(3, 3, 1, 1),
(4, 3, 9, 1),
(5, 4, 1, 1),
(6, 4, 9, 1),
(7, 4, 7, 1),
(8, 5, 1, 1),
(9, 5, 7, 1),
(10, 5, 8, 1),
(11, 5, 9, 1),
(12, 7, 1, 3),
(13, 7, 5, 2),
(14, 8, 9, 4),
(15, 9, 1, 3),
(16, 9, 7, 2);

-- --------------------------------------------------------

--
-- Structure de la table `paniers`
--

DROP TABLE IF EXISTS `paniers`;
CREATE TABLE IF NOT EXISTS `paniers` (
  `paniersId` int(11) NOT NULL AUTO_INCREMENT,
  `panierUserTemp` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `valided` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`paniersId`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `paniers`
--

INSERT INTO `paniers` (`paniersId`, `panierUserTemp`, `created_at`, `valided`) VALUES
(1, '1593951383', '2020-07-05 14:43:43', 0),
(2, '1593953124', '2020-07-05 14:45:30', 0),
(3, '1593957836', '2020-07-05 16:04:08', 0),
(4, '1593958583', '2020-07-05 16:16:28', 0),
(5, '1593960895', '2020-07-05 16:55:09', 0),
(6, '1594199015', '2020-07-08 11:04:11', 0),
(7, '1594199015', '2020-07-08 11:05:43', 0),
(8, '1594199015', '2020-07-08 11:14:09', 0),
(9, '1594308780', '2020-07-09 17:33:12', 0);

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(5,2) NOT NULL,
  `created_at` datetime NOT NULL,
  `availability` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`id`, `name`, `image`, `quantity`, `price`, `created_at`, `availability`) VALUES
(1, 'Aerodynamic Knife', 'https://picsum.photos/400/400/?83654', 92, '59.90', '2020-07-04 14:27:35', 1),
(2, 'Mediocre Paper Coat', 'https://picsum.photos/400/400/?21604', 99, '55.25', '2020-07-04 14:27:35', 1),
(3, 'Ergonomic Wool Coat', 'https://picsum.photos/400/400/?11969', 99, '25.85', '2020-07-04 14:27:35', 1),
(4, 'Ergonomic Wool Clock', 'https://picsum.photos/400/400/?19112', 99, '75.65', '2020-07-04 14:27:35', 1),
(5, 'Awesome Wooden Lamp', 'https://picsum.photos/400/400/?74277', 97, '45.55', '2020-07-04 14:27:35', 1),
(6, 'Ergonomic Copper Gloves', 'https://picsum.photos/400/400/?92834', 99, '48.95', '2020-07-04 14:27:35', 1),
(7, 'Rustic Cotton Hat', 'https://picsum.photos/400/400/?62745', 96, '84.25', '2020-07-04 14:27:35', 1),
(8, 'Durable Silk Computer', 'https://picsum.photos/400/400/?84685', 98, '54.25', '2020-07-04 14:27:35', 1),
(9, 'Small Steel Computer', 'https://picsum.photos/400/400/?35408', 94, '47.75', '2020-07-04 14:27:35', 1),
(10, 'Heavy Duty Steel Wallet', 'https://picsum.photos/400/400/?33246', 99, '39.90', '2020-07-04 14:27:35', 1);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `lignes_commande`
--
ALTER TABLE `lignes_commande`
  ADD CONSTRAINT `lignes_commande_ibfk_1` FOREIGN KEY (`paniersId`) REFERENCES `paniers` (`paniersId`),
  ADD CONSTRAINT `lignes_commande_ibfk_2` FOREIGN KEY (`produitsId`) REFERENCES `product` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
