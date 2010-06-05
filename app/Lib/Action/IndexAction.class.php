<?php
class IndexAction extends CommonAction{
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
	
	
    public function index(){
    	$this->assign('pick',$this->_new_list(2001,'h','0,1'));
    	$this->assign('pick2',$this->_new_list(2001,'p','0,2'));
    	$this->assign('pick8',$this->_new_list(2001,'','2,8'));
    	
    	$this->assign('city_type',$this->_get_tree(1000));
    	$this->assign('classifieds_type',$this->_get_tree(1));
    	$classifieds_type=$this->_get_classifieds_type();
    	
    	
    	$classifieds=array();
    	foreach ($classifieds_type as $v){
    		$classifieds[$v['id']]['id']=$v['id'];
    		$classifieds[$v['id']]['_sub']=$this->_get_carc($v['id'],'0,10',$this->pcid);
    	}
    	//dump($classifieds);
    	$this->assign('classifieds',$classifieds);
    	
    	$page=array();
		$page['title']='BeingfunChina';
		$page['keywords']='BeingfunChina';
		$page['description']='BeingfunChina';
		$this->assign('page',$page);

    	$this->display();
    }
    
    
}
?>