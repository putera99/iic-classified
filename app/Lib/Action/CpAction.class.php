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
	function _initialize() {
		//预处理
		if (!$this->_is_login()){
			$this->redirect("/Public/login");
		}
		import("ORG.Util.Page");
		parent::_initialize();
	}//end _initialize()
	
	/**
	 *控制面板首页
	 *@date 2010-5-5
	 *@time 下午03:42:51
	 */
	function index() {
		//控制面板首页
		$page=array();
		$page['title']='My Control Panel -  BeingfunChina';
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
    	$condition['uid']=$this->user['uid'];
    	$count=$dao->where($condition)->count();
		$page=new Page($count,10);
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
		$page=array();
		$page['title']='My Message -  My Control Panel -  BeingfunChina';
		$page['keywords']='My Message';
		$page['description']='My Message';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:my_message');
		$this->display("Cp:layout");
	}//end my_message
	
	/**
	 *分类信息发布的评论
	 *@date 2010-5-20
	 *@time 下午08:38:54
	 */
	function my_comments() {
		//分类信息发布的评论
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
		$xid=$_REQUEST['xid'];
		$dao=D("Comment");
		$info=$dao->where("xid=$xid")->find();
		if (empty($info) || $info['uid']!=$this->user['uid']){
			$this->error("Insufficient privileges!");
		}else{
			$dao->where("xid=$xid")->limit('1')->delete();
			if($_REQUEST['types']){
				$this->redirect('/Cp/my_comments/types/'.$_REQUEST['types']);
			}else{
				$this->redirect('/Cp/my_comments');
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
		if($_REQUEST['info']){
			$condition=array();
			$info=explode('_',$_REQUEST['info']);
			$condition['channel']=$info['0'];
			$condition['id']=$info['1'];
		}else{
			$this->error('info is null!');
		}
		$arc=D("Archives");
		$arc_info=$arc->where($condition)->field('id,typeid,title,channel,albumid,albumnum,uid')->limit('1')->find();
		if(empty($arc_info)){
			dump($arc->getLastSql());
			$this->error('archives is null!');
		}
		if($arc_info['uid']!=$this->user['uid']){
			$this->error("Insufficient privileges!");
		}
		$this->assign('arc_info',$arc_info);
		
		$album=D("Album");
		$album_info=$album->where("uid={$this->user['uid']}")->findAll();
		$this->assign('album',$album_info);
		
		$pic=D("Pic");
		$pic_info=$pic->where("xid={$condition['id']}")->findAll();
		dump($pic_info);
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
			$this->error('info is null!');
		}
		$arc=D("Archives");
		if($_REQUEST['type']=='album'){
			$album=D("Album");
			$vo=$album->create();
			
			if($vo){
				$album_id=$album->add($vo);
				
				if($album_id){
					
					$arc->where($condition)->setField("albumid",$album_id);
					$pic=$this->_photo($album_id);
					$pic=$pic['0']["savepath"].$pic['0']['savename'];
					$album->where("id=$album_id")->setField('pic',$pic);
					$this->redirect('/Cp/photo/info/'.$_REQUEST['info']);
				}else{
					$this->error($album->getError());
				}
			}else{
				$this->error($album->getError());
			}
		}else{
			$pic=$this->_photo($_REQUEST['album_id']);
			//dump($pic);
			$list='';
			$list=count($_FILES['filename']);
			//dump($list);
			$dao=D("Pic");
			//dump($_FILES['filename']['name']);
			$num=0;
			for($i=0;$i<$list;$i++){
				
				if($_FILES['filename']['name'][$i]){
					$data=array();
					$data['title']=$_REQUEST['title'][$i];
					$data['album_id']=$_REQUEST['album_id'];
					$data['msg']=nl2br($_REQUEST['msg'][$i]);
					$data['filepath']=$pic[$i]['savepath'];
					$data['filename']=$pic[$i]['filename'];
					$data['type']=$pic[$i]['type'];
					$data['remote']=0;
					$data['xid']=$condition['id'];
					$data=$dao->create($data);
					$id=$dao->add($data);
					if($id){
						$num+=1;
					}
					//$pic->add($data);
				}
			}
			$arc->where($condition)->setField("albumnum",$num);
			$this->redirect('/Cp/photo/info/'.$_REQUEST['info']);
			//dump($data);
		}
	}//end add_photo
	
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
		$dao=D("Archives");
		if(!empty($_FILES)) {
			$this->_upload();
		}
		$vo=$dao->create();
		if($vo){
			$t=explode('/',$vo['showstart']);
			$vo['showstart']=mktime('0',0,0,$t['1'],$t['0'],$t['2']);
			$t=explode('/',$vo['showend']);
			$vo['showend']=mktime('0',0,0,$t['1'],$t['0'],$t['2']);
			$t=explode('_',$vo['typeid']);
			$vo['typeid']=$t['1'];
			$vo['channel']=$t['0'];
			$kw=str_word_count($vo['title'],1);
    			$keywords="";
    			foreach ($kw as $vkw){
    				$keywords.=$vkw.',';
    			}
    		$vo['keywords']=trim($keywords,',');
			$vo['description']=msubstr(strip_tags($_POST['content']),0,400);
			
			$aid=$dao->add($vo);
			if ($aid) {
				$data=array();
				switch (true){
					case $vo['channel']==4:
						$data['joblocated']=$_POST['joblocated']['2'].','.$_POST['joblocated']['1'].','.$_POST['joblocated']['0'];
						$data['joblocated']=$data['joblocated']==',0,0'?'':trim($data['joblocated'],',');
						$data['experience']=$_POST['experience'];
						$data['salary']=$_POST['salary'];
						$table="iic_addon_jobs";
					break;
					case $vo['channel']==5:
						$data['published']=$_POST['published'];
						$data['size']=$_POST['size'];
						$data['price']=$_POST['price'];
						$data['rooms']=$_POST['rooms'];
						$table="iic_addon_realestate";
					break;
					case $vo['channel']==6:
						$data['quantity']=$_POST['quantity'];
						$data['price']=$_POST['price'];
						$table="iic_addon_commerce";
					break;
					case $vo['channel']==7:
						$data['joblocated']=$_POST['joblocated'];
						$data['experience']=$_POST['experience'];
						$data['experience']=$_POST['experience'];
						$data['salary']=$_POST['salary'];
						$table="iic_addon_agents";
					break;
					case $vo['channel']==8:
						$table="iic_addon_personals";
					break;
					case $vo['channel']==9:
						$table="iic_addon_services";
					break;
					default:
						$dao->Table("iic_archives")->where("id=$aid")->limit('1')->delete();
						$this->error("栏目分类读取错误!");
					break;
				}
				$data['content']=nl2br($_POST['content']);
				$data['aid']=$aid;
				$id=$dao->Table($table)->add($data);
				if($id){
					$this->success('发布成功!');
				}else{
					$dao->Table("iic_archives")->where("id=$aid")->limit('1')->delete();
					$this->error("附属表写入失败!");
				}
			}else{
				$this->error('主档案表更新失败!');
			}
		}else{
			$this->error($dao->getError());
		}
	}//end add_classifieds
	
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
    	$condition['uid']=$this->user['uid'];
    	$count=$dao->where($condition)->count();
		$page=new Page($count,25);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$limit=$page->firstRow.','.$page->listRows;
    	$cityguide=array();
    	$cityguide=$dao->where($condition)->order("pubdate DESC")->limit("$limit")->findAll();
    	dump($dao->getLastSql());
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
		$this->assign('class_tree',$class_tree=$this->_get_tree(1000));
		$this->assign('citylist',$this->_get_city('city'));
		$page=array();
		$page['title']='Post CityGuide -  My Control Panel -  BeingfunChina';
		$page['keywords']='Post CityGuide';
		$page['description']='Post CityGuide';
		$this->assign('page',$page);
		$this->assign('content','Cp:my_post_cityguide');
		$this->display("Cp:layout");
	}//end my_cityguide_post
	
	/**
	 *增加城市指南
	 *@date 2010-5-25
	 *@time 下午03:14:18
	 */
	function add_cityguide() {
		//增加城市指南
		$dao=D("Archives");
		if(!empty($_FILES)) {
			$this->_upload();
		}
		$vo=$dao->create();
		if($vo){
			$vo['description']=String::msubstr($vo['my_content'],0,200);
			$vo['my_content']=nl2br($vo['my_content']);
			$t=explode('/',$vo['showstart']);
			$vo['showstart']=mktime('0',0,0,$t['1'],$t['0'],$t['2']);
			$t=explode('/',$vo['showend']);
			$vo['showend']=mktime('0',0,0,$t['1'],$t['0'],$t['2']);
			$t=explode('_',$vo['typeid']);
			$vo['typeid']=$t['1'];
			$vo['channel']=$t['0'];
			$vo['maps']=$_POST['maps']['2'].','.$_POST['maps']['1'].','.$_POST['maps']['0'];
			$kw=str_word_count($vo['title'],1);
    			$keywords="";
    			foreach ($kw as $vkw){
    				$keywords.=$vkw.',';
    			}
    		$vo['keywords']=trim($keywords,',');
			$aid=$dao->add($vo);
			if ($aid) {
				$data=array();
				$data['content']=nl2br($_REQUEST['content']);
				$data['aid']=$aid;
				$id=$dao->Table("iic_addon_article")->add($data);
				if($id){
					$this->success('发布成功!');
				}else{
					$dao->Table("iic_archives")->where("id=$aid")->limit('1')->delete();
					$this->error("附属表写入失败!");
				}
			}else{
				$this->error('主档案表更新失败!');
			}
			//dump($vo);
		}else{
			$this->error($dao->getError());
		}
	}//end add_cityguide
	
	/**
	 *我的收藏夹
	 *@date 2010-5-29
	 *@time 下午04:27:12
	 */
	function my_stuff() {
		//我的收藏夹
		$page=array();
		$page['title']='My Stuff -  My Control Panel -  BeingfunChina';
		$page['keywords']='My Stuff';
		$page['description']='My Stuff';
		$this->assign('page',$page);
		$this->assign('content','Cp:my_stuff');
		$this->display("Cp:layout");
	}//end my_stuff
	
	/**
	 *发布一个展会信息
	 *@date 2010-5-29
	 *@time 下午05:33:40
	 */
	function my_post_fair() {
		//发布一个展会信息
		$class_tree=$this->_get_tree(1232);
		$this->assign("class_tree",$class_tree);
		$this->assign('citylist',$this->_get_city('fair'));
		$this->assign('ltd',$this->_get_ltd());
		$page=array();
		$page['title']='Post Fair -  My Control Panel -  BeingfunChina';
		$page['keywords']='Post Fair';
		$page['description']='Post Fair';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:my_post_fair');
		$this->display("Cp:layout");
	}//end my_post_fair
	
	/**
	 *增加展会信息
	 *@date 2010-6-1
	 *@time 上午10:13:56
	 */
	function add_fair() {
		//增加展会信息
		$dao=D("Archives");
		if(!empty($_FILES)) {
			$this->_upload();
		}
		$vo=$dao->create();
		if($vo){
			$area=$this->_get_city('fair');
			$vo['description']=String::msubstr($vo['my_content'],0,200);
			$vo['my_content']=nl2br($vo['my_content']);
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
			if ($aid) {
				$data=array();
				$data['visitor']=nl2br($_REQUEST['visitor']);
				$data['exhibitor']=nl2br($_REQUEST['exhibitor']);
				$data['product']=$_REQUEST['product'];
				$data['website']=$_REQUEST['website'];
				$data['aid']=$aid;
				$id=$dao->Table("iic_fair")->add($data);
				if($id){
					$this->success('发布成功!');
				}else{
					$dao->Table("iic_archives")->where("id=$aid")->limit('1')->delete();
					$this->error("附属表写入失败!");
				}
			}else{
				$this->error('主档案表更新失败!');
			}
			//dump($vo);
		}else{
			$this->error($dao->getError());
		}
	}//end add_fair
	
	
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
		$page=array();
		$page['title']='Post Articles -  My Control Panel -  BeingfunChina';
		$page['keywords']='Post Articles';
		$page['description']='Post Articles';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:post_art');
		$this->display("Cp:layout");
	}//end post_art
	
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
		$vo=$dao->create();
		if($vo){
			$vo['description']=String::msubstr($vo['my_content'],0,200);
			$vo['my_content']=nl2br($vo['my_content']);
			if($vo['showstart']){
				$t=explode('/',$vo['showstart']);
				$vo['showstart']=mktime('0',0,0,$t['1'],$t['0'],$t['2']);
			}else {
				$vo['showstart']=0;
			}
			if($vo['showend']){
				$t=explode('/',$vo['showend']);
				$vo['showend']=mktime('0',0,0,$t['1'],$t['0'],$t['2']);
			}else{
				$vo['showend']=0;
			}
			$t=explode('_',$vo['typeid']);
			$vo['typeid']=$t['1'];
			$vo['channel']=$t['0'];
			$kw=str_word_count($vo['my_content'],1);
    			$keywords="";
    			foreach ($kw as $vkw){
    				$keywords.=$vkw.',';
    			}
    		$vo['keywords']=trim($keywords,',');
			$aid=$dao->add($vo);
			if ($aid) {
				$data=array();
				$data['content']=nl2br($_REQUEST['content']);
				$data['aid']=$aid;
				$id=$dao->Table("iic_addon_arc")->add($data);
				if($id){
					$this->success('发布成功!');
				}else{
					$dao->Table("iic_archives")->where("id=$aid")->limit('1')->delete();
					$this->error("附属表写入失败!");
				}
			}else{
				$this->error('主档案表更新失败!');
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
    	$condition['uid']=$this->user['uid'];
    	$count=$dao->where($condition)->count();
		$page=new Page($count,25);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$limit=$page->firstRow.','.$page->listRows;
    	$classifieds=array();
    	$classifieds=$dao->where($condition)->order("pubdate DESC")->limit("$limit")->findAll();
		$this->assign('classifieds',$classifieds);
		
		$page=array();
		$page['title']='Post Articles -  My Control Panel -  BeingfunChina';
		$page['keywords']='Post Articles';
		$page['description']='Post Articles';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:my_art');
		$this->display("Cp:layout");
	}//end my_art
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
			$this->_group_up('',132,105);
		}
		$vo=$dao->create();
		if($vo){
			$gid=$dao->add($vo);
			if ($gid){
				$this->_add_tagspace($gid,1);
				$this->assign('jumpUrl',__URL__.'/group_my');
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
	function group_tread_join() {
		//我参与过的话题
		
	}//end group_tread_join
	
	/**
	 *我发布的话题
	 *@date 2010-6-7
	 *@time 下午05:09:57
	 */
	function group_tread() {
		//我发布的话题
		
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
	

	
	
	
	
}//end CpAction