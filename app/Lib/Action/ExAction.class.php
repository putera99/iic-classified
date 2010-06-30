<?php
/**
 +------------------------------------------------------------------------------
 * ExAction控制器类
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  app
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-6-29
 * @time  上午11:51:59
 +------------------------------------------------------------------------------
 */
class ExAction extends CommonAction{
	
	/**
	 *读取EXCEL
	 *@date 2010-6-29
	 *@time 上午11:52:12
	 */
	function index() {
		//读取EXCEL
		import("@.Com.Spreadsheet_Excel_Reader");
		$file="20100625.xls";
		$arr=array();
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('CP936');
		
		$data->read($file);
		dump($data);
		error_reporting(E_ALL ^ E_NOTICE);
	
		for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
			for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
				echo "\"".$data->sheets[0]['cells'][$i][$j]."\",";
			}
			echo "\n";
		}
	}//end index
	
}//end ExAction