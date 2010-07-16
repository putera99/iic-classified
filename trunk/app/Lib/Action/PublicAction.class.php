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
			cookie('cid',null);
			if ($_REQUEST['remember']) {
				cookie('cid',$cid,array('expire'=>60*60*60*24*30));
			}
			
			if(empty($_REQUEST['to'])){
				$this->redirect('/Index/index');
			}else{
				$url=mydecode($_REQUEST['to']);
				//echo $url;
				//dump($_SESSION);
				$this->redirect($url);
			}
		}else{
			$this->display();
		}
	}//end index
	
	
	function login() {//登录
		if ($this->user) {
			$this->error("is login");
		}
		if($_REQUEST['to']){
			$this->assign('to',$_REQUEST['to']);
		}
		srand((double)microtime()*1000000);
		$rand=rand(1,8);
		$this->assign('rand',$rand);
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
		if(empty($_REQUEST['to'])){
			$this->redirect('/Index/index');
		}else{
			$url=mydecode($_REQUEST['to']);
			$this->redirect($url);
		}
	}// END login_out
	
	function check() {
		//登录校验
		$username=trim($_POST['username']);
		$password=md5(trim($_POST['password']));
		/*$_SESSION['uid']='3594';
		$_SESSION['username']=$username;
		$this->ajaxReturn("3594",'登录成功','1');*/
		$dao=new Model();
		$info=$dao->Table("cdb_members")->where("username='$username'")->find();
		if ($info) {
			if($password==$info['password']) {
				$_SESSION['uid']=$info['uid'];
				$_SESSION['username']=$info['username'];
				$_SESSION["info"]=$info;
				if ($_REQUEST['is_ajax']) {
					$this->ajaxReturn("{$info['uid']}",'You’ve already logged in','1');
				}else{
					if(empty($_REQUEST['to'])){
						$this->redirect("/Cp/index");
					}else{
						$url=mydecode($_REQUEST['to']);
						$this->redirect($url);
					}
				}
			}else{
				if ($_REQUEST['is_ajax']) {
					$this->ajaxReturn("0",'Wrong password!','0');
				}else{
					echo $password;
					$this->error("Wrong password!!");
				}
			}
		}else{
			if ($_REQUEST['is_ajax']) {
					$this->ajaxReturn("0",'Wrong user’s name!','0');
				}else{
					$this->error("Wrong user’s name!!");
				}
		}

	}// END check
	
	/**
	 *ajax检查是否登录
	 *@date 2010-6-8
	 *@time 下午05:17:23
	 */
	function ajax_is_login() {
		//ajax检查是否登录
		$user=$this->_is_login();
		if ($user){
			$this->ajaxReturn($user,'You’ve already logged in!','1');
		}else {
			$this->ajaxReturn("0",'Log in please.','0');
		}
		
	}//end ajax_is_login
	
	function reg_add() {//注册写入
		$data['username']=$_POST['username'];
		$data['password']=$_POST['password'];
		$data['email']=$_POST['email'];
		$data['groupid']=10;
		if ($data['password']!=$_POST['repassword'] || empty($data['password'])||empty($data['email'])||empty($data['username'])) {
			$this->error("Wrong parameter!");
		}
		$data['password']=md5($data['password']);
		$dao=new Model();
		$uid=$dao->Table("cdb_members")->add($data);
		if ($uid) {
			$info=$dao->Table("cdb_members")->where("uid='$uid'")->find();
			$_SESSION['uid']=$info['uid'];
			$_SESSION['username']=$info['username'];
			$_SESSION["info"]=$info;
			if(empty($_REQUEST['to'])){
				$this->redirect("/Cp/index");
			}else{
				$url=mydecode($_REQUEST['to']);
				$this->redirect($url);
			}
		}else{
			$this->error("Wrong parameter!!");
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