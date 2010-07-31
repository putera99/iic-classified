<?php
/**
 +------------------------------------------------------------------------------
 * MagazineAction控制器类
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  app
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-5-11
 * @time  上午10:42:16
 +------------------------------------------------------------------------------
 */
class MagazineAction extends CommonAction{
	/**
	   *杂志首页
	   *@date 2010-7-21
	   *@time 上午09:37:43
	   */
	function index() {
		//杂志首页
		$this->display();
	}//end index
	
	/**
	   *接收动画留言
	   *@date 2010-7-31
	   *@time 下午04:17:09
	   */
	function guest() {
		//接收动画留言
		if(!empty($_POST)) S('swf',$_POST,60);
		dump(S('swf'));
		dump($_POST);
	}//end guest
}//end MagazineAction