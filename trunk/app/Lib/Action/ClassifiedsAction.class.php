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
	protected $pcid='';//页面查询用的独立城市ID
	//protected $cityguide_type=array();
	
	/**
	  *预处理
	  *@date 2010-4-30
	  *@time 上午10:08:20
	  */
	function _initialize() {
		//预处理
		parent::_initialize();
		if (intval($_GET['cid'])){
			
			$this->pcid=intval($_GET['cid']);
		}else{
			$this->_set_cid();
			$this->pcid=$this->cid;
		}
		$this->assign('city_type',$this->_get_tree(1000));
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
		
		$arctype=D("Arctype");
		$info=$arctype->where("id=$typeid")->find();
		
		if($info['ispart']==1){
			$small=$arctype->where("reid=$typeid")->field("id")->findAll();
			$str='';
			foreach ($small as $v){
				$str.=$v['id'].',';
			}
			$str='typeid IN ('.trim($str,',').')';
		}else{
			$str="typeid={$typeid}";
		}
		//信息列表
		$now=time();
		import("ORG.Util.Page");
		$dao=D("Archives");
		//$count=$dao->where("((typeid={$typeid} AND cid={$this->pcid}) AND ismake=1) AND (showstart<{$now} AND showend>{$now})")->order("pubdate DESC")->count();
		$count=$dao->where("($str AND cid={$this->pcid}) AND ismake=1")->order("pubdate DESC")->count();
		$page=new Page($count,10);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$limit=$page->firstRow.','.$page->listRows;
		//$data=$dao->where("((typeid={$typeid} AND cid={$this->pcid}) AND ismake=1) AND (showstart<{$now} AND showend>{$now}))")->order("pubdate DESC")->limit("$limit")->findAll();
		$data=$dao->where("($str AND cid={$this->pcid}) AND ismake=1")->order("pubdate DESC")->limit("$limit")->findAll();
		$this->assign('list',$data);
		
		//页面信息
		$arctype=D("Arctype");
		$info=$arctype->where("id=$typeid")->find();
		$this->assign('info',$info);
		$page=array();
		$page['title']=empty($info['seotitle'])?$info['typename'].'  -  BeingfunChina':$info['seotitle'].'  -  BeingfunChina';
		$page['keywords']=empty($info['keywords'])?$info['typename']:$info['keywords'];
		$page['description']=empty($info['description'])?$info['typename']:$info['description'];
		
		if($info['reid']!='1'){
			$reinfo=$arctype->where("id={$info['reid']}")->find();
			$this->assign('reinfo',$reinfo);
		}
		
		$this->assign('page',$page);
		
		$this->display();
	}//end function_name
	
	/**
	 *分类信息内容页面
	 *@date 2010-4-30
	 *@time 下午05:08:30
	 */
	function show() {
		//分类信息内容页面
		$aid=intval($_GET['aid']);
		if(empty($aid)){
			$this->error("error: aid is null!");
		}
		$dao=D("Archives");
		$info=$dao->where("id=$aid")->find();
		switch (true) {
			case $info['channel']==4://Jobs
				$info['_jobs']=$dao->relationGet("jobs");
				$info['itype']=$info['itype']=='0'?'All':$info['itype']=='1'?'Offered':'Wanted';
				$category=array("All","Full time","Part time","Internship","Voluntary");
				$info['category']=$category[$info['category']];
				//$info['city']=
			break;
			
			case $info['channel']==5://realestate
			
			break;
		}

		$this->assign('info',$info);
		
		$page=array();
		$page['title']=$info['title'].'  -  BeingfunChina';
		$page['keywords']=$info['keywords'];
		$page['description']=$info['description'];
		$this->assign('page',$page);
		
		
		//dump($this->_get_dh($info['typeid']));
		$this->assign('dh',$this->_get_dh($info['typeid']));
		$this->display();
	}//end show
}//end ClassifiedsAction