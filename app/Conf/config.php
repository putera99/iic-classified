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
	'P_NAME'=>		'e_com',		//本项目的名字
	'URL_MODEL'=>	3,
	'APP_DEBUG'=>true,
);
?>