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
			$url='';
			$title=$_GET['title']?'/'.$_GET['title']:'';
			if ($_REQUEST['remember']) {
				cookie('cid',$cid,array('expire'=>60*60*60*24*30));
			}
			if(empty($_REQUEST['to'])){
				$url=array_search($cid,$this->cityname);
				redirect("/index/".$url.".html");
			}else{
				$url=mydecode($_REQUEST['to']);
				//dump($_REQUEST);
				redirect($url.'/'.array_search($cid,$this->cityname).$title.'.html');
			}
		}else{
			$this->display("Public:select_city");
		}
	}//end index
	
	
	function login() {//登录
		if ($this->user) {
			$this->error("You’ve already logged in.");
		}
		if($_REQUEST['to']){
			$this->assign('to',$_REQUEST['to']);
		}
		srand((double)microtime()*1000000);
		$rand=rand(1,16);
		$this->assign('rand',$rand);
		$this->display();
	}// END login
	
	function register() {//注册
		$page=array();
		$page['title']='Register with Beingfunchina to get yourself connected now! If you are already a member,please log in directly. Beingfunchina: Being fun in China, start with us!';
		$page['keywords']='Register,Beingfunchina,connected,already,member,directly';
		$page['description']='Register with Beingfunchina to get yourself connected now! If you are already a member,please log in directly.Beingfunchina is an English website providing practical and localized information, supporting free posts of classifieds and facilitating interpersonal communication for foreigners in China. Our channels include City Guide, Classifieds, Feature Columns, China Fairs, Events, E-magazines and Groups. At present, our target cities include Beijing, Shanghai, Guangzhou and Shenzhen. To explore more cities is within our future development plan.';
		$this->assign('page',$page);
		$this->display();
	}// END reg
	
	function getpassword() {//取回密码
		$username=Input::getVar($_POST['username']);
		$email=Input::getVar($_POST['email']);
		if($username){
			$dao=D("Members");
			$info=array();
			$info=$dao->where("username='$username'")->find();
			if($info){
				if($email==$info['email']){
					
					$repw=substr(md5(time()),0,16);
					$data=array();
					$data['repw']=$repw;
					$dao->where("id={$info['id']}")->save($data);
					$str=$info['id'].'-_-'.$repw;
					$repw=myencode($str);
					$Title="The massage is from Beingfunchina, please get your password back";
					$Content="Hi,\n\t <br>Your ID on Beingfunchina.com is: \"{$info['username']}\" \n<br>(ID partially hidden for your privacy) \n \n \n<br>";
					$Content.="You recently requested a new password to sign in to your account. To select a new password, click on the link below:\n\n";
					$Content.="<br><br><A HREF='http://www.beingfunchina.com/Public/repassword/repw/{$repw}.html' target='_blank'>http://www.beingfunchina.com/Public/repassword/repw/{$repw}.html</A>\n <br>";
					$Content.="This request was made on ".date("F M, Y H:iA e",time()).". \n<br>";
					$Content.="For security reasons, the password reset link will expire at ".date("F M, Y H:iA e",mktime(date("H"),date("i"),0,date("m"),date("d")+3))." or after you reset your password. \n<br>";
					$Content.="Please do not reply to this message. Mail sent to this address cannot be answered. If you have any problems in using our service, please feel free to contact us by sending mail to beingfunchina365@gmail.com \n <br>";
					$Content.="Regards,\n <br>Beingfunchina Account Services.";
					/*if(!C('MAILSERVER')||!C('MAILPORT')||!C('MAILID')||!C('MAILPW')){
						$this->error("Please administrator to set up mail server");
					}
					import("@.COM.Smtp");
					//require_once(BeingfunChina_PATH."inc/class.mail.php");
					$smtp = new smtp(C('MAILSERVER'),C('MAILPORT'),true,C('MAILID'),C('MAILPW'));
					$smtp->debug = false;
					if($smtp->sendmail($info['email'],C("MAILID"), $Title, $Content, "HTML")){
						$msg="New password has been successfully sent to your mailbox, please watch for!";
					}else{
						$msg="Failed to send e-mail. This may be due to wrong e-mail address or problems of the mail server.";
					}*/
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
					$headers .= 'From: beingfunchina@gmail.com' . "\r\n" ;
					//$headers .= 'Reply-To: webmaster@example.com' . "\r\n";
					if(mail($info['email'], $Title, $Content,$headers)){
						
						$this->assign("jumpUrl","/Public/login.html");
						$this->success("New password has been successfully sent to your mailbox, please note!");
					}else{
						$msg="Failed to send e-mail. This may be due to wrong e-mail address or problems of the mail server.";
					}
					$this->assign('msg',$msg);
				}else{
					$this->error("Wrong E-mail!");
				}
			}else{
				$this->error("Wrong Username!");
			}
		}
		$this->display();
		
	}// END getpassword
	
	/**
	   *修改取回密码
	   *@date 2010-9-29
	   *@time 下午04:25:17
	   */
	function repassword() {
		//修改取回密码
		$repw=Input::getVar($_REQUEST['repw']);
		$data=array();
		$data['password']=Input::getVar($_POST['password']);
		$repassword=Input::getVar($_POST['repassword']);
		if($data['password']){
			//dump(mydecode($repw));
			$repw=explode('-_-',mydecode($repw));
			//dump($repw);
			if ($data['password']!=$repassword || empty($data['password'])) {
				$this->error("Wrong parameter!");
			}
			$condition=array();
			$condition['id']=$repw['0'];
			$condition['repw']=$repw['1'];
			$dao=D("Members");
			$info=$dao->where($condition)->find();
			//dump($dao->getLastSql());
			if($info){
				$data['password']=md5($data['password']);
				$data['repw']='';
				$dao->where($condition)->save($data);
				$this->assign("jumpUrl","/Public/login.html");
				$this->success("you have sucessfully reset your password,please login.");
			}else{
				$this->error("Wrong parameter!");
			}
		}elseif(empty($repw)){
			$this->error("Wrong parameter!");
		}else{
			$this->assign('repw',$repw);
			$this->display();	
		}
	}//end repassword
	
	function logout() {//退出
		unset($_SESSION['uid']);
		unset($_SESSION['username']);
		unset($_SESSION['info']);
		$this->user='';
		if(empty($_REQUEST['to'])){
			redirect('/index.html');
		}else{
			$url=mydecode($_REQUEST['to']);
			redirect($url);
		}
	}// END login_out
	
	function check() {
		//登录校验
		$username='';
		$password='';
		$username=trim($_POST['username']);
		$password=md5(trim($_POST['password']));
		$dao=D("Members");
		$info=array();
		$info=$dao->where("username='$username'")->find();
		//dump($dao->getLastSql());
		if ($info){
			if($password==$info['password']) {
				unset($_SESSION['uid']);
				unset($_SESSION['username']);
				unset($_SESSION['info']);
				
				$_SESSION['uid']=$info['id'];
				$_SESSION['username']=$info['username'];
				$info['avatar']=avatar($info['avatar']);
				$_SESSION["info"]=$info;
				if ($_REQUEST['is_ajax']) {
					$this->ajaxReturn("{$info['uid']}",'You’ve already logged in.','1');
				}else{
					if(empty($_REQUEST['to'])){
						redirect("/Cp/index");
					}else{
						$url=mydecode($_REQUEST['to']);
						redirect($url);
					}
				}
			}else{
				if ($_REQUEST['is_ajax']) {
					$this->ajaxReturn("0",'Wrong password!','0');
				}else{
					$this->error("Wrong password!!");
				}
			}
		}else{
			if ($_REQUEST['is_ajax']) {
					$this->ajaxReturn("0",'Wrong Username!','0');
				}else{
					$this->error("Wrong Username!");
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
		if($_POST['policy']!='1'){
			$this->error("You should agree with the Terms of Service to finish registration.");
		}
		$dao=D("Members");
		$data=array();
		if(!$dao->create()){
			$this->error($dao->getError());
		}
		$data['username']=Input::getVar($_POST['username']);
		$data['password']=Input::getVar($_POST['password']);
		$data['email']=Input::getVar($_POST['email']);
		$data['groupid']=10;
		if ($data['password']!=$_POST['repassword'] || empty($data['password'])||empty($data['email'])||empty($data['username'])) {
			$this->error("Wrong parameter!");
		}
		$data['password']=md5($data['password']);
		$data['bday']=Input::getVar($_POST['bdayyear']).'-'.Input::getVar($_POST['birthmonth']).'-'.Input::getVar($_POST['birthday']);
		$data['nationality']=Input::getVar($_POST['nationality']);
		$address=Input::getVar($_POST['address']);
		$data['address']=empty($address)?Input::getVar($_POST['address2']):$address;
		$data['nationality']=Input::getVar($_POST['nationality']);
		$data['gender']=Input::getVar($_POST['gender']);
		$data['fname']=Input::getVar($_POST['first_name']);
		$data['lname']=Input::getVar($_POST['last_name']);
		
		$uid=$dao->add($data);
		//dump($dao->getLastSql());
		if ($uid) {
			$info=$dao->where("id='$uid'")->find();
			$_SESSION['uid']=$info['id'];
			$_SESSION['username']=$info['username'];
			$info['avatar']=avatar($info['avatar']);
			$_SESSION["info"]=$info;
			if(empty($_REQUEST['to'])){
				redirect("/Cp/index");
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
		$this->display("Public:select_city");
	}//end select_city
	
	/**
	   *检查资料是否重复
	   *@date 2010-9-19
	   *@time 下午05:49:28
	   */
	function chk_info() {
		//检查资料是否重复
		$f=$_REQUEST['fld'];
		$val=$_REQUEST['val'];
		$dao=D("Members");
		$condition=array($f=>$val);
		$count=$dao->where($condition)->count();
		if($count==0){
			$this->ajaxReturn("1",'ok!','1');
		}else{
			$this->ajaxReturn("0",'User name "'.$val.'" already existed. Please select another one.','0');
		}
	}//end chk_info
	
	/**
	   *天气预报
	   *@date 2010-11-22
	   *@time 下午02:57:49
	   */
	function weather_forecast() {
		//天气预报
		//$_SESSION['cid']=empty($_GET['cid'])?$_SESSION['cid']:$_GET['cid'];
		$cityname = array (1 => array ('GUANGZHOU', 'CH006' ), 2 => array ('BEIJING', 'CH002' ), 3 => array ('SHANGHAI', 'CH024' ), 4 => array ('SHENZHEN', 'CH006' ) );
		//$incity = empty ( $_SESSION['cid']) ? $cityname ['2'] : $cityname [$_SESSION['cid']];
		$this->assign ( 'city_name', $cityname );
		$this->display();
	}//end weather_forecast
	
	/**
   *邮件列表
   *@date 2010-8-20
   *@time 上午10:47:07
   */
	function maillist() {
		//邮件列表
		if($_POST['submit']){
			$dao=D("MailList");
			$mail=trim($_POST['email ']);
			$this->assign("jumpUrl",'/index.html');
			if($mail){
				$this->error("Failed to subscribe!");
			}
			$vo=$dao->create();
			$condition=array();
			$condition['email']=$mail;
			$info=$dao->where($condition)->find();
			if($info){
				$this->error("You've been sucessfully subscribe to Beingfunchina newsletter.");
			}
			if($vo){
				$vo['types']='news';
				$vo['status']='0';
				$id=$dao->add($vo);
				if($id){
					//发送验证邮件
					$repw=$vo['mail'].'||'.$vo['ctime'].'||'.$vo['status'];
					$repw=myencode($repw);
					$Title="Confirmation Letter-Beingfunchina Newsletter";
					$Content='Hello, <br>Welcome, and thank you for becoming a Beingfunchina.com Insider. <br>';
					$Content.="you have subscribed {$vo['mail']} to Beingfunchina.com's newsletter. <br>";
					$Content.='To confirm this subscription, please click the link below:<br>';
					$Content.="<br><br><A HREF='http://www.beingfunchina.com/Public/chmail/key/{$repw}.html' target='_blank'>http://www.beingfunchina.com/Public/chmail/key/{$repw}.html</A>\n <br>";
					$Content.='If you do not wish to subscribe, do nothing. <br>';
					$Content.='You will be automatically removed. <br>';
					$Content.='Regards,Beingfunchina.com<br>';
					/*if(!C('MAILSERVER')||!C('MAILPORT')||!C('MAILID')||!C('MAILPW')){
						$this->error("Please administrator to set up mail server");
					}
					import("@.COM.Smtp");
					//require_once(BeingfunChina_PATH."inc/class.mail.php");
					$smtp = new smtp(C('MAILSERVER'),C('MAILPORT'),true,C('MAILID'),C('MAILPW'));
					$smtp->debug = false;
					if($smtp->sendmail($info['email'],C("MAILID"), $Title, $Content, "HTML")){
						$msg="New password has been successfully sent to your mailbox, please watch for!";
					}else{
						$msg="Failed to send e-mail. This may be due to wrong e-mail address or problems of the mail server.";
					}*/
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
					$headers .= 'From: beingfunchina@gmail.com' . "\r\n" ;
					//$headers .= 'Reply-To: webmaster@example.com' . "\r\n";
					if(mail($vo['mail'], $Title, $Content,$headers)){
						
						//$this->assign("jumpUrl","/Public/login.html");
						$text="<h1>Thank you!</h1><p>We appreciate your time. Check your e-mail inbox for the latest newsletter from Beingfunchina.</p>"; 
						$this->success($text);
					}else{
						$this->error("Oops! Please try again.");
					}
					//发送验证邮件
				}else{
					$this->error("Oops! Please try again.");
				}
			}else{
				$this->error("Oops! Please try again.");
			}
		}else{
			$this->display();
		}
	}//end maillist
	
	
	/**
	 +----------------------------------------------------------
	 * 验证订阅的邮件或取消订阅
	 * @date 2011-3-11 - @time 下午05:54:37
	 +----------------------------------------------------------
	 * @static
	 * @access public
	 +----------------------------------------------------------
	 * @param string 
	 +----------------------------------------------------------
	 * @return void
	 +----------------------------------------------------------
	 */
	function chmail() {
		//验证订阅的邮件或取消订阅
		$key=mydecode($_GET['key']);
		$key=explode("||", $key);
		$dao=D("MailList");
		$condition=array();
		$condition['email']=$key['0'];
		$condition['ctime']=$key['1'];
		$condition['status']=$key['2'];
		$info=$dao->where($condition)->find();
		$this->assign("jumpUrl",'/index.html');
		if($info){
			$status=$condition['status']=='1'?'0':'1';
			$dao->where($condition)->save(array("status"=>$status));
			$this->success("You request has been sent.");
		}elseif($condition['status']=='1'){
			$this->error("You've been sucessfully subscribe to Beingfunchina newsletter.");
		}else{
			$this->error("You've been unsubscribed sucessfully.Please do not repeat.");
		}
		
	}//end chmail
}//END PublicAction