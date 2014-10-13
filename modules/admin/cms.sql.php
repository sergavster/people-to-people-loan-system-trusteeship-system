CREATE TABLE IF NOT EXISTS `{}` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `littitle` varchar(200) NOT NULL,
  `status` int(2) NOT NULL DEFAULT '0',
  `litpic` varchar(255) NOT NULL DEFAULT '',
  `flag` varchar(50) NOT NULL,
  `publish` varchar(50) NOT NULL,
  `is_jump` int(1) NOT NULL,
  `author` varchar(50) NOT NULL,
  `source` varchar(50) NOT NULL,
  `jumpurl` varchar(255) NOT NULL DEFAULT '',
  `summary` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  `hits` int(11) NOT NULL DEFAULT '0',
  `comment` int(11) NOT NULL DEFAULT '0',
  `is_comment` int(1) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `addtime` varchar(50) NOT NULL DEFAULT '',
  `addip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
			) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `[]` (
  `aid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL,
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM  ;