CREATE TABLE IF NOT EXISTS `{liuyan}` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`title` varchar(100) NOT NULL DEFAULT '',  
	`name` varchar(100) NOT NULL DEFAULT '',
	`email` varchar(100) NOT NULL DEFAULT '',
	`company` varchar(100) NOT NULL DEFAULT '',
	`tel` varchar(100) NOT NULL DEFAULT '',
	`fax` varchar(100) NOT NULL DEFAULT '',
	`address` varchar(100) NOT NULL DEFAULT '',
	`type` varchar(50) NOT NULL DEFAULT '',
	`status` int(2) NOT NULL DEFAULT '0',
	`litpic` varchar(255) NOT NULL DEFAULT '',
	`content` text NOT NULL,
	`user_id` int(11) NOT NULL DEFAULT '0',
	`addtime` varchar(50) NOT NULL DEFAULT '',
	`addip` varchar(50) NOT NULL DEFAULT '',
	`reply` text NOT NULL,
	`reply_id` int(11) NOT NULL DEFAULT '0',
	`replytime` varchar(50) NOT NULL DEFAULT '',
	`replyip` varchar(50) NOT NULL DEFAULT '',
	PRIMARY KEY (`id`)) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `{liuyan_set}` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`name` varchar(100) NOT NULL DEFAULT '', 
`nid` varchar(100) NOT NULL DEFAULT '',  
`value` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)) ENGINE=MyISAM;

INSERT INTO `{liuyan_set}` (`id`, `name`, `nid`, `value`) VALUES
(1, '内容标题', 'name', '留言本'),
(2, '留言类型', 'type', '咨询,购买'),
(3, '留言状态', 'status', '1'),
(4, '显示页数', 'page', '20');
