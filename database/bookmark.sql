-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-11-05 06:31:35
-- 服务器版本： 5.7.9
-- PHP Version: 7.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookmark`
--

-- --------------------------------------------------------

--
-- 表的结构 `bk_favorite`
--

DROP TABLE IF EXISTS `bk_favorite`;
CREATE TABLE IF NOT EXISTS `bk_favorite` (1
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'pk',
  `cls_id` int(11) NOT NULL DEFAULT '0' COMMENT '分类id',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '名称',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '网址',
  `mark` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `click` int(11) NOT NULL DEFAULT '0' COMMENT '点击',
  `userid` int(11) NOT NULL DEFAULT '0',
  `create_at` int(10) NOT NULL DEFAULT '0',
  `update_at` int(10) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0正常1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `bk_favorite`
--

INSERT INTO `bk_favorite` (`id`, `cls_id`, `name`, `url`, `mark`, `click`, `userid`, `create_at`, `update_at`, `deleted`) VALUES
(1, 1, '百度', 'http://www.baidu.com', '百度一下，你就知道', 0, 1, 1450624252, 1450624252, 0);

-- --------------------------------------------------------

--
-- 表的结构 `bk_type`
--

DROP TABLE IF EXISTS `bk_type`;
CREATE TABLE IF NOT EXISTS `bk_type` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'pk',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '分类名称',
  `parent` int(11) NOT NULL DEFAULT '0' COMMENT '上级分类',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `userid` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `create_at` int(10) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0正常1删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `bk_type`
--

INSERT INTO `bk_type` (`id`, `name`, `parent`, `sort`, `userid`, `create_at`, `deleted`) VALUES
(1, '搜索', 0, 0, 1, 1450624252, 0);

-- --------------------------------------------------------

--
-- 表的结构 `bk_user`
--

DROP TABLE IF EXISTS `bk_user`;
CREATE TABLE IF NOT EXISTS `bk_user` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'pk',
  `account` varchar(32) NOT NULL DEFAULT '',
  `pass` varchar(32) NOT NULL DEFAULT '',
  `salt` varchar(16) NOT NULL DEFAULT '',
  `nickname` varchar(16) NOT NULL DEFAULT '' COMMENT '昵称',
  `level` tinyint(1) NOT NULL DEFAULT '0' COMMENT '级别',
  `create_at` int(10) NOT NULL DEFAULT '0',
  `update_at` int(10) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0正常1删除',
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `bk_user`
--

INSERT INTO `bk_user` (`id`, `account`, `pass`, `salt`, `nickname`, `level`, `create_at`, `update_at`, `deleted`) VALUES
(1, 'test@qq.com', 'e10adc3949ba59abbe56e057f20f883e', '123456', '米花2015', 0, 1450498557, 1450624068, 0);

-- --------------------------------------------------------

--
-- 表的结构 `bk_user_reg`
--

DROP TABLE IF EXISTS `bk_user_reg`;
CREATE TABLE IF NOT EXISTS `bk_user_reg` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'pk',
  `account` varchar(32) NOT NULL DEFAULT '',
  `nickname` varchar(16) NOT NULL DEFAULT '' COMMENT '昵称',
  `code` varchar(32) NOT NULL DEFAULT '' COMMENT '邀请码',
  `userid` int(11) NOT NULL DEFAULT '0',
  `create_at` int(10) NOT NULL DEFAULT '0',
  `update_at` int(10) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0正常1删除',
  PRIMARY KEY (`id`),
  KEY `account` (`account`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
