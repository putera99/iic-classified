<?php
function dafen($cstar,$cmember){
    return empty($cstar)?0:round(($cstar/($cmember*5)),2)*10;
}
function get_username() {
	return $_SESSION['username'];
}// END get_username

function get_uid() {
	return $_SESSION['uid'];
}// END get_uid

//返回格林威治标准时间
function MyDate($format='Y-m-d H:i:s',$timest=0){
	$cfg_cli_time=C('CLI_TIME');
	$addtime = $cfg_cli_time * 3600;
	if(empty($format))
	{
		$format = 'Y-m-d H:i:s';
	}
	return gmdate($format,$timest+$addtime);
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


function toImg($img){
	//return '<img src="/'.$img.'" width="88" height="31" />';
	return '<a href="/'.$img.'" target="_blank">查看图片</a>';
}
function ru($url,$p=array(),$h=true,$r=false) {
	return U($url,$p,$r,$h);
}
function IP($ip = '', $file = 'UTFWry.dat') {
	$_ip = array ();
	if (isset ( $_ip [$ip] )) {
		return $_ip [$ip];
	} else {
		import ( "ORG.Net.IpLocation" );
		$iplocation = new IpLocation ( $file );
		$location = $iplocation->getlocation ( $ip );
		$_ip [$ip] = $location ['country'] . $location ['area'];
	}
	return $_ip [$ip];
}

/**
 +----------------------------------------------------------
 * 获取登录验证码 默认为4位数字
 +----------------------------------------------------------
 * @param string $fmode 文件名
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
function build_verify($length = 4, $mode = 1) {
	return rand_string ( $length, $mode );
}

function toDate($time, $format = 'Y-m-d H:i:s') {
	if (empty ( $time )) {
		return '';
	}
	$format = str_replace ( '#', ':', $format );
	return date ($format, $time );
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

function dhtml($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = dhtml($val);
		}
	} else {
		$string = str_replace(array('"', '\'', '<', '>', "\t", "\r", '{', '}'), array('&quot;', '&#39;', '&lt;', '&gt;', '&nbsp;&nbsp;', '', '&#123;', '&#125;'), $string);
	}
	return $string;
}

function delete_html($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = shtmlspecialchars($val);
		}
	} else {
		$string = preg_replace("/<(\/?(p|strong|span).*?)>/si","",$string);
		$string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
			str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
	}
	return nl2br($string);
}

/**
 * 转换网址
 */
function cvhttp($http){
	if ($http==''){
		return $http;
	}else {
		$link=substr($http,0,7)=="http://"?$http:'http://'.$http;
		return $link;
	}
	
}
function htmlCv($string) {
	$pattern = array('/(javascript|jscript|js|vbscript|vbs|about):/i','/on(mouse|exit|error|click|dblclick|key|load|unload|change|move|submit|reset|cut|copy|select|start|stop)/i','/<script([^>]*)>/i','/<iframe([^>]*)>/i','/<frame([^>]*)>/i','/<link([^>]*)>/i','/@import/i');
	$replace = array('','','&lt;script${1}&gt;','&lt;iframe${1}&gt;','&lt;frame${1}&gt;','&lt;link${1}&gt;','');
	$string = preg_replace($pattern, $replace, $string);
	$string = str_replace(array('</script>', '</iframe>', '&#'), array('&lt;/script&gt;', '&lt;/iframe&gt;', '&amp;#'), $string);
	return stripslashes($string);
}

function checkisbn($isbn) {
	$isbn10 = ereg_replace("[^0-9X]","",$isbn);
	$checkdigit = 11 - ( ( 10 * substr($isbn10,0,1) + 9 * substr($isbn10,1,1) + 8 * substr($isbn10,2,1) + 7 * substr($isbn10,3,1) + 6 * substr($isbn10,4,1) + 5 * substr($isbn10,5,1) + 4 * substr($isbn10,6,1) + 3 * substr($isbn10,7,1) + 2 * substr($isbn10,8,1) ) % 11);
	if( $checkdigit == 10 ) $checkdigit = "X";
	if( $checkdigit == 11 ) $checkdigit = 0;
	if( $checkdigit == substr($isbn10,9,1) ){
		return true;
	}else {
		return false;
	}
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
?>