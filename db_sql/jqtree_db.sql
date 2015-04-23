-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 23 2015 г., 11:36
-- Версия сервера: 5.5.41-log
-- Версия PHP: 5.4.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `jqtree_db`
--

-- --------------------------------------------------------

--
-- Структура таблицы `jqtree_data`
--

CREATE TABLE IF NOT EXISTS `jqtree_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL DEFAULT '''node''',
  `is_node` tinyint(2) NOT NULL,
  `is_open` tinyint(2) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL,
  `index_node` int(11) NOT NULL,
  `level` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Дамп данных таблицы `jqtree_data`
--

INSERT INTO `jqtree_data` (`id`, `name`, `is_node`, `is_open`, `parent_id`, `index_node`, `level`) VALUES
(1, 'class', 1, 1, 0, 0, 2),
(2, 'container', 1, 1, 0, 1, 2),
(3, 'data', 1, 1, 0, 2, 2),
(4, 'dd', 1, 1, 0, 3, 2),
(5, 'dialog', 1, 1, 0, 4, 2),
(6, 'chart', 1, 1, 2, 6, 3),
(7, 'AbstractContainer.js', 0, 0, 2, 0, 3),
(8, 'ButtonGroup.js', 0, 0, 2, 1, 3),
(9, 'Container.js', 0, 0, 2, 2, 3),
(10, 'DockingContainer.js', 0, 0, 2, 3, 3),
(11, 'Monitir.js', 0, 0, 2, 5, 3),
(12, 'Viewport.js', 0, 0, 2, 4, 3);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
