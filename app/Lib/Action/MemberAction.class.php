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
		/*if (!$this->_is_login()){
			$this->redirect("/Public/login");
		}*/
		parent::_initialize();
	}//end _initialize()
	
	/**
	 *推荐资源到群组
	 *@date 2010-5-17
	 *@time 上午10:14:04
	 */
	function share() {
		//推荐资源到群组
		
	}//end share
	
	/**
	 *个人收藏夹
	 *@date 2010-5-17
	 *@time 上午10:34:17
	 */
	function favorite() {
		//个人收藏夹
		
	}//end favorite
	
	/**
	 *举报信息
	 *@date 2010-5-17
	 *@time 上午10:35:41
	 */
	function report() {
		//举报信息
		
	}//end report
	
}//end MemberAction