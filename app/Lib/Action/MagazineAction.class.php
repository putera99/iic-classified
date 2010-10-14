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
	function index() {
		//杂志首页
		$vol=Input::getVar($_GET['vol']);
		$vol=empty($vol)?'2':$vol;
		if($vol=='1'){
			$y=array();
			$y=F('dnum','',APP_PATH.'/file/');
			if(empty($y)){
				$y=array('num'=>1800,'time'=>time());
			}
			if(($y['time']+180)<time()){
				if((date('H')<22)&&(date('H')>8)){
					$rand=rand(10,100);
				}else{
					$rand=rand(100,500);
				}
				$time=time();
			}else{
				$rand=0;
				$time=$y['time'];
			}
			$x=array('num'=>$y['num']+$rand,'time'=>$time);
			F('dnum',$x,APP_PATH.'/file/');
			$this->assign('dnum',$x);
			
			$this->assign("group",$this->member_group($this->member_comments($vol,14),"0,6"));
			$tpl='index'.$vol;
		}else{
			$y=array();
			$y=F('dnum_'.$vol,'',APP_PATH.'/file/');
			if(empty($y)){
				$y=array('num'=>1800,'time'=>time());
			}
			if(($y['time']+180)<time()){
				if((date('H')<22)&&(date('H')>8)){
					$rand=rand(10,100);
				}else{
					$rand=rand(100,500);
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
			$tpl='index'.$vol;
		}
		$this->assign('vol',$vol);
		$page=array(
			'1'=>array(
				'title'=>'DateCity first issue. 电子杂志,第一期 - BeingfunChina 缤纷中国',
				'keywords'=>'DateCity,first,issue,Magazine',
				'description'=>'Date City vol.1 Introduction: August DateCity will feature on Christianity in China, with the theme of Different Land, Same Pray. DateCity will brief you on the development history of Christianity in China while focus on introducing some local Christianity churches with detailed worship time, activities and transportation guidance, etc. As the Expo is well on its way, we’ll also show you some fantastic scenic spots around Shanghai like Wuzhen and Zhouzhuang, so as to make your China trip more fulfilling. In Talk, three young foreigners will tell us about their life in China and their Chinese friends. They will share funny stories and funny people. You can also expect some breathtaking photos in Vision, which always aims to offer you a visual feast and a special feeling. '
			),
			'2'=>array(
				'title'=>'DateCity Vol.2 电子杂志,第二期- BeingfunChina 缤纷中国',
				'keywords'=>'DateCity,Magazine,缤纷中国,BeingfunChina,电子杂志',
				'description'=>'DateCity Vol.2 Introduction: September DateCity will feature on films, with the theme of Old Film, New Interpretation. The editors have carefully selected five films that could display the real situation of China. We hope that after reading this feature, our foreign readers could have a deeper understanding of China, especially China\'s culture, traditional values and Chinese people\'s typical characters. In Walk, we\'ll embark on a memorable journey called Discover the Last Shangri-la. Starting from Chengdu, DateCity will guide you all the way to our destination – Daocheng. On the way to Daocheng, you can visit Land of Beauties (Danba), the Paradise for Photographers (Xinduqiao) and the Red Grassland, etc. Detailed travel guidance will also be provided. In Vision, precious old advertisements in Shanghai will be presented. We believe these old ads will give you a shock. Finally, in Talk, three foreign teachers are going to share their interesting experience in China with us.'
			),
		);
		
		$this->assign("otherarc",$this->otherarc(3001,'vol'.$vol));
		$this->assign('page',$page[$vol]);
		$this->display($tpl);
	}//end index
	
	/**
	   *下载
	   *@date 2010-8-8
	   *@time 上午12:24:20
	   */
	function download() {
		//下载
		$vol=Input::getVar($_GET['vol']);
		$down=array(
			'1'=>"http://iiccms.googlecode.com/files/DateCity.Vol.1.exe.zip",
			//'2'=>'http://iiccms.googlecode.com/files/DateCity.VOL.2.zip'
			'2'=>'http://iiccms.googlecode.com/files/DateCity_VOL_2.exe.zip',
		);

		$url=$down[$vol];
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
		$username=$_REQUEST['username'];
		$username=$_REQUEST['username'];
		echo "&success=0";
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
}//end MagazineAction