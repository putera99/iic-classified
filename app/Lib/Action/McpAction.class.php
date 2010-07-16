<?php
/**
 +------------------------------------------------------------------------------
 * McpAction控制器类
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  app
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-6-25
 * @time  上午10:04:58
 +------------------------------------------------------------------------------
 */
class McpAction extends CpAction{
	function _initialize() {
		//预处理
		parent::_initialize();
		if ($this->user['username']!='iicc' && $this->user['username']!='bfcadmin') {
			$this->error("Insufficient privileges!");
		}
	}//end _initialize()
	
	/**
	 *统计
	 *@date 2010-6-25
	 *@time 上午10:07:53
	 */
	function stat() {
		//统计
		$condition=array();
		$user=intval($_POST['uid']);
		if($user){
			$condition['uid']=$user;
		}
		$username=$_POST['username'];
		if($username){
			$condition['username']=$username;
		}
		$stype=$_POST['stype'];
		if($stype){
			$condition['stype']=$stype;
		}
		$st=$_POST['st'];
		$et=$_POST['et'];
		if($st){
			$condition['ctime']=array('gt',$st);
		}
		if($et){
			$condition['ctime']=array('lt',$et);
		}
		$act=$_POST['act'];
		if($act){
			$condition['act']=$act;
		}
		$dao=D("Action");
		$count=$dao->where($condition)->count();
		$page=new Page($count,25);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$limit=$page->firstRow.','.$page->listRows;
    	$alog=array();
    	$alog=$dao->where($condition)->order("ctime DESC")->limit("$limit")->findAll();
    	dump($dao->getLastSql());
		$this->assign('alog',$alog);
		
		
		$page=array();
		$page['title']='Statistics -  My Control Panel -  BeingfunChina';
		$page['keywords']='Statistics';
		$page['description']='Statistics';
		$this->assign('page',$page);
		
		$this->assign('content','Mcp:stat');
		$this->display("Cp:layout");
	}//end stat
	
	/**
	 *角色管理
	 *@date 2010-6-26
	 *@time 下午02:41:33
	 */
	function role() {
		//角色管理
		$dao=M("Role");
		if($_POST['act']=='add'){
			$name=trim($_POST['name']);
			if(empty($name)){
				$this->error('角色名不能为空');
			}
			$qxarr=array();
			foreach ($_POST['xtype'] as $type){
				$qxarr[$type]=empty($_POST['qx'][$type])?$type.'_2':$_POST['qx'][$type];
			}
			$txt=serialize($qxarr);
			
			$data=array("name"=>$name,'status'=>'1','remark'=>$txt);
			$id=$dao->add($data);
			if ($id) {
				$this->success('添加成功');
			}else{
				$this->error('添加失败');
			}
		}elseif($_POST['act']=='edit'){
			$name=trim($_POST['name']);
			$roid=$_POST['roid'];
			if(empty($name)){
				$this->error('角色名不能为空');
			}
			$qxarr=array();
			foreach ($_POST['xtype'] as $type){
				$qxarr[$type]=empty($_POST['qx'][$type])?$type.'_2':$_POST['qx'][$type];
			}
			$txt=serialize($qxarr);
			$dao=M("Role");
			$data=array("id"=>$roid,"name"=>$name,'status'=>'1','remark'=>$txt);
			$id=$dao->save($data);
			if ($id) {
				$this->success('成功');
			}else{
				$this->error('失败');
			}
		}elseif($_GET['act']=='del'){
			$id=trim($_GET['roid']);
			$data=array("id"=>$id,'status'=>'0');
			$id=$dao->save($data);
			if ($id) {
				$this->success('成功');
			}else{
				$this->error('失败');
			}
		}
		if(!empty($_GET['roleid'])){
			$info=$dao->where("id={$_GET['roleid']}")->find();
			$info['remark']=unserialize($info['remark']);
			$this->assign('info',$info);
		}
		$alog=$dao->where("status=1")->findAll();
		$this->assign('alog',$alog);
		$page=array();
		$page['title']='Statistics -  My Control Panel -  BeingfunChina';
		$page['keywords']='Statistics';
		$page['description']='Statistics';
		$this->assign('page',$page);
		
		$this->assign('content','Mcp:role');
		$this->display("Cp:layout");
	}//end role
	
	/**
	 *用户管理
	 *@date 2010-6-26
	 *@time 下午02:49:22
	 */
	function user() {
		//用户管理
		$page=array();
		$page['title']='Statistics -  My Control Panel -  BeingfunChina';
		$page['keywords']='Statistics';
		$page['description']='Statistics';
		$this->assign('page',$page);
		
		$this->assign('content','Mcp:stat');
		$this->display("Cp:layout");
	}//end user
}//end McpAction