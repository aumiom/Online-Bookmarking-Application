-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2015 at 12:07 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bookmark`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookmark`
--

CREATE TABLE IF NOT EXISTS `bookmark` (
  `user` char(20) NOT NULL DEFAULT '',
  `title` char(70) NOT NULL DEFAULT '',
  `url` char(200) NOT NULL DEFAULT '',
  `description` mediumtext,
  `private` enum('0','1') DEFAULT NULL,
  `date` timestamp NOT NULL,
  `childof` int(11) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `favicon` varchar(200) DEFAULT NULL,
  `public` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title` (`title`,`url`,`description`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

--
-- Dumping data for table `bookmark`
--

INSERT INTO `bookmark` (`user`, `title`, `url`, `description`, `private`, `date`, `childof`, `id`, `deleted`, `favicon`, `public`) VALUES
('admin', 'google', 'http://www.google.com', 'search engine', NULL, '0000-00-00 00:00:00', 0, 1, '0', NULL, '1'),
('ompr', 'wewe', 'http://ewe', 'ewew', NULL, '0000-00-00 00:00:00', 4, 2, '0', NULL, '0'),
('om', 'sdsdsd', 'http://dsds', 'dsdsd', NULL, '0000-00-00 00:00:00', 7, 9, '0', NULL, '0'),
('ompr', 'sasas', 'http://sas', 'sa', NULL, '0000-00-00 00:00:00', 7, 5, '0', NULL, '0'),
('ompr', 'sasasasas', 'http://sasasas', 'sas', NULL, '0000-00-00 00:00:00', 8, 6, '0', NULL, '0'),
('omtester', 'new', 'http://eee', 'eee', NULL, '0000-00-00 00:00:00', 16, 15, '0', NULL, '0'),
('ompr', 'ssstest', 'http://test', 'ere', NULL, '0000-00-00 00:00:00', 8, 8, '0', NULL, '0'),
('omtest', 'trrfr', 'http://trtrfrf', 'trtrfrf', NULL, '0000-00-00 00:00:00', 0, 13, '0', NULL, '0'),
('omtest', 'asaas', 'http://sasa', 'sasa', NULL, '0000-00-00 00:00:00', 14, 14, '0', NULL, '0'),
('omtester', 'Google', 'http://google.com', '', NULL, '0000-00-00 00:00:00', 0, 27, '0', NULL, '0'),
('omtester', 'res', 'http://dd', 'ss', NULL, '0000-00-00 00:00:00', 16, 30, '0', NULL, '0'),
('omtester', 'zxs', 'http://xsx', '', NULL, '0000-00-00 00:00:00', 0, 24, '0', NULL, '0'),
('omtester', 'dsdsihihh', 'http:/dsd', 'dsdsds', NULL, '0000-00-00 00:00:00', 0, 25, '0', NULL, '0'),
('omtester', 'adadda', 'http://gsgsg', 'dsdsdsd', NULL, '0000-00-00 00:00:00', 16, 26, '0', NULL, '0'),
('omtester', 'Save The Internet', 'http://savetheinternet.in', '', NULL, '0000-00-00 00:00:00', 0, 28, '0', NULL, '0'),
('omtester', 'kjhjk', 'nnm,,', 'jbbbnm', NULL, '0000-00-00 00:00:00', 16, 31, '0', NULL, '0');

-- --------------------------------------------------------

--
-- Table structure for table `folder`
--

CREATE TABLE IF NOT EXISTS `folder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `childof` int(11) NOT NULL DEFAULT '0',
  `name` char(70) NOT NULL DEFAULT '',
  `user` char(20) NOT NULL DEFAULT '',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `public` enum('0','1') NOT NULL DEFAULT '0',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `folder`
--

INSERT INTO `folder` (`id`, `childof`, `name`, `user`, `deleted`, `public`) VALUES
(1, 0, 'search', 'admin', '0', '0'),
(2, 0, 'test', 'omprakash.sch@gmail.', '0', '0'),
(3, 0, 'test', 'omprakash.sch@gmail.', '0', '1'),
(4, 0, 'test', 'om', '0', '1'),
(5, 4, 'test', 'ompr', '0', '0'),
(6, 4, 'testing', 'ompr', '0', '0'),
(7, 0, 'asas', 'ompr', '0', '0'),
(8, 7, 'des', 'ompr', '0', '0'),
(9, 7, 'sasasa', 'ompr', '0', '0'),
(10, 7, 'asa', 'omtest', '0', '0'),
(11, 7, 'saassa', 'omtest', '0', '1'),
(15, 0, 'wsws', 'omtester', '0', '0'),
(16, 0, 'aqaqa', 'omtester', '0', '0'),
(14, 0, 'qq', 'omtest', '0', '0'),
(17, 15, 'terst', 'omtester', '0', '0');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `username` char(50) NOT NULL DEFAULT '',
  `password` char(50) NOT NULL DEFAULT '',
  `theme` varchar(50) NOT NULL DEFAULT '',
  `admin` enum('0','1') NOT NULL DEFAULT '0',
  `language` char(20) NOT NULL DEFAULT '',
  `root_folder_name` char(50) NOT NULL DEFAULT 'My Bookmarks',
  `column_width_folder` smallint(3) NOT NULL DEFAULT '0',
  `column_width_bookmark` smallint(3) NOT NULL DEFAULT '0',
  `table_height` smallint(3) NOT NULL DEFAULT '0',
  `confirm_delete` enum('0','1') NOT NULL DEFAULT '1',
  `open_new_window` enum('0','1') NOT NULL DEFAULT '1',
  `show_bookmark_description` enum('0','1') NOT NULL DEFAULT '1',
  `show_bookmark_icon` enum('0','1') NOT NULL DEFAULT '1',
  `show_column_date` enum('0','1') NOT NULL DEFAULT '1',
  `date_format` smallint(6) NOT NULL DEFAULT '0',
  `show_column_edit` enum('0','1') NOT NULL DEFAULT '1',
  `show_column_move` enum('0','1') NOT NULL DEFAULT '1',
  `show_column_delete` enum('0','1') NOT NULL DEFAULT '1',
  `fast_folder_minus` enum('0','1') NOT NULL DEFAULT '1',
  `fast_folder_plus` enum('0','1') NOT NULL DEFAULT '1',
  `fast_symbol` enum('0','1') NOT NULL DEFAULT '1',
  `simple_tree_mode` enum('0','1') NOT NULL DEFAULT '0',
  `show_public` enum('0','1') NOT NULL DEFAULT '1',
  UNIQUE KEY `id` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`username`, `password`, `theme`, `admin`, `language`, `root_folder_name`, `column_width_folder`, `column_width_bookmark`, `table_height`, `confirm_delete`, `open_new_window`, `show_bookmark_description`, `show_bookmark_icon`, `show_column_date`, `date_format`, `show_column_edit`, `show_column_move`, `show_column_delete`, `fast_folder_minus`, `fast_folder_plus`, `fast_symbol`, `simple_tree_mode`, `show_public`) VALUES
('admin', '21232f297a57a5a743894a0e4a801fc3', '', '1', '', 'My Bookmarks', 0, 0, 0, '1', '1', '1', '1', '1', 0, '1', '1', '1', '1', '1', '1', '0', '1'),
('omprakash.sch@gmail.com', '202cb962ac59075b964b07152d234b70', '', '0', '', 'My Bookmarks', 0, 0, 0, '1', '1', '1', '1', '1', 0, '1', '1', '1', '1', '1', '1', '0', '1'),
('om', '202cb962ac59075b964b07152d234b70', '', '1', '', 'My Bookmarks', 0, 0, 0, '1', '1', '1', '1', '1', 0, '1', '1', '1', '1', '1', '1', '0', '1'),
('ompr', '202cb962ac59075b964b07152d234b70', '', '1', '', 'My Bookmarks', 0, 0, 0, '1', '1', '1', '1', '1', 0, '1', '1', '1', '1', '1', '1', '0', '1'),
('test', '202cb962ac59075b964b07152d234b70', '', '1', '', 'My Bookmarks', 0, 0, 0, '1', '1', '1', '1', '1', 0, '1', '1', '1', '1', '1', '1', '0', '1'),
('omtest', '202cb962ac59075b964b07152d234b70', '', '0', '', 'My Bookmarks', 0, 0, 0, '1', '1', '1', '1', '1', 0, '1', '1', '1', '1', '1', '1', '0', '1'),
('omtester', '202cb962ac59075b964b07152d234b70', '', '0', '', 'My Bookmarks', 0, 0, 0, '1', '1', '1', '1', '1', 0, '1', '1', '1', '1', '1', '1', '0', '1');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
