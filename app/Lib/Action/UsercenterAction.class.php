<?php
/**
 +------------------------------------------------------------------------------
 * UsercenterAction控制器类
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  app
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2011-2-25
 * @time  上午11:52:12
 +------------------------------------------------------------------------------
 */
class UsercenterAction extends CpAction{
	function _initialize() {
		//预处理
		parent::_initialize();
		/*if ($_SESSION["info"]['']) {
			$this->error("Insufficient privileges!");
		}*/
		
		$rudao=D("RoleUser");
		$condition=array(
			"user_id"=>$this->user['uid'],
		);
		$data=array();
		$data=$rudao->where($condition)->find();
		if(!count($data)>0){
			$this->error("Insufficient privileges!");
		}else{
			$data['per']=unserialize($data['permissions']);
			$this->user_per=$data;
			$this->assign("permissions",$this->permissions);
			$this->assign("per",$data);
		}
	}//end _initialize()
	
	/**
	   *城市指南用户操作台
	   *用户发布的城市指南信息管理
	   *@date 2011-2-25
	   *@time 下午03:42:42
	   */
	function ctg_index() {
		//城市指南用户操作台
		$arc=D("Archives");
		$condition=array();
		$condition['editer']=$this->user['uid'];
		$condition['channel']="2";
		$ctg=array();
		$count=$arc->where($condition)->count();
		$page=new Page($count,25);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$limit=$page->firstRow.','.$page->listRows;
		$ctg=$arc->where($condition)->limit($limit)->findAll();
		$this->assign("list",$ctg);
		
		$page=array();
		$page['title']='My Control Panel -  BeingfunChina 缤纷中国';
		$page['keywords']='My Control Panel';
		$page['description']='My Control Panel';
		$this->assign('page',$page);
		
		$role_user=D("RoleUser");
		$condition=array();
		
		$this->assign('content','Usercenter:ctg_index');
		$this->display("Cp:layout");
	}//end ctg_index
	
	/**
	   *添加城市指南
	   *@date 2011-2-28
	   *@time 下午02:32:33
	   */
	function ctg_add() {
		//添加城市指南
		
		$class_tree = $this->_get_tree ( 1000 );
		$this->assign ( 'class_tree', $class_tree );
		$this->assign ( 'citylist', $this->_get_city ( 'city' ) );
		$info=Input::getVar($_GET['info']);
		$info=explode('_',$info);
		$arc=D("Archives");
		if($info['2']=="edit"){
			$condition=array();
			$condition['id']=$info['1'];
			$condition['channel']=$info['0'];
			$condition['editer']=$this->user['uid'];
			$info=array();
			$info=$arc->where($condition)->find();
			if($info){
				$addon = $arc->relationGet ( "article" );
				$this->assign ( 'data', $info );
				$this->assign ( 'addon', $addon );
			}else{
				$this->error("Insufficient privileges!");
			}
		}else{
			$condition=array();
			$condition['editer']=$this->user['uid'];
			$condition['channel']="2";
			$count=$arc->where($condition)->count();
			if($this->user_per['per']['5']<=$count||empty($this->user_per['per']['5'])){//超过权限规定的发布条数
				$this->error("Insufficient privileges!");
			}
		}
		$this->assign('content','Usercenter:ctg_add');
		$this->display("Cp:layout");
	}//end ctg_add
	
	/**
	 +----------------------------------------------------------
	 * 编辑自己发的城市指南文章
	 * @date 2011-2-28 - @time 下午02:44:30
	 +----------------------------------------------------------
	 * @static
	 * @access public
	 +----------------------------------------------------------
	 * @param string 
	 +----------------------------------------------------------
	 * @return void
	 +----------------------------------------------------------
	 */
	function ctg_do() {
		//编辑自己发的城市指南文章
		$dao=D("Archives");
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
			$vo['editer']=$this->user['uid'];
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
			if(!empty($_POST['contact'])){
				$vo['maps']=$_POST['contact'];
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
	    		$condition=array();
				$condition['editer']=$this->user['uid'];
				$condition['channel']="2";
				$count=$dao->where($condition)->count();
				if($this->user_per['per']['5']<=$count||empty($this->user_per['per']['5'])){//超过权限规定的发布条数
					$this->error("Insufficient privileges!");
				}
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
					
					$txt='<h4>Successful release. </h4><br><a href="'.__APP__.'/ctgs/'.$aid.'.html">View city guide</a>   /   ';
					$txt.='<a href="'.__APP__.'/Cp/photo/info/'.$vo['channel'].'_'.$aid.'">Add pictures</a><br>';
					$txt.='<a href="'.__APP__.'/Cp/my_post_cityguide">Post city guide </a>   /   ';
					$txt.='<a href="'.__APP__.'/Cp/my_cityguide_post">Back to the list </a><br>';
					$txt.='You will be directed to the picture uploading page in three seconds! ';
					$this->assign('jumpUrl',__APP__.'/Cp/photo/info/'.$vo['channel'].'_'.$aid);
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
	}//end ctg_do
	
	
	/**
	 +----------------------------------------------------------
	 * 新建用户相册
	 * @date 2011-2-28 - @time 下午02:45:07
	 +----------------------------------------------------------
	 * @static
	 * @access public
	 +----------------------------------------------------------
	 * @param string 
	 +----------------------------------------------------------
	 * @return void
	 +----------------------------------------------------------
	 */
	function ctg_albums() {
		//新建用户相册
		$dao=D("Album");
		$act=empty($_GET['act'])?"index":$_GET['act'];
		$act_arr=array('index','add','edit','do','add_photo');
		if(!in_array($act, $act_arr,TRUE)){
			$this->error("Insufficient privileges!");
		}
		$page=array();
		$page['title']='My Control Panel -  BeingfunChina 缤纷中国';
		$page['keywords']='My Control Panel';
		$page['description']='My Control Panel';
		$this->assign('page',$page);
		switch (true) {
			case $act=="add":
				//新增相册
				$info=array();
				$aid=$_GET['aid'];
				$condition=array();
				$condition['uid']=$this->user['uid'];
				$condition['itype']="vip";
				$condition['id']=$_GET['aid'];
				$info=$dao->where($condition)->find();
				if(!$info){
					$this->error("Insufficient privileges!");
				}
				$pic = D ( "Pic" );
				if ($_REQUEST ['action'] == 'del') {
					$pid = Input::getVar ( $_GET ['pid'] );
					$pinfo = $pic->where ( "id=$pid" )->find ();
					$pdata = array ('id' => $pid, 'is_show' => 0, 'dtime' => time () );
					if ($arc_info ['uid'] == $this->user ['uid'] || $pinfo ['uid'] == $this->user ['uid'] || in_array ( $this->user ['username'], $this->admin )) {
						$pic->save ( $pdata );
					} else {
						$this->error ( "Insufficient privileges!" );
					}
				} elseif ($_REQUEST ['action'] == 'cover') {
					$pid = Input::getVar ( $_GET ['pid'] );
					$pinfo = $pic->where ( "id=$pid" )->find ();
					if (end ( explode ( ',', $pinfo ['thumb'] ) ) != 's_') {
						import ( "ORG.Util.Image" );
						$thumbname = $pinfo ['filepath'] . 's_' . $pinfo ['filename'];
						$filename = $pinfo ['filepath'] . $pinfo ['filename'];
						Image::thumb ( $filename, $thumbname, '', 120, 120, true );
					}
					$data = array ('pic' => $pinfo ['filepath'] . 's_' . $pinfo ['filename'] );
					$dao->where ( $condition )->limit ( '1' )->save ( $data );
					if ($this->user ['username'] == 'iicc') {
						dump ( $dao->getLastSql () );
					}
				}
				$condition = array ();
				$condition ['album_id'] =$aid;
				$condition ['is_show'] = '1';
				$pic_info = $pic->where ( $condition )->findAll ();
				//dump($pic_info);
				$this->assign ( 'pic', $pic_info );
				$this->assign("info",$info);
				$this->assign('content','Usercenter:ctg_albums_add');
				$this->display("Cp:layout");
				
			break;
			case $act=="add_photo":
				if ($_REQUEST ['album_id']) {
					$condition = array ();
					$condition ['id'] = $_REQUEST ['album_id'];
				} else {
					$this->error ( 'Wrong parameter' );
				}
				$pic = $this->_photo( $condition ['id'],'550,200','650,200' );
				//dump($pic);
				$list = '';
				$list = count ( $_FILES ['filename'] ['name'] );
				$picdao = D ( "Pic" );
				$num = 0;
				for($i = 0; $i < $list; $i ++) {
					if ($_FILES ['filename'] ['name'] [$i]) {
						$data = array ();
						$data ['title'] = empty ( $_REQUEST ['title'] [$i] ) ? $_FILES ['filename'] ['name'] [$i] : $_REQUEST ['title'] [$i];
						$data ['msg'] = $_REQUEST ['msg'] [$i];
						$data ['filepath'] = $pic [$i] ['savepath'];
						$data ['filename'] = $pic [$i] ['savename'];
						$data ['type'] = $pic [$i] ['type'];
						$data ['size'] = $pic [$i] ['size'];
						$data ['thumb'] = 'm_,s_';
						$data ['remote'] = 0;
						$data ['album_id'] = $condition ['id'];
						/*$data ['xid'] = $condition ['id'];
						$data ['xtype'] = $condition ['channel'];*/
						$data = $picdao->create ( $data );
						$id = $picdao->add ( $data );
						//dump($picdao->getlastsql());
						if ($id) {
							$num += 1;
						}
					}
				}
				$count = $picdao->where ( "album_id={$condition['id']} AND is_show='1'" )->count ();
				$dao->where ( $condition )->setField(array("picnum", "updatetime"),array($count,time()));
				redirect ( __APP__.'/Usercenter/ctg_albums/act/add/aid/' . $_REQUEST ['album_id'] );
			break;
			case $act=="edit":
				if($_GET['aid']){
					$condition=array();
					$condition['uid']=$this->user['uid'];
					$condition['itype']="vip";
					$condition['id']=$_GET['aid'];
					$info=$dao->where($condition)->find();
					if(!$info){
						$this->error("Insufficient privileges!");
					}
					$this->assign("info",$info);
					$this->assign('content','Usercenter:ctg_albums');
					$this->display("Cp:layout");
				}else{
					$this->error("Insufficient privileges!");
				}
			break;
			case $act=="do":
				$vo=$dao->create();
				if(!vo){
					$this->error($dao->getError());
				}
				if(empty($_POST['id'])){//如果不是更新检查可建相册权限
					$condition=array();
					$condition['uid']=$this->user['uid'];
					$condition['itype']="vip";
					$count=$dao->where($condition)->count();
					if($this->user_per['per']['3']<=$count||empty($this->user_per['per']['3'])){//超过权限规定的发布条数
						$this->error("Insufficient privileges!");
					}
					$aid=$dao->add($vo);
					if($aid){
						$txt='<h4>Successful release. </h4><br><a href="'.__APP__.'/albums/'.$aid.'.html">View album</a>   /   ';
						$txt.='<a href="'.__APP__.'/Usercenter/ctg_albums">Back to the list </a><br>';
						$txt.='You will be directed to the list in three seconds! ';
						$this->assign('jumpUrl',__APP__.'/Usercenter/ctg_albums');
						$this->success($txt);
					}else{
						$this->error("Insufficient privileges!");
					}
				}else{
					$vo['updatetime']=time();
					$dao->save($vo);
					$txt='<h4>Successful release. </h4><br><a href="'.__APP__.'/albums/'.$aid.'.html">View album</a>   /   ';
					$txt.='<a href="'.__APP__.'/Usercenter/ctg_albums">Back to the list </a><br>';
					$txt.='You will be directed to the list in three seconds! ';
					$this->assign('jumpUrl',__APP__.'/Usercenter/ctg_albums');
					$this->success($txt);
				}
			break;
			
			default:
				$condition=array();
				$condition['uid']=$this->user['uid'];
				$condition['itype']="vip";
				$condition['ctg_id']=$_GET['ctgid'];
				$count=$dao->where($condition)->count();
				$page=new Page($count,25);
				$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
				$this->assign('showpage',$page->show());
				$limit=$page->firstRow.','.$page->listRows;
				$list=$dao->where($condition)->limit($limit)->findAll();
				$this->assign("list",$list);
				$this->assign('content','Usercenter:ctg_albums');
				$this->display("Cp:layout");
			break;
		}
		
	}//end ctg_albums
	
	
	/**
	 +----------------------------------------------------------
	 * 城市指南用户发布相关文章
	 * @date 2011-2-28 - @time 下午02:46:21
	 +----------------------------------------------------------
	 * @static
	 * @access public
	 +----------------------------------------------------------
	 * @param string 
	 +----------------------------------------------------------
	 * @return void
	 +----------------------------------------------------------
	 */
	function ctg_article() {
		//城市指南用户发布相关文章
		$dao=D("Archives");
		$act=empty($_GET['act'])?"index":$_GET['act'];
		$act_arr=array('index','add','edit');
		if(!in_array($act, $act_arr,TRUE)){
			$this->error("Insufficient privileges!");
		}
		$page=array();
		$page['title']='My Control Panel -  BeingfunChina 缤纷中国';
		$page['keywords']='My Control Panel';
		$page['description']='My Control Panel';
		$this->assign('page',$page);
		switch (true) {
			case $act=="add":
				//新增加则 检查可建相册权限
				$ctgid=$_GET['ctgid'];
				if(empty($ctgid)){
					$this->assign("jumpUrl",__APP__."/Usercenter/ctg_index");
					$this->error("Insufficient privileges!");
				}
				$condition = array ();
				$condition ['channel'] = '12';
				$condition ['uid'] = $this->user ['uid'];
				$count=$dao->where($condition)->count();
				if($this->user_per['per']['1']<=$count||empty($this->user_per['per']['1'])){//超过权限规定的发布条数
					$this->error($this->user_per['per']['3']."Insufficient privileges!".$count);
				}
				$this->assign('content','Usercenter:ctg_article_add');
				$this->display("Cp:layout");
			break;
			case $act=="edit":
				if($_GET['id']){
					$condition=array();
					$condition['uid']=$this->user['uid'];
					$condition['id']=$_GET['id'];
					$info=$dao->where($condition)->find();
					if(!$info){
						$this->error("Insufficient privileges!");
					}
					$this->assign("info",$info);
					$this->assign('content','Usercenter:ctg_article_add');
					$this->display("Cp:layout");
				}else{
					$this->error("Insufficient privileges!");
				}
			break;
			default:
				$condition = array ();
				$condition ['channel'] = '12';
				$condition ['typeid'] = '2002';
				$condition ['uid'] = $this->user ['uid'];
				if($_GET['ctgid']){
					$condition ['typeid2'] =$_GET['ctgid'];
				}
				$count=$dao->where($condition)->count();
				$page=new Page($count,25);
				$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
				$this->assign('showpage',$page->show());
				$limit=$page->firstRow.','.$page->listRows;
				$list=$dao->where($condition)->limit($limit)->findAll();
				//dump($dao->getLastSql());
				$this->assign("list",$list);
				$this->assign('content','Usercenter:ctg_article');
				$this->display("Cp:layout");
			break;
		}
	}//end ctg_article
	
	
	/**
	 +----------------------------------------------------------
	 * 处理城市指南用户发的文章
	 * @date 2011-2-28 - @time 下午02:47:19
	 +----------------------------------------------------------
	 * @static
	 * @access public
	 +----------------------------------------------------------
	 * @param string 
	 +----------------------------------------------------------
	 * @return void
	 +----------------------------------------------------------
	 */
	function ctg_article_do() {
		//处理城市指南用户发的文章
			//增加新闻
		$dao = D ( "Archives" );
		$_POST ['my_content'] = Input::getVar ( $_POST ['my_content'] );
		$vo = $dao->create ();
		if ($vo) {
			if (empty ( $_POST ['my_content'] )) {
				$this->error ( "You must fill in the field of 'Summary'" );
			}
			$vo ['description'] = String::msubstr ( $vo ['my_content'], 0, 200 );
			$vo ['my_content'] = nl2br ( $vo ['my_content'] );
			$vo ['my_content'] = trim ( $vo ['my_content'], '<br>' );
			if ($vo ['showstart']) {
				$t = explode ( '/', $vo ['showstart'] );
				$vo ['showstart'] = mktime ( '0', 0, 0, $t ['1'], $t ['0'], $t ['2'] );
			} else {
				$vo ['showstart'] = time ();
			}
			if ($vo ['showend']) {
				$t = explode ( '/', $vo ['showend'] );
				$vo ['showend'] = mktime ( '0', 0, 0, $t ['1'], $t ['0'], $t ['2'] );
			} else {
				$vo ['showend'] = $vo ['showstart'] + (60 * 60 * 60 * 24);
			}
			$vo ['typeid'] ="2002";
			$vo ['channel'] ="12";
			$kw = str_word_count ( $vo ['description'], 1 );
			$keywords = "";
			foreach ( $kw as $vkw ) {
				$keywords .= $vkw . ',';
			}
			$vo ['keywords'] = empty ( $_POST ['keywords'] ) ? trim ( $keywords, ',' ) : $_POST ['keywords'];
			$eid = '';
			$xid = Input::getVar ( $_REQUEST ['id'] );
			if ($xid) {
				$aid = $xid;
				$eid = $xid;
				$dao->where ( "id=$aid" )->save ( $vo );
			} else {
				$aid = $dao->add ( $vo );
			}

			$actlog = $dao->getLastSql ();
			if ($aid) {
				$data = array ();
				$_POST ['content'] = Input::getVar ( $_POST ['content'] );
				if (empty ( $_POST ['content'] )) {
					$this->error ( "You must fill in the field of 'Content'" );
				}
				//$data['content']=nl2br($_REQUEST['content']);
				$data ['content'] = $_POST ['content'];
				if (! empty ( $xid )) {
					$id = $dao->Table ( "iic_addon_arc" )->where ( "aid=$aid" )->save ( $data );
				} else {
					$data ['aid'] = $aid;
					$id = $dao->Table ( "iic_addon_arc" )->add ( $data );
				}
				//dump($dao->getLastSql());
				if ($id || $id == '0') {
					$actlog .= '<br>' . $dao->getLastSql ();
					if (empty ( $xid )) {
						$this->_act_log ( $aid, $vo ['channel'], 'add', $actlog );
					} else {
						$this->_act_log ( $aid, $vo ['channel'], 'edit', $actlog );
					}
					//echo "发布成功!";
					$txt = '<h4>Successful release. </h4><br><a href="'.__APP__.'/article/' . $aid . '.html">View articles </a>   /   ';
					$txt .= '<a href="'.__APP__.'/Usercenter/ctg_article/act/add/ctgid/'.$_POST['typeid2'].'">Post articles</a><br>';
					$txt .= '<a href="'.__APP__.'/Usercenter/ctg_article/">Back to the list </a>';
					$txt .= 'You will be directed to the list page in three seconds! ';
					$this->assign ( 'jumpUrl', __APP__.'/Usercenter/ctg_article/ctgid/'.$_POST['typeid2']);
					$this->success ( $txt );
				} else {
					if (empty ( $xid )) {
						$dao->Table ( "iic_archives" )->where ( "id=$aid" )->limit ( '1' )->delete ();
					}
					$this->error ( "Failed to write in subsidiary table. " );
				}
			} else {
				if ($this->user ['username'] == 'iicc') {
					dump ( $dao->getLastSql () );
				}
				$this->error ( 'Failed to update the main profile table. ' );
			}
		
		//dump($vo);
		} else {
			$this->error ( $dao->getError () );
		}
	}//end ctg_article_do
	
	

}//end UsercenterAction