<?php
/**
 +------------------------------------------------------------------------------
 * SoKeyModel 数据对象映射类
 +------------------------------------------------------------------------------
 * @category   SubModel
 * @package  app
 * @subpackage  Model
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-10-29
 * @time  上午10:42:28
 +------------------------------------------------------------------------------
 */
class SoKeyModel extends Model{
	protected $_auto=array(
		array('ctime','time',1,'function'),
		array('mtime','time',3,'function'),
	);
}//end SoKeyModel