-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Июн 21 2022 г., 21:06
-- Версия сервера: 5.5.68-MariaDB
-- Версия PHP: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `qwert136_vkclean`
--

-- --------------------------------------------------------

--
-- Структура таблицы `deposit`
--

CREATE TABLE IF NOT EXISTS `deposit` (
  `user_id` int(12) NOT NULL,
  `sum` int(12) NOT NULL,
  `time` int(15) NOT NULL,
  `status` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `error_log`
--

CREATE TABLE IF NOT EXISTS `error_log` (
  `group_id` int(12) NOT NULL,
  `user` varchar(255) NOT NULL,
  `del_id` int(12) NOT NULL,
  `err_code` varchar(255) NOT NULL,
  `error` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(12) NOT NULL,
  `group_id` int(12) NOT NULL,
  `url` varchar(255) NOT NULL,
  `chk_status` int(12) NOT NULL,
  `users` int(12) NOT NULL,
  `dogs` int(12) NOT NULL,
  `na` int(12) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `time_start` int(15) NOT NULL,
  `offset` int(12) NOT NULL,
  `time_script` varchar(255) NOT NULL,
  `na_val` int(12) NOT NULL,
  `last_chk` varchar(50) NOT NULL,
  `cln_status` int(12) NOT NULL,
  `cln_offset` int(12) NOT NULL,
  `cln_dogs` int(12) NOT NULL,
  `cln_na` int(12) NOT NULL,
  `last_clean` int(15) NOT NULL,
  `dogs_del` int(12) NOT NULL,
  `na_del` int(12) NOT NULL,
  `ava` varchar(255) NOT NULL,
  `owner_id` int(12) NOT NULL,
  `need_send` int(12) NOT NULL,
  `send_to` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`id`, `group_id`, `url`, `chk_status`, `users`, `dogs`, `na`, `group_name`, `time_start`, `offset`, `time_script`, `na_val`, `last_chk`, `cln_status`, `cln_offset`, `cln_dogs`, `cln_na`, `last_clean`, `dogs_del`, `na_del`, `ava`, `owner_id`, `need_send`, `send_to`) VALUES
(20, 82873452, 'https://vk.com/prognozyotmaksa', 1, 38, 11, 9, 'Прогнозы и Розыгрыши CS GO от Макса', 1655686730, 0, '1655686730', 6, '20.06.2022 03:58:50', 0, 0, 0, 0, 0, 0, 0, 'https://sun9-14.userapi.com/s/v1/if1/ssS3aD9h-kFtk2GHMXRd2VhVXDELzZk1z5kf1xZFPW4MG3jLfY_sZaNoR7CNQ7KbmrogqQ.jpg?size=50x50&quality=96&crop=61,20,514,514&ava=1', 0, 0, ''),
(21, 66352011, 'https://vk.com/plattenbauten', 0, 137435, 19049, 17169, 'Панельки', 1652869543, 137000, '1652869636', 6, '18.05.2022 13:27:16', 0, 0, 0, 0, 0, 0, 0, 'https://sun9-51.userapi.com/s/v1/ig2/cY3Tqm9bsR1vn-Vtx8Rc2JrINh9RuPaBHbSU1V2NierhdAdwNFRk00gSusGapyB9CyPksN9KVm_hACgPYm3OmMiD.jpg?size=50x50&quality=95&crop=1,0,424,424&ava=1', 0, 0, ''),
(22, 119320808, 'https://vk.com/another.beautyy', 2, 1057371, 0, 0, 'Другая красота', 1655686833, 414000, '1655687132', 6, '', 0, 0, 0, 0, 0, 0, 0, 'https://sun9-3.userapi.com/s/v1/if1/GVgTRWo_pK9MarJCcT9f_P9Dev69ctEFQPJ28K_cRw3K3bgUWQFZs-Y_nNDcgLuXTXhBRncE.jpg?size=50x50&quality=96&crop=0,722,1244,1244&ava=1', 0, 0, ''),
(23, 188946873, 'https://vk.com/zhd.online', 0, 176, 0, 0, 'ЖД билеты', 1655686848, 0, '1655686849', 6, '20.06.2022 04:00:49', 0, 0, 0, 0, 0, 0, 0, 'https://sun9-17.userapi.com/s/v1/ig2/w4WNydvOiOZd_1bAVkGZobGPP_eh6uBgL0dfS5ZptDwUpXY76NiHGmxojZXT7BY_lFVZWXWeS1UXz_mCDSv9xDpH.jpg?size=50x50&quality=96&crop=0,0,512,512&ava=1', 0, 0, '');

-- --------------------------------------------------------

--
-- Структура таблицы `invite`
--

CREATE TABLE IF NOT EXISTS `invite` (
  `hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `log`
--

CREATE TABLE IF NOT EXISTS `log` (
  `errors` varchar(255) NOT NULL,
  `time` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `mails_history`
--

CREATE TABLE IF NOT EXISTS `mails_history` (
  `id` int(12) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `need_send` int(12) NOT NULL,
  `group_sn` varchar(255) NOT NULL,
  `count` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `to_user` varchar(255) NOT NULL,
  `new` int(12) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `money`
--

CREATE TABLE IF NOT EXISTS `money` (
  `balance` int(12) NOT NULL,
  `ref_balance` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `my_groups`
--

CREATE TABLE IF NOT EXISTS `my_groups` (
  `id` int(12) NOT NULL,
  `user_id` int(12) NOT NULL,
  `group_sn` varchar(255) NOT NULL,
  `err_code` int(12) NOT NULL,
  `error` varchar(255) NOT NULL,
  `chk_status` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `scan_history`
--

CREATE TABLE IF NOT EXISTS `scan_history` (
  `id` int(12) NOT NULL,
  `user_id` int(12) NOT NULL,
  `group_sn` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(12) NOT NULL,
  `login` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `mail_chk` int(12) NOT NULL,
  `mail_hash` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `status` int(12) NOT NULL,
  `akk_bal` int(12) NOT NULL,
  `ref_bal` int(12) NOT NULL,
  `free_scan` varchar(255) NOT NULL,
  `last_in` int(15) NOT NULL,
  `ref_id` int(12) NOT NULL,
  `ref_money` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `withdraw`
--

CREATE TABLE IF NOT EXISTS `withdraw` (
  `id` int(12) NOT NULL,
  `user_id` int(12) NOT NULL,
  `wallet` varchar(255) NOT NULL,
  `sum` int(12) NOT NULL,
  `time` int(15) NOT NULL,
  `status` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `mails_history`
--
ALTER TABLE `mails_history`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `my_groups`
--
ALTER TABLE `my_groups`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `scan_history`
--
ALTER TABLE `scan_history`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `withdraw`
--
ALTER TABLE `withdraw`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT для таблицы `mails_history`
--
ALTER TABLE `mails_history`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `my_groups`
--
ALTER TABLE `my_groups`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `scan_history`
--
ALTER TABLE `scan_history`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `withdraw`
--
ALTER TABLE `withdraw`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
