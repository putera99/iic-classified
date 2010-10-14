<?php
if (!defined('THINK_PATH')){ 
	exit();
}
return array(
	'DB_TYPE'=>		'mysql',
	'DB_HOST'=>		'localhost',		//连接mysql主机
	'DB_NAME'=>		'e_com_test',			//数据库名
	'DB_USER'=>		'root',			//mysql用户
	'DB_PWD'=>		'weiming',		//mysql密码
	'DB_PORT'=>		'3306',				//端口
	'DB_PREFIX'=>	'iic_',			//数据表的前缀
	'P_NAME'=>		'BeingfunChina',		//本项目的名字
	'URL_MODEL'=>2,
	'URL_PATHINFO_MODEL'=>2,
	'URL_HTML_SUFFIX'=>'.html',
	//'APP_DEBUG'=>true,
	//'mkrtags'=>"@.TagLib.TagLibMkrtags",
	//'TAGLIB_PRE_LOAD'       => 'mkrtags',
	'TOKEN_ON'=>false,
	'TOKEN_NAME'=>'IIC_CN',
	'GOOGLE_MAP_API'=>'ABQIAAAAjJHqxde4ntibYvgZu1ye2xQFw05-dUaae2f4nTjx_OHejRkIjhSYn4LuA7O6EOw6NtsYisKrxcTAhQ',
//	'SHOW_RUN_TIME'			=> true,   // 运行时间显示
//    'SHOW_ADV_TIME'			=> true,   // 显示详细的运行时间
//    'SHOW_DB_TIMES'			=> true,   // 显示数据库查询和写入次数
//    'SHOW_CACHE_TIMES'		=> true,   // 显示缓存操作次数
//    'SHOW_USE_MEM'			=> true,   // 显示内存开销
//    'SHOW_PAGE_TRACE'		=> false,   // 显示页面Trace信息 由Trace文件定义和Action操作赋值
    'URL_ROUTER_ON'=>true,
	'MAILID'=>"beingfunchina@580w.com",
	'FMAILID'=>"beingfunchina@gmail.com",
	'MAILSERVER'=>"smtp.ym.163.com",
	'MAILPORT'=>"25",
	'MAILPW'=>"26GMIBgGVu",
);
?>