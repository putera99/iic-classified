<?php
/**
 +------------------------------------------------------------------------------
 * UserCollectionModel 数据对象映射类
 +------------------------------------------------------------------------------
 * @category   SubModel
 * @package  app
 * @subpackage  Model
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-5-23
 * @time  下午03:13:36
 +------------------------------------------------------------------------------
 */
//import("AdvModel");
class UserCollectionModel extends Model{
	protected $_auto=array(
		array('ctime','time',1,'function'),
		
	);
}//end UserCollectionModel
?>