-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : lun. 25 sep. 2023 à 16:30
-- Version du serveur : 5.7.39
-- Version de PHP : 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `blog`
--

-- --------------------------------------------------------

--
-- Structure de la table `blogpost`
--

CREATE TABLE `blogpost` (
  `blogpost_id` int(11) NOT NULL,
  `title` tinytext NOT NULL,
  `summary` tinytext NOT NULL,
  `content` text,
  `author` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `modification_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `blogpost`
--

INSERT INTO `blogpost` (`blogpost_id`, `title`, `summary`, `content`, `author`, `creation_date`, `modification_date`) VALUES
(13, 'Les erreurs que tous les developpers junior font', 'Retrouvez les 3 erreurs les plus courantes chez les devs junior', '1- Avoir peur de demander de l’aide\r\n2- Ne pas dire quand on ne sait pas\r\n3- Le syndrome de l’imposteur', 33, '2023-09-24 08:13:59', '2023-09-24 14:48:53'),
(19, 'Les outils indispensables pour dev', 'La boîte à outils de nos développeurs aux quotidiens', 'Les développeurs sont constamment à la recherche d’outils qui peuvent améliorer leur efficacité et productivité au quotidien. Les logiciels de développement ont évolué ces dernières années pour offrir un large éventail de fonctionnalités et de capacités.', 33, '2023-09-22 13:05:11', NULL),
(20, 'Langages de programmation les mieux payés en 2023', 'Palmarès 2023 des langages amenant aux meilleurs salaires', 'Si vous voulez vous lancer dans la programmation web ou si vous désirez vous spécialiser dans un langage particulier, cela peut être une bonne idée de chercher à savoir quels sont les langages de programmation les mieux payés du moment.\r\n\r\nEn effet, le développement web est non seulement l’un des secteurs du numérique qui recrute le plus ces dernières années en France, mais il fait surtout partie de ceux qui rémunèrent le mieux.\r\n\r\nDe nouvelles technologies voient régulièrement le jour et les entreprises n’hésitent pas à mettre le prix qu’il faut pour dénicher les meilleurs talents.', 33, '2023-09-21 16:37:13', NULL),
(21, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor. Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper congue, euismod non, mi. Proin porttitor, orci nec nonummy molestie, enim est eleifend mi, non fermentum diam nisl sit amet erat. Duis semper. Duis arcu massa, scelerisque vitae, consequat in, pretium a, enim. Pellentesque congue. Ut in risus volutpat libero pharetra tempor. Cras vestibulum bibendum augue. Praesent egestas leo in pede. Praesent blandit odio eu enim. Pellentesque sed dui ut augue blandit sodales. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aliquam nibh. Mauris ac mauris sed pede pellentesque fermentum. Maecenas adipiscing ante non diam sodales hendrerit.', 33, '2023-09-20 16:11:04', NULL),
(22, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor. Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper congue, euismod non, mi. Proin porttitor, orci nec nonummy molestie, enim est eleifend mi, non fermentum diam nisl sit amet erat. Duis semper. Duis arcu massa, scelerisque vitae, consequat in, pretium a, enim. Pellentesque congue. Ut in risus volutpat libero pharetra tempor. Cras vestibulum bibendum augue. Praesent egestas leo in pede. Praesent blandit odio eu enim. Pellentesque sed dui ut augue blandit sodales. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aliquam nibh. Mauris ac mauris sed pede pellentesque fermentum. Maecenas adipiscing ante non diam sodales hendrerit.', 33, '2023-09-19 09:32:17', NULL),
(23, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor. Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper congue, euismod non, mi. Proin porttitor, orci nec nonummy molestie, enim est eleifend mi, non fermentum diam nisl sit amet erat. Duis semper. Duis arcu massa, scelerisque vitae, consequat in, pretium a, enim. Pellentesque congue. Ut in risus volutpat libero pharetra tempor. Cras vestibulum bibendum augue. Praesent egestas leo in pede. Praesent blandit odio eu enim. Pellentesque sed dui ut augue blandit sodales. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aliquam nibh. Mauris ac mauris sed pede pellentesque fermentum. Maecenas adipiscing ante non diam sodales hendrerit.', 33, '2023-09-19 09:32:17', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

CREATE TABLE `comment` (
  `comment_id` int(11) NOT NULL,
  `title` tinytext NOT NULL,
  `text` text NOT NULL,
  `author` int(11) DEFAULT NULL,
  `blogpost` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `modification_date` datetime DEFAULT NULL,
  `is_validated` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`comment_id`, `title`, `text`, `author`, `blogpost`, `creation_date`, `modification_date`, `is_validated`) VALUES
(16, 'Très intéressant', 'Merci pour ce blogpost de qualité', 33, 13, '2023-09-25 16:25:40', NULL, 1),
(17, 'Bof...', 'Ce blogpost manque un peu de substance', 33, 13, '2023-09-25 16:26:11', NULL, 1),
(18, 'Je m\'y reconnais', 'C\'est effectivement tout à fait ce que j\'ai vécu', 1, 13, '2023-09-25 16:27:24', NULL, 1),
(19, 'des supers produits', 'Venez achetr me sproduits incroyables d\'hygiène sur superProds.com', 33, 13, '2023-09-25 16:29:10', NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(255) NOT NULL,
  `roles` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`user_id`, `email`, `password`, `roles`) VALUES
(1, 'user@user.com', 'ab56b4d92b40713acc5af89985d4b786', 'user'),
(33, 'admin@admin.com', 'ab56b4d92b40713acc5af89985d4b786', 'user,admin');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `blogpost`
--
ALTER TABLE `blogpost`
  ADD PRIMARY KEY (`blogpost_id`),
  ADD KEY `blogpost_user_user_id_fk` (`author`);

--
-- Index pour la table `comment`
--
ALTER TABLE `comment`
  ADD UNIQUE KEY `comment_comment_id_uindex` (`comment_id`),
  ADD KEY `comment_user_user_id_fk` (`author`),
  ADD KEY `comment_blogpost_blogpost_id_fk` (`blogpost`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `blogpost`
--
ALTER TABLE `blogpost`
  MODIFY `blogpost_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `comment`
--
ALTER TABLE `comment`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `blogpost`
--
ALTER TABLE `blogpost`
  ADD CONSTRAINT `blogpost_user_user_id_fk` FOREIGN KEY (`author`) REFERENCES `user` (`user_id`);

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_blogpost_blogpost_id_fk` FOREIGN KEY (`blogpost`) REFERENCES `blogpost` (`blogpost_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_user_user_id_fk` FOREIGN KEY (`author`) REFERENCES `user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
