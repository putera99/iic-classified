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
	function my_classifieds_comments() {
		//分类信息发布的评论
		
		$page=array();
		$page['title']='My Comments -  My Control Panel -  BeingfunChina';
		$page['keywords']='My Comments';
		$page['description']='My Comments';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:my_classifieds_comments');
		$this->display("Cp:layout");
	}//end my_classifieds_comments
	
	/**
	 *发送分类信息
	 *@date 2010-5-21
	 *@time 上午09:21:48
	 */
	function my_post_classifieds() {
		//发送分类信息
		$class_tree=$this->_get_tree(1);
		/*echo "<pre>";
		print_r($class_tree[0]);
		echo "<pre>";*/
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
		$vo=$dao->create();
		if($vo){
			$t=explode('/',$vo['showstart']);
			$vo['showstart']=mktime('0',0,0,$t['1'],$t['0'],$t['2']);
			$t=explode('/',$vo['showend']);
			$vo['showend']=mktime('0',0,0,$t['1'],$t['0'],$t['2']);
			$t=explode('_',$vo['typeid']);
			$vo['typeid']=$t['1'];
			$vo['channel']=$t['0'];
			$vo['description']=msubstr(strip_tags($_POST['content']),0,400);
			
			$aid=$dao->add($vo);
			if ($aid) {
				$data=array();
				switch (true){
					case $vo['channel']==4:
						$data['joblocated']=$_POST['joblocated']['2'].','.$_POST['joblocated']['1'].','.$_POST['joblocated']['0'];
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
		$page=array();
		$page['title']='Post Fair -  My Control Panel -  BeingfunChina';
		$page['keywords']='Post Fair';
		$page['description']='Post Fair';
		$this->assign('page',$page);
		
		$this->assign('content','Cp:my_post_fair');
		$this->display("Cp:layout");
	}//end my_post_fair
	
}//end CpAction