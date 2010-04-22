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
	function login() {//登录
		$this->display();
	}// END login
	
	function reg() {//注册
		$this->display();
	}// END reg
	
	function getpassword() {//取回密码
		$this->display();
	}// END getpassword
	
	function mpass() {//修改密码
		$this->display();
	}// END mpass
	
	function login_out() {//退出
		unset($_SESSION['uid'],$_SESSION['username']);
		$this->user='';
		$this->display();
	}// END login_out
	
	function check() {//登录校验
		$this->display('login');
	}// END check
	
	function reg_add() {//注册写入
		$this->display();
	}// END login
	
	public function verify(){
		//verify验证码
		if(isset($_REQUEST['adv'])) {
        	Image::showAdvVerify();
        }else {
        	Image::buildImageVerify();
        }
	}//verify function END
}//END PublicAction