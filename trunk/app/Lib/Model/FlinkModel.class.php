<?php
/**
 +------------------------------------------------------------------------------
 * FlinkModel 数据对象映射类
 +------------------------------------------------------------------------------
 * @category   SubModel
 * @package  app
 * @subpackage  Model
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-8-5
 * @time  上午11:23:45
 +------------------------------------------------------------------------------
 */
class FlinkModel extends Model{
	protected $_auto=array(
		array("ctime","time",1,"function"),
		array("ischeck",1,1),
	);
}//end FlinkModel