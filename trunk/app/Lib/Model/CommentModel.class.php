<?php
/**
 +------------------------------------------------------------------------------
 * CommentModel 数据对象映射类
 +------------------------------------------------------------------------------
 * @category   SubModel
 * @package  app
 * @subpackage  Model
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-5-24
 * @time  上午10:52:37
 +------------------------------------------------------------------------------
 */
class CommentModel extends Model{
	protected $_auto=array(
		array('hot','0',1),
		array('username','get_username',1,'function'),
		array('ip','client_ip',1,'function'),
		array('dateline','time',1,'function'),
	);
}//end CommentModel