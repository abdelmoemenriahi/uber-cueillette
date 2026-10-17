-- Uber-Cueillette database schema
-- Personal account, offer, application and profile data removed for public portfolio use.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uber_cueillette`
--

-- --------------------------------------------------------

--
-- Table structure for table `agriculteur`
--

DROP TABLE IF EXISTS `agriculteur`;
CREATE TABLE IF NOT EXISTS `agriculteur` (
  `id_agriculteur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cin` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pseudo` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_agriculteur`),
  UNIQUE KEY `cin` (`cin`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `pseudo` (`pseudo`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- --------------------------------------------------------

--
-- Table structure for table `candidature`
--

DROP TABLE IF EXISTS `candidature`;
CREATE TABLE IF NOT EXISTS `candidature` (
  `id_candidature` int NOT NULL AUTO_INCREMENT,
  `id_offre` int NOT NULL,
  `id_ouvrier` int NOT NULL,
  `decision` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_candidature` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `note` int DEFAULT NULL,
  `commentaire` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remuneration` float DEFAULT NULL,
  PRIMARY KEY (`id_candidature`),
  KEY `id_offre` (`id_offre`),
  KEY `id_ouvrier` (`id_ouvrier`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- --------------------------------------------------------

--
-- Table structure for table `gouvernorat`
--

DROP TABLE IF EXISTS `gouvernorat`;
CREATE TABLE IF NOT EXISTS `gouvernorat` (
  `id_gouvernorat` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_gouvernorat`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gouvernorat`
--

INSERT INTO `gouvernorat` (`id_gouvernorat`, `libelle`) VALUES
(1, 'Ariana'),
(2, 'Béja'),
(3, 'Ben Arous'),
(4, 'Bizerte'),
(5, 'Gabès'),
(6, 'Gafsa'),
(7, 'Jendouba'),
(8, 'Kairouan'),
(9, 'Kasserine'),
(10, 'Kébili'),
(11, 'Le Kef'),
(12, 'Mahdia'),
(13, 'La Manouba'),
(14, 'Médenine'),
(15, 'Monastir'),
(16, 'Nabeul'),
(17, 'Sfax'),
(18, 'Sidi Bouzid'),
(19, 'Siliana'),
(20, 'Sousse'),
(21, 'Tataouine'),
(22, 'Tozeur'),
(23, 'Tunis'),
(24, 'Zaghouan');

-- --------------------------------------------------------

--
-- Table structure for table `offre`
--

DROP TABLE IF EXISTS `offre`;
CREATE TABLE IF NOT EXISTS `offre` (
  `id_offre` int NOT NULL AUTO_INCREMENT,
  `id_type_fruit` int NOT NULL,
  `id_gouvernorat` int NOT NULL,
  `adresse` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `nombre_ouvriers` int NOT NULL,
  `prix_journee` float NOT NULL,
  `date_limite` date NOT NULL,
  `id_agriculteur` int NOT NULL,
  PRIMARY KEY (`id_offre`),
  KEY `id_type_fruit` (`id_type_fruit`),
  KEY `id_gouvernorat` (`id_gouvernorat`),
  KEY `id_agriculteur` (`id_agriculteur`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- --------------------------------------------------------

--
-- Table structure for table `ouvrier`
--

DROP TABLE IF EXISTS `ouvrier`;
CREATE TABLE IF NOT EXISTS `ouvrier` (
  `id_ouvrier` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cin` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` longblob,
  `description` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pseudo` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_ouvrier`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- --------------------------------------------------------

--
-- Table structure for table `type_fruit`
--

DROP TABLE IF EXISTS `type_fruit`;
CREATE TABLE IF NOT EXISTS `type_fruit` (
  `id_type_fruit` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_type_fruit`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `type_fruit`
--

INSERT INTO `type_fruit` (`id_type_fruit`, `libelle`) VALUES
(1, 'Olives'),
(2, 'Agrumes'),
(3, 'Tomates'),
(4, 'Pommes'),
(5, 'Poires'),
(6, 'Pêches'),
(7, 'Abricots'),
(8, 'Raisins'),
(9, 'Fraises'),
(10, 'Figues');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
