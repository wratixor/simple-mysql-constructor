-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 14 2020 г., 15:32
-- Версия сервера: 10.3.13-MariaDB-log
-- Версия PHP: 7.1.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `wta`
--

-- --------------------------------------------------------

--
-- Структура таблицы `wta_users`
--

CREATE TABLE `wta_users` (
  `user_id` int(11) NOT NULL,
  `user_name` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_pass` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_lvl` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `wta_users`
--

INSERT INTO `wta_users` (`user_id`, `user_name`, `user_pass`, `user_lvl`) VALUES
(2, 'admin', '$2y$10$f0maXE7.P8znVNdecqtnv.mpqnOQ0vprrQYKnYm/JcM.ayC6PJefm', 777);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `wta_users`
--
ALTER TABLE `wta_users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `wta_users`
--
ALTER TABLE `wta_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
