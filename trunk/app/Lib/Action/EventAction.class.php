<?php
	/**
	 +------------------------------------------------------------------------------
	 * EventAction控制器类
	 +------------------------------------------------------------------------------
	 * @category   SubAction
	 * @package  bi
	 * @subpackage  Action
	 * @author   朝闻道 <hydata@gmail.com>
	 * @date 2010年 05月 04日 星期二 10:51:37 CST 
	 +------------------------------------------------------------------------------
	 */
	class EventAction extends CommonAction{
		function _initialize() {
			parent::_initialize();
		}//end _initialize()
		
		/**
		 *活动频道首页
		 *@date 2010年 05月 04日 星期二 10:56:27 CST
		 */
		function index() {
			//活动频道首页
			
			$this->display();
		}//end index
		
		/**
		 *活动列表页
		 *@date 2010年 05月 04日 星期二 10:57:56 CST
		 */
		function ls() {
			//活动列表页
			
			$this->display();
		}//end ls

		/**
		 *活动内容页
		 *@date 2010-6-10
		 *@time 下午03:28:48
		 */
		function show() {
			//活动内容页
			
			$this->display();
		}//end show
		
	}// END EventAction
?>
