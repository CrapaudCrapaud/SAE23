-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 30, 2023 at 07:44 AM
-- Server version: 8.0.33-0ubuntu0.22.04.2
-- PHP Version: 8.1.2-1ubuntu2.11

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
  `login` varchar(255) NOT NULL,
  `password` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Batiment`
--

CREATE TABLE `Batiment` (
  `id_bat` int NOT NULL,
  `nom_bat` varchar(255) NOT NULL,
  `login_gest` varchar(255) NOT NULL,
  `password_gest` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Capteur`
--

CREATE TABLE `Capteur` (
  `id_capt` int NOT NULL,
  `id_bat` int NOT NULL,
  `nom_capt` varchar(255) NOT NULL,
  `type_capt` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Mesure`
--

CREATE TABLE `Mesure` (
  `id_mes` int NOT NULL,
  `id_capt` int NOT NULL,
  `date_mes` date NOT NULL,
  `horarire_mes` time NOT NULL,
  `valeur_mes` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  MODIFY `id_bat` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Capteur`
--
ALTER TABLE `Capteur`
  MODIFY `id_capt` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Mesure`
--
ALTER TABLE `Mesure`
  MODIFY `id_mes` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Capteur`
--
ALTER TABLE `Capteur`
  ADD CONSTRAINT `Capteur_ibfk_1` FOREIGN KEY (`id_bat`) REFERENCES `Batiment` (`id_bat`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Mesure`
--
ALTER TABLE `Mesure`
  ADD CONSTRAINT `Mesure_ibfk_1` FOREIGN KEY (`id_capt`) REFERENCES `Capteur` (`id_capt`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
