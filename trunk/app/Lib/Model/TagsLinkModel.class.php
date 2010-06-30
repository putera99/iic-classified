<?php
/**
 +------------------------------------------------------------------------------
 * TagsLinkModel 数据对象映射类
 +------------------------------------------------------------------------------
 * @category   SubModel
 * @package  app
 * @subpackage  Model
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-6-30
 * @time  下午05:14:07
 +------------------------------------------------------------------------------
 */
class TagsLinkModel extends Model{
	protected $_auto=array(
		array('ctime','time',1,'function'),
	);
}//end TagsLinkModel