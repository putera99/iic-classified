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
		
		$this->assign('city_type',$this->_get_tree(1000));
	}//end _initialize()
	
	
	/**
	   *分类信息频道页
	   *@date 2010-4-29
	   *@time 上午09:58:27
	   */
	function index() {
		//分类信息频道页
		$this->chk_cid();
		
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
		$this->chk_cid();
		
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
		$page->config=array('header'=>'','prev'=>'<','next'=>'>','first'=>'«','last'=>'»','theme'=>' %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage_bot',$page->show_img());
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
		$this->assign('classifieds_type',$this->_get_tree(1));
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
		if (empty($info)) {
			$this->error("error: info is null!");
		}
		$city=$this->_get_city('localion');
		switch (true) {
			case $info['channel']==4://Jobs
				$info['_jobs']=$dao->relationGet("jobs");
				$info['itype']=$info['itype']=='0'?'All':$info['itype']=='1'?'Offered':'Wanted';
				$category=array("All","Full time","Part time","Internship","Voluntary");
				$info['category']=$category[$info['category']];
			break;
			
			case $info['channel']==5://realestate
				$info['_realestate']=$dao->relationGet("realestate");
			break;
			
			case $info['channel']==6://commerce
				$types=array(1=>'All',2=>'Offered',3=>'Wanted');
				$cat=array('Brand-new','Second-hand');
				$info['itype']=$types[$info['type']];
				$info['category']=$cat[$info['category']];
				$info['_commerce']=$dao->relationGet("commerce");
			break;
			
			case $info['channel']==7://agents
				$info['_agents']=$dao->relationGet("agents");
			break;
			
			case $info['channel']==8://personals
				$info['_personals']=$dao->relationGet("personals");
			break;
			
			case $info['channel']==9://services
				$info['itype']=$info['itype']==1?'All':$info['itype']==2?'Offered':'Wanted';
				$info['_services']=$dao->relationGet("services");
			break;
		}
		$local='';
		if($info['zone_id'] && $info['city_id']){
			$local=$city[$info['city_id']]['_zone'][$info['zone_id']]['name'].','.$city[$info['city_id']]['cename'];
		}elseif(empty($info['zone_id']) && $info['city_id']) {
			$local=$city[$info['city_id']]['cename'];
		}
		$info['position']=trim($info['position'].','.$local,',');

		$this->assign('info',$info);
		
		//$this->assign('comments',$this->_get_comments($info['id'],$info['channel']));
		
		$page=array();
		$page['title']=$info['title'].'  -  BeingfunChina';
		$page['keywords']=$info['keywords'];
		$page['description']=$info['description'];
		$this->assign('page',$page);
		
		
		//dump($this->_get_dh($info['typeid']));
		$this->assign('dh',$this->_get_dh($info['typeid']));
		$this->display();
	}//end show
	
	/**
	 *检查城市选项
	 *@date 2010-6-23
	 *@time 上午10:17:39
	 */
	protected function chk_cid() {
		//检查城市选项
		if (intval($_GET['cid'])){
			if($_SESSION['cid']){
				$this->pcid=intval($_GET['cid']);
			}else{
				$_SESSION['cid']=intval($_GET['cid']);
				cookie('cid',null);
				if ($_REQUEST['remember']) {
					cookie('cid',$cid,array('expire'=>60*60*60*24*30));
				}
			}
		}else{
			$this->_set_cid();
			$this->pcid=$this->cid;
		}
	}//end chk_cid
}//end ClassifiedsAction