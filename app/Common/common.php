<?php

/**
 *格式时间
 *@date 2010-5-20
 *@time 下午08:35:01
 */
function toDate($time,$format='Y年m月d日 H:i:s'){
	if( empty($time)) {
		return '';
	}
    $format = str_replace('#',':',$format);
	return date(auto_charset($format),$time);
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

// 获取客户端IP地址
function client_ip(){
   if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
       $ip = getenv("HTTP_CLIENT_IP");
   else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
       $ip = getenv("HTTP_X_FORWARDED_FOR");
   else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
       $ip = getenv("REMOTE_ADDR");
   else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
       $ip = $_SERVER['REMOTE_ADDR'];
   else
       $ip = "unknown";
   return($ip);
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

/**
 *清除HTML和双引号
 *@date 2010-5-19
 *@time 下午02:20:40
 */
function clearh($string) {
	//清除HTML和双引号
	$string = preg_replace("/<(\/?.*?)>/si","",$string);
	//$string = preg_replace('"',"",$string);
	return $string;
}//end clearh

function delete_html($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = shtmlspecialchars($val);
		}
	} else {
		$string = preg_replace("/<(\/?(p|strong|span|o:p|a|st1).*?)>/si","",$string);
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