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
		import("@.Com.OLERead");
		import("@.Com.Spreadsheet_Excel_Reader");
		$file="Public/excel/1-20.xls";
		$arr=array();
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('utf-8');
		
		$data->read($file);
		
		error_reporting(E_ALL ^ E_NOTICE);
		
		//echo $data->sheets[0]['numRows'];
		//echo $data->sheets[0]['numCols'];
		//echo $data->sheets[0]['cells'][3][8];
		
		$arr=array();
		for ($i = 3; $i <= 42; $i++) {
			
			$arr[$i]['title']=$data->sheets[0]['cells'][$i][1];
			$arr[$i]['bycity']=$this->city($data->sheets[0]['cells'][$i][2]);
			$time=$this->get_time($data->sheets[0]['cells'][$i][3]);
			$arr[$i]['showstart']=$time['st'];
			$arr[$i]['showend']=$time['et'];
			
			$l=$this->info($data->sheets[0]['cells'][$i][4]);
			$arr[$i]['dir']=$l['0'];
			$arr[$i]['industry']=$l['1'];//id和语言
			$arr[$i]['albumnum']=$data->sheets[0]['cells'][$i][5];
			$arr[$i]['typeid']=$data->sheets[0]['cells'][$i][6];
			$arr[$i]['typeid2']=$data->sheets[0]['cells'][$i][7];
			$arr[$i]['contact']=$this->br_or_b($data->sheets[0]['cells'][$i][9]);
			$arr[$i]['maps']=$data->sheets[0]['cells'][$i][10];
			
			$arr[$i]['fair']['product']=$this->br_or_b($data->sheets[0]['cells'][$i][8]);
			$arr[$i]['fair']['description']=$this->br_or_b($data->sheets[0]['cells'][$i][11]);
			$arr[$i]['fair']['website']=$data->sheets[0]['cells'][$i][12];
			$arr[$i]['fair']['source']=$data->sheets[0]['cells'][$i][13];
		}
		
		dump($arr);
	}//end index
	
	
	function get_time($t) {
		//get_time
		$t=explode("-",$t);
		$st=explode('/',str_replace('.','/',$t['0']));
		$st=mktime('0',0,0,$st['1'],$st['2'],$st['0']);
		$et=explode('/',str_replace('.','/',$t['1']));
		$et=mktime('0',0,0,$et['1'],$et['2'],$et['0']);
		return array('st'=>$st,'et'=>$et);
	}//end function_name
	
	
	function br_or_b($str) {
		return str_replace(array("[b]",'[/b]','||'),array("<b>",'</b>','<br>'),$str);
	}//end br_or_b
	
	function city($str){
		$str=strtolower($str);
		switch ($str) {
			case 'beijing':
				$str='2';
			break;
			case 'guangzhou':
				$str='1';
			break;
			case 'shanghai':
				$str='3';
			break;
			case 'shenzhen':
				$str='4';
			break;
		}
		return $str;
	}
	
	/**
	 *获取图片目录及语言
	 *@date 2010-7-1
	 *@time 下午05:33:11
	 */
	function info($str) {
		//获取图片目录及语言
		return explode('_',$str);
	}//end info
}//end ExAction