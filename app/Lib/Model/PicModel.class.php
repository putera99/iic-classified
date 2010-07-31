<?php
/**
 +------------------------------------------------------------------------------
 * PicModel 数据对象映射类
 +------------------------------------------------------------------------------
 * @category   SubModel
 * @package  app
 * @subpackage  Model
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-5-31
 * @time  下午06:23:27
 +------------------------------------------------------------------------------
 */
class PicModel extends Model{
	protected $_auto=array(
		array('uid','get_uid',1,'function'),
		array('username','get_username',1,'function'),
		array('dateline','time',1,'function'),
		array('postip','client_ip',1,'function'),
	);
}//end PicModel