-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 03 avr. 2026 à 16:05
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
-- Base de données : `dorocho_logements`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

CREATE TABLE `administrateur` (
  `idAdmin` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `login` varchar(50) NOT NULL,
  `motDePasse` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `annonce`
--

CREATE TABLE `annonce` (
  `idAnnonce` int(11) NOT NULL,
  `titre` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(10,2) DEFAULT NULL,
  `localisation` varchar(255) DEFAULT NULL,
  `estColocation` tinyint(1) DEFAULT 0,
  `datePublication` datetime DEFAULT current_timestamp(),
  `idBailleur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `bailleur`
--

CREATE TABLE `bailleur` (
  `idUtilisateur` int(11) NOT NULL,
  `estVerifie` tinyint(1) DEFAULT 0,
  `estShadowban` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `candidature`
--

CREATE TABLE `candidature` (
  `idCandidature` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `statut` varchar(50) DEFAULT 'Envoyée',
  `dateEnvoi` datetime DEFAULT current_timestamp(),
  `idEtudiant` int(11) NOT NULL,
  `idAnnonce` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE `etudiant` (
  `idUtilisateur` int(11) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `localisation` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `faq`
--

CREATE TABLE `faq` (
  `idFAQ` int(11) NOT NULL,
  `question` text NOT NULL,
  `reponse` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `favoris`
--

CREATE TABLE `favoris` (
  `idEtudiant` int(11) NOT NULL,
  `idAnnonce` int(11) NOT NULL,
  `dateAjout` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `informationlegale`
--

CREATE TABLE `informationlegale` (
  `idInfo` int(11) NOT NULL,
  `titre` varchar(255) DEFAULT NULL,
  `contenu` text DEFAULT NULL,
  `dateMiseAJour` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE `message` (
  `idMessage` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `dateEnvoi` datetime DEFAULT current_timestamp(),
  `estLu` tinyint(1) DEFAULT 0,
  `idEtudiant` int(11) NOT NULL,
  `idBailleur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `signalement`
--

CREATE TABLE `signalement` (
  `idSignalement` int(11) NOT NULL,
  `motif` text NOT NULL,
  `dateSignalement` datetime DEFAULT current_timestamp(),
  `statut` varchar(50) DEFAULT 'En attente',
  `idEtudiant` int(11) NOT NULL,
  `idAnnonce` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `idUtilisateur` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD PRIMARY KEY (`idAdmin`),
  ADD UNIQUE KEY `login` (`login`);

--
-- Index pour la table `annonce`
--
ALTER TABLE `annonce`
  ADD PRIMARY KEY (`idAnnonce`),
  ADD KEY `fk_annonce_bailleur` (`idBailleur`);

--
-- Index pour la table `bailleur`
--
ALTER TABLE `bailleur`
  ADD PRIMARY KEY (`idUtilisateur`);

--
-- Index pour la table `candidature`
--
ALTER TABLE `candidature`
  ADD PRIMARY KEY (`idCandidature`),
  ADD KEY `fk_cand_etudiant` (`idEtudiant`),
  ADD KEY `fk_cand_annonce` (`idAnnonce`);

--
-- Index pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`idUtilisateur`);

--
-- Index pour la table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`idFAQ`);

--
-- Index pour la table `favoris`
--
ALTER TABLE `favoris`
  ADD PRIMARY KEY (`idEtudiant`,`idAnnonce`),
  ADD KEY `fk_fav_annonce` (`idAnnonce`);

--
-- Index pour la table `informationlegale`
--
ALTER TABLE `informationlegale`
  ADD PRIMARY KEY (`idInfo`);

--
-- Index pour la table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`idMessage`),
  ADD KEY `fk_msg_etudiant` (`idEtudiant`),
  ADD KEY `fk_msg_bailleur` (`idBailleur`);

--
-- Index pour la table `signalement`
--
ALTER TABLE `signalement`
  ADD PRIMARY KEY (`idSignalement`),
  ADD KEY `fk_sign_etudiant` (`idEtudiant`),
  ADD KEY `fk_sign_annonce` (`idAnnonce`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`idUtilisateur`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `administrateur`
--
ALTER TABLE `administrateur`
  MODIFY `idAdmin` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `annonce`
--
ALTER TABLE `annonce`
  MODIFY `idAnnonce` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `candidature`
--
ALTER TABLE `candidature`
  MODIFY `idCandidature` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `faq`
--
ALTER TABLE `faq`
  MODIFY `idFAQ` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `informationlegale`
--
ALTER TABLE `informationlegale`
  MODIFY `idInfo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `idMessage` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `signalement`
--
ALTER TABLE `signalement`
  MODIFY `idSignalement` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `idUtilisateur` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `annonce`
--
ALTER TABLE `annonce`
  ADD CONSTRAINT `fk_annonce_bailleur` FOREIGN KEY (`idBailleur`) REFERENCES `bailleur` (`idUtilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `bailleur`
--
ALTER TABLE `bailleur`
  ADD CONSTRAINT `fk_bailleur_user` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `candidature`
--
ALTER TABLE `candidature`
  ADD CONSTRAINT `fk_cand_annonce` FOREIGN KEY (`idAnnonce`) REFERENCES `annonce` (`idAnnonce`),
  ADD CONSTRAINT `fk_cand_etudiant` FOREIGN KEY (`idEtudiant`) REFERENCES `etudiant` (`idUtilisateur`);

--
-- Contraintes pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD CONSTRAINT `fk_etudiant_user` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`idUtilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `favoris`
--
ALTER TABLE `favoris`
  ADD CONSTRAINT `fk_fav_annonce` FOREIGN KEY (`idAnnonce`) REFERENCES `annonce` (`idAnnonce`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_fav_etudiant` FOREIGN KEY (`idEtudiant`) REFERENCES `etudiant` (`idUtilisateur`) ON DELETE CASCADE;

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `fk_msg_bailleur` FOREIGN KEY (`idBailleur`) REFERENCES `bailleur` (`idUtilisateur`),
  ADD CONSTRAINT `fk_msg_etudiant` FOREIGN KEY (`idEtudiant`) REFERENCES `etudiant` (`idUtilisateur`);

--
-- Contraintes pour la table `signalement`
--
ALTER TABLE `signalement`
  ADD CONSTRAINT `fk_sign_annonce` FOREIGN KEY (`idAnnonce`) REFERENCES `annonce` (`idAnnonce`),
  ADD CONSTRAINT `fk_sign_etudiant` FOREIGN KEY (`idEtudiant`) REFERENCES `etudiant` (`idUtilisateur`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
