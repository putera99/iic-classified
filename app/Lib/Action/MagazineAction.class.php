<?php
/**
 +------------------------------------------------------------------------------
 * MagazineAction控制器类
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  app
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-5-11
 * @time  上午10:42:16
 +------------------------------------------------------------------------------
 */
class MagazineAction extends CommonAction{
	/**
	   *杂志首页
	   *@date 2010-7-21
	   *@time 上午09:37:43
	   */
	function ls() {
		//杂志首页
		
	}//end index
	
	/**
	   *下载
	   *@date 2010-8-8
	   *@time 上午12:24:20
	   */
	function download() {
		//下载
		$vol=Input::getVar($_GET['vol']);
		if($vol<5){
			$down=array(
				'1'=>"http://iiccms.googlecode.com/files/DateCity.Vol.1.exe.zip",
				'2'=>'http://iiccms.googlecode.com/files/DateCity_VOL_2.exe.zip',
				'3'=>'http://iiccms.googlecode.com/files/DateCity_Vol3.zip',
				'4'=>'http://iiccms.googlecode.com/files/DateCity_Vol_4.zip',
			);
			$url=$down[$vol];
		}else{
			$dao=D("Magazines");
			$url=$dao->where("vol=$vol")->field("downurl")->find();
			$url=$url['downurl'];
		}
		$this->iicstat($vol,'14');
		redirect($url);
	}//end down
	
	/**
	   *接收动画留言
	   *@date 2010-7-31
	   *@time 下午04:17:09
	   */
	function guest() {
		//接收动画留言
		$username=$_REQUEST['username'];
		$password=$_REQUEST['password'];
		if($this->_is_login($username,$password)){
			$data=array();
			$data['message']="<b>".$_REQUEST['subject']."</b>";
			$data['message'].=$_REQUEST['content'];
			$data['click_6']=0;
			$data['types']=14;
			$data['xid']=$_REQUEST['version'];
			$dao=D("Comment");
			$vo=$dao->create($data);
			$vo['uid']=$this->user['uid'];
			$id=$dao->add($vo);
			if ($id) {
				echo "x=You’ve sent successfully";
			}else{
				echo "x=Comment failed";
			}
		}else{
			echo "x=You’ve sent is failed to release\n";
		}
		
	}//end guest
	
	
	/**
	   *投票
	   *@date 2010-8-2
	   *@time 下午09:09:52
	   */
	function toupiao() {
		//投票
		echo "&success=1";
	}//end function_name
	
	/**
	   *邮件列表
	   *@date 2010-8-20
	   *@time 上午10:47:07
	   */
	function maillist() {
		//邮件列表
		$dao=D("MailList");
		if($_POST['email ']){
			$this->error("Failed to subscribe!");
		}
		$vo=$dao->create();
		$this->assign("jumpUrl",'/Magazine/index');
		if($vo){
			$id=$dao->add($vo);
			if($id){
				$this->success("Subscribed successfully!");
			}else{
				$this->error("Failed to subscribe!");
			}
		}else{
			$this->error("Failed to subscribe!");
		}
		
	}//end maillist
	
	/**
   *其他文章
   *@date 2010-9-28
   *@time 下午03:04:38
   */
	protected function otherarc($cat,$vol,$order='pubdate DESC',$limit="0,10") {
		//其他文章
		$dao=D("Archives");
		$str="typeid={$cat} AND industry='{$vol}'";
		//信息列表
		$data=$dao->where("$str AND ismake=1")->order($order)->limit($limit)->findAll();
		/*if($this->_is_admin()){
			dump($dao->getLastSql());
		}*/
		if($data){
			return $data;
		}else{
			return false;
		}
	}//end other
	
	/**
	   *读取数据库的首页
	   *@date 2011-1-10 / @time 下午06:05:52
	   */
	function index() {
		//读取数据库的首页
		$vol=Input::getVar($_GET['vol']);
		$dao=D("Magazines");
		$index='';
		if(empty($vol)){
			$condition=array("showtime"=>array('lt',time()),);
			$top=$dao->where($condition)->order("id DESC")->find();
			$index=$top['vol'];
			$vol=empty($vol)?$index:$vol;
		}else{
			$condition=array("vol"=>array('eq',$vol),);
			$top=$dao->where($condition)->find();
		}
		$top['mid']=explode(",",trim($top['middleimg'],','));
		$top['small']=explode(",",trim($top['smallimg'],','));
		self::pre_and_next($vol,$dao);
		$this->assign("top",$top);
		self::all_zine($dao);
		
		$y=array();
		$y=F('dnum_'.$vol,'',APP_PATH.'/file/');
		if(empty($y)){
			$num=rand(200, 1800);
			$y=array('num'=>$num,'time'=>time());
		}
		if(($y['time']+180)<time()){
			if((date('H')>22)||(date('H')<8)){
				if($vol==$index){
					$rand=rand(10,100);
				}else{
					$rand=rand(8,80);
				}
			}else{
				if($vol==$index){
					$rand=rand(100,500);
				}else{
					$rand=rand(80,300);
				}
			}
			$time=time();
		}else{
			$rand=0;
			$time=$y['time'];
		}
		$x=array('num'=>$y['num']+$rand,'time'=>$time);
		F('dnum_'.$vol,$x,APP_PATH.'/file/');
		$this->assign('dnum',$x);
		
		$this->assign("group",$this->member_group($this->member_comments($vol,14),"0,6"));
		
		$page=array(
			'title'=>$top['seotitle'],
			'keywords'=>$top['keywords'],
			'description'=>$top['description']
		);
		
		
		$this->assign('page',$page);
		$this->assign("otherarc",$this->otherarc(3001,'vol'.$vol));
		
		
		$this->display("index_2");
	}//end index2
	
	/**
	   *获取上一期和下一期的图片
	   *@date 2011-1-10 / @time 下午09:09:58
	   */
	function pre_and_next($vol,$dao) {
		//获取上一期和下一期的图片
		$other=array();
		$time=time();
		$other['pre']=$dao->where("vol=$vol-1 AND showtime<$time")->field("smallimg")->find();
		$other['netx']=$dao->where("vol=$vol+1 AND showtime<$time")->field("smallimg")->find();
		$other['pre']=empty($other['pre']['smallimg'])?'':explode(",",$other['pre']['smallimg']);
		$other['next']=empty($other['netx']['smallimg'])?'':explode(",",$other['netx']['smallimg']);
		$this->assign("other",$other);
		unset($other);
	}//end pre_and_next
	
	/**
	   *获取已经发布的电子杂志
	   *@date 2011-1-10 / @time 下午09:52:16
	   */
	function all_zine($dao) {
		//获取已经发布的电子杂志
		$all_zine=array();
		$condition=array("showtime"=>array('lt',time()),);
		$all_zine=$dao->where($condition)->field("id,title,vol,cover,cover_s")->order("id DESC")->findAll();
		$this->assign("all_zine",$all_zine);
		unset($all_zine);
	}//end all_zine
}//end MagazineAction