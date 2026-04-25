-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : sam. 25 avr. 2026 à 09:58
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `enestage`
--

-- --------------------------------------------------------

--
-- Structure de la table `affectations`
--

CREATE TABLE `affectations` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) DEFAULT NULL,
  `entreprise_id` int(11) DEFAULT NULL,
  `encadrant_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `affectations_encadrants`
--

CREATE TABLE `affectations_encadrants` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `encadrant_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `affectations_encadrants`
--

INSERT INTO `affectations_encadrants` (`id`, `etudiant_id`, `encadrant_id`) VALUES
(1, 11, 37),
(2, 10, 37),
(3, 6, 14),
(4, 6, 6),
(5, 6, 6),
(6, 10, 37),
(7, 8, 32),
(8, 6, 31),
(9, 5, 23),
(10, 8, 23),
(11, 6, 10),
(12, 6, 9),
(14, 6, 45),
(15, 6, 45),
(16, 4, 45);

-- --------------------------------------------------------

--
-- Structure de la table `affectations_entreprises`
--

CREATE TABLE `affectations_entreprises` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) DEFAULT NULL,
  `entreprise_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `affectations_entreprises`
--

INSERT INTO `affectations_entreprises` (`id`, `etudiant_id`, `entreprise_id`) VALUES
(5, 7, 2),
(6, 6, 3),
(7, 4, 1),
(8, 6, 3),
(9, 11, 1),
(10, 11, 1),
(11, 6, 6),
(15, 6, 8),
(16, 6, 36),
(17, 6, 8),
(18, 6, 33),
(19, 6, 10),
(20, 10, 30),
(21, 6, 30),
(22, 9, 30),
(23, 10, 30),
(24, 4, 30),
(25, 6, 30),
(26, 9, 30),
(27, 10, 30),
(28, 7, 8),
(30, 6, 8),
(32, 5, 33),
(35, 6, 39),
(36, 8, 36),
(37, 6, 40),
(38, 5, 40);

-- --------------------------------------------------------

--
-- Structure de la table `candidatures`
--

CREATE TABLE `candidatures` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) DEFAULT NULL,
  `offre_id` int(11) DEFAULT NULL,
  `statut` enum('en_attente','accepte','refuse') DEFAULT 'en_attente',
  `date_postulation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

CREATE TABLE `commentaires` (
  `id` int(11) NOT NULL,
  `encadrant_id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commentaires`
--

INSERT INTO `commentaires` (`id`, `encadrant_id`, `etudiant_id`, `contenu`, `date_creation`) VALUES
(1, 6, 2, 'ien', '2026-04-15 21:23:45'),
(2, 6, 2, 'nmj', '2026-04-16 04:02:30'),
(3, 6, 9, 'cool', '2026-04-16 04:37:01'),
(4, 6, 4, 'jkhljhhhjlkjh', '2026-04-18 05:55:56');

-- --------------------------------------------------------

--
-- Structure de la table `cv`
--

CREATE TABLE `cv` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) DEFAULT NULL,
  `fichier` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `cv`
--

INSERT INTO `cv` (`id`, `etudiant_id`, `fichier`) VALUES
(1, 35, 'Lettre de Motivation bien.pdf'),
(2, 21, 'LETTRE D\'ENGAGEMENT DE PRISE EN CHARGE.docx'),
(3, 24, 'EE1_2026.pdf'),
(4, 22, 'lettre prise en charge 05-04-2025 14.20.pdf'),
(5, 9, 'Certifications en Qualification de Maintenace.pdf'),
(6, 43, 'CERTIFF 2025.docx');

-- --------------------------------------------------------

--
-- Structure de la table `demandes`
--

CREATE TABLE `demandes` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `entreprise_id` int(11) NOT NULL,
  `statut` varchar(50) DEFAULT 'en attente',
  `date_demande` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `demandes`
--

INSERT INTO `demandes` (`id`, `etudiant_id`, `entreprise_id`, `statut`, `date_demande`) VALUES
(1, 21, 30, 'validé', '2026-04-22 09:54:36'),
(2, 21, 30, 'refusé', '2026-04-22 10:20:58'),
(3, 9, 30, 'validé', '2026-04-22 11:58:18'),
(4, 43, 10, 'validé', '2026-04-22 15:44:14');

-- --------------------------------------------------------

--
-- Structure de la table `demandes_stage`
--

CREATE TABLE `demandes_stage` (
  `id_demande` int(11) NOT NULL,
  `etudiant_id` varchar(100) DEFAULT NULL,
  `entreprise` varchar(100) DEFAULT NULL,
  `statut` varchar(50) DEFAULT NULL,
  `encadrant_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `encadrants`
--

CREATE TABLE `encadrants` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `specialite` varchar(100) DEFAULT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `entreprises`
--

CREATE TABLE `entreprises` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nom_entreprise` varchar(255) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mot_de_passe` varchar(100) DEFAULT NULL,
  `nom` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `entreprises`
--

INSERT INTO `entreprises` (`id`, `user_id`, `nom_entreprise`, `adresse`, `email`, `mot_de_passe`, `nom`) VALUES
(1, 8, NULL, NULL, 'QWEc@HOTMAIL.COM', '$2y$10$GYpEmpP7nKBJo0uHFTABxeyDwE0NGO.i8B1j0J43gwew54QMItFGa', 'YUa'),
(2, 10, NULL, NULL, NULL, NULL, NULL),
(3, 18, NULL, NULL, NULL, NULL, NULL),
(4, 8, NULL, NULL, NULL, NULL, NULL),
(5, 30, NULL, NULL, NULL, NULL, NULL),
(6, 36, NULL, NULL, NULL, NULL, NULL),
(7, 38, NULL, NULL, NULL, NULL, NULL),
(8, NULL, NULL, NULL, NULL, NULL, 'total'),
(10, NULL, NULL, NULL, NULL, NULL, 'simon'),
(17, NULL, NULL, NULL, NULL, NULL, 'YU'),
(18, NULL, NULL, NULL, NULL, NULL, 'YUa'),
(30, 30, NULL, NULL, NULL, NULL, 'DETERGEANT'),
(33, 33, NULL, NULL, NULL, NULL, 'TELECOM'),
(36, 36, NULL, NULL, NULL, NULL, 'entre2'),
(38, 38, NULL, NULL, NULL, NULL, 'dodosarl'),
(39, 39, NULL, NULL, NULL, NULL, 'yao'),
(40, 51, NULL, NULL, NULL, NULL, NULL),
(41, 40, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `etudiants`
--

CREATE TABLE `etudiants` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `filiere` varchar(100) DEFAULT NULL,
  `niveau` varchar(50) DEFAULT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `stage` varchar(100) DEFAULT NULL,
  `progression` int(11) DEFAULT NULL,
  `note` int(11) DEFAULT NULL,
  `encadrant_id` int(11) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `etudiant_id` varchar(100) DEFAULT NULL,
  `statut` varchar(50) DEFAULT 'en attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etudiants`
--

INSERT INTO `etudiants` (`id`, `user_id`, `filiere`, `niveau`, `cv`, `stage`, `progression`, `note`, `encadrant_id`, `prenom`, `etudiant_id`, `statut`) VALUES
(3, 16, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'en attente'),
(4, 22, 'INFO8', NULL, NULL, NULL, NULL, 3, NULL, 'Las', NULL, 'en attente'),
(5, 24, 'BRAVO', NULL, NULL, NULL, NULL, NULL, NULL, 'msd', NULL, 'en attente'),
(6, 9, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'en attente'),
(7, 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'en attente'),
(8, 25, 'kgjhgkjhg', NULL, NULL, NULL, NULL, NULL, NULL, 'bnvmnbv', NULL, 'en attente'),
(9, 28, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'en attente'),
(10, 29, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'en attente'),
(11, 35, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'en attente');

-- --------------------------------------------------------

--
-- Structure de la table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` int(11) NOT NULL,
  `note` int(11) DEFAULT NULL,
  `commentaire` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `etudiant_id` int(11) DEFAULT NULL,
  `encadrant_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `evaluations`
--

INSERT INTO `evaluations` (`id`, `note`, `commentaire`, `user_id`, `etudiant_id`, `encadrant_id`) VALUES
(1, 14, 'bien', NULL, NULL, NULL),
(2, 14, 'bien', NULL, NULL, NULL),
(3, 3, 'asd', NULL, NULL, NULL),
(4, 14, 'ESSAYE', NULL, NULL, NULL),
(5, 15, 'Bon travail', NULL, 1, NULL),
(6, 20, 'nb', NULL, 1, NULL),
(7, 12, 'kii', NULL, 2, NULL),
(8, 12, 'bv', NULL, 1, NULL),
(9, 14, '25', NULL, 3, NULL),
(10, 10, 'mn', NULL, 9, NULL),
(11, 10, 'mn', NULL, 9, NULL),
(12, 15, '', NULL, 10, NULL),
(13, 13, 'waou', NULL, 9, NULL),
(14, 12, 'good', NULL, 11, 37),
(15, 17, 'lol', NULL, 11, 37),
(16, 14, 'nhh', NULL, 6, 6),
(17, 14, 'tres bonne noete', NULL, 9, NULL),
(18, 10, 'tu as seulement 10', NULL, 21, NULL),
(19, 12, 'ik', NULL, 21, NULL),
(20, 17, 'bravo', NULL, 43, NULL),
(21, 2, '', NULL, 43, NULL),
(22, 10, 'ok', NULL, 8, 32);

-- --------------------------------------------------------

--
-- Structure de la table `offres`
--

CREATE TABLE `offres` (
  `id` int(11) NOT NULL,
  `entreprise_id` int(11) DEFAULT NULL,
  `titre` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `date_publication` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `offres`
--

INSERT INTO `offres` (`id`, `entreprise_id`, `titre`, `description`, `date_publication`) VALUES
(8, 2, 'rttf', 'wwa', '2026-04-13 14:19:22'),
(9, 2, 'merci', 'as', '2026-04-14 05:25:53'),
(10, 4, 'nouveau poste', 'disponible', '2026-04-15 08:38:56'),
(11, 2, 'le18avril', 'ok', '2026-04-18 19:07:45'),
(12, 5, 'le22avril2026', 'postuler maintenant', '2026-04-22 08:07:31'),
(13, 5, 'postulez ici mes amis', 'cest bon caa', '2026-04-22 09:07:05'),
(14, 5, 'la femme', 'ded', '2026-04-22 12:10:05'),
(15, 2, 'MONEY', 'LARGENT', '2026-04-22 15:49:46'),
(16, 2, 'fete', 'parfait', '2026-04-22 16:59:28'),
(17, 41, 'frd', 'sdsd', '2026-04-25 07:20:14');

-- --------------------------------------------------------

--
-- Structure de la table `rapports`
--

CREATE TABLE `rapports` (
  `id_rapport` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `encadrant_id` int(11) NOT NULL,
  `titre` varchar(255) DEFAULT NULL,
  `contenu` text DEFAULT NULL,
  `statut` varchar(50) DEFAULT 'en attente',
  `date_soumission` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reponses_offres`
--

CREATE TABLE `reponses_offres` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) DEFAULT NULL,
  `offre_id` int(11) DEFAULT NULL,
  `statut` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reponses_offres`
--

INSERT INTO `reponses_offres` (`id`, `etudiant_id`, `offre_id`, `statut`) VALUES
(1, 9, 8, 'accepté'),
(2, 9, 9, 'accepté'),
(3, 9, 10, 'accepté'),
(4, 9, 8, 'refusé'),
(5, 9, 9, 'refusé'),
(6, 9, 9, 'accepté'),
(7, 28, 11, 'accepté'),
(8, 35, 8, 'refusé'),
(9, 35, 8, 'refusé'),
(10, 35, 8, 'refusé'),
(11, 35, 9, 'refusé'),
(12, 29, 11, 'refusé'),
(13, 9, 12, 'accepté'),
(14, 21, 13, 'accepté'),
(15, 21, 13, 'accepté'),
(16, 21, 12, 'accepté'),
(17, 21, 11, 'refusé'),
(18, 21, 14, 'refusé'),
(19, 43, 11, 'accepté'),
(20, 43, 12, 'accepté'),
(21, 43, 14, 'accepté');

-- --------------------------------------------------------

--
-- Structure de la table `stages`
--

CREATE TABLE `stages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `entreprise_id` int(11) DEFAULT NULL,
  `encadrant_id` int(11) DEFAULT NULL,
  `statut` enum('en_cours','termine') DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `entreprise` varchar(100) DEFAULT NULL,
  `debut` date DEFAULT NULL,
  `fin` date DEFAULT NULL,
  `etudiant_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `stages`
--

INSERT INTO `stages` (`id`, `user_id`, `entreprise_id`, `encadrant_id`, `statut`, `email`, `entreprise`, `debut`, `fin`, `etudiant_id`) VALUES
(1, NULL, NULL, NULL, '', '1', 'boss', '2026-04-08', '2026-04-10', NULL),
(2, NULL, NULL, NULL, '', '9', 'SOTRACODIM', '2026-04-16', '2026-04-29', NULL),
(3, NULL, NULL, NULL, '', '9', 'ANELIA', '2026-04-11', '2026-04-27', NULL),
(4, NULL, NULL, NULL, '', '16', 'COGENI', '2026-04-13', '2026-04-30', NULL),
(5, NULL, NULL, NULL, '', '16', 'INTER', '2026-04-14', '2026-04-23', NULL),
(8, 11, NULL, NULL, '', NULL, 'SOL', '2026-04-13', '2026-04-30', NULL),
(9, NULL, NULL, NULL, '', NULL, 'PETROL', '2026-04-15', '2026-04-20', 3),
(10, NULL, NULL, NULL, '', NULL, 'jonba', '2026-04-06', '2026-04-27', 4),
(11, NULL, NULL, NULL, '', NULL, 'pooo', '2026-04-09', '2026-04-28', 3),
(12, NULL, NULL, NULL, '', NULL, 'SOMALI', '2026-04-17', '2026-04-29', 5),
(13, NULL, NULL, NULL, '', NULL, 'mascareignes', '2026-04-08', '2026-04-30', 6),
(14, NULL, NULL, NULL, '', NULL, '2', NULL, NULL, NULL),
(15, NULL, NULL, NULL, '', NULL, '30', NULL, NULL, NULL),
(16, NULL, NULL, NULL, '', NULL, '30', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','admin_sys','etudiant','entreprise','encadrant') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `etudiant_id` int(11) DEFAULT NULL,
  `cv` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `nom`, `prenom`, `email`, `password`, `role`, `created_at`, `etudiant_id`, `cv`) VALUES
(3, 'pro', 'admin', 'admin74@yahoo.fr', '$2y$10$cbD8kIXDHOHDpq0A2OVokuQPOCXENlJqNrgCVoEOKajq6MedWHsZq', 'admin', '2026-03-31 17:09:15', NULL, NULL),
(4, 'fort', 'hu', 'admin22@yahoo.com', '$2y$10$m8WImtzDAKx1n0X3lvAHLOu/dHe11FuX9C5DmDdqaPECLhzunjUxq', 'admin', '2026-03-31 20:52:30', NULL, NULL),
(6, 'mimi', 'mi', 'mimi@gmail.com', '$2y$10$1xk5Ho24fwCL21E5gaVvEu8ckZaZzK/ndv6UkMEHIPDTd.zHhoNGC', 'encadrant', '2026-04-07 08:00:04', NULL, NULL),
(7, 'pc', 'pc1', 'pc@gmail.com', '$2y$10$4.cc4PwhcNuOQAqfa.g35O8Z0E3MJNBMfebVMWX6inkmI07n2V1y.', 'encadrant', '2026-04-08 03:31:13', NULL, NULL),
(8, 'total', 'ener', 'total@gmail.com', '$2y$10$A1MyHAv7D88QiIRZH8cFl.NiR.7v1Rb5pnW.OEHGRgPmzjjB95zMK', 'entreprise', '2026-04-08 07:47:26', NULL, NULL),
(9, 'eva', 'duchelle', 'eva14@gmail.com', '$2y$10$TEtbtTBNfoEjMdQz9xUzM.8NDai1c3krAGNz4JEmgOfnVc58tRDAK', 'etudiant', '2026-04-08 07:57:46', NULL, NULL),
(10, 'simon', 'cephas', 'simon12@gmail.com', '$2y$10$QAll4ps/Xhe5B2syfljnZe2I/mmWZzJkxC4NDkhnYKroyGrEp2yzS', 'entreprise', '2026-04-12 04:35:57', NULL, NULL),
(11, 'TOLOTRA', 'RAI', 'TOLOTRA4@YAHOO.FR', '$2y$10$pmSiKTNhitm7xvzi6.ySyeTcTUUZLkxjNVls5DFFQu3w.LNBy0JFq', 'etudiant', '2026-04-12 07:06:01', NULL, NULL),
(13, 'gabon', 'gouop', 'gabon14@gmail.com', '$2y$10$4IgYxMG5mO367JKT8uDy5.mNUUGnYN6QeKx1P38c0T2JtGQIZ2Hum', 'etudiant', '2026-04-12 07:22:58', NULL, NULL),
(14, 'gabonD', 'gouop', 'gabonQ14@gmail.com', '$2y$10$e2A0gp2f2YsELbXx5hHlvOGjcWup9.zMze4q7YZZCNm4X7lOWmVEC', 'encadrant', '2026-04-12 07:28:00', NULL, NULL),
(15, 'gab', 'go', 'gaboT14@gmail.com', '$2y$10$GbI6y.Tm.aux7xL6ih/t3uJLH6cVomBwpRA9eoIPfCiLjOuncGJNi', 'etudiant', '2026-04-12 07:33:00', NULL, NULL),
(16, 'gabW', 'goA', 'gaboTA4@gmail.com', '$2y$10$pku2DwZg3UJKwMOw.DZjg.LIjW7jBQiZtDPPL0jErisflYz6v3Vl6', 'etudiant', '2026-04-12 07:39:30', NULL, NULL),
(17, 'YU', 'AS', 'QWE@HOTMAIL.COM', '$2y$10$Gbm4tnI76p0AL0lWt6foguRSKdCNRk5DiTRboLq0BQ1th6WAp07.i', 'entreprise', '2026-04-12 07:40:31', NULL, NULL),
(18, 'YUa', 'ASt', 'QWEc@HOTMAIL.COM', '$2y$10$GYpEmpP7nKBJo0uHFTABxeyDwE0NGO.i8B1j0J43gwew54QMItFGa', 'entreprise', '2026-04-12 07:44:14', NULL, NULL),
(20, 'merci', 'dieu', 'merci@gmail.com', '$2y$10$vZWqjmZHLuyrZEivD8oHjuWhrcEZ4e6/1z533AjrLruIjcM.SQNvW', 'admin', '2026-04-13 07:51:35', NULL, NULL),
(21, 'tonka', 'ernest', 'ERNEST@YAHOO.FR', '$2y$10$d19fId9rhePfZp.ZyeWkyeg7FM01pKiReOQmqEXVwzyAALkvBU.x6', 'etudiant', '2026-04-13 16:14:54', NULL, NULL),
(22, 'DOU', 'Las', 'las25@yahoo.fr', '$2y$10$ocwyqY9zWa0eyqvPunIYLO3rPxj6yWrByqq.9RjnIXH08arEJspHC', 'etudiant', '2026-04-13 16:18:28', NULL, NULL),
(23, 'phone', 'huawei30', 'phone@gmail.com', '$2y$10$sUrPIwLE0Ct8aL7YRDSEV.MJNWOiPLCwN2el5IfRFIux8CI5Dx8sa', 'encadrant', '2026-04-15 06:11:59', NULL, NULL),
(24, 'ne', 'msd', 'job@yahoo.fr', '$2y$10$0MX9sUR5rat1i1XOIpuQlOg0APJaLOf3bbGxRC4SQQd6/T1Cyr.MG', 'etudiant', '2026-04-15 06:52:27', NULL, NULL),
(25, 'jkhlkjh', 'bnvmnbv', 'uytiut@gmail.com', '$2y$10$cBVkRUxDZID5IrpbakjMpeCfBDp8qIGO8WAVJ1ISNPEPOeE9wR4Xm', 'etudiant', '2026-04-18 05:42:57', NULL, NULL),
(26, 'fiu', 'sds', 'fuit@gmail.com', '$2y$10$zaeTcULkPo47WzPdaDKVOON/xa3xpEnCKLSXiR67FCg3UDvRL/Y3W', 'admin', '2026-04-18 05:52:02', NULL, NULL),
(27, 'Admin', 'System', 'admin@sys.com', '$2y$10$btJmXERCw9iOjftWUIfc9uMxKr0xRLwvkvafqVUbfubCuQCSxugSW', 'admin_sys', '2026-04-18 21:59:24', NULL, NULL),
(28, 'FOTSO', 'ariane', 'ariane@gmail.com', '$2y$10$2YdTVBuW9zOiZu0G.khTLuvYyI58YTwFCf..ryLYUAf1UCD5bmqlS', 'etudiant', '2026-04-18 23:02:24', NULL, NULL),
(29, 'konka', 'vladimir', 'vladimir@yahoo.fr', '$2y$10$EqhmIKuxU51dEjyAA2ymIOqWoSmxxZvsEDQndDf1OX35Tn1WSRJJW', 'etudiant', '2026-04-19 03:35:24', NULL, NULL),
(30, 'DETERGEANT', NULL, 'detergeant@yahoo.fr', '$2y$10$2L0K4sO0WV/1rb1ByLrVDuzk11Ai0LQCT/LYSjSi52/oXhttiZS6i', 'entreprise', '2026-04-19 03:37:09', NULL, NULL),
(31, 'simo', NULL, 'simo@hotmail.com', '$2y$10$7acev7q6cOFS8M8mq9PX2uySUjZbFRfFXpC0mkGHvWGBGBp5qqra2', 'encadrant', '2026-04-19 05:30:03', NULL, NULL),
(32, 'zonfa', NULL, 'zonfa@gmail.com', '$2y$10$m7GcNnNk2TW5zu7dy1o4pOm9aI.7z9N7KktC7oJwKBqHOPsUKGkAi', 'encadrant', '2026-04-20 05:57:58', NULL, NULL),
(33, 'TELECOM', NULL, 'TELECOM@GMAIL.COM', '$2y$10$96WXG1UAqiheOBhSOeVaMu9Ep1ANjnH7l6.YajmpQFH0Y8MusUbHm', 'entreprise', '2026-04-20 07:10:32', NULL, NULL),
(34, 'test1', 'test123', 'test1@gmail.com', '$2y$10$jW8w83v5u3wPQ2nvXGV4K.eAQ80UuN0YOBe3v0OuFLDdCJmJPx6A6', 'etudiant', '2026-04-20 07:28:01', NULL, NULL),
(35, 'student', 'studenttete', 'student@gmail.com', '$2y$10$mpk5noxYAfWZdDgJV0xBuu9QI.BYbpuuiQObGlIKuVFg9bY0yzJ96', 'etudiant', '2026-04-20 08:27:19', NULL, NULL),
(36, 'entre2', NULL, 'entrep2@gmail.com', '$2y$10$lJAMv/d6UwwMX7JHciXe1O3VM9UH3pfx5RRhl2Izsvj5SvglPkwJu', 'entreprise', '2026-04-20 08:51:52', NULL, NULL),
(37, 'enca', NULL, 'wind@gmail.com', '$2y$10$L4aIGTAPVtiFIAH4vdSZCepowOvnq5UBG.EMNZ3epg9dCEca6iSvi', 'encadrant', '2026-04-20 08:56:01', NULL, NULL),
(38, 'dodosarl', NULL, 'dodo@gmail.com', '$2y$10$t1YRrnwAjE1ElV/A5/5NjOYydSOaTUIPYjfIoyWFIffXmjGxluCUy', 'entreprise', '2026-04-20 09:36:49', NULL, NULL),
(39, 'yao', NULL, 'yaout@gmail.com', '$2y$10$A2YTV8Q/pcLOORXAwTGNZee6Cfdi8Ll1OmAeTaCpBIjzedblEgY9O', 'entreprise', '2026-04-20 14:05:41', NULL, NULL),
(40, 'entreprise2', NULL, 'entrep3@jun.com', '$2y$10$x0H.UwLPT0MxfCxWdywDdetu8AirvAWWT2EaNtKTF.8mo56YpLTNe', 'entreprise', '2026-04-22 14:29:38', NULL, NULL),
(41, 'entr3', NULL, 'entreprise4@kl.fr', '$2y$10$D0iZ5q0/Oz5D5nHqKLwxUuwAUaEKXZVGAX8vpJ2NgWYBNsi9yzDpO', 'entreprise', '2026-04-22 14:31:19', NULL, NULL),
(42, 'simon12', 'cephas', 'simon24@gmail.com', '$2y$10$O5GwbTRQXL/T98z18V6B7.U55c5xWQq1BNe395ApwZuNIXXsFzoQ.', 'etudiant', '2026-04-22 15:32:17', NULL, NULL),
(43, 'mawa', 'FIONA', 'MAWAFIONA@gmail.com', '$2y$10$Jv/sYQikDQxMnRcayt.icuBGDyRf/A7BAusrPFuF6Fn9DD57izoNS', 'etudiant', '2026-04-22 15:42:00', NULL, NULL),
(44, 'TUTO', 'P', 'PTUTO@YAHOO.FR', '$2y$10$tDzK4Cj1pJXlTcywHop/MO1sjuvVx3FSQYFvAZIG7.58W4RP2OvqW', 'etudiant', '2026-04-25 05:45:52', NULL, NULL),
(45, 'ENCADREUR', NULL, 'ENCADREUR@HOTMAIL.COM', '$2y$10$0XRXas51na2QSvDc8YPbIOyo82q941hSKN8zpxrmJR9a0kQ0h3Srq', 'encadrant', '2026-04-25 05:48:01', NULL, NULL),
(46, 'ENTREPRISE', NULL, 'ENTREPRISE@GMAIL.COM', '$2y$10$0vVSj8WH6r9an6BimDq2S.2jlOikY4BDjDGEb4Qk.RrJoo3Qa1c4e', 'entreprise', '2026-04-25 05:49:46', NULL, NULL),
(47, 'lalalal', 'la14', 'MAINTENANT@GMAIL.COM', '$2y$10$tj8yikmRvY8tzzMCE2Tk7u1OtwUZ1QAuGFcD6zvLJxnkPCc2zKp9a', 'etudiant', '2026-04-25 06:27:08', NULL, NULL),
(48, 'fraaaaaaa', 's', 'francv@yahoo.fr', '$2y$10$RUbp4K0hdb9LsY1oT/FQFOl81ZG3wg7maHdVWI3zGDTzgZijE..Tm', 'etudiant', '2026-04-25 06:35:23', NULL, NULL),
(49, 'CE DAY', 'MEME', 'CEDE@gmail.com', '$2y$10$OXVj5HLpkawwHOP/6s978eUlsGKiqcakiId.4WXFTCdzopdCqth3y', 'etudiant', '2026-04-25 06:57:34', NULL, NULL),
(50, 'entrepo', NULL, 'entrepo3@gmail.com', '$2y$10$tGyOKapD3JgB7Yub.aomleibPBP9e5Zkb6R3CCE3n7vhfFo3tj2DO', 'entreprise', '2026-04-25 07:10:55', NULL, NULL),
(51, 'entre3333', NULL, 'entr@gmail.com', '$2y$10$Vn228nAWQZBEOMTAHP3vmeixF6JJdXZs.IO0XEy7YNXwS3CMMbp8G', 'entreprise', '2026-04-25 07:18:07', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id_user` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `statut` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `mot_de_passe` varchar(255) DEFAULT NULL,
  `id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_user`, `nom`, `email`, `role`, `statut`, `password`, `mot_de_passe`, `id`) VALUES
(1, 'Admin', 'admin@mail.com', 'admin', 'actif', NULL, '1234', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `affectations`
--
ALTER TABLE `affectations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `affectations_encadrants`
--
ALTER TABLE `affectations_encadrants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etudiant_id` (`etudiant_id`),
  ADD KEY `encadrant_id` (`encadrant_id`);

--
-- Index pour la table `affectations_entreprises`
--
ALTER TABLE `affectations_entreprises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etudiant_id` (`etudiant_id`),
  ADD KEY `entreprise_id` (`entreprise_id`);

--
-- Index pour la table `candidatures`
--
ALTER TABLE `candidatures`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etudiant_id` (`etudiant_id`),
  ADD KEY `offre_id` (`offre_id`);

--
-- Index pour la table `commentaires`
--
ALTER TABLE `commentaires`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cv`
--
ALTER TABLE `cv`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `demandes`
--
ALTER TABLE `demandes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `demandes_stage`
--
ALTER TABLE `demandes_stage`
  ADD PRIMARY KEY (`id_demande`);

--
-- Index pour la table `encadrants`
--
ALTER TABLE `encadrants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `entreprises`
--
ALTER TABLE `entreprises`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `etudiants`
--
ALTER TABLE `etudiants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Index pour la table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `offres`
--
ALTER TABLE `offres`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entreprise_id` (`entreprise_id`);

--
-- Index pour la table `rapports`
--
ALTER TABLE `rapports`
  ADD PRIMARY KEY (`id_rapport`);

--
-- Index pour la table `reponses_offres`
--
ALTER TABLE `reponses_offres`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `stages`
--
ALTER TABLE `stages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etudiant_id` (`user_id`),
  ADD KEY `entreprise_id` (`entreprise_id`),
  ADD KEY `encadrant_id` (`encadrant_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_etudiant` (`etudiant_id`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `affectations`
--
ALTER TABLE `affectations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `affectations_encadrants`
--
ALTER TABLE `affectations_encadrants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `affectations_entreprises`
--
ALTER TABLE `affectations_entreprises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT pour la table `candidatures`
--
ALTER TABLE `candidatures`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commentaires`
--
ALTER TABLE `commentaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `cv`
--
ALTER TABLE `cv`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `demandes`
--
ALTER TABLE `demandes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `demandes_stage`
--
ALTER TABLE `demandes_stage`
  MODIFY `id_demande` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `encadrants`
--
ALTER TABLE `encadrants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `entreprises`
--
ALTER TABLE `entreprises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT pour la table `etudiants`
--
ALTER TABLE `etudiants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT pour la table `offres`
--
ALTER TABLE `offres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `rapports`
--
ALTER TABLE `rapports`
  MODIFY `id_rapport` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reponses_offres`
--
ALTER TABLE `reponses_offres`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `stages`
--
ALTER TABLE `stages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `affectations_encadrants`
--
ALTER TABLE `affectations_encadrants`
  ADD CONSTRAINT `affectations_encadrants_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `affectations_encadrants_ibfk_2` FOREIGN KEY (`encadrant_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `affectations_entreprises`
--
ALTER TABLE `affectations_entreprises`
  ADD CONSTRAINT `affectations_entreprises_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`),
  ADD CONSTRAINT `affectations_entreprises_ibfk_2` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`);

--
-- Contraintes pour la table `candidatures`
--
ALTER TABLE `candidatures`
  ADD CONSTRAINT `candidatures_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`),
  ADD CONSTRAINT `candidatures_ibfk_2` FOREIGN KEY (`offre_id`) REFERENCES `offres` (`id`);

--
-- Contraintes pour la table `encadrants`
--
ALTER TABLE `encadrants`
  ADD CONSTRAINT `encadrants_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `entreprises`
--
ALTER TABLE `entreprises`
  ADD CONSTRAINT `entreprises_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `etudiants`
--
ALTER TABLE `etudiants`
  ADD CONSTRAINT `etudiants_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `offres`
--
ALTER TABLE `offres`
  ADD CONSTRAINT `offres_ibfk_1` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`);

--
-- Contraintes pour la table `stages`
--
ALTER TABLE `stages`
  ADD CONSTRAINT `stages_ibfk_2` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`),
  ADD CONSTRAINT `stages_ibfk_3` FOREIGN KEY (`encadrant_id`) REFERENCES `encadrants` (`id`);

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_etudiant` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
