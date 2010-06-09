<?php
/**
 +------------------------------------------------------------------------------
 * PostModel 数据对象映射类
 +------------------------------------------------------------------------------
 * @category   SubModel
 * @package  app
 * @subpackage  Model
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-6-8
 * @time  上午11:33:20
 +------------------------------------------------------------------------------
 */
class PostModel extends Model{
	protected $_auto=array(
		array("dateline",'time',1,'function'),
		array("lasttime",'time',3,'function'),
		array('uid',"get_uid",1,'function'),
		array('username',"get_username",1,'function'),
		array('ip','client_ip',1,'function'),
	);
}//end PostModel