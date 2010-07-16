<?php
/**
 +------------------------------------------------------------------------------
 * EventAction控制器类
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  bi
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010年 05月 04日 星期二 10:51:37 CST 
 +------------------------------------------------------------------------------
 */
class EventAction extends CommonAction{
	protected $pcid='';
	function _initialize() {
		parent::_initialize();
	}//end _initialize()
	
	/**
	 *活动频道首页
	 *@date 2010年 05月 04日 星期二 10:56:27 CST
	 */
	function index() {
		//活动频道首页
		$this->chk_cid();
		$time=time();
		$dao=D("Archives");
		$condition=array();
		$condition['channel']='10';
		$condition['ismake']='1';
		$condition['cid']=$this->pcid;
		$condition['showstart']=array('lt',$time);
		$condition['showend']=array('gt',$time);
		$list=$dao->where($condition)->order("showstart DESC")->limit("0,11")->findAll();
		$this->assign('list',$list);
		$condition['showend']=array('lt',$time);
		$old=$dao->where($condition)->order("showstart DESC")->limit("0,8")->findAll();
		$this->assign('old',$old);
		
		$this->assign('range_time',$this->range_time());
		$this->assign('class_tree',$this->get_class(2050));
		$this->display();
	}//end index
	
	/**
	 *活动列表页
	 *@date 2010年 05月 04日 星期二 10:57:56 CST
	 */
	function ls() {
		//活动列表页
		$this->chk_cid();
		if ($_REQUEST['id']) {
			$_SESSION['typeid']=Input::getVar($_REQUEST['id']);
		}
		if ($_REQUEST['type']=='all') {
			unset($_SESSION['typeid']);
		}
		if ($_REQUEST['st']) {
			$_SESSION['st']=Input::getVar($_REQUEST['st']);
		}
		if ($_REQUEST['et']) {
			$_SESSION['et']=Input::getVar($_REQUEST['et']);
		}
		$condition['cid']=$this->pcid;
		$this->assign('range_time',$this->range_time());
		$this->assign('class_tree',$this->get_class(2050));
		
		$time=time();
		$dao=D("Archives");
		$condition=array();
		$condition['channel']='10';
		$condition['ismake']='1';
		if($_SESSION['st']!='0' && $_SESSION['et']!='0'){
			$condition['_string'] = "(`showstart`>='{$_SESSION['st']}' AND `showstart`<='{$_SESSION['et']}') OR (`showend`>='{$_SESSION['st']}' AND `showend`>='{$_SESSION['et']}')";
		}else{
			$condition['_string'] = "";
		}
		if($_SESSION['typeid']){
			$condition['typeid']=$_SESSION['typeid'];
		}

		$count=$dao->where($condition)->order("showstart DESC")->count();
		import("ORG.Util.Page");
		$page=new Page($count,10);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$page->config=array('header'=>'','prev'=>'<','next'=>'>','first'=>'«','last'=>'»','theme'=>' %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage_bot',$page->show_img());
		$limit=$page->firstRow.','.$page->listRows;
		$data=$dao->where($condition)->order("showstart DESC")->limit($limit)->findAll();
		$this->assign('data',$data);
		
		$this->assign('dh',$this->_get_dh($_SESSION['typeid']));
		$this->display();
	}//end ls

	/**
	 *活动内容页
	 *@date 2010-6-10
	 *@time 下午03:28:48
	 */
	function show() {
		//活动内容页
		$aid=Input::getVar($_REQUEST['aid']);
		$dao=D("Archives");
		$condition=array();
		$condition['channel']='10';
		$condition['ismake']='1';
		$condition['id']=$aid;
		$info=$dao->where($condition)->find();
		$info['content']=$dao->relationGet("event");
		$this->assign('info',$info);
		
		$this->assign('dh',$this->_get_dh($info['typeid']));
		
		$condition=array();
		$condition['types']=10;
		$condition['tid']=$info['id'];
		//hot 1感兴趣 2关注 3不关心
		$condition['hot']=1;
		$dao=D("Thought");
		$thought=$dao->where($condition)->order("ctime DESC")->limit("0,7")->findAll();
		$this->assign('thought',$thought);
		
		$post=D("Post");
		$condition=array();
		$condition['aid']=$info['id'];
		$condition['l']='1';
		
		$count=$post->where($condition)->count();
		import("ORG.Util.Page");
		$page=new Page($count,10);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$page->config=array('header'=>'','prev'=>'<','next'=>'>','first'=>'«','last'=>'»','theme'=>' %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage_bot',$page->show_img());
		$limit=$page->firstRow.','.$page->listRows;
		
		$thread=$post->where($condition)->order("dateline DESC")->limit($limit)->findAll();
		$this->assign('thread',$thread);
		
		$this->display();
	}//end show
	
	/**
	 *参与
	 *@date 2010-6-18
	 *@time 下午03:37:35
	 */
	function thought() {
		//参与
		$id=Input::getVar($_REQUEST['id']);
		
	}//end thought
	
	/**
	 *生成时间段
	 *@date 2010-6-13
	 *@time 下午03:39:35
	 */
	function range_time() {
		//生成时间段
		$t=time();
		$tarr=array();
		$day=60*60*24;
		$tarr['7']['name']='Last Week';
		$tarr['7']['st']=$t-($day*7);
		$tarr['7']['et']=$t+($day*7);
		$tarr['30']['name']='Last Month';
		$tarr['30']['st']=$t-($day*30);
		$tarr['30']['et']=$t+($day*30);
		$tarr['90']['name']='Recent Three Months';
		$tarr['90']['st']=$t-($day*30*3);
		$tarr['90']['et']=$t+($day*30*3);
		$tarr['365']['name']='This Year';
		$tarr['365']['st']=mktime(0,0,0,1,1,date('Y'));
		$tarr['365']['et']=mktime(0,0,0,1,1,date('Y')+1)-1;
		$tarr['all']['name']='All Time';
		$tarr['all']['st']=0;
		$tarr['all']['et']=0;
		return $tarr;
	}//end range_time
	
	/**
	 *获取分类
	 *@date 2010-7-6
	 *@time 下午03:48:19
	 */
	protected function get_class($topid) {
		//sm
		$dao=D("Arctype");
		$list=$dao->where("topid=$topid AND ishidden=0")->order("id ASC")->findAll();
		unset($dao);
		return $list;
	}//end function_name
	
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



}// END EventAction
?>
