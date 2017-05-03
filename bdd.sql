-- phpMyAdmin SQL Dump
-- version 4.7.0-beta1
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Lun 13 Mars 2017 à 00:03
-- Version du serveur :  5.6.35
-- Version de PHP :  7.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `oswa_inv`
--

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `id` int(11) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `artist` varchar(255) NOT NULL,
  `quantity` varchar(50) DEFAULT NULL,
  `price` decimal(25,2) NOT NULL,
  `categorie` enum('Rock','Pop','Electro','Rap') NOT NULL,
  `cover` varchar(255) NOT NULL,
  `statut` varchar(255) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `products`
--

INSERT INTO `products` (`id`, `title`, `artist`, `quantity`, `price`, `categorie`, `cover`, `statut`, `date`) VALUES
(18, 'Mahler: Symphony No. 3', 'Barbirolli', '1000', '10.99', 'Rock', 'https://images-na.ssl-images-amazon.com/images/I/310YEW2KWYL.jpg', 'En stock', '2017-03-12'),
(19, 'Motets O Maria', 'CHARPENTIER', '200', '10.60', 'Rap', 'https://images-na.ssl-images-amazon.com/images/I/41NfFtSWGCL.jpg', 'Approvisionnement en cours', '2017-03-12'),
(20, 'L\'archange &amp; Le Lys', 'Ensemble Correspondances', '10', '2.00', 'Pop', 'https://images-na.ssl-images-amazon.com/images/I/41ZmXfnSJ9L.jpg', 'En rupture de stock chez le fournisseur', '2017-03-12'),
(21, 'Charpentier: Litanies de la Vierge, Motets pour la Maison de Guise', 'Ensemble Correspondances', '10', '20.00', 'Pop', 'https://images-na.ssl-images-amazon.com/images/I/51LM8Tukz7L.jpg', 'En stock', '2017-03-12');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
