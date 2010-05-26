<?php
/**
 +------------------------------------------------------------------------------
 * PublicAction控制器类
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  bi
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-2-4
 * @time  下午05:12:30
 +------------------------------------------------------------------------------
 */
class PublicAction extends CommonAction{
	
	/**
	 *处理选择城市
	 *@date 2010-5-4
	 *@time 下午03:16:36
	 */
	function index() {
		//处理选择城市
		$cid=$_GET['cid'];
		if($cid){
			$_SESSION['cid']=$cid;
			$_COOKIE['cid']=$cid;
			$this->redirect('/Index/index');
		}else{
			$this->display();
		}
	}//end index
	
	
	function login() {//登录
		if ($this->user) {
			$this->error("login");
		}
		$this->display();
	}// END login
	
	function register() {//注册
		$this->display();
	}// END reg
	
	function getpassword() {//取回密码
		$this->display();
	}// END getpassword
	
	function mpass() {//修改密码
		$this->display();
	}// END mpass
	
	function logout() {//退出
		unset($_SESSION['uid']);
		unset($_SESSION['username']);
		$this->user='';
		$this->redirect('/Index/index');
	}// END login_out
	
	function check() {
		//登录校验
		$username=trim($_POST['username']);
		$password=md5($_POST['password']);
		$dao=new Model();
		$info=$dao->Table("cdb_members")->where("username='$username'")->find();
		if ($info) {
			if ($password==$info['password']) {
				$_SESSION['uid']=$info['uid'];
				$_SESSION['username']=$info['username'];
				$_SESSION["info"]=$info;
				$this->redirect("/Cp/index");
			}else{
				$this->error("ERROR:2!");
			}
		}else{
			$this->error("ERROR:1!");
		}

	}// END check
	
	function reg_add() {//注册写入
		$data['username']=$_POST['username'];
		$data['password']=$_POST['password'];
		$data['email']=$_POST['email'];
		$data['groupid']=10;
		if ($data['password']!=$_POST['repassword'] || empty($data['password'])||empty($data['email'])||empty($data['username'])) {
			$this->error("ERROR:1!");
		}
		$data['password']=md5($data['password']);
		$dao=new Model();
		$uid=$dao->Table("cdb_members")->add($data);
		if ($uid) {
			$info=$dao->Table("cdb_members")->where("uid='$uid'")->find();
			$_SESSION['uid']=$info['uid'];
			$_SESSION['username']=$info['username'];
			$_SESSION["info"]=$info;
			$this->redirect("/Cp/index");
		}else{
			$this->error("ERROR:2!");
		}
	}// END login
	
	/**
	 *选择城市
	 *@date 2010-5-4
	 *@time 上午10:12:13
	 */
	function select_city() {
		//选择城市
		$this->display();
	}//end select_city
	
	
}//END PublicAction