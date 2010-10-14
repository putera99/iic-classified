<?php
/**
 +------------------------------------------------------------------------------
 * GroupAction控制器类
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  bi
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010年 05月 04日 星期二 11:19:55 CST +------------------------------------------------------------------------------
 */
class GroupAction extends CommonAction{
	function _initialize(){
		parent::_initialize();
	}
	
	/**
	 *群组首页
	 *@date 2010年 05月 04日 星期二 11:21:11 CST
	 */
	function index() {
		//群组首页
		$this->assign('cat',$this->_get_mtag());
		$this->assign('hot',$this->_get_group('hot','0,30'));
		$this->assign('new',$this->_get_group('new','0,15'));

        $page=array();
		$page['title']='An interaction platform for foreigners in China.缤纷中国';
		$page['keywords']=empty($info['keywords'])?"Group,list":$info['keywords'];
		$page['description']=empty($info['description'])?"Groups in BeingfunChina":$info['description'];

		$this->assign('page',$page);
		$this->ads('2','channel');
		$this->display();
	}//end index

	/**
	 *群组列表页
	 *@date 2010-6-4
	 *@time 下午03:56:54
	 */
	function ls() {
		//群组列表页
		$condition=array();
		$dh='';
		if ($_POST['kw']) {
			$condition['groupname']=array("like","%{$_POST['kw']}%");
			$this->assign('kw',$_POST['kw']);
		}
		if (intval($_GET['id'])) {
			$condition['cat_id']=intval($_GET['id']);
			$dh=$this->_get_pinfo($condition['cat_id']);
		}
		if(empty($condition)){
			$this->error('Wrong parameter.');
		}
		$dao=D("Group");
		if ($condition['cat_id']) {
			$cat=D("MtagCat");
			$info=$cat->where("id={$condition['cat_id']} AND is_show=1")->find();
			$this->assign('info',$info);
			if($info['is_dir']=='1'){
				$son=$cat->where("pid={$condition['cat_id']} AND is_show=1")->field("id,title")->findAll();
				$arr=array();
				foreach ($son as $v){
					$arr[$v['id']]=$v;
					$arr[$v['id']]['count']=$this->_get_catnum($v['id']);
				}
				$this->assign("son",$arr);
				$str='';
				if($son){
					foreach ($son as $v){
						$str.=$v['id'].',';
					}
					$str=trim($str,',');
					$condition['cat_id']=array('IN',$str);
				}
			}
		}
		$data=$dao->where($condition)->order("ctime DESC")->findAll();
		$this->assign("list",$data);
		
		$page=array();
		$page['title']=empty($info['title'])?$info['title'].'  -  BeingfunChina 缤纷中国':'Group list  -  BeingfunChina 缤纷中国';
		$page['keywords']=empty($info['keywords'])?"Group,list":$info['keywords'];
		$page['description']=empty($info['description'])?"Groups in BeingfunChina":$info['description'];
	
		$this->assign('page',$page);
		
		$this->assign('dh',$dh);
		$this->display();
	}//end ls
	
	/**
	 *群组页面
	 *@date 2010-6-4
	 *@time 下午03:57:30
	 */
	function show() {
		//群组页面
		$id=intval($_REQUEST['aid']);
		if(empty($id)){
			$this->error("Wrong parameter.");
		}
		$dao=D("Group");
		$info=$dao->where("id=$id")->find();
		$this->assign('dh',$this->_get_pinfo($info['cat_id']));
		$this->assign('info',$info);
		
		
		$post=D("Post");
		$condition=array();
		$condition['gid']=$info['id'];
		$condition['l']='0';
		$condition['topid']='0';
		$condition['is_show']='1';
		
		$count=$post->where($condition)->count();
		import("ORG.Util.Page");
		$page=new Page($count,10);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$page->config=array('header'=>'','prev'=>'<','next'=>'>','first'=>'«','last'=>'»','theme'=>'%first% %upPage%  %prePage%  %linkPage%  %nextPage% %downPage% %end%');
		$this->assign('showpage_bot',$page->show_img());
		$limit=$page->firstRow.','.$page->listRows;
		
		$thread=$post->where($condition)->order("dateline DESC")->limit($limit)->findAll();
		$this->assign('thread',$thread);
		$this->assign("newmember",$this->get_gmember($info['id']));
		$all_member=$this->get_gmember($info['id'],"","");
		$this->assign('member_group',$this->member_group($all_member,"0,6"));
		
		$page=array();
		$page['title']=$info['groupname'].'  -  BeingfunChina 缤纷中国';
		$page['keywords']=$info['groupname'];
		$page['description']=$info['announcement'];
		$this->assign('page',$page);
		
		$this->ads('13','content');
		$this->display();
	}//end show
	
	
	/**
	 *话题列表页
	 *@date 2010-6-4
	 *@time 下午03:59:33
	 */
	function topic() {
		//话题列表页
		$gid=empty($_REQUEST['id'])?0:intval($_REQUEST['id']);
		$kw=empty($_REQUEST['kw'])?0:$_REQUEST['kw'];
		$condition=array();
		if($gid){
			$condition['gid']=$gid;
		}
		if($kw){
			$condition['title']=array("like","%$kw%");
			$this->assign('kw',$_POST['kw']);
		}
		if(empty($condition)){
			$this->error('Wrong parameter.');
		}
		$condition['l']='1';
		$condition['topid']='0';
		$condition['is_show']='1';
		
		$post=D("Post");
		$count=$post->where($condition)->count();
		import("ORG.Util.Page");
		$page=new Page($count,25);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$page->config=array('header'=>'','prev'=>'<','next'=>'>','first'=>'«','last'=>'»','theme'=>'%first% %upPage%  %prePage%  %linkPage%  %nextPage% %downPage% %end%');
		$this->assign('showpage_bot',$page->show_img());
		$limit=$page->firstRow.','.$page->listRows;
		
		$thread=$post->where($condition)->order("dateline DESC")->limit($limit)->findAll();
		$this->assign('thread',$thread);
		$this->display();
	}//end topic
	
	
	/**
	 *话题页面
	 *@date 2010-6-4
	 *@time 下午03:57:52
	 */
	function thread(){
		//话题页面
		$tid=empty($_REQUEST['id'])?0:intval($_REQUEST['id']);
		$lou=empty($_REQUEST['lou'])?0:intval($_REQUEST['lou']);
		$condition=array();
		if($tid){
			$condition['topid']=$tid;
		}
		if(empty($condition)){
			$this->error('Wrong parameter.');
		}
		$condition['requery']='0';
		$condition['qid']='0';
		$pn=1000;
		$post=D("Post");
		$count=$post->where($condition)->count();
		import("ORG.Util.Page");
		$page=new Page($count,$pn);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$page->config=array('header'=>'','prev'=>'<','next'=>'>','first'=>'«','last'=>'»','theme'=>'%first% %upPage%  %prePage%  %linkPage%  %nextPage% %downPage% %end%');
		$this->assign('showpage_bot',$page->show_img());
		$limit=$page->firstRow.','.$page->listRows;
		if($lou){
			$ls=$lou<$pn?1:intval($lou/$pn)*$pn;
			$limit=$ls.','.$page->listRows;
		}
		$thread=$post->where($condition)->order("dateline ASC")->limit($limit)->findAll();
		$info=$post->where("id=$tid")->find();
		$arr=array();
		foreach ($thread as $t){//获取回复
			$arr[$t['id']]=$t;
			$condition['requery']=$t['id'];
			$arr[$t['id']]['_rarr']=$post->where("requery={$t['id']} or qid={$t['id']}")->order("dateline ASC")->findAll();
			//dump($post->getLastSql());
		}
		$this->assign('thread',$arr);
		
		$this->assign('info',$info);
		
		$ginfo=get_info($info['gid'],'*');
		$this->assign('ginfo',$ginfo);
		$dh=$this->_get_pinfo($ginfo['cat_id']);
		$this->assign('dh',$dh);
		
		$page=array();
		$page['title']=empty($info['title'])?'Group Thread  -  BeingfunChina':$info['title'].'  -  BeingfunChina';
		$page['keywords']=empty($info['tags'])?"Group,Thread":$info['tags'];
		$page['description']=empty($info['title'])?"Groups in BeingfunChina":$info['title'];
		$this->assign('page',$page);
		$this->assign("new_topics",$this->_new_thread($info['gid']));
		$this->assign("group",$this->member_group($this->member_thread($tid),"0,6"));
		$this->display();
	}//end thread
	
	/**
	 *群组收藏
	 *@date 2010-6-4
	 *@time 下午04:00:35
	 */
	function collection() {
		//群组收藏
		
	}//end collection
	
	/**
	 *群内成员列表
	 *@date 2010-6-4
	 *@time 下午04:02:01
	 */
	function members() {
		//群内成员
		$gid=Input::getVar($_GET['gid']);
		$dao=D("Tagspace");
		$count=$dao->where("tagid=$gid")->count();
		import("ORG.Util.Page");
		$page=new Page($count,50);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$page->config=array('header'=>'','prev'=>'<','next'=>'>','first'=>'«','last'=>'»','theme'=>'%first% %upPage%  %prePage%  %linkPage%  %nextPage% %downPage% %end%');
		$this->assign('showpage_bot',$page->show_img());
		$limit=$page->firstRow.','.$page->listRows;
		$member=$this->get_gmember($gid,'',$limit);
		$this->assign('member',$member);
		$this->display();
	}//end members
	
	/**
	   *加入群组
	   *@date 2010-6-9
	   *@time 下午10:45:32
	   */
	function join_group() {
		//加入群组
		$gid=intval($_REQUEST['gid']);
		if (empty($gid)){
			$this->ajaxReturn('0','Wrong parameter.','0');
		}
		if(!$this->_is_login()){
			$this->ajaxReturn('login',"Login please.",'0');
		}
		$dao=D("Tagspace");
		$condition=array();
		$condition['tagid']=$gid;
		$condition['uid']=$this->user['uid'];
		$info=$dao->where($condition)->find();
		if($info){
			unset($info);
			$this->ajaxReturn('0','You\'ve joined this group already!','0');
		}else{
			$condition['grade']="3";
			$condition['username']=$this->user['username'];
			$condition['ctime']=time();
			$id=$dao->add($condition);
			if ($id) {
				$this->edit_thread($gid,3);
				$this->ajaxReturn($id,"Joined successfully! ",'1');
			}else{
				$this->ajaxReturn('0',"Failed to join in !",'0');
			}
		}
		
		
	}//end join_group
	
	/**
	 *发表话题
	 *@date 2010-6-4
	 *@time 下午04:36:18
	 */
	function add_post() {
		//搜索群组
		if(!$this->_is_login()){
			$this->ajaxReturn('login',"Login please.",'0');
		}
		$dao=D("Post");
		$vo=$dao->create();
		if($vo){
			if(empty($vo['message'])){
				$this->ajaxReturn('0','You must fill in the field of "Content".','0');
			}
			$vo['title']=$vo['title']?$vo['title']:"";
			$vo['aid']=$vo['aid']?$vo['aid']:"0";
			$vo['qid']=$vo['qid']?$vo['qid']:"0";
			$vo['l']=$vo['l']?$vo['l']:"1";
			$vo['topid']=$vo['topid']?$vo['topid']:"0";
			$vo['requery']=$vo['requery']?$vo['requery']:"0";
			$vo['qidstr']=$vo['qidstr']?$vo['qidstr']:"0";
			$vo['message']=nl2br(remove_xss($vo['message']));
			$vo['is_show']=1;
			if($vo['topid']!='0' && $vo['qid']=='0'){//主题的回复
				$top=$dao->where("topid={$vo['topid']}")->field('id,l,topid')->order("l DESC")->find();
				$vo['l']=$top['l']+1;
			}elseif($vo['topid']=='0' && $vo['qid']=='0'){//主题
				$vo['l']='0';
			}elseif($vo['topid']!='0' && $vo['qid']!='0'){//主题回复的回复
				$top=$dao->where("qid={$vo['qid']}")->field('id,l,topid')->order("l DESC")->find();
				$vo['l']=$top['l']+1;
			}
			
			//dump($vo);
			$pid=$dao->add($vo);
			if($pid){
				if($vo['topid']!='0'){
					$this->posts_lasttime($vo['topid']);
				}
				$data=$dao->where("id=$pid")->find();
				$data['dateline']=toDate($data['dateline'],'Y-m-d');
				$data['lasttime']=toDate($data['lasttime'],'Y-m-d');
				if($data['l']=='0'){
					$this->edit_thread($data['gid']);
				}else{
					$this->edit_thread($data['gid'],2);
				}
				$this->ajaxReturn($data,"You’ve sent successfully.",'1');
			}else{
				$this->ajaxReturn('0',"You’ve sent successfully.",'0');
			}
		}else{
			$this->ajaxReturn('0','Replied successfully!','0');
		}
	}//end add_group
	
	/**
     *获取当前栏目位置
     *@date 2010-5-10
     *@time 上午09:47:29
     */
    protected function _get_pinfo($typeid) {
    	//获取当前位置
    	$dao=D("MtagCat");
    	$data=$dao->where("id=$typeid AND is_show=1")->field('id,pid,title,is_dir')->find();
    	if ($data['is_dir']=='0'){
    		$data['_pid']=$dao->where("id={$data['pid']}")->field('id,pid,title,is_dir')->find();
    	}
    	return $data;
    }//end _get_dh
    
    /**
     *修改主题数
     *@date 2010-6-23
     *@time 上午10:55:29
     */
    protected function edit_thread($gid,$type='1',$num='1') {
    	//修改主题数
    	if(!empty($gid)){
	    	$dao=D("Group");
	    	$info=$dao->where("id=$gid")->find();
	    	if($type=='1'){
	    		$info['threadnum']=$info['threadnum']+$num;
	    	}elseif($type=='2'){
	    		$info['postnum']=$info['postnum']+$num;
	    	}elseif($type=='3'){
	    		$info['membernum']=$info['membernum']+$num;
	    	}
	    	$info['lasttime']=time();
	    	return $dao->save($info);
    	}
    }//end edit_thread

    /**
       *修改话题的最后回复时间
       *@date 2010-9-21
       *@time 下午02:13:51
       */
    function posts_lasttime($topid) {
    	//修改话题的最后回复时间
    	$dao=D("Post");
    	$dao->where("id=$topid")->setField('lasttime',time());
    }//end posts_lasttime
    	/**
	   *获取最新话题
	   *@date 2010-8-17
	   *@time 下午04:07:35
	   */
	function _new_thread($gid,$limit="0,10",$mode="new") {
		//获取最新话题
		$post=D("Post");
		$condition=array();
		$condition['gid']=$gid;
		$condition['is_show']='1';
		$condition['l']='0';
		if($mode=="new"){
			$order="dateline DESC";
		}elseif($mode=="hot"){
			$order="lasttime DESC";
		}
		$list=$post->where($condition)->order($order)->limit($limit)->findAll();
		return $list;
	}//end _new_thread
}// END GroupAction

?>
