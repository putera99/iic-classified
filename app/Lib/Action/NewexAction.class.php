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
			echo '<a href='.__URL__."/index/fname/301_320/>301_320</a><br>";
			echo '<a href='.__URL__."/index/fname/321_340/>321_340</a><br>";
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
				dump($v);
				echo "<br>";
				//$sql.="UPDATE `iic_archives` SET `showstart` = '{$v['showstart']}',`showend` = '{$v['showend']}' WHERE `writer` ='{$v['writer']}' and channel='11';";
			}
			//echo $sql;
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
	
	/**
	   *event的时间
	   *@date 2010-10-18
	   *@time 上午10:17:54
	   */
	function mkt($t) {
		//event的时间
		$t=trim($t);
		if(strpos($t,' ')){
			$t=explode(' ', $t);
			$date=explode("-", $t['0']);
			$time=explode(":", $t['1']);
			return mktime($time['0'],$time['1'],$time['2'],$date['1'],$date['2'],$date['0']);
		}else{
			$date=explode("-", $t);
			return mktime(0,0,0,$date['1'],$date['2'],$date['0']);
		}
	}//end mkt
	
	function br_or_b($str) {
		//$str=deletehtml($str);
		$str=nl2br($str);
		return str_replace(array("[b]",'[/b]','||','\t\n'),array("<b>",'</b>','<br />','<br />'),$str);
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
			case "dalian":
				$str='22';
			break;
			case "shenyang":
				$str='23';
			break;
			case "suzhou":
				$str='24';
			break;
			case "hangzhou":
				$str='25';
			break;
			case "kunming":
				$str='26';
			break;
			case "zhengzhou":
				$str='27';
			break;
			case "xiamen":
				$str='28';
			break;
			case "shijiangzhuang":
				$str='29';
			break;
			case "yangjiang":
				$str='30';
			break;
			case "changchun":
				$str='31';
			break;
			case "yantai":
				$str='32';
			break;
			case "harbin":
				$str='33';
			break;
			case "zhuhai":
				$str='34';
			break;
			case "zhongshan":
				$str='35';
			break;
			case "foshan":
				$str='36';
			break;
			case "changsha":
				$str='37';
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
	
	/*********************city guide*******************************/
	/**
	   *导入数据到城市指南
	   *@date 2010-10-14
	   *@time 下午02:05:57
	   */
	function exctg() {
		//导入数据到城市指南
		$file="Public/ctg/chi_school.xls";
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
		
		$arr=array();
		
		for ($i =2; $i <= $highestRow; $i++) {
			/*for($x=0;$x<=6;$x++){
				$arr[$i][$x]=$sheet->getCellByColumnAndRow($x,$i)->getValue();
			}*/
			$arr[$i]['title']=$sheet->getCellByColumnAndRow(0,$i)->getValue();
			$content="City:".$sheet->getCellByColumnAndRow(2,$i)->getValue();
			$content.="<br>Total Student:".$sheet->getCellByColumnAndRow(3,$i)->getValue();
			$content.="<br>International Student:".$sheet->getCellByColumnAndRow(4,$i)->getValue();
			$content.="<br>E-mail:".$sheet->getCellByColumnAndRow(5,$i)->getValue();
			$content.="<br>Address:".$sheet->getCellByColumnAndRow(7,$i)->getValue();
			$arr[$i]['my_content']=$content;
			$arr[$i]['picurl']=$sheet->getCellByColumnAndRow(6,$i)->getValue();
			$arr[$i]['contact']=$sheet->getCellByColumnAndRow(7,$i)->getValue();
			$arr[$i]['ctg']['content']=$sheet->getCellByColumnAndRow(8,$i)->getValue();
			$arr[$i]['ctg']['content'].=$sheet->getCellByColumnAndRow(9,$i)->getValue();
			$arr[$i]['ctg']['redirecturl']=$sheet->getCellByColumnAndRow(1,$i)->getValue();
			
		}
		//dump($arr);
		if($_GET['act']=='ok'){
			S("ctg",NULL);
			S("ctg",$arr);
			//$this->ctg($arr);
		}else{
			dump($arr);
		}
	}//end exctg
	
	/**
	   *ctg
	   *@date 2010-10-14
	   *@time 下午02:57:33
	   */
	function ctg() {
		//ctg
		$arr=S("ctg");
		$dao=M("Archives");
		$sub=M("AddonArticle");
		foreach ($arr as $v){
			$vo=$dao->create($v);
			$vo['uid']='3926';
			$vo['ismake']='1';
			$vo['typeid']='3003';
			$vo['cid']=$vo['albumnum']=$vo['click']='0';
			$vo['senddate']=$vo['pubdate']=$vo['edittime']=$vo['editpwd']=time();
			unset($vo['ctg']);
			$id=$dao->add($vo);
			if($id){
				echo $dao->getlastSql();
				echo "<br>";
				$data=array();
				$data['content']=$v['ctg']['content'];
				$data['aid']=$id;
				$data['redirecturl']=$id;
				//dump($data);
				$sid=$sub->add($data);
				if($sid){
					echo $sub->getlastSql();
					echo "<br>";
				}else{
					echo $sub->getlastSql();
					echo "error_sub";
					echo "<br>";
				}
				echo "<br>";
				echo "<br>";
			}else{
				echo $dao->getlastSql();
				echo "ERROR";
				echo "<br>";
			}
		}
	}//end ctg

	/**
	   *导入Events
	   *@date 2010-10-16
	   *@time 上午10:00:44
	   */
	function event() {
		//导入Events
		import("ORG.Util.Image");
		$filename=$_GET['fname'];
		$act=$_GET['act'];
		if(empty($filename) && $act!='hebing'){
			echo '<a href='.__URL__."/event/fname/Guangzhou1110/>Guangzhou1110</a><br>";
			echo '<a href='.__URL__."/event/fname/Shanghai1110/>Shanghai1110</a><br>";
			echo '<a href='.__URL__."/event/fname/shenzhen20101109/>shenzhen20101109</a><br>";
			echo '<a href='.__URL__."/event/fname/Beijing1111/>Beijing1111</a><br>";
			echo '<a href='.__URL__."/event/act/hebing/>合并数据</a><br>";
			exit();
		}
		
		if($act=='hebing'){
			$newarr=array();
			$arr1=S('Guangzhou1110');
			$arr2=S('Shanghai1110');
			$arr3=S('shenzhen20101109');
			$arr4=S('Beijing1111');
			$newarr=array_merge($arr1,$arr2,$arr3,$arr4);
			shuffle($newarr);
			S('hebing',$newarr,6000);
			if(S('hebing')){
				echo "<a href='".__URL__."/event/'>返回</a><br>";
				echo '写入成功';
				
			}else{
				echo '写入bu成功';
			}
			exit();
		}
		
		error_reporting(E_ALL);
		import("@.Com.PHPExcel",'','.php');
		//import("@.Com.PHPExcel.IOFactory",'','.php');
		import("@.Com.PHPExcel.Reader.Excel5",'','.php');
		import("@.Com.PHPExcel.Reader.Excel2007",'','.php');
		
		$path="Public/events/";
		$file=$path.$filename.'.xls';
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
		$arr=array();
		if($_GET['act']=='test'){
			$t=nl2br($sheet->getCellByColumnAndRow(1,3)->getValue());
			dump($t);
			exit();
		}
		$warr=0;
		$msg='';
		$errnum=0;
		for ($i =2; $i <= $highestRow; $i++) {
			$arr[$i]['title']=$sheet->getCellByColumnAndRow(0,$i)->getValue();
			if(empty($arr[$i]['title'])){
				unset($arr[$i]);
				continue;
			}
			$arr[$i]['cid']=$sheet->getCellByColumnAndRow(1,$i)->getValue();
			
			if(!is_float($arr[$i]['cid'])||empty($arr[$i]['cid'])){
				unset($arr[$i]);
				continue;
			}
			$msg.='CID '.$arr[$i]['cid'].'___'.$sheet->getCellByColumnAndRow(2,$i)->getValue().'-->'.$sheet->getCellByColumnAndRow(3,$i)->getValue();
			$time=$this->mkt($sheet->getCellByColumnAndRow(2,$i)->getValue());
			$arr[$i]['showstart']=$time;
			$arr[$i]['showstart2']=date("Y-m-d H:i:s",$time);
			$time=$this->mkt($sheet->getCellByColumnAndRow(3,$i)->getValue());
			$arr[$i]['showend']=$time;
			$arr[$i]['showend2']=date("Y-m-d H:i:s",$time);
			$arr[$i]['channel']=10;
			$l=$sheet->getCellByColumnAndRow(4,$i)->getValue();
			$arr[$i]['dir']=$path.$filename.'/'.$l.'/';
			$arr[$i]['writer']=$sheet->getCellByColumnAndRow(4,$i)->getValue();;
			$arr[$i]['albumnum']=$sheet->getCellByColumnAndRow(5,$i)->getValue();
			$arr[$i]['typeid']=$sheet->getCellByColumnAndRow(6,$i)->getValue();
			if(empty($arr[$i]['typeid'])){
				unset($arr[$i]);
				continue;
			}
			
			$msg.='typeid '.$arr[$i]['typeid'].'___';
			
			
			$arr[$i]['maps']=$this->br_or_b($sheet->getCellByColumnAndRow(8,$i)->getValue());
			$arr[$i]['event']['detail']=$this->br_or_b($sheet->getCellByColumnAndRow(7,$i)->getValue());
			
			$kw=str_word_count($arr[$i]['title'],1);
    		$keywords="";
    		foreach ($kw as $vkw){
    			$keywords.=$vkw.',';
    		}
			$arr[$i]['keywords']=trim($keywords,',');
			$arr[$i]['my_content']=strip_tags($arr[$i]['event']['detail']);
			$arr[$i]['description']=$arr[$i]['my_content'];
			
			
			if($arr[$i]['albumnum'] || $arr[$i]['albumnum']=='0'){
				$flist=$this->file_list($arr[$i]['dir']);
				//dump($flist);
				$img=count($flist);
				$num=$arr[$i]['albumnum']+1;
				if($num!=$img){
					$msg.=$l.'图片数量不正确实际图：'.$img.'填写图片:'.$arr[$i]['albumnum']."<br>\n";
					$errnum++;
				}else{
					$msg.=$l."信息图片匹配<br>\n";
					foreach ($flist as $v){
						$type=Image::getImageInfo($v);
						if($type['type']=='bmp'){
							$msg.='<b>'.$v.'图片格式不能是bmp</b><br>';
							$warr++;
						}elseif($act=='ok' && !empty($filename)){//是否确认写入
							$filelen=strpos($v,'.'); //获取文件名长度
							$filename_name=substr($v, 0, $filelen); //截取文件名
							$filename_name=end(explode("/", $filename_name));
							$ext=strtolower(end(explode(".", $v)));
							if($filename_name=='0'){
								$width=array('120','514');
								$height=array('140','600');
								$pre=array('s_','m_');
								
								$fname='s_'.md5_file(auto_charset($v,'utf-8','gbk')).'.'.$ext;
								$dir=$l.'/'.date("Y_m_d").'/';
								$spath='Public/event/exl'.$filename.'/'.$dir;
								//dump($spath);
								if(in_array($dir.$fname,$img_arr)){
									$arr[$i]['picurl']='/'.$spath.$fname;
									$arr[$i]['pic'][]['filename']='/'.$spath.'m_'.md5_file(auto_charset($v,'utf-8','gbk')).'.'.$ext;
								}else{
									$img_arr[$i]=$dir.$fname;
									$this->mvimg($v,$spath,md5_file(auto_charset($v,'utf-8','gbk')),$width,$height,$pre);
									$arr[$i]['picurl']='/'.$spath.$fname;
									$arr[$i]['pic'][]['filename']='/'.$spath.'m_'.md5_file(auto_charset($v,'utf-8','gbk')).'.'.$ext;
								}
							}else{
								$width=array('514');
								$height=array('600');
								$pre=array('m_');
								$fname='m_'.md5_file(auto_charset($v,'utf-8','gbk')).'.'.$ext;
								$dir=$l.'/'.date("Y_m_d").'/';
								$spath='Public/event/exl'.$filename.'/'.$dir;
								if(in_array($dir.$fname,$img_arr)){
									$arr[$i]['pic'][]['filename']='/'.$spath.$fname;
								}else{
									$img_arr[$i]=$dir.$fname;
									$this->mvimg($v,$spath,md5_file(auto_charset($v,'utf-8','gbk')),$width,$height,$pre);
									$arr[$i]['pic'][]['filename']='/'.$spath.$fname;
								}
							}
						}//图片不是BMP格式
					}
					//dump($arr[$i]['picurl']);
				}
			}else{
				$msg.='<b>'.$l."信息无图片</b><br>\n";
				$warr++;
			}
		}
		echo $msg;
		echo "<a href='".__URL__."/event/fname/{$filename}/act/ok'>写入缓存文件</a><br>";
		if($act=='ok'){
			S($filename,$arr);
			echo "已经写入缓存";
			
		}
		dump($arr);
		/*foreach ($arr as $v){
			echo $v['writer'].'-------';
			echo $v['showstart2'].'-------';
			echo $v['showend2'].'-------';
			echo '<br>';
		}*/
	}//end event
	
	
		/**
	   *写入
	   *@date 2010-7-22
	   *@time 下午02:48:47
	   */
	function event_add() {
		//写入
		$return='';
		$arc=D("Archives");
		$event=D("Event");
		$arr=S('hebing');
		foreach ($arr as $d){
			if($_GET['act']=='test'){
				dump($d);
			}else{
				if(!empty($d['title']) && !empty($d['typeid'])){
					$acr_vo='';
					$arc_vo=$arc->create($d);
					$user=$this->randuser();
					$arc_vo['uid']=$user['0'];
					$aid=$arc->add($arc_vo);
					if($aid){
						$event_vo=$event->create($d['event']);
						$event_vo['aid']=$aid;
						$eid=$event->add($event_vo);
						$this->add_photo($aid,$d['pic'],$d['writer'],10,$arc_vo['uid'],$user['1']);
						$return.=$d['writer']."写入成功！<br>\n";
					}else{
						$return.=$d['writer']."主档写入失败！<br>\n";
					}
				}else{
					$return.=$d['writer']."标题或分类为空写入失败！<br>\n";
				}
			}
		}
		echo $return;
	}//end event_add
	
		/**
	   *随机用户
	   *@date 2010-8-7
	   *@time 下午07:20:28
	   */
	function randuser() {
		//随机用户
		$user=array(
		'Sanchez'=>'2490',
		'Birdman'=>'2491',
		'Luz'=>'2492',
		'Hicks'=>'2493',
		'Beki'=>'2495',
		'aby'=>'2496',
		'Diesel'=>'2497',
		'Stewart011'=>'2498',
		'Hirkic'=>'2499',
		'Omega'=>'2500',
		'Tenic'=>'2501',
		'Ramirez'=>'2502',
		'Sean S.'=>'2503',
		'Norn'=>'2504',
		'Shaina'=>'2505',
		'Leroux'=>'2506',
		'Sieger'=>'2507',
		'Longoria'=>'2508',
		'Tojimaru'=>'2509',
		'Lewis'=>'1032',
		'Peterson Ap.'=>'2511',
		'Lynn503'=>'2512',
		'Summers'=>'2513',
		'Donahue'=>'2517',
		'Mcgrady'=>'2518',
		'Grapner'=>'2519',
		'Swagga'=>'2520',
		'Dominguez'=>'2523',
		'Ahrens'=>'2524',
		'Veronica007'=>'2525',
		'Martinez'=>'2530',
		'Pope'=>'2531',
		'Aycoth'=>'2532',
		'Purvis'=>'2533',
		'Sergio'=>'2534',
		'Noelle'=>'2535',
		'Talamante'=>'2536',
		'Adrian'=>'2537',
		'Pena'=>'2538',
		'Debbra'=>'2539',
		'Cesar'=>'2540',
		'Josh Mee'=>'2541',
		'Mel'=>'2542',
		'Dojo'=>'2543',
		'Ray'=>'2544',
		'Sasha'=>'2514',
		'Vengeful'=>'2527',
		'Nakihei'=>'2516',
		'Brandee'=>'2545',
		'Daoust'=>'2546',
		'Jolee'=>'2547',
		'Healy'=>'2548',
		'Avitia'=>'2549',
		'Rivas'=>'2550',
		'Jaye'=>'2551',
		'SanchezZ'=>'2552',
		'Dodgion'=>'2553',
		'Olson'=>'2555',
		'Mahan'=>'2556',
		'Erik'=>'2557',
		'Joshua080'=>'2558',
		'Gerig'=>'2559',
		'Glen303'=>'2526',
		'Dante'=>'2528',
		'JonV'=>'2567',
		'Oldaker'=>'2568',
		'Celis'=>'2569',
		'Doll'=>'2570',
		'Goldie'=>'2571',
		'Sucio'=>'2572',
		'Laxfoss'=>'2360',
		'Bryan302'=>'2361',
		'Nisha'=>'2362',
		'Kason'=>'2363',
		'Kaithlyn'=>'2364',
		'Watson'=>'2365',
		'Vince'=>'2366',
		'Zendejas'=>'2367',
		'Gerard'=>'2368',
		'Kaydee'=>'2369',
		'Parker'=>'2370',
		'Coons'=>'2371',
		'Flores'=>'2372',
		'Cortez'=>'2373',
		'Chris924'=>'2374',
		'Mott'=>'2375',
		'Fronk'=>'2376',
		'Freeman'=>'2377',
		'Leah7605'=>'2378',
		'Jasmine231'=>'2380',
		'Bel'=>'2381',
		'Trifiletti'=>'2382',
		'Grinter'=>'2383',
		'Nikita199'=>'2387',
		'Douglas401'=>'2386',
		'Melissa G.'=>'2388',
		'Mills'=>'2389',
		'Casey'=>'2390',
		'Zahrim'=>'2339',
		'Lauzon'=>'2340',
		'Lycretia'=>'2342',
		'Trish863'=>'2343',
		'Nwokobia'=>'2344',
		'Mandee'=>'2345',
		'Jenni'=>'2346',
		'Glenn'=>'2347',
		'Javier'=>'2348',
		'Pennini'=>'2349',
		'Kandi'=>'2350',
		'Pineda'=>'2354',
		'Hinton'=>'2355',
		'Lullaby'=>'2356',
		'Ambriz'=>'2357',
		'Rance'=>'2358',
		'Brandon680'=>'2391',
		'Cinderella'=>'2392',
		'Nikia321'=>'2393',
		'Dave7901'=>'2394',
		);
		$userx=array();
		foreach ($user as $k=>$v){
			$userx[$v][0]=$v;
			$userx[$v][1]=$k;
		}
		$x=array_rand($userx);
		return $userx[$x];
	}//end function_name
}//end ExAction