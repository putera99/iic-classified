-- phpMyAdmin SQL Dump
-- version 3.3.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 08, 2011 at 05:59 AM
-- Server version: 5.1.35
-- PHP Version: 5.2.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `e_com_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `iic_access`
--

CREATE TABLE IF NOT EXISTS `iic_access` (
  `role_id` smallint(6) unsigned NOT NULL COMMENT '角色ID',
  `node_id` smallint(6) unsigned NOT NULL COMMENT '节点ID',
  `level` tinyint(1) NOT NULL COMMENT '等级',
  `module` varchar(50) DEFAULT NULL COMMENT '模型',
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='角色权限';

-- --------------------------------------------------------

--
-- Table structure for table `iic_action`
--

CREATE TABLE IF NOT EXISTS `iic_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `xid` int(11) DEFAULT NULL COMMENT '资源ID',
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `username` varchar(20) DEFAULT NULL COMMENT '用户名',
  `mon` int(11) DEFAULT NULL COMMENT '月份',
  `stype` varchar(40) DEFAULT NULL COMMENT '资源类别',
  `d` int(11) DEFAULT NULL COMMENT '日期',
  `uip` varchar(40) NOT NULL,
  `act` varchar(20) NOT NULL COMMENT '操作类型',
  `sql` mediumtext COMMENT 'sql',
  `ctime` int(11) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='事件统计综合' AUTO_INCREMENT=13443 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_act_city`
--

CREATE TABLE IF NOT EXISTS `iic_act_city` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '城市ID',
  `ctitle` varchar(240) DEFAULT NULL COMMENT '城市英文名',
  `cename` varchar(240) DEFAULT NULL COMMENT '城市中文名',
  `ctime` int(11) DEFAULT NULL COMMENT '创建时间',
  `content` text COMMENT '城市简介',
  `city` tinyint(1) DEFAULT '0' COMMENT '启用状态',
  `fair` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是展会城市',
  `localion` tinyint(1) NOT NULL DEFAULT '1' COMMENT '作为地区选择',
  `group` tinyint(1) NOT NULL DEFAULT '0' COMMENT '城市群',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='已经开通的城市' AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_ad`
--

CREATE TABLE IF NOT EXISTS `iic_ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `wz` varchar(20) DEFAULT NULL COMMENT '广告的位置',
  `info` varchar(255) NOT NULL DEFAULT '0' COMMENT '对应的信息',
  `model` varchar(40) NOT NULL COMMENT '投放的模块',
  `cat` varchar(100) NOT NULL DEFAULT '0' COMMENT '栏目',
  `title` varchar(240) NOT NULL COMMENT '广告名称',
  `type` varchar(40) DEFAULT NULL COMMENT '广告类型',
  `isclose` varchar(40) DEFAULT NULL COMMENT '文字广告位是否新开窗口',
  `begintime` int(11) DEFAULT NULL COMMENT '广告投放开始日期',
  `endtime` int(11) DEFAULT NULL COMMENT '广告投放结束日期',
  `adlink` varchar(255) DEFAULT NULL COMMENT '广告代码',
  `picurl` varchar(255) NOT NULL COMMENT '图片',
  `adcode` mediumtext NOT NULL COMMENT '广告代码',
  `cid` varchar(50) NOT NULL COMMENT '城市ID',
  `ctime` int(11) DEFAULT NULL COMMENT '提交日期',
  `list` int(11) DEFAULT NULL COMMENT '排序值',
  `uid` varchar(100) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `about` mediumtext NOT NULL COMMENT '广告备注',
  `is_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='广告信息表' AUTO_INCREMENT=69 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_addon_agents`
--

CREATE TABLE IF NOT EXISTS `iic_addon_agents` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `aid` varchar(40) DEFAULT NULL COMMENT '档案信息ID',
  `content` mediumtext COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='折扣信息' AUTO_INCREMENT=1058 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_addon_arc`
--

CREATE TABLE IF NOT EXISTS `iic_addon_arc` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `aid` int(11) DEFAULT NULL COMMENT '档案信息ID',
  `pages` varchar(40) DEFAULT NULL COMMENT '文章分页',
  `content` mediumtext COMMENT '文章内容',
  `redirecturl` varchar(240) DEFAULT NULL COMMENT '跳转的URL',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文章表2' AUTO_INCREMENT=89 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_addon_article`
--

CREATE TABLE IF NOT EXISTS `iic_addon_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `aid` int(11) DEFAULT NULL COMMENT '档案信息ID',
  `pages` varchar(40) DEFAULT NULL COMMENT '文章分页',
  `content` mediumtext COMMENT '文章内容',
  `redirecturl` varchar(240) DEFAULT NULL COMMENT '跳转的URL',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文章表' AUTO_INCREMENT=5716 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_addon_commerce`
--

CREATE TABLE IF NOT EXISTS `iic_addon_commerce` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `aid` int(11) DEFAULT NULL COMMENT '档案信息ID',
  `quantity` varchar(240) DEFAULT NULL COMMENT '数量',
  `price` varchar(40) DEFAULT NULL COMMENT '单位价格',
  `content` mediumtext COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='买卖' AUTO_INCREMENT=1076 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_addon_jobs`
--

CREATE TABLE IF NOT EXISTS `iic_addon_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `aid` int(11) DEFAULT NULL COMMENT '档案信息ID',
  `joblocated` varchar(240) DEFAULT NULL COMMENT '工作地区',
  `experience` varchar(240) DEFAULT NULL COMMENT '工作经验',
  `salary` varchar(40) DEFAULT NULL COMMENT '薪水',
  `content` mediumtext COMMENT '工作描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='招聘与求职' AUTO_INCREMENT=8090 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_addon_personals`
--

CREATE TABLE IF NOT EXISTS `iic_addon_personals` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `aid` varchar(40) DEFAULT NULL COMMENT '档案信息ID',
  `content` mediumtext COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='交友信息' AUTO_INCREMENT=1557 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_addon_realestate`
--

CREATE TABLE IF NOT EXISTS `iic_addon_realestate` (
  `id` mediumint(7) NOT NULL AUTO_INCREMENT,
  `aid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '档案信息ID',
  `published` varchar(50) NOT NULL COMMENT '发布人身份',
  `size` varchar(50) DEFAULT '' COMMENT '面积',
  `price` varchar(20) DEFAULT '0' COMMENT '价格',
  `rooms` varchar(100) DEFAULT '' COMMENT '房间信息',
  `content` mediumtext NOT NULL COMMENT '描述',
  PRIMARY KEY (`id`),
  KEY `aid` (`aid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='房产信息' AUTO_INCREMENT=5707 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_addon_services`
--

CREATE TABLE IF NOT EXISTS `iic_addon_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `aid` int(11) DEFAULT NULL COMMENT '档案信息ID',
  `content` mediumtext COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='服务信息' AUTO_INCREMENT=3023 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_album`
--

CREATE TABLE IF NOT EXISTS `iic_album` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '相册ID',
  `itype` varchar(40) DEFAULT NULL COMMENT '相册类别',
  `albumname` varchar(240) DEFAULT NULL COMMENT '相册名字',
  `uid` varchar(40) DEFAULT NULL COMMENT '用户ID',
  `ctg_id` int(10) NOT NULL DEFAULT '0',
  `username` varchar(40) DEFAULT NULL COMMENT '用户名',
  `dateline` int(11) DEFAULT NULL COMMENT '建立时间',
  `updatetime` int(11) DEFAULT NULL COMMENT '修改时间',
  `picnum` varchar(40) DEFAULT NULL COMMENT '照片数量',
  `pic` varchar(240) DEFAULT NULL COMMENT '封面照片',
  `picflag` varchar(40) DEFAULT NULL COMMENT '是否有图片',
  `friend` varchar(40) DEFAULT NULL COMMENT '隐私设置',
  `password` varchar(40) DEFAULT NULL COMMENT '相册密码',
  `target_ids` text COMMENT '允许查看相的用户ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户相册' AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_apptree`
--

CREATE TABLE IF NOT EXISTS `iic_apptree` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(40) NOT NULL COMMENT '标题',
  `shortname` varchar(24) NOT NULL,
  `type` int(11) NOT NULL COMMENT '类型',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '上级ID',
  `appmodel` varchar(40) DEFAULT NULL COMMENT '数据表',
  `link` varchar(240) DEFAULT NULL COMMENT '链接',
  `seqNo` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=43 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_arcatt`
--

CREATE TABLE IF NOT EXISTS `iic_arcatt` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '属性记录ID',
  `att` varchar(40) DEFAULT NULL COMMENT '属性代码',
  `attname` varchar(40) DEFAULT NULL COMMENT '属性名称',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='属性数据表' AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_archives`
--

CREATE TABLE IF NOT EXISTS `iic_archives` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `typeid` varchar(10) DEFAULT NULL COMMENT '主分类',
  `typeid2` varchar(20) DEFAULT NULL COMMENT '次分类',
  `industry` varchar(40) DEFAULT NULL COMMENT '行业ID',
  `bycity` mediumint(5) DEFAULT NULL COMMENT '所在城市',
  `cid` smallint(4) DEFAULT NULL COMMENT '城市类别',
  `uid` int(11) DEFAULT NULL COMMENT '会员ID',
  `flag` varchar(40) DEFAULT NULL COMMENT '附属属性',
  `ismake` tinyint(1) DEFAULT NULL COMMENT '是否审核 ',
  `channel` varchar(5) DEFAULT NULL COMMENT '所属模型ID',
  `arcrank` varchar(5) DEFAULT NULL COMMENT '阅读权限',
  `click` mediumint(5) DEFAULT NULL COMMENT '点击量',
  `title` varchar(200) DEFAULT NULL COMMENT '标题',
  `shorttitle` varchar(120) DEFAULT NULL COMMENT '短标题',
  `color` varchar(40) DEFAULT NULL COMMENT '标题颜色',
  `writer` varchar(40) DEFAULT NULL COMMENT '作者',
  `source` varchar(100) DEFAULT NULL COMMENT '来源',
  `reurl` varchar(255) NOT NULL COMMENT '跳转页面',
  `pubdate` int(11) DEFAULT NULL COMMENT '发布日期',
  `senddate` int(11) DEFAULT NULL COMMENT '发送日期',
  `keywords` varchar(240) DEFAULT NULL COMMENT '关键字',
  `lastpost` int(11) DEFAULT NULL COMMENT '最后回复',
  `star1` mediumint(5) DEFAULT NULL COMMENT '星星1',
  `star2` mediumint(5) DEFAULT NULL COMMENT '星星1',
  `star3` mediumint(5) DEFAULT NULL COMMENT '星星1',
  `star4` mediumint(5) DEFAULT NULL COMMENT '星星1',
  `star5` mediumint(5) DEFAULT NULL COMMENT '星星1',
  `goodpost` smallint(5) DEFAULT NULL COMMENT '好评数',
  `badpost` smallint(5) DEFAULT NULL COMMENT '差评数',
  `notpost` tinyint(1) DEFAULT '1' COMMENT '是否开启评论',
  `description` varchar(240) DEFAULT NULL COMMENT '描述',
  `picurl` varchar(200) DEFAULT NULL COMMENT '封面图片',
  `comments` mediumint(7) DEFAULT '0' COMMENT '评论数',
  `my_content` mediumtext NOT NULL COMMENT '页面摘要',
  `filename` varchar(50) NOT NULL COMMENT 'è‹±æ–‡å',
  `uip` varchar(15) DEFAULT NULL COMMENT '用户IP',
  `lastview` int(11) DEFAULT NULL COMMENT '最后浏览时间',
  `editpwd` varchar(32) DEFAULT NULL COMMENT '编辑密码',
  `showstart` int(11) DEFAULT NULL COMMENT '开始显示时间',
  `showend` int(11) DEFAULT NULL COMMENT '停止显示的时间',
  `editer` varchar(40) DEFAULT NULL COMMENT '编辑者',
  `edittime` int(11) DEFAULT NULL COMMENT '编辑时间',
  `albumid` varchar(5) DEFAULT NULL COMMENT '附属相册ID',
  `albumnum` varchar(10) DEFAULT NULL COMMENT '附属图片张数',
  `itype` varchar(40) DEFAULT NULL COMMENT '信息性质',
  `category` varchar(240) DEFAULT NULL COMMENT '信息类别',
  `telephone` varchar(40) DEFAULT NULL COMMENT '联系电话',
  `fax` varchar(40) DEFAULT NULL COMMENT '传真号码',
  `mobphone` varchar(15) DEFAULT NULL COMMENT '联系手机',
  `email` varchar(80) DEFAULT NULL COMMENT '联系邮箱',
  `oicq` varchar(15) DEFAULT NULL COMMENT '联系QQ',
  `msn` varchar(240) DEFAULT NULL COMMENT '联系MSN',
  `maps` varchar(240) DEFAULT NULL COMMENT '地图参数',
  `city_id` varchar(40) DEFAULT NULL COMMENT '城市信息',
  `zone_id` varchar(40) DEFAULT NULL COMMENT '县城或区',
  `street_id` varchar(40) DEFAULT NULL COMMENT '街道或村镇',
  `position` varchar(240) DEFAULT NULL COMMENT '个人位置',
  `contact` varchar(500) DEFAULT NULL COMMENT '联系地址',
  `ltdid` int(11) DEFAULT NULL COMMENT '公司信息',
  `linkman` varchar(240) DEFAULT NULL COMMENT '联系人',
  PRIMARY KEY (`id`),
  UNIQUE KEY `typeid` (`typeid`,`cid`,`uid`,`title`,`uip`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='整站档案' AUTO_INCREMENT=66836 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_arctype`
--

CREATE TABLE IF NOT EXISTS `iic_arctype` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `reid` varchar(40) DEFAULT NULL COMMENT '上级栏目',
  `topid` varchar(40) DEFAULT NULL COMMENT '顶级栏目',
  `sortrank` varchar(40) DEFAULT NULL COMMENT '栏目排序',
  `typename` varchar(40) DEFAULT NULL COMMENT '栏目名称',
  `ename` varchar(40) DEFAULT NULL COMMENT '栏目的英文名',
  `typedir` varchar(40) DEFAULT NULL COMMENT '链接地址',
  `issend` varchar(40) DEFAULT NULL COMMENT '是否支持投稿',
  `channeltype` varchar(40) DEFAULT NULL COMMENT '系统模块',
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `cid` varchar(40) DEFAULT NULL COMMENT '城市类别',
  `maxpage` varchar(40) DEFAULT NULL COMMENT 'maxpage',
  `ispart` varchar(40) DEFAULT NULL COMMENT '栏目属性',
  `corank` varchar(40) DEFAULT NULL COMMENT '浏览权限',
  `tempindex` varchar(40) DEFAULT NULL COMMENT '封面模板',
  `templist` varchar(40) DEFAULT NULL COMMENT '列表模板',
  `temparticle` varchar(40) DEFAULT NULL COMMENT '内页面板',
  `namerule` varchar(40) DEFAULT NULL COMMENT '文章命名规则',
  `namerule2` varchar(40) DEFAULT NULL COMMENT '列表命名规则',
  `modname` varchar(40) DEFAULT NULL COMMENT '模板风格名',
  `description` varchar(250) DEFAULT NULL COMMENT '描述',
  `keywords` varchar(240) DEFAULT NULL COMMENT '关键字',
  `seotitle` varchar(240) DEFAULT NULL COMMENT 'SEO标题',
  `moresite` varchar(40) DEFAULT NULL COMMENT '是否启用子域名',
  `sitepath` varchar(40) DEFAULT NULL COMMENT '栏目地址',
  `siteurl` varchar(40) DEFAULT NULL COMMENT '绑定域名',
  `ishidden` varchar(40) DEFAULT NULL COMMENT '是否隐藏',
  `cross` varchar(40) DEFAULT NULL COMMENT '栏目交',
  `hits` int(11) DEFAULT NULL COMMENT '栏目点击',
  `posttime` int(11) DEFAULT NULL COMMENT '提交时间',
  `mtime` int(11) DEFAULT NULL COMMENT '修改时间',
  `crossid` text COMMENT '交叉栏目ID',
  `content` text COMMENT '栏目内容 ',
  `smalltypes` text COMMENT 'smalltypes',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='系用栏目列表' AUTO_INCREMENT=3023 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_area`
--

CREATE TABLE IF NOT EXISTS `iic_area` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `fup` varchar(40) DEFAULT NULL COMMENT 'fup',
  `name` varchar(240) DEFAULT NULL COMMENT 'name',
  `class` varchar(40) DEFAULT NULL COMMENT 'class',
  `sons` varchar(40) DEFAULT NULL COMMENT 'sons',
  `type` varchar(40) DEFAULT NULL COMMENT 'type',
  `admin` varchar(240) DEFAULT NULL COMMENT 'admin',
  `list` int(11) DEFAULT NULL COMMENT 'list',
  `listorder` varchar(40) DEFAULT NULL COMMENT 'listorder',
  `passwd` varchar(40) DEFAULT NULL COMMENT 'passwd',
  `logo` varchar(240) DEFAULT NULL COMMENT 'logo',
  `descrip` text COMMENT 'descrip',
  `style` varchar(240) DEFAULT NULL COMMENT 'style',
  `template` text COMMENT 'template',
  `jumpurl` varchar(240) DEFAULT NULL COMMENT 'jumpurl',
  `maxperpage` varchar(40) DEFAULT NULL COMMENT 'maxperpage',
  `metakeywords` varchar(240) DEFAULT NULL COMMENT 'metakeywords',
  `metadescription` varchar(240) DEFAULT NULL COMMENT 'metadescription',
  `allowcomment` varchar(40) DEFAULT NULL COMMENT 'allowcomment',
  `allowpost` varchar(240) DEFAULT NULL COMMENT 'allowpost',
  `allowviewtitle` varchar(240) DEFAULT NULL COMMENT 'allowviewtitle',
  `allowviewcontent` varchar(240) DEFAULT NULL COMMENT 'allowviewcontent',
  `allowdownload` varchar(240) DEFAULT NULL COMMENT 'allowdownload',
  `forbidshow` varchar(40) DEFAULT NULL COMMENT 'forbidshow',
  `config` text COMMENT 'config',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='城市和地区表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_comment`
--

CREATE TABLE IF NOT EXISTS `iic_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` varchar(40) DEFAULT NULL COMMENT '用户ID',
  `xid` varchar(40) DEFAULT NULL COMMENT '资源ID',
  `types` varchar(40) DEFAULT NULL COMMENT '资源类型',
  `hot` varchar(40) DEFAULT NULL COMMENT '热度',
  `click_6` varchar(40) DEFAULT NULL COMMENT '漂亮表态数',
  `click_7` varchar(40) DEFAULT NULL COMMENT '酷毙表态数',
  `click_8` varchar(40) DEFAULT NULL COMMENT '雷人表态数',
  `click_9` varchar(40) DEFAULT NULL COMMENT '鲜花表态数',
  `click_10` varchar(40) DEFAULT NULL COMMENT '鸡蛋表态数',
  `username` varchar(40) DEFAULT NULL COMMENT '用户名',
  `ip` varchar(40) DEFAULT NULL COMMENT 'IP',
  `dateline` int(11) DEFAULT NULL COMMENT '时间',
  `message` text COMMENT '信息',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='通用评论' AUTO_INCREMENT=228 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_commlist`
--

CREATE TABLE IF NOT EXISTS `iic_commlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '列表ID',
  `title` varchar(240) DEFAULT NULL COMMENT '列表标题',
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `username` varchar(40) DEFAULT NULL COMMENT '用户名',
  `msg` varchar(240) DEFAULT NULL COMMENT '简介',
  `cip` varchar(40) DEFAULT NULL COMMENT '创建IP',
  `ctime` int(11) DEFAULT NULL COMMENT '创建时间',
  `mtime` int(11) DEFAULT NULL COMMENT '修改时间',
  `status` varchar(40) DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='共用列表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_doing`
--

CREATE TABLE IF NOT EXISTS `iic_doing` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` varchar(40) DEFAULT NULL COMMENT '用户ID',
  `username` varchar(40) DEFAULT NULL COMMENT '用户名',
  `tags` varchar(40) DEFAULT NULL COMMENT '关键字',
  `from` varchar(40) DEFAULT NULL COMMENT '发表来源',
  `dateline` int(11) DEFAULT NULL COMMENT '时间',
  `message` text COMMENT '记录的内容',
  `ip` varchar(40) DEFAULT NULL COMMENT '记录的发表IP',
  `replynum` int(11) DEFAULT NULL COMMENT ' 记录回复数',
  `mood` varchar(40) DEFAULT NULL COMMENT '迷你博客的心情',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='个人心情' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_event`
--

CREATE TABLE IF NOT EXISTS `iic_event` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `aid` int(11) DEFAULT NULL COMMENT '资源ID',
  `public` tinyint(4) DEFAULT NULL COMMENT '公开状态:0私密,1半公开,2完全公开',
  `membernum` smallint(5) DEFAULT NULL COMMENT '成员数',
  `attending` text COMMENT '参与',
  `mightatten` text COMMENT '可能参与',
  `notattending` text COMMENT '不参与',
  `detail` text COMMENT '活动详细内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='活动' AUTO_INCREMENT=1189 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_fair`
--

CREATE TABLE IF NOT EXISTS `iic_fair` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `aid` int(11) NOT NULL COMMENT '资源ID',
  `starttime` int(11) DEFAULT NULL COMMENT '开始时间',
  `endtime` int(11) DEFAULT NULL COMMENT '结束时间',
  `description` mediumtext COMMENT 'description',
  `exhibitor` mediumtext COMMENT '参展商',
  `product` mediumtext COMMENT '产品相册ID',
  `website` varchar(200) DEFAULT NULL COMMENT '官方网站',
  PRIMARY KEY (`id`),
  UNIQUE KEY `aid` (`aid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='展会' AUTO_INCREMENT=499 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_feedback`
--

CREATE TABLE IF NOT EXISTS `iic_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `fid` int(11) NOT NULL DEFAULT '0' COMMENT '上层ID',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `username` varchar(40) NOT NULL COMMENT '用户名',
  `arctitle` varchar(240) NOT NULL COMMENT '标题',
  `lmxifhui` varchar(40) NOT NULL COMMENT '联系方式',
  `ip` varchar(15) NOT NULL DEFAULT '0.0.0.0' COMMENT 'IP',
  `ischeck` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否审核',
  `ctime` int(11) NOT NULL DEFAULT '0' COMMENT '发布时间',
  `msg` text NOT NULL COMMENT '留言内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='通用留言板' AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_flink`
--

CREATE TABLE IF NOT EXISTS `iic_flink` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `sortrank` int(11) NOT NULL DEFAULT '1000' COMMENT '排序',
  `url` varchar(255) DEFAULT NULL COMMENT '网址',
  `webname` varchar(40) DEFAULT NULL COMMENT '网站名称',
  `msg` varchar(255) DEFAULT NULL COMMENT '简介',
  `email` varchar(40) DEFAULT NULL COMMENT '联系email',
  `logo` varchar(250) NOT NULL COMMENT 'LOGO',
  `ctime` int(11) NOT NULL DEFAULT '0' COMMENT '创建日期',
  `itype` varchar(40) DEFAULT NULL COMMENT '放置位置',
  `ischeck` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'ischeck',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='友情链接' AUTO_INCREMENT=132 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_friend`
--

CREATE TABLE IF NOT EXISTS `iic_friend` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '好友表主键',
  `uid` varchar(40) DEFAULT NULL COMMENT '用户ID',
  `fuid` varchar(40) DEFAULT NULL COMMENT '好友ID',
  `fusername` varchar(40) DEFAULT NULL COMMENT '好友名',
  `status` varchar(40) DEFAULT NULL COMMENT '关系状态',
  `gid` varchar(40) DEFAULT NULL COMMENT '好友组ID',
  `note` varchar(40) DEFAULT NULL COMMENT '好友描述',
  `num` varchar(40) DEFAULT NULL COMMENT '好友关系数',
  `dateline` int(11) DEFAULT NULL COMMENT '加好友时间',
  `mtime` int(11) DEFAULT NULL COMMENT '审批时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='好友信息' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_friendlog`
--

CREATE TABLE IF NOT EXISTS `iic_friendlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` varchar(40) DEFAULT NULL COMMENT '用户ID',
  `fuid` varchar(40) DEFAULT NULL COMMENT '对方ID',
  `action` varchar(40) DEFAULT NULL COMMENT '事件',
  `dateline` int(11) DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='好友动态' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_group`
--

CREATE TABLE IF NOT EXISTS `iic_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '群组ID',
  `groupname` varchar(40) DEFAULT NULL COMMENT '群组名称',
  `uid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `username` char(15) NOT NULL COMMENT '用户名',
  `tags` varchar(240) DEFAULT NULL COMMENT '相关标签',
  `membernum` varchar(40) DEFAULT NULL COMMENT '用户数',
  `threadnum` varchar(40) DEFAULT NULL COMMENT '主题数',
  `postnum` varchar(40) DEFAULT NULL COMMENT '回复数',
  `close` varchar(40) DEFAULT NULL COMMENT '群组关闭',
  `announcement` text COMMENT '群组公告',
  `pic` varchar(240) DEFAULT NULL COMMENT '群组图片',
  `closeapply` varchar(40) DEFAULT NULL COMMENT '关闭加入',
  `joinperm` varchar(40) DEFAULT NULL COMMENT '加入权限',
  `viewperm` varchar(40) DEFAULT NULL COMMENT '浏览权限',
  `threadperm` varchar(40) DEFAULT NULL COMMENT '发表话题权限',
  `postperm` varchar(40) DEFAULT NULL COMMENT '回复话题权限',
  `recommend` varchar(40) DEFAULT NULL COMMENT '推荐时间',
  `moderator` varchar(240) DEFAULT NULL COMMENT '群组群主',
  `cat_id` varchar(40) DEFAULT NULL COMMENT '分类ID',
  `fcat_id` varchar(240) DEFAULT NULL COMMENT '附属分类ID',
  `attr` varchar(240) DEFAULT NULL COMMENT '附属属性',
  `ctime` int(11) DEFAULT NULL COMMENT '建创时间',
  `lasttime` int(11) DEFAULT NULL COMMENT '最后活跃时间',
  `en` varchar(40) DEFAULT NULL COMMENT '组英文名',
  `description` varchar(240) DEFAULT NULL COMMENT '群简介',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='群组信息' AUTO_INCREMENT=40 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_ltd` 已经废弃
--

CREATE TABLE IF NOT EXISTS `iic_ltd` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `title` varchar(240) DEFAULT NULL COMMENT '公司名称',
  `passwd` varchar(40) DEFAULT NULL COMMENT '管理密码',
  `info` text COMMENT '公司信息',
  `jyfw` varchar(240) DEFAULT NULL COMMENT '营经范围',
  `yyzz` varchar(240) DEFAULT NULL COMMENT '营业执照副本',
  `zstitle1` varchar(240) DEFAULT NULL COMMENT '书证标题',
  `zssm1` varchar(240) DEFAULT NULL COMMENT '证书说明',
  `zspic1` varchar(240) DEFAULT NULL COMMENT '证书图片',
  `zstitle2` varchar(240) DEFAULT NULL COMMENT '书证标题',
  `zssm2` varchar(240) DEFAULT NULL COMMENT '证书说明',
  `zspic2` varchar(240) DEFAULT NULL COMMENT '证书图片',
  `zstitle3` varchar(240) DEFAULT NULL COMMENT '书证标题',
  `zssm3` varchar(240) DEFAULT NULL COMMENT '证书说明',
  `zspic3` varchar(240) DEFAULT NULL COMMENT '证书图片',
  `zstitle4` varchar(240) DEFAULT NULL COMMENT '书证标题',
  `zssm4` varchar(240) DEFAULT NULL COMMENT '证书说明',
  `zspic4` varchar(240) DEFAULT NULL COMMENT '证书图片',
  `ctime` int(11) DEFAULT NULL COMMENT '创建时间',
  `mtime` int(11) DEFAULT NULL COMMENT '修改时间',
  `cip` varchar(40) DEFAULT NULL COMMENT '创建IP',
  `mip` varchar(40) DEFAULT NULL COMMENT '修改IP',
  `status` varchar(40) DEFAULT NULL COMMENT '是否有效',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='公司信息' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_magazines` 电子杂志表
--

CREATE TABLE IF NOT EXISTS `iic_magazines` (
  `id` mediumint(5) NOT NULL AUTO_INCREMENT,
  `title` varchar(80) NOT NULL,
  `vol` varchar(5) NOT NULL,
  `album_id` mediumint(8) NOT NULL,
  `cover` varchar(255) NOT NULL,
  `cover_s` varchar(255) NOT NULL,
  `bigimg` varchar(255) NOT NULL COMMENT '大图',
  `middleimg` mediumtext NOT NULL COMMENT '中图',
  `smallimg` mediumtext NOT NULL COMMENT '小图',
  `content` mediumtext NOT NULL,
  `seotitle` varchar(80) NOT NULL,
  `keywords` varchar(100) NOT NULL,
  `description` varchar(200) NOT NULL,
  `ctime` int(10) NOT NULL,
  `readurl` varchar(200) NOT NULL COMMENT '阅读地址',
  `downurl` varchar(200) NOT NULL COMMENT '下载地址',
  `readnum` int(10) NOT NULL DEFAULT '0' COMMENT '阅读次数',
  `downnum` int(10) NOT NULL DEFAULT '0' COMMENT '下载次数',
  `showtime` int(10) NOT NULL DEFAULT '0' COMMENT '示显时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_mail_list`
--

CREATE TABLE IF NOT EXISTS `iic_mail_list` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `ctime` int(10) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `types` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`,`types`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='订阅用的邮件列表' AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_members` discuz的用户表
--

CREATE TABLE IF NOT EXISTS `iic_members` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` char(15) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` char(32) NOT NULL DEFAULT '' COMMENT '密码',
  `gender` tinyint(1) NOT NULL DEFAULT '0',
  `adminid` tinyint(1) NOT NULL DEFAULT '0',
  `groupid` smallint(6) unsigned NOT NULL DEFAULT '0',
  `groupexpiry` int(10) unsigned NOT NULL DEFAULT '0',
  `regip` char(15) NOT NULL DEFAULT '',
  `regdate` int(10) unsigned NOT NULL DEFAULT '0',
  `lastip` char(15) NOT NULL DEFAULT '',
  `lastvisit` int(10) unsigned NOT NULL DEFAULT '0',
  `lastactivity` int(10) unsigned NOT NULL DEFAULT '0',
  `lastpost` int(10) unsigned NOT NULL DEFAULT '0',
  `posts` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `digestposts` smallint(6) unsigned NOT NULL DEFAULT '0',
  `oltime` smallint(6) unsigned NOT NULL DEFAULT '0',
  `pageviews` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `credits` int(10) NOT NULL DEFAULT '0',
  `extcredits1` int(10) NOT NULL DEFAULT '0',
  `extcredits2` int(10) NOT NULL DEFAULT '0',
  `extcredits3` int(10) NOT NULL DEFAULT '0',
  `extcredits4` int(10) NOT NULL DEFAULT '0',
  `extcredits5` int(10) NOT NULL DEFAULT '0',
  `extcredits6` int(10) NOT NULL DEFAULT '0',
  `extcredits7` int(10) NOT NULL DEFAULT '0',
  `extcredits8` int(10) NOT NULL DEFAULT '0',
  `email` char(40) NOT NULL DEFAULT '',
  `bday` date NOT NULL DEFAULT '0000-00-00',
  `address` varchar(20) NOT NULL COMMENT '当前所在地',
  `nationality` varchar(20) NOT NULL COMMENT '国籍',
  `fname` varchar(10) NOT NULL COMMENT 'First name',
  `lname` varchar(10) NOT NULL COMMENT 'Last name',
  `avatar` varchar(255) NOT NULL COMMENT '头像',
  `dateformat` tinyint(1) NOT NULL DEFAULT '0',
  `timeformat` tinyint(1) NOT NULL DEFAULT '0',
  `pmsound` tinyint(1) NOT NULL DEFAULT '0',
  `showemail` tinyint(1) NOT NULL DEFAULT '0',
  `newsletter` tinyint(1) NOT NULL DEFAULT '0',
  `timeoffset` char(4) NOT NULL DEFAULT '',
  `newpm` tinyint(1) NOT NULL DEFAULT '0',
  `accessmasks` tinyint(1) NOT NULL DEFAULT '0',
  `editormode` tinyint(1) unsigned NOT NULL DEFAULT '2',
  `customshow` tinyint(1) unsigned NOT NULL DEFAULT '26',
  `xspacestatus` tinyint(1) NOT NULL DEFAULT '0',
  `repw` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4074 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_mtaginvite` 暂时无用
--

CREATE TABLE IF NOT EXISTS `iic_mtaginvite` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '邀请ID',
  `uid` varchar(40) DEFAULT NULL COMMENT '用户ID',
  `username` varchar(40) DEFAULT NULL COMMENT '用户名',
  `tagid` varchar(40) DEFAULT NULL COMMENT '群组ID',
  `fromuid` varchar(40) DEFAULT NULL COMMENT '受邀用户ID',
  `fromusername` varchar(40) DEFAULT NULL COMMENT '受邀用户名',
  `dateline` int(11) DEFAULT NULL COMMENT '邀请时间',
  `endtime` int(11) DEFAULT NULL COMMENT '邀请有效期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='邀请加入群组' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_mtag_cat` 暂时无用
--

CREATE TABLE IF NOT EXISTS `iic_mtag_cat` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `pid` varchar(40) DEFAULT NULL COMMENT '父级ID',
  `title` varchar(40) DEFAULT NULL COMMENT '分类标题',
  `num` varchar(40) DEFAULT NULL COMMENT '排序',
  `attr` varchar(240) DEFAULT NULL COMMENT '附属属性',
  `is_show` varchar(40) DEFAULT NULL COMMENT '是否显示',
  `description` varchar(240) DEFAULT NULL COMMENT '描述',
  `keywords` varchar(240) DEFAULT NULL COMMENT '关键字',
  `is_dir` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是目录',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='群组分类' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_mtag_collection`
--

CREATE TABLE IF NOT EXISTS `iic_mtag_collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '收藏ID',
  `gid` mediumint(8) DEFAULT NULL COMMENT '群组ID',
  `listid` int(11) DEFAULT NULL COMMENT '共用列表ID',
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `username` varchar(40) DEFAULT NULL COMMENT '用户名',
  `types` varchar(40) DEFAULT NULL COMMENT '内容类型',
  `xid` varchar(40) DEFAULT NULL COMMENT '资源ID',
  `ctime` int(11) DEFAULT NULL COMMENT '收藏时间',
  `is_show` tinyint(1) DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='群组收藏' AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_node` 原计划做权限节点表 暂时无用
--

CREATE TABLE IF NOT EXISTS `iic_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `name` varchar(20) NOT NULL COMMENT '节点名',
  `title` varchar(50) DEFAULT NULL COMMENT '显示名',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态',
  `remark` varchar(255) DEFAULT NULL,
  `sort` smallint(6) unsigned DEFAULT NULL COMMENT '排序',
  `pid` smallint(6) unsigned NOT NULL COMMENT '父节点',
  `level` tinyint(1) unsigned NOT NULL COMMENT '级别',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='节点管理' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_ok_bad` 原计划做投票表 暂时无用
--

CREATE TABLE IF NOT EXISTS `iic_ok_bad` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `uid` varchar(40) DEFAULT NULL COMMENT '用户ID',
  `itype` varchar(40) DEFAULT NULL COMMENT '内容类型',
  `xid` varchar(40) DEFAULT NULL COMMENT '资源ID',
  `ok` varchar(40) DEFAULT NULL COMMENT '支持',
  `bad` varchar(40) DEFAULT NULL COMMENT '反对',
  `ctime` int(11) DEFAULT NULL COMMENT '创建时间',
  `ip` varchar(40) DEFAULT NULL COMMENT '用户IP',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='支持或反对' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_pic` 图片文件登记表 上传的图片均在此处管理
--

CREATE TABLE IF NOT EXISTS `iic_pic` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '图片ID',
  `album_id` varchar(40) DEFAULT NULL COMMENT '所属相册',
  `uid` varchar(40) DEFAULT NULL COMMENT '用户ID',
  `username` varchar(40) DEFAULT NULL COMMENT '所属用户',
  `dateline` int(11) DEFAULT NULL COMMENT '上传时间',
  `postip` varchar(40) DEFAULT NULL COMMENT '上传ip',
  `filename` varchar(240) DEFAULT '0' COMMENT '文件名',
  `title` varchar(240) DEFAULT NULL COMMENT '图片标题',
  `type` varchar(40) DEFAULT NULL COMMENT '图片类型',
  `size` varchar(20) DEFAULT NULL COMMENT '图片大小',
  `filepath` varchar(240) DEFAULT NULL COMMENT '图片路径',
  `thumb` varchar(40) DEFAULT NULL COMMENT '缩略图',
  `remote` varchar(40) DEFAULT NULL COMMENT '远程图片',
  `hot` mediumint(8) DEFAULT '0' COMMENT '热度',
  `click_6` mediumint(8) DEFAULT '0' COMMENT '漂亮表态数',
  `click_7` mediumint(8) DEFAULT '0' COMMENT '酷毙表态数',
  `click_8` mediumint(8) DEFAULT '0' COMMENT '雷人表态数',
  `click_9` mediumint(8) DEFAULT '0' COMMENT '鲜花表态数',
  `click_10` mediumint(8) DEFAULT '0' COMMENT '鸡蛋表态数',
  `xid` int(11) NOT NULL DEFAULT '0' COMMENT '资料ID',
  `xtype` varchar(20) NOT NULL COMMENT '资源类别',
  `msg` varchar(500) NOT NULL DEFAULT '' COMMENT '简单说明',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用',
  `mtime` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `dtime` int(10) NOT NULL DEFAULT '0' COMMENT '移除时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户图片' AUTO_INCREMENT=4890 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_pm`
--

CREATE TABLE IF NOT EXISTS `iic_pm` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `touid` int(11) DEFAULT NULL COMMENT '发送者ID',
  `xid` int(11) DEFAULT NULL COMMENT '资源ID',
  `itype` varchar(40) DEFAULT NULL COMMENT '资源类别',
  `fromuid` varchar(200) DEFAULT NULL COMMENT '接收方ID',
  `tousername` varchar(40) DEFAULT NULL COMMENT '发送方用户名',
  `type` varchar(40) DEFAULT NULL COMMENT '信件类别',
  `ifnew` tinyint(1) DEFAULT '1' COMMENT '是否新',
  `title` varchar(240) DEFAULT NULL COMMENT '标题',
  `ctime` int(11) DEFAULT NULL COMMENT '发送时间',
  `content` text COMMENT '内容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='短信息' AUTO_INCREMENT=369 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_post`
--

CREATE TABLE IF NOT EXISTS `iic_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '评论ID',
  `topid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '话题ID',
  `qid` varchar(40) DEFAULT NULL COMMENT '回复ID',
  `l` varchar(40) DEFAULT NULL COMMENT '楼次',
  `requery` varchar(40) DEFAULT NULL COMMENT '是否引用',
  `qidstr` varchar(240) DEFAULT NULL COMMENT '盖楼上级ID',
  `aid` varchar(40) DEFAULT NULL COMMENT '主题ID',
  `gid` mediumint(8) DEFAULT NULL COMMENT '群组ID',
  `title` varchar(240) DEFAULT NULL COMMENT '标题',
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `username` varchar(40) DEFAULT NULL COMMENT '用户名',
  `ip` varchar(40) DEFAULT NULL COMMENT '用户IP',
  `dateline` int(11) DEFAULT NULL COMMENT '发布时间',
  `message` text COMMENT '回复内容',
  `pic` varchar(240) DEFAULT NULL COMMENT '回复图片',
  `hotuser` text COMMENT '表意用户',
  `attr` varchar(240) DEFAULT NULL COMMENT '属性',
  `tags` varchar(240) DEFAULT NULL COMMENT '键字关',
  `lasttime` int(11) DEFAULT NULL COMMENT '最后表意时间',
  `is_show` tinyint(1) DEFAULT '1' COMMENT '否是显示',
  `position` varchar(40) DEFAULT NULL COMMENT '评论状态',
  `star` varchar(40) DEFAULT NULL COMMENT '评论级别',
  `click` varchar(40) DEFAULT NULL COMMENT '表态心情',
  `ok` varchar(40) DEFAULT NULL COMMENT '支持',
  `smile` varchar(40) DEFAULT NULL COMMENT '微笑',
  `bad` varchar(40) DEFAULT NULL COMMENT '反对',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='主题评论综合表' AUTO_INCREMENT=1964 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_report`
--

CREATE TABLE IF NOT EXISTS `iic_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `tid` varchar(40) DEFAULT NULL COMMENT '信息ID',
  `itype` varchar(40) DEFAULT NULL COMMENT '资源类型',
  `is_new` varchar(40) DEFAULT NULL COMMENT '是否为新',
  `num` varchar(40) DEFAULT NULL COMMENT '被举报次数',
  `dateline` int(11) DEFAULT NULL COMMENT '被举报时间',
  `lasttime` int(11) DEFAULT NULL COMMENT '最近时间',
  `ip` varchar(40) DEFAULT NULL COMMENT '用户ID',
  `subject` varchar(40) NOT NULL,
  `reason` text COMMENT '被举报原因',
  `uid` mediumint(8) NOT NULL DEFAULT '0' COMMENT '举报者ID',
  `username` varchar(40) NOT NULL,
  `is_show` varchar(40) DEFAULT NULL COMMENT '是否处理',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户举报表' AUTO_INCREMENT=15 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_role`
--

CREATE TABLE IF NOT EXISTS `iic_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `name` varchar(20) NOT NULL COMMENT '名称',
  `pid` smallint(6) DEFAULT NULL COMMENT '父级',
  `status` tinyint(1) unsigned DEFAULT NULL COMMENT '状态',
  `remark` varchar(255) DEFAULT NULL,
  `about` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='角色管理' AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_role_user`
--

CREATE TABLE IF NOT EXISTS `iic_role_user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `role_id` mediumint(9) unsigned DEFAULT NULL COMMENT '角色ID',
  `user_id` char(32) DEFAULT NULL COMMENT '用户ID',
  `ctime` int(11) NOT NULL,
  `etime` int(10) NOT NULL DEFAULT '0' COMMENT '修改时间',
  `endtime` int(10) NOT NULL DEFAULT '0' COMMENT '过期时间',
  `permissions` varchar(200) NOT NULL DEFAULT '' COMMENT '权限', -- 序列后的数组
  `act` text NOT NULL COMMENT '操作记录',
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_id` (`role_id`,`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='角色用户关系表' AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_settings` 由于后台功能大部分移到了前台 该处废弃
--

CREATE TABLE IF NOT EXISTS `iic_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `title` varchar(40) DEFAULT NULL COMMENT '键名',
  `values` text COMMENT '键值',
  `about` varchar(240) DEFAULT NULL COMMENT '说明',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统设置' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_so_key` 搜索关键字记录表
--

CREATE TABLE IF NOT EXISTS `iic_so_key` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `keyword` varchar(80) NOT NULL,
  `ctime` int(11) NOT NULL DEFAULT '0',
  `mtime` int(11) NOT NULL DEFAULT '0',
  `hot` int(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyword` (`keyword`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='搜索关键字记录表' AUTO_INCREMENT=127 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_stat`
--

CREATE TABLE IF NOT EXISTS `iic_stat` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `xid` int(11) NOT NULL COMMENT '资源ID',
  `mon` int(11) NOT NULL COMMENT '月份',
  `stype` varchar(40) NOT NULL COMMENT '资源类别',
  `d01` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d02` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d03` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d04` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d05` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d06` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d07` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d08` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d09` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d10` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d11` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d12` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d13` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d14` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d15` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d16` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d17` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d18` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d19` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d20` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d21` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d22` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d23` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d24` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d25` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d26` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d27` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d28` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d29` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d30` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  `d31` int(11) NOT NULL DEFAULT '0' COMMENT '日期',
  PRIMARY KEY (`id`),
  UNIQUE KEY `xid` (`xid`,`mon`,`stype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='点击统计综合' AUTO_INCREMENT=167 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_stepselect` 后台使用
--

CREATE TABLE IF NOT EXISTS `iic_stepselect` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `itemname` char(30) DEFAULT '' COMMENT '类别名',
  `egroup` char(20) DEFAULT '' COMMENT '组名称',
  `issign` tinyint(1) unsigned DEFAULT '0' COMMENT '是否启用',
  `issystem` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否系统',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='联动数据类' AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_subscription` 
--

CREATE TABLE IF NOT EXISTS `iic_subscription` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `email` varchar(50) NOT NULL COMMENT '邮箱',
  `ctime` int(11) NOT NULL COMMENT '订阅的时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='电子杂志订阅' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_sys_enum` 后台使用
--

CREATE TABLE IF NOT EXISTS `iic_sys_enum` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `ename` char(30) NOT NULL DEFAULT '' COMMENT '名称',
  `evalue` smallint(6) NOT NULL DEFAULT '0' COMMENT '值',
  `egroup` char(20) NOT NULL DEFAULT '' COMMENT '组名',
  `disorder` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `issign` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否启用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='联动数据' AUTO_INCREMENT=45 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_tags` 废弃
--

CREATE TABLE IF NOT EXISTS `iic_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '关键字ID',
  `tcatid` mediumint(5) NOT NULL COMMENT 'ä¸»è¦åˆ†ç±»',
  `ftcatid` varchar(10) DEFAULT NULL COMMENT 'æ¬¡çº§åˆ†ç±»',
  `tagsname` varchar(15) NOT NULL COMMENT 'å…³é”®å­—',
  `classifieds` mediumint(5) DEFAULT '0' COMMENT 'ç¾Žå®¹ä¸ªæ•°',
  `cityguide` mediumint(5) DEFAULT '0' COMMENT 'æœé¥°ä¸ªæ•°',
  `artcile` mediumint(5) DEFAULT '0' COMMENT 'è¯»ä¹¦ä¸ªæ•°',
  `biz` mediumint(5) DEFAULT '0' COMMENT 'ç”µå½±ä¸ªæ•°',
  `magezine` mediumint(5) DEFAULT '0' COMMENT 'éŸ³ä¹ä¸ªæ•°',
  `group` mediumint(5) DEFAULT '0' COMMENT 'BLOGä¸ªæ•°',
  `event` mediumint(5) DEFAULT '0' COMMENT 'æ´»åŠ¨ä¸ªæ•°',
  `ctime` int(10) NOT NULL DEFAULT '0' COMMENT 'åˆ›å»ºæ—¶é—´',
  `mtime` int(10) NOT NULL DEFAULT '0',
  `close` tinyint(1) DEFAULT '0' COMMENT '否是锁定',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='tags类资源个数' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_tagspace`
--

CREATE TABLE IF NOT EXISTS `iic_tagspace` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `tagid` mediumint(8) DEFAULT NULL COMMENT '群组ID',
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `username` varchar(40) DEFAULT NULL COMMENT '用户名',
  `grade` varchar(40) DEFAULT NULL COMMENT '成员级别',
  `ctime` int(11) DEFAULT NULL COMMENT '加入时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户群组关系表' AUTO_INCREMENT=1112 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_tags_cat` 废弃
--

CREATE TABLE IF NOT EXISTS `iic_tags_cat` (
  `id` mediumint(5) NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `title` varchar(20) NOT NULL COMMENT '分类名称',
  `pid` varchar(20) NOT NULL DEFAULT '0' COMMENT '上级',
  `num` tinyint(3) NOT NULL DEFAULT '0' COMMENT '排列',
  `keywords` varchar(100) DEFAULT NULL COMMENT '关键字',
  `description` varchar(200) DEFAULT NULL COMMENT '描述',
  `is_show` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否使用',
  `ctime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='关键字分类表' AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_tags_link` 废弃
--

CREATE TABLE IF NOT EXISTS `iic_tags_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `tagsid` int(11) NOT NULL COMMENT '关键字ID',
  `types` varchar(10) NOT NULL COMMENT '资源类型',
  `xid` int(11) NOT NULL COMMENT '资源ID',
  `is_ok` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否失效',
  `ctime` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='tags资源关系' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_thought`
--

CREATE TABLE IF NOT EXISTS `iic_thought` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `types` smallint(3) NOT NULL DEFAULT '0' COMMENT '关注的类别',
  `tid` int(11) NOT NULL DEFAULT '0' COMMENT '资源ID',
  `uid` mediumint(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `username` varchar(40) NOT NULL,
  `click` varchar(40) DEFAULT NULL COMMENT '点击次数',
  `hot` varchar(40) DEFAULT NULL COMMENT '关注程度',
  `ctime` int(11) DEFAULT NULL COMMENT '关注起始时间',
  `mtime` int(11) DEFAULT NULL COMMENT '最近关注时间',
  `ip` varchar(15) NOT NULL DEFAULT '0.0.0.0' COMMENT '点击次数',
  `is_show` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `types` (`types`,`tid`,`uid`,`is_show`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户关注' AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_user` 后台登陆使用
--

CREATE TABLE IF NOT EXISTS `iic_user` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `account` varchar(64) NOT NULL COMMENT '帐号',
  `nickname` varchar(50) NOT NULL COMMENT '昵称',
  `password` char(32) NOT NULL COMMENT '密码',
  `bind_account` varchar(50) NOT NULL,
  `last_login_time` int(11) unsigned DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` varchar(40) DEFAULT NULL COMMENT '最后登录IP',
  `login_count` mediumint(8) unsigned DEFAULT '0' COMMENT '登录次数',
  `verify` varchar(32) DEFAULT NULL,
  `email` varchar(50) NOT NULL COMMENT '邮箱',
  `remark` varchar(255) NOT NULL,
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL COMMENT '修改时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态',
  `type_id` tinyint(2) unsigned DEFAULT '0',
  `info` text NOT NULL COMMENT '备注',
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='后台用户表' AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_user_collection`
--

CREATE TABLE IF NOT EXISTS `iic_user_collection` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '收藏ID',
  `listid` int(11) DEFAULT NULL COMMENT '共用列表ID',
  `uid` varchar(40) DEFAULT NULL COMMENT '用户ID',
  `username` varchar(40) DEFAULT NULL COMMENT '用户名',
  `types` varchar(40) DEFAULT NULL COMMENT '内容类型',
  `tid` varchar(40) DEFAULT NULL COMMENT '资源类型',
  `ctime` int(11) DEFAULT NULL COMMENT '收藏时间',
  `is_show` varchar(40) DEFAULT NULL COMMENT '私有或公开',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户收藏' AUTO_INCREMENT=49 ;

-- --------------------------------------------------------

--
-- Table structure for table `iic_zone`
--

CREATE TABLE IF NOT EXISTS `iic_zone` (
  `id` mediumint(7) unsigned NOT NULL AUTO_INCREMENT,
  `cid` mediumint(7) unsigned NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL DEFAULT '',
  `list` int(10) NOT NULL DEFAULT '0',
  `descrip` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fup` (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=136 ;
