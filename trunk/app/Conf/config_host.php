<?php
if (!defined('THINK_PATH')){ 
	exit();
}
return array(
	'DB_TYPE'=>		'mysql',
	'DB_HOST'=>		'127.0.0.1',		//连接mysql主机
	'DB_NAME'=>		'test',			//数据库名
	'DB_USER'=>		'testbfc',			//mysql用户
	'DB_PWD'=>		'CTpv4uh7aPrqtpLS',		//mysql密码
	'DB_PORT'=>		'3306',				//端口
	'DB_PREFIX'=>	'iic_',			//数据表的前缀
	'P_NAME'=>		'BeingfunChina',		//本项目的名字
	'URL_MODEL'=>	2,
	'URL_PATHINFO_MODEL'=>2,
	//'APP_DEBUG'=>true,
	//'mkrtags'=>"@.TagLib.TagLibMkrtags",
	//'TAGLIB_PRE_LOAD'       => 'mkrtags',
	'TOKEN_ON'=>false,
	'URL_HTML_SUFFIX'       => '.html',  // URL伪静态后缀设置
	'TOKEN_NAME'=>'IIC_CN',
	//'GOOGLE_MAP_API'=>'ABQIAAAAjJHqxde4ntibYvgZu1ye2xRM6XnYNeX6u_g7fubmJ5ncrvnU1hSeOgkHK18pFPxS3L_YPvyU1Ei_iw',//tel
	'GOOGLE_MAP_API'=>'ABQIAAAAjJHqxde4ntibYvgZu1ye2xRQZ60gi6Ttsm4JkPI71jlCuQ0jihREk32RdS7t_NT3CpW7lSmSa0BYPQ',//www
        'URL_ROUTER_ON'=>true,
);
?>
