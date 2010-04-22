<?php
//返回格林威治标准时间
function MyDate($format='Y-m-d H:i:s',$timest=0){
	$cfg_cli_time=C('CLI_TIME');
	$addtime = $cfg_cli_time * 3600;
	if(empty($format))
	{
		$format = 'Y-m-d H:i:s';
	}
	return gmdate ($format,$timest+$addtime);
}

function GetMkTime($dtime){
	$cfg_cli_time=C('CLI_TIME');
	if(!ereg("[^0-9]",$dtime))	{
		return $dtime;
	}
	$dtime = trim($dtime);
	$dt = Array(1970,1,1,0,0,0);
	$dtime = ereg_replace("[\r\n\t]|日|秒"," ",$dtime);
	$dtime = str_replace("年","-",$dtime);
	$dtime = str_replace("月","-",$dtime);
	$dtime = str_replace("时",":",$dtime);
	$dtime = str_replace("分",":",$dtime);
	$dtime = trim(ereg_replace("[ ]{1,}"," ",$dtime));
	$ds = explode(" ",$dtime);
	$ymd = explode("-",$ds[0]);
	if(!isset($ymd[1]))	{
		$ymd = explode(".",$ds[0]);
	}
	if(isset($ymd[0]))	{
		$dt[0] = $ymd[0];
	}
	if(isset($ymd[1]))	{
		$dt[1] = $ymd[1];
	}
	if(isset($ymd[2]))	{
		$dt[2] = $ymd[2];
	}
	if(strlen($dt[0])==2)	{
		$dt[0] = '20'.$dt[0];
	}
	if(isset($ds[1]))	{
		$hms = explode(":",$ds[1]);
		if(isset($hms[0]))		{
			$dt[3] = $hms[0];
		}
		if(isset($hms[1]))		{
			$dt[4] = $hms[1];
		}
		if(isset($hms[2]))		{
			$dt[5] = $hms[2];
		}
	}
	foreach($dt as $k=>$v)	{
		$v = ereg_replace("^0{1,}",'',trim($v));
		if($v==''){
			$dt[$k] = 0;
		}
	}
	$mt = @gmmktime($dt[3],$dt[4],$dt[5],$dt[1],$dt[2],$dt[0]) - 3600 * $cfg_cli_time;
	if(!empty($mt))	{
		return $mt;
	}else{
		return time();
	}
}

function SubDay($ntime,$ctime){
	$dayst = 3600 * 24;
	$cday = ceil(($ntime-$ctime)/$dayst);
	return $cday;
}

function AddDay($ntime,$aday){
	$dayst = 3600 * 24;
	$oktime = $ntime + ($aday * $dayst);
	return $oktime;
}

function GetDateTimeMk($mktime){
	return MyDate('Y-m-d H:i:s',$mktime);
}

function GetDateMk($mktime){
	return MyDate("Y-m-d",$mktime);
}
function get_client_ip() {
	if (getenv ( "HTTP_CLIENT_IP" ) && strcasecmp ( getenv ( "HTTP_CLIENT_IP" ), "unknown" ))
		$ip = getenv ( "HTTP_CLIENT_IP" );
	else if (getenv ( "HTTP_X_FORWARDED_FOR" ) && strcasecmp ( getenv ( "HTTP_X_FORWARDED_FOR" ), "unknown" ))
		$ip = getenv ( "HTTP_X_FORWARDED_FOR" );
	else if (getenv ( "REMOTE_ADDR" ) && strcasecmp ( getenv ( "REMOTE_ADDR" ), "unknown" ))
		$ip = getenv ( "REMOTE_ADDR" );
	else if (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], "unknown" ))
		$ip = $_SERVER ['REMOTE_ADDR'];
	else
		$ip = "unknown";
	return ($ip);
}
/**
	 +----------------------------------------------------------
 * 产生随机字串，可用来自动生成密码
 * 默认长度6位 字母和数字混合 支持中文
	 +----------------------------------------------------------
 * @param string $len 长度
 * @param string $type 字串类型
 * 0 字母 1 数字 其它 混合
 * @param string $addChars 额外字符
	 +----------------------------------------------------------
 * @return string
	 +----------------------------------------------------------
 */
function rand_string($len = 6, $type = '', $addChars = '') {
	$str = '';
	switch ($type) {
		case 0 :
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
			break;
		case 1 :
			$chars = str_repeat ( '0123456789', 3 );
			break;
		case 2 :
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
			break;
		case 3 :
			$chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
			break;
		default :
			// 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
			$chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
			break;
	}
	if ($len > 10) { //位数过长重复字符串一定次数
		$chars = $type == 1 ? str_repeat ( $chars, $len ) : str_repeat ( $chars, 5 );
	}
	if ($type != 4) {
		$chars = str_shuffle ( $chars );
		$str = substr ( $chars, 0, $len );
	} else {
		// 中文随机字
		for($i = 0; $i < $len; $i ++) {
			$str .= msubstr ( $chars, floor ( mt_rand ( 0, mb_strlen ( $chars, 'utf-8' ) - 1 ) ), 1 );
		}
	}
	return $str;
}

function myencode($string) {
   $data = base64_encode($string);
   $data = str_replace(array('+','/','='),array('-','_',''),$data);
   return $data;
}

function mydecode($string) {
   $data = str_replace(array('-','_'),array('+','/'),$string);
   $mod4 = strlen($data) % 4;
   if ($mod4) {
       $data .= substr('====', $mod4);
   }
   return base64_decode($data);
}
/**
 * 返回格式化文件尺寸
 * 
 * @param Int $size 文件尺寸单位（B）
 */
function RealSize($size)
{
	if ($size < 1024)
	{
		return $size.' Byte';
	}
	if ($size < 1048576)
	{
		return round($size / 1024, 2).' KB';
	}
	if ($size < 1073741824)
	{
		return round($size / 1048576, 2).' MB';
	}
	if ($size < 1099511627776)
	{
		return round($size / 1073741824, 2).' GB';
	}
}

function makeoption($table,$id='id',$where='',$option='title',$order,$outadd){
	//$option外键的说明
	//$where外键的过滤
	//$f_idvalue外键的id
	//$idt现在的值
	if($option==''){$option='title';}
	if($where==''){$where='';}
	if($id==''){$id='';}
	if($order==''){$order='id desc';}
	//if($outadd==''){$outadd='>';}
		$thisdao=D($table);
		//$thisdao->Cache(true);
		$options=explode(',',$option);
		$list=$thisdao->where($where)->field($option.',id')->order($order)->findAll();
		for ($i = 0; $i < count($list); $i++) {
				$optionkey[]=$list[$i][$id];
				foreach($options as $optionlist){
					$var.=$outadd.$list[$i][$optionlist];
				}
				$optionval[]=$var;
				$var='';
			}
		$tmp=array_combine($optionkey,$optionval);

	return  $tmp;
}


function getsorttype($talbe){

	 if($_GET['order']==$talbe){
           if($_GET['sort']=="desc"){
            		$sorttype="asc";
            }else{
            		$sorttype="desc";
            }
    }else{
        $sorttype="asc";
    }
    	return $sorttype;
}

function getsortimg($talbe){

	 if($_GET['order']==$talbe){
           if($_GET['sort']=="desc"){
            		$sorttype="asc";
            }else{
            		$sorttype="desc";
            }
		$sortimg= '<img border="0" src="'.APP_PUBLIC_PATH.'/images/'.$sorttype.'.gif">';
		return $sortimg;
    }
}
function clean_html($value){
  $value = htmlspecialchars($value, ENT_QUOTES);
  $value = strtr($value, array(
    '('   => '&#40;',
    ')'   => '&#41;'
  ));
  return nl2br($value);
}


function un_clean_html($value){
  $value = htmlspecialchars_decode($value);
  $value = strtr($value, array(
    '&#40;'   => '(',
    '&#41;'   => ')'
  ));
  return nl2br($value);
}


function toDate($time,$format='Y年m月d日 H:i:s')
{
	if( empty($time)) {
		return '';
	}
    $format = str_replace('#',':',$format);
	return date(auto_charset($format),$time);
}


function substrGB($in,$num)
    {
      //$num=16;
      $pos=0;
      $byteNum=0;
      $out="";
      while($num){

    $c=mb_substr($in,$pos,1,"EUC-JP");
    if($c=="n") break;
    if(strlen($c)==1){
      $pos++;
      $byteNum++;
      if($byteNum>$num) break;
      $out.=$c;
    }
    else
      {
        $pos++;
        $byteNum=$byteNum+2;
        if($byteNum>$num) break;
        $out.=$c;
      }
      }
      return $out;
}

?>