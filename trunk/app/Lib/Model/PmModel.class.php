<?php
/**
 +------------------------------------------------------------------------------
 * PmModel 数据对象映射类
 +------------------------------------------------------------------------------
 * @category   SubModel
 * @package  app
 * @subpackage  Model
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-9-27
 * @time  下午03:20:48
 +------------------------------------------------------------------------------
 */
class PmModel extends Model{
	protected $_auto=array(
		array('ifnew','1','1'),
		array('ctime','time','1','function'),
		array('tousername','get_username','1','function'),
		array('touid','get_uid','1','function'),
	);
}//end PmModel