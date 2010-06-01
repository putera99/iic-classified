<?php
/**
 +------------------------------------------------------------------------------
 * LtdModel 数据对象映射类
 +------------------------------------------------------------------------------
 * @category   SubModel
 * @package  app
 * @subpackage  Model
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-6-1
 * @time  上午11:58:43
 +------------------------------------------------------------------------------
 */
class LtdModel extends Model{
	protected $_auto=array(
		array('ctime','time',1,'function'),
		array('mtime','time',3,'function'),
		array('cip','client_ip',1,'function'),
		array('mip','client_ip',3,'function'),
		array('uid','get_uid',1,'function'),
		array('status','1'),
	);
}//end LtdModel