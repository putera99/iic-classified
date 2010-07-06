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
		import("ORG.Util.Image");
		$filename=$_GET['fname'];
		$path="Public/excel/";
		$file=$path.$filename.'.xls';
		$arr=array();
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('utf-8');
		
		$data->read($file);
		
		error_reporting(E_ALL ^ E_NOTICE);
		
		//echo $data->sheets[0]['numRows'];
		//echo $data->sheets[0]['numCols'];
		//echo $data->sheets[0]['cells'][3][8];
		$act=$_GET['act'];
		if($act=='ok'){
			import("ORG.Io.Dir");
			if(is_dir('Public/fair/exl'.$filename)){
        		Dir::del('Public/fair/exl'.$filename);
			}
		}
		$arr=array();
		$msg='';
		$errnum=0;
		$warr=0;
		$img_arr=array();
		for ($i = 3; $i <= 42; $i++) {
			if(empty($data->sheets[0]['cells'][$i][1]) || empty($data->sheets[0]['cells'][$i][6])){
				continue;
			}
			$arr[$i]['title']=$data->sheets[0]['cells'][$i][1];
			$arr[$i]['bycity']=$this->city($data->sheets[0]['cells'][$i][2]);
			$time=$this->get_time($data->sheets[0]['cells'][$i][3]);
			$arr[$i]['showstart']=$time['st'];
			$arr[$i]['showend']=$time['et'];
			$arr[$i]['channel']=11;
			
			$l=explode('_',$data->sheets[0]['cells'][$i][4]);
			$arr[$i]['dir']=$path.$filename.'/'.$l['0'].'/';
			$arr[$i]['writer']=$data->sheets[0]['cells'][$i][4];
			
			$arr[$i]['industry']=$l['1'];//id和语言
			
			$arr[$i]['albumnum']=$data->sheets[0]['cells'][$i][5];
			$arr[$i]['typeid']=$data->sheets[0]['cells'][$i][6];
			$arr[$i]['typeid2']=$data->sheets[0]['cells'][$i][7];
			$arr[$i]['contact']=$this->br_or_b($data->sheets[0]['cells'][$i][9]);
			$arr[$i]['maps']=$this->br_or_b($data->sheets[0]['cells'][$i][10]);
			
			$arr[$i]['fair']['product']=$this->br_or_b($data->sheets[0]['cells'][$i][8]);
			$arr[$i]['fair']['description']=$this->br_or_b($data->sheets[0]['cells'][$i][11]);
			$arr[$i]['fair']['website']=$data->sheets[0]['cells'][$i][12];
			$arr[$i]['source']=$data->sheets[0]['cells'][$i][13];
			
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
			
			if($arr[$i]['albumnum'] || $arr[$i]['albumnum']=='0'){
				$flist=$this->file_list($arr[$i]['dir']);
				$img=count($flist);
				$num=$arr[$i]['albumnum']=='0'?'1':$arr[$i]['albumnum'];
				if($num!=$img){
					$msg.=$data->sheets[0]['cells'][$i][4].'图片数量不正确实际图：'.$img.'填写图片:'.$arr[$i]['albumnum'].'<br>';
					$errnum++;
				}else{
					$msg.=$data->sheets[0]['cells'][$i][4].'信息图片匹配<br>';
					foreach ($flist as $v){
						$type=Image::getImageInfo($v);
						if($type['type']=='bmp'){
							$msg.='<b>'.$v.'图片格式不能是bmp</b><br>';
							$warr++;
						}elseif($act=='ok'){
							$filelen=strpos($v,'.'); //获取文件名长度
							$filename_name=substr($v, 0, $filelen); //截取文件名
							$filename_name=end(explode("/", $filename_name));
							$ext=strtolower(end(explode(".", $v)));
							if($filename_name=='0'){
								$width=array('120','514');
								$height=array('140','600');
								$pre=array('s_','m_');
								
								$fname='s_'.md5_file(auto_charset($v,'utf-8','gbk')).'.'.$ext;
								$dir=$l['0'].'/'.date("Y_m_d").'/';
								$spath='Public/fair/exl'.$filename.'/'.$dir;
								if(in_array($dir.$fname,$img_arr)){
									$arr[$i]['picurl']=$spath.$fname;
									$arr[$i]['pic'][]['filename']=$spath.'m_'.md5_file(auto_charset($v,'utf-8','gbk')).'.'.$ext;
								}else{
									$img_arr[$i]=$dir.$fname;
									$this->mvimg($v,$spath,md5_file(auto_charset($v,'utf-8','gbk')),$width,$height,$pre);
									$arr[$i]['picurl']=$spath.$fname;
									$arr[$i]['pic'][]['filename']=$spath.'m_'.md5_file(auto_charset($v,'utf-8','gbk')).'.'.$ext;
								}
							}else{
								$width=array('514');
								$height=array('600');
								$pre=array('m_');
								$fname='m_'.md5_file(auto_charset($v,'utf-8','gbk')).'.'.$ext;
								$dir=$l['0'].'/'.date("Y_m_d").'/';
								$spath='Public/fair/exl'.$filename.'/'.$dir;
								if(in_array($dir.$fname,$img_arr)){
									$arr[$i]['pic'][]['filename']=$spath.$fname;
								}else{
									$img_arr[$i]=$dir.$fname;
									$this->mvimg($v,$spath,md5_file(auto_charset($v,'utf-8','gbk')),$width,$height,$pre);
									$arr[$i]['pic'][]['filename']=$spath.$fname;
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
			
		}//foreach end
		
		if($act=='ok'){
			$return='';
			$arc=D("Archives");
			$fair=D("Fair");
			foreach ($arr as $d){
				if(!empty($d['title']) && !empty($d['typeid'])){
					$acr_vo='';
					$arc_vo=$arc->create($d);
					$aid=$arc->add($arc_vo);
					if($aid){
						$fair_vo=$fair->create($d['fair']);
						$fair_vo['aid']=$aid;
						$fid=$fair->add($fair_vo);
						$this->add_photo($aid,$d['pic'],$filename);
						$return.=$d['writer'].'写入成功！<br>';
					}else{
						$return.=$d['writer'].'主档写入失败！<br>';
					}
				}else{
					$return.=$d['writer'].'标题或分类为空写入失败！<br>';
				}
			}
			echo $return;
		}elseif($act=='clear'){
			
		}else{
			echo '共有'.$errnum.'错误!<br>'.$warr.'警告!<br>';
			echo $msg;
			echo "<a href='".__URL__."/index/act/ok/fname/".$filename."'>写入数据</a>";
		}
		//dump($arr);
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
					return false;
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
	
	function add_photo($aid,$filename,$title){
		$dao=D("Pic");
		if(is_array($filename)){
			foreach ($filename as $v){
				$data=array();
				$data['aid']=$aid;
				$data['filename']=$v;
				$data['title']=$title;
				$vo=array();
				$vo=$dao->create($data);
				$dao->add($vo);
			}
		}else{
			$data=array();
			$data['aid']=$aid;
			$data['filename']=$aid;
			$data['title']=$title;
			$vo=array();
			$vo=$dao->create($data);
			$dao->add($vo);
		}
	}
	
	
	
	/**
	 *导入活动数据
	 *@date 2010-7-6
	 *@time 下午02:34:57
	 */
	function events() {
		//导入活动数据
		import("@.Com.OLERead");
		import("@.Com.Spreadsheet_Excel_Reader");
		import("ORG.Util.Image");
		$filename=$_GET['fname'];
		$path="Public/events/";
		$file=$path.$filename.'.xls';
		$arr=array();
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('utf-8');
		
		$data->read($file);
		
		error_reporting(E_ALL ^ E_NOTICE);
		
		//echo $data->sheets[0]['numRows'];
		//echo $data->sheets[0]['numCols'];
		//echo $data->sheets[0]['cells'][3][8];
		$act=$_GET['act'];
		if($act=='ok'){
			import("ORG.Io.Dir");
			if(is_dir('Public/event/exl'.$filename)){
        		Dir::del('Public/event/exl'.$filename);
			}
		}
		$arr=array();
		$msg='';
		$errnum=0;
		$warr=0;
		$img_arr=array();
		dump($data);
		/*for ($i = 3; $i <= 170; $i++) {
			$arr[$i]['title']=$data->sheets[0]['cells'][$i][1];
			$arr[$i]['cid']=$this->city($data->sheets[0]['cells'][$i][2]);
			$time=$this->get_time($data->sheets[0]['cells'][$i][3]);
			$arr[$i]['showstart']=$time['st'];
			$arr[$i]['showend']=$time['et'];
			$arr[$i]['channel']=10;
			
			$l=explode('_',$data->sheets[0]['cells'][$i][4]);
			$arr[$i]['dir']=$path.$filename.'/'.$l['0'].'/';
			$arr[$i]['writer']=$data->sheets[0]['cells'][$i][4];
			
			$arr[$i]['industry']=$l['1'];//id和语言
			
			$arr[$i]['albumnum']=$data->sheets[0]['cells'][$i][5];
			$arr[$i]['typeid']=$data->sheets[0]['cells'][$i][6];
			$arr[$i]['typeid2']=$data->sheets[0]['cells'][$i][7];
			$arr[$i]['contact']=$this->br_or_b($data->sheets[0]['cells'][$i][9]);
			$arr[$i]['maps']=$this->br_or_b($data->sheets[0]['cells'][$i][10]);
			
			$arr[$i]['fair']['product']=$this->br_or_b($data->sheets[0]['cells'][$i][8]);
			$arr[$i]['fair']['description']=$this->br_or_b($data->sheets[0]['cells'][$i][11]);
			$arr[$i]['fair']['website']=$data->sheets[0]['cells'][$i][12];
			$arr[$i]['source']=$data->sheets[0]['cells'][$i][13];
		}*/
		
	}//end events
	
}//end ExAction