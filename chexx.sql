DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `no` int(11) NOT NULL AUTO_INCREMENT,
  `uid` text NOT NULL,
  `status` text NOT NULL,
  `item` text NOT NULL,
  `skill` text NOT NULL,
  `hero` text NOT NULL,
  `arrange` text NOT NULL,
  PRIMARY KEY (`no`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
