<?php
/**
 +------------------------------------------------------------------------------
 * MtagCollectionModel 数据对象映射类
 +------------------------------------------------------------------------------
 * @category   SubModel
 * @package  app
 * @subpackage  Model
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-6-10
 * @time  下午02:29:35
 +------------------------------------------------------------------------------
 */
class MtagCollectionModel extends Model{
	protected $_auto=array(
		array('is_show',1,1),
		array('ctime','time',1,'function'),
		array('username','get_username',1,'function'),
		array('uid','get_uid',1,'function'),
	);
}//end MtagCollectionModel