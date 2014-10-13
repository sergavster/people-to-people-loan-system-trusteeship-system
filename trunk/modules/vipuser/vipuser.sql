DROP TABLE IF EXISTS `{vip_card_type}`;
CREATE  TABLE IF NOT EXISTS `{vip_card_type}` (
  `id` INT NOT NULL AUTO_INCREMENT COMMENT '类型ID' ,
  `name` VARCHAR(40) NULL COMMENT '类型名称' ,
  `month_num` TINYINT NULL COMMENT '月数' ,
  `addtime` varchar(30) default NULL COMMENT '添加时间',
  `addip` varchar(30) default NULL COMMENT '添加ip',
  `updatetime` varchar(30) default NULL COMMENT '修改时间',
  `updateip` varchar(30) default NULL COMMENT '修改ip',
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) ,
  UNIQUE INDEX `month_num_UNIQUE` (`month_num` ASC) )
ENGINE = MyISAM;

DROP TABLE IF EXISTS `{vip_card}`;
CREATE  TABLE IF NOT EXISTS `{vip_card}` (
  `id` int(11) NOT NULL auto_increment,
  `flag` varchar(30) NOT NULL COMMENT '定义属性',
  `order` varchar(10) NULL COMMENT '排序',
  `city` varchar(50) NULL COMMENT '城市',
  `serial_number` VARCHAR(15) NOT NULL COMMENT 'VIP卡号' ,
  `batch` INT NOT NULL COMMENT '生成批次' ,
  `password` VARCHAR(50) NOT NULL COMMENT '密码' ,
  `create_time` INT NULL COMMENT '创建时间' ,
  `start_date` INT NULL COMMENT '有效期开始日期' ,
  `end_date` INT NULL COMMENT '有效期结束日期' ,
  `is_up_end_date` TINYINT(1) default 0 COMMENT '是否延期' ,
  `vct_name` VARCHAR(40) NULL COMMENT '充值卡类型' ,
  `month_num` TINYINT NULL COMMENT '有效月数' ,
  `open_time` INT NULL COMMENT '激活时间' ,
  `status` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '状态:0:未激活，1正常，2冻结，3停卡, 4封号' ,
  `freeze_time` INT NULL COMMENT '冻结时间' ,
  `freeze_day` INT NULL COMMENT '冻结天数' ,
  `freeze_times` TINYINT NULL default 0 COMMENT '冻结次数' ,
  `stop_time` INT NULL COMMENT '停卡时间' ,
  `stop_day` INT NULL COMMENT '停卡天数' ,
  `stop_times` TINYINT NULL default 0 COMMENT '停卡次数' ,
  `create_user` VARCHAR(20) NOT NULL COMMENT '生成者' ,
  `remark` text NULL COMMENT '备注' ,
  `hits` int(11) NULL COMMENT '点击次数',
  `addtime` varchar(30) NULL COMMENT '添加时间',
  `addip` varchar(30) NULL COMMENT '添加ip',
  `updatetime` varchar(30) NULL COMMENT '修改时间',
  `updateip` varchar(30) NULL COMMENT '修改ip',
   PRIMARY KEY  (`id`),
   UNIQUE KEY `idx_vip_sn` (`serial_number`))
COMMENT = 'VIP卡' ENGINE=MyISAM ;

DROP TABLE IF EXISTS `{vip_user}`;
CREATE  TABLE IF NOT EXISTS `{vip_user}` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `card_number` VARCHAR(15) NOT NULL COMMENT 'VIP卡号' ,
  `flag` varchar(30) NULL COMMENT '定义属性',
  `order` varchar(10) NULL COMMENT '排序',
  `hits` int(11) NULL COMMENT '点击次数',
  `addtime` varchar(30) NULL COMMENT '添加时间',
  `addip` varchar(30) NULL COMMENT '添加ip',
  `updatetime` varchar(30) NULL COMMENT '修改时间',
  `updateip` varchar(30) NULL COMMENT '修改ip',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `idx_vipu_u` (`user_id`))
COMMENT = 'VIP卡用户' ENGINE=MyISAM ;