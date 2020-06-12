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
-- Datenbank: `fh_2018_web4_1610458007`
--
CREATE DATABASE IF NOT EXISTS `fh_2018_web4_1610458007` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `fh_2018_web4_1610458007`;

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
(1, 1, 'Test', 'First Test Article', 'This is a first Test Article, to see if the Database Connection works...', 1, b'1', '2018-05-17 12:47:34'),
(2, 1, 'Second Test Article', 'To see if the ordering is working', 'Just relax and let it flow. That easy. Now, we\'re going to fluff this cloud. That\'s a crooked tree. We\'ll send him to Washington. The man who does the best job is the one who is happy at his job. Almost everything is going to happen for you automatically - you don\'t have to spend any time working or worrying.\r\n\r\nYou\'ve got to learn to fight the temptation to resist these things. Just let them happen. Maybe we got a few little happy bushes here, just covered with snow. Get away from those little Christmas tree things we used to make in school. Every time you practice, you learn more.\r\n\r\nPut your feelings into it, your heart, it\'s your world. Be brave. Now we\'ll take the almighty fan brush. You have to make those little noises or it won\'t work. Everything is happy if you choose to make it that way.', 1, b'1', '2018-05-17 14:34:07'),
(3, 4, 'New Test Article', 'New Article from Simon', 'Test Test', 2, b'1', '2018-05-18 15:00:37'),
(4, 3, 'TestArtikel', 'Dies ist ein Testartikel für die Dokumentation der Website', '<h4>Bob Ross Ipsum</h4>\r\n\r\n<b>Use absolutely no pressure.</b> Just like an angel\'s wing. We wash our brush with odorless thinner. Almost everything is going to happen for you automatically - you don\'t have to spend any time working or worrying. With something so strong, a little bit can go a long way. Let all these little things happen. Don\'t fight them. Learn to use them. Isn\'t it fantastic that you can change your mind and create all these happy <i>things?</i> If you don\'t like it - change it. It\'s your world.\r\n\r\nI think there\'s an artist hidden in the bottom of every single one of us. Think about a cloud. Just float around and be there. The first step to doing anything is to believe you can do it. See it finished in your mind before you ever start. Maybe he has a little friend that lives right over here. Brown is such a nice color.\r\n\r\nI really believe that if you practice enough you could paint the \'Mona Lisa\' with a two-inch brush. See how easy it is to create a little tree right in your world. Now then, let\'s play. Poor old tree. Just let go - and fall like a little waterfall. A big strong tree needs big strong roots. It\'s beautiful - and we haven\'t even done anything to it yet.\r\n\r\nPut it in, leave it alone. Just let your mind wander and enjoy. This should make you happy. Everyone needs a friend. Friends are the most valuable things in the world. Let all these things just sort of happen. This painting comes right out of your heart. A tree needs to be your friend if you\'re going to paint him.\r\n\r\n', 3, b'0', '2018-05-19 18:24:14');

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
(1, 2, 1, 'Testcomment', '2018-05-19 15:23:16', b'1'),
(2, 2, 1, 'a second Testcomment', '2018-05-19 15:27:35', b'1'),
(3, 2, 2, 'This is a third Testcomment from a different <b>User</b>', '2018-05-19 16:34:31', b'1'),
(4, 3, 2, 'This is a Testcomment on this article', '2018-05-19 17:11:28', b'1'),
(5, 2, 3, 'Dies ist ein Testkommentar, auch <i>hier</i> sind wieder <b>HTML-Tags</b> möglich!', '2018-05-19 18:33:13', b'0');

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
(1, 'scm4', 'a8af855d47d091f0376664fe588207f334cdad22'),
(2, 'simonbergmaier', '7053b56daad84def593f88d15733b94982e51d82'),
(3, 'testuser', '684c600a02cd64a623672f2a597cf69b58ba4ce5');

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
