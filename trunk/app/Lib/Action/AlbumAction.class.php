<?php
/**
 +------------------------------------------------------------------------------
 * AlbumAction控制器类
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  app
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2011-3-8
 * @time  上午09:27:00
 +------------------------------------------------------------------------------
 */
class AlbumAction extends CommonAction{
	
	/**
	 +----------------------------------------------------------
	 * 企业相册列表
	 * @date 2011-3-8 - @time 上午09:27:24
	 +----------------------------------------------------------
	 * @static
	 * @access public
	 +----------------------------------------------------------
	 * @param string 
	 +----------------------------------------------------------
	 * @return void
	 +----------------------------------------------------------
	 */
	function index() {
		//企业相册列表
		$str=explode('_', $_GET['ctg']);
		$ctgid=$str['0'];
		$dao=D("Album");
		$condition=array();
		if($str['1']){
			$id=$str['1'];
			$condition['id']=$id;
		}else{
			$condition['ctg_id']=$ctgid;
		}
		$count=$dao->where($condition)->count();
		import ( "ORG.Util.Page" );
		$page=new Page($count,16);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$limit=$page->firstRow.','.$page->listRows;
		$list=array();
		$list=$dao->where($condition)->limit($limit)->findAll();
		$data=array();
		$pic=D("Pic");
		foreach ($list as $k=>$v){
			$v["_son"]=array();
			$condition=array();
			$condition['album_id']=$v['id'];
			$v['_son']=$pic->where($condition)->limit("0,200")->findAll();
			$data[$k]=$v;
		}
		$this->assign("list",$data);
		$this->display();
	}//end index
}//end AlbumAction