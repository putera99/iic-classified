<?php
/**
 +------------------------------------------------------------------------------
 * MemberAction控制器类 管理登录后才能使用的操作
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  app
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-5-6
 * @time  上午11:43:32
 +------------------------------------------------------------------------------
 */
class MemberAction extends CommonAction{
	function _initialize() {
		//预处理
		if (!$this->_is_login()){
			$this->redirect("/Public/login");
		}
		parent::_initialize();
	}//end _initialize()
	
	
}//end MemberAction