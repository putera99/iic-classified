<?php
/**
 +------------------------------------------------------------------------------
 * CityGuideViewModel 数据对象映射类
 +------------------------------------------------------------------------------
 * @category   SubModel
 * @package  app
 * @subpackage  Model
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-5-15
 * @time  下午01:44:08
 +------------------------------------------------------------------------------
 */
import("Think.Core.Model.ViewModel");
class CityGuideViewModel extends ViewModel{
	protected $viewFields = array( 
		"Archives"=>array("*"), 
		"AddonArticle"=>array("content","_on"=>"Archives.id=AddonArticle.aid") 
	); 
	
	// 视图模型的主键
	Public function getPk() { 
		return "Archives.id"; 
	}
	
}//end CityGuideViewModel