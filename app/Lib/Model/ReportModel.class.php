<?php
/**
 +------------------------------------------------------------------------------
 * ReportModel 数据对象映射类
 +------------------------------------------------------------------------------
 * @category   SubModel
 * @package  app
 * @subpackage  Model
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-7-24
 * @time  下午03:30:18
 +------------------------------------------------------------------------------
 */
class ReportModel extends Model{
	protected $_auto=array(
		array('is_new','1','1'),
		array('num','1','1'),
		array('dateline','time','1','function'),
		array('lasttime','time','3','function'),
		array('ip','client_ip','1','function'),
		array('uid','get_uid','1','function'),
		array('username','get_username','1','function'),
		array('is_show','1','1'),
	);
}//end ReportModel