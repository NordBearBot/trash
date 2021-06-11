-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Окт 15 2020 г., 19:40
-- Версия сервера: 5.6.41
-- Версия PHP: 7.0.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `soc_set_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `userone` int(11) NOT NULL,
  `usertwo` int(11) NOT NULL,
  `messages_json` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `messages`
--

INSERT INTO `messages` (`id`, `userone`, `usertwo`, `messages_json`) VALUES
(1, 12, 13, '[{\"id\":\"12\",\"text\":\"234567890-\"},{\"id\":\"12\",\"text\":\"234567890-\"},{\"id\":\"12\",\"text\":\"234567890-\"},{\"id\":\"12\",\"text\":\"234567890-\"},{\"id\":\"12\",\"text\":\"123\"},{\"id\":\"12\",\"text\":\"ebal\"},{\"id\":\"12\",\"text\":\"suska\"},{\"id\":\"12\",\"text\":\"Сука ты санная, я найду тебя, твоих родителей, родителей твоих родителей, переверну там всё верх дном!\"},{\"id\":\"13\",\"text\":\"Я тебя тварь ненавижу!!\"},{\"id\":\"12\",\"text\":\"будь ты сука проклят\"},{\"id\":\"12\",\"text\":\"1\"},{\"id\":\"12\",\"text\":\"2\"},{\"id\":\"12\",\"text\":\"3\"},{\"id\":\"12\",\"text\":\"1\"},{\"id\":\"12\",\"text\":\"1\"},{\"id\":\"12\",\"text\":\"refreerferferferf\"},{\"id\":\"12\",\"text\":\"ergerg<br />ergerg<br />hyt htyhty\"},{\"id\":\"12\",\"text\":\"gergerg<br />erg<br />er<br />ge<br />rg<br />erg<br />er<br />g<br />er<br />gerg\"}]'),
(2, 12, 14, '[{\"id\":\"12\",\"text\":\"Test message\"}]'),
(5, 15, 12, '[{\"id\":\"15\",\"text\":\"123\"},{\"id\":\"15\",\"text\":\"ПЕРЕРИСКА УРА!!<br />\"}]'),
(6, 20, 20, '[{\"id\":\"20\",\"text\":\"\"},{\"id\":\"20\",\"text\":\"fwefwefwef\"}]');

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `message` text NOT NULL,
  `images_json` text NOT NULL,
  `groupid` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL,
  `fromuser` int(11) DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `posts`
--

INSERT INTO `posts` (`id`, `message`, `images_json`, `groupid`, `userid`, `fromuser`, `date`) VALUES
(2, 'rgtersgegrtggertgrtgrtgtrgrt', '[]', NULL, 17, 17, '2020-10-13 07:58:49'),
(3, 'reoeop[rkgoperkpogkeropkgpekrpogkporekpog', '[\"https://miro.medium.com/max/1200/1*mk1-6aYaf_Bes1E3Imhc0A.jpeg\",\"data:image/png;base64,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\"]', NULL, 17, 17, '2020-10-13 08:02:50'),
(4, 'gergergergegregerger', '[]', NULL, 20, 20, '2020-10-13 09:37:39');

-- --------------------------------------------------------

--
-- Структура таблицы `pushes`
--

CREATE TABLE `pushes` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `fromuser` int(11) NOT NULL,
  `type` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `pushes`
--

INSERT INTO `pushes` (`id`, `userid`, `fromuser`, `type`) VALUES
(3, 13, 15, 'tofriend'),
(4, 14, 15, 'tofriend');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `login` varchar(8) NOT NULL,
  `password` text NOT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `info_json` text,
  `posts_json` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `login`, `password`, `date`, `info_json`, `posts_json`) VALUES
(20, 'Yagir', 'Yagir', '$2y$10$HAdzXDLK6nEGYa18Da0iEuDwvhzPXzgpT0cVUyKGgz4musfyj9wyO', '2020-10-13 08:31:33', '{\"location\":\"\",\"born\":\"\",\"job\":\"\",\"study\":\"\",\"type\":\"\",\"hobby\":\"\",\"email\":\"\",\"background\":\"img/background.jpg\",\"avatar\":\"img/avatar.jpg\",\"friends\":[\"21\"]}', '[4]'),
(21, 'Yagir1', 'Yagir1', '$2y$10$HAdzXDLK6nEGYa18Da0iEuDwvhzPXzgpT0cVUyKGgz4musfyj9wyO', '2020-10-13 08:31:33', '{\"location\":\"\",\"born\":\"\",\"job\":\"\",\"study\":\"\",\"type\":\"\",\"hobby\":\"\",\"email\":\"\",\"background\":\"img/background.jpg\",\"avatar\":\"img/avatar.jpg\",\"friends\":[\"21\"]}', '[4]'),
(22, 'Nika', 'Nika', '$2y$10$FAEQ.0tADukxzt0EjHQb0endWAh2WGzKxm7p9ZMUC3XJQfmSiPWIu', '2020-10-13 10:27:33', '{\"location\":\"\",\"born\":\"\",\"job\":\"\",\"study\":\"\",\"type\":\"\",\"hobby\":\"\",\"email\":\"yar_x@mail.ru\",\"background\":\"img/background.jpg\",\"avatar\":\"img/avatar.jpg\",\"friends\":[]}', '[]');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `pushes`
--
ALTER TABLE `pushes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `pushes`
--
ALTER TABLE `pushes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
