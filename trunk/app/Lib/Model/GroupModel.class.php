<?php
/**
 +------------------------------------------------------------------------------
 * GroupModel 数据对象映射类
 +------------------------------------------------------------------------------
 * @category   SubModel
 * @package  app
 * @subpackage  Model
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-6-5
 * @time  上午10:06:56
 +------------------------------------------------------------------------------
 */
class GroupModel extends Model{
	protected $_auto=array(
		array('close',0,1),
		array('membernum',1,1),
		array('uid',"get_uid",1,'function'),
		array('username',"get_username",1,'function'),
		array('threadnum',0,1),
		array('postnum',0,1),
		array('closeapply',0,1),
		array('joinperm',0,1),
		array('viewperm',0,1),
		array('threadperm',0,1),
		array('postperm',0,1),
		array('recommend',0,1),
		array('moderator','get_uid',1,'function'),
		array('ctime','time',1,'function'),
		array('lasttime','time',1,'function'),
	);
}//end GroupModel