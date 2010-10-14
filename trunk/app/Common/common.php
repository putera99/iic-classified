<?php

/**
   *匹配展会编码
   *@date 2010-9-18
   *@time 下午06:04:53
   */
function get_fair_lang($w) {
	//匹配展会编码
	if(strstr($w,'_')){
		$w=explode('_',$w);
		if(empty($w['0'])){
			$w=time().rand_string(5,2);
		}else{
			$w=$w['0'];
		}
	}else{
		$w=time().rand_string(5,2);
	}
	return array('0'=>$w.'_EN','1'=>$w.'_CN');
}//end get_fair_lang
/**
   *编码需要打开的网址
   *@date 2010-9-8
   *@time 下午06:06:25
   */
function jump($url,$id) {
    //编码需要打开的网址
    $url=$url.'||'.$id;
    $url=myencode($url);
    return __APP__.'/link/'.$url;
}//end function_name
/**
   *针对图片数组组合图片地址
   *@date 2010-8-13
   *@time 下午07:00:47
   */
function out_images($pic=array(),$thumb='m') {
	//针对图片数组组合图片地址
	if($pic['remote']){
		$image=picurl($pic['remote']);
	}elseif($pic['thumb']){
		if($thumb=='m'){
			$image=picurl($pic['filepath'].'m_'.$pic['filename']);
		}else{
			$image=picurl($pic['filepath'].'s_'.$pic['filename']);
		}
	}else{
		$image=picurl($pic['filename']);
	}
	return $image;
}//end out_images

function picurl($url){
	$arr=explode('/',$url);
	if ($arr['0']=='article') {
		$url='http://www.beingfunchina.com/upload_files/'.$url;
	}elseif($arr['0']=='customavatars'){
		$url='/Public/'.$url;
	}elseif($arr['0']=='Public'){
		$url='/'.$url;
	}elseif($arr['0']=='.'){
		$url=str_replace("./Public",'/Public',$url);
	}elseif($arr['0']=='http:'){
		$url=$url;
	}
	return $url;
}

/**
   *用户头像
   *@date 2010-7-20
   *@time 下午02:50:52
   */
function avatar($url) {
	//用户头像
	if(empty($url)){
		$url='/Public/img/default-avatar.jpg';
	}else{
		$url=picurl($url);
	}
	return $url;
}//end avatar

/**
   *获取用户头像
   *@date 2010-7-21
   *@time 上午11:18:53
   */
function get_avatar($uid) {
	//获取用户头像
	$dao=D("Members");
	$condition=array("id"=>$uid);
	$avatar=$dao->where($condition)->field("avatar")->find();
	//dump($dao->getLastSql());
	$avatar=avatar($avatar['avatar']);
	return $avatar;
}//end function_name
function br2nl($text) {    
	return preg_replace('/<br\\s*?\/??>/i', '', $text);   
} 

function arrayischk($arr,$value='-100'){
	$re='';
	if($value=='-100'){
		$re=empty($arr)?'': 'checked="checked"';
	}else{
		$re=($arr==$value)? 'checked="checked"':'';
	}
	return $re;
}

/**
* 将数组用指定字符连接
*
* @param  $array 数组
* @param  $split 分隔符
* @return  string
*/
function array2string($array, $split = ','){
    if(is_array($array)){
    	$dot='';
        foreach ($array as $data){
            $string .= $dot.$data;
            $dot = $split;
        }
        return empty($string) ? '' : $string;
    }else{
        return $array;
    }
}

/**
* 将指定数据转换成数组再对比，符合条件则返回checked
*
* @param  $sring 输入字符
* @param  $param 条件
* @param  $split 分隔符
* @return  string
*/
function string2checked($sring ,$param, $split = ',',$type='checked'){
    $splitParam = explode($split, $param);
    if (in_array($sring, $splitParam)) $result = " $type=$type";
    return $result;
}


/**
*
*  下拉框，单选按钮 自动选择
* @param  $string 输入字符
* @param  $param 条件
* @param  $type 类型  selected checked
* @return   string
*/
function selected($string, $param =1, $type = 'select'){
    if ($string == $param) {
        $returnString = $type == 'select' ? 'selected="selected"' : 'checked="checked"' ;
    }
    return $returnString;
}

/**
* 状态选择
* @param  $string 待对比字符
* @param  $param 对比条件,二维数组
* @return string 返回符合条件数据
*/

function status($string, $param = array('0'=>'NO', '1'=>'OK')){
    foreach($param as $key => $data) {
        if($string == $key){
            $str = $data;
        }
    }
    return $str;
}

/**
 *格式化时间
 *@date 2010-6-12
 *@time 下午12:02:45
 */
function ftime($oldtime) {
	//格式化时间
	$t=explode('/',$oldtime);
	return mktime('0',0,0,$t['1'],$t['0'],$t['2']);
}//end ftime

/**
   *获取群组级别
   *@date 2010-6-7
   *@time 下午09:50:09
   */
function get_grade($id,$arr=array()) {
	//获取群组级别
	if (empty($arr)) {
		$arr=array(
			1=>'Founder',
			2=>'Admin',
			3=>'VIP',
			4=>'Member',
			5=>'Exclude',
		);
	}
	return $arr[$id];
}//end get_grade

/**
   *获取群组名称
   *@date 2010-6-7
   *@time 下午09:42:30
   */
function get_info($gid,$field='groupname',$table="Group") {
	//获取群组名称
	$dao=D($table);
	$condition=array();
	$condition['id']=$gid;
	$info=$dao->where($condition)->field($field)->find();
	if($field!='*' && !empty($field)){
		$info=$info[$field];
	}
	return $info;
}//end get_info

/**
 *给出类名
 *@date 2010-5-29
 *@time 下午02:59:17
 */
function get_type($type) {
	$ch='';
	switch (true) {
		case $type==1:
			$ch='Art';
		break;
		case $type==2:
			$ch='CityGuide';
		break;
		case $type>=4 && $type<=9:
			$ch='Classifieds';
		break;
		case $type==10:
			$ch='Event';
		break;
		case $type==11:
			$ch='Biz';
		break;
		case $type==12:
			$ch='Art';
		break;
		case $type=="group":
			$ch='Group';
		break;
		case $type=="13":
			$ch='Group';
		break;
		case $type=="14":
			$ch='Magazine';
		break;
	}
	return $ch;
}//end function_name


function get_arctype($typeid,$field='typename'){
	$arctype=D("Arctype");
	$info=$arctype->where("id=$typeid")->find();
	if($field){
		return $info[$field];
	}else{
		return $info;
	}
}

function get_ltd($ltd_id,$field){
	$dao=D("Ltd");
	$info=$dao->where("id=$ltd_id")->find();
	//dump($info);
	if($field){
		return $info[$field];
	}else{
		return $info;
	}
}

function get_title($aid,$type='1'){
	if($type!='group'){
		$dao=D("Archives");
		$title=$dao->where("id=$aid")->field("title")->find();
		unset($dao);
	}else{
		$dao=D("Group");
		$title=$dao->where("id=$aid")->field("groupname")->find();
		unset($dao);
	}
	return $title['title'];
}
function get_city($cid,$field){
	$dao=M("ActCity");
	$city=$dao->where("id=$cid")->find();
	if($field){
		//$city[$field]=$_SESSION['flang']=='CN'?$city['cename']:$city[$field];
		return $city[$field];
	}else{
		return $city;
	}
}

function getkey($arr,$k) {
	$narr=array();
	foreach ($arr as $v){
		$narr[$k]=$v;
	}
	return $narr;
}

/**
 *格式时间
 *@date 2010-5-20
 *@time 下午08:35:01
 */
function toDate($time,$format='Y-m-d H:i:s'){
	if(empty($time)) {
		return '';
	}
    $format = str_replace('#',':',$format);
	return date(auto_charset($format),$time);
}
function get_username() {
	return empty($_SESSION['username'])?'0':$_SESSION['username'];
}// END get_username

function get_day(){
	return date("d");
}

function get_uid() {
	return empty($_SESSION['uid'])?'0':$_SESSION['uid'];
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


function toImg($img,$type){
	if($type=='event'){
		$img=empty($img)?__PUBLIC__.'/img/bz.gif':$img;
	}elseif($type=='biz'){
		$img=empty($img)?__PUBLIC__.'/img/bz2.gif':$img;
	}elseif($type=='group'){
		$img=empty($img)?__PUBLIC__.'/img/bz3.gif':$img;
	}elseif($type=='classifieds'){
		$img=empty($img)?__PUBLIC__.'/img/bz5.gif':$img;
	}elseif($type=='cityguide'){
		$img=empty($img)?__PUBLIC__.'/img/bz4.gif':$img;
	}
	return picurl($img);
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
	$string = preg_replace('"',"",$string);
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

function deletehtml($str){
	$str = trim($str);
	$str=strip_tags($str,"");
	$str=preg_replace("{\t}","",$str);
	$str=preg_replace("{\r\n}","",$str);
	$str=preg_replace("{\r}","",$str);
	$str=preg_replace("{\n}","",$str);
	$str=preg_replace("{   }"," ",$str);
	$str=preg_replace("{  }"," ",$str);
	return $str;
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
function RealSize($size){
	if ($size < 1024){
		return $size.' Byte';
	}
	if ($size < 1048576){
		return round($size / 1024, 2).' KB';
	}
	if ($size < 1073741824){
		return round($size / 1048576, 2).' MB';
	}
	if ($size < 1099511627776){
		return round($size / 1073741824, 2).' GB';
	}
}
?>