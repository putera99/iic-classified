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
		if(empty($filename)){
			echo '<a href='.__URL__."/index/fname/1_20/>1_20</a><br>";
			echo '<a href='.__URL__."/index/fname/21_40/>21_40</a><br>";
			echo '<a href='.__URL__."/index/fname/41_60/>41_60</a><br>";
			echo '<a href='.__URL__."/index/fname/61_80/>61_80</a><br>";
			echo '<a href='.__URL__."/index/fname/81_100/>81_100</a><br>";
			echo '<a href='.__URL__."/index/fname/101_120/>101_120</a><br>";
			echo '<a href='.__URL__."/index/fname/121_140/>121_140</a><br>";
			echo '<a href='.__URL__."/index/fname/141_160/>141_160</a><br>";
			echo '<a href='.__URL__."/index/fname/161_180/>161_180</a><br>";
			echo '<a href='.__URL__."/index/fname/181_200/>181_200</a><br>";
			exit();
		}
		$path="Public/excel/";
		$file=$path.$filename.'.xls';
		$arr=array();
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('utf-8');
		
		$data->read($file);
		//dump($data);
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
		//dump($data->sheets[0]);
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
			
			if($arr[$i]['albumnum'] || $arr[$i]['albumnum']=='0'){//是否有图片
				$flist=$this->file_list($arr[$i]['dir']);//获取文件列表
				$img=count($flist);
				$num=$arr[$i]['albumnum']=='0'?'1':$arr[$i]['albumnum'];
				if($num!=$img){//检查文件数量
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
		//dump($arr);
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
						//dump($d['pic']);
						if(!empty($d['pic'])){
							$this->add_photo($aid,$d['pic'],$d['writer'],'11');
						}
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
		if(strpos($t,'_')){
			$t=explode("-",$t);
			$st=explode('/',str_replace('.','/',$t['0']));
			$st=mktime('0',0,0,$st['1'],$st['2'],$st['0']);
			$et=explode('/',str_replace('.','/',$t['1']));
			$et=mktime('0',0,0,$et['1'],$et['2'],$et['0']);
		}else{
			$t=$this->excel_time($t);
			$st=explode('/',str_replace('.','/',$t));
			$st=mktime('0',0,0,$st['1'],$st['2'],$st['0']);
			$et=mktime('0',0,0,date('m'),date('d'),date('Y')+1);
		}
		return array('st'=>$st,'et'=>$et);
	}//end function_name
	
	
	function br_or_b($str) {
		$str=deletehtml($str);
		return str_replace(array("[b]",'[/b]','||'),array("<b>",'</b>','<br>'),$str);
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
			default:
				//dump($str);
				//$this->error('城市名称不匹配');
				$str='0';
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
	
	function add_photo($aid,$filename,$title,$xtype){
		$dao=D("Pic");
		//if(is_array($filename)){
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
		/*}else{
			$data=array();
			$data['xid']=$aid;
			$data['filename']=$filename;
			$data['title']=$title;
			$data['xtype']=$xtype;
			$vo=array();
			$vo=$dao->create($data);
			$dao->add($vo);
		}*/
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
		$act=$_GET['act'];
		if(empty($filename) && $act!='hebing'){
			echo '<a href='.__URL__."/events/fname/bj1_100/>bj1_100</a><br>";
			echo '<a href='.__URL__."/events/fname/gz1_39/>gz1_39</a><br>";
			echo '<a href='.__URL__."/events/fname/sh1_168/>sh1_168</a><br>";
			echo '<a href='.__URL__."/events/fname/sh169_230/>sh169_230</a><br>";
			echo '<a href='.__URL__."/events/fname/sz1_31/>sz1_31</a><br>";
			echo '<a href='.__URL__."/events/act/hebing/>合并数据</a><br>";
			exit();
		}
		if($act=='hebing'){
			$newarr=array();
			$arr1=S('bj1_100');
			$arr2=S('gz1_39');
			$arr3=S('sh1_168');
			$arr4=S('sh169_230');
			$arr5=S('sz1_31');
			$newarr=array_merge($arr1,$arr2,$arr3,$arr4,$arr5);
			shuffle($newarr);
			S('hebing',$newarr,6000);
			if(S('hebing')){
				echo "<a href='".__URL__."/events/'>返回</a><br>";
				echo '写入成功';
				
			}else{
				echo '写入bu成功';
			}
			exit();
		}
		$path="Public/events/";
		$file=$path.$filename.'.xls';
		$arr=array();
		$data = new Spreadsheet_Excel_Reader();
		$data->setOutputEncoding('utf-8');
		
		$data->read($file);
		
		//error_reporting(E_ALL ^ E_NOTICE);
		
		//echo $data->sheets[0]['numRows'];
		//echo $data->sheets[0]['numCols'];
		//echo $data->sheets[0]['cells'][3][8];
		
		if(($act=='ok' || $act=='fdb')&& !empty($filename)){
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
		if(empty($data->sheets[0])){
			//dump($data->formatRecords);
			$this->error("0");
		}
		for ($i=2; $i <= 220; $i++) {
			if($data->sheets[0]['cells'][$i][16]=='1' || empty($data->sheets[0]['cells'][$i][1])){
				continue;
			}
			$l=$data->sheets[0]['cells'][$i][4];
			if(strpos($l,'_')){
				$l=end(explode('_',$l));
			}

			//dump($data->sheets[0]['cells'][$i]);
			$arr[$i]['title']=$data->sheets[0]['cells'][$i][1];
			$arr[$i]['cid']=$this->city($data->sheets[0]['cells'][$i][2]);
			if($arr[$i]['cid']=='0'){
				continue;
			}
			$msg.='CID '.$arr[$i]['cid'].'___';
			$time=$this->get_time($data->sheets[0]['cells'][$i][3]);
			$arr[$i]['showstart']=$time['st'];
			$arr[$i]['showend']=$time['et'];
			$arr[$i]['channel']=10;
			
			
			$arr[$i]['dir']=$path.$filename.'/'.$l.'/';
			
			$arr[$i]['writer']=$data->sheets[0]['cells'][$i][4];
			
			if(!empty($data->sheets[0]['cells'][$i][5]) || $data->sheets[0]['cells'][$i][5]=='0'){
				$arr[$i]['albumnum']=$data->sheets[0]['cells'][$i][5];
			}
			if(empty($data->sheets[0]['cells'][$i][6])){
				continue;
			}
			$arr[$i]['typeid']=$this->get_etype($data->sheets[0]['cells'][$i][6]);
			$msg.='typeid '.$arr[$i]['typeid'].'___';
			if(!empty($data->sheets[0]['cells'][$i][7])){
				$arr[$i]['typeid2']=$data->sheets[0]['cells'][$i][7];
			}
			$arr[$i]['maps']=$this->br_or_b($data->sheets[0]['cells'][$i][9]);
			$arr[$i]['event']['detail']=$this->br_or_b($data->sheets[0]['cells'][$i][8]);
			
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
					$img=count($flist);
					$num=$arr[$i]['albumnum']+1;
					if($num!=$img){
						$msg.=$data->sheets[0]['cells'][$i][4].'图片数量不正确实际图：'.$img.'填写图片:'.$arr[$i]['albumnum']."<br>\n";
						$errnum++;
					}else{
						$msg.=$data->sheets[0]['cells'][$i][4]."信息图片匹配<br>\n";
						foreach ($flist as $v){
							$type=Image::getImageInfo($v);
							if($type['type']=='bmp'){
								$msg.='<b>'.$v.'图片格式不能是bmp</b><br>';
								$warr++;
							}elseif(($act=='ok' || $act=='fdb')&& !empty($filename)){//是否确认写入
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
									$spath='Public/event/exl'.$filename.'/'.$dir;
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
									$spath='Public/event/exl'.$filename.'/'.$dir;
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
					$msg.='<b>'.$data->sheets[0]['cells'][$i][4]."信息无图片</b><br>\n";
					$warr++;
				}
		}//end 循环读取数据
		
		if($act=='ok'){
			$return='';
			$arc=D("Archives");
			$event=D("Event");
			$arr=S('hebing');
			foreach ($arr as $d){
				if(!empty($d['title']) && !empty($d['typeid'])){
					$acr_vo='';
					$arc_vo=$arc->create($d);
					$aid=$arc->add($arc_vo);
					if($aid){
						$event_vo=$event->create($d['event']);
						$event_vo['aid']=$aid;
						$eid=$event->add($event_vo);
						$this->add_photo($aid,$d['pic'],$d['writer'],11);
						$return.=$d['writer']."写入成功！<br>\n";
					}else{
						$return.=$d['writer']."主档写入失败！<br>\n";
					}
				}else{
					$return.=$d['writer']."标题或分类为空写入失败！<br>\n";
				}
			}
			echo $return;
		}elseif($act=='fdb'){
			S($filename,$arr,6000);
			if(S($filename)){
				echo "<a href='".__URL__."/events/'>返回</a><br>";
				echo '写入成功';
				
			}else{
				echo '写入bu成功';
			}
		}else{			
			echo '共有'.$errnum.'错误!<br>'.$warr."警告!<br>\n";
			echo $msg;
			
			echo "<a href='".__URL__."/events/act/fdb/fname/".$filename."'>写入缓存</a><br>";
			echo "<a href='".__URL__."/events/act/ok/fname/".$filename."'>写入数据</a>";
		}
		
		
	}//end events
	
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
			if(!empty($d['title']) && !empty($d['typeid'])){
				$acr_vo='';
				$arc_vo=$arc->create($d);
				$aid=$arc->add($arc_vo);
				if($aid){
					$event_vo=$event->create($d['event']);
					$event_vo['aid']=$aid;
					$eid=$event->add($event_vo);
					$this->add_photo($aid,$d['pic'],$d['writer'],11);
					$return.=$d['writer']."写入成功！<br>\n";
				}else{
					$return.=$d['writer']."主档写入失败！<br>\n";
				}
			}else{
				$return.=$d['writer']."标题或分类为空写入失败！<br>\n";
			}
		}
		echo $return;
	}//end event_add
	
	/**
	   *sm
	   *@date 2010-7-22
	   *@time 下午03:18:51
	   */
	function test_arr() {
		//sm
		
		$arr1=S('bj1_100');
		$arr2=S('gz1_39');
		$arr3=S('sh1_168');
		$arr4=S('sh169_230');
		$arr5=S('sz1_31');
		dump(S('hebing'));
	}//end test_arr
	
	/**
	   *查出活动类别
	   *@date 2010-7-16
	   *@time 上午11:53:57
	   */
	function get_etype($name) {
		//查出活动类别
		$arr=array();
		$arr=S("etype");
		if(empty($arr)){
			$dao=D("Arctype");
			$arr=$dao->where("channeltype=10")->field('id,typename')->findAll();
			S('etype',$arr,60000);
		}
		$typeid='';
		$name=strtolower(trim($name));
		$name=str_replace(' ','',$name);
		$name=$name=='festival'?'festivals':$name;
		foreach ($arr as $v){
			$v['typename']=strtolower(trim($v['typename']));
			$v['typename']=str_replace(' ','',$v['typename']);
			if($v['typename']==$name){
				$typeid=$v['id'];
			}else{
				continue;
			}
		}
		if($typeid){
			return $typeid;
		}else{
			return $name;
		}
	}//end get_etype
	
	
	/**
	   *导入用户头像
	   *@date 2010-7-20
	   *@time 下午02:14:37
	   */
	function ex_avatar() {
		//导入用户头像
		$dao=new Model();
		$count=$dao->Table("cdb_memberfields")->count();
		import("ORG.Util.Page");//引用分页类
		$p= new page($count,200);
		$page=$p->show();//显示分页
		$this->assign("showpage_bot",$page);//显示分页
		$limit=$p->firstRow.",".$p->listRows;//设定分面的大小
		$limit=($limit==",")?'':$limit;//分页的大小
    	$data=$dao->Table("cdb_memberfields")->limit($limit)->findAll();
    	$member=D("Members");
    	echo $page;
    	foreach ($data as $v){
    		//dump($v['avatar']);
    		if($v['avatar']){
	    		$info=array();
	    		$info['id']=$v['uid'];
	    		$info['avatar']=$v['avatar'];
	    		$member->save($info);
	    		dump($member->getLastSql());
    		}else{
    			continue;
    		}
    	}
    	echo $page;
	}//end ex_avatar
	
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
	
	/**
	   *ex_user_mail
	   *@date 2010-7-24
	   *@time 下午02:41:49
	   */
	function ex_user_mail() {
		//ex_user_mail
		$dao=D("Members");
		$info=$dao->where("groupid=10")->findAll();
		foreach($info as $v){
			if($v['email']){
				echo $v['username'].'<br>'.$v['email'].'<br><br>';
			}
		}
	}//end ex_user_mail
}//end ExAction