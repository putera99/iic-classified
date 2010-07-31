<?php
/**
 +------------------------------------------------------------------------------
 * ThoughtModel 数据对象映射类
 +------------------------------------------------------------------------------
 * @category   SubModel
 * @package  app
 * @subpackage  Model
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-6-11
 * @time  下午04:04:29
 +------------------------------------------------------------------------------
 */
class ThoughtModel extends Model{
	protected $_auto=array(
		array("ctime",'time',1,'function'),
		array("mtime",'time',3,'function'),
		array('uid',"get_uid",1,'function'),
		array('username',"get_username",1,'function'),
		array('click',"0",1),
		array('ip','client_ip',1,'function'),
	);
}//end ThoughtModel