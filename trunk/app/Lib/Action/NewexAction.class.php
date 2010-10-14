<?php
/**
 +------------------------------------------------------------------------------
 * ExAction控制器类
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  app
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-9-1
 * @time  下午02:28:53
 +------------------------------------------------------------------------------
 */
class NewexAction extends Action{
	protected function _initialize() {
		header("Content-Type:text/html; charset=utf-8");
		import("ORG.Util.Image");
		
	}
	
	/**
	   *导入指定文件名的Excel
	   *@date 2010-9-1
	   *@time 下午02:36:01
	   */
	function index() {
		//导入指定文件名的Excel
		$fname=$_GET['fname'];
		
		if(empty($fname)){
			echo '<a href='.__URL__."/index/fname/281_300/>281_300</a><br>";
			exit();
		}
		$path="Public/excel/";
		$file=$path.$fname.'.xls';
		
		if($_GET['act']=='dump'){
			/*if (!file_exists($file)) {
				exit("文件'$file'不存在.\n");
			}*/
			$arr=S(md5($file));
			if(empty($arr)){
				$this->error('读取缓存数据失败');
			}
			echo "<a href='".__URL__."/sdata/fname/{$fname}'>写入数据</a><br>";
			//dump($arr);
			$sql='';
			foreach ($arr as $v){
				echo $v['writer'];
				echo "<br>";
				echo date('Y-m-d',$v['showstart']);
				echo "<br>";
				echo date('Y-m-d',$v['showend']);
				echo "<br>";
				echo "<br>";
				$sql.="UPDATE `iic_archives` SET `showstart` = '{$v['showstart']}',`showend` = '{$v['showend']}' WHERE `writer` ='{$v['writer']}' and channel='11';";
			}
			echo $sql;
			exit();
		}
		
		error_reporting(E_ALL);
		
		import("@.Com.PHPExcel",'','.php');
		//import("@.Com.PHPExcel.IOFactory",'','.php');
		import("@.Com.PHPExcel.Reader.Excel5",'','.php');
		import("@.Com.PHPExcel.Reader.Excel2007",'','.php');
		if (!file_exists($file)) {
			exit("文件'$file'不存在.\n");
		}
		
		$PHPExcel = new PHPExcel();    
		$PHPReader = new PHPExcel_Reader_Excel5();   
		$objPHPExcel=$PHPReader->load($file);
		$sheet = $objPHPExcel->getSheet(0); // 读取第一個工作表(编号从 0 开始) 
		$highestRow = $sheet->getHighestRow(); // 取得总行数 
		$highestColumn = $sheet->getHighestColumn(); // 取得总列数  
		//$arr = array(1=>'A',2=>'B',3=>'C',4=>'D',5=>'E',6=>'F',7=>'G',8=>'H',9=>'I',10=>'J',11=>'K',12=>'L',13=>'M', 14=>'N',15=>'O',16=>'P',17=>'Q',18=>'R',19=>'S',20=>'T',21=>'U',22=>'V',23=>'W',24=>'X',25=>'Y',26=>'Z');
		
		//导入数据
		$arr=array();
		if($_GET['act']=='test'){
			$t=nl2br($sheet->getCellByColumnAndRow(1,3)->getValue());
			dump($t);
			exit();
		}

		for ($i =3; $i <= $highestRow; $i++) { 
	        $arr[$i]['title']=trim($sheet->getCellByColumnAndRow(0,$i)->getValue());
	        $arr[$i]['typeid']=$sheet->getCellByColumnAndRow(5,$i)->getValue();
	        if(empty($arr[$i]['title']) || empty($arr[$i]['typeid'])){
				continue;
			}
			$arr[$i]['bycity']=$this->city($sheet->getCellByColumnAndRow(1,$i)->getValue());
			$time=$this->get_time($sheet->getCellByColumnAndRow(2,$i)->getValue());
			$arr[$i]['showstart']=$time['st'];
			$arr[$i]['showend']=$time['et'];
			$arr[$i]['channel']=11;
			
			$l=explode('_',$sheet->getCellByColumnAndRow(3,$i)->getValue());
			$arr[$i]['dir']=$path.$fname.'/'.$l['0'].'/';
			$arr[$i]['writer']=$sheet->getCellByColumnAndRow(3,$i)->getValue();
			
			$arr[$i]['industry']=$l['1'];//id和语言
			
			$arr[$i]['albumnum']=$sheet->getCellByColumnAndRow(4,$i)->getValue();
			
			$arr[$i]['typeid2']=$sheet->getCellByColumnAndRow(6,$i)->getValue();
			$arr[$i]['contact']=$this->br_or_b($sheet->getCellByColumnAndRow(8,$i)->getValue());
			$arr[$i]['maps']=$this->br_or_b($sheet->getCellByColumnAndRow(9,$i)->getValue());
			
			$arr[$i]['fair']['product']=$this->br_or_b($sheet->getCellByColumnAndRow(7,$i)->getValue());
			$arr[$i]['fair']['description']=$this->br_or_b($sheet->getCellByColumnAndRow(10,$i)->getValue());
			$arr[$i]['fair']['website']=$sheet->getCellByColumnAndRow(11,$i)->getValue();
			$arr[$i]['source']=$sheet->getCellByColumnAndRow(12,$i)->getValue();
	        
			if($l['1']=='EN'){
				$kw=str_word_count($arr[$i]['title'],1);
    			$keywords="";
    			foreach ($kw as $vkw){
    				$keywords.=$vkw.',';
    			}
				$arr[$i]['keywords']=trim($keywords,',');
				$arr[$i]['my_content']=strip_tags($arr[$i]['fair']['product']);
				$arr[$i]['description']=strip_tags($arr[$i]['title'].$arr[$i]['fair']['product']);
			}
			
			//图片检查和处理
			if($arr[$i]['albumnum'] || $arr[$i]['albumnum']=='0'){//是否有图片
					$flist=$this->file_list($arr[$i]['dir']);//获取文件列表
					$img=count($flist);
					$num=$arr[$i]['albumnum']=='0'?'1':$arr[$i]['albumnum'];
					if($num!=$img){//检查文件数量
						$msg.=$arr[$i]['writer'].'图片数量不正确实际图：'.$img.'填写图片:'.$arr[$i]['albumnum'].'<br>';
						$errnum++;
					}else{
						$msg.=$arr[$i]['writer'].'信息图片匹配<br>';
						foreach ($flist as $v){
							$type=Image::getImageInfo($v);
							if($type['type']=='bmp'){
								$msg.='<b>'.$v.'图片格式不能是bmp</b><br>';
								$warr++;
							}else{
								$filelen=strpos($v,'.'); //获取文件名长度
								$filename_name=substr($v, 0, $filelen); //截取文件名
								$filename_name=end(explode("/", $filename_name));
								$ext=strtolower(end(explode(".", $v)));
								if($filename_name=='0'){
									$width=array('120','514');
									$height=array('140','600');
									$pre=array('s_','m_');
									
									$finame='s_'.md5_file(auto_charset($v,'utf-8','gbk')).'.'.$ext;
									$dir=$l['0'].'/'.date("Y_m_d").'/';
									$spath='Public/fair/exl'.$fname.'/'.$dir;
									if(in_array($dir.$finame,$img_arr)){
										$arr[$i]['picurl']=$spath.$finame;
										$arr[$i]['pic'][]['filename']=$spath.'m_'.md5_file(auto_charset($v,'utf-8','gbk')).'.'.$ext;
									}else{
										$img_arr[$i]=$dir.$finame;
										$this->mvimg($v,$spath,md5_file(auto_charset($v,'utf-8','gbk')),$width,$height,$pre);
										$arr[$i]['picurl']=$spath.$finame;
										$arr[$i]['pic'][]['filename']=$spath.'m_'.md5_file(auto_charset($v,'utf-8','gbk')).'.'.$ext;
									}
								}else{
									$width=array('514');
									$height=array('600');
									$pre=array('m_');
									$finame='m_'.md5_file(auto_charset($v,'utf-8','gbk')).'.'.$ext;
									$dir=$l['0'].'/'.date("Y_m_d").'/';
									$spath='Public/fair/exl'.$fname.'/'.$dir;
									if(in_array($dir.$finame,$img_arr)){
										$arr[$i]['pic'][]['filename']=$spath.$finame;
									}else{
										$img_arr[$i]=$dir.$finame;
										$this->mvimg($v,$spath,md5_file(auto_charset($v,'utf-8','gbk')),$width,$height,$pre);
										$arr[$i]['pic'][]['filename']=$spath.$finame;
									}
								}
							}//图片不是BMP格式
						}
						//dump($arr[$i]['picurl']);
					}
				}else{
					$msg.='<b>'.$data->sheets[0]['cells'][$i][4].'信息无图片</b><br>';
					$warr++;
				}
			//图片检查处理结束
			
			//检查数据是否有对象
	        foreach ($arr[$i] as $d){
	        	if(is_object($d)){
	        		echo "数据表读取出错！";
	        		dump($arr[$i]);
	        		exit();
	        	}
	        }
	    	 
		}
		
		echo '共有'.$errnum.'错误!<br>'.$warr.'警告!<br>';
		echo $msg;
		//dump($arr);
		S(md5($file),$arr);
		if($arr){
			echo "数据读取成功！<br>";
			echo "<a href='".__URL__."/index/fname/{$fname}/act/dump'>查看数据</a><br>";
			echo "<a href='".__URL__."/sdata/fname/{$fname}'>写入数据</a><br>";
		}else{
			echo "数据读取不成功！<br>";
		}
		
		
	}//end index
	
	/**
	   *写入数据
	   *@date 2010-9-2
	   *@time 下午04:14:27
	   */
	function sdata() {
		//写入数据
		$fname=$_REQUEST['fname'];
		$path="Public/excel/";
		$file=$path.$fname.'.xls';
		/*if (!file_exists($file)) {
			exit("文件'$file'不存在.\n");
		}*/
		$data=S(md5($file));
		if(empty($data)){
			$this->error('读取缓存数据失败');
		}
		//dump($data);
		
		
		$return='';
		$arc=D("Archives");
		$fair=D("Fair");
		foreach ($data as $k=>$v){
			if(!empty($v['title']) && !empty($v['typeid'])){
				$acr_vo='';
				$arc_vo=$arc->create($v);
				$aid=$arc->add($arc_vo);
				if($aid){
					$fair_vo=$fair->create($v['fair']);
					$fair_vo['aid']=$aid;
					$fid=$fair->add($fair_vo);
					if(!empty($v['pic'])){
						$this->add_photo($aid,$v['pic'],$v['writer'],'11');
					}
					$return.=$v['writer'].'写入成功！<br>';
				}else{
					$return.=$v['writer'].'主档写入失败！<br>';
				}
			}else{
				$return.=$v['writer'].'标题或分类为空写入失败！<br>';
			}
		}
		echo $return;
		//dump($edata);
	}//end sdata
	
	
	function get_time($t) {
		//get_time
		if(strpos($t,'-')){
			$t=explode("-",$t);
			$st=explode('/',str_replace('.','/',$t['0']));
			$st=mktime('0',0,0,$st['1'],$st['2'],$st['0']);
			$et=explode('/',str_replace('.','/',$t['1']));
			$et=mktime('0',0,0,$et['1'],$et['2'],$et['0']);
		}else{
			$t=$this->excel_time($t);
			$st=explode('/',str_replace('.','/',$t));
			$st=mktime('0',0,0,$st['1'],$st['2'],$st['0']);
			$et=mktime('0',0,0,date('m',$st),date('d',$st)+2,date('Y',$st));
		}
		return array('st'=>$st,'et'=>$et);
	}//end function_name
	
	
	function br_or_b($str) {
		//$str=deletehtml($str);
		$str=nl2br($str);
		return str_replace(array("[b]",'[/b]','||','\t\n'),array("<b>",'</b>','<br>','<br>'),$str);
	}//end br_or_b
	
	function city($str){
		$str=strtolower(trim($str));
		$str=str_replace(' ','',$str);
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
			case 'dongguan':
				$str='6';
			break;
			case 'qingdao':
				$str='7';
			break;
			case 'yiwu':
				$str='8';
			break;
			case 'hongkong':
				$str='9';
			break;
			case 'macao':
				$str='10';
			break;
			case 'chengdu':
				$str='11';
			break;
			case 'nanning':
				$str='12';
			break;
			case 'tianjin':
				$str='13';
			break;
			case 'chongqing':
				$str='14';
			break;
			case "xi'an":
				$str='15';
			break;
			case "qingyuan":
				$str='16';
			break;
			case "nanjing":
				$str='17';
			break;
			case "fuzhou":
				$str='18';
			break;
			case "ningbo":
				$str='19';
			break;
			case "jinan":
				$str='20';
			break;
			case "wuhan":
				$str='21';
			break;
			default:
				dump($str);
				$this->error('城市名称不匹配');
				//$str='0';
			break;
		}
		return $str;
	}
	
	/**
	 *mvimg
	 *@date 2010-7-2
	 *@time 上午09:12:41
	 */
	protected function mvimg($file,$path,$name,$thumbWidth,$thumbHeight,$thumbPrefix) {
		//mvimg
		mk_dir($path);
		$extension=strtolower(end(explode(".", $file)));
		
		for($i=0,$len=count($thumbWidth); $i<$len; $i++) {
			$thumbname=$path.$thumbPrefix[$i].$name.'.'.$extension;
			$type= Image::getImageInfo($file);
			$info='0';
			if($type['type']!='bmp'){
				$info=Image::thumb($file,$thumbname,'',$thumbWidth[$i],$thumbHeight[$i],true);
				if($info!=$thumbname){
					$this->error($thumbname);
					//return false;
				}
			}
		}
		return $info;
	}//end mvimg
	
	function file_list($dir) {     //dir为指定的目录参数
		$handle=opendir($dir);
		$i=0;
		while($file=readdir($handle)) {
			if(($file!=".")and($file!="..")) {//.和..是当前目录和上级目录的硬链接，这里不需要
					$types=array('jpg','png','gif','jpeg','bmp');
					$ftype=strtolower(end(explode(".", $file)));
					if(in_array($ftype,$types)){
						$list[$i]=$dir.$file;
						$i=$i+1;
					}
			}
		}
		closedir($handle);
		return $list;
		//dump($list);
	}
	
	function add_photo($aid,$filename,$title,$xtype,$uid){
		$dao=D("Pic");
		if(is_array($filename)){
			foreach ($filename as $v){
				$data=array();
				$data['xid']=$aid;
				$data['filename']=$v['filename'];
				$data['title']=$title;
				$data['xtype']=$xtype;
				$vo=array();
				$vo=$dao->create($data);
				$dao->add($vo);
			}
		}else{
			$data=array();
			$data['xid']=$aid;
			$data['filename']=$filename;
			$data['title']=$title;
			$data['xtype']=$xtype;
			$vo=array();
			$vo=$dao->create($data);
			$dao->add($vo);
		}
	}
	
		/**
	   *@date 2010-7-22
	   *@time 上午10:45:39
	   */
	function excel_time($days='40368') {
		//function 
		if(is_numeric($days)){
			$jd=gregoriantojd(1,1,1970);
			$gregorian = JDToGregorian($jd+intval($days)-25569);
			 $myDate = explode('/',$gregorian);
			$myDateStr = str_pad($myDate[2],4,'0', STR_PAD_LEFT)
			."-".str_pad($myDate[0],2,'0', STR_PAD_LEFT)
			."-".str_pad($myDate[1],2,'0', STR_PAD_LEFT)
			.($time?" 00:00:00":'');
			return $myDateStr;
		}else{
			return $days;
		}
	}//end excel_time
}//end ExAction