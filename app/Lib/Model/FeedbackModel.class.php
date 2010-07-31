<?php
/**
 +------------------------------------------------------------------------------
 * FeedbackModel 数据对象映射类
 +------------------------------------------------------------------------------
 * @category   SubModel
 * @package  app
 * @subpackage  Model
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-7-24
 * @time  下午05:14:22
 +------------------------------------------------------------------------------
 */
class FeedbackModel extends Model{
	protected $_auto=array(
		array('ctime','time','1','function'),
		array('ip','client_ip',1,'function'),
		array('uid','get_uid',1,'function'),
	);
	
}//end FeedbackModel