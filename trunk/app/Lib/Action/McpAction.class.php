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
		if (!in_array($this->user['username'],$this->admin,true)) {
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
    	//dump($dao->getLastSql());
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
	   *管理友情链接
	   *@date 2010-8-12
	   *@time 下午03:19:24
	   */
	function links() {
		//管理友情链接
		$act=empty($_GET['act'])?'index':Input::getVar($_GET['act']);
		$jump=empty($_GET['to'])?'/Mcp/links/act/index':mydecode($_REQUEST['to']);
		$this->assign('jumpUrl',$jump);
		$dao=D("Flink");
		switch ($act) {
			case 'add'://添加链接
				$vo=$dao->create();
				if($vo){
					if(empty($_POST['is_upfile'])){
						$vo['logo']=$_POST['logo_url'];
					}else{
						if(!empty($_FILES["logo"]["name"])) {
							$vo['logo']=$this->_group_up(132,105,false,'/Public/Uploads/flinks/');
						}else{
							$vo['logo']=$_POST['logo_url'];
						}
					}
					if($_POST['action']=="edit"){
						if($dao->save($vo)){
							$this->success("Saved successfully!");
						}else{
							$this->error("Failed to save, try again!");
						}
					}elseif($_POST['action']=="add"){
						$id=$dao->add($vo);
						if($id){
							$this->success("Added successfully!");
						}else{
							$this->error("Failed to add, try again!");
						}
					}
				}else{
					$this->error($dao->getDbError());
				}
			break;
			case 'del'://删除链接
				$id=Input::getVar($_GET['id']);
				$condition=array('id'=>$id);
				if($dao->where($condition)->limit('1')->delete()){
					$this->success("delete successfully!");
				}else{
					$this->error("Failed to delete, try again!");
				}
			break;
			case 'show'://隐藏或显示
				$id=Input::getVar($_GET['id']);
				$condition=array('id'=>$id);
				$info=$dao->where($condition)->find();
				if($info['ischeck']=='1'){
					$data=array("ischeck"=>'0');
				}else{
					$data=array("ischeck"=>'1');
				}
				if($dao->where($condition)->save($data)){
					$this->success("Saved successfully!");
				}else{
					$this->error("Failed to save, try again!");
				}
			break;
			case 'edit'://修改链接
				$id=Input::getVar($_GET['id']);
				$condition=array('id'=>$id);
				$info=$dao->where($condition)->find();
				$this->assign('info',$info);
			break;
		}
		$condition=array();
		$links=$dao->where($condition)->order("sortrank ASC")->findAll();
		$this->assign('links',$links);
		$page=array();
		$page['title']='links -  My Control Panel -  BeingfunChina';
		$page['keywords']='links';
		$page['description']='links';
		$this->assign('page',$page);
		$this->assign('content','Mcp:links');
		$this->display("Cp:layout");
	}//end links
	
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
			$data=array("name"=>$name,'status'=>'1','remark'=>$txt,'about'=>$_POST['about'],'ctime'=>time());
			$id=$dao->add($data);
			if ($id) {
				$this->redirect('/Mcp/role');
//				$this->assign('jumpUrl',__URL__.'/role');
//				$this->success('添加成功',0);
			}else{
				$this->error('添加失败',0);
			}
		}elseif($_POST['act']=='edit'){
			$name=trim($_POST['name']);
			$roid=$_POST['roid'];
			if(empty($name)){
				$this->error('角色名不能为空',0);
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
				$this->redirect('/Mcp/role');
//				$this->assign('jumpUrl',__URL__.'/role',0);
//				$this->success('成功');
			}else{
				$this->error('失败',0);
			}
		}elseif($_GET['act']=='del'){
			$id=trim($_GET['id']);
			//$data=array("id"=>$id,'status'=>'0');
			$id=$dao->where("id=$id")->delete();
			if ($id) {
				$this->redirect('/Mcp/role');
//				$this->assign('jumpUrl',__URL__.'/role');
//				$this->success('成功',0);
			}else{
				$this->error('失败',0);
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
		$id=Input::getVar($_REQUEST['id']);
		$dao=D("RoleUser");
		if($id){
			$this->assign('roid',$id);
			$data['role_id']=$id;
			$info=$dao->where($data)->findAll();
		}else{
			$this->assign('jumpUrl',__URL__."/role/");
			$this->error('请先选择角色组');
		}
		$this->assign('info',$info);
		
		$act=Input::getVar($_REQUEST['act']);
		if($act=='add'){
			$member=D("Member");
			$uinfo=$member->where("username='{$_POST['username']}' AND uid='{$_POST['uid']}'")->find();
			dump($member->getLastSql());
			if($uinfo){
				$vo=$dao->create();
				if(empty($vo['role_id'])){
					$this->assign('jumpUrl',__URL__."/user/id/{$id}");
					$this->error('设置失败');
				}
				if(empty($vo['user_id'])){
					$this->assign('jumpUrl',__URL__."/user/id/{$id}");
					$this->error('设置失败');
				}
				
				$id=$dao->add($vo);
				if($id){
					$this->redirect("/Mcp/user/id/{$id}");
				}else{
					$this->assign('jumpUrl',__URL__."/user/id/{$id}");
					$this->error('设置失败');
				}
			}else{
				//$this->assign('jumpUrl',__URL__."/user/id/{$id}");
				$this->error('用户信息不匹配');
			}
			
		}elseif($act=='del'){
			$ruid=Input::getVar($_REQUEST['ruid']);
			if ($ruid){
				$dao->where("id=$ruid")->delete();
				$this->redirect("/Mcp/user/id/{$id}");
			}
		}
		$page=array();
		$page['title']='Role_user -  My Control Panel -  BeingfunChina';
		$page['keywords']='Role_user';
		$page['description']='Role_user';
		$this->assign('page',$page);
		
		$this->assign('content','Mcp:user');
		$this->display("Cp:layout");
	}//end user
	
	/**
	 *发布杂志
	 *@date 2010-6-3
	 *@time 下午02:48:59
	 */
	function post_magazine() {
		//发布杂志
		$page=array();
		$page['title']='Post Magazine -  My Control Panel -  BeingfunChina';
		$page['keywords']='Post Magazine';
		$page['description']='Post Magazine';
		$this->assign('page',$page);
		
		if($_GET['act']=='edit'){
			$id=Input::getVar($_GET['id']);
			$dao=D("Magazines");
			$data=$dao->where("id=$id")->find();
			$this->assign("data",$data);
		}
		
		$this->assign('content','Mcp:post_magazine');
		$this->display("Cp:layout");
	}//end post_art
	
		/**
	 *增加新闻
	 *@date 2010-6-3
	 *@time 下午04:06:38
	 */
	function add_magazine() {
		//增加新闻
		$dao=D("Magazines");
		$vo=$dao->create();
		if($vo){
			$vo['middleimg']=trim($vo['middleimg'],',');
			$vo['smallimg']=trim($vo['smallimg'],',');
			$vo['content']=nl2br($vo['content']);
			$t=explode('/',$vo['showtime']);
			$vo['showtime']=mktime('0',0,0,$t['1'],$t['0'],$t['2']);
			if($_POST['id']){
				$dao->save($vo);
				$aid=$_POST['id'];
			}else{
				$aid=$dao->add($vo);
			}
			if ($aid) {
				//echo "发布成功!";
				$txt='<h4>Successful release. </h4><br><a href="/Magazine/index/vol/'.$vo['vol'].'">View magazine </a>   /   ';
				$txt.='<a href="/Mcp/photo/info/14_'.$aid.'">Add pictures</a><br>';
				$txt.='<a href="/Mcp/post_magazine">Post magazine</a>   /   ';
				$txt.='<a href="/Mcp/my_magazine">Back to the list </a><br>';
				$txt.='You will be directed to the picture uploading page in three seconds! ';
				$this->assign('jumpUrl','/Cp/photo/info/14_'.$aid);
				$this->success($txt);
			}else{
				$this->error('Failed to update the main profile table. ');
			}
			//dump($vo);
		}else{
			$this->error($dao->getError());
		}
	}//end add_art
	
	/**
	 *我的新闻
	 *@date 2010-6-3
	 *@time 下午02:48:59
	 */
	function my_magazine() {
		//发布新闻
		$dao=D("Magazines");
		$condition=array();
    	$count=$dao->where($condition)->count();
		$page=new Page($count,25);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$limit=$page->firstRow.','.$page->listRows;
    	$magazine=array();
    	$magazine=$dao->where($condition)->order("id DESC")->limit("$limit")->findAll();
		$this->assign('magazine',$magazine);
		//dump($dao->getLastSql());
		$page=array();
		$page['title']='My magazine -  My Control Panel -  BeingfunChina';
		$page['keywords']='My magazine';
		$page['description']='My magazine';
		$this->assign('page',$page);
		
		$this->assign('content','Mcp:my_magazine');
		$this->display("Cp:layout");
	}//end my_art
	
	/**
	 *编辑信息
	 *@date 2010-6-18
	 *@time 下午06:29:13
	 */
	function my_edit_art() {
		//编辑信息
		$info=Input::getVar($_REQUEST['info']);
		$info=explode('_',$info);
		$dao=D("Archives");
		$condition=array();
		$condition['channel']=$info['0'];
		$condition['id']=$info['1'];
		$data=$dao->where($condition)->find();
		if ($data['uid']==$this->user['uid']) {
			$data['content']=$dao->relationGet("arc");
			$this->assign('data',$data);
		}else{
			$this->error("Insufficient privilege.");
		}
		
		$this->assign('flag',$this->_get_flag());
		$class_tree=$this->_get_tree(2000);
		$this->assign("class_tree",$class_tree);
		$this->assign('citylist',$this->_get_city('city'));
		$page=array();
		$page['title']='Edit Articles -  My Control Panel -  BeingfunChina';
		$page['keywords']='Edit Articles';
		$page['description']='Edit Articles';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:post_art');
		$this->display("Cp:layout");
	}//end my_edit_art
	
}//end McpAction