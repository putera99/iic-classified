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
		$class_tree=$this->_classifieds_tree();
		/*echo "<pre>";
		print_r($class_tree[0]);
		echo "<pre>";*/
		$this->assign("class_tree",$class_tree);
		$this->assign('citylist',$this->city);
		
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
		
	}//end add_classifieds
	
}//end CpAction