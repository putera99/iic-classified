<?php
/**
 +------------------------------------------------------------------------------
 * ArcAction控制器类
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  app
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-6-3
 * @time  上午11:39:42
 +------------------------------------------------------------------------------
 */
class ArcAction extends CommonAction{
	/**
	 *文章列表
	 *@date 2010-6-3
	 *@time 下午12:06:18
	 */
	function ls() {
		//文章列表
		$typeid=intval($_GET['id']);
		$arctype=D("Arctype");
		$info=$arctype->where("id=$typeid")->find();
		$condition=array();
		if($typeid=='3001'){
			$condition['typeid']=$typeid;
			if(!empty($_GET['type'])){
				$condition['itype']=$_GET['type'];
			}
		}elseif($info['ispart']==1){
			$small=$arctype->where("reid=$typeid")->field("id")->findAll();
			$str='';
			foreach ($small as $v){
				$str.=$v['id'].',';
			}
			$condition['typeid']=array('IN',trim($str,','));
		}else{
			$condition['typeid']=$typeid;
		}
		$condition['ismake']='1';
		//信息列表
		$now=time();
		import("ORG.Util.Page");
		$dao=D("Archives");
		//$count=$dao->where("((typeid={$typeid} AND (cid={$this->pcid}) OR cid=0) AND ismake=1) AND (showstart<{$now} AND showend>{$now})")->order("pubdate DESC")->count();
		$count=$dao->where($condition)->order("pubdate DESC")->count();
		$page=new Page($count,10);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$page->config=array('header'=>'','prev'=>'<','next'=>'>','first'=>'«','last'=>'»','theme'=>' %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage_bot',$page->show_img());
		$limit=$page->firstRow.','.$page->listRows;
		//$data=$dao->where("((typeid={$typeid} AND cid={$this->pcid}) AND ismake=1) AND (showstart<{$now} AND showend>{$now}))")->order("pubdate DESC")->limit("$limit")->findAll();
		$data=$dao->where($condition)->order("pubdate DESC")->limit("$limit")->findAll();
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
		$page['title']=empty($info['seotitle'])?$info['typename'].'  -  BeingfunChina 缤纷中国':$info['seotitle'].'  -  BeingfunChina 缤纷中国';
		$page['keywords']=empty($info['keywords'])?$info['typename']:$info['keywords'];
		$page['description']=empty($info['description'])?$info['typename']:$info['description'];
		$this->assign('page',$page);
		
		if($info['reid']!='1000'){
			$reinfo=$arctype->where("id={$info['reid']}")->find();
			$this->assign('reinfo',$reinfo);
		}
		$this->assign('group',$this->_get_group('new','0,6'));
		$this->display();
	}//end ls
	
	/**
	 *文章内容页
	 *@date 2010-6-3
	 *@time 下午12:06:40
	 */
	function show() {
		//文章内容页
		$aid=intval($_GET['aid']);
		$ename=$_REQUEST['ename'];
		if(empty($aid) && empty($ename)){
			$this->error("error: aid is null!");
		}
		$condition=array();
		if(!empty($ename)){
			$condition['filename']=$ename;
		}
		if(!empty($aid)){
			$condition['id']=$aid;
		}
		
		$dao=D("Archives");
		$info=$dao->where($condition)->find();
		//dump($dao->getLastSql());
		if(empty($info)){
			$this->error("is null!");
		}
		//dump($info);
		if(!empty($info['reurl'])&&$info['reurl']!='http://'){
			redirect($info['reurl']);
		}
		$info['content']=$dao->relationGet("arc");
		
		$this->assign('info',$info);
		$page=array();
		$page['title']=$info['title'].' - BeingfunChina 缤纷中国';
		$page['keywords']=$info['keywords'];
		$page['description']=$info['description'];
		$this->assign('page',$page);
		
		$this->assign('dh',$this->_get_dh($info['typeid']));
		$this->assign('group',$this->_get_group('new','0,6'));
		$this->assign("otherarc",$this->otherarc($info['typeid']));
		$this->display();
	}//end show
	
	/**
	   *其他文章
	   *@date 2010-9-28
	   *@time 下午03:04:38
	   */
	protected function otherarc($cat,$order='pubdate DESC',$limit="0,5") {
		//其他文章
		$dao=D("Archives");
		$arctype=D("Arctype");
		$info=$arctype->where("id=$cat")->find();
		
		if($info['ispart']==1){
			$small=$arctype->where("reid=$cat")->field("id")->findAll();
			$str='';
			foreach ($small as $v){
				$str.=$v['id'].',';
			}
			$str='typeid IN ('.trim($str,',').')';
		}else{
			$str="typeid={$cat}";
		}
		//信息列表
		$now=time();
		$data=$dao->where("$str AND ismake=1")->order($order)->limit($limit)->findAll();
		//dump($dao->getLastSql());
		if($data){
			return $data;
		}else{
			return false;
		}
	}//end other
	

}//end ArcAction