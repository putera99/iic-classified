<?php
if (!defined('THINK_PATH')) exit();
return array(
	'DB_HOST'=>'localhost', //服务器地址
	'DB_NAME'=>'e_com_test', //数据库名
	'DB_USER'=>'root', //用户名
	'DB_PWD'=>'weiming', //密码
	'DB_PREFIX'=>'bfc_', //数据库表前缀
	'SHOW_RUN_TIME'=>true, //Bool 运行时间显示
	'SHOW_ADV_TIME'=>true, //Bool 显示详细的运行时间
	'SHOW_DB_TIMES'=>true, //Bool 显示数据库查询和写入次数
	'SHOW_CACHE_TIMES'=>true, //Bool 显示缓存操作次数
	'SHOW_USE_MEM'=>true, //Bool 显示内存开销
	'URL_MODEL'=>3, //URL访问模式,可选参数0、1、2、3,代表以下四种模式：0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式) 当URL_DISPATCH_ON开启后有效; 默认为PATHINFO 模式，提供最好的用户体验和SEO支持
	'APP_DEBUG'=>true,
);
?>