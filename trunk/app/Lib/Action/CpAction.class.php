<?php
/**
 +------------------------------------------------------------------------------
 * CpAction控制器类 控制面板
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  app
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-5-5
 * @time  下午03:39:52
 +------------------------------------------------------------------------------
 */
class CpAction extends CommonAction{
	protected $admin=array('yeahbill','iicc','bfcadmin','Alex','bfc168');
	function _initialize() {
		//预处理
		if (!$this->_is_login()){
			$arr=array('/cid/3','/cid/2','/cid/1','/cid/4','/cid/0','/1.html','/0.html','/2.html','/3.html','/4.html','.html','/index.php?s=','/Public/select_city');
	        $url=str_replace($arr,'',$_SERVER["REQUEST_URI"]);
	        $url=$url=='/'?'/Index/index':$url;
	        $url=myencode($url);
			redirect("/Public/login/to/$url");
		}
		$role=$this->_get_role();
		import("ORG.Util.Page");
		parent::_initialize();
		
		$this->assign('pmnum',$this->ckh_pm());
	}//end _initialize()
	
	/**
	 *控制面板首页
	 *@date 2010-5-5
	 *@time 下午03:42:51
	 */
	function index() {
		//控制面板首页
		$page=array();
		$page['title']='My Control Panel -  BeingfunChina 缤纷中国';
		$page['keywords']='My Control Panel';
		$page['description']='My Control Panel';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:index');
		$this->display("Cp:layout");
	}//end index
	
	/**
	 *我发布的分类信息
	 *@date 2010-5-20
	 *@time 下午07:20:28
	 */
	function my_classifieds_post() {
		//我发布的分类信息
		$dao=D("Archives");
		$condition=array();
		$condition['channel']=array('in','4,5,6,7,8,9');
		$classifieds_type=$this->_get_classifieds_type();
    	$this->assign('classifieds_type',$classifieds_type);
    	$typeid=$_REQUEST['id'];
    	if(!empty($typeid)){
    		$type=D("Arctype");
	    	$data=$type->where("id=$typeid")->field('id,typename,reid,topid,ispart')->find();
	    	$typearr=array();
	    	if($data['ispart']!=0){
	    		$typearr=$type->where("reid=$typeid")->field('id,typename,reid,topid,ispart')->findAll();
	    		$in='';
	    		foreach ($typearr as $v){
	    			$in.=$v['id'].',';
	    		}
	    		$in=trim($in,',');
	    		$condition['typeid']=array('IN',$in);
	    	}else{
	    		$condition['typeid']=$typeid;
	    	}
    	}
    	if(!$this->is_admin()){
    		$condition['uid']=$this->user['uid'];
    		$condition['ismake']='1';
    	}
    	$count=$dao->where($condition)->count();
		$page=new Page($count,25);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$limit=$page->firstRow.','.$page->listRows;
    	$classifieds=array();
    	$classifieds=$dao->where($condition)->order("pubdate DESC")->limit("$limit")->findAll();
		$this->assign('classifieds',$classifieds);
    	
		$page=array();
		$page['title']='My Classifieds -  My Control Panel -  BeingfunChina';
		$page['keywords']='My Control Panel';
		$page['description']='My Control Panel';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:my_classifieds_post');
		$this->display("Cp:layout");
	}//end my_classifieds_post
	
	/**
	 *我的站内短信
	 *@date 2010-5-20
	 *@time 下午08:02:19
	 */
	function my_message() {
		//我的站内短信
		$dao=D("Pm");
		$condition=array();
		$condition['fromuid']=$this->user['uid'];
		$count=$dao->where($condition)->count();
		$page=new Page($count,10);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$limit=$page->firstRow.','.$page->listRows;
		$list=$dao->where($condition)->order("ctime DESC")->limit("$limit")->findAll();
		$this->assign('pm',$list);
		
		$page=array();
		$page['title']='My Message -  My Control Panel -  BeingfunChina';
		$page['keywords']='My Message';
		$page['description']='My Message';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:my_message');
		$this->display("Cp:layout");
	}//end my_message

	/**
	   *发送短消息
	   *@date 2010-8-2
	   *@time 下午03:12:48
	   */
	function post_pm() {
		//发送短消息
		$reid=input::getVar($_REQUEST['reid']);
		if($reid){
			$dao=D("Pm");
			$info=$dao->where(array('id'=>$reid))->find();
			$this->assign('info',$info);
		}
		
		$page=array();
		$page['title']='My Message -  My Control Panel -  BeingfunChina';
		$page['keywords']='My Message';
		$page['description']='My Message';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:post_pm');
		$this->display("Cp:layout");
	}//end my_pm
	
		/**
	   *查看短消息
	   *@date 2010-9-29
	   *@time 下午05:59:42
	   */
	function view_pm() {
		//查看短消息
		$id=Input::getVar($_REQUEST['id']);
		if($id){
			$dao=D("Pm");
			$info=$dao->where(array('id'=>$id))->find();
			if($info['fromuid']==$this->user['uid']||$this->_is_admin()){
				$dao->where(array('id'=>$id))->setField('ifnew','0');
				$this->assign('info',$info);
			}else{
				$this->error("Insufficient privileges!");
			}
			$page=array();
			$page['title']='My Message -  My Control Panel -  BeingfunChina';
			$page['keywords']='My Message';
			$page['description']='My Message';
			$this->assign('page',$page);
			
			$this->assign('content','Cp:view_pm');
			$this->display("Cp:layout");
		}else{
			$this->error("Wrong parameter!");
		}
	}//end viewpm
	
	/**
	   *删除短信
	   *@date 2010-10-9
	   *@time 下午03:23:41
	   */
	function del_pm() {
		//删除短信
		$dao=D("Pm");
		$id=Input::getVar($_REQUEST['id']);
		$info=$dao->where(array('id'=>$id))->find();
		if($info['fromuid']==$this->user['uid']||$this->_is_admin()){
			$dao->where(array('id'=>$id))->delete();
			$this->success('Successful release.');
		}else{
			$this->error("Insufficient privileges!");
		}
	}//end del_pm
	
	/**
	 *分类信息发布的评论
	 *@date 2010-5-20
	 *@time 下午08:38:54
	 */
	function my_comments() {
		//分类信息发布的评论
		$this->is_act(1,2);
		$dao=D("Comment");
		$condition=array();
		$condition['uid']=$this->user['uid'];
		if ($_REQUEST['types']) {
			$condition['types']=$_REQUEST['types'];
		}else{
			$condition['types']=array(array('egt',4),array('elt',9));
		}
		import("ORG.Util.Page");
		$count=$dao->where($condition)->order("dateline DESC")->count();
		$page=new Page($count,25);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$page->config=array('header'=>'','prev'=>'<','next'=>'>','first'=>'«','last'=>'»','theme'=>' %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage_bot',$page->show_img());
		$limit=$page->firstRow.','.$page->listRows;
		//$data=$dao->where("((typeid={$typeid} AND cid={$this->pcid}) AND ismake=1) AND (showstart<{$now} AND showend>{$now}))")->order("pubdate DESC")->limit("$limit")->findAll();
		$data=$dao->where($condition)->order("dateline DESC")->limit("$limit")->findAll();
		$this->assign('list',$data);
		
		$page=array();
		$page['title']='My Comments -  My Control Panel -  BeingfunChina';
		$page['keywords']='My Comments';
		$page['description']='My Comments';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:my_classifieds_comments');
		$this->display("Cp:layout");
	}//end my_classifieds_comments
	
	/**
	 *删除留言
	 *@date 2010-5-31
	 *@time 下午04:34:14
	 */
	function comment_delete() {
		//删除留言
		$this->is_act(1,2);
		$xid=$_REQUEST['xid'];
		$dao=D("Comment");
		$info=$dao->where("xid=$xid")->find();
		if (empty($info) || $info['uid']!=$this->user['uid']){
			$this->error("Insufficient privileges!");
		}else{
			$dao->where("xid=$xid")->limit('1')->delete();
			if($_REQUEST['types']){
				redirect('/Cp/my_comments/types/'.$_REQUEST['types']);
			}else{
				redirect('/Cp/my_comments');
			}
		}
		
	}//end comment_delete
	
	/**
	 *添加相关的图片
	 *@date 2010-5-31
	 *@time 下午05:02:57
	 */
	function photo() {
		//添加相关的图片
		
		$pic=D("Pic");
		if($_REQUEST['info']){
			$condition=array();
			$info=explode('_',$_REQUEST['info']);
			$condition['channel']=$info['0'];
			$condition['id']=$info['1'];
		}else{
			$this->error('Wrong parameter');
		}
		$arc=D("Archives");
		$arc_info=$arc->where($condition)->field()->limit('1')->find();
		if(empty($arc_info)){
			//dump($arc->getLastSql());
			$this->error('Wrong parameter');
		}
		if($arc_info['uid']!=$this->user['uid']&&!in_array($this->user['username'],$this->admin)){
			$this->error("Insufficient privileges!");
		}
		
		$reurl=array();
		$reurl['4']="Cp/my_classifieds_post";
		$reurl['5']="Cp/my_classifieds_post";
		$reurl['6']="Cp/my_classifieds_post";
		$reurl['7']="Cp/my_classifieds_post";
		$reurl['8']="Cp/my_classifieds_post";
		$reurl['9']="Cp/my_classifieds_post";
		$reurl['10']="Cp/event_my_post";
		$reurl['11']="Cp/my_fair_post";
		$reurl['12']="Cp/my_art";
		$reurl['13']="Cp/group_my";
		$reurl['14']="Mcp/my_magazine";
		$this->assign('reurl',$reurl);
		
		if($_REQUEST['act']=='del'){
			$pid=Input::getVar($_GET['pid']);
			$pinfo=$pic->where("id=$pid")->find();
			$pdata=array('id'=>$pid,'is_show'=>0,'dtime'=>time());
			if($arc_info['uid']==$this->user['uid']||$pinfo['uid']==$this->user['uid']||in_array($this->user['username'],$this->admin)){
				$pic->save($pdata);
			}else{
				$this->error("Insufficient privileges!");
			}
		}elseif($_REQUEST['act']=='cover'){
			$pid=Input::getVar($_GET['pid']);
			$pinfo=$pic->where("id=$pid")->find();
			if(end(explode(',',$pinfo['thumb']))!='s_'){
				import("ORG.Util.Image");
                $thumbname	=$pinfo['filepath'].'s_'.$pinfo['filename'];
                $filename=$pinfo['filepath'].$pinfo['filename'];
                Image::thumb($filename,$thumbname,'',120,120,true);
			}
			$data=array('picurl'=>$pinfo['filepath'].'s_'.$pinfo['filename']);
			$arc->where($condition)->limit('1')->save($data);
			if($this->user['username']=='iicc'){
				dump($arc->getLastSql());
			}
		}
		
		$this->assign('arc_info',$arc_info);
		
		/*$album=D("Album");
		$album_info=$album->where("uid={$this->user['uid']}")->findAll();
		$this->assign('album',$album_info);*/
		
		
		$condition=array();
		$condition['xtype']=$info['0'];
		$condition['xid']=$info['1'];
		$condition['is_show']='1';
		$pic_info=$pic->where($condition)->findAll();
		//dump($pic_info);
		$this->assign('pic',$pic_info);
		
		$page=array();
		$page['title']='My Photo -  My Control Panel -  BeingfunChina';
		$page['keywords']='My Photo';
		$page['description']='My Photo';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:add_photo');
		$this->display("Cp:layout");
	}//end add_photo
	
	/**
	 *增加图片
	 *@date 2010-5-31
	 *@time 下午05:52:13
	 */
	function add_photo() {
		//增加图片
		if($_REQUEST['info']){
			$condition=array();
			$info=explode('_',$_REQUEST['info']);
			$condition['channel']=$info['0'];
			$condition['id']=$info['1'];
		}else{
			$this->error('Wrong parameter');
		}
		$pic=$this->_photo($condition['id']);
		//dump($pic);
		$list='';
		$list=count($_FILES['filename']['name']);
		$dao=D("Pic");
		$num=0;
		for($i=0;$i<$list;$i++){
			if($_FILES['filename']['name'][$i]){
				$data=array();
				$data['title']=empty($_REQUEST['title'][$i])?$_FILES['filename']['name'][$i]:$_REQUEST['title'][$i];
				$data['msg']=$_REQUEST['msg'][$i];
				$data['filepath']=$pic[$i]['savepath'];
				$data['filename']=$pic[$i]['savename'];
				$data['type']=$pic[$i]['type'];
				$data['size']=$pic[$i]['size'];
				$data['thumb']='m_,s_';
				$data['remote']=0;
				$data['xid']=$condition['id'];
				$data['xtype']=$condition['channel'];
				$data=$dao->create($data);
				$id=$dao->add($data);
				if($id){
					$num+=1;
				}
			}
		}
		$count=$dao->where("xid={$condition['id']} AND xtype={$condition['channel']}")->count();
		$arc=D("Archives");
		$arc->where($condition)->setField("albumnum",$count);
		redirect('/Cp/photo/info/'.$_REQUEST['info']);
		//dump($data);
	}//end add_photo
	
	
////////////////////classifieds start//////////////////////
	
	/**
	 *发送分类信息
	 *@date 2010-5-21
	 *@time 上午09:21:48
	 */
	function my_post_classifieds() {
		//发送分类信息
		$class_tree=$this->_get_tree(1);
		$this->assign("class_tree",$class_tree);
		$this->assign('citylist',$this->_get_city('city'));
		$page=array();
		$page['title']='Post Classifieds -  My Control Panel -  BeingfunChina';
		$page['keywords']='Post Classifieds';
		$page['description']='Post Classifieds';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:my_post_classifieds');
		$this->display("Cp:layout");
	}//end my_post_classifieds
	
	/**
	 *增加分类信息
	 *@date 2010-5-21
	 *@time 上午09:22:32
	 */
	function add_classifieds() {
		//增加分类信息
		if($_POST['cid']=='Select City'){
			$this->error("Please select City.");
		}
		$dao=D("Archives");
		/*if(!empty($_FILES["picurl"]["name"])) {
			$this->_upload();
		}*/
		$vo=$dao->create();
		if($vo){
			//$vo['picurl']='';
			if($vo['showstart']){
				$t=explode('/',$vo['showstart']);
				$vo['showstart']=mktime('0',0,0,$t['1'],$t['0'],$t['2']);
			}else {
				$vo['showstart']=time();
			}
			if($vo['showend']){
				$t=explode('/',$vo['showend']);
				$vo['showend']=mktime('0',0,0,$t['1'],$t['0'],$t['2']);
			}else{
				$vo['showend']=$vo['showstart']+(60*60*24*365);
			}
			$t=explode('_',$vo['typeid']);
			$vo['typeid']=$t['1'];
			$vo['channel']=$t['0'];
			if(empty($vo['typeid']) || empty($vo['channel'])){
				$this->error("Please select one option in 'Section'.");
			}
			if (Input::getVar($_POST['typeid2'])){
				$type2=array();
				foreach ($_POST['typeid2'] as $v){
					$t='';
					$t=explode('_',$v);
					if($vo['channel']!=$t['0']){
						$this->error("The content in the field of \"channel\" should be consistent.");
					}
					$type2[]=$t['1'];
				}
			}
			$vo['typeid2']=array2string($type2,',');
			$kw=str_word_count($vo['title'],1);
    			$keywords="";
    			foreach ($kw as $vkw){
    				$keywords.=$vkw.',';
    			}
    		$vo['keywords']=empty($_POST['keywords'])?trim($keywords,','):$_POST['keywords'];
			$vo['description']=msubstr(strip_tags($_POST['content']),0,200);
			
			$eid='';
    		$xid=Input::getVar($_POST['id']);
    		if($xid){
    			$aid=$xid;
    			$eid=$dao->where("id=$aid")->save($vo);
    		}else{
				$aid=$dao->add($vo);
    		}
    		$actlog=$dao->getLastSql();
			if ($aid) {
				$data=array();
				switch (true){
					case $vo['channel']=='4':
						if($_POST['joblocated']['0']!='0' && !empty($_POST['joblocated']['2'])){
							$data['joblocated']=$_POST['joblocated']['2'].','.$_POST['joblocated']['1'].','.$_POST['joblocated']['0'];
							$data['joblocated']=$data['joblocated']==',0,0'?'':trim($data['joblocated'],',');
						}
						$data['experience']=$_POST['experience'];
						$data['salary']=$_POST['salary'];
						$table="iic_addon_jobs";
					break;
					case $vo['channel']=='5':
						$data['published']=$_POST['published'];
						$data['size']=$_POST['size'];
						$data['price']=$_POST['price'];
						$data['rooms']=$_POST['rooms'];
						$table="iic_addon_realestate";
					break;
					case $vo['channel']=='6':
						$data['quantity']=$_POST['quantity'];
						$data['price']=$_POST['price'];
						$table="iic_addon_commerce";
					break;
					case $vo['channel']=='7':
						$table="iic_addon_agents";
					break;
					case $vo['channel']=='8':
						$table="iic_addon_personals";
					break;
					case $vo['channel']=='9':
						$table="iic_addon_services";
					break;
					default:
						$dao->Table("iic_archives")->where("id=$aid")->limit('1')->delete();
						$this->error("Wrong parameter");
					break;
				}
				
				if(empty($_POST['content'])){
					$this->error("Description is null!");
				}
				$data['content']=Input::getVar($_POST['content']);
				$data['content']=nl2br($data['content']);

				if(isset($xid)){
					$id=$dao->Table($table)->where("aid=$aid")->save($data);
				}else{
					$data['aid']=$aid;
					$id=$dao->Table($table)->add($data);
				}
				//dump($dao->getLastSql());
				if($id || $id=='0'){
					$actlog.='<br>'.$dao->getLastSql();
					if (empty($xid)) {
						$this->_act_log($aid,$vo['channel'],'add',$actlog);
					}else{
						$this->_act_log($aid,$vo['channel'],'edit',$actlog);
					}
					$txt='<h4>Successful release. </h4><br><a href="/'.get_type($vo['channel']).'/show/aid/'.$aid.'">View classified</a>   /   ';
					$txt.='<a href="/Cp/photo/info/'.$vo['channel'].'_'.$aid.'">Add pictures</a><br>';
					$txt.='<a href="/Cp/my_post_classifieds">Post classified</a>   /   ';
					$txt.='<a href="/Cp/my_classifieds_post">Back to the list </a><br>';
					$txt.='You will be directed to the picture uploading page in three seconds! ';
					$this->assign('jumpUrl','/Cp/photo/info/'.$vo['channel'].'_'.$aid);
					$this->success($txt);
					//echo '发布成功!';
				}else{
					if(empty($xid)){
						$dao->Table("iic_archives")->where("id=$aid")->limit('1')->delete();
					}
					$this->error("Failed to write in subsidiary table.");
				}
			}else{
				$this->error('Failed to update the main profile table. ');
			}
		}else{
			$this->error($dao->getError());
		}
	}//end add_classifieds
	
	/**
	 *修改分类信息
	 *@date 2010-6-21
	 *@time 下午03:18:34
	 */
	function my_edit_classifieds() {
		//修改分类信息
		$info=Input::getVar($_REQUEST['info']);
		$info=explode('_',$info);
		$dao=D("Archives");
		$condition=array();
		$condition['channel']=$info['0'];
		$condition['id']=$info['1'];
		$info=$dao->where($condition)->find();
		if (empty($info)) {
			$this->error("Wrong parameter");
		}
		$city=$this->_get_city('localion');
		switch (true) {
			case $info['channel']==4://Jobs
				$addon=$dao->relationGet("jobs");
			break;
			
			case $info['channel']==5://realestate
				$addon=$dao->relationGet("realestate");
			break;
			
			case $info['channel']==6://commerce
				$addon=$dao->relationGet("realestate");
			break;
			
			case $info['channel']==7://agents
				$addon=$dao->relationGet("agents");
			break;
			
			case $info['channel']==8://personals
				$addon=$dao->relationGet("personals");
			break;
			
			case $info['channel']==9://services
				$addon=$dao->relationGet("services");
			break;
		}
		
		$this->assign('data',$info);
		$this->assign('addon',$addon);
		$class_tree=$this->_get_tree(1);
		$this->assign("class_tree",$class_tree);
		$this->assign('citylist',$this->_get_city('city'));
		$page=array();
		$page['title']='Post Classifieds -  My Control Panel -  BeingfunChina';
		$page['keywords']='Post Classifieds';
		$page['description']='Post Classifieds';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:my_post_classifieds');
		$this->display("Cp:layout");
	}//end my_edit_classifieds
	
	/**
	   *删除信息
	   *@date 2010-7-21
	   *@time 下午05:29:19
	   */
	function del_arc() {
		//删除信息
		$dao=D("Archives");
		$info=Input::getVar($_REQUEST['info']);
		$info=explode('_',$info);
		$dao=D("Archives");
		$condition=array();
		$condition['channel']=$info['0'];
		$condition['id']=$info['1'];
		$info=$dao->where($condition)->find();
		if (empty($info)) {
			$this->error("Wrong parameter");
		}
		if($info['uid']==$this->user['uid']||$this->is_admin()){
			$data=array();
			if($_GET['act']=="show"){
				$data['ismake']='1';
			}else{
				$data['ismake']='0';
			}
			$dao->where($condition)->save($data);
		}else{
			$this->error("Wrong parameter");
		}
		/*if($this->is_admin()){
			dump($dao->getLastSql());
		}*/
		$to=mydecode($_GET['to']);
		$to=empty($_GET['p'])?$to:$to.'?p='.$_GET['p'];
		redirect($to);
	}//end del_arc
	
////////////////////classifieds end//////////////////////

//////////////////////cityguide start////////////////////////
	/**
	 *我发布的城市指南
	 *@date 2010-5-25
	 *@time 下午03:29:59
	 */
	function my_cityguide_post() {
		//我发布的城市指南
		$dao=D("Archives");
		$condition=array();
		$condition['channel']=2;
		$cityguide_type=$this->_get_cityguide_type();
    	$this->assign('cityguide_type',$cityguide_type);
    	$typeid=$_REQUEST['id'];
    	if(!empty($typeid)){
    		$type=D("Arctype");
	    	$data=$type->where("id=$typeid")->field('id,typename,reid,topid,ispart')->find();
	    	$typearr=array();
	    	if($data['ispart']!=0){
	    		$typearr=$type->where("reid=$typeid")->field('id,typename,reid,topid,ispart')->findAll();
	    		$in='';
	    		foreach ($typearr as $v){
	    			$in.=$v['id'].',';
	    		}
	    		$in=trim($in,',');
	    		$condition['typeid']=array('IN',$in);
	    	}else{
	    		$condition['typeid']=$typeid;
	    	}
    	}
    	if(!$this->is_admin()){
    		$condition['uid']=$this->user['uid'];
    	}
    	$count=$dao->where($condition)->count();
		$page=new Page($count,25);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$limit=$page->firstRow.','.$page->listRows;
    	$cityguide=array();
    	$cityguide=$dao->where($condition)->order("pubdate DESC")->limit("$limit")->findAll();
    	//dump($dao->getLastSql());
		$this->assign('cityguide',$cityguide);
		
		$page=array();
		$page['title']='My Post CityGuide -  My Control Panel -  BeingfunChina';
		$page['keywords']='My Post CityGuide';
		$page['description']='My Post CityGuide';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:my_cityguide_post');
		$this->display("Cp:layout");
	}//end my_cityguide_post
	
	/**
	 *发送城市指南
	 *@date 2010-5-25
	 *@time 下午03:13:17
	 */
	function my_post_cityguide() {
		//发送城市指南
                if($_SESSION["info"]['extcredits8']=='1'||$this->is_admin()){
                    if($_SESSION["info"]['extcredits7']!='0'){
                        $this->assign('cat',$_SESSION["info"]['extcredits7']);
                    }
                    $class_tree=$this->_get_tree(1000);
                    $this->assign('class_tree',$class_tree);
                    $this->assign('citylist',$this->_get_city('city'));
                    $page=array();
                    $page['title']='Post CityGuide -  My Control Panel -  BeingfunChina';
                    $page['keywords']='Post CityGuide';
                    $page['description']='Post CityGuide';
                    $this->assign('page',$page);
                    $this->assign('content','Cp:my_post_cityguide');
                    $this->display("Cp:layout");
                }else{
                    redirect('/Cp/index');
                }
	}//end my_cityguide_post
	
	/**
	 *修改城市指南
	 *@date 2010-5-25
	 *@time 下午03:13:17
	 */
	function my_edit_cityguide() {
		//修改城市指南
		$info=Input::getVar($_REQUEST['info']);
		$info=explode('_',$info);
		if($info['0']!='2'){
			$this->error("Wrong parameter");
		}
		$dao=D("Archives");
		$condition=array();
		//$condition['channel']=$info['0'];
		$condition['id']=$info['1'];
		$info=$dao->where($condition)->find();
		//dump($dao->getLastSql());
		if (empty($info)) {
			$this->error("Wrong parameter");
		}
		$addon=$dao->relationGet("article");
		$this->assign('data',$info);
		$this->assign('addon',$addon);
		$this->assign('class_tree',$class_tree=$this->_get_tree(1000));
		$this->assign('citylist',$this->_get_city('city'));
		$page=array();
		$page['title']='Post CityGuide -  My Control Panel -  BeingfunChina';
		$page['keywords']='Post CityGuide';
		$page['description']='Post CityGuide';
		$this->assign('page',$page);
		$this->assign('content','Cp:my_post_cityguide');
		$this->display("Cp:layout");
	}//end my_edit_cityguide
	
	/**
	 *增加城市指南
	 *@date 2010-5-25
	 *@time 下午03:14:18
	 */
	function add_cityguide() {
		//增加城市指南
		$dao=D("Archives");
	/*	if(!empty($_FILES["picurl"]["name"])) {
			$this->_upload();
		}*/
		$vo=$dao->create();
		if($vo){
			$vo['description']=String::msubstr($vo['my_content'],0,200);
			$vo['my_content']=nl2br($vo['my_content']);
			if($vo['showstart']){
				$t=explode('/',$vo['showstart']);
				$vo['showstart']=mktime('0',0,0,$t['1'],$t['0'],$t['2']);
			}else {
				$vo['showstart']=time();
			}
			if($vo['showend']){
				$t=explode('/',$vo['showend']);
				$vo['showend']=mktime('0',0,0,$t['1'],$t['0'],$t['2']);
			}else{
				$vo['showend']=$vo['showstart']+(60*60*24*365);
			}
			$t=explode('_',$vo['typeid']);
			$vo['typeid']=$t['1'];
			$vo['channel']=$t['0'];
			if(empty($vo['typeid']) || empty($vo['channel'])){
				$this->error("Please select one option in 'Section'.");
			}
			if (Input::getVar($_POST['typeid2'])){
				$type2=array();
				foreach ($_POST['typeid2'] as $v){
					$t='';
					$t=explode('_',$v);
					if($vo['channel']!=$t['0']){
						$this->error("The content in the field of “channel” should be consistent.");
					}
					$type2[]=$t['1'];
				}
			}
			$vo['typeid2']=array2string($type2,',');
			unset($vo['maps']);
			$local='';
			$city=$this->_get_city('localion');
			if($_POST['zone_id'] && $_POST['city_id']){
				$local=$city[$info['city_id']]['_zone'][$info['zone_id']]['name'].','.$city[$info['city_id']]['cename'];
			}elseif(empty($info['zone_id']) && $info['city_id']) {
				$local=$city[$info['city_id']]['cename'];
			}
			$local=trim($_POST['position'].','.$local,',');
			if(!empty($_POST['maps']['0']) || !empty($_POST['maps']['2'])){
				$vo['maps']=array2string($_POST['maps'],',');
			}else{
				$vo['maps']=$local;
			}
			$kw=str_word_count($vo['title'],1);
    			$keywords="";
    			foreach ($kw as $vkw){
    				$keywords.=$vkw.',';
    			}
    		$vo['keywords']=empty($_POST['keywords'])?trim($keywords,','):$_POST['keywords'];
			$eid='';
    		$xid=Input::getVar($_POST['id']);
    		
    		if($xid){
    			$aid=$xid;
    			$eid=$dao->where("id=$aid")->save($vo);
    		}else{
				$aid=$dao->add($vo);
    		}
    		$actlog=$dao->getLastSql();
			if ($aid) {
				$data=array();
				$data['content']=Input::getVar($_POST['content']);
				//$data['content']=nl2br($data['content']);
				if(isset($xid)){
					$id=$dao->Table("iic_addon_article")->where("aid=$aid")->save($data);
				}else{
					$data['aid']=$aid;
					$id=$dao->Table("iic_addon_article")->add($data);
				}
				if($id || $id=='0'){
					$actlog.='<br>'.$dao->getLastSql();
					if (empty($xid)) {
						$this->_act_log($aid,$vo['channel'],'add',$actlog);
					}else{
						$this->_act_log($aid,$vo['channel'],'edit',$actlog);
					}
					
					$txt='<h4>Successful release. </h4><br><a href="/CityGuide/show/aid/'.$aid.'">View city guide</a>   /   ';
					$txt.='<a href="/Cp/photo/info/'.$vo['channel'].'_'.$aid.'">Add pictures</a><br>';
					$txt.='<a href="/Cp/my_post_cityguide">Post city guide </a>   /   ';
					$txt.='<a href="/Cp/my_cityguide_post">Back to the list </a><br>';
					$txt.='You will be directed to the picture uploading page in three seconds! ';
					$this->assign('jumpUrl','/Cp/photo/info/'.$vo['channel'].'_'.$aid);
					$this->success($txt);

					//echo '发布成功!';
				}else{
					if(empty($xid)){
						$dao->Table("iic_archives")->where("id=$aid")->limit('1')->delete();
					}
					$this->error("Failed to write in subsidiary table.");
				}
			}else{
				$this->error('Failed to update the main profile table. ');
			}
			//dump($vo);
		}else{
			$this->error($dao->getError());
		}
	}//end add_cityguide
	
//////////////////////cityguide end////////////////////////
	
	
	/**
	 *我的收藏夹
	 *@date 2010-5-29
	 *@time 下午04:27:12
	 */
	function my_stuff() {
		//我的收藏夹
		$page=array();
		$page['title']='My Favorite -  My Control Panel -  BeingfunChina';
		$page['keywords']='My Favorite';
		$page['description']='My Favorite';
		$this->assign('page',$page);
		$this->assign('content','Cp:my_stuff');
		$this->display("Cp:layout");
	}//end my_stuff
	
	
////////////////////biz fair start/////////////////////
	
	/**
	 *我发布的展会管理
	 *@date 2010-6-17
	 *@time 下午05:33:39
	 */
	function my_fair_post() {
		//我发布的展会管理
		$lang=Input::getVar($_GET['lang']);
		$dao=D("Archives");
		$condition=array();
		$condition['channel']="11";
		if(!$this->is_admin()){
    		$condition['uid']=$this->user['uid'];
		}
		if($lang){
			$condition['industry']=$lang;
		}
    	$count=$dao->where($condition)->count();
		$page=new Page($count,20);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$limit=$page->firstRow.','.$page->listRows;
    	$fair=array();
    	$fair=$dao->where($condition)->order("pubdate DESC")->limit("$limit")->findAll();
		$this->assign('fair',$fair);
		
		$page=array();
		$page['title']='My Fair -  My Control Panel -  BeingfunChina';
		$page['keywords']='My Fair';
		$page['description']='My Fair';
		$this->assign('page',$page);
		$this->assign('content','Cp:my_fair_post');
		$this->display("Cp:layout");
	}//end my_fair_post
	
	/**
	 *发布一个展会信息
	 *@date 2010-5-29
	 *@time 下午05:33:40
	 */
	function my_post_fair() {
		//发布一个展会信息
		$lang=Input::getVar($_GET['lang']);
		$writer=Input::getVar($_GET['writer']);
		$l=array('CN','EN');
		
		if(empty($writer)){
			$writer=time().'_'.$lang;
		}else{
			$dao=D("Archives");
			$condition=array('writer'=>$writer,'channel'=>'11');
			$data=$dao->where($condition)->find();
			if($data){
				redirect('/Cp/my_edit_fair/info/11_'.$data['id'].'/writer/'.$writer);
			}else{
				$lang=end(explode('_',$writer));
			}
		}
		$class_tree=$this->_get_tree(1232);
		$this->assign("class_tree",$class_tree);
		$this->assign('citylist',$this->_get_city('fair'));
		$this->assign('ltd',$this->_get_ltd());
		$page=array();
		$page['title']='Post Fair -  My Control Panel -  BeingfunChina';
		$page['keywords']='Post Fair';
		$page['description']='Post Fair';
		$this->assign('page',$page);
		
		
		if(in_array($lang,$l,true)){
			$tpl='Cp:my_post_fair_'.strtolower($lang);
		}else{
			$tpl='Cp:my_post_fair_sel';
		}
		$this->assign('writer',$writer);
		$this->assign('content',$tpl);
		$this->display("Cp:layout");
	}//end my_post_fair
	
	/**
	 *修改展会信息
	 *@date 2010-6-19
	 *@time 下午03:42:19
	 */
	function my_edit_fair($info) {
		//修改展会信息
		$info=Input::getVar($_REQUEST['info']);
		$info=explode('_',$info);
		$dao=D("Archives");
		$condition=array();
		$condition['channel']=$info['0'];
		$condition['id']=$info['1'];
		$data=$dao->where($condition)->find();
		if ($data['uid']==$this->user['uid']||$this->is_admin()) {
			$data['content']=$dao->relationGet("fair");
			if($_GET['writer']&&empty($data['writer'])){
				$data['industry']=end(explode('_',$_GET['writer']));
				$data['writer']=$_GET['writer'];
			}else{
				$data['industry']=end(explode('_',$data['writer']));
			}
			
			$this->assign('data',$data);
		}else{
			$this->error("Insufficient privilege.");
		}
		$lang=$data['industry'];
		$l=array('CN','EN');
		$class_tree=$this->_get_tree(1232);
		$this->assign("class_tree",$class_tree);
		$this->assign('citylist',$this->_get_city('fair'));
		$this->assign('ltd',$this->_get_ltd());
		$page=array();
		$page['title']='Post Fair -  My Control Panel -  BeingfunChina';
		$page['keywords']='Post Fair';
		$page['description']='Post Fair';
		$this->assign('page',$page);
		
		if(in_array($lang,$l,true)){
			$tpl='Cp:my_post_fair_'.strtolower($lang);
		}else{
			$tpl='Cp:my_post_fair_sel';
		}
		$this->assign('content',$tpl);
		$this->display("Cp:layout");
	}//end my_edit_fair
	
	/**
	 *增加展会信息
	 *@date 2010-6-1
	 *@time 上午10:13:56
	 */
	function add_fair() {
		//增加展会信息
		$dao=D("Archives");
		$_POST['typeid2']=array2string($_POST['typeid2'],',');
		$vo=$dao->create();
		$vo['industry']=$_POST['writer'];
		$vo['writer']=time().'_'.$vo['writer'];
		if($vo){
			$area=$this->_get_city('fair');
			if($vo['showstart']){
				$t=explode('/',$vo['showstart']);
				$vo['showstart']=mktime('0',0,0,$t['1'],$t['0'],$t['2']);
			}else {
				$vo['showstart']=time();
			}
			if($vo['showend']){
				$t=explode('/',$vo['showend']);
				$vo['showend']=mktime('0',0,0,$t['1'],$t['0'],$t['2']);
			}else{
				$vo['showend']=$vo['showstart']+(60*60*24*356);
			}
			
			$t=explode('_',$vo['typeid']);
			$vo['typeid']=$t['1'];
			$vo['channel']=$t['0'];
			//$vo['maps']=$_POST['position'].','.$area[$_POST['city_id']]['_zone'][$_POST['zone_id']]['name'].','.$area[$_POST['city_id']]['ctitle'];
			$kw=str_word_count($vo['title'],1);
    			$keywords="";
    			foreach ($kw as $vkw){
    				$keywords.=$vkw.',';
    			}
    		$vo['keywords']=empty($_POST['keywords'])?trim($keywords,','):$_POST['keywords'];
			$eid='';
    		$xid=Input::getVar($_POST['id']);
    		if($xid){
    			$aid=$xid;
    			$eid=$dao->where("id=$aid")->save($vo);
    		}else{
				$aid=$dao->add($vo);
    		}
    		//dump($dao->getLastSql());
    		$actlog.=$dao->getLastSql();
			if ($aid) {
				$data=array();
				$data['description']=nl2br(Input::getVar($_POST['description']));
				$data['product']=Input::getVar($_POST['product']);
				$data['website']=Input::getVar($_POST['website']);
				if($xid){
					$id=$dao->Table("iic_fair")->where("aid=$aid")->save($data);
				}else{
					$data['aid']=$aid;
					$id=$dao->Table("iic_fair")->add($data);
				}
				if($id){
					$actlog.='<br>'.$dao->getLastSql();
					if (empty($xid)) {
						$this->_act_log($aid,$vo['channel'],'add',$actlog);
					}else{
						$this->_act_log($aid,$vo['channel'],'edit',$actlog);
					}
					
					$txt='<h4>Successful release. </h4><br><a href="/Biz/show/aid/'.$aid.'">View fair</a>   /   ';
					$txt.='<a href="/Cp/photo/info/'.$vo['channel'].'_'.$aid.'">Add pictures</a><br>';
					$txt.='<a href="/Cp/my_fair_post">Post fair</a>   /   ';
					$txt.='<a href="/Cp/my_post_fair">Back to the list </a><br>';
					$txt.='You will be directed to the picture uploading page in three seconds! ';
					$this->assign('jumpUrl','/Cp/photo/info/'.$vo['channel'].'_'.$aid);
					$this->success($txt);
					
				}else{
					if(empty($xid)){
						$dao->Table("iic_archives")->where("id=$aid")->limit('1')->delete();
					}
					//dump($dao->getLastSql());
					$this->error("Failed to write in subsidiary table. ");
				}
			}else{
				$this->error('Failed to update the main profile table.');
			}
			//dump($vo);
		}else{
			$this->error($dao->getError());
		}
	}//end add_fair
	
	/**
	   *最新的展会
	   *@date 2010-8-14
	   *@time 下午03:41:21
	   */
	function fair_latest() {
		//最新的展会
		$time=time()+(60*60*24*7);
		$condition=array();
		$condition['channel']=11;
		$condition['ismake']=1;
		$condition['showend']=array('lt',$time);
		$dao=D("Archives");
		$list=$dao->where($condition)->order("showend DESC")->limit("0,25")->findAll();
		$this->assign('fair',$list);
		$page=array();
		$page['title']='Latest Fairs -  My Control Panel -  BeingfunChina';
		$page['keywords']='Latest Fairs';
		$page['description']='Latest Fair';
		$this->assign('page',$page);
		$this->assign('content','Cp:fair_latest');
		$this->display("Cp:layout");
	}//end fair_latest
	
	/**
	   *我关注的展会
	   *@date 2010-8-14
	   *@time 下午05:01:39
	   */
	function fair_focus($types,$tid) {
		//我关注的展会
		$act=empty($_REQUEST['act'])?'0':$_REQUEST['act'];
		$dao=D("Thought");
		$jump=empty($_GET['to'])?'/Cp/fair_focus':mydecode($_REQUEST['to']);
		$this->assign('jumpUrl',$jump);
		if($act=='add'){
			$condition=array();
			$condition['types']=empty($types)?$_GET['types']:$types;
			$condition['tid']=empty($tid)?$_GET['aid']:$tid;
			$condition['uid']=$this->user['uid'];
			$condition['username']=$this->user['username'];
			$condition['hot']=1;
			$info=$dao->where($condition)->find();
			$data=array();
			if($info['is_show']=='0'){
				$condition['id']=$info['id'];
				$data['is_show']=1;
				$data['hot']=1;
				$data=$dao->create($data);
				$dao->where($condition)->save($data);
			}elseif($info['is_show']=='1'){
				$this->error("Your Interested is failed to release.");
			}else{
				$data=$dao->create($condition);
				$data['is_show']=1;
				$id=$dao->add($data);
				if(empty($id)){
					$this->error("Your Interested is failed to release.");
				}
			}
			$this->success("Your Interested has been to release.");
		}elseif( $act=='remove'){				
			$condition=array();
			$condition['types']=empty($types)?$_GET['types']:$types;
			$condition['tid']=empty($tid)?$_GET['aid']:$tid;
			$condition['uid']=$this->user['uid'];
			$condition['username']=$this->user['username'];
			$condition['hot']=1;
			$condition['is_show']=1;
			$info=$dao->where($condition)->find();
			if($info){
				$condition['id']=$info['id'];
				$data=array("is_show"=>0);
				$dao->where($condition)->save($data);
			}
			$this->success("You’ve sent successfully.");
		}elseif($act==0){
			$condition=array();
			$condition['types']=11;
			$condition['uid']=$this->user['uid'];
			$condition['hot']=1;
			$condition['is_show']=1;
			$count=$dao->where($condition)->count();
			$page=new Page($count,25);
			$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
			$this->assign('showpage',$page->show());
			$limit=$page->firstRow.','.$page->listRows;
	    	$list=array();
	    	$list=$dao->where($condition)->order("mtime DESC")->limit("$limit")->findAll();
			$this->assign('fair',$list);
			
			//dump($dao->getLastSql());
			$page=array();
			$page['title']='My focus -  My Control Panel -  BeingfunChina';
			$page['keywords']='My focus';
			$page['description']='My focus';
			$this->assign('page',$page);
			
			$this->assign('content','Cp:fair_focus');
			$this->display("Cp:layout");
		}
	}//end fair_focus
	
	/**
	   *修改展会属性
	   *@date 2010-9-20
	   *@time 下午01:59:29
	   */
	function fair_attr() {
		//修改展会属性
		$info=Input::getVar($_GET['info']);
		$info=explode('_',$info);
		$fld=Input::getVar($_GET['fld']);
		$val=Input::getVar($_GET['val']);
		if(empty($info['1'])){
			$this->error('Wrong parameter!');
		}
		if($this->is_admin()){
			$dao=D("Archives");
			$condition=array();
			$condition['id']=$info['1'];
			$condition['channel']=$info['0'];
			$infot=$dao->where($condition)->find();
			$arr=explode(',',$info['flag']);
		}else{
			$this->error('Wrong parameter!');
		}
		
	}//end fair_attr
	
////////////////////biz fair end/////////////////////
	
///////////////////////////////////////文章 start///////////////////////////
	/**
	 *发布新闻
	 *@date 2010-6-3
	 *@time 下午02:48:59
	 */
	function post_art() {
		//发布新闻
		$this->assign('flag',$this->_get_flag());
		$class_tree=$this->_get_tree(2000);
		$this->assign("class_tree",$class_tree);
		$this->assign('citylist',$this->_get_city('city'));
		$page=array();
		$page['title']='Post Articles -  My Control Panel -  BeingfunChina';
		$page['keywords']='Post Articles';
		$page['description']='Post Articles';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:post_art');
		$this->display("Cp:layout");
	}//end post_art
	
	/**
	   *删除新闻
	   *@date 2010-8-2
	   *@time 下午02:36:03
	   */
	function del_art() {
		//删除新闻
		$info=Input::getVar($_GET['info']);
		$info=explode('_',$info);
		if(empty($info['1'])){
			$this->error('Wrong parameter!');
		}
		$dao=D("Archives");
		$condition=array();
		$condition['id']=$info['1'];
		$condition['channel']=$info['0'];
		
		$infot=$dao->where($condition)->find();
		if($infot['uid']==$this->user['uid']){
			$data=array('ismake'=>'0');
			$dao->where($condition)->save($data);
			$this->success('Successful release.');
		}else{
			$this->error('Wrong parameter!');
		}
	}//end del_art
	
	/**
	 *增加新闻
	 *@date 2010-6-3
	 *@time 下午04:06:38
	 */
	function add_art() {
		//增加新闻
		$dao=D("Archives");
		if(!empty($_FILES["picurl"]["name"])) {
			$this->_upload('',132,105);
		}
		if (!empty($_POST['flag'])) {
			$flag='';
			foreach ($_POST['flag'] as $v){
				$flag.=$v.',';
			}
			$_POST['flag']=trim($flag,',');
		}else{
			$_POST['flag']='';
		}
		$_POST['typeid2']=array2string($_POST['typeid2'],',');
		$_POST['my_content']=Input::getVar($_POST['my_content']);
		$vo=$dao->create();
		if($vo){
			if (empty($_POST['my_content'])) {
				$this->error("You must fill in the field of 'Summary'");
			}
			$vo['description']=String::msubstr($vo['my_content'],0,200);
			$vo['my_content']=nl2br($vo['my_content']);
			$vo['my_content']=trim($vo['my_content'],'<br>');
			if($vo['showstart']){
				$t=explode('/',$vo['showstart']);
				$vo['showstart']=mktime('0',0,0,$t['1'],$t['0'],$t['2']);
			}else {
				$vo['showstart']=time();
			}
			if($vo['showend']){
				$t=explode('/',$vo['showend']);
				$vo['showend']=mktime('0',0,0,$t['1'],$t['0'],$t['2']);
			}else{
				$vo['showend']=1280000000;
			}
			$t=explode('_',$vo['typeid']);
			$vo['typeid']=$t['1'];
			$vo['channel']=$t['0'];
			$kw=str_word_count($vo['description'],1);
    			$keywords="";
    			foreach ($kw as $vkw){
    				$keywords.=$vkw.',';
    			}
    		$vo['keywords']=empty($_POST['keywords'])?trim($keywords,','):$_POST['keywords'];
    		$eid='';
    		$xid=Input::getVar($_REQUEST['id']);
    		if($xid){
    			$aid=$xid;
    			$eid=$xid;
    			$dao->where("id=$aid")->save($vo);
    		}else{
				$aid=$dao->add($vo);
    		}
    		if(in_array($_SESSION['username'],$this->admin)){
				//dump($dao->getLastSql());
			}
    		$actlog=$dao->getLastSql();
			if ($aid) {
				$data=array();
				$_POST['content']=Input::getVar($_POST['content']);
				if (empty($_POST['content'])) {
					$this->error("You must fill in the field of 'Content'");
				}
				//$data['content']=nl2br($_REQUEST['content']);
				$data['content']=$_POST['content'];
				if(!empty($xid)){
					$id=$dao->Table("iic_addon_arc")->where("aid=$aid")->save($data);
				}else{
					$data['aid']=$aid;
					$id=$dao->Table("iic_addon_arc")->add($data);
				}
				//dump($dao->getLastSql());
				if($id || $id=='0'){
					$actlog.='<br>'.$dao->getLastSql();
					if (empty($xid)) {
						$this->_act_log($aid,$vo['channel'],'add',$actlog);
					}else{
						$this->_act_log($aid,$vo['channel'],'edit',$actlog);
					}
					//echo "发布成功!";
					$txt='<h4>Successful release. </h4><br><a href="/Art/show/aid/'.$aid.'">View articles </a>   /   ';
					$txt.='<a href="/Cp/photo/info/'.$vo['channel'].'_'.$aid.'">Add pictures</a><br>';
					$txt.='<a href="/Cp/post_art">Post articles</a>   /   ';
					$txt.='<a href="/Cp/my_art">Back to the list </a><br>';
					$txt.='You will be directed to the picture uploading page in three seconds! ';
					$this->assign('jumpUrl','/Cp/photo/info/'.$vo['channel'].'_'.$aid);
					$this->success($txt);
				}else{
					if(empty($xid)){
						$dao->Table("iic_archives")->where("id=$aid")->limit('1')->delete();
					}
					$this->error("Failed to write in subsidiary table. ");
				}
			}else{
				if($this->user['username']=='iicc'){
					dump($dao->getLastSql());
				}
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
	function my_art() {
		//发布新闻
		$dao=D("Archives");
		$condition=array();
		$condition['channel']='12';
		$condition['ismake']='1';
		if($this->user['username']!='iicc'){
    	$condition['uid']=$this->user['uid'];
		}
    	$count=$dao->where($condition)->count();
		$page=new Page($count,25);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$limit=$page->firstRow.','.$page->listRows;
    	$classifieds=array();
    	$classifieds=$dao->where($condition)->order("pubdate DESC")->limit("$limit")->findAll();
		$this->assign('classifieds',$classifieds);
		//dump($dao->getLastSql());
		$page=array();
		$page['title']='My Articles -  My Control Panel -  BeingfunChina';
		$page['keywords']='My Articles';
		$page['description']='My Articles';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:my_art');
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
		if ($data['uid']==$this->user['uid']||in_array($this->user['username'],$this->admin)) {
			$data['content']=$dao->relationGet("arc");
			//dump($dao->getLastSql());
			$this->assign('data',$data);
			//dump($data);
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
	
///////////////////////////////////////文章 start///////////////////////////

////////////////////////////////群组——start///////////////////////////////////
	/**
	 *创建群组
	 *@date 2010-6-7
	 *@time 下午05:08:07
	 */
	function group_create() {
		//创建群组
		$this->assign('cat',$this->_get_mtag());
		
		$page=array();
		$page['title']='Create Group -  My Control Panel -  BeingfunChina';
		$page['keywords']='Create Group';
		$page['description']='Create Group';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:group_create');
		$this->display("Cp:layout");
	}//end group_create
	
	/**
	 *增加群组
	 *@date 2010-6-7
	 *@time 下午05:57:16
	 */
	function add_group() {
		//增加群组
		$dao=D("Group");
		if(!empty($_FILES["pic"]["name"])) {
			$this->_group_up(132,105);
		}
		$vo=$dao->create();
		$vo['announcement']=nl2br($vo['announcement']);
		if($vo){
			$gid=$dao->add($vo);
			if ($gid){
				$this->_add_tagspace($gid,1);
				$this->assign('jumpUrl',__URL__.'/group_my');
					$actlog=$dao->getLastSql();
					if (empty($xid)) {
						$this->_act_log($gid,'group','add',$actlog);
					}else{
						$this->_act_log($gid,'group','edit',$actlog);
					}
				$this->success("Create Group Success!");
			}else{
				$this->error($dao->getDbError());
			}
		}else {
			$this->error($dao->getDbError());
		}
	}//end add_group
	
	/**
	 *我推荐到群组的资源
	 *@date 2010-6-7
	 *@time 下午05:08:45
	 */
	function group_collection() {
		//我推荐到群组的资源
		
	}//end group_collection
	
	/**
	 *我参与过的话题
	 *@date 2010-6-7
	 *@time 下午05:09:23
	 */
	function group_thread_join() {
		//我参与过的话题
		
	}//end group_tread_join
	
	/**
	 *我发布的话题
	 *@date 2010-6-7
	 *@time 下午05:09:57
	 */
	function group_thread() {
		//我发布的话题
		$dao=D("Post");
		$condition=array('uid'=>$this->user['uid'],'l'=>'1');
		$count=$dao->where($condition)->count();
		$page=new Page($count,10);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$limit=$page->firstRow.','.$page->listRows;
    	$list=array();
    	$list=$dao->where($condition)->order("ctime DESC")->limit("$limit")->findAll();
    	$this->assign('list',$list);
    	
    	$page=array();
		$page['title']='My Group -  My Control Panel -  BeingfunChina';
		$page['keywords']='My Group';
		$page['description']='My Group';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:group_thread');
		$this->display("Cp:layout");
	}//end group_tread
	
	/**
	 *我的群组
	 *@date 2010-6-7
	 *@time 下午05:10:18
	 */
	function group_my() {
		//我的群组
		$dao=D("Tagspace");
		$condition=array();
		$condition['uid']=$this->user['uid'];
    	$count=$dao->where($condition)->count();
		$page=new Page($count,10);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$limit=$page->firstRow.','.$page->listRows;
    	$group=array();
    	$group=$dao->where($condition)->order("ctime DESC")->limit("$limit")->findAll();
		$this->assign('group',$group);
		
		$page=array();
		$page['title']='My Group -  My Control Panel -  BeingfunChina';
		$page['keywords']='My Group';
		$page['description']='My Group';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:group_my');
		$this->display("Cp:layout");
	}//end group_my
	
	/**
	 *退出群组
	 *@date 2010-6-10
	 *@time 下午04:51:56
	 */
	function out_group() {
		//退出群组
		$gid=$_REQUEST['gid'];
	}//end function_name
////////////////////////////////群组——end///////////////////////////////////
	
///////////////////////////////////////////活动部分开始//////////////////////////////////////////////
	/**
	 *我发起的活动
	 *@date 2010-6-11
	 *@time 下午03:59:04
	 */
	function event_my_post() {
		//我发起的活动
		$dao=D("Archives");
		$condition=array();
		$condition['channel']='10';
		if(!$this->is_admin()){
    		$condition['uid']=$this->user['uid'];
		}
    	$count=$dao->where($condition)->count();
		$page=new Page($count,25);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$limit=$page->firstRow.','.$page->listRows;
    	$classifieds=array();
    	$classifieds=$dao->where($condition)->order("pubdate DESC")->limit("$limit")->findAll();
    	//dump($dao->getLastSql());
		$this->assign('event',$classifieds);
		
		$page=array();
		$page['title']='My Event -  My Control Panel -  BeingfunChina';
		$page['keywords']='My Event';
		$page['description']='My Event';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:event_my_post');
		$this->display("Cp:layout");
	}//end event_my_post
	
	
	/**
	 *系统推荐的活动
	 *@date 2010-6-11
	 *@time 下午03:59:42
	 */
	function event_cool() {
		//系统推荐的活动
		
		$page=array();
		$page['title']='My Group -  My Control Panel -  BeingfunChina';
		$page['keywords']='My Group';
		$page['description']='My Group';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:event_cool');
		$this->display("Cp:layout");
	}//end event_cool
	
	/**
	 *我参与评论的活动
	 *@date 2010-6-11
	 *@time 下午04:00:26
	 */
	function event_my_join() {
		//我参与评论的活动
		
		$page=array();
		$page['title']='My Group -  My Control Panel -  BeingfunChina';
		$page['keywords']='My Group';
		$page['description']='My Group';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:event_my_join');
		$this->display("Cp:layout");
	}//end event_my_join
	
	/**
	 *我表态的活动
	 *@date 2010-6-11
	 *@time 下午04:00:51
	 */
	function event_attention() {
		//我表态的活动
		$condition=array();
		$condition['types']=10;
		$condition['uid']=$this->user['uid'];
		//hot 1感兴趣 2关注 3不关心
		$hot=intval($_REQUEST['hot']);
		$act=Input::getVar($_GET['act']);
		$id=Input::getVar($_GET['id']);
		$this->assign('hot',$hot);
		$condition['hot']=$hot;
		
		$dao=D("Thought");
		if($act=='del' && !empty($id)){
			$info=$dao->where("id=$id")->find();
			if($info['uid']==$this->user['uid']){
				$dao->where("id=$id")->delete();	
			}
		}
		$count=$dao->where($condition)->count();
		$page=new Page($count,25);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$limit=$page->firstRow.','.$page->listRows;
    	$event=array();
    	$event=$dao->where($condition)->order("mtime DESC")->limit("$limit")->findAll();
    	$this->assign('event',$event);
    	
		$page=array();
		$page['title']='My Event -  My Control Panel -  BeingfunChina';
		$page['keywords']='My Event';
		$page['description']='My Event';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:event_attention');
		$this->display("Cp:layout");
	}//end function_name
	
	/**
	 *发起活动
	 *@date 2010-6-11
	 *@time 下午04:14:12
	 */
	function event_create() {
		//发起活动
		$this->assign('citylist',$this->_get_city('city'));
		$class_tree=$this->_get_tree(2050);
		$this->assign('class_tree',$class_tree);
		$page=array();
		$page['title']='Create My Event -  My Control Panel -  BeingfunChina';
		$page['keywords']='Create My Event';
		$page['description']='Create My Event';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:event_create');
		$this->display("Cp:layout");
	}//end event_create
	
	/**
	   *编辑活动
	   *@date 2010-9-19
	   *@time 下午05:13:15
	   */
	function my_edit_event() {
		//编辑活动
		$info=Input::getVar($_REQUEST['info']);
		$info=explode('_',$info);
		$dao=D("Archives");
		$condition=array();
		$condition['channel']=$info['0'];
		$condition['id']=$info['1'];
		
	}//end my_edit_event
	
	/**
	 *增加活动信息
	 *@date 2010-6-11
	 *@time 下午04:54:49
	 */
	function event_add() {
		//增加活动信息
		$dao=D("Archives");
		/*if(!empty($_FILES["picurl"]['name'])) {
			$this->_upload();
		}*/
		$vo=$dao->create();
		//dump($vo);
		if($vo){
			$area=$this->_get_city('fair');
			$vo['description']=String::msubstr(strip_tags($_POST['detail']),0,200);
			$vo['my_content']=nl2br($vo['description']);
			$t=explode('/',$vo['showstart']);
			$vo['showstart']=mktime('0',0,0,$t['1'],$t['0'],$t['2']);
			$t=explode('/',$vo['showend']);
			$vo['showend']=mktime('0',0,0,$t['1'],$t['0'],$t['2']);
			
			$t=explode('_',$vo['typeid']);
			$vo['typeid']=$t['1'];
			$vo['channel']=$t['0'];
			$vo['maps']=$_POST['position'].','.$area[$_POST['city_id']]['_zone'][$_POST['zone_id']]['name'].','.$area[$_POST['city_id']]['ctitle'];
			$kw=str_word_count($vo['my_content'],1);
    			$keywords="";
    			foreach ($kw as $vkw){
    				$keywords.=$vkw.',';
    			}
    		$vo['keywords']=trim($keywords,',');
			$aid=$dao->add($vo);
			$actlog=$dao->getLastSql();
			if ($aid) {
				$data=array();
				
				$data['deadline']=ftime($_REQUEST['deadline']);
				$data['starttime']=ftime($_REQUEST['starttime']);
				$data['endtime']=ftime($_REQUEST['endtime']);
				$data['detail']=nl2br(strip_tags($_REQUEST['detail']));
				$data['aid']=$aid;
				$data['public']='2';
				$data['membernum']='0';
				$id=$dao->Table("iic_event")->add($data);
				if($id){
					$actlog='<br>'.$dao->getLastSql();
					if (empty($xid)) {
						$this->_act_log($aid,$vo['channel'],'add',$actlog);
					}else{
						$this->_act_log($aid,$vo['channel'],'edit',$actlog);
					}
					$this->success('Successful release. ');
				}else{
					$dao->Table("iic_archives")->where("id=$aid")->limit('1')->delete();
					$this->error("Failed to write in subsidiary table. ");
				}
			}else{
				$this->error('Failed to update the main profile table. ');
			}
			//dump($vo);
		}else{
			$this->error($dao->getError());
		}
	}//end event_add
	
///////////////////////////////////////////活动部分结束//////////////////////////////////////////////
	
	function is_act($ch,$num) {
		if($this->role[$ch]){
			$role=$this->role[$ch];
		}else{
			$role=$ch.'_2';
		}
		$num_end=end(explode('_',$num));
		$role_end=end(explode('_',$role));
		if($role_end>=$num_end){
			return 1;
		}else{
			return 0;
		}
	}
//////////////////////////////修改密码 编辑资料//////////////////////////////////////////////////////
	/**
	   *修改密码
	   *@date 2010-7-19
	   *@time 上午11:35:31
	   */
	function ch_password() {
		//修改密码
		if($_POST){
			$old=Input::getVar($_POST['oldpassword']);
			$new=Input::getVar($_POST['password']);
			$renew=Input::getVar($_POST['repassword']);
			if ($new!=$renew){
				$this->error("The two passwords you input are inconsistent.");
			}
			if (empty($old)) {
				$this->error("Please enter your current password. ");
			}
			$dao=D("Member");
			$data=array();
			$data['uid']=$this->user['uid'];
			$info=$dao->where($data)->find();
			if ($info['password']!=md5($old)){
				$this->assign('jumpUrl',__URL__.'/index');
				$this->error("Wrong password!");
			}else{
				$data['password']=md5($new);
				$dao->save($data);
				$this->assign('jumpUrl',__URL__.'/index');
				$this->success('Password changed successfully. ');
			}
		}
		$page=array();
		$page['title']='Modify Password -  My Control Panel -  BeingfunChina';
		$page['keywords']='Modify Password';
		$page['description']='Modify Password';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:ch_password');
		$this->display("Cp:layout");
	}//end ch_password
	
	/**
	   *修改用户资料
	   *@date 2010-7-20
	   *@time 上午11:23:52
	   */
	function edit() {
		//修改用户资料
		$dao=D("Members");
		if($_POST['act']=='edit'){
			$data=array();
			$data=$dao->create();
			if(empty($_FILES['avatar']['name'])){
				$data['avatar']=$_POST['old_avatar'];
			}else{
				$finfo=$this->_avatar();
				$data['avatar']='/Public/avatar/s_'.$finfo[0]['savename'];
			}
			$data['bday']=Input::getVar($_POST['bdayyear']).'-'.Input::getVar($_POST['birthmonth']).'-'.Input::getVar($_POST['birthday']);
			$address=Input::getVar($_POST['address']);
			$data['address']=empty($address)?Input::getVar($_POST['address2']):$address;
			$data['id']=$this->user['uid'];
			$re=$dao->save($data);
			if($re){
				$info=$dao->where(array('id'=>$this->user['uid']))->find();
				$info['avatar']=avatar($info['avatar']);
				unset($_SESSION["info"]);
				$_SESSION["info"]=$info;
				redirect("/Cp/edit");
			}else{
				//$this->redirect("/Cp/edit",array(),5,'','Failed to update the Profile.');
				$this->assign('jumpUrl',__APP__."/Cp/edit");
				$this->error('Failed to update the Profile.');
			}
		}else{
			$info=$dao->where(array('id'=>$this->user['uid']))->find();
			$bday=explode('-',$info['bday']);
			$info['bdayyear']=$bday['0'];
			$info['birthmonth']=$bday['1'];
			$info['birthday']=$bday['2'];
			$this->assign('info',$info);
		}
		$page=array();
		$page['title']='Edit Profile -  My Control Panel -  BeingfunChina';
		$page['keywords']='Edit Profile';
		$page['description']='Edit Profile';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:edit');
		$this->display("Cp:layout");
	}//end edit
	
	
	/**
	   *检查权限
	   *@date 2010-8-4
	   *@time 下午06:35:20
	   */
	function is_admin() {
		//检查权限
		if (in_array($this->user['username'],$this->admin,true)) {
			$re=true;
		}else{
			$re=false;
		}
		return $re;
	}//end is_admin
}//end CpAction