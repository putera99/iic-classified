<?php
/**
 +------------------------------------------------------------------------------
 * CityGuideAction控制器类
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  app
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-4-30
 * @time  下午02:52:05
 +------------------------------------------------------------------------------
 */
class CityGuideAction extends CommonAction{
	
	protected $pcid='';//页面查询用的独立城市ID
	
	/**
	  *预处理
	  *@date 2010-4-30
	  *@time 上午10:08:20
	  */
	function _initialize() {
		//预处理
		if (intval($_GET['cid'])){
			
			$this->pcid=intval($_GET['cid']);
		}else{
			$this->_set_cid();
			$this->pcid=$this->cid;
		}
		parent::_initialize();
	}//end _initialize()
	
	/**
	 *城市指南频道首页
	 *@date 2010-4-30
	 *@time 下午02:52:26
	 */
	function index() {
		//城市指南频道首页
		$arctype=D("Arctype");
		$data=$arctype->where("topid=1000")->order("id asc")->findAll();
		$list=list_to_tree($data,'id','reid','_son',1);
		$this->assign('list',$list);
		$info=$arctype->where('id=1000')->find();
		$this->assign('info',$info);
		$page=array();
		$page['title']=empty($info['seotitle'])?$info['typename'].'  -  BeingfunChina':$info['seotitle'].'  -  BeingfunChina';
		$page['keywords']=empty($info['keywords'])?$info['typename']:$info['keywords'];
		$page['description']=empty($info['description'])?$info['typename']:$info['description'];
		$this->assign('page',$page);
		$this->assign('ctype',$this->_get_cityguide_type());
		$this->display();
	}//end index
}//end CityGuideAction