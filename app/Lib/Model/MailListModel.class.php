<?php
/**
 +------------------------------------------------------------------------------
 * MailListModel 数据对象映射类
 +------------------------------------------------------------------------------
 * @category   SubModel
 * @package  app
 * @subpackage  Model
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-8-18
 * @time  上午10:21:00
 +------------------------------------------------------------------------------
 */
class MailListModel extends Model{
	protected $_auto=array(
		array('ctime','time',1,'function'),
		array('status','1',1),
	);
}//end MailListModel