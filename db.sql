

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `campaign`
--

INSERT INTO `campaign` VALUES(1, 1, 'A badass twitter campaign', '2011-06-01 00:00:00', '2011-06-30 00:00:00');
INSERT INTO `campaign` VALUES(4, 1, 'A badass twitter campaign', '2011-06-01 00:00:00', '2011-06-30 00:00:00');

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

INSERT INTO `campaign_attr` VALUES(1, 'twitter_hashtag', 'a');
INSERT INTO `campaign_attr` VALUES(1, 'twitter_hashtag', 'wikileaks');
INSERT INTO `campaign_attr` VALUES(1, 'twitter_replyto', 'asd');

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

INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_hashtag', 'wikileaks,100');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'assange,wikileaks,16');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'anonymous,wikileaks,12');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'google,wikileaks,8');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'antisec,wikileaks,8');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'cablegate,wikileaks,7');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'anonymiss,wikileaks,7');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'lulzsec,wikileaks,6');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'duckpond,wikileaks,6');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'blog,wikileaks,5');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'chavez,wikileaks,3');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'teamfollowback,wikileaks,3');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'god,wikileaks,3');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'manning,wikileaks,2');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'hrafnsson,wikileaks,2');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'lulzsecbrasil,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'ala11,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'p2,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'corrupacao,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'australia,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'nz,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'freespeech,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'democracy,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'censorship,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'wiki,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'tfb,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'news,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'bodou,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'brasil,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'lesapatria,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'opitaly,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'libya,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'bahrein,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'algerie,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'indect,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'espana,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'freebradley,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'gaypride,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'banksters,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'fbi,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'opmanning,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'conservatives,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'tunisie,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'censilia,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'petrobras,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'hackers,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'dinnerwithbarack,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'italianrevolution,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'israel,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'lulzsecbrazil,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'venezuela,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'jan25,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'acta,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'ppi,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'presse,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_related_hashtag', 'pirates,wikileaks,1');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:18', 'twitter_agent_hashtag_lastid', 'wikileaks,84778382168035328');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:27', 'twitter_hashtag', 'wikileaks,0');
INSERT INTO `campaign_data` VALUES(1, '2011-06-26 03:24:27', 'twitter_agent_hashtag_lastid', 'wikileaks,84778382168035328');

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
