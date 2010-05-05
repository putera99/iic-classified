<?php
/**
 +------------------------------------------------------------------------------
 * CpAction控制器类
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  app
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-5-5
 * @time  下午03:39:52
 +------------------------------------------------------------------------------
 */
class CpAction extends CommonAction{
	function _initialize() {
		//预处理
		if (!$this->_is_login()){
			$this->redirect("/Public/login");
		}
		parent::_initialize();
	}//end _initialize()
	
	/**
	 *控制面板首页
	 *@date 2010-5-5
	 *@time 下午03:42:51
	 */
	function index() {
		//控制面板首页
		$this->display();
	}//end index
	
}//end CpAction