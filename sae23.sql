-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 08, 2023 at 12:14 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sae23`
--

-- --------------------------------------------------------

--
-- Table structure for table `Administration`
--

CREATE TABLE `Administration` (
  `login` varchar(25) NOT NULL,
  `password` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Administration`
--

INSERT INTO `Administration` (`login`, `password`) VALUES
('admin', 'e4abae53cc1cebe5fe89ea93882c699a5e71ab0bbf42a83b7d833975b61c4a41');

-- --------------------------------------------------------

--
-- Table structure for table `Batiment`
--

CREATE TABLE `Batiment` (
  `id_bat` tinyint(4) NOT NULL,
  `nom_bat` varchar(1) NOT NULL,
  `login_gest` varchar(25) NOT NULL,
  `password_gest` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Batiment`
--

INSERT INTO `Batiment` (`id_bat`, `nom_bat`, `login_gest`, `password_gest`) VALUES
(1, 'B', 'gest1', '2908476d0ff2b0f6195677d324173b69ed53e397fd1d7fef206758c5516ca6a7'),
(2, 'E', 'gest2', 'b57b66b18e6689677f2c3e8b9da2f1ae4e9ffbf013c8d6da3389b0fc49bb0797'),
(4, 'C', 'azer', 'd4b3dfbf113cc8b2f6fd71bcb24b761d04b47c04a59b22a2a7db91b275542892');

-- --------------------------------------------------------

--
-- Table structure for table `Capteur`
--

CREATE TABLE `Capteur` (
  `id_capt` tinyint(4) NOT NULL,
  `id_bat` tinyint(4) NOT NULL,
  `nom_capt` varchar(25) NOT NULL,
  `type_capt` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Capteur`
--

INSERT INTO `Capteur` (`id_capt`, `id_bat`, `nom_capt`, `type_capt`) VALUES
(1, 2, 'E208-luminosité', 'luminosité'),
(2, 2, 'E208-température', 'température'),
(3, 1, 'B106-luminosité', 'luminosité'),
(4, 1, 'B106-température', 'température');

-- --------------------------------------------------------

--
-- Table structure for table `Mesure`
--

CREATE TABLE `Mesure` (
  `id_mes` mediumint(9) NOT NULL,
  `id_capt` tinyint(4) NOT NULL,
  `date_mes` date NOT NULL,
  `horaire_mes` time NOT NULL,
  `valeur_mes` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Mesure`
--

INSERT INTO `Mesure` (`id_mes`, `id_capt`, `date_mes`, `horaire_mes`, `valeur_mes`) VALUES
(1, 1, '2023-06-02', '06:14:59', '18'),
(2, 1, '2023-06-08', '06:14:59', '27'),
(3, 2, '2023-06-08', '06:15:48', '19'),
(4, 2, '2023-06-08', '06:15:48', '32'),
(5, 3, '2023-06-08', '06:16:36', '192'),
(6, 3, '2023-06-08', '06:16:36', '28'),
(7, 4, '2023-05-08', '06:16:36', '11'),
(8, 4, '2023-06-08', '06:16:36', '16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Batiment`
--
ALTER TABLE `Batiment`
  ADD PRIMARY KEY (`id_bat`);

--
-- Indexes for table `Capteur`
--
ALTER TABLE `Capteur`
  ADD PRIMARY KEY (`id_capt`),
  ADD KEY `id_bat` (`id_bat`);

--
-- Indexes for table `Mesure`
--
ALTER TABLE `Mesure`
  ADD PRIMARY KEY (`id_mes`),
  ADD KEY `id_capt` (`id_capt`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Batiment`
--
ALTER TABLE `Batiment`
  MODIFY `id_bat` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Capteur`
--
ALTER TABLE `Capteur`
  MODIFY `id_capt` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Mesure`
--
ALTER TABLE `Mesure`
  MODIFY `id_mes` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
