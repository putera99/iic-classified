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
		/*if (intval($_GET['cid'])){
			
			$this->pcid=intval($_GET['cid']);
		}else{
			$this->_set_cid();
			$this->pcid=$this->cid;
		}*/
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
		$data=$arctype->where("id>1000 AND topid=1000")->order("id asc")->findAll();
		$list=list_to_tree($data,'id','reid','_son',1000);
		//dump($list);
		$this->assign('list',$list);
		$info=$arctype->where('id=1000')->find();
		$this->assign('info',$info);
		$page=array();
		$page['title']=empty($info['seotitle'])?$info['typename'].'  -  BeingfunChina':$info['seotitle'].'  -  BeingfunChina';
		$page['keywords']=empty($info['keywords'])?$info['typename']:$info['keywords'];
		$page['description']=empty($info['description'])?$info['typename']:$info['description'];
		$this->assign('page',$page);
		$this->assign('ctype',$this->_get_classifieds_type());
		$this->display();
	}//end index
	
	/**
	 *分类信息列表页面
	 *@date 2010-4-30
	 *@time 下午04:36:24
	 */
	function ls() {
		//分类信息列表页面
		$typeid=intval($_GET['id']);
		
		//信息列表
		$now=time();
		import("ORG.Util.Page");
		$dao=D("Archives");
		//$count=$dao->where("((typeid={$typeid} AND cid={$this->pcid}) AND ismake=1) AND (showstart<{$now} AND showend>{$now})")->order("pubdate DESC")->count();
		$count=$dao->where("(typeid={$typeid} AND cid={$this->pcid}) AND ismake=1")->order("pubdate DESC")->count();
		$page=new Page($count,10);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$limit=$page->firstRow.','.$page->listRows;
		//$data=$dao->where("((typeid={$typeid} AND cid={$this->pcid}) AND ismake=1) AND (showstart<{$now} AND showend>{$now}))")->order("pubdate DESC")->limit("$limit")->findAll();
		$data=$dao->where("typeid={$typeid} AND ismake=1")->order("pubdate DESC")->limit("$limit")->findAll();
		$this->assign('list',$data);
		//dump($dao->getLastSql());
		//分类信息 导航
		$this->assign('classifieds_type',$this->_get_classifieds_type());
		
		//页面信息
		$arctype=D("Arctype");
		$info=$arctype->where("id=$typeid")->find();
		$this->assign('info',$info);
		$page=array();
		$page['title']=empty($info['seotitle'])?$info['typename'].'  -  BeingfunChina':$info['seotitle'].'  -  BeingfunChina';
		$page['keywords']=empty($info['keywords'])?$info['typename']:$info['keywords'];
		$page['description']=empty($info['description'])?$info['typename']:$info['description'];
		$this->assign('page',$page);
		
		if($info['reid']!='1000'){
			$reinfo=$arctype->where("id={$info['reid']}")->find();
			$this->assign('reinfo',$reinfo);
		}

		$this->display();
	}//end function_name
	
	/**
	 *城市指南内容页
	 *@date 2010-5-12
	 *@time 下午04:13:26
	 */
	function show() {
		//城市指南内容页
		$aid=intval($_GET['aid']);
		if(empty($aid)){
			$this->error("error: aid is null!");
		}
		$dao=D("CityGuideView");
		$info=$dao->where("Archives.id=$aid")->find();
		$this->assign('info',$info);
		
		$page=array();
		$page['title']=$info['title'].'  -  BeingfunChina';
		$page['keywords']=$info['keywords'];
		$page['description']=$info['description'];
		$this->assign('page',$page);
		
		$this->assign('dh',$this->_get_dh($info['typeid']));
		
		$this->display();
	}//end show
}//end CityGuideAction