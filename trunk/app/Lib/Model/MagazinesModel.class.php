<?php
/**
 +------------------------------------------------------------------------------
 * MagazinesModel 数据对象映射类
 +------------------------------------------------------------------------------
 * @category   SubModel
 * @package  app
 * @subpackage  Model
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2011-1-10
 * @time  下午06:08:20
 +------------------------------------------------------------------------------
 */
class MagazinesModel extends Model{
	protected $_auto=array(
		array("ctime",'time',1,'function'),
	);
}//end MagazinesModel