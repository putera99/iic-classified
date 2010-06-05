<?php
/**
 +------------------------------------------------------------------------------
 * GroupAction控制器类
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  bi
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010年 05月 04日 星期二 11:19:55 CST +------------------------------------------------------------------------------
 */
class GroupAction extends CommonAction{
	function _initialize(){
		parent::_initialize();
		parent::_is_login();
	}
	
	/**
	 *群组首页
	 *@date 2010年 05月 04日 星期二 11:21:11 CST
	 */
	function index() {
		//群组首页
		$this->assign('cat',$this->_get_mtag());
		$this->assign('hot',$this->_get_group('hot'));
		$this->assign('new',$this->_get_group('new'));
		$this->display();
	}//end index

	/**
	 *群组列表页
	 *@date 2010-6-4
	 *@time 下午03:56:54
	 */
	function ls() {
		//群组列表页
		$condition=array();
		if (intval($_GET['id'])) {
			$condition['cat_id']=intval($_GET['id']);
		}
		if ($_POST['kw']) {
			$condition['groupname']=array("like","%{$_POST['kw']}%");
		}
		if(empty($condition)){
			$this->error('参数错误！');
		}
		$dao=D("Group");
		$this->display();
	}//end ls
	
	/**
	 *群组页面
	 *@date 2010-6-4
	 *@time 下午03:57:30
	 */
	function show() {
		//群组页面
		
	}//end show
	
	
	/**
	 *话题列表页
	 *@date 2010-6-4
	 *@time 下午03:59:33
	 */
	function topic() {
		//话题列表页
		
	}//end topic
	
	
	/**
	 *话题页面
	 *@date 2010-6-4
	 *@time 下午03:57:52
	 */
	function thread(){
		//话题页面
		
	}//end thread
	
	/**
	 *群组收藏
	 *@date 2010-6-4
	 *@time 下午04:00:35
	 */
	function collection() {
		//群组收藏
		
	}//end collection
	
	/**
	 *群内成员列表
	 *@date 2010-6-4
	 *@time 下午04:02:01
	 */
	function members() {
		//群内成员
		
	}//end members
	
	/**
	 *搜索群组
	 *@date 2010-6-4
	 *@time 下午04:36:18
	 */
	function so() {
		//搜索群组
		$this->display();
	}//end add_group
	
	/**
	 *增加群组
	 *@date 2010-6-4
	 *@time 下午04:36:53
	 */
	function add_group_check() {
		//增加群组
		
	}//end add_group_check
	
	/**
	 *获取制定类下的子类信息
	 *@date 2010-6-5
	 *@time 上午11:54:24
	 */
	protected function _get_mtag($pid='1'){
		//获取制定类下的子类信息
		$dao=D("MtagCat");
		$condition=array();
		$condition['pid']=$pid;
		$arr=$dao->where($condition)->order("num asc")->findAll();
		$data=array();
		foreach ($arr as $v){
			$data[$v['id']]=$v;
			$son=$dao->where("pid={$v['id']}")->order("num asc")->findAll();
			if($son){
				$son_data=array();
				foreach ($son as $s){
					$son_data[$s['id']]=$s;
				}
			$data[$v['id']]['_son']=$son_data;
			}
		}
		return $data;
	}//end _get_mtag
	
	
	/**
	*获取热点群组
	*@date Sat Jun 05 16:48:56 CST 2010
	*@time 16:48:56
	*/
	protected function _get_group($mode="",$limit="0,6"){
		//获取热点群组
		$dao=D("Group");
		if ($mode=='hot') {
			$str="threadnum>0 AND viewperm=0";
			$order="postnum,lasttime DESC";
		}elseif($mode=="new") {
			$str="";
			$order="ctime DESC";
		}
		$data=$dao->where($str)->order($order)->limit($limit)->findAll();
		dump($dao->getlastSql());
		return $data;
	}//end _get_hot
	
}// END GroupAction

?>
