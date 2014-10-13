CREATE TABLE IF NOT EXISTS `{user}` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL,
  `purview` varchar(100) NOT NULL,
  `username` varchar(30) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  `realname` varchar(20) NOT NULL DEFAULT '',
  `status` int(2) NOT NULL,
  `email` varchar(30) NOT NULL DEFAULT '',
  `sex` varchar(10) NOT NULL,
  `litpic` varchar(250) NOT NULL DEFAULT '',
  `tel` varchar(50) NOT NULL DEFAULT '',
  `phone` varchar(50) NOT NULL DEFAULT '',
  `qq` varchar(50) NOT NULL DEFAULT '',
`province` varchar(10) NOT NULL,
`city` varchar(10) NOT NULL,
`area` varchar(10) NOT NULL,
  `address` varchar(200) NOT NULL,
  `logintime` int(11) NOT NULL DEFAULT '0',
  `addtime` varchar(50) NOT NULL,
  `addip` varchar(50) NOT NULL,
  `uptime` varchar(50) NOT NULL,
  `upip` varchar(50) NOT NULL,
  `lasttime` varchar(50) NOT NULL,
  `lastip` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM;


CREATE TABLE IF NOT EXISTS `{user_log}` (
  `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `search` varchar(50) NOT NULL,
  `url` varchar(200) NOT NULL,
  `type` varchar(50) NOT NULL,
  `result` varchar(100) NOT NULL,
  `addtime` varchar(50) NOT NULL,
  `addip` varchar(50) NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM;


CREATE TABLE IF NOT EXISTS `{user_type}` (
  `type_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `purview` text NOT NULL,
  `order` varchar(10) NOT NULL,
  `status` int(2) NOT NULL,
  `type` int(2) NOT NULL,
  `summary` varchar(200) NOT NULL,
  `remark` varchar(200) NOT NULL,
  `addtime` varchar(50) NOT NULL,
  `addip` varchar(50) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM ;