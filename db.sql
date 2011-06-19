-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 19, 2011 at 10:15 PM
-- Server version: 5.5.9
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `socialmining`
--

-- --------------------------------------------------------

--
-- Table structure for table `campaign`
--

CREATE TABLE `campaign` (
  `id_campaign` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(11) unsigned DEFAULT NULL,
  `title` varchar(64) NOT NULL,
  `from` datetime DEFAULT NULL,
  `to` datetime DEFAULT NULL,
  PRIMARY KEY (`id_campaign`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `campaign`
--

INSERT INTO `campaign` VALUES(1, 1, 'test2', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `campaign_attr`
--

CREATE TABLE `campaign_attr` (
  `id_campaign` int(11) unsigned NOT NULL,
  `attr` varchar(32) NOT NULL,
  `val` varchar(256) NOT NULL,
  PRIMARY KEY (`id_campaign`,`attr`),
  KEY `attr` (`attr`),
  KEY `val` (`val`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `campaign_attr`
--

INSERT INTO `campaign_attr` VALUES(1, 'twitter_replyto', 'gizm0bill');
INSERT INTO `campaign_attr` VALUES(1, 'twitter_hashtag', 'zend');

-- --------------------------------------------------------

--
-- Table structure for table `campaign_data`
--

CREATE TABLE `campaign_data` (
  `id_campaign` int(11) unsigned NOT NULL,
  `attr` varchar(32) NOT NULL,
  `val` varchar(265) NOT NULL,
  KEY `id_campaign` (`id_campaign`),
  KEY `attr` (`attr`),
  KEY `val` (`val`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `campaign_data`
--

INSERT INTO `campaign_data` VALUES(1, 'twitter_hashtag_result_count', '2011-06-19 18:42:37,53');
INSERT INTO `campaign_data` VALUES(1, 'twitter_hashtag_result_count', '2011-06-19 18:46:29,53');
INSERT INTO `campaign_data` VALUES(1, 'twitter_hashtag_result_count', '2011-06-19 18:47:34,53');
INSERT INTO `campaign_data` VALUES(1, 'twitter_hashtag_result_count', '2011-06-19 20:22:44,49');
INSERT INTO `campaign_data` VALUES(1, 'twitter_hashtag_result_count', '2011-06-19 20:23:06,49');
INSERT INTO `campaign_data` VALUES(1, 'twitter_hashtag_result_count', '2011-06-19 20:26:12,49');
INSERT INTO `campaign_data` VALUES(1, 'twitter_hashtag_result_count', '2011-06-19 20:26:30,49');
INSERT INTO `campaign_data` VALUES(1, 'twitter_hashtag_result_count', '2011-06-19 20:27:31,49');
INSERT INTO `campaign_data` VALUES(1, 'twitter_hashtag_result_count', '2011-06-19 20:30:53,49');
INSERT INTO `campaign_data` VALUES(1, 'twitter_hashtag_result_count', '2011-06-19 20:31:14,49');
INSERT INTO `campaign_data` VALUES(1, 'twitter_hashtag_result_count', '2011-06-19 20:36:13,49');
INSERT INTO `campaign_data` VALUES(1, 'twitter_hashtag_result_count', '2011-06-19 20:46:19,49');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL,
  `password` varchar(64) NOT NULL,
  `role` enum('guest','agent','manager','root') NOT NULL,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` VALUES(1, 'test', 'f1621a7f9862d961ffa2c141be58de6efc4b42c7f9af6ce80341', 'agent');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `campaign`
--
ALTER TABLE `campaign`
  ADD CONSTRAINT `campaign_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `campaign_attr`
--
ALTER TABLE `campaign_attr`
  ADD CONSTRAINT `campaign_attr_ibfk_1` FOREIGN KEY (`id_campaign`) REFERENCES `campaign` (`id_campaign`) ON DELETE CASCADE ON UPDATE CASCADE;
