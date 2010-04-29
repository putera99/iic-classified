<?php
/**
 +------------------------------------------------------------------------------
 * ClassifiedsAction控制器类
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  app
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-4-29
 * @time  上午09:57:29
 +------------------------------------------------------------------------------
 */
class ClassifiedsAction extends CommonAction{
	/**
	   *分类信息频道页
	   *@date 2010-4-29
	   *@time 上午09:58:27
	   */
	function index() {
		//分类信息频道页
		$arctype=D("Arctype");
		$data=$arctype->where("topid=1")->order("id asc")->findAll();
		load("extend");
		$list=list_to_tree($data,'id','reid','_son',1);
		//dump($list);
		$this->display();
	}//end index
}//end ClassifiedsAction