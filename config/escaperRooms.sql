SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `escapeRoom` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `difficulty` int(10) UNSIGNED NOT NULL,
  `timeLimit` int(10) UNSIGNED NOT NULL,
  `minPlayers` int(10) UNSIGNED NOT NULL,
  `maxPlayers` int(10) UNSIGNED NOT NULL,
  `image` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `riddle` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` enum('numeric','colors','shapes','rebus','directions','sorting','trivia','other') NOT NULL,
  `image` varchar(500) NOT NULL,
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
  (5, 90, 2, 3, 'https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fwallpaperaccess.com%2Ffull%2F3435812.jpg&f=1&nofb=1&ipt=4edf11eafe48708946ccf6f59402034b39e259af5213f0cc570b2050f9b05a72&ipo=images'),
  (6, 120, 4, 7, 'https://external-content.duckduckgo.com/iu/?u=http%3A%2F%2Fen.protothema.gr%2Fwp-content%2Fuploads%2F2017%2F07%2FEnigma-machine.jpg&f=1&nofb=1&ipt=f36bae70838b81e3ab111845d260e47665192151da1fcf10bc5a6a40b78f4406&ipo=images'),
  (7, 150, 5, 7, 'https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fimages.fineartamerica.com%2Fimages%2Fartworkimages%2Fmediumlarge%2F1%2Fsunset-sand-timer-maria-dryfhout.jpg&f=1&nofb=1&ipt=253833e6aa68af62223389b0325a3acaa00411229bda8627526d124d01a93469&ipo=images'),
  (4, 60, 3, 4, 'https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Farchinect.imgix.net%2Fuploads%2Fc8%2Fc850df04337659dbb6a14ef03ca82d6e%3Ffit%3Dcrop%26auto%3Dcompress%252Cformat%26w%3D1200&f=1&nofb=1&ipt=0e5ad05b265d0daf577f06778d5a4efeec3e2a4a847f10381cec4fbd29779dd9&ipo=images'),
  (8, 180, 7, 9, 'https://external-content.duckduckgo.com/iu/?u=http%3A%2F%2Fgallery.nen.gov.uk%2Fassets%2F0709%2F0000%2F0714%2Fdscf3168.jpg&f=1&nofb=1&ipt=d564d39e4059d8d64c200336c4d183669d667ee60691d5833bdb11caf6b7cce4&ipo=images'),
  (3, 45, 2, 2, 'https://external-content.duckduckgo.com/iu/?u=http%3A%2F%2F2.bp.blogspot.com%2F-_b7pBg3IRVs%2FTq1kloPo-GI%2FAAAAAAAALJ0%2FlTMw00iW72k%2Fs1600%2F4774009273_a448c90abf_z.jpg&f=1&nofb=1&ipt=e5cfbd7334b68ccf860c361c0720fb0637b129a03162c9f753ddf113e2f16542&ipo=images'),
  (9, 120, 1, 2, 'https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fs-media-cache-ak0.pinimg.com%2Foriginals%2F7e%2F82%2F22%2F7e8222ae90e9a053055a7958a5848de9.jpg&f=1&nofb=1&ipt=847d6eae137b3ef87ae67109fed4263fbcdcbc1eefaef2bd04cec9c1f8a6975a&ipo=images'),
  (2, 240, 4, 8, 'https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fi.pinimg.com%2Foriginals%2Fa8%2Fca%2Fad%2Fa8caadd1b3136b46c6e62873f527a01c.jpg&f=1&nofb=1&ipt=ddb49db80cb87bac8d268f082d342157b39a558663bbeeff609078a164161cdd&ipo=images');

INSERT INTO `escapeRoomTranslation`
  (`roomId`, `language`, `name`) 
VALUES
  (1, 'en', 'Sixth Sense'),
  (1, 'bg', 'Шесто Чувтсво'),
  (2, 'en', 'Enigma'),
  (2, 'bg', 'Енигма'),
  (3, 'en', 'Out of Time'),
  (3, 'bg', 'Без Време'),
  (4, 'en', 'Detention'),
  (4, 'bg', 'Наказание'),
  (5, 'en', 'Prison Break'),
  (5, 'bg', 'Бягство от Затвора'),
  (6, 'en', 'Haunted Hotel'),
  (6, 'bg', 'Призрачен Хотел'),
  (7, 'en', 'Fatal Error'),
  (7, 'bg', 'Пагубна Грешка'),
  (8, 'en', 'Escape Reality'),
  (8, 'bg', 'Избягай от Реалността');

INSERT INTO `riddle`
  (`type`, `image`) 
VALUES
  ('numeric', 'https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fwww.publicdomainpictures.net%2Fpictures%2F150000%2Fvelka%2Fnumerical-keypad.jpg&f=1&nofb=1&ipt=674f6cf061d1f3be373c91ab5af823797c41783cc79f9b5e0913a8c9d0fd3327&ipo=images'),
  ('colors', 'https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fsplurgefrugal.com%2Fwp-content%2Fuploads%2Fdull-colors.jpg&f=1&nofb=1&ipt=ebb226edfe2053cc24c2cf481109ee392c06cb593aae2af98cceeaaa57f3cdfa&ipo=images'),
  ('shapes', 'https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fandroidhdwallpapers.com%2Fmedia%2Fuploads%2F2017%2F08%2FMulticolored-Polygons-Dark-Pattern-Art-Abstract.jpg&f=1&nofb=1&ipt=bcc83779965b309cdb9ea8181a22a5ed71112042eb70f9105571e50c3dcb56e9&ipo=images'),
  ('rebus', 'https://eslvault.com/wp-content/plugins/phastpress/phast.php/c2VydmljZT1pbWFnZXMmc3JjPWh0dHBzJTNBJTJGJTJGZXNsdmF1bHQuY29tJTJGd3AtY29udGVudCUyRnVwbG9hZHMlMkYyMDIyJTJGMTAlMkZhZHVsdC1yZWJ1cy1wdXp6bGVzLTE4LmpwZyZjYWNoZU1hcmtlcj0xNjc0MzA2MTE5LTQ4Njg2JnRva2VuPTQ3ZmM1MWFmNjY3NTZiODg.q.jpg'),
  ('directions', 'https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fcoolappsman.com%2Fwp-content%2Fuploads%2F2014%2F07%2Fswipe-the-arrows-3.jpg&f=1&nofb=1&ipt=fce59c585b21d10f6540c9b37001415608af6947bda0807f98e1a1d1417a4f08&ipo=images'),
  ('sorting', 'https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fi.pinimg.com%2Foriginals%2Fad%2F66%2Fc4%2Fad66c43d8e42bdb165c38532f35167c8.png&f=1&nofb=1&ipt=19c596c4ebc5c47b4e7c7e7ea71f331c58fc26c6d1e0ebda70e0acadb7ddde5e&ipo=images'),
  ('trivia', 'https://external-content.duckduckgo.com/iu/?u=http%3A%2F%2Fclipart-library.com%2Fimages%2FrTjrd7Xyc.jpg&f=1&nofb=1&ipt=f17ea9f4b3b04b38c84b63c1834b520edb04295f9b7127da3539e5bcfb846587&ipo=images'),
  ('other', 'https://external-content.duckduckgo.com/iu/?u=https%3A%2F%2Fstatic8.depositphotos.com%2F1000143%2F817%2Fi%2F450%2Fdepositphotos_8176967-stock-photo-miscellaneous-object-in-cargo-office.jpg&f=1&nofb=1&ipt=67f8a29d917dfb9ab81f032a69a9a4fef0fe5860f308f8f682314c600887b6d7&ipo=images');

INSERT INTO `riddleTranslation`
  (`riddleId`, `language`, `task`, `solution`) 
VALUES
  (1, 'en', 'What is the smallest number that increases by 12 when it is flipped and turned upside down?', 'The answer is 86. When it is turned upside down and flipped, it becomes 98, which is 12 more than 86.'),
  (1, 'bg', 'Кое е най-малкото число, което се увеличава с 12 когато е обърнато и завъртяно с главата надолу', 'Отговора е 86. Когато се обърне и завърти с главата надолу, то става 98, а 98 е равно на 12 плюс 86.'),
  (2, 'en', 'What is black when you buy it, red when you use it, and gray when you throw it away?', 'Charcoal'),
  (2, 'bg', 'Какво е черно когато го купиш, червено докато го използваш и сиво когато го изхвърлиш?', 'Въглен'),
  (3, 'en', 'What is the same at the beginning, the same at the end but has no middle?', 'A ring'),
  (3, 'bg', 'Какво има еднакви начало и край, но няма среда?', 'Пръстен'),
  (4, 'en', 'Can you guess the words and expressions?', '1 – Capacitator; 2 – Integrate; 3 – Noisy; 4 – No one to call; 5 – Fishwife;6 – Escape; 7 – Seasoning; 8 – Spacesuit; 9 – Wide awake; 10 – Limestone; 11 – Snowed; 12 – Canoe'),
  (4, 'bg', 'Можете ли да разпознаете думите и изразите?', '1 – Кондензатор; 2 – Интегрирам; 3 – Шумно; 4 – Няма на кого да се обадя; 5 – Продавачка на Риба;6 – Бягство; 7 – Подправка; 8 – Скафандър; 9 – Напълно Буден; 10 – Варовик; 11 – Заснежен; 12 – Кану'),
  (5, 'en', 'There is panel with 4 arrow buttons on it and a locked cabinet below. What is the correct order to open the lock?', 'Right; Up; Left; Right; Down'),
  (5, 'bg', 'Има панел с 4 стрелки на него и заключено чекмедже под панела. Коя е комбинацията, която ще отключи чекмеджето?', 'Дясно; Горе; Ляво; Дясно; Долу'),
  (6, 'en', '3 is somewhere to the right of 2. 4 is not directly next to 1, 2, 5, or 6. 5 is somewhere to the left of 2. 6 is somewhere in between 1 and 3. 2 is somewhere to the left of 1.', '5, 2, 1, 6, 3, 4'),
  (6, 'bg', '3 е някъде вдясно от 2. 4 не е непосредствено до 1, 2, 5 или 6. 5 е някъде вляво от 2. 6 е някъде между 1 и 3. 2 е някъде вляво от 1 .', '5, 2, 1, 6, 3, 4'),
  (7, 'en', 'What 1949 science fiction book by author George Orwell describes a dystopian world in the future?', '1984'),
  (7, 'bg', 'Каква научнофантастична книга от 1949 г. на автора Джордж Оруел описва един дистопичен свят в бъдещето', '1984'),
  (8, 'en', 'A truck driver is going opposite traffic on a one-way street. A police officer sees him but does not stop him. Why did the police officer not stop him?', 'He was walking'),
  (8, 'bg', 'Шофьор на камион се движи срещу движение по еднопосочна улица. Полицай го вижда, но не го спира. Защо полицаят не го спря?', 'Той ходеше');

INSERT INTO `roomRiddle` 
  (`roomId`, `riddleId`)
VALUES
  (1, 1),
  (1, 2),
  (1, 3),
  (1, 4),
  (1, 5),
  (1, 6),
  (1, 7),
  (1, 8),
  (2, 1),
  (2, 2),
  (2, 3),
  (2, 4),
  (2, 5),
  (2, 6),
  (2, 7),
  (2, 8),
  (3, 1),
  (3, 2),
  (3, 3),
  (3, 4),
  (3, 5),
  (3, 6),
  (3, 7),
  (3, 8),
  (4, 1),
  (4, 2),
  (4, 3),
  (4, 4),
  (4, 5),
  (4, 6),
  (4, 7),
  (4, 8),
  (5, 1),
  (5, 2),
  (5, 3),
  (5, 4),
  (5, 5),
  (5, 6),
  (5, 7),
  (5, 8),
  (6, 1),
  (6, 2),
  (6, 3),
  (6, 4),
  (6, 5),
  (6, 6),
  (6, 7),
  (6, 8),
  (7, 1),
  (7, 2),
  (7, 3),
  (7, 4),
  (7, 5),
  (7, 6),
  (7, 7),
  (7, 8),
  (8, 1),
  (8, 2),
  (8, 3),
  (8, 4),
  (8, 5),
  (8, 6),
  (8, 7),
  (8, 8);
COMMIT;