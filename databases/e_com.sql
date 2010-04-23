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

CREATE TABLE IF NOT EXISTS `iic_addon_article` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `aid` mediumint(8) DEFAULT '0' COMMENT '档案信息ID',
  `typeid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '所属栏目',
  `pages` smallint(4) NOT NULL DEFAULT '0' COMMENT '文章分页',
  `body` mediumtext NOT NULL COMMENT '文章内容',
  `redirecturl` varchar(255) NOT NULL DEFAULT '' COMMENT '跳转的URL',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文章表';



CREATE TABLE IF NOT EXISTS `iic_arcatt` (
  `id` int(5) NOT NULL AUTO_INCREMENT COMMENT '属性记录ID',
  `att` char(10) NOT NULL DEFAULT '' COMMENT '属性代码',
  `attname` char(30) NOT NULL DEFAULT '' COMMENT '属性名称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT '属性数据表';

CREATE TABLE IF NOT EXISTS `iic_archives` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `typeid` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '主分类',
  `typeid2` varchar(90) NOT NULL DEFAULT '0' COMMENT '次分类',
  `industry` smallint(50) NOT NULL COMMENT '行业ID',
  `bycity` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '所在城市',
  `cid` varchar(20) DEFAULT '0' COMMENT '城市类别',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
  `flag` varchar(20) DEFAULT NULL COMMENT '附属属性',
  `ismake` smallint(6) NOT NULL DEFAULT '0' COMMENT '是否审核 ',
  `channel` smallint(6) NOT NULL DEFAULT '1' COMMENT '所属模型ID',
  `arcrank` smallint(6) NOT NULL DEFAULT '0' COMMENT '阅读权限',
  `click` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '点击量',
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
  `fax` char(100) NOT NULL DEFAULT '' COMMENT '传真号码',
  `mobphone` varchar(12) NOT NULL DEFAULT '' COMMENT '联系手机',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '联系邮箱',
  `oicq` varchar(11) NOT NULL DEFAULT '' COMMENT '联系QQ',
  `msn` varchar(50) NOT NULL DEFAULT '' COMMENT '联系MSN',
  `maps` varchar(50) NOT NULL DEFAULT '' COMMENT '地图参数',
  `city_id` mediumint(7) NOT NULL DEFAULT '0' COMMENT '城市信息',
  `zone_id` mediumint(7) NOT NULL DEFAULT '0' COMMENT '县城或区',
  `street_id` mediumint(7) NOT NULL DEFAULT '0' COMMENT '街道或村镇',
  `position` varchar(100) DEFAULT '' COMMENT '个人位置',
  `contact` varchar(250) NOT NULL DEFAULT '' COMMENT '联系地址',
  `ltdid` int(10) DEFAULT '0' COMMENT '公司信息',
  `linkman` varchar(50) NOT NULL DEFAULT '' COMMENT '联系人',
  PRIMARY KEY (`id`),
  KEY `mainindex` (`arcrank`,`typeid`,`channel`,`flag`,`uid`),
  KEY `lastpost` (`lastpost`,`goodpost`,`badpost`,`notpost`)
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

CREATE TABLE IF NOT EXISTS `iic_fair_city` (
	`id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '城市ID',
	`ctitle` varchar(50) DEFAULT NULL COMMENT '城市中文名',
	`cename` varchar(20) DEFAULT '' COMMENT '城市英文名',
	`ctime` int(11) DEFAULT '0' COMMENT '创建时间',
	`status` tinyint(1) DEFAULT '1' COMMENT '启用状态',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='展会城市';

CREATE TABLE IF NOT EXISTS `iic_addon_jobs` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `aid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '档案信息ID',
  `joblocated` varchar(100) DEFAULT '' COMMENT '工作地区',
  `Experience` varchar(50) NOT NULL DEFAULT '' COMMENT '工作经验',
  `salary` varchar(20) DEFAULT '0' COMMENT '薪水',
  `content` mediumtext NOT NULL COMMENT '工作描述',
  PRIMARY KEY (`id`),
  KEY `aid` (`aid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='招聘与求职';


CREATE TABLE IF NOT EXISTS `iic_addon_realestate` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `aid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '档案信息ID',
  `price` varchar(20) DEFAULT '0' COMMENT '价格',
  `content` mediumtext NOT NULL COMMENT '描述',
  PRIMARY KEY (`id`),
  KEY `aid` (`aid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='房产信息';


CREATE TABLE IF NOT EXISTS `iic_addon_commerce` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '',
	`aid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '档案信息ID',
	`quantity` varchar(50) NOT NULL DEFAULT '' COMMENT '数量',
	`price` varchar(20) DEFAULT '0' COMMENT '单位价格',
	`content` mediumtext NOT NULL COMMENT '描述',
	PRIMARY KEY (`id`),
	KEY `aid` (`aid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='买卖';

CREATE TABLE IF NOT EXISTS `iic_addon_agents` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '',
	`aid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '档案信息ID',
	`content` mediumtext NOT NULL COMMENT '描述',
	PRIMARY KEY (`id`),
	KEY `aid` (`aid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='折扣信息';

CREATE TABLE IF NOT EXISTS `iic_addon_personals` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '',
	`aid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '档案信息ID',
	`content` mediumtext NOT NULL COMMENT '描述',
	PRIMARY KEY (`id`),
	KEY `aid` (`aid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='交友信息';

CREATE TABLE IF NOT EXISTS `iic_addon_services` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '',
	`aid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '档案信息ID',
	`content` mediumtext NOT NULL COMMENT '描述',
	PRIMARY KEY (`id`),
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


CREATE TABLE IF NOT EXISTS `iic_feedback` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fid` int(10) DEFAULT '0' COMMENT '上层ID',
  `xid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '资源ID',
  `itype` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '资源类别',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `username` char(20) NOT NULL DEFAULT '' COMMENT '用户名',
  `arctitle` varchar(60) NOT NULL DEFAULT '' COMMENT '标题',
  `ip` char(15) NOT NULL DEFAULT '' COMMENT 'IP',
  `ischeck` smallint(6) NOT NULL DEFAULT '1' COMMENT '是否审核',
  `ctime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  `bad` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '反对人数',
  `good` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '支持人数',
  `face` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '表情',
  `msg` text COMMENT '留言内容',
  PRIMARY KEY (`id`),
  KEY `xid` (`xid`,`ischeck`,`itype`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='通用留言板' ;

CREATE TABLE IF NOT EXISTS `iic_pm` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `touid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '发送者ID',
  `xid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '资源ID',
  `itype` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '资源类别',
  `fromuid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '接收方ID',
  `tousername` varchar(15) NOT NULL DEFAULT '' COMMENT '发送方用户名',
  `type` enum('rebox','sebox','public') NOT NULL DEFAULT 'rebox' COMMENT '信件类别',
  `ifnew` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否新',
  `title` varchar(130) NOT NULL DEFAULT '' COMMENT '标题',
  `ctime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发送时间',
  `content` text NOT NULL COMMENT '内容',
  PRIMARY KEY (`id`),
  KEY `touid` (`touid`),
  KEY `fromuid` (`fromuid`),
  KEY `type` (`type`,`xid`,`itype`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='短信息' ;

CREATE TABLE IF NOT EXISTS `iic_ok_bad` (
  `id` int(11) NOT NULL auto_increment COMMENT '主键',
  `uid` mediumint(8) NOT NULL COMMENT '用户ID',
  `itype` mediumint(8) default NULL COMMENT '内容类型',
  `xid` mediumint(8) NOT NULL COMMENT '资源ID',
  `ok` tinyint(1) default '0' COMMENT '支持',
  `bad` tinyint(1) default '0' COMMENT '反对',
  `ctime` int(11) NOT NULL COMMENT '创建时间',
  `ip` varchar(20) NOT NULL default '' COMMENT '用户IP',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支持或反对';


CREATE TABLE IF NOT EXISTS `iic_report` (
  `id` mediumint(8) unsigned NOT NULL auto_increment COMMENT '主键',
  `tid` mediumint(8) unsigned NOT NULL default '0' COMMENT '信息ID',
  `itype` varchar(15) NOT NULL default '' COMMENT '资源类型',
  `is_new` tinyint(1) NOT NULL default '1' COMMENT '是否为新',
  `num` smallint(6) unsigned NOT NULL default '0' COMMENT '被举报次数',
  `dateline` int(10) unsigned NOT NULL default '0' COMMENT '被举报时间',
  `lasttime` int(10) unsigned NOT NULL default '0' COMMENT '最近时间',
  `ip` varchar(20) NOT NULL default '' COMMENT '用户ID',
  `reason` text NOT NULL COMMENT '被举报原因',
  `uids` text NOT NULL COMMENT '举报者ID',
  `is_show` tinyint(1) NOT NULL default '1' COMMENT '是否处理',
  PRIMARY KEY  (`id`),
  KEY `tid` (`tid`,`itype`,`num`,`dateline`),
  KEY `is_new` (`is_new`,`dateline`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户举报表';

CREATE TABLE IF NOT EXISTS `iic_flink` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `sortrank` smallint(6) NOT NULL DEFAULT '0' COMMENT '排序',
  `url` char(60) NOT NULL DEFAULT '' COMMENT '网址',
  `webname` char(30) NOT NULL DEFAULT '' COMMENT '网站名称',
  `msg` char(200) NOT NULL DEFAULT '' COMMENT '简介',
  `email` char(50) NOT NULL DEFAULT '' COMMENT '联系email',
  `logo` char(60) NOT NULL DEFAULT '' COMMENT 'LOGO',
  `ctime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建日期',
  `itype` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '放置位置',
  `ischeck` smallint(6) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='友情链接' ;

CREATE TABLE IF NOT EXISTS `iic_album` (
  `id` mediumint(8) unsigned NOT NULL auto_increment COMMENT '相册ID',
  `itype` smallint(5) NOT NULL DEFAULT '0' COMMENT '相册类别',
  `aid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '档案信息ID',
  `albumname` varchar(50) NOT NULL default '' COMMENT '相册名字',
  `uid` mediumint(8) unsigned NOT NULL default '0' COMMENT '用户ID',
  `username` varchar(15) NOT NULL default '' COMMENT '用户名',
  `dateline` int(10) unsigned NOT NULL default '0' COMMENT '建立时间',
  `updatetime` int(10) unsigned NOT NULL default '0' COMMENT '修改时间',
  `picnum` smallint(6) unsigned NOT NULL default '0' COMMENT '照片数量',
  `pic` varchar(60) NOT NULL default '' COMMENT '封面照片',
  `picflag` tinyint(1) NOT NULL default '0' COMMENT '是否有图片',
  `friend` tinyint(1) NOT NULL default '0' COMMENT '隐私设置',
  `password` varchar(10) NOT NULL default '' COMMENT '相册密码',
  `target_ids` text NOT NULL COMMENT '允许查看相的用户ID',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`,`updatetime`),
  KEY `friend` (`friend`,`updatetime`),
  KEY `updatetime` (`updatetime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户相册';

CREATE TABLE IF NOT EXISTS `iic_pic` (
  `id` mediumint(8) NOT NULL auto_increment COMMENT '图片ID',
  `album_id` mediumint(8) unsigned NOT NULL default '0' COMMENT '所属相册',
  `uid` mediumint(8) unsigned NOT NULL default '0' COMMENT '用户ID',
  `username` varchar(15) NOT NULL default '' COMMENT '所属用户',
  `dateline` int(10) unsigned NOT NULL default '0' COMMENT '上传时间',
  `postip` varchar(20) NOT NULL COMMENT '上传ip',
  `filename` varchar(100) NOT NULL COMMENT '文件名',
  `title` varchar(255) NOT NULL COMMENT '图片标题',
  `type` varchar(20) NOT NULL COMMENT '图片类型',
  `size` int(10) unsigned NOT NULL default '0' COMMENT '图片大小',
  `filepath` varchar(60) NOT NULL COMMENT '图片路径',
  `thumb` tinyint(1) NOT NULL default '0' COMMENT '缩略图',
  `remote` tinyint(1) NOT NULL default '0' COMMENT '远程图片',
  `hot` mediumint(8) unsigned NOT NULL default '0' COMMENT '热度',
  `click_6` smallint(6) unsigned NOT NULL default '0' COMMENT '漂亮表态数',
  `click_7` smallint(6) unsigned NOT NULL default '0' COMMENT '酷毙表态数',
  `click_8` smallint(6) unsigned NOT NULL default '0' COMMENT '雷人表态数',
  `click_9` smallint(6) unsigned NOT NULL default '0' COMMENT '鲜花表态数',
  `click_10` smallint(6) unsigned NOT NULL default '0' COMMENT '鸡蛋表态数',
  PRIMARY KEY  (`id`),
  KEY `albumid` (`album_id`,`dateline`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户图片';

CREATE TABLE IF NOT EXISTS `iic_comment` (
  `id` mediumint(8) unsigned NOT NULL auto_increment COMMENT '主键',
  `uid` mediumint(8) unsigned NOT NULL default '0' COMMENT '用户ID',
  `xid` mediumint(8) unsigned NOT NULL default '0' COMMENT '资源ID',
  `types` varchar(20) NOT NULL default '' COMMENT '资源类型',
  `hot` mediumint(8) unsigned NOT NULL default '0' COMMENT '热度',
  `click_6` smallint(6) unsigned NOT NULL default '0' COMMENT '漂亮表态数',
  `click_7` smallint(6) unsigned NOT NULL default '0' COMMENT '酷毙表态数',
  `click_8` smallint(6) unsigned NOT NULL default '0' COMMENT '雷人表态数',
  `click_9` smallint(6) unsigned NOT NULL default '0' COMMENT '鲜花表态数',
  `click_10` smallint(6) unsigned NOT NULL default '0' COMMENT '鸡蛋表态数',
  `username` varchar(15) NOT NULL default '' COMMENT '用户名',
  `ip` varchar(20) NOT NULL default '' COMMENT 'IP',
  `dateline` int(10) unsigned NOT NULL default '0' COMMENT '时间',
  `message` text NOT NULL COMMENT '信息',
  PRIMARY KEY  (`id`),
  KEY `xid` (`xid`,`types`,`dateline`),
  KEY `uid` (`uid`,`types`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='通用评论';

CREATE TABLE IF NOT EXISTS `iic_doing` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `uid` mediumint(8) unsigned NOT NULL default '0' COMMENT '用户ID',
  `username` varchar(15) NOT NULL default '' COMMENT '用户名',
  `tags` varchar(20) NOT NULL default '' COMMENT '关键字',
  `from` varchar(20) NOT NULL default '' COMMENT '发表来源',
  `dateline` int(10) unsigned NOT NULL default '0' COMMENT '时间',
  `message` text NOT NULL COMMENT '记录的内容',
  `ip` varchar(20) NOT NULL default '' COMMENT '记录的发表IP',
  `replynum` int(10) unsigned NOT NULL default '0' COMMENT ' 记录回复数',
  `mood` smallint(6) NOT NULL default '0' COMMENT '迷你博客的心情',
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`,`dateline`),
  KEY `dateline` (`dateline`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='个人心情';

CREATE TABLE IF NOT EXISTS `iic_event` (
  `id` mediumint(8) unsigned NOT NULL auto_increment COMMENT '',
  `aid` mediumint(8) unsigned NOT NULL default '0' COMMENT '资源ID',
  `deadline` int(10) unsigned NOT NULL default '0' COMMENT '报名截止时间',
  `starttime` int(10) unsigned NOT NULL default '0' COMMENT '活动开始时间',
  `endtime` int(10) unsigned NOT NULL default '0' COMMENT '活动结束时间',
  `public` tinyint(3) NOT NULL default '0' COMMENT '公开状态:0私密,1半公开,2完全公开',
  `membernum` mediumint(8) unsigned NOT NULL default '0' COMMENT '成员数',
  `attending` text default '' COMMENT '参与',
  `mightatten` text default '' COMMENT '可能参与',
  `notattending` text default '' COMMENT '不参与',
  `detail` text NOT NULL COMMENT '活动详细内容',
  PRIMARY KEY  (`id`),
  KEY `aid` (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='活动';

CREATE TABLE IF NOT EXISTS `iic_fair` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
	`aid` mediumint(8) unsigned NOT NULL default '0' COMMENT '资源ID',
	`starttime` int(11) NOT NULL DEFAULT '0' COMMENT '开始时间',
	`endtime` int(11) NOT NULL DEFAULT '0' COMMENT '结束时间',
	`visitor` mediumtext COMMENT '参展游客',
	`exhibitor` mediumtext COMMENT '参展商',
	`product` varchar(200) NOT NULL DEFAULT '' COMMENT '产品相册ID',
	`website` char(100) NOT NULL DEFAULT '' COMMENT '官方网站',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='展会';

CREATE TABLE IF NOT EXISTS `iic_group` (
  `id` mediumint(8) unsigned NOT NULL auto_increment COMMENT '群组ID',
  `groupname` varchar(40) NOT NULL COMMENT '群组名称',
  `tags` varchar(50) NOT NULL default '' COMMENT '相关标签',
  `membernum` mediumint(8) unsigned NOT NULL default '0' COMMENT '用户数',
  `threadnum` mediumint(8) unsigned NOT NULL default '0' COMMENT '主题数',
  `postnum` mediumint(8) unsigned NOT NULL default '0' COMMENT '回复数',
  `close` tinyint(1) NOT NULL default '0' COMMENT '群组关闭',
  `announcement` text NOT NULL COMMENT '群组公告',
  `pic` varchar(150) NOT NULL default '' COMMENT '群组图片',
  `closeapply` tinyint(1) NOT NULL default '0' COMMENT '关闭加入',
  `joinperm` tinyint(1) NOT NULL default '0' COMMENT '加入权限',
  `viewperm` tinyint(1) NOT NULL default '0' COMMENT '浏览权限',
  `threadperm` tinyint(1) NOT NULL default '0' COMMENT '发表话题权限',
  `postperm` tinyint(1) NOT NULL default '0' COMMENT '回复话题权限',
  `recommend` tinyint(1) NOT NULL default '0' COMMENT '推荐时间',
  `moderator` varchar(255) NOT NULL default '' COMMENT '群组群主',
  `cat_id` mediumint(8) default NULL COMMENT '分类ID',
  `fcat_id` varchar(100) default NULL COMMENT '附属分类ID',
  `attr` varchar(255) default NULL COMMENT '附属属性',
  `ctime` int(11) NOT NULL COMMENT '建创时间',
  `lasttime` int(11) NOT NULL COMMENT '最后活跃时间',
  `en` char(20) default '' COMMENT '组英文名',
  `description` varchar(255) default NULL COMMENT '群简介',
  PRIMARY KEY  (`id`),
  KEY `groupname` (`groupname`),
  KEY `membernum` (`membernum`),
  KEY `threadnum` (`threadnum`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8  COMMENT='群组信息';

CREATE TABLE IF NOT EXISTS `iic_mtaginvite` (
  `id` mediumint(8) unsigned NOT NULL auto_increment COMMENT '邀请ID',
  `uid` mediumint(8) unsigned NOT NULL default '0' COMMENT '用户ID',
  `username` char(15) NOT NULL default '' COMMENT '用户名',
  `tagid` mediumint(8) unsigned NOT NULL default '0' COMMENT '群组ID',
  `fromuid` mediumint(8) unsigned NOT NULL default '0' COMMENT '受邀用户ID',
  `fromusername` char(15) NOT NULL default '' COMMENT '受邀用户名',
  `dateline` int(10) unsigned NOT NULL default '0' COMMENT '邀请时间',
  `endtime` int(10) unsigned NOT NULL default '0' COMMENT '邀请有效期',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='邀请加入群组';

CREATE TABLE IF NOT EXISTS `iic_mtag_cat` (
  `id` mediumint(8) NOT NULL auto_increment COMMENT '分类ID',
  `pid` mediumint(8) NOT NULL COMMENT '父级ID',
  `title` char(20) NOT NULL COMMENT '分类标题',
  `num` mediumint(8) NOT NULL COMMENT '排序',
  `attr` varchar(255) default NULL COMMENT '附属属性',
  `is_show` tinyint(1) NOT NULL default '1' COMMENT '是否显示',
  `description` varchar(255) default NULL COMMENT '描述',
  `keywords` varchar(255) default NULL COMMENT '关键字',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='群组分类';

CREATE TABLE IF NOT EXISTS `iic_mtag_collection` (
  `id` mediumint(8) NOT NULL auto_increment COMMENT '收藏ID',
  `tagid` mediumint(8) NOT NULL COMMENT '群组ID',
  `listid` int(11) DEFAULT '0' COMMENT '共用列表ID',
  `uid` mediumint(8) NOT NULL COMMENT '用户ID',
  `username` char(15) NOT NULL default '' COMMENT '用户名',
  `types` mediumint(8) default NULL COMMENT '内容类型',
  `tid` mediumint(8) default NULL COMMENT '资源ID',
  `ctime` int(11) NOT NULL COMMENT '收藏时间',
  `is_show` tinyint(1) NOT NULL default '1' COMMENT '是否显示',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='群组收藏';

CREATE TABLE IF NOT EXISTS `iic_user_collection` (
  `id` mediumint(8) NOT NULL auto_increment COMMENT '收藏ID',
  `listid` int(11) DEFAULT '0' COMMENT '共用列表ID',
  `uid` mediumint(8) NOT NULL COMMENT '用户ID',
  `username` char(15) NOT NULL default '' COMMENT '用户名',
  `types` mediumint(8) default NULL COMMENT '内容类型',
  `tid` mediumint(8) default NULL COMMENT '资源类型',
  `ctime` int(11) NOT NULL COMMENT '收藏时间',
  `is_show` tinyint(1) NOT NULL default '1' COMMENT '私有或公开',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户收藏';

CREATE TABLE IF NOT EXISTS `iic_commlist` (
	`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '列表ID',
	`title` varchar(50) DEFAULT NULL COMMENT '列表标题',
	`uid` int(11) DEFAULT '0' COMMENT '用户ID',
	`username` varchar(15) DEFAULT '' COMMENT '用户名',
	`msg` varchar(255) DEFAULT '' COMMENT '简介',
	`cip` varchar(15) DEFAULT '' COMMENT '创建IP',
	`ctime` int(11) DEFAULT '0' COMMENT '创建时间',
	`mtime` int(11)	DEFAULT '0' COMMENT '修改时间',
	`status` tinyint(1) DEFAULT '0' COMMENT '状态',
	PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='共用列表';



CREATE TABLE IF NOT EXISTS `iic_tagspace` (
  `id` mediumint(8) unsigned NOT NULL auto_increment COMMENT '主键',
  `tagid` mediumint(8) unsigned NOT NULL default '0' COMMENT '群组ID',
  `uid` mediumint(8) unsigned NOT NULL default '0' COMMENT '用户ID',
  `username` char(15) NOT NULL default '' COMMENT '用户名',
  `grade` smallint(6) NOT NULL default '0' COMMENT '成员级别',
  `ctime` int(11) NOT NULL COMMENT '加入时间',
  PRIMARY KEY  (`id`),
  KEY `grade` (`tagid`,`grade`),
  KEY `uid` (`uid`,`grade`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户群组关系表';


CREATE TABLE IF NOT EXISTS `iic_tags_cat` (
  `id` mediumint(8) NOT NULL auto_increment COMMENT '分类ID',
  `title` char(20) NOT NULL COMMENT '分类名称',
  `pid` mediumint(8) NOT NULL default '0' COMMENT '上级',
  `num` smallint(3) NOT NULL default '0' COMMENT '排列',
  `keywords` varchar(255) NOT NULL COMMENT '关键字',
  `description` varchar(25) NOT NULL COMMENT '描述',
  `is_show` tinyint(1) NOT NULL default '1' COMMENT '是否使用',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='关键字分类表';

CREATE TABLE IF NOT EXISTS `iic_tags` (
  `id` mediumint(8) NOT NULL auto_increment COMMENT '关键字ID',
  `tcatid` mediumint(8) NOT NULL default '0' COMMENT '主要分类',
  `ftcatid` char(20) NOT NULL default '0' COMMENT '次级分类',
  `uid` mediumint(8) NOT NULL COMMENT '创建者ID',
  `tagsname` char(30) default NULL COMMENT '关键字',
  `mr` smallint(6) default '0' COMMENT '美容个数',
  `fs` smallint(6) default '0' COMMENT '服饰个数',
  `book` smallint(6) default '0' COMMENT '读书个数',
  `move` smallint(6) default '0' COMMENT '电影个数',
  `music` smallint(6) default '0' COMMENT '音乐个数',
  `blog` smallint(6) default '0' COMMENT 'BLOG个数',
  `event` smallint(6) default '0' COMMENT '活动个数',
  `dateline` int(11) default NULL COMMENT '创建时间',
  `close` tinyint(1) NOT NULL default '0' COMMENT '是否锁定',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `tagsname` (`tagsname`),
  KEY `id` (`id`,`uid`),
  KEY `iic` (`mr`,`fs`,`book`,`move`,`music`,`event`),
  KEY `name` (`tagsname`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='tags类资源个数';

CREATE TABLE IF NOT EXISTS `iic_tags_link` (
  `id` mediumint(8) unsigned NOT NULL auto_increment COMMENT '主键',
  `tagsid` mediumint(8) NOT NULL COMMENT '关键字ID',
  `types` varchar(10) NOT NULL COMMENT '资源类型',
  `tid` mediumint(8) default NULL COMMENT '资源ID',
  `is_ok` tinyint(1) NOT NULL default '1' COMMENT '是否失效',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `types` (`types`,`tid`),
  KEY `tagsid` (`tagsid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='tags资源关系';


CREATE TABLE IF NOT EXISTS `iic_thought` (
  `id` int(11) unsigned NOT NULL auto_increment COMMENT '主键',
  `types` mediumint(8) NOT NULL COMMENT '关注的类别',
  `tid` int(11) NOT NULL default '0' COMMENT '资源ID',
  `uid` mediumint(8) NOT NULL COMMENT '用户ID',
  `click` mediumint(6) default '0' COMMENT '点击次数',
  `hot` tinyint(1) default '0' COMMENT '关注程度',
  `ctime` int(11) NOT NULL COMMENT '关注起始时间',
  `mtime` int(11) NOT NULL COMMENT '最近关注时间',
  `ip` varchar(20) NOT NULL default '' COMMENT '点击次数',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户关注';

CREATE TABLE IF NOT EXISTS `iic_settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '键名',
  `values` text COMMENT '键值',
  `about` varchar(50) NOT NULL DEFAULT '' COMMENT '说明',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统设置';

CREATE TABLE IF NOT EXISTS `iic_stat` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `xid` int(8) NOT NULL COMMENT '资源ID',
  `mon` int(11) NOT NULL COMMENT '月份',
  `stype` char(10) NOT NULL COMMENT '资源类别',
  `d01` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d02` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d03` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d04` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d05` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d06` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d07` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d08` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d09` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d10` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d11` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d12` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d13` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d14` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d15` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d16` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d17` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d18` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d19` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d20` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d21` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d22` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d23` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d24` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d25` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d26` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d27` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d28` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d29` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d30` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  `d31` int(8) NOT NULL DEFAULT '0' COMMENT '日期',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mon` (`xid`,`mon`,`stype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='点击统计综合' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `iic_post` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '评论ID',
  `qid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '回复ID',
  `l` mediumint(8) unsigned NOT NULL DEFAULT '1' COMMENT '楼次',
  `requery` varchar(20) NOT NULL DEFAULT '0' COMMENT '是否引用',
  `qidstr` varchar(255) NOT NULL DEFAULT '0' COMMENT '盖楼上级ID',
  `aid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '主题ID',
  `gid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '群组ID',
  `title` varchar(100) DEFAULT '' COMMENT '标题',
  `uid` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `username` varchar(15) NOT NULL DEFAULT '' COMMENT '用户名',
  `ip` varchar(20) NOT NULL DEFAULT '' COMMENT '用户IP',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
  `message` text NOT NULL COMMENT '回复内容',
  `pic` varchar(255) NOT NULL DEFAULT '' COMMENT '回复图片',
  `hotuser` text NOT NULL COMMENT '表意用户',
  `attr` varchar(255) DEFAULT NULL COMMENT '属性',
  `tags` varchar(255) DEFAULT NULL COMMENT '键字关',
  `lasttime` int(11) DEFAULT NULL COMMENT '最后表意时间',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '否是显示',
  `position` tinyint(1) NOT NULL DEFAULT '0' COMMENT '评论状态',
  `star` tinyint(1) NOT NULL DEFAULT '0' COMMENT '评论级别',
  `click` tinyint(1) NOT NULL DEFAULT '0' COMMENT '表态心情',
  `ok` mediumint(8) default '0' COMMENT '支持',
  `smile` mediumint(8) DEFAULT '0' COMMENT '微笑',
  `bad` mediumint(8) default '0' COMMENT '反对',
  PRIMARY KEY (`id`),
  KEY `aid` (`aid`,`uid`,`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='主题评论综合表' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `iic_friend` (
  `id` int(10) unsigned NOT NULL auto_increment COMMENT '好友表主键',
  `uid` mediumint(8) unsigned NOT NULL default '0' COMMENT '用户ID',
  `fuid` mediumint(8) unsigned NOT NULL default '0' COMMENT '好友ID',
  `fusername` char(15) NOT NULL default '' COMMENT '好友名',
  `status` tinyint(1) NOT NULL default '0' COMMENT '关系状态',
  `gid` smallint(6) unsigned NOT NULL default '0' COMMENT '好友组ID',
  `note` char(50) NOT NULL default '' COMMENT '好友描述',
  `num` mediumint(8) unsigned NOT NULL default '0' COMMENT '好友关系数',
  `dateline` int(10) unsigned NOT NULL default '0' COMMENT '加好友时间',
  `mtime` int(10) unsigned NOT NULL default '0' COMMENT '审批时间',
  PRIMARY KEY  (`id`),
  KEY `fuid` (`fuid`),
  KEY `status` (`uid`,`status`,`num`,`dateline`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='好友信息';


CREATE TABLE IF NOT EXISTS `iic_friendlog` (
  `id` mediumint(8) unsigned NOT NULL auto_increment COMMENT '主键',
  `uid` mediumint(8) unsigned NOT NULL default '0' COMMENT '用户ID',
  `fuid` mediumint(8) unsigned NOT NULL default '0' COMMENT '对方ID',
  `action` char(10) NOT NULL default '' COMMENT '事件',
  `dateline` int(10) unsigned NOT NULL default '0' COMMENT '时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='好友动态';

CREATE TABLE IF NOT EXISTS `iic_member` (
  `id` mediumint(8) unsigned NOT NULL auto_increment COMMENT '用户ID',
  `username` char(15) NOT NULL default '' COMMENT '用户名',
  `password` char(32) NOT NULL default '' COMMENT '密码',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '用户邮箱',
  `en` varchar(20) DEFAULT NULL COMMENT '用户空间英文',
  `pic` varchar(100) DEFAULT '' COMMENT '头像',
  `is_mail` tinyint(1) DEFAULT '0' COMMENT '是否邮件认证',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='成员登录';

CREATE TABLE IF NOT EXISTS `iic_stepselect` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `itemname` char(30) DEFAULT '' COMMENT '类别名',
  `egroup` char(20) DEFAULT '' COMMENT '组名称',
  `issign` tinyint(1) unsigned DEFAULT '0' COMMENT '是否启用',
  `issystem` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否系统',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='联动数据类';


CREATE TABLE IF NOT EXISTS `iic_sys_enum` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `ename` char(30) NOT NULL DEFAULT '' COMMENT '名称',
  `evalue` smallint(6) NOT NULL DEFAULT '0' COMMENT '值',
  `egroup` char(20) NOT NULL DEFAULT '' COMMENT '组名',
  `disorder` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `issign` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='联动数据';
