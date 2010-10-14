<?php
/**
 +------------------------------------------------------------------------------
 * CityGuideAction控制器类
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  app
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-4-30
 * @time  下午02:52:05
 +------------------------------------------------------------------------------
 */
class CityGuideAction extends CommonAction{
	
	protected $pcid='';//页面查询用的独立城市ID
	
	/**
	  *预处理
	  *@date 2010-4-30
	  *@time 上午10:08:20
	  */
	function _initialize() {
		//预处理
		parent::_initialize();
		$cid=Input::getVar($_GET['cid']);
		if (empty($cid)){
			if($_SESSION['cid']){
				$this->pcid=$cid;
			}else{
				$_SESSION['cid']=$cid;
				$this->pcid=$cid;
				cookie('cid',null);
				if ($_REQUEST['remember']) {
					cookie('cid',$cid,array('expire'=>60*60*60*24*30));
				}
			}
		}else{
			//$this->_set_cid();
			$this->pcid=$this->cid;
		}
	}//end _initialize()
	
	/**
	 *城市指南频道首页
	 *@date 2010-4-30
	 *@time 下午02:52:26
	 */
	function index() {
		//城市指南频道首页
		//$this->chk_cid();
		$this->ads('2','channel');
		$arctype=D("Arctype");
		$data=$arctype->where("id>1000 AND topid=1000")->order("id asc")->findAll();
		$list=list_to_tree($data,'id','reid','_son',1000);
		//dump($list);
		$this->assign('list',$list);
		$info=$arctype->where('id=1000')->find();
		$this->assign('info',$info);
		$group=$this->_get_group('hot',"0,5");
                $this->assign('group',$group);
		$page=array();
		$page['title']=empty($info['seotitle'])?$info['typename'].'  -  BeingfunChina 缤纷中国':$info['seotitle'].'  -  BeingfunChina 缤纷中国';
		$page['keywords']=empty($info['keywords'])?$info['typename']:$info['keywords'];
		$page['description']=empty($info['description'])?$info['typename']:$info['description'];
		$this->assign('page',$page);

        $this->assign('city_type',$this->_get_tree(1000));
		$this->assign('classifieds_type',$this->_get_tree(1));
		$this->display();
	}//end index
	
	/**
	 *分类信息列表页面
	 *@date 2010-4-30
	 *@time 下午04:36:24
	 */
	function ls() {
		//分类信息列表页面
		//$this->chk_cid();
		$typeid=intval($_GET['id']);
		
		$arctype=D("Arctype");
		$info=$arctype->where("id=$typeid")->find();
		
		//if($info['ispart']==1){
		$small=$arctype->where("reid=$typeid")->field("id")->findAll();
		$str='';
		if($small){
			foreach ($small as $v){
				$str.=$v['id'].',';
			}
			$str='typeid IN ('.trim($str,',').')';
		}else{
			$str="typeid={$typeid}";
		}
		//信息列表
		$now=time();
		import("ORG.Util.Page");
		$dao=D("Archives");
		//$count=$dao->where("((typeid={$typeid} AND cid={$this->pcid}) AND ismake=1) AND (showstart<{$now} AND showend>{$now})")->order("pubdate DESC")->count();
		if($info['id']=='1393' || $info['reid']=='1393' || $this->pcid=='0'||empty($this->pcid)){
			$city='1=1';
		}else{
			$city="(cid={$this->pcid} or cid='0')";
		}
		$count=$dao->where("($str AND $city) AND ismake=1")->order("pubdate DESC")->count();
		$page=new Page($count,10);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$page->config=array('header'=>'','prev'=>'<','next'=>'>','first'=>'«','last'=>'»','theme'=>'%first% %upPage%  %prePage%  %linkPage%  %nextPage% %downPage% %end%');
		$this->assign('showpage_bot',$page->show_img());
		$limit=$page->firstRow.','.$page->listRows;
		//$data=$dao->where("((typeid={$typeid} AND cid={$this->pcid}) AND ismake=1) AND (showstart<{$now} AND showend>{$now}))")->order("pubdate DESC")->limit("$limit")->findAll();
		$data=$dao->where("($str AND $city) AND ismake=1")->order("edittime DESC, pubdate DESC")->limit("$limit")->findAll();
		$this->assign('list',$data);
		//dump($dao->getLastSql());
		//分类信息 导航
		$this->assign('city_type',$this->_get_tree(1000));
        $this->assign('classifieds_type',$this->_get_tree(1));
		
		//页面信息
		$arctype=D("Arctype");
		$info=$arctype->where("id=$typeid")->find();
		$this->assign('info',$info);
		$page=array();
		$page['title']=empty($info['seotitle'])?$info['typename'].'  -  BeingfunChina 缤纷中国':$info['seotitle'].' -'.$info['keywords'].' -  BeingfunChina 缤纷中国';
		$page['keywords']=empty($info['keywords'])?$info['typename']:$info['keywords'];
		$page['description']=empty($info['description'])?$info['typename']:$info['description'];
		$this->assign('page',$page);
		
		if($info['reid']!='1000'){
			$reinfo=$arctype->where("id={$info['reid']}")->find();
			$this->assign('reinfo',$reinfo);
		}
        $this->ads('2','list',0,$typeid);
		$this->display();
	}//end function_name
	
	/**
	 *城市指南内容页
	 *@date 2010-5-12
	 *@time 下午04:13:26
	 */
	function show() {
		//城市指南内容页
		$aid=intval($_GET['aid']);
		if(empty($aid)){
			$this->error("error: aid is null!");
		}
		/*$dao=D("CityGuideView");
		$info=$dao->where("Archives.id=$aid")->find();*/
		$dao=D("Archives");
		$info=$dao->where("id=$aid")->find();
		if($info['ismake']=='0'){
			$this->error("error: the information has been deleted!");
		}
		$info['content']=$dao->relationGet("article");
		$cname=array('bj'=>'Beijing','sh'=>'Shanghai','gz'=>'Guangzhou','sz'=>'Shenzhen');
		//if(empty($info['maps'])){
			$city=$this->_get_city('localion');
			$local='';
			if($info['zone_id'] && $info['city_id']){
				$local=$cname[$city[$info['city_id']]['cename']].' '.$city[$info['city_id']]['_zone'][$info['zone_id']]['name'].' District';
			}elseif(empty($info['zone_id']) && $info['city_id']) {
				$local=$city[$info['city_id']]['cename'];
			}
			$local=trim($info['position'].','.$local,',');
			$info['maps']=$info['contact'];
		//}
		
		$album=$this->get_album($info['id'],$info['channel']);
		if($info['picurl']){
			$info['picurl']=str_replace('../Public/Uploads','/Public/Uploads',$info['picurl']);
		}else{
			$info['picurl']=out_images($album['0'],'s');
		}
		$info['albumnum']=count($album);
		$this->assign("album",$album);
		$this->assign("group",$this->member_group($this->member_comments($info['id'],$info['channel']),"0,6"));
		$this->assign('info',$info);
		$page=array();
		$page['title']=$info['title'].'-'.$info['keywords'].' - BeingfunChina 缤纷中国';
		$page['keywords']=$info['keywords'];
		$page['description']=$info['description'];
		$this->assign('page',$page);
		
		$this->assign('dh',$this->_get_dh($info['typeid']));
		$this->ads('2','content',0,$info['typeid']);
		if($this->_is_admin()){
			$cp='<a href="/Cp/photo/info/2_'.$info['id'].'">Add Photos</a> / <a href="/Cp/my_edit_cityguide/info/2_'.$info['id'].'">edit</a> / <a href="/Cp/del_arc/info/2_'.$info['id'].'/to/'.myencode("/cglist/{$info['typeid']}.html").'">remove</a>';
			$this->assign('cp',$cp);
		}
		$this->display();
	}//end show
	
		/**
	 *检查城市选项
	 *@date 2010-6-23
	 *@time 上午10:17:39
	 */
	protected function chk_cid() {
		//检查城市选项
		$cid=Input::getVar($_GET['cid']);
		if ($cid){
			if($_SESSION['cid']){
				$this->pcid=$cid;
			}else{
				$_SESSION['cid']=$cid;
				$this->pcid=$cid;
				cookie('cid',null);
				if ($_REQUEST['remember']) {
					cookie('cid',$cid,array('expire'=>60*60*60*24*30));
				}
			}
		}else{
			//$this->_set_cid();
			$this->pcid=empty($this->cid)?0:$this->cid;
		}
	}//end chk_cid
}//end CityGuideAction