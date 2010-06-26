<?php
/**
 +------------------------------------------------------------------------------
 * ActionModel 数据对象映射类
 +------------------------------------------------------------------------------
 * @category   SubModel
 * @package  app
 * @subpackage  Model
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-6-25
 * @time  上午11:32:55
 +------------------------------------------------------------------------------
 */
class ActionModel extends Model{
	protected $_auto=array(
		array('uid','get_uid',1,'function'),
		array('username','get_username',1,'function'),
		array('d','get_day',1,'function'),
		array('ctime','time',1,'function'),
		array('uip','client_ip',1,'function'),
	);
}//end ActionModel