-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas wygenerowania: 19 Kwi 2016, 14:37
-- Wersja serwera: 5.5.47-0ubuntu0.14.04.1
-- Wersja PHP: 5.5.9-1ubuntu4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Baza danych: `contacts_test`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `addresses`
--

CREATE TABLE IF NOT EXISTS `addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) DEFAULT NULL,
  `city` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `street` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `houseNumber` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `appartamentNumber` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6FCA7516E7A1254A` (`contact_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=21 ;

--
-- Zrzut danych tabeli `addresses`
--

INSERT INTO `addresses` (`id`, `contact_id`, `city`, `street`, `houseNumber`, `appartamentNumber`) VALUES
(5, 1, 'City 1', 'Some street 1', '12A/4', '123'),
(6, 2, 'Somecity', 'Somestreeet', '14a', '13#'),
(10, 3, 'Warszawa', 'al. Jerozolimskie', '101A', '13#'),
(15, 5, 'Warszawa', 'al. Jerozolimskie', '101/6', '13a'),
(16, 5, 'Gdańsk', 'ul. Neptuna', '13', NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

--
-- Zrzut danych tabeli `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `surname`, `description`) VALUES
(1, 'ZedZed', 'Zedowsky', 'A very very long desciption of all the little quirks of this individual that I wanted to keep track of for the sake of seeing how it will be displayed here. Lorem ipsum, and so on, and so on...'),
(2, 'Kyle', 'Anon', 'First!!!'),
(3, 'Imię', 'Nazwisko', 'Polskie znaki: ąęółśżźć // znaki specjalne: @#$%^&*?><{}[]'),
(4, 'NewName', 'NewSurname', 'Descripting description of described subcject'),
(5, 'Karol', 'Gżegżółka', 'Opisany opis'),
(13, 'Undescribable', 'Aaron', NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `emails`
--

CREATE TABLE IF NOT EXISTS `emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) DEFAULT NULL,
  `address` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4C81E852E7A1254A` (`contact_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Zrzut danych tabeli `emails`
--

INSERT INTO `emails` (`id`, `contact_id`, `address`, `type`) VALUES
(2, 2, 'asd@asd.com', NULL),
(3, 2, 'asd@asd.com', 'priv'),
(4, 5, 'qwertyliusz123@no.such.domain.com', 'private'),
(5, 5, 'k.gzegzolka@company.name', 'company'),
(6, 5, 'test.blank.description@test.pl', NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `fos_users`
--

CREATE TABLE IF NOT EXISTS `fos_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_32427CF792FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_32427CF7A0D96FBF` (`email_canonical`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Zrzut danych tabeli `fos_users`
--

INSERT INTO `fos_users` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `roles`, `credentials_expired`, `credentials_expire_at`) VALUES
(1, 'test', 'test', 'asd@asd', 'asd@asd', 1, 'gigzmrn80rw4swsk4gwswgw48cooss8', '$2y$13$gigzmrn80rw4swsk4gwsweEeMGUYMJIuM08MfWzE.fw1cCOeSPjOC', '2016-04-19 14:29:58', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL),
(2, 'twojastara', 'twojastara', 'twojastara@twoj.stary', 'twojastara@twoj.stary', 1, '2wgd1pmub5gkwsg4k8oo80kco8g4cc0', '$2y$13$2wgd1pmub5gkwsg4k8oo8uvMjIfROQ4PkRbs/0JNqi4DQGzuRJ6zS', NULL, 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `phones`
--

CREATE TABLE IF NOT EXISTS `phones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) DEFAULT NULL,
  `number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E3282EF5E7A1254A` (`contact_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Zrzut danych tabeli `phones`
--

INSERT INTO `phones` (`id`, `contact_id`, `number`, `type`) VALUES
(1, 2, '+48-22-8727272', 'landline'),
(2, 2, '+48-693258654', 'mobile'),
(6, 5, '0048-22-8727272', 'landline'),
(7, 5, '+48-693258654', 'mobile'),
(8, 5, 'not a nu no type', NULL);

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `FK_6FCA7516E7A1254A` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`);

--
-- Ograniczenia dla tabeli `emails`
--
ALTER TABLE `emails`
  ADD CONSTRAINT `FK_4C81E852E7A1254A` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`);

--
-- Ograniczenia dla tabeli `phones`
--
ALTER TABLE `phones`
  ADD CONSTRAINT `FK_E3282EF5E7A1254A` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
