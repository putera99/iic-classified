<?php
/**
 +------------------------------------------------------------------------------
 * AlbumModel 数据对象映射类
 +------------------------------------------------------------------------------
 * @category   SubModel
 * @package  app
 * @subpackage  Model
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-5-31
 * @time  下午05:24:46
 +------------------------------------------------------------------------------
 */
class AlbumModel extends Model{
	protected $_auto=array(
		array('uid','get_uid',1,'function'),
		array('username','get_username',1,'function'),
		array('dateline','time',1,'function'),
		array('updatetime','time',2,'function'),
		array('target_ids','all',1),
		array('friend','0',1),
		array('picnum','0',1),
	
	);
}//end AlbumModel