-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3307
-- Време на генериране: 25 май 2023 в 19:31
-- Версия на сървъра: 10.4.27-MariaDB
-- Версия на PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данни: `escapeRooms`
--

-- --------------------------------------------------------

--
-- Структура на таблица `escapeRoom`
--

CREATE TABLE `escapeRoom` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `language` varchar(2) NOT NULL,
  `difficulty` int(10) UNSIGNED NOT NULL,
  `timeLimit` int(10) UNSIGNED NOT NULL,
  `minPlayers` int(10) UNSIGNED NOT NULL,
  `maxPlayers` int(10) UNSIGNED NOT NULL,
  `image` varchar(250) NOT NULL
) ;

--
-- Схема на данните от таблица `escapeRoom`
--

INSERT INTO `escapeRoom` (`id`, `name`, `language`, `difficulty`, `timeLimit`, `minPlayers`, `maxPlayers`, `image`) VALUES
(12, 'abc', 'en', 5, 123, 1, 6, 'abracadabra'),
(13, 'abc', 'en', 5, 123, 1, 6, 'abracadabra');

-- --------------------------------------------------------

--
-- Структура на таблица `riddle`
--

CREATE TABLE `riddle` (
  `id` int(11) UNSIGNED NOT NULL,
  `type` enum('numeric','colors','shapes','rebus','directions','sorting','trivia','other') NOT NULL,
  `language` varchar(2) NOT NULL,
  `task` text NOT NULL,
  `solution` text NOT NULL,  
  `image` varchar(250) NOT NULL
) ;

--
-- Схема на данните от таблица `riddle`
--

INSERT INTO `riddle` (`id`, `type`, `language`, `task`, `solution`, `image`) VALUES
(4, '', 'gb', 'How is called a man living in Vratsa', 'Pustinqk', 'nema'),
(5, '', 'gb', 'How is called a man living in Vratsa', 'Pustinqk', 'nema');

-- --------------------------------------------------------

--
-- Структура на таблица `roomRiddle`
--

CREATE TABLE `roomRiddle` (
  `roomId` int(11) UNSIGNED NOT NULL,
  `riddleId` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Схема на данните от таблица `roomRiddle`
--

INSERT INTO `roomRiddle` (`roomId`, `riddleId`) VALUES
(12, 5);

--
-- Indexes for dumped tables
--

--
-- Индекси за таблица `escapeRoom`
--
ALTER TABLE `escapeRoom`
  ADD PRIMARY KEY (`id`);

--
-- Индекси за таблица `riddle`
--
ALTER TABLE `riddle`
  ADD PRIMARY KEY (`id`);

--
-- Индекси за таблица `roomRiddle`
--
ALTER TABLE `roomRiddle`
  ADD PRIMARY KEY (`roomId`,`riddleId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `escaperoom`
--
ALTER TABLE `escapeRoom`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `riddle`
--
ALTER TABLE `riddle`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ограничения за дъмпнати таблици
--

--
-- Ограничения за таблица `roomRiddle`
--
ALTER TABLE `roomRiddle`
  ADD CONSTRAINT `riddleFK` FOREIGN KEY (`riddleId`) REFERENCES `riddle` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `roomFK` FOREIGN KEY (`roomId`) REFERENCES `escapeRoom` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
