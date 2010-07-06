<?php
/**
 +------------------------------------------------------------------------------
 * BizAction控制器类
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  app
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-5-11
 * @time  上午10:41:43
 +------------------------------------------------------------------------------
 */
class BizAction extends CommonAction{
	/**
	 *展会频道首页
	 *@date 2010-5-25
	 *@time 下午01:18:29
	 */
	function index() {
		//展会频道首页
		$this->assign('industries',$this->_get_fair());
		$this->assign("city",$this->_get_city('fair'));
		$this->assign('date',$this->_get_time());
		
		$this->assign('pick',$this->_new_list(2003,'h','0,1'));
    	$this->assign('pick8',$this->_new_list(2003,'','1,8'));
		$year=array();
		$nowyear=date('Y');
		$year['sy']['name']=$nowyear;
		$year['sy']['ms']=mktime(0,0,0,1,1,$nowyear);
		$year['sy']['me']=mktime(0,0,0,1,1,$nowyear+1)-1;
		$year['ny']['name']=$nowyear+1;
		$year['ny']['ms']=mktime(0,0,0,1,1,$nowyear+1);
		$year['ny']['me']=mktime(0,0,0,1,1,$nowyear+2)-1;
		$this->assign('year',$year);
		$condition=array();

		$small=$this->_get_fair();
		$str='';
		foreach ($small as $v){
			$str.=$v['id'].',';
		}
		$str=trim($str,',');
		$condition['typeid']=array('IN',$str);
		$condition['ismake']=1;
		$condition['industry']=empty($_REQUEST['flang'])?'EN':$_REQUEST['flang'];
		$dao=D("Archives");
		$count=$dao->where($condition)->order("id DESC")->count();
		import("ORG.Util.Page");
		$page=new Page($count,10);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$page->config=array('header'=>'','prev'=>'<','next'=>'>','first'=>'«','last'=>'»','theme'=>' %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage_bot',$page->show_img());
		$limit=$page->firstRow.','.$page->listRows;
		$data=$dao->where($condition)->order("id DESC")->findAll();
		$this->assign('data',$data);
		
		$page=array();
		$page['title']='China Biz  -  BeingfunChina';
		$page['keywords']="China,Biz,Fair";
		$page['description']="China,Biz,Fair";
		$this->assign('page',$page);
		
		$this->display();
	}//end index
	
	/**
	 *展会列表
	 *@date 2010-5-28
	 *@time 下午04:43:02
	 */
	function ls() {
		//展会列表
		if ($_REQUEST['year']) {
			$_SESSION['year']=$_REQUEST['year'];
		}
		if ($_REQUEST['id']) {
			if($_REQUEST['id']=='all'){
				unset($_SESSION['fair_id']);
			}else{
				$_SESSION['fair_id']=$_REQUEST['id'];
			}
		}
		if ($_REQUEST['bycity']) {
			if($_REQUEST['bycity']=='all'){
				unset($_SESSION['bycity']);
			}else{
				$_SESSION['bycity']=$_REQUEST['bycity'];
			}
		}
		
		if ($_REQUEST['tn']) {
			$_SESSION['tn']=$_REQUEST['tn'];
		}
		if($_REQUEST['order']){
			$_SESSION['order']=$_REQUEST['order'];
		}else{
			$_SESSION['order']='DESC';
		}
		if($_REQUEST['flang']){
			$_SESSION['flang']=$_REQUEST['flang'];
		}
		
		$condition=array();
		$year=array();
		$nowyear=date('Y');
		if ($_SESSION['year']) {
			$this->assign('date',$this->_get_time($_SESSION['year']));
			
			$year['sy']['name']=$_SESSION['year'];
			$year['sy']['ms']=mktime(0,0,0,1,1,$_SESSION['year']);
			$year['sy']['me']=mktime(0,0,0,1,1,$_SESSION['year']+1)-1;
			$year['ny']['name']=$_SESSION['year']+1;
			$year['ny']['ms']=mktime(0,0,0,1,1,$_SESSION['year']+1);
			$year['ny']['me']=mktime(0,0,0,1,1,$_SESSION['year']+2)-1;
			$_SESSION['ms']=$year['sy']['ms'];
			$_SESSION['me']=$year['sy']['me'];
		}else{
			$this->assign('date',$this->_get_time());
			$year['sy']['name']=$nowyear;
			$year['sy']['ms']=mktime(0,0,0,1,1,$nowyear);
			$year['sy']['me']=mktime(0,0,0,1,1,$nowyear+1)-1;
			$year['ny']['name']=$nowyear+1;
			$year['ny']['ms']=mktime(0,0,0,1,1,$nowyear+1);
			$year['ny']['me']=mktime(0,0,0,1,1,$nowyear+2)-1;
		}
		$this->assign('year',$year);
		if ($_REQUEST['ms']) {
			$_SESSION['ms']=$_REQUEST['ms'];
		}
		if ($_REQUEST['me']) {
			$_SESSION['me']=$_REQUEST['me'];
		}
		if ($_SESSION['fair_id']) {
			$condition['typeid']=$_SESSION['fair_id'];
		}else{
			$small=$this->_get_fair();
			$str='';
			foreach ($small as $v){
				$str.=$v['id'].',';
			}
			$str=trim($str,',');
			$condition['typeid']=array('IN',$str);
		}
		if ($_SESSION['bycity']) {
			$condition['bycity']=$_SESSION['bycity'];
		}
		if($_SESSION['flang']){
			$condition['industry']=empty($_SESSION['flang'])?'EN':$_SESSION['flang'];
		}
		if ($_SESSION['ms'] || $_SESSION['me']) {
			$condition['_string'] = "(`showstart`>='{$_SESSION['ms']}' AND `showstart`<='{$_SESSION['me']}') OR (`showend`>='{$_SESSION['ms']}' AND `showend`>='{$_SESSION['me']}')";
		}
		
		$this->assign('industries',$this->_get_fair());
		$this->assign("city",$this->_get_city('fair'));
		$condition['ismake']=1;
		
		$dao=D("Archives");
		$count=$dao->where($condition)->order("id {$_SESSION['order']}")->count();
		import("ORG.Util.Page");
		$page=new Page($count,10);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$page->config=array('header'=>'','prev'=>'<','next'=>'>','first'=>'«','last'=>'»','theme'=>' %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage_bot',$page->show_img());
		$limit=$page->firstRow.','.$page->listRows;
		$data=$dao->where($condition)->order("id {$_SESSION['order']}")->limit($limit)->findAll();
		$this->assign('data',$data);
		
		$arctype=D("Arctype");
		$info=$arctype->where("id={$_SESSION['fair_id']}")->find();
		$this->assign('info',$info);
		$page=array();
		$page['title']=empty($info['seotitle'])?$info['typename'].' - Fair -  BeingfunChina':$info['seotitle'].' - Fair  -  BeingfunChina';
		$page['keywords']=empty($info['keywords'])?$info['typename']:$info['keywords'];
		$page['description']=empty($info['description'])?$info['typename']:$info['description'];
		if($info['reid']!='1232'){
			$reinfo=$arctype->where("id={$info['reid']}")->find();
			$this->assign('reinfo',$reinfo);
		}
		$this->assign('page',$page);
		$this->display();
	}//end ls
	
	/**
	 *展会信息
	 *@date 2010-6-2
	 *@time 上午10:38:22
	 */
	function show() {
		//展会信息
		$aid=intval($_REQUEST['aid']);
		$this->assign('industries',$this->_get_fair());
		$this->assign("city",$this->_get_city('fair'));
		$this->assign('date',$this->_get_time());
		
		$year=array();
		$nowyear=date('Y');
		$year['sy']['name']=$nowyear;
		$year['sy']['ms']=mktime(0,0,0,1,1,$nowyear);
		$year['sy']['me']=mktime(0,0,0,1,1,$nowyear+1)-1;
		$year['ny']['name']=$nowyear+1;
		$year['ny']['ms']=mktime(0,0,0,1,1,$nowyear+1);
		$year['ny']['me']=mktime(0,0,0,1,1,$nowyear+2)-1;
		$this->assign('year',$year);
		
		$flang=Input::getVar($_REQUEST['flang']);
		$dao=D("Archives");
		$condition=array();
		if($flang){
			$lan=explode('_',$flang);
			if($lan['1']=='CN'){
				$flang=$lan['0'].'_CN';
			}else{
				$flang=$lan['0'].'_EN';
			}
			$condition['writer']=$flang;
			$condition['channel']=11;
		}else{
			$condition['id']=$aid;
		}
		$info=$dao->where($condition)->find();
		$lan=explode('_',$info['writer']);
		$lang['cn']=$lan['0'].'_CN';
		$lang['en']=$lan['0'].'_EN';
		$this->assign('lang',$lang);
		$info['_fair']=$dao->relationGet("fair");
		$this->assign('info',$info);
		$page=array();
		$page['title']=$info['title'].' China Biz  -  BeingfunChina';
		$page['keywords']=$info['keywords'].',fair,china,biz';
		$page['description']=$info['description'];
		$this->assign('page',$page);
		
		$this->assign('dh',$this->_get_dh($info['typeid']));
		
		$this->display();
	}//end show
	
	
	
	/**
	 *获取展会的类别
	 *@date 2010-5-28
	 *@time 下午04:13:31
	 */
	protected function _get_fair() {
		//获取展会的类别
		$data=S("fair_industries");
		if(empty($data)){
			$dao=D("Arctype");
			$data=$dao->where("reid=1232")->findAll();
			S("fair_industries",$data,60*60*60*24*30);
		}
		return $data;
	}//end _get_fair
	
	protected function _get_time($y=0){
		$y=$y==0?date('Y',time()):$y;
		$data=array();
		for($i=1;$i<13;$i++){
			$data[$i]['name']=date('M',mktime(0,0,0,$i,1,$y));
			$data[$i]['start']=mktime(0,0,0,$i,1,$y);
			$data[$i]['end']=mktime(0,0,0,$i+1,1,$y)-1;
		}
		return $data;
	}
}//end BizAction