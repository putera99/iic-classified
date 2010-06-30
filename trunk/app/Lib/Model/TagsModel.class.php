<?php
/**
 +------------------------------------------------------------------------------
 * TagsModel 数据对象映射类
 +------------------------------------------------------------------------------
 * @category   SubModel
 * @package  app
 * @subpackage  Model
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-6-30
 * @time  下午03:09:41
 +------------------------------------------------------------------------------
 */
class TagsModel extends Model{
	protected $_auto=array(
		array('dateline','time',1,'function'),
	);
}//end TagsModel