<?php
/**
 +------------------------------------------------------------------------------
 * ArchivesModel 数据对象映射类 关联模型
 +------------------------------------------------------------------------------
 * @category   SubModel
 * @package  app
 * @subpackage  Model
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-4-29
 * @time  上午10:16:36
 +------------------------------------------------------------------------------
 */
import("RelationModel");
class ArchivesModel extends RelationModel{
	protected $_auto=array(
		array('uid','get_uid',1,'function'),
		array('senddate','time',1,'function'),
		array('pubdate','time',1,'function'),
		array('uip','client_ip',1,'function'),
		array('editpwd','time',1,'function'),
		array('albumnum','0',1),
		array('click','0',1),
		array('ismake','1',1),
	);
	protected $_link = array(
		'Jobs'=>array(//关联招聘
				//ONE_TO_ONE(HAS_ONE/BELONGS_TO)、ONE_TO_MANY(HAS_MANY/BELONGS_TO)、  MANY_TO_MANY
				'mapping_type'=>HAS_ONE,
				'class_name'=>'AddonJobs',
		        'foreign_key'=>'aid',
		        'mapping_name'=>'jobs',
		        //'mapping_order'=>'ctime desc'
			),
		'Realestate'=>array(//关联房产
				//ONE_TO_ONE(HAS_ONE/BELONGS_TO)、ONE_TO_MANY(HAS_MANY/BELONGS_TO)、  MANY_TO_MANY
				'mapping_type'=>HAS_ONE,
				'class_name'=>'AddonRealestate',
		        'foreign_key'=>'aid',
		        'mapping_name'=>'realestate',
		        //'mapping_order'=>'ctime desc'
			),
		'Commerce'=>array(//关联买卖
				//ONE_TO_ONE(HAS_ONE/BELONGS_TO)、ONE_TO_MANY(HAS_MANY/BELONGS_TO)、  MANY_TO_MANY
				'mapping_type'=>HAS_ONE,
				'class_name'=>'AddonCommerce',
		        'foreign_key'=>'aid',
		        'mapping_name'=>'commerce',
		        //'mapping_order'=>'ctime desc'
			),
		'Agents'=>array(//关联折扣信息
				//ONE_TO_ONE(HAS_ONE/BELONGS_TO)、ONE_TO_MANY(HAS_MANY/BELONGS_TO)、  MANY_TO_MANY
				'mapping_type'=>HAS_ONE,
				'class_name'=>'AddonAgents',
		        'foreign_key'=>'aid',
		        'mapping_name'=>'agents',
		        //'mapping_order'=>'ctime desc'
			),
		'Personals'=>array(//关联交友
				//ONE_TO_ONE(HAS_ONE/BELONGS_TO)、ONE_TO_MANY(HAS_MANY/BELONGS_TO)、  MANY_TO_MANY
				'mapping_type'=>HAS_ONE,
				'class_name'=>'AddonPersonals',
		        'foreign_key'=>'aid',
		        'mapping_name'=>'personals',
		        //'mapping_order'=>'ctime desc'
			),
		'Services'=>array(//关联服务
				//ONE_TO_ONE(HAS_ONE/BELONGS_TO)、ONE_TO_MANY(HAS_MANY/BELONGS_TO)、  MANY_TO_MANY
				'mapping_type'=>HAS_ONE,
				'class_name'=>'AddonServices',
		        'foreign_key'=>'aid',
		        'mapping_name'=>'services',
		        //'mapping_order'=>'ctime desc'
			),
    );
    
}//end ArchivesModel