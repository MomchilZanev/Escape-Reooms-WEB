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
-- База данни: `escaperooms`
--

-- --------------------------------------------------------

--
-- Структура на таблица `escaperoom`
--

CREATE TABLE `escaperoom` (
  `room_id` int(11) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `room_lang` varchar(2) NOT NULL,
  `difficulty` int(10) UNSIGNED NOT NULL,
  `timeLimit` int(10) UNSIGNED NOT NULL,
  `minPlayers` int(10) UNSIGNED NOT NULL,
  `maxPlayers` int(10) UNSIGNED NOT NULL,
  `image` varchar(250) NOT NULL
) ;

--
-- Схема на данните от таблица `escaperoom`
--

INSERT INTO `escaperoom` (`room_id`, `name`, `room_lang`, `difficulty`, `timeLimit`, `minPlayers`, `maxPlayers`, `image`) VALUES
(12, 'abc', 'en', 5, 123, 1, 6, 'abracadabra'),
(13, 'abc', 'en', 5, 123, 1, 6, 'abracadabra');

-- --------------------------------------------------------

--
-- Структура на таблица `riddle`
--

CREATE TABLE `riddle` (
  `riddle_id` int(11) UNSIGNED NOT NULL,
  `type` enum('numeric','colors','shapes','rebus','directions','sorting','trivia','other') NOT NULL,
  `task` text NOT NULL,
  `solution` text NOT NULL,
  `riddle_lang` varchar(2) NOT NULL,
  `image` varchar(250) NOT NULL
) ;

--
-- Схема на данните от таблица `riddle`
--

INSERT INTO `riddle` (`riddle_id`, `type`, `task`, `solution`, `riddle_lang`, `image`) VALUES
(4, '', 'How is called a man living in Vratsa', 'Pustinqk', 'gb', 'nema'),
(5, '', 'How is called a man living in Vratsa', 'Pustinqk', 'gb', 'nema');

-- --------------------------------------------------------

--
-- Структура на таблица `room_riddle`
--

CREATE TABLE `room_riddle` (
  `room_id` int(11) UNSIGNED NOT NULL,
  `riddle_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Схема на данните от таблица `room_riddle`
--

INSERT INTO `room_riddle` (`room_id`, `riddle_id`) VALUES
(12, 5);

--
-- Indexes for dumped tables
--

--
-- Индекси за таблица `escaperoom`
--
ALTER TABLE `escaperoom`
  ADD PRIMARY KEY (`room_id`);

--
-- Индекси за таблица `riddle`
--
ALTER TABLE `riddle`
  ADD PRIMARY KEY (`riddle_id`);

--
-- Индекси за таблица `room_riddle`
--
ALTER TABLE `room_riddle`
  ADD PRIMARY KEY (`room_id`,`riddle_id`),
  ADD KEY `riddle_id` (`riddle_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `escaperoom`
--
ALTER TABLE `escaperoom`
  MODIFY `room_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `riddle`
--
ALTER TABLE `riddle`
  MODIFY `riddle_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Ограничения за дъмпнати таблици
--

--
-- Ограничения за таблица `room_riddle`
--
ALTER TABLE `room_riddle`
  ADD CONSTRAINT `room_riddle_ibfk_1` FOREIGN KEY (`riddle_id`) REFERENCES `riddle` (`riddle_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `room_riddle_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `escaperoom` (`room_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
