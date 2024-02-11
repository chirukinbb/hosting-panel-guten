-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июн 19 2022 г., 19:09
-- Версия сервера: 5.7.33
-- Версия PHP: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `hosting`
--

-- --------------------------------------------------------

--
-- Структура таблицы `tables`
--

CREATE TABLE `tables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `table_class` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `object` blob NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `broadcaster_class` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `removed_job_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `tables`
--

INSERT INTO `tables` (`id`, `table_class`, `object`, `deleted_at`, `created_at`, `updated_at`, `status`, `broadcaster_class`, `removed_job_id`) VALUES
(46, 'App\\Game\\Tables\\HoldemTwoPokerTable', 0x4f3a33353a224170705c47616d655c5461626c65735c486f6c64656d54776f506f6b65725461626c65223a393a7b733a31333a22002a0074696d654f6e5475726e223b693a32303b733a383a22002a00626c696e64223b693a31303b733a31353a22002a00706c6179657273436f756e74223b693a323b733a31333a22002a006d696e4e6f6d696e616c223b693a313b733a31343a22002a006361726473496e48616e64223b693a323b733a393a22002a00706c61636573223b613a303a7b7d733a31303a22002a00706c6179657273223b4f3a33383a224170705c47616d655c436f6c6c656374696f6e735c506c6179657273436f6c6c656374696f6e223a313a7b733a31333a22002a00636f6c6c656374696f6e223b613a323a7b693a303b4f3a31353a224170705c47616d655c506c61796572223a31393a7b733a373a22002a006e616d65223b733a32373a22d091d0bed0b3d0b4d0b0d0bd20d0a7d0b8d180d18ed0bad0b8d0bd223b733a393a22002a00617661746172223b733a33373a22687474703a2f2f686f7374696e672e6c6f63616c2f696d672f4a6f686e446f652e77656270223b733a383a22002a006361726473223b4f3a33363a224170705c47616d655c436f6c6c656374696f6e735c4361726473436f6c6c656374696f6e223a313a7b733a31333a22002a00636f6c6c656374696f6e223b613a303a7b7d7d733a383a22002a00706c616365223b693a303b733a383a22002a00636f6d626f223b4f3a34303a224170705c47616d655c436f6c6c656374696f6e735c55736572436f6d626f436f6c6c656374696f6e223a313a7b733a31333a22002a00636f6c6c656374696f6e223b613a303a7b7d7d733a393a22002a00696e47616d65223b623a313b733a31303a22002a00696e526f756e64223b623a313b733a31313a22002a0069734465616c6572223b623a303b733a373a22002a0069734242223b623a303b733a373a22002a0069734c42223b623a303b733a31303a22002a00616374696f6e73223b4f3a33373a224170705c47616d655c436f6c6c656374696f6e735c416374696f6e436f6c6c656374696f6e223a313a7b733a31333a22002a00636f6c6c656374696f6e223b613a353a7b693a303b4f3a32313a224170705c47616d655c416374696f6e735c466f6c64223a343a7b733a373a22002a006e616d65223b733a343a22466f6c64223b733a353a22002a006964223b693a303b733a31383a22002a00616d6f756e74496e4d657373616765223b623a303b733a31313a22002a006973416374697665223b623a303b7d693a313b4f3a32323a224170705c47616d655c416374696f6e735c436865636b223a343a7b733a373a22002a006e616d65223b733a353a22436865636b223b733a353a22002a006964223b693a313b733a31383a22002a00616d6f756e74496e4d657373616765223b623a303b733a31313a22002a006973416374697665223b623a303b7d693a323b4f3a32323a224170705c47616d655c416374696f6e735c5261697365223a343a7b733a373a22002a006e616d65223b733a393a22526169736520746f20223b733a353a22002a006964223b693a323b733a31383a22002a00616d6f756e74496e4d657373616765223b623a313b733a31313a22002a006973416374697665223b623a303b7d693a333b4f3a32323a224170705c47616d655c416374696f6e735c416c6c496e223a343a7b733a373a22002a006e616d65223b733a353a22416c6c496e223b733a353a22002a006964223b693a333b733a31383a22002a00616d6f756e74496e4d657373616765223b623a303b733a31313a22002a006973416374697665223b623a303b7d693a343b4f3a32313a224170705c47616d655c416374696f6e735c43616c6c223a343a7b733a373a22002a006e616d65223b733a343a2243616c6c223b733a353a22002a006964223b693a343b733a31383a22002a00616d6f756e74496e4d657373616765223b623a303b733a31313a22002a006973416374697665223b623a303b7d7d7d733a363a22002a00626964223b4f3a33313a224170705c47616d655c436f6c6c656374696f6e735c42696453746f72616765223a303a7b7d733a393a22002a00616d6f756e74223b693a313030303b733a32303a22002a00697343757272656e7453686f77646f776e223b623a303b733a31343a22002a0069734f70656e4361726473223b623a303b733a393a22002a0077696e6e6572223b623a303b733a31373a22002a00697353686f77646f776e50617373223b623a313b733a31343a22002a00706c616365496e47616d65223b693a303b733a393a22002a00757365724964223b693a31373b7d693a313b4f3a31353a224170705c47616d655c506c61796572223a31393a7b733a373a22002a006e616d65223b733a363a22797979797931223b733a393a22002a00617661746172223b733a37333a22687474703a2f2f686f7374696e672e6c6f63616c2f617661746172732f43765a776f494b3451653767714d6d4c36714347665536786d41696130576c5073387146437a6f422e6a7067223b733a383a22002a006361726473223b4f3a33363a224170705c47616d655c436f6c6c656374696f6e735c4361726473436f6c6c656374696f6e223a313a7b733a31333a22002a00636f6c6c656374696f6e223b613a303a7b7d7d733a383a22002a00706c616365223b693a313b733a383a22002a00636f6d626f223b4f3a34303a224170705c47616d655c436f6c6c656374696f6e735c55736572436f6d626f436f6c6c656374696f6e223a313a7b733a31333a22002a00636f6c6c656374696f6e223b613a303a7b7d7d733a393a22002a00696e47616d65223b623a313b733a31303a22002a00696e526f756e64223b623a313b733a31313a22002a0069734465616c6572223b623a303b733a373a22002a0069734242223b623a303b733a373a22002a0069734c42223b623a303b733a31303a22002a00616374696f6e73223b4f3a33373a224170705c47616d655c436f6c6c656374696f6e735c416374696f6e436f6c6c656374696f6e223a313a7b733a31333a22002a00636f6c6c656374696f6e223b613a353a7b693a303b4f3a32313a224170705c47616d655c416374696f6e735c466f6c64223a343a7b733a373a22002a006e616d65223b733a343a22466f6c64223b733a353a22002a006964223b693a303b733a31383a22002a00616d6f756e74496e4d657373616765223b623a303b733a31313a22002a006973416374697665223b623a303b7d693a313b4f3a32323a224170705c47616d655c416374696f6e735c436865636b223a343a7b733a373a22002a006e616d65223b733a353a22436865636b223b733a353a22002a006964223b693a313b733a31383a22002a00616d6f756e74496e4d657373616765223b623a303b733a31313a22002a006973416374697665223b623a303b7d693a323b4f3a32323a224170705c47616d655c416374696f6e735c5261697365223a343a7b733a373a22002a006e616d65223b733a393a22526169736520746f20223b733a353a22002a006964223b693a323b733a31383a22002a00616d6f756e74496e4d657373616765223b623a313b733a31313a22002a006973416374697665223b623a303b7d693a333b4f3a32323a224170705c47616d655c416374696f6e735c416c6c496e223a343a7b733a373a22002a006e616d65223b733a353a22416c6c496e223b733a353a22002a006964223b693a333b733a31383a22002a00616d6f756e74496e4d657373616765223b623a303b733a31313a22002a006973416374697665223b623a303b7d693a343b4f3a32313a224170705c47616d655c416374696f6e735c43616c6c223a343a7b733a373a22002a006e616d65223b733a343a2243616c6c223b733a353a22002a006964223b693a343b733a31383a22002a00616d6f756e74496e4d657373616765223b623a303b733a31313a22002a006973416374697665223b623a303b7d7d7d733a363a22002a00626964223b4f3a33313a224170705c47616d655c436f6c6c656374696f6e735c42696453746f72616765223a303a7b7d733a393a22002a00616d6f756e74223b693a313030303b733a32303a22002a00697343757272656e7453686f77646f776e223b623a303b733a31343a22002a0069734f70656e4361726473223b623a303b733a393a22002a0077696e6e6572223b623a303b733a31373a22002a00697353686f77646f776e50617373223b623a313b733a31343a22002a00706c616365496e47616d65223b693a303b733a393a22002a00757365724964223b693a31383b7d7d7d733a31313a22002a00636172644465636b223b4f3a33363a224170705c47616d655c436f6c6c656374696f6e735c4361726473436f6c6c656374696f6e223a313a7b733a31333a22002a00636f6c6c656374696f6e223b613a35323a7b693a303b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a313b733a31323a22002a0073756974496e646578223b693a303b733a353a22002a006964223b693a343b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2232223b733a31313a22002a00737569744e616d65223b733a313a2273223b7d693a313b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a313b733a31323a22002a0073756974496e646578223b693a313b733a353a22002a006964223b693a353b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2232223b733a31313a22002a00737569744e616d65223b733a313a2264223b7d693a323b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a313b733a31323a22002a0073756974496e646578223b693a323b733a353a22002a006964223b693a363b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2232223b733a31313a22002a00737569744e616d65223b733a313a2263223b7d693a333b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a313b733a31323a22002a0073756974496e646578223b693a333b733a353a22002a006964223b693a373b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2232223b733a31313a22002a00737569744e616d65223b733a313a2268223b7d693a343b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a323b733a31323a22002a0073756974496e646578223b693a303b733a353a22002a006964223b693a383b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2233223b733a31313a22002a00737569744e616d65223b733a313a2273223b7d693a353b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a323b733a31323a22002a0073756974496e646578223b693a313b733a353a22002a006964223b693a393b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2233223b733a31313a22002a00737569744e616d65223b733a313a2264223b7d693a363b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a323b733a31323a22002a0073756974496e646578223b693a323b733a353a22002a006964223b693a31303b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2233223b733a31313a22002a00737569744e616d65223b733a313a2263223b7d693a373b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a323b733a31323a22002a0073756974496e646578223b693a333b733a353a22002a006964223b693a31313b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2233223b733a31313a22002a00737569744e616d65223b733a313a2268223b7d693a383b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a333b733a31323a22002a0073756974496e646578223b693a303b733a353a22002a006964223b693a31323b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2234223b733a31313a22002a00737569744e616d65223b733a313a2273223b7d693a393b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a333b733a31323a22002a0073756974496e646578223b693a313b733a353a22002a006964223b693a31333b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2234223b733a31313a22002a00737569744e616d65223b733a313a2264223b7d693a31303b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a333b733a31323a22002a0073756974496e646578223b693a323b733a353a22002a006964223b693a31343b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2234223b733a31313a22002a00737569744e616d65223b733a313a2263223b7d693a31313b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a333b733a31323a22002a0073756974496e646578223b693a333b733a353a22002a006964223b693a31353b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2234223b733a31313a22002a00737569744e616d65223b733a313a2268223b7d693a31323b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a343b733a31323a22002a0073756974496e646578223b693a303b733a353a22002a006964223b693a31363b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2235223b733a31313a22002a00737569744e616d65223b733a313a2273223b7d693a31333b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a343b733a31323a22002a0073756974496e646578223b693a313b733a353a22002a006964223b693a31373b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2235223b733a31313a22002a00737569744e616d65223b733a313a2264223b7d693a31343b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a343b733a31323a22002a0073756974496e646578223b693a323b733a353a22002a006964223b693a31383b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2235223b733a31313a22002a00737569744e616d65223b733a313a2263223b7d693a31353b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a343b733a31323a22002a0073756974496e646578223b693a333b733a353a22002a006964223b693a31393b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2235223b733a31313a22002a00737569744e616d65223b733a313a2268223b7d693a31363b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a353b733a31323a22002a0073756974496e646578223b693a303b733a353a22002a006964223b693a32303b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2236223b733a31313a22002a00737569744e616d65223b733a313a2273223b7d693a31373b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a353b733a31323a22002a0073756974496e646578223b693a313b733a353a22002a006964223b693a32313b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2236223b733a31313a22002a00737569744e616d65223b733a313a2264223b7d693a31383b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a353b733a31323a22002a0073756974496e646578223b693a323b733a353a22002a006964223b693a32323b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2236223b733a31313a22002a00737569744e616d65223b733a313a2263223b7d693a31393b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a353b733a31323a22002a0073756974496e646578223b693a333b733a353a22002a006964223b693a32333b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2236223b733a31313a22002a00737569744e616d65223b733a313a2268223b7d693a32303b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a363b733a31323a22002a0073756974496e646578223b693a303b733a353a22002a006964223b693a32343b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2237223b733a31313a22002a00737569744e616d65223b733a313a2273223b7d693a32313b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a363b733a31323a22002a0073756974496e646578223b693a313b733a353a22002a006964223b693a32353b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2237223b733a31313a22002a00737569744e616d65223b733a313a2264223b7d693a32323b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a363b733a31323a22002a0073756974496e646578223b693a323b733a353a22002a006964223b693a32363b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2237223b733a31313a22002a00737569744e616d65223b733a313a2263223b7d693a32333b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a363b733a31323a22002a0073756974496e646578223b693a333b733a353a22002a006964223b693a32373b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2237223b733a31313a22002a00737569744e616d65223b733a313a2268223b7d693a32343b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a373b733a31323a22002a0073756974496e646578223b693a303b733a353a22002a006964223b693a32383b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2238223b733a31313a22002a00737569744e616d65223b733a313a2273223b7d693a32353b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a373b733a31323a22002a0073756974496e646578223b693a313b733a353a22002a006964223b693a32393b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2238223b733a31313a22002a00737569744e616d65223b733a313a2264223b7d693a32363b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a373b733a31323a22002a0073756974496e646578223b693a323b733a353a22002a006964223b693a33303b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2238223b733a31313a22002a00737569744e616d65223b733a313a2263223b7d693a32373b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a373b733a31323a22002a0073756974496e646578223b693a333b733a353a22002a006964223b693a33313b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2238223b733a31313a22002a00737569744e616d65223b733a313a2268223b7d693a32383b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a383b733a31323a22002a0073756974496e646578223b693a303b733a353a22002a006964223b693a33323b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2239223b733a31313a22002a00737569744e616d65223b733a313a2273223b7d693a32393b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a383b733a31323a22002a0073756974496e646578223b693a313b733a353a22002a006964223b693a33333b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2239223b733a31313a22002a00737569744e616d65223b733a313a2264223b7d693a33303b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a383b733a31323a22002a0073756974496e646578223b693a323b733a353a22002a006964223b693a33343b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2239223b733a31313a22002a00737569744e616d65223b733a313a2263223b7d693a33313b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a383b733a31323a22002a0073756974496e646578223b693a333b733a353a22002a006964223b693a33353b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2239223b733a31313a22002a00737569744e616d65223b733a313a2268223b7d693a33323b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a393b733a31323a22002a0073756974496e646578223b693a303b733a353a22002a006964223b693a33363b733a31343a22002a006e6f6d696e616c4e616d65223b733a323a223130223b733a31313a22002a00737569744e616d65223b733a313a2273223b7d693a33333b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a393b733a31323a22002a0073756974496e646578223b693a313b733a353a22002a006964223b693a33373b733a31343a22002a006e6f6d696e616c4e616d65223b733a323a223130223b733a31313a22002a00737569744e616d65223b733a313a2264223b7d693a33343b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a393b733a31323a22002a0073756974496e646578223b693a323b733a353a22002a006964223b693a33383b733a31343a22002a006e6f6d696e616c4e616d65223b733a323a223130223b733a31313a22002a00737569744e616d65223b733a313a2263223b7d693a33353b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a393b733a31323a22002a0073756974496e646578223b693a333b733a353a22002a006964223b693a33393b733a31343a22002a006e6f6d696e616c4e616d65223b733a323a223130223b733a31313a22002a00737569744e616d65223b733a313a2268223b7d693a33363b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a31303b733a31323a22002a0073756974496e646578223b693a303b733a353a22002a006964223b693a34303b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a226a223b733a31313a22002a00737569744e616d65223b733a313a2273223b7d693a33373b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a31303b733a31323a22002a0073756974496e646578223b693a313b733a353a22002a006964223b693a34313b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a226a223b733a31313a22002a00737569744e616d65223b733a313a2264223b7d693a33383b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a31303b733a31323a22002a0073756974496e646578223b693a323b733a353a22002a006964223b693a34323b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a226a223b733a31313a22002a00737569744e616d65223b733a313a2263223b7d693a33393b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a31303b733a31323a22002a0073756974496e646578223b693a333b733a353a22002a006964223b693a34333b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a226a223b733a31313a22002a00737569744e616d65223b733a313a2268223b7d693a34303b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a31313b733a31323a22002a0073756974496e646578223b693a303b733a353a22002a006964223b693a34343b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2271223b733a31313a22002a00737569744e616d65223b733a313a2273223b7d693a34313b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a31313b733a31323a22002a0073756974496e646578223b693a313b733a353a22002a006964223b693a34353b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2271223b733a31313a22002a00737569744e616d65223b733a313a2264223b7d693a34323b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a31313b733a31323a22002a0073756974496e646578223b693a323b733a353a22002a006964223b693a34363b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2271223b733a31313a22002a00737569744e616d65223b733a313a2263223b7d693a34333b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a31313b733a31323a22002a0073756974496e646578223b693a333b733a353a22002a006964223b693a34373b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2271223b733a31313a22002a00737569744e616d65223b733a313a2268223b7d693a34343b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a31323b733a31323a22002a0073756974496e646578223b693a303b733a353a22002a006964223b693a34383b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a226b223b733a31313a22002a00737569744e616d65223b733a313a2273223b7d693a34353b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a31323b733a31323a22002a0073756974496e646578223b693a313b733a353a22002a006964223b693a34393b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a226b223b733a31313a22002a00737569744e616d65223b733a313a2264223b7d693a34363b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a31323b733a31323a22002a0073756974496e646578223b693a323b733a353a22002a006964223b693a35303b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a226b223b733a31313a22002a00737569744e616d65223b733a313a2263223b7d693a34373b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a31323b733a31323a22002a0073756974496e646578223b693a333b733a353a22002a006964223b693a35313b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a226b223b733a31313a22002a00737569744e616d65223b733a313a2268223b7d693a34383b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a31333b733a31323a22002a0073756974496e646578223b693a303b733a353a22002a006964223b693a35323b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2261223b733a31313a22002a00737569744e616d65223b733a313a2273223b7d693a34393b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a31333b733a31323a22002a0073756974496e646578223b693a313b733a353a22002a006964223b693a35333b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2261223b733a31313a22002a00737569744e616d65223b733a313a2264223b7d693a35303b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a31333b733a31323a22002a0073756974496e646578223b693a323b733a353a22002a006964223b693a35343b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2261223b733a31313a22002a00737569744e616d65223b733a313a2263223b7d693a35313b4f3a31333a224170705c47616d655c43617264223a353a7b733a31353a22002a006e6f6d696e616c496e646578223b693a31333b733a31323a22002a0073756974496e646578223b693a333b733a353a22002a006964223b693a35353b733a31343a22002a006e6f6d696e616c4e616d65223b733a313a2261223b733a31313a22002a00737569744e616d65223b733a313a2268223b7d7d7d733a353a22002a006964223b693a313635353635343437373b7d, NULL, '2022-06-19 13:01:17', '2022-06-19 13:01:53', 0, NULL, '0');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `tables`
--
ALTER TABLE `tables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;