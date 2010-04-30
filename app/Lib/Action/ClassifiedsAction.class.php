<?php
/**
 +------------------------------------------------------------------------------
 * ClassifiedsAction控制器类
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  app
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-4-29
 * @time  上午09:57:29
 +------------------------------------------------------------------------------
 */
class ClassifiedsAction extends CommonAction{
	//protected $cityguide_type=array();
	/**
	  *预处理
	  *@date 2010-4-30
	  *@time 上午10:08:20
	  */
	function _initialize() {
		//预处理
		//$this->$cityguide_type=$this->_get_cityguide_type();
		parent::_initialize();
	}//end _initialize()
	
	
	/**
	   *分类信息频道页
	   *@date 2010-4-29
	   *@time 上午09:58:27
	   */
	function index() {
		//分类信息频道页
		$arctype=D("Arctype");
		$data=$arctype->where("topid=1 AND ishidden=0")->order("id asc")->findAll();
		$list=list_to_tree($data,'id','reid','_son',1);
		$this->assign('list',$list);
		//dump($list);
		$info=$arctype->where('id=1')->find();
		$this->assign('info',$info);
		$page=array();
		$page['title']=empty($info['seotitle'])?$info['typename'].'  -  BeingfunChina':$info['seotitle'].'  -  BeingfunChina';
		$page['keywords']=empty($info['keywords'])?$info['typename']:$info['keywords'];
		$page['description']=empty($info['description'])?$info['typename']:$info['description'];
		$this->assign('page',$page);
		$this->assign('ctype',$this->_get_cityguide_type());
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
		$cid=empty(intval($_GET['cid']))? $this->cid : intval($_GET['cid']);
		$now=time();
		import("ORG.Util.Page");
		$dao=D("Archives");
		$count=$dao->where("((typeid={$typeid} AND cid={$cid}) AND ismake=1) AND (showstart<{$now} AND showend>{$now})")->order("pubdate DESC")->count();
		$page=new Page($count);
		$this->assign('showpage',$page->show());
		$limit=$page->firstRow.','.$page->listRows;
		$data=$dao->where("((typeid={$typeid} AND cid={$cid}) AND ismake=1) AND (showstart<{$now} AND showend>{$now})")->order("pubdate DESC")->limit("$limit")->findAll();
		$this->assign('list',$data);
		$this->display();
	}//end function_name
	
	/**
	 *分类信息内容页面
	 *@date 2010-4-30
	 *@time 下午05:08:30
	 */
	function show() {
		//分类信息内容页面
		
		
	}//end show
}//end ClassifiedsAction