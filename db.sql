
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `campaign`
--

INSERT INTO `campaign` VALUES(1, 1, 'A badass twitter campaign', '2011-06-01 00:00:00', '2011-06-30 00:00:00');
INSERT INTO `campaign` VALUES(4, 1, 'A badass twitter campaign', '2011-06-01 00:00:00', '2011-06-30 00:00:00');
INSERT INTO `campaign` VALUES(5, 1, 'test', '2011-06-26 00:00:00', '2011-06-30 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `campaign_attr`
--

CREATE TABLE `campaign_attr` (
  `id_campaign` int(11) unsigned NOT NULL,
  `attr` varchar(32) NOT NULL,
  `val` varchar(256) NOT NULL,
  PRIMARY KEY (`id_campaign`,`attr`,`val`),
  KEY `attr` (`attr`),
  KEY `val` (`val`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `campaign_attr`
--

INSERT INTO `campaign_attr` VALUES(1, 'twitter_hashtag', 'x');
INSERT INTO `campaign_attr` VALUES(1, 'twitter_hashtag', 'xx');

-- --------------------------------------------------------

--
-- Table structure for table `campaign_data`
--

CREATE TABLE `campaign_data` (
  `id_campaign` int(11) unsigned NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `attr` varchar(64) NOT NULL,
  `val` varchar(1024) NOT NULL,
  KEY `id_campaign` (`id_campaign`),
  KEY `attr` (`attr`),
  KEY `val` (`val`(767)),
  KEY `time` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `campaign_data`
--

INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_hashtag', 'retailers,15');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_hashtag', 'mobile,retailers,5');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_percent', 'mobile,retailers,0.333333');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_hashtag', 'tech,retailers,4');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_percent', 'tech,retailers,0.266667');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_hashtag', 'sharesquare,retailers,2');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_percent', 'sharesquare,retailers,0.133333');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_hashtag', 'qrcodes,retailers,2');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_percent', 'qrcodes,retailers,0.133333');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_hashtag', 'shopsavvy,retailers,2');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_percent', 'shopsavvy,retailers,0.133333');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_hashtag', 'li,retailers,2');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_percent', 'li,retailers,0.133333');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_hashtag', 'video,retailers,2');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_percent', 'video,retailers,0.133333');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_hashtag', 'upsell,retailers,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_percent', 'upsell,retailers,0.066667');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_hashtag', 'gleem,retailers,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_percent', 'gleem,retailers,0.066667');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_hashtag', 'dehumidifier,retailers,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_percent', 'dehumidifier,retailers,0.066667');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_hashtag', 'danby,retailers,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_percent', 'danby,retailers,0.066667');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_hashtag', 'saxophone,retailers,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_percent', 'saxophone,retailers,0.066667');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_hashtag', 'detergent,retailers,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_percent', 'detergent,retailers,0.066667');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_hashtag', 'toothpaste,retailers,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_percent', 'toothpaste,retailers,0.066667');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_hashtag', 'bed,retailers,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_percent', 'bed,retailers,0.066667');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_hashtag', 'shopping,retailers,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_percent', 'shopping,retailers,0.066667');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_hashtag', 'scanner,retailers,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_percent', 'scanner,retailers,0.066667');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_hashtag', 'barcode,retailers,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_percent', 'barcode,retailers,0.066667');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_hashtag', 'win,retailers,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_percent', 'win,retailers,0.066667');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_hashtag', 'bathtub,retailers,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_related_percent', 'bathtub,retailers,0.066667');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:52:46', 'twitter_agent_hashtag_lastid', 'retailers,85002040253743104');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:53:41', 'twitter_hashtag', 'retailers,0');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 18:53:41', 'twitter_agent_hashtag_lastid', 'retailers,85002040253743104');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `id_client` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` int(10) unsigned NOT NULL,
  `name` varchar(256) NOT NULL,
  `domain` varchar(256) NOT NULL,
  `email` varchar(1024) NOT NULL,
  PRIMARY KEY (`id_client`),
  KEY `id_user` (`id_user`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `client`
--

INSERT INTO `client` VALUES(1, 1, 'name', 'domain', 'example@example.com');

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
