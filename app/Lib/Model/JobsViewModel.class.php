<?php
import("Think.Core.Model.ViewModel");
class AddonJobsModel extends ViewModel{
	// 视图模型中的字段定义 
	protected $viewFields = array( 
		"Archives"=>array("*"), 
		"AddonJobs"=>array("joblocated","experience","salary","content","_on"=>"Archives.id=AddonJobs.aid") 
	); 
/*	  `joblocated` varchar(100) DEFAULT '' COMMENT '工作地区',
  `experience` varchar(50) NOT NULL DEFAULT '' COMMENT '工作经验',
  `salary` varchar(20) DEFAULT '0' COMMENT '薪水',
  `content` mediumtext NOT NULL COMMENT '工作描述',*/
	
	// 视图模型的主键
	Public function getPk() { 
		return "Archives.id"; 
	}

}
?>