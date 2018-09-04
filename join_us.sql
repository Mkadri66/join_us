-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  mar. 04 sep. 2018 à 13:24
-- Version du serveur :  10.1.26-MariaDB
-- Version de PHP :  7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `join_us`
--

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alt` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id`, `url`, `alt`) VALUES
(1, 'png', 'index.png'),
(2, 'png', 'avatar1_big.png'),
(3, 'png', 'GitHub-Mark.png'),
(4, 'jpeg', '20180416 Bazaruto-Archipel, Mosambik 1920x1080.jpg'),
(5, 'jpeg', '20180405 Die Ausgrabungsstätte Mada\'in Salih in Saudi-Arabien 1920x1080.jpg'),
(6, 'jpeg', '20180405 Die Ausgrabungsstätte Mada\'in Salih in Saudi-Arabien 1920x1080.jpg'),
(7, 'png', 'avatar1_big.png'),
(8, 'png', 'trapflow_logo2.png'),
(11, 'png', 'trapflow_logo2.png'),
(12, 'jpeg', 'photo_mous.jpg'),
(13, 'jpeg', 'work.jpg'),
(14, 'png', 'avatar1_big.png'),
(15, 'png', 'trapflow_logo2.png'),
(16, 'png', 'index.png'),
(17, 'jpeg', 'admin.jpg'),
(18, 'jpeg', 'admin.jpg'),
(19, 'png', 'index.png'),
(20, 'png', 'avatar1_big.png');

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `auteur_id` int(11) NOT NULL,
  `partie_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `contenu` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `partie`
--

CREATE TABLE `partie` (
  `id` int(11) NOT NULL,
  `organisateur_id` int(11) NOT NULL,
  `sport_id` int(11) NOT NULL,
  `ville_id` int(11) NOT NULL,
  `adresse` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `termine` tinyint(1) NOT NULL,
  `total_joueurs` int(11) NOT NULL,
  `horaire` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `partie`
--

INSERT INTO `partie` (`id`, `organisateur_id`, `sport_id`, `ville_id`, `adresse`, `date`, `termine`, `total_joueurs`, `horaire`) VALUES
(32, 20, 3, 2, 'city la pece', '2018-08-16', 0, 4, '20:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `partie_utilisateur`
--

CREATE TABLE `partie_utilisateur` (
  `utilisateur_id` int(11) NOT NULL,
  `partie_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `partie_utilisateur`
--

INSERT INTO `partie_utilisateur` (`utilisateur_id`, `partie_id`) VALUES
(19, 32),
(20, 32);

-- --------------------------------------------------------

--
-- Structure de la table `sport`
--

CREATE TABLE `sport` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `sport`
--

INSERT INTO `sport` (`id`, `libelle`) VALUES
(1, 'football'),
(2, 'basketball'),
(3, 'tennis'),
(4, 'padel'),
(5, 'handball');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ville_id` int(11) NOT NULL,
  `avatar_id` int(11) NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `nom`, `prenom`, `mail`, `ville_id`, `avatar_id`, `username`, `password`, `is_active`, `salt`) VALUES
(18, 'julien', 'julien', 'julien@gmail.com', 4, 18, 'julien', '$2y$13$XRAdcHQ0Ae2A4KL4oRMP8.AGL/BP15Ny1jc7hpWf2sV2grj4BZ0MO', 1, 'fd9ab0b76e96fae0350537e455f05619'),
(19, 'mous', 'mous', 'mous@gmail.com', 7, 19, 'mous', '$2y$13$oFzP0jBgs2.XPq3rcmf9weLxcx5if8omaHGmzVT/vnH9Uidjzrxnu', 1, 'e3f60b07ab5f018c2cd318b0650b6af0'),
(20, 'blabla', 'blabla', 'blabla@gmail.com', 8, 20, 'blabla', '$2y$13$yPl66ZkhbQxpsEHGIH4LJ.R3N4V/SVdfGPN/O/.P3Kz0eOjK7hkW2', 1, '2a1197f691ff167d9cebce8f5c0bd4c3');

-- --------------------------------------------------------

--
-- Structure de la table `ville`
--

CREATE TABLE `ville` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `ville`
--

INSERT INTO `ville` (`id`, `libelle`) VALUES
(2, 'Perpignan'),
(3, 'Montpellier'),
(4, 'Toulouse'),
(5, 'Lyon'),
(6, 'Paris'),
(7, 'Marseille'),
(8, 'Bordeaux'),
(9, 'Lille');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B6BD307F60BB6FE6` (`auteur_id`),
  ADD KEY `IDX_B6BD307FE075F7A4` (`partie_id`);

--
-- Index pour la table `partie`
--
ALTER TABLE `partie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_59B1F3DD936B2FA` (`organisateur_id`),
  ADD KEY `IDX_59B1F3DAC78BCF8` (`sport_id`),
  ADD KEY `IDX_59B1F3DA73F0036` (`ville_id`);

--
-- Index pour la table `partie_utilisateur`
--
ALTER TABLE `partie_utilisateur`
  ADD PRIMARY KEY (`utilisateur_id`,`partie_id`),
  ADD KEY `IDX_87F5E0FBFB88E14F` (`utilisateur_id`),
  ADD KEY `IDX_87F5E0FBE075F7A4` (`partie_id`);

--
-- Index pour la table `sport`
--
ALTER TABLE `sport`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_1D1C63B386383B10` (`avatar_id`),
  ADD UNIQUE KEY `UNIQ_1D1C63B3F85E0677` (`username`),
  ADD KEY `IDX_1D1C63B3A73F0036` (`ville_id`);

--
-- Index pour la table `ville`
--
ALTER TABLE `ville`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `partie`
--
ALTER TABLE `partie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT pour la table `sport`
--
ALTER TABLE `sport`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `ville`
--
ALTER TABLE `ville`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `FK_B6BD307F60BB6FE6` FOREIGN KEY (`auteur_id`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `FK_B6BD307FE075F7A4` FOREIGN KEY (`partie_id`) REFERENCES `partie` (`id`);

--
-- Contraintes pour la table `partie`
--
ALTER TABLE `partie`
  ADD CONSTRAINT `FK_59B1F3DA73F0036` FOREIGN KEY (`ville_id`) REFERENCES `ville` (`id`),
  ADD CONSTRAINT `FK_59B1F3DAC78BCF8` FOREIGN KEY (`sport_id`) REFERENCES `sport` (`id`),
  ADD CONSTRAINT `FK_59B1F3DD936B2FA` FOREIGN KEY (`organisateur_id`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `partie_utilisateur`
--
ALTER TABLE `partie_utilisateur`
  ADD CONSTRAINT `FK_87F5E0FBE075F7A4` FOREIGN KEY (`partie_id`) REFERENCES `partie` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_87F5E0FBFB88E14F` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `FK_1D1C63B386383B10` FOREIGN KEY (`avatar_id`) REFERENCES `image` (`id`),
  ADD CONSTRAINT `FK_1D1C63B3A73F0036` FOREIGN KEY (`ville_id`) REFERENCES `ville` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
