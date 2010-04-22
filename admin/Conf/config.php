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
		/*'USER_AUTH_ON'=>true,
		'USER_AUTH_TYPE'			=>1,		// 默认认证类型 1 登录认证 2 实时认证
		'USER_AUTH_KEY'			=>'authId',	// 用户认证SESSION标记
	    'ADMIN_AUTH_KEY'			=>'administrator',
		'USER_AUTH_MODEL'		=>'User',	// 默认验证数据表模型
		'AUTH_PWD_ENCODER'		=>'md5',	// 用户认证密码加密方式
		'USER_AUTH_GATEWAY'	=>'/Public/login',	// 默认认证网关
		'NOT_AUTH_MODULE'		=>'Public',		// 默认无需认证模块
		'REQUIRE_AUTH_MODULE'=>'',		// 默认需要认证模块
		'NOT_AUTH_ACTION'		=>'',		// 默认无需认证操作
		'REQUIRE_AUTH_ACTION'=>'',		// 默认需要认证操作
	    'GUEST_AUTH_ON'          => false,    // 是否开启游客授权访问
	    'GUEST_AUTH_ID'           =>    0,     // 游客的用户ID
		'SHOW_RUN_TIME'=>true,			// 运行时间显示
		'SHOW_ADV_TIME'=>true,			// 显示详细的运行时间
		'SHOW_DB_TIMES'=>true,			// 显示数据库查询和写入次数
		'SHOW_CACHE_TIMES'=>true,		// 显示缓存操作次数
		'SHOW_USE_MEM'=>true,			// 显示内存开销
	    'DB_LIKE_FIELDS'=>'title|remark',
		'RBAC_ROLE_TABLE'=>'iic_role',
		'RBAC_USER_TABLE'	=>	'iic_role_user',
		'RBAC_ACCESS_TABLE' =>	'iic_access',
		'RBAC_NODE_TABLE'	=> 'iic_node',*/
	);

?>