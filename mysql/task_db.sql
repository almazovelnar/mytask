-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Хост: db
-- Время создания: Фев 01 2021 г., 11:20
-- Версия сервера: 5.7.32
-- Версия PHP: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `task_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `boosterpack`
--

CREATE TABLE `boosterpack` (
  `id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bank` decimal(10,2) NOT NULL DEFAULT '0.00',
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `time_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `boosterpack`
--

INSERT INTO `boosterpack` (`id`, `price`, `bank`, `time_created`, `time_updated`) VALUES
(1, '5.00', '2.00', '2020-03-30 00:17:28', '2021-02-01 08:19:00'),
(2, '20.00', '12.00', '2020-03-30 00:17:28', '2021-02-01 06:21:38'),
(3, '50.00', '24.00', '2020-03-30 00:17:28', '2021-02-01 06:31:26');

-- --------------------------------------------------------

--
-- Структура таблицы `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `user_id` int(10) UNSIGNED NOT NULL,
  `assign_id` int(10) UNSIGNED NOT NULL,
  `text` text NOT NULL,
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `time_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `likes` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `comment`
--

INSERT INTO `comment` (`id`, `parent_id`, `user_id`, `assign_id`, `text`, `time_created`, `time_updated`, `likes`) VALUES
(1, 0, 2, 1, 'test1', '2021-02-01 08:24:39', '2021-02-01 08:24:39', 0),
(2, 0, 2, 1, 'test2', '2021-02-01 08:24:41', '2021-02-01 08:28:18', 1),
(3, 1, 2, 1, 'testtest', '2021-02-01 08:24:48', '2021-02-01 08:24:48', 0),
(4, 2, 2, 1, 'test2tt', '2021-02-01 08:24:57', '2021-02-01 08:28:16', 1),
(5, 1, 2, 1, 'test', '2021-02-01 08:27:16', '2021-02-01 08:27:16', 0),
(6, 2, 2, 1, '333', '2021-02-01 08:27:22', '2021-02-01 08:27:22', 0),
(7, 6, 2, 1, 'awd', '2021-02-01 08:27:32', '2021-02-01 08:27:32', 0),
(8, 1, 2, 1, 'test', '2021-02-01 08:28:04', '2021-02-01 08:28:04', 0),
(9, 0, 2, 1, 'test', '2021-02-01 08:31:50', '2021-02-01 08:31:50', 0),
(10, 9, 2, 1, 'testset', '2021-02-01 08:31:54', '2021-02-01 08:31:54', 0),
(11, 0, 2, 1, 'testsetset', '2021-02-01 08:31:58', '2021-02-01 08:31:58', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `text` text NOT NULL,
  `img` varchar(1024) DEFAULT NULL,
  `time_created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `time_updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `likes` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `post`
--

INSERT INTO `post` (`id`, `user_id`, `text`, `img`, `time_created`, `time_updated`, `likes`) VALUES
(1, 1, 'Тестовый постик 1', '/images/posts/1.png', '2018-08-30 13:31:14', '2021-02-01 08:33:04', 10),
(2, 1, 'Печальный пост', '/images/posts/2.png', '2018-10-11 01:33:27', '2021-01-31 19:02:40', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(60) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `personaname` varchar(50) NOT NULL DEFAULT '',
  `avatarfull` varchar(150) NOT NULL DEFAULT '',
  `rights` tinyint(4) NOT NULL DEFAULT '0',
  `wallet_balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `wallet_total_refilled` decimal(10,2) NOT NULL DEFAULT '0.00',
  `wallet_total_withdrawn` decimal(10,2) NOT NULL DEFAULT '0.00',
  `likes` int(11) NOT NULL DEFAULT '0',
  `time_created` datetime NOT NULL,
  `time_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `personaname`, `avatarfull`, `rights`, `wallet_balance`, `wallet_total_refilled`, `wallet_total_withdrawn`, `likes`, `time_created`, `time_updated`) VALUES
(1, 'admin@niceadminmail.pl', '123', 'AdminProGod', 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/96/967871835afdb29f131325125d4395d55386c07a_full.jpg', 0, '0.00', '0.00', '0.00', 0, '2019-07-26 01:53:54', '2021-01-31 21:30:01'),
(2, 'almazovelnar96@gmail.com', '123', 'elnar', 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/86/86a0c845038332896455a566a1f805660a13609b_full.jpg', 0, '2.00', '157.00', '155.00', 3, '2019-07-26 01:53:54', '2021-02-01 08:28:18');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `boosterpack`
--
ALTER TABLE `boosterpack`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `time_created` (`time_created`),
  ADD KEY `time_updated` (`time_updated`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `boosterpack`
--
ALTER TABLE `boosterpack`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
