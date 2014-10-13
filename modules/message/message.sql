CREATE TABLE IF NOT EXISTS `{message}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sent_user` int(11) NOT NULL DEFAULT '0' COMMENT '发送用户',
  `receive_user` int(11) NOT NULL DEFAULT '0' COMMENT '接收用户',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `status` int(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `type` varchar(50) NOT NULL DEFAULT '0' COMMENT '类型',
  `reply` varchar(250) NOT NULL DEFAULT '0' COMMENT '回复',
  `replytime` varchar(50) NOT NULL DEFAULT '0' COMMENT '回复时间',
  `sented` int(2) NOT NULL DEFAULT '0' COMMENT '是否保存在发件',
  `deltype` int(2) NOT NULL DEFAULT '0' COMMENT '是否删除',
  `content` text NOT NULL DEFAULT '' COMMENT '内容',
  `addtime` varchar(50) NOT NULL DEFAULT '',
  `addip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) COMMENT '短消息';


CREATE TABLE IF NOT EXISTS `{friends}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户',
  `friends_userid` int(11) NOT NULL DEFAULT '0' COMMENT '朋友',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `status` int(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `type` int(2) NOT NULL DEFAULT '0' COMMENT '类型',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '内容',
  `addtime` varchar(50) NOT NULL DEFAULT '',
  `addip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) COMMENT '好友';


CREATE TABLE IF NOT EXISTS `{friends_request}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户',
  `friends_userid` int(11) NOT NULL DEFAULT '0' COMMENT '朋友',
  `status` int(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `addtime` varchar(50) NOT NULL DEFAULT '',
  `addip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) COMMENT '好友记录';


insert into {linkage_type} set `name` = '短消息类型',`nid`="message_type",`order`='10';


