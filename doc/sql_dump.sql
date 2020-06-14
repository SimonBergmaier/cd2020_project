-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 19. Mai 2018 um 20:42
-- Server-Version: 10.1.30-MariaDB
-- PHP-Version: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `cd_2020_db`
--
CREATE DATABASE IF NOT EXISTS `cd_2020_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `cd_2020_db`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `article`
--

CREATE TABLE `article` (
  `id` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `author` int(11) NOT NULL,
  `active` bit(1) NOT NULL DEFAULT b'1',
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `article`
--

INSERT INTO `article` (`id`, `category`, `title`, `subtitle`, `text`, `author`, `active`, `creationDate`) VALUES
(1, 1, 'Title First Article', 'Subtitle First Article', 'Text First Article', 1, b'1', '2020-05-17 12:47:34'),
(2, 1, 'Title Second Article', 'Subtitle Second Article', 'Text First Article', 1, b'1', '2020-05-17 14:34:07'),
(3, 4, 'Title Third Article', 'Subtitle Third Article', 'Text First Article', 2, b'1', '2020-05-18 15:00:37'),
(4, 3, 'Title Fourth Article', 'Subtitle Fourth Article', 'Text First Article', 3, b'0', '2020-05-19 18:24:14');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Politics'),
(2, 'Sport'),
(3, 'Business'),
(4, 'Science');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `articleId` int(11) NOT NULL,
  `authorId` int(11) NOT NULL,
  `text` text NOT NULL,
  `creationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` bit(1) NOT NULL DEFAULT b'1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `comment`
--

INSERT INTO `comment` (`id`, `articleId`, `authorId`, `text`, `creationDate`, `active`) VALUES
(1, 2, 1, 'First Comment Second Article', '2020-05-19 15:23:16', b'1'),
(2, 2, 1, 'Second Comment Second Article', '2020-05-19 15:27:35', b'1'),
(3, 2, 2, 'Third Comment Second Article', '2020-05-19 16:34:31', b'1'),
(4, 3, 2, 'First Comment Third Article', '2020-05-19 17:11:28', b'1'),
(5, 2, 3, 'Fourth Comment Second Article', '2020-05-19 18:33:13', b'0');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `passhash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `username`, `passhash`) VALUES
(1, 'FirstUser', '76251a601a94e2473db21d20ef42eb0e2f2935d4'),
(2, 'SecondUser', '9a67e89075172149960404ac76ad74ac6d54961d'),
(3, 'ThirdUser', 'e97a299a7d0831ea2b4057d060a6a6e0c0034811');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`),
  ADD KEY `author` (`author`);

--
-- Indizes für die Tabelle `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `articleInd` (`articleId`),
  ADD KEY `authorInd` (`authorId`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT für Tabelle `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`category`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `article_ibfk_2` FOREIGN KEY (`author`) REFERENCES `users` (`id`);

--
-- Constraints der Tabelle `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`authorId`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`articleId`) REFERENCES `article` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
