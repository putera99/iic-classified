<?php
import("Think.Core.Model.ViewModel");
class AddonAgentsModel extends ViewModel
{
	// 视图模型中的字段定义 
	protected $viewFields = array( 
		"Model1"=>array("field1","field2","field3"), 
		"Model2"=>array("field1","field2","_on"=>"Model1.field1=Model2.field1") 
	); 
	
	// 视图模型的主键
	Public function getPk() { 
		return "Model1.field1"; 
	}

}
?>