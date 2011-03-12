<?php
/**
 +------------------------------------------------------------------------------
 * RoleUserModel 数据对象映射类
 +------------------------------------------------------------------------------
 * @category   SubModel
 * @package  app
 * @subpackage  Model
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-7-19
 * @time  上午10:22:49
 +------------------------------------------------------------------------------
 */
class RoleUserModel extends Model{
	protected $_auto=array(
		array('ctime','time',1,'function'),
		array('etime','time',2,'function'),
	);
}//end RoleUserModel