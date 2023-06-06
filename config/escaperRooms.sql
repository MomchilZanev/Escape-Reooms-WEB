SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `escapeRoom` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `difficulty` int(10) UNSIGNED NOT NULL,
  `timeLimit` int(10) UNSIGNED NOT NULL,
  `minPlayers` int(10) UNSIGNED NOT NULL,
  `maxPlayers` int(10) UNSIGNED NOT NULL,
  `image` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `riddle` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` enum('numeric','colors','shapes','rebus','directions','sorting','trivia','other') NOT NULL,
  `image` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `escapeRoomTranslation` (
  `roomId` int(11) UNSIGNED NOT NULL,
  `language` varchar(2) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`roomId`, `language`),
  FOREIGN KEY (`roomId`) REFERENCES `escapeRoom` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `riddleTranslation` (
  `riddleId` int(11) UNSIGNED NOT NULL,
  `language` varchar(2) NOT NULL,
  `task` text NOT NULL,
  `solution` text NOT NULL,
  PRIMARY KEY (`riddleId`, `language`),
  FOREIGN KEY (`riddleId`) REFERENCES `riddle` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `roomRiddle` (
  `roomId` int(11) UNSIGNED NOT NULL,
  `riddleId` int(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`roomId`,`riddleId`),
  FOREIGN KEY (`roomId`) REFERENCES `escapeRoom` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`riddleId`) REFERENCES `riddle` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO `escapeRoom`
  (`difficulty`, `timeLimit`, `minPlayers`, `maxPlayers`, `image`) 
VALUES
  (5, 90, 1, 3, 'https://escapetheroom.com/wp-content/uploads/2018/11/escape-the-room-head.jpg');

INSERT INTO `escapeRoomTranslation`
  (`roomId`, `language`, `name`) 
VALUES
  (1, 'en', 'Dummy escape room'),
  (1, 'bg', 'Примерна ескейп стая');

INSERT INTO `riddle`
  (`type`, `image`) 
VALUES
  ('other', 'https://teambuilding.com/wp-content/uploads/2020/11/escape-room-puzzle-2.jpg'),
  ('other', 'https://teambuilding.com/wp-content/uploads/2020/11/escape-room-puzzle-4.jpg');

INSERT INTO `riddleTranslation`
  (`riddleId`, `language`, `task`, `solution`) 
VALUES
  (1, 'en', 'Dummy task 1', 'Dummy solution 1'),
  (1, 'bg', 'Примерна задача 1', 'Примерно решение 1'),
  (2, 'en', 'Dummy task 2', 'Dummy solution 2'),
  (2, 'bg', 'Примерна задача 2', 'Примерно решение 2');

INSERT INTO `roomRiddle` 
  (`roomId`, `riddleId`)
VALUES
  (1, 1),
  (1, 2);

COMMIT;