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
		protected $pcid='';//页面查询用的独立城市ID
		function _initialize() {
		if (intval($_GET['cid'])){
			$this->pcid=intval($_GET['cid']);
		}else{
			$this->_set_cid();
			$this->pcid=$this->cid;
		}
		parent::_initialize();
	}//end _initialize()
		
		/**
		 *活动频道首页
		 *@date 2010年 05月 04日 星期二 10:56:27 CST
		 */
		function index() {
			//活动频道首页
			
			
		}//end index
		
		/**
		 *活动列表页
		 *@date 2010年 05月 04日 星期二 10:57:56 CST
		 */
		function ls() {
			//活动列表页
			
			
		}//end ls


		
	}// END EventAction
?>
