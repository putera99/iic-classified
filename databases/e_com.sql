 CREATE TABLE IF NOT EXISTS `iic_ad` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `keywords` varchar(50) NOT NULL DEFAULT '' COMMENT '广告的关键字',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '广告名称',
  `type` varchar(30) NOT NULL DEFAULT '0' COMMENT '广告类型',
  `isclose` tinyint(1) NOT NULL DEFAULT '0' COMMENT '文字广告位是否新开窗口',
  `begintime` int(10) NOT NULL DEFAULT '0' COMMENT '广告投放开始日期',
  `endtime` int(10) NOT NULL DEFAULT '0' COMMENT '广告投放结束日期',
  `adcode` text NOT NULL COMMENT '广告代码',
  `posttime` int(10) NOT NULL DEFAULT '0' COMMENT '提交日期',
  `list` int(10) NOT NULL DEFAULT '0' COMMENT '排序值',
  `uid` mediumint(7) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `hits` mediumint(7) NOT NULL DEFAULT '0' COMMENT '点击量',
  `money` mediumint(6) NOT NULL DEFAULT '0' COMMENT '广告价格',
  `moneycard` mediumint(6) NOT NULL DEFAULT '0' COMMENT '点卡数',
  `ifsale` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已卖出',
  `autoyz` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否自动通过审核',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='广告信息表' ;

CREATE TABLE IF NOT EXISTS `iic_ad_user` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `ad_id` mediumint(7) NOT NULL DEFAULT '0' COMMENT '广告的ID',
  `u_uid` mediumint(7) NOT NULL DEFAULT '0' COMMENT '购买者ID',
  `u_username` varchar(30) NOT NULL DEFAULT '' COMMENT '购买这用户名',
  `u_day` smallint(4) NOT NULL DEFAULT '0' COMMENT '购买天数',
  `u_begintime` int(10) NOT NULL DEFAULT '0' COMMENT '',
  `u_endtime` int(10) NOT NULL DEFAULT '0' COMMENT '',
  `u_hits` mediumint(7) NOT NULL DEFAULT '0' COMMENT '点击',
  `u_yz` tinyint(1) NOT NULL DEFAULT '0' COMMENT '审核与否',
  `u_code` text NOT NULL COMMENT '广告代码',
  `u_money` mediumint(7) NOT NULL DEFAULT '0' COMMENT '价钱',
  `u_moneycard` mediumint(7) NOT NULL DEFAULT '0' COMMENT '点卡',
  `u_posttime` int(10) NOT NULL DEFAULT '0' COMMENT '提交日期',
  PRIMARY KEY (`id`),
  KEY `u_endtime` (`u_endtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='发布广告的用户表';

CREATE TABLE IF NOT EXISTS `iic_area` (
  `id` mediumint(7) unsigned NOT NULL AUTO_INCREMENT,
  `fup` mediumint(7) unsigned NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL DEFAULT '',
  `class` smallint(4) NOT NULL DEFAULT '0',
  `sons` smallint(4) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `admin` varchar(100) NOT NULL DEFAULT '',
  `list` int(10) NOT NULL DEFAULT '0',
  `listorder` tinyint(2) NOT NULL DEFAULT '0',
  `passwd` varchar(32) NOT NULL DEFAULT '',
  `logo` varchar(150) NOT NULL DEFAULT '',
  `descrip` text NOT NULL,
  `style` varchar(50) NOT NULL DEFAULT '',
  `template` text NOT NULL,
  `jumpurl` varchar(150) NOT NULL DEFAULT '',
  `maxperpage` tinyint(3) NOT NULL DEFAULT '0',
  `metakeywords` varchar(255) NOT NULL DEFAULT '',
  `metadescription` varchar(255) NOT NULL DEFAULT '',
  `allowcomment` tinyint(1) NOT NULL DEFAULT '0',
  `allowpost` varchar(150) NOT NULL DEFAULT '',
  `allowviewtitle` varchar(150) NOT NULL DEFAULT '',
  `allowviewcontent` varchar(150) NOT NULL DEFAULT '',
  `allowdownload` varchar(150) NOT NULL DEFAULT '',
  `forbidshow` tinyint(1) NOT NULL DEFAULT '0',
  `config` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fup` (`fup`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='城市和地区表';

CREATE TABLE IF NOT EXISTS `iic_addonarticle` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `aid` mediumint(8) DEFAULT '0' COMMENT '档案信息ID',
  `typeid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '所属栏目',
  `pages` smallint(4) NOT NULL DEFAULT '0' COMMENT '文章分页',
  `body` mediumtext NOT NULL COMMENT '文章内容',
  `redirecturl` varchar(255) NOT NULL DEFAULT '' COMMENT '跳转的URL',
  PRIMARY KEY (`id`),
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文章表';



CREATE TABLE IF NOT EXISTS `iic_arcatt` (
  `id` int(5) NOT NULL AUTO_INCREMENT COMMENT '属性记录ID',
  `att` char(10) NOT NULL DEFAULT '' COMMENT '属性代码',
  `attname` char(30) NOT NULL DEFAULT '' COMMENT '属性名称',
  PRIMARY KEY (`att`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT '属性数据表';

CREATE TABLE IF NOT EXISTS `iic_archives` (
  `id` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `typeid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '主分类',
  `typeid2` varchar(90) NOT NULL DEFAULT '0' COMMENT '次分类',
  `cid` varchar(20) DEFAULT '0' COMMENT '城市类别',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `sortrank` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '时间排序',
  `flag` varchar(20) DEFAULT NULL COMMENT '附属属性',
  `ismake` smallint(6) NOT NULL DEFAULT '0' COMMENT '是否审核 ',
  `channel` smallint(6) NOT NULL DEFAULT '1' COMMENT '所属模型ID',
  `arcrank` smallint(6) NOT NULL DEFAULT '0' COMMENT '阅读权限',
  `click` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '点击量',
  `money` smallint(6) NOT NULL DEFAULT '0' COMMENT '消费点数',
  `title` char(60) NOT NULL DEFAULT '' COMMENT '标题',
  `shorttitle` char(36) NOT NULL DEFAULT '' COMMENT '短标题',
  `color` char(7) NOT NULL DEFAULT '' COMMENT '标题颜色',
  `writer` char(20) NOT NULL DEFAULT '' COMMENT '作者',
  `source` char(30) NOT NULL DEFAULT '' COMMENT '来源',
  `litpic` char(60) NOT NULL DEFAULT '' COMMENT '缩略图',
  `pubdate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发布日期',
  `senddate` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发送日期',
  `keywords` char(30) NOT NULL DEFAULT '' COMMENT '关键字',
  `lastpost` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '最后回复',
  `scores` mediumint(8) NOT NULL DEFAULT '0' COMMENT '阅读权限',
  `star1` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '星星1',
  `star2` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '星星1',
  `star3` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '星星1',
  `star4` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '星星1',
  `star5` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '星星1',
  `goodpost` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '好评数',
  `badpost` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '差评数',
  `notpost` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启评论',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `filename` varchar(40) NOT NULL DEFAULT '' COMMENT '英文名',
  `uip` char(15) NOT NULL DEFAULT '0' COMMENT '用户IP',
  `lastview` int(10) NOT NULL DEFAULT '0' COMMENT '最后浏览时间',
  `editpwd` varchar(32) NOT NULL DEFAULT '' COMMENT '编辑密码',
  `showstart` int(11) NOT NULL DEFAULT '0' COMMENT '开始显示时间',
  `showend` int(11) NOT NULL DEFAULT '0' COMMENT '停止显示的时间',
  `editer` varchar(30) NOT NULL DEFAULT '' COMMENT '编辑者',
  `edittime` int(10) NOT NULL DEFAULT '0' COMMENT '编辑时间',
  `albumid` mediumint(7) NOT NULL DEFAULT '0' COMMENT '附属相册ID',
  `albumnum` varchar(50) NOT NULL DEFAULT '' COMMENT '附属图片张数',
  `itype` varchar(20) NOT NULL DEFAULT '' COMMENT '信息性质',
  `category` varchar(50) NOT NULL DEFAULT '' COMMENT '信息类别',
  `telephone` varchar(30) NOT NULL DEFAULT '' COMMENT '联系电话',
  `mobphone` varchar(12) NOT NULL DEFAULT '' COMMENT '联系手机',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '联系邮箱',
  `oicq` varchar(11) NOT NULL DEFAULT '' COMMENT '联系QQ',
  `msn` varchar(50) NOT NULL DEFAULT '' COMMENT '联系MSN',
  `maps` varchar(50) NOT NULL DEFAULT '' COMMENT '地图参数',
  `city_id` mediumint(7) NOT NULL DEFAULT '0' COMMENT '城市信息',
  `zone_id` mediumint(7) NOT NULL DEFAULT '0' COMMENT '县城或区',
  `street_id` mediumint(7) NOT NULL DEFAULT '0' COMMENT '街道或村镇',
  `position` varchar(100) DEFAULT '' COMMENT '个人位置',
  `contact` varchar(250) NOT NULL DEFAULT NULL COMMENT '联系方式',
  `ltdid` int(10) DEFAULT '0' COMMENT '公司信息',
  PRIMARY KEY (`id`),
  KEY `sortrank` (`sortrank`),
  KEY `mainindex` (`arcrank`,`typeid`,`channel`,`flag`,`uid`),
  KEY `lastpost` (`lastpost`,`scores`,`goodpost`,`badpost`,`notpost`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='整站档案';

CREATE TABLE IF NOT EXISTS `iic_arctype` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `reid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '上级栏目',
  `topid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '顶级栏目',
  `sortrank` smallint(5) unsigned NOT NULL DEFAULT '50' COMMENT '栏目排序',
  `typename` char(30) NOT NULL DEFAULT '' COMMENT '栏目名称',
  `ename` varchar(20) DEFAULT '' COMMENT '栏目的英文名',
  `typedir` char(60) NOT NULL DEFAULT '' COMMENT '链接地址',
  `issend` smallint(6) NOT NULL DEFAULT '0' COMMENT '是否支持投稿',
  `channeltype` smallint(6) DEFAULT '1' COMMENT '系统模块',
  `uid` int(11) DEFAULT '0' COMMENT '用户ID',
  `cid` varchar(20) DEFAULT '0' COMMENT '城市类别',
  `maxpage` smallint(6) NOT NULL DEFAULT '-1' COMMENT '',
  `ispart` smallint(6) NOT NULL DEFAULT '0' COMMENT '栏目属性',
  `corank` smallint(6) NOT NULL DEFAULT '0' COMMENT '浏览权限',
  `tempindex` char(50) NOT NULL DEFAULT '' COMMENT '封面模板',
  `templist` char(50) NOT NULL DEFAULT '' COMMENT '列表模板',
  `temparticle` char(50) NOT NULL DEFAULT '' COMMENT '内页面板',
  `namerule` char(50) NOT NULL DEFAULT '' COMMENT '文章命名规则',
  `namerule2` char(50) NOT NULL DEFAULT '' COMMENT '列表命名规则',
  `modname` char(20) NOT NULL DEFAULT '' COMMENT '模板风格名',
  `description` char(150) NOT NULL DEFAULT '' COMMENT '描述',
  `keywords` varchar(60) NOT NULL DEFAULT '' COMMENT '关键字',
  `seotitle` varchar(80) NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `moresite` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否启用子域名',
  `sitepath` char(60) NOT NULL DEFAULT '' COMMENT '栏目地址',
  `siteurl` char(50) NOT NULL DEFAULT '' COMMENT '绑定域名',
  `ishidden` smallint(6) NOT NULL DEFAULT '0' COMMENT '是否隐藏',
  `cross` tinyint(1) NOT NULL DEFAULT '0' COMMENT '栏目交',
  `hits` int(8) NOT NULL DEFAULT '0' COMMENT '栏目点击',
  `posttime` int(11) NOT NULL DEFAULT '0' COMMENT '提交时间',
  `mtime` int(11) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `crossid` text COMMENT '交叉栏目ID',
  `content` text COMMENT '栏目内容 ',
  `smalltypes` text COMMENT '',
  PRIMARY KEY (`id`),
  KEY `reid` (`reid`,`ename`,`channeltype`,`ispart`,`corank`,`topid`,`ishidden`),
  KEY `sortrank` (`sortrank`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='系用栏目列表';


CREATE TABLE IF NOT EXISTS `iic_act_city` (
	`id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '城市ID',
	`ctitle` varchar(50) DEFAULT NULL COMMENT '城市中文名',
	`cename` varchar(20) DEFAULT '' COMMENT '城市英文名',
	`ctime` int(11) DEFAULT '0' COMMENT '创建时间',
	`content` text COMMENT '城市简介',
	`status` tinyint(1) DEFAULT '1' COMMENT '启用状态',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='已经开通的城市';

CREATE TABLE IF NOT EXISTS `iic_addon_jobs` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `aid` mediumint(8) NOT NULL DEFAULT '' COMMENT '档案信息ID',
  `joblocated` varchar(100) DEFAULT '' COMMENT '工作地区',
  `Experience` varchar(50) NOT NULL DEFAULT '' COMMENT '工作经验',
  `salary` varchar(20) DEFAULT '0' COMMENT '薪水',
  `content` mediumtext NOT NULL COMMENT '工作描述',
  PRIMARY KEY (`id`),
  KEY `aid` (`aid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='招聘与求职';


CREATE TABLE IF NOT EXISTS `iic_addon_realestate` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `aid` mediumint(8) NOT NULL DEFAULT '' COMMENT '档案信息ID',
  `price` varchar(20) DEFAULT '0' COMMENT '价格',
  `content` mediumtext NOT NULL COMMENT '描述',
  PRIMARY KEY (`id`),
  KEY `aid` (`aid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='房产信息';


CREATE TABLE IF NOT EXISTS `iic_addon_commerce` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '',
	`aid` mediumint(8) NOT NULL DEFAULT '' COMMENT '档案信息ID',
	`quantity` varchar(50) NOT NULL DEFAULT '' COMMENT '数量',
	`price` varchar(20) DEFAULT '0' COMMENT '单位价格',
	`content` mediumtext NOT NULL COMMENT '描述',
	PRIMARY KEY (`id`)
	KEY `aid` (`aid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='买卖';

CREATE TABLE IF NOT EXISTS `iic_addon_agents` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '',
	`aid` mediumint(8) NOT NULL DEFAULT '' COMMENT '档案信息ID',
	`content` mediumtext NOT NULL COMMENT '描述',
	PRIMARY KEY (`id`)
	KEY `aid` (`aid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='折扣信息';

CREATE TABLE IF NOT EXISTS `iic_addon_personals` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '',
	`aid` mediumint(8) NOT NULL DEFAULT '' COMMENT '档案信息ID',
	`content` mediumtext NOT NULL COMMENT '描述',
	PRIMARY KEY (`id`)
	KEY `aid` (`aid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='交友信息';

CREATE TABLE IF NOT EXISTS `iic_addon_services` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '',
	`aid` mediumint(8) NOT NULL DEFAULT '' COMMENT '档案信息ID',
	`content` mediumtext NOT NULL COMMENT '描述',
	PRIMARY KEY (`id`)
	KEY `aid` (`aid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='服务信息';

CREATE TABLE `iic_ltd` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL default '0' COMMENT '用户ID',
  `title` varchar(50) DEFAULT NULL COMMENT '公司名称',
  `passwd` varchar(32) NOT NULL COMMENT '管理密码',
  `info` text NOT NULL COMMENT '公司信息',
  `jyfw` varchar(500) NOT NULL COMMENT '营经范围',
  `yyzz` varchar(200) NOT NULL COMMENT '营业执照副本',
  `zstitle1` varchar(200) NOT NULL COMMENT '书证标题',
  `zssm1` varchar(500) NOT NULL COMMENT '证书说明',
  `zspic1` varchar(200) NOT NULL COMMENT '证书图片',
  `zstitle2` varchar(200) NOT NULL COMMENT '书证标题',
  `zssm2` varchar(500) NOT NULL COMMENT '证书说明',
  `zspic2` varchar(200) NOT NULL COMMENT '证书图片',
  `zstitle3` varchar(200) NOT NULL COMMENT '书证标题',
  `zssm3` varchar(500) NOT NULL COMMENT '证书说明',
  `zspic3` varchar(200) NOT NULL COMMENT '证书图片',
  `zstitle4` varchar(200) NOT NULL COMMENT '书证标题',
  `zssm4` varchar(500) NOT NULL COMMENT '证书说明',
  `zspic4` varchar(200) NOT NULL COMMENT '证书图片',
  `ctime` int(11) NOT NULL COMMENT '创建时间',
  `mtime` int(11) NOT NULL COMMENT '修改时间',
  `cip` varchar(20) NOT NULL COMMENT '创建IP',
  `mip` varchar(20) NOT NULL COMMENT '修改IP',
  `status` tinyint(1) DEFAULT '0' COMMENT '是否有效',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公司信息';




