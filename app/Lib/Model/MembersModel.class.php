<?php
/**
 +------------------------------------------------------------------------------
 * MemberModel 数据对象映射类
 +------------------------------------------------------------------------------
 * @category   SubModel
 * @package  app
 * @subpackage  Model
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-7-19
 * @time  上午10:29:58
 +------------------------------------------------------------------------------
 */
class MembersModel extends Model{
	//protected $trueTableName ='iic_members';
	//protected $dbName ='members';
	protected $_auto=array(
		array('regip','client_ip',1,'function'),
		array('lastip','client_ip',3,'function'),
		array('groupid','10',1),
		array('groupid','10',1),
		array('adminid','0',1),
		array('regdate','time',1,'function'),
		array('lastvisit','time',3,'function'),
	);
}//end MemberModel