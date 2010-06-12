<?php
/**
 +------------------------------------------------------------------------------
 * EventModel 数据对象映射类
 +------------------------------------------------------------------------------
 * @category   SubModel
 * @package  app
 * @subpackage  Model
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-6-12
 * @time  上午11:16:22
 +------------------------------------------------------------------------------
 */
class EventModel extends Model{
	protected $_auto=array(
		array('membernum','0',1),
		array('attending','',1),
		array('mightatten','',1),
		array('notattending','',1),
		array('public','2',1),
	);
}//end EventModel