CREATE TABLE IF NOT EXISTS `{attestation}` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户名称',
  `type_id` int(11) NOT NULL DEFAULT '0' COMMENT '上传的类型',
  `name` varchar(255) NOT NULL DEFAULT '',
  `status` int(2) NOT NULL DEFAULT '0' COMMENT '认证的状态',
  `litpic` varchar(255) NOT NULL DEFAULT '' COMMENT '认证的图片',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '认证的简介',
  `jifen` int(20) NOT NULL DEFAULT '0' COMMENT '认证的积分',
  `verify_time` varchar(32) NULL DEFAULT '' COMMENT '审核时间',
  `verify_user` int(11)  NULL DEFAULT '0' COMMENT '审核人',
  `verify_remark` varchar(250)  NULL DEFAULT '' COMMENT '审核备注',
  `addtime` varchar(50) NOT NULL DEFAULT '',
  `addip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

CREATE TABLE IF NOT EXISTS `{attestation_type}` (
  `type_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '类型名称',
  `order` varchar(10) NOT NULL,
  `status` int(2) NOT NULL,
  `jifen` int(20) NOT NULL DEFAULT '0' COMMENT '积分',
  `summary` varchar(200) NOT NULL COMMENT '简介',
  `remark` varchar(200) NOT NULL  COMMENT '备注',
  `addtime` varchar(50) NOT NULL,
  `addip` varchar(50) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM ;