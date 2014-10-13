CREATE TABLE IF NOT EXISTS `{borrow_line}` (
   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL DEFAULT '0' COMMENT '所属站点',
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户名称',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `status` int(2) NOT NULL DEFAULT '0' COMMENT '状态',
  `order` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `hits` int(11) NOT NULL DEFAULT '0' COMMENT '点击次数',
  `litpic` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
  `flag` varchar(50) NOT NULL DEFAULT '' COMMENT '属性',
  
  `type` int(2) NOT NULL DEFAULT '0' COMMENT '借款类型',
  `borrow_use` int(10) NOT NULL DEFAULT '0' COMMENT '贷款用途',
  `borrow_qixian` int(10) NOT NULL DEFAULT '0' COMMENT '贷款期限',
  `province` int(10) NOT NULL DEFAULT '0' COMMENT '省份',
  `city` int(10) NOT NULL DEFAULT '0' COMMENT '城市',
  `area` int(10) NOT NULL DEFAULT '0' COMMENT '地区',
  `account` varchar(11) DEFAULT NULL COMMENT '贷款金额',
  `content` text COMMENT '详细说明',

  `pawn` varchar(2) DEFAULT NULL COMMENT '有无抵押',
  `tel` varchar(15) DEFAULT NULL COMMENT '电话',
  `sex` varchar(2) DEFAULT NULL COMMENT '性别',
  `xing` varchar(11) DEFAULT NULL COMMENT '姓',
  
  `verify_user` int(11) DEFAULT NULL COMMENT '审核人',
  `verify_time` varchar(50) NOT NULL DEFAULT '' COMMENT '审核时间',
  `verify_remark` varchar(255) NOT NULL,
  
  `addtime` varchar(50) NOT NULL DEFAULT '',
  `addip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM;

