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
			$time=time();
			$dao=D("Archives");
			$condition=array();
			$condition['channel']='10';
			$condition['ismake']='1';
			$condition['showstart']=array('lt',$time);
			$condition['showend']=array('gt',$time);
			$list=$dao->where($condition)->order("showstart DESC")->limit("0,11")->findAll();
			$this->assign('list',$list);
			
			$condition['showend']=array('lt',$time);
			$old=$dao->where($condition)->order("showstart DESC")->limit("0,8")->findAll();
			$this->assign('list',$old);
			
			$this->assign('range_time',$this->range_time());
			$this->assign('class_tree',$this->_get_tree(2050));
			$this->display();
		}//end index
		
		/**
		 *活动列表页
		 *@date 2010年 05月 04日 星期二 10:57:56 CST
		 */
		function ls() {
			//活动列表页
			$this->assign('range_time',$this->range_time());
			$this->assign('class_tree',$this->_get_tree(2050));
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
		
		/**
		 *生成时间段
		 *@date 2010-6-13
		 *@time 下午03:39:35
		 */
		function range_time() {
			//生成时间段
			$t=time();
			$tarr=array();
			$day=60*60*24;
			$tarr['7']['name']='最近一周';
			$tarr['7']['st']=$t-($day*7);
			$tarr['7']['et']=$t+($day*7);
			$tarr['30']['name']='最近一月';
			$tarr['30']['st']=$t-($day*30);
			$tarr['30']['et']=$t+($day*30);
			$tarr['90']['name']='最近三月';
			$tarr['90']['st']=$t-($day*30*3);
			$tarr['90']['et']=$t+($day*30*3);
			$tarr['365']['name']='今年';
			$tarr['365']['st']=mktime(0,0,0,1,1,date('Y'));
			$tarr['365']['et']=mktime(0,0,0,1,1,date('Y')+1)-1;
			$tarr['all']['name']='全部时间';
			$tarr['all']['st']=0;
			$tarr['all']['et']=0;
			return $tarr;
		}//end range_time
	}// END EventAction
?>
