<?php
class PublicAction extends Action {
	public function _initialize() {
		import('ORG.Util.RBAC');
		import("ORG.Util.Page");//引用分页类
		import("@.Com.ajaxpage");//引用ajax分页类
		if (C('USER_AUTH_ON') && !in_array(MODULE_NAME,explode(',',C('NOT_AUTH_MODULE')))) {
			if (!RBAC::AccessDecision()) {
				//检查认证识别号
				if (!$_SESSION [C('USER_AUTH_KEY')]) {
					//跳转到认证网关
					redirect(PHP_FILE.C('USER_AUTH_GATEWAY'));
				}
				// 没有权限 抛出错误
				if (C ( 'RBAC_ERROR_PAGE' )) {
					// 定义权限错误页面
					redirect (C('RBAC_ERROR_PAGE' ));
				} else {
					if (C('GUEST_AUTH_ON' )) {
						$this->assign('jumpUrl',PHP_FILE.C('USER_AUTH_GATEWAY'));
					}
					// 提示错误信息
					$this->error ('没有权限！');
				}
			}
		}
	}

	public function checkLogin() {
		if(empty($_POST['account'])) {
			$this->error('帐号错误！');
		}elseif (empty($_POST['password'])){
			$this->error('密码必须！');
		}elseif (empty($_POST['verify'])){
			$this->error('验证码必须！');
		}
		// 登录验证码获取
		$verifyCodeStr   = $_POST['verify'];
		$verifyCodeNum   = array_flip($_SESSION['verifyCode']);
		$len	=	strlen(trim($_POST['verify']));
		for($i=0; $i<$len; $i++) {
			$verify .=  $verifyCodeNum[$verifyCodeStr[$i]];
		}
		if($verify!='0123456789'){
			$this->error('验证码错误！');
		}
		$User=M('User');
		//生成认证条件
		$map            =   array();
		$map["account"]	=	$_POST['account'];
		$map["status"]	=	array('gt',0);

		//$authInfo = $User->find($map);
		$authInfo = RBAC::authenticate($map);
		//使用用户名、密码和状态的方式进行认证
        if(false === $authInfo) {
            $this->error('帐号不存在或已禁用！');
        }else {
            if($authInfo['password'] != md5($_POST['password'])) {
            	$this->error('密码错误！');
            }
            $_SESSION[C('USER_AUTH_KEY')]	=	$authInfo['id'];
            $_SESSION['email']	=	$authInfo['email'];
            $_SESSION['loginUserName']		=	$authInfo['nickname'];
            $_SESSION['lastLoginTime']		=	$authInfo['last_login_time'];
			$_SESSION['login_count']	=	$authInfo['login_count'];
            if($authInfo['account']=='admin') {
            	$_SESSION['administrator']		=	true;
            }
            //保存登录信息
			$User	=	M('User');
			$ip		=	get_client_ip();
			$time	=	time();
            $data = array();
			$data['id']	=	$authInfo['id'];
			$data['last_login_time']	=	$time;
			$data['login_count']	=	array('exp','login_count+1');
			$data['last_login_ip']	=	$ip;
			$User->save($data);

			// 缓存访问权限
            RBAC::saveAccessList();
			$this->success('登录成功！');
		}
	}

	public function login() {
		if(!isset($_SESSION[C('USER_AUTH_KEY')])) {
			$this->display();
		}else{
			$this->redirect('/Index/index');
		}
	}

	public function logout()
	{
		if(isset($_SESSION[C('USER_AUTH_KEY')])) {
			unset($_SESSION['menu'.$_SESSION[C('USER_AUTH_KEY')]]);
			unset($_SESSION[C('USER_AUTH_KEY')]);
			unset($_SESSION['administrator']);
			$this->assign("jumpUrl",__URL__.'/login/');
			$this->success('登出成功！');
		}else {
			$this->error( '已经登出！');
		}
	}

	/**
     +----------------------------------------------------------
     * 验证码显示
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     * @throws FcsException
     +----------------------------------------------------------
     */
	function verify(){
		import("ORG.Util.Image");
		Image::showAdvVerify();
	}

	/**
     +----------------------------------------------------------
     * 取得操作成功后要返回的URL地址
     * 默认返回当前模块的默认操作
     * 可以在action控制器中重载
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
	function getReturnUrl()
	{
		return __URL__.'?'.C('VAR_MODULE').'='.MODULE_NAME.'&'.C('VAR_ACTION').'='.C('DEFAULT_ACTION');
	}

	/**
     +----------------------------------------------------------
     * 根据表单生成查询条件
     * 进行列表过滤
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param string $name 数据对象名称
     +----------------------------------------------------------
     * @return HashMap
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
	protected function _search($name='')
	{
		//生成查询条件
		if(empty($name)) {
			$name	=	$this->name;
		}
		$model	=	D($name);
		$map	=	array();
		foreach($model->getDbFields() as $key=>$val) {
			if(isset($_REQUEST[$val]) && $_REQUEST[$val]!='') {
				$map[$val]	=	$_REQUEST[$val];
			}
		}
		return $map;
	}

	/**
     +----------------------------------------------------------
     * 根据表单生成查询条件
     * 进行列表过滤
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     * @param Model $model 数据对象
     * @param HashMap $map 过滤条件
     * @param string $sortBy 排序
     * @param boolean $asc 是否正序
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
	protected function _list($model,$map,$sortBy='',$asc=true)
	{
		//排序字段 默认为主键名
		if(isset($_REQUEST['order'])) {
			$order = $_REQUEST['order'];
		}else {
			$order = !empty($sortBy)? $sortBy: $model->getPk();
		}
		//排序方式默认按照倒序排列
		//接受 sost参数 0 表示倒序 非0都 表示正序
		if(isset($_REQUEST['sort'])) {
			$sort = $_REQUEST['sort']?'asc':'desc';
		}else {
			$sort = $asc?'asc':'desc';
		}
		//取得满足条件的记录数
		$count      = $model->count($map);
		if($count>0) {
			import("ORG.Util.Page");
			//创建分页对象
			if(!empty($_REQUEST['listRows'])) {
				$listRows  =  $_REQUEST['listRows'];
			}else {
				$listRows  =  '';
			}
			$p          = new Page($count,$listRows);
			//分页查询数据
			$voList     = $model->where($map)->order($order.' '.$sort)->limit($p->firstRow.','.$p->listRows)->findAll();
			//分页跳转的时候保证查询条件
			foreach($map as $key=>$val) {
				if(is_array($val)) {
					foreach ($val as $t){
						$p->parameter	.= $key.'[]='.urlencode($t)."&";
					}
				}else{
					$p->parameter   .=   "$key=".urlencode($val)."&";
				}
			}
			//分页显示
			$page       = $p->show();
			//列表排序显示
			$sortImg    = $sort ;                                   //排序图标
			$sortAlt    = $sort == 'desc'?'升序排列':'倒序排列';    //排序提示
			$sort       = $sort == 'desc'? 1:0;                     //排序方式
			//模板赋值显示
			$this->assign('list',       $voList);
			$this->assign('sort',       $sort);
			$this->assign('order',      $order);
			$this->assign('sortImg',    $sortImg);
			$this->assign('sortType',   $sortAlt);
			$this->assign("page",       $page);
		}
		return ;
	}


	function update() {
		$model	=	D($this->name);
		if(false === $vo = $model->create()) {
			$this->error($model->getError());
		}
		$result	=	$model->save();
		if($result) {
			//成功提示
			$this->success('更新成功！');
		}else {
			//错误提示
			$this->error('更新失败！');
		}
	}

	/**
     +----------------------------------------------------------
     * 默认删除操作
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
	public function delete()
	{
		//删除指定记录
		$model        = D($this->name);
		if(!empty($model)) {
			$id         = $_REQUEST['id'];
			if(isset($id)) {
				if($model->delete($id)){
					$this->success('删除成功！');
				}else {
					$this->error('删除失败！');
				}
			}else {
				$this->error('非法操作');
			}
		}
	}



	/**
     +----------------------------------------------------------
     * 默认排序操作
     *
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     * @throws FcsException
     +----------------------------------------------------------
     */


	function sort()
	{
		$thismodel=$this->name;
		$list      =   D($thismodel);
		if(!$_REQUEST['pid']){
			$sortList  =   $list->order('seqNo asc')->findall();
		}else{
			$sortList  =   $list->where('pid='.$_REQUEST['pid'])->field('*')->order('seqNo asc')->findall();
		}
		//dump($sortList);
		$this->assign("thismodel",$thismodel);
		$this->assign("sortList",$sortList);
		$this->display('Public:sort');
		return ;
	}


	/**
     +----------------------------------------------------------
     * 默认排序保存操作
     *
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     * @throws FcsException
     +----------------------------------------------------------
     */
	function saveSort()
	{
		$seqNoList  =   $_POST['sortvaue'];
		if(!empty($seqNoList)) {
			//更新数据对象
			$thismodel=$this->name;
			$list      =   D($thismodel);
			$col    =   explode(',',$seqNoList);
			$i=1;
			foreach($col as $val) {
				$data['id'] =$val;
				$data['seqNo'] =$i;
				$list->data($data)->save();
				$i++;
			}
		}
	}

	protected function checkUser() {
		if(!isset($_SESSION[C('USER_AUTH_KEY')])) {
			$this->assign ('jumpUrl', PHP_FILE . C('USER_AUTH_GATEWAY') );
			$this->error('没有登录');
		}
	}
}
?>