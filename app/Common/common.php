<?php

function alivv_ad_helper($url) {
	$content = '';
	$done = false;
	if (ini_get ( 'allow_url_fopen' ) == '1') {
		if ($fp = @fopen ( $url, 'r' )) {
			while ( $line = @fread ( $fp, 1024 ) ) {
				$content .= $line;
				$done = true;
			}
		}
	}
	if (! $done) {
		$parsedUrl = parse_url ( $url );
		$host = $parsedUrl ['host'];
		if (isset ( $parsedUrl ['path'] )) {
			$path = $parsedUrl ['path'];
		}
		$timeout = 10;
		$fp = @fsockopen ( $host, '80', $errno, $errstr, $timeout );
		if (! $fp) {
		} else {
			@fputs ( $fp, "GET $path HTTP/1.0" . "Host: $host" );
			while ( $line = @fread ( $fp, 4096 ) ) {
				$content .= $line;
			}
			@fclose ( $fp );
			$pos = strpos ( $content, "

" );
			$content = substr ( $content, $pos + 4 );
		}
	}
	return $content;
}
/* 将数组格式化为XML字符串 */
function array2xml($array, $level = 0) {
	$return = '';
	if ($level == 0) {
		$return = '<?xml version="1.0" encoding="utf-8" ?><root>';
	}
	foreach ( $array as $key => $item ) {
		if (! is_array ( $item )) {
			$return .= "<item key='{$key}'>{$item}</item>";
		} else {
			$return .= "<item key='{$key}'>";
			$return .= array2xml ( $item, $level + 1 );
			$return .= "</item>";
		}
	}
	if ($level == 0) {
		$return .= '</root>';
	}
	return $return;
}

/* 辅助函数用来获取DOM跟节点 */
function getXmlRoot($xml) {
	$doc = new DOMDocument ();
	$doc->loadXML ( $xml );
	$root = $doc->documentElement;
	return $root;
}

/* 将被array2xml格式化的XML还原 */
function xml2array($xml) {
	$return_array = array ();
	foreach ( $xml->childNodes as $node ) {
		$length = $node->childNodes->length;
		$key = $node->getAttribute ( 'key' );
		if ($length == 1 and $node->firstChild->nodeType == XML_TEXT_NODE) {
			$return_array [$key] = $node->nodeValue;
		} else {
			$return_array [$key] = xml2array ( $node );
		}
	}
	return $return_array;
}

function get_channel($type) {
	$ch = '';
	switch (true) {
		case $type == 1 :
			$ch = 'News';
			break;
		case $type == 2 :
			$ch = 'CityGuide';
			break;
		case $type == 4 :
			$ch = 'Jobs';
			break;
		case $type == 5 :
			$ch = 'Property';
			break;
		case $type == 6 :
			$ch = 'Commerce';
			break;
		case $type == 7 :
			$ch = 'Commerce';
			break;
		case $type == 8 :
			$ch = 'Personals';
			break;
		case $type == 9 :
			$ch = 'Services';
			break;
		
		case $type == 10 :
			$ch = 'Event';
			break;
		case $type == 11 :
			$ch = 'Fair';
			break;
		case $type == 12 :
			$ch = 'News';
			break;
		case $type == "group" :
			$ch = 'Group';
			break;
		case $type == "13" :
			$ch = 'Group';
			break;
		case $type == "14" :
			$ch = 'Magazine';
			break;
	}
	return $ch;
} //end function_name


/**
 *返回城市名
 *@date 2010-10-19
 *@time 上午09:42:15
 */
function get_cityname($str, $sm = '') {
	//返回城市名
	$smname = '';
	switch ($str) {
		case '0' :
			$str = 'All';
			$smname = 'All';
			break;
		case '2' :
			$str = 'Beijing';
			$smname = 'BJ';
			break;
		case '1' :
			$str = 'Guangzhou';
			$smname = 'GZ';
			break;
		case '3' :
			$str = 'Shanghai';
			$smname = 'SH';
			break;
		case '4' :
			$str = 'Shenzhen';
			$smname = 'SZ';
			break;
		case '6' :
			$str = 'Dongguan';
			$smname = 'DG';
			break;
		case '7' :
			$str = 'Qingdao';
			break;
		case '8' :
			$str = 'Yiwu';
			break;
		case '9' :
			$str = 'Hongkong';
			break;
		case '10' :
			$str = 'Macao';
			break;
		case '11' :
			$str = 'Chengdu';
			break;
		case '12' :
			$str = 'Nanning';
			break;
		case '13' :
			$str = 'Tianjin';
			break;
		case '14' :
			$str = 'Chongqing';
			break;
		case '15' :
			$str = "Xi'An";
			break;
		case "16" :
			$str = 'qingyuan';
			break;
		case "17" :
			$str = 'nanJing';
			break;
		case "18" :
			$str = 'FuZhou';
			break;
		case "19" :
			$str = 'NingBo';
			break;
		case "20" :
			$str = 'JiNan';
			break;
		case "21" :
			$str = 'WuHan';
			break;
		case "22" :
			$str = 'Dalian';
			break;
		case "23" :
			$str = 'Shenyang';
			break;
		case "24" :
			$str = 'Suzhou';
			break;
		case "25" :
			$str = 'Hangzhou';
			break;
		case "26" :
			$str = 'Kunming';
			break;
		
		default :
			$str = '';
			$smname = '';
			break;
	}
	if ($sm) {
		$str = $smname == '' ? $str : $smname;
	}
	return $str;
} //end get_cityname


/**
 *将支付串转成网址适应格式
 *@date 2011-1-13 / @time 下午05:45:02
 */
function str_to_url($str, $str2 = '') {
	//将支付串转成网址适应格式
	$str = Pinyin ( $str, 1 );
	$str = substr ( strtolower ( str_replace ( ' ', '-', str_replace ( '  ', ' ', trim ( preg_replace ( "/[^_a-zA-Z0-9-]/", " ", $str ), " -" ) ) ) ), 0, 100 );
	if ($str2 != '') {
		$str = substr ( $str, 0, 90 ) . $str2;
	}
	return $str;
} //end str_to_url


//将汉字专为拼音start
function Pinyin($_String, $_Code = 'gb2312') {
	$_DataKey = "a|ai|an|ang|ao|ba|bai|ban|bang|bao|bei|ben|beng|bi|bian|biao|bie|bin|bing|bo|bu|ca|cai|can|cang|cao|ce|ceng|cha" . "|chai|chan|chang|chao|che|chen|cheng|chi|chong|chou|chu|chuai|chuan|chuang|chui|chun|chuo|ci|cong|cou|cu|" . "cuan|cui|cun|cuo|da|dai|dan|dang|dao|de|deng|di|dian|diao|die|ding|diu|dong|dou|du|duan|dui|dun|duo|e|en|er" . "|fa|fan|fang|fei|fen|feng|fo|fou|fu|ga|gai|gan|gang|gao|ge|gei|gen|geng|gong|gou|gu|gua|guai|guan|guang|gui" . "|gun|guo|ha|hai|han|hang|hao|he|hei|hen|heng|hong|hou|hu|hua|huai|huan|huang|hui|hun|huo|ji|jia|jian|jiang" . "|jiao|jie|jin|jing|jiong|jiu|ju|juan|jue|jun|ka|kai|kan|kang|kao|ke|ken|keng|kong|kou|ku|kua|kuai|kuan|kuang" . "|kui|kun|kuo|la|lai|lan|lang|lao|le|lei|leng|li|lia|lian|liang|liao|lie|lin|ling|liu|long|lou|lu|lv|luan|lue" . "|lun|luo|ma|mai|man|mang|mao|me|mei|men|meng|mi|mian|miao|mie|min|ming|miu|mo|mou|mu|na|nai|nan|nang|nao|ne" . "|nei|nen|neng|ni|nian|niang|niao|nie|nin|ning|niu|nong|nu|nv|nuan|nue|nuo|o|ou|pa|pai|pan|pang|pao|pei|pen" . "|peng|pi|pian|piao|pie|pin|ping|po|pu|qi|qia|qian|qiang|qiao|qie|qin|qing|qiong|qiu|qu|quan|que|qun|ran|rang" . "|rao|re|ren|reng|ri|rong|rou|ru|ruan|rui|run|ruo|sa|sai|san|sang|sao|se|sen|seng|sha|shai|shan|shang|shao|" . "she|shen|sheng|shi|shou|shu|shua|shuai|shuan|shuang|shui|shun|shuo|si|song|sou|su|suan|sui|sun|suo|ta|tai|" . "tan|tang|tao|te|teng|ti|tian|tiao|tie|ting|tong|tou|tu|tuan|tui|tun|tuo|wa|wai|wan|wang|wei|wen|weng|wo|wu" . "|xi|xia|xian|xiang|xiao|xie|xin|xing|xiong|xiu|xu|xuan|xue|xun|ya|yan|yang|yao|ye|yi|yin|ying|yo|yong|you" . "|yu|yuan|yue|yun|za|zai|zan|zang|zao|ze|zei|zen|zeng|zha|zhai|zhan|zhang|zhao|zhe|zhen|zheng|zhi|zhong|" . "zhou|zhu|zhua|zhuai|zhuan|zhuang|zhui|zhun|zhuo|zi|zong|zou|zu|zuan|zui|zun|zuo";
	
	$_DataValue = "-20319|-20317|-20304|-20295|-20292|-20283|-20265|-20257|-20242|-20230|-20051|-20036|-20032|-20026|-20002|-19990" . "|-19986|-19982|-19976|-19805|-19784|-19775|-19774|-19763|-19756|-19751|-19746|-19741|-19739|-19728|-19725" . "|-19715|-19540|-19531|-19525|-19515|-19500|-19484|-19479|-19467|-19289|-19288|-19281|-19275|-19270|-19263" . "|-19261|-19249|-19243|-19242|-19238|-19235|-19227|-19224|-19218|-19212|-19038|-19023|-19018|-19006|-19003" . "|-18996|-18977|-18961|-18952|-18783|-18774|-18773|-18763|-18756|-18741|-18735|-18731|-18722|-18710|-18697" . "|-18696|-18526|-18518|-18501|-18490|-18478|-18463|-18448|-18447|-18446|-18239|-18237|-18231|-18220|-18211" . "|-18201|-18184|-18183|-18181|-18012|-17997|-17988|-17970|-17964|-17961|-17950|-17947|-17931|-17928|-17922" . "|-17759|-17752|-17733|-17730|-17721|-17703|-17701|-17697|-17692|-17683|-17676|-17496|-17487|-17482|-17468" . "|-17454|-17433|-17427|-17417|-17202|-17185|-16983|-16970|-16942|-16915|-16733|-16708|-16706|-16689|-16664" . "|-16657|-16647|-16474|-16470|-16465|-16459|-16452|-16448|-16433|-16429|-16427|-16423|-16419|-16412|-16407" . "|-16403|-16401|-16393|-16220|-16216|-16212|-16205|-16202|-16187|-16180|-16171|-16169|-16158|-16155|-15959" . "|-15958|-15944|-15933|-15920|-15915|-15903|-15889|-15878|-15707|-15701|-15681|-15667|-15661|-15659|-15652" . "|-15640|-15631|-15625|-15454|-15448|-15436|-15435|-15419|-15416|-15408|-15394|-15385|-15377|-15375|-15369" . "|-15363|-15362|-15183|-15180|-15165|-15158|-15153|-15150|-15149|-15144|-15143|-15141|-15140|-15139|-15128" . "|-15121|-15119|-15117|-15110|-15109|-14941|-14937|-14933|-14930|-14929|-14928|-14926|-14922|-14921|-14914" . "|-14908|-14902|-14894|-14889|-14882|-14873|-14871|-14857|-14678|-14674|-14670|-14668|-14663|-14654|-14645" . "|-14630|-14594|-14429|-14407|-14399|-14384|-14379|-14368|-14355|-14353|-14345|-14170|-14159|-14151|-14149" . "|-14145|-14140|-14137|-14135|-14125|-14123|-14122|-14112|-14109|-14099|-14097|-14094|-14092|-14090|-14087" . "|-14083|-13917|-13914|-13910|-13907|-13906|-13905|-13896|-13894|-13878|-13870|-13859|-13847|-13831|-13658" . "|-13611|-13601|-13406|-13404|-13400|-13398|-13395|-13391|-13387|-13383|-13367|-13359|-13356|-13343|-13340" . "|-13329|-13326|-13318|-13147|-13138|-13120|-13107|-13096|-13095|-13091|-13076|-13068|-13063|-13060|-12888" . "|-12875|-12871|-12860|-12858|-12852|-12849|-12838|-12831|-12829|-12812|-12802|-12607|-12597|-12594|-12585" . "|-12556|-12359|-12346|-12320|-12300|-12120|-12099|-12089|-12074|-12067|-12058|-12039|-11867|-11861|-11847" . "|-11831|-11798|-11781|-11604|-11589|-11536|-11358|-11340|-11339|-11324|-11303|-11097|-11077|-11067|-11055" . "|-11052|-11045|-11041|-11038|-11024|-11020|-11019|-11018|-11014|-10838|-10832|-10815|-10800|-10790|-10780" . "|-10764|-10587|-10544|-10533|-10519|-10331|-10329|-10328|-10322|-10315|-10309|-10307|-10296|-10281|-10274" . "|-10270|-10262|-10260|-10256|-10254";
	$_TDataKey = explode ( '|', $_DataKey );
	$_TDataValue = explode ( '|', $_DataValue );
	
	$_Data = (PHP_VERSION >= '5.0') ? array_combine ( $_TDataKey, $_TDataValue ) : _Array_Combine ( $_TDataKey, $_TDataValue );
	arsort ( $_Data );
	reset ( $_Data );
	
	if ($_Code != 'gb2312')
		$_String = _U2_Utf8_Gb ( $_String );
	$_Res = '';
	for($i = 0; $i < strlen ( $_String ); $i ++) {
		$_P = ord ( substr ( $_String, $i, 1 ) );
		
		if ($_P > 160) {
			$_Q = ord ( substr ( $_String, ++ $i, 1 ) );
			$_P = $_P * 256 + $_Q - 65536;
		}
		$_Res .= _Pinyin ( $_P, $_Data );
	}
	return $_Res; //preg_replace ( "/[^a-zA-Z0-9]*/", '', $_Res );
}

function _Pinyin($_Num, $_Data) {
	if ($_Num > 0 && $_Num < 160)
		return chr ( $_Num );
	elseif ($_Num < - 20319 || $_Num > - 10247)
		return '';
	else {
		foreach ( $_Data as $k => $v ) {
			if ($v <= $_Num)
				break;
		}
		return $k . '-';
	}
}

function _U2_Utf8_Gb($_C) {
	/*$_String = '';
	if ($_C < 0x80)
		$_String .= $_C;
	elseif ($_C < 0x800) {
		$_String .= chr ( 0xC0 | $_C >> 6 );
		$_String .= chr ( 0x80 | $_C & 0x3F );
	} elseif ($_C < 0x10000) {
		$_String .= chr ( 0xE0 | $_C >> 12 );
		$_String .= chr ( 0x80 | $_C >> 6 & 0x3F );
		$_String .= chr ( 0x80 | $_C & 0x3F );
	} elseif ($_C < 0x200000) {
		$_String .= chr ( 0xF0 | $_C >> 18 );
		$_String .= chr ( 0x80 | $_C >> 12 & 0x3F );
		$_String .= chr ( 0x80 | $_C >> 6 & 0x3F );
		$_String .= chr ( 0x80 | $_C & 0x3F );
	}*/
	return iconv ( 'UTF-8', 'GB2312//IGNORE', $_C );
}

function _Array_Combine($_Arr1, $_Arr2) {
	for($i = 0; $i < count ( $_Arr1 ); $i ++)
		$_Res [$_Arr1 [$i]] = $_Arr2 [$i];
	return $_Res;
}
//将汉字专为拼音end


/**
 *获取数组的前几个
 *@date 2010-10-20
 *@time 下午02:59:29
 */
function rand_arr($arr, $num, $split = ',') {
	//获取数组的前几个
	$str = '';
	if (! is_array ( $arr )) { //传入数组
		$arr = trim ( $str, $split );
		$arr = trim ( $str );
		$arr = explode ( $split, $arr );
	}
	shuffle ( $arr );
	$num = count ( $arr ) >= $num ? $num : count ( $arr );
	for($i = 0; $i <= $num; $i ++) {
		$str .= $arr [$i] . $split;
	}
	return trim ( $str, $split );
} //end function_name


/**
 *匹配展会编码
 *@date 2010-9-18
 *@time 下午06:04:53
 */
function get_fair_lang($w) {
	//匹配展会编码
	if (strstr ( $w, '_' )) {
		$w = explode ( '_', $w );
		if (empty ( $w ['0'] )) {
			$w = time () . rand_string ( 5, 2 );
		} else {
			$w = $w ['0'];
		}
	} else {
		$w = time () . rand_string ( 5, 2 );
	}
	return array ('0' => $w . '_EN', '1' => $w . '_CN' );
} //end get_fair_lang
/**
 *编码需要打开的网址
 *@date 2010-9-8
 *@time 下午06:06:25
 */
function jump($url, $id) {
	//编码需要打开的网址
	$url = $url . '||' . $id;
	$url = myencode ( $url );
	return __APP__ . '/link/' . $url;
} //end function_name
/**
 *针对图片数组组合图片地址
 *@date 2010-8-13
 *@time 下午07:00:47
 */
function out_images($pic = array(), $thumb = 'm') {
	//针对图片数组组合图片地址
	if ($pic ['remote']) {
		$image = picurl ( $pic ['remote'] );
	} elseif ($pic ['thumb']) {
		if ($thumb == 'm') {
			$image = picurl ( $pic ['filepath'] . 'm_' . $pic ['filename'] );
		} else {
			$image = picurl ( $pic ['filepath'] . 's_' . $pic ['filename'] );
		}
	} else {
		$image = picurl ( $pic ['filename'] );
	}
	return $image;
} //end out_images


function picurl($url) {
	$arr = explode ( '/', $url );
	if ($arr ['0'] == 'article') {
		$url = 'http://www.beingfunchina.com/upload_files/' . $url;
	} elseif ($arr ['0'] == 'customavatars') {
		$url = '/Public/' . $url;
	} elseif ($arr ['0'] == 'Public') {
		$url = '/' . $url;
	} elseif ($arr ['0'] == '.') {
		$url = str_replace ( "./Public", '/Public', $url );
	} elseif ($arr ['0'] == 'http:') {
		$url = $url;
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
	if (empty ( $url )) {
		$url = '/Public/img/default-avatar.jpg';
	} else {
		$url = picurl ( $url );
	}
	return $url;
} //end avatar


/**
 *获取用户头像
 *@date 2010-7-21
 *@time 上午11:18:53
 */
function get_avatar($uid) {
	//获取用户头像
	$dao = D ( "Members" );
	$condition = array ("id" => $uid );
	$avatar = $dao->where ( $condition )->field ( "avatar" )->find ();
	//dump($dao->getLastSql());
	$avatar = avatar ( $avatar ['avatar'] );
	return $avatar;
} //end function_name
function br2nl($text) {
	return preg_replace ( '/<br\\s*?\/??>/i', '', $text );
}

function arrayischk($arr, $value = '-100') {
	$re = '';
	if ($value == '-100') {
		$re = empty ( $arr ) ? '' : 'checked="checked"';
	} else {
		$re = ($arr == $value) ? 'checked="checked"' : '';
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
function array2string($array, $split = ',') {
	if (is_array ( $array )) {
		$dot = '';
		foreach ( $array as $data ) {
			$string .= $dot . $data;
			$dot = $split;
		}
		return empty ( $string ) ? '' : $string;
	} else {
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
function string2checked($sring, $param, $split = ',', $type = 'checked') {
	$splitParam = explode ( $split, $param );
	if (in_array ( $sring, $splitParam ))
		$result = $type == 'checked' || $type == 'selected' ? " $type=$type" : "$type";
	return $result;
}

/**
 *
 * 下拉框，单选按钮 自动选择
 * @param  $string 输入字符
 * @param  $param 条件
 * @param  $type 类型  selected checked
 * @return   string
 */
function selected($string, $param = 1, $type = 'select') {
	if ($string == $param) {
		$returnString = $type == 'select' ? 'selected="selected"' : 'checked="checked"';
	}
	return $returnString;
}

/**
 * 状态选择
 * @param  $string 待对比字符
 * @param  $param 对比条件,二维数组
 * @return string 返回符合条件数据
 */

function status($string, $param = array('0'=>'NO', '1'=>'OK')) {
	foreach ( $param as $key => $data ) {
		if ($string == $key) {
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
	$t = explode ( '/', $oldtime );
	return mktime ( '0', 0, 0, $t ['1'], $t ['0'], $t ['2'] );
} //end ftime


/**
 *获取群组级别
 *@date 2010-6-7
 *@time 下午09:50:09
 */
function get_grade($id, $arr = array()) {
	//获取群组级别
	if (empty ( $arr )) {
		$arr = array (1 => 'Founder', 2 => 'Admin', 3 => 'VIP', 4 => 'Member', 5 => 'Exclude' );
	}
	return $arr [$id];
} //end get_grade


/**
 *获取群组名称
 *@date 2010-6-7
 *@time 下午09:42:30
 */
function get_info($gid, $field = 'groupname', $table = "Group") {
	//获取群组名称
	$dao = D ( $table );
	$condition = array ();
	$condition ['id'] = $gid;
	$info = $dao->where ( $condition )->field ( $field )->find ();
	if ($field != '*' && ! empty ( $field )) {
		$info = $info [$field];
	}
	return $info;
} //end get_info


/**
 *给出类名
 *@date 2010-5-29
 *@time 下午02:59:17
 */
function get_type($type) {
	$ch = '';
	switch (true) {
		case $type == 1 :
			$ch = 'Arc';
			break;
		case $type == 2 :
			$ch = 'CityGuide';
			break;
		case $type >= 4 && $type <= 9 :
			$ch = 'Classifieds';
			break;
		case $type == 10 :
			$ch = 'Event';
			break;
		case $type == 11 :
			$ch = 'Biz';
			break;
		case $type == 12 :
			$ch = 'Arc';
			break;
		case $type == "group" :
			$ch = 'Group';
			break;
		case $type == "13" :
			$ch = 'Group';
			break;
		case $type == "14" :
			$ch = 'Magazine';
			break;
	}
	return $ch;
} //end function_name


/**
 *给出类名
 *@date 2010-5-29
 *@time 下午02:59:17
 */
function get_url($type, $model = "show") {
	$ch = array ();
	switch (true) {
		case $type == 1 :
			$ch ['list'] = 'Arc/ls/id';
			$ch ['show'] = 'article';
			break;
		case $type == 2 :
			$ch ['list'] = 'cglist';
			$ch ['show'] = 'ctgs';
			break;
		case $type >= 4 && $type <= 9 :
			$ch ['list'] = 'clist';
			$ch ['show'] = 'clss';
			break;
		case $type == 10 :
			$ch ['list'] = 'Event/ls/id';
			$ch ['show'] = 'evts';
			break;
		case $type == 11 :
			$ch ['list'] = 'Biz/ls/id';
			$ch ['show'] = 'fair';
			break;
		case $type == 12 :
			$ch ['list'] = 'Arc/ls/id';
			$ch ['show'] = 'article';
			break;
		case $type == "group" :
			$ch ['list'] = 'Group/ls/id';
			$ch ['show'] = 'grps';
			break;
		case $type == "13" :
			$ch ['list'] = 'Group/ls/id';
			$ch ['show'] = 'grps';
			break;
	}
	return $ch [$model];
} //end function_name


function get_arctype($typeid, $field = 'typename') {
	$arctype = D ( "Arctype" );
	$info = $arctype->where ( "id=$typeid" )->find ();
	if ($field) {
		return $info [$field];
	} else {
		return $info;
	}
}

function get_ltd($ltd_id, $field) {
	$dao = D ( "Ltd" );
	$info = $dao->where ( "id=$ltd_id" )->find ();
	//dump($info);
	if ($field) {
		return $info [$field];
	} else {
		return $info;
	}
}

function get_title($aid, $type = '1') {
	if ($type != 'group') {
		$dao = D ( "Archives" );
		$title = $dao->where ( "id=$aid" )->field ( "title" )->find ();
		unset ( $dao );
	} else {
		$dao = D ( "Group" );
		$title = $dao->where ( "id=$aid" )->field ( "groupname" )->find ();
		unset ( $dao );
	}
	return $title ['title'];
}
function get_city($cid, $field) {
	$dao = M ( "ActCity" );
	$city = $dao->where ( "id=$cid" )->find ();
	if ($field) {
		//$city[$field]=$_SESSION['flang']=='CN'?$city['cename']:$city[$field];
		return $city [$field];
	} else {
		return $city;
	}
}

function getkey($arr, $k) {
	$narr = array ();
	foreach ( $arr as $v ) {
		$narr [$k] = $v;
	}
	return $narr;
}

/**
 *格式时间
 *@date 2010-5-20
 *@time 下午08:35:01
 */
function toDate($time, $format = 'Y-m-d H:i:s') {
	if (empty ( $time )) {
		return '';
	}
	$format = str_replace ( '#', ':', $format );
	return date ( auto_charset ( $format ), $time );
}
function get_username() {
	return empty ( $_SESSION ['username'] ) ? '0' : $_SESSION ['username'];
} // END get_username


function get_day() {
	return date ( "d" );
}

function get_uid() {
	return empty ( $_SESSION ['uid'] ) ? '0' : $_SESSION ['uid'];
} // END get_uid


//返回格林威治标准时间
function MyDate($format = 'Y-m-d H:i:s', $timest = 0) {
	$cfg_cli_time = C ( 'CLI_TIME' );
	$addtime = $cfg_cli_time * 3600;
	if (empty ( $format )) {
		$format = 'Y-m-d H:i:s';
	}
	return gmdate ( $format, $timest + $addtime );
}

function GetMkTime($dtime) {
	$cfg_cli_time = C ( 'CLI_TIME' );
	if (! ereg ( "[^0-9]", $dtime )) {
		return $dtime;
	}
	$dtime = trim ( $dtime );
	$dt = Array (1970, 1, 1, 0, 0, 0 );
	$dtime = ereg_replace ( "[\r\n\t]|日|秒", " ", $dtime );
	$dtime = str_replace ( "年", "-", $dtime );
	$dtime = str_replace ( "月", "-", $dtime );
	$dtime = str_replace ( "时", ":", $dtime );
	$dtime = str_replace ( "分", ":", $dtime );
	$dtime = trim ( ereg_replace ( "[ ]{1,}", " ", $dtime ) );
	$ds = explode ( " ", $dtime );
	$ymd = explode ( "-", $ds [0] );
	if (! isset ( $ymd [1] )) {
		$ymd = explode ( ".", $ds [0] );
	}
	if (isset ( $ymd [0] )) {
		$dt [0] = $ymd [0];
	}
	if (isset ( $ymd [1] )) {
		$dt [1] = $ymd [1];
	}
	if (isset ( $ymd [2] )) {
		$dt [2] = $ymd [2];
	}
	if (strlen ( $dt [0] ) == 2) {
		$dt [0] = '20' . $dt [0];
	}
	if (isset ( $ds [1] )) {
		$hms = explode ( ":", $ds [1] );
		if (isset ( $hms [0] )) {
			$dt [3] = $hms [0];
		}
		if (isset ( $hms [1] )) {
			$dt [4] = $hms [1];
		}
		if (isset ( $hms [2] )) {
			$dt [5] = $hms [2];
		}
	}
	foreach ( $dt as $k => $v ) {
		$v = ereg_replace ( "^0{1,}", '', trim ( $v ) );
		if ($v == '') {
			$dt [$k] = 0;
		}
	}
	$mt = @gmmktime ( $dt [3], $dt [4], $dt [5], $dt [1], $dt [2], $dt [0] ) - 3600 * $cfg_cli_time;
	if (! empty ( $mt )) {
		return $mt;
	} else {
		return time ();
	}
}

function SubDay($ntime, $ctime) {
	$dayst = 3600 * 24;
	$cday = ceil ( ($ntime - $ctime) / $dayst );
	return $cday;
}

function AddDay($ntime, $aday) {
	$dayst = 3600 * 24;
	$oktime = $ntime + ($aday * $dayst);
	return $oktime;
}

function GetDateTimeMk($mktime) {
	return MyDate ( 'Y-m-d H:i:s', $mktime );
}

function GetDateMk($mktime) {
	return MyDate ( "Y-m-d", $mktime );
}

function toImg($img, $type) {
	if ($type == 'event') {
		$img = empty ( $img ) ? __PUBLIC__ . '/img/bz.gif' : $img;
	} elseif ($type == 'biz') {
		$img = empty ( $img ) ? __PUBLIC__ . '/img/bz2.gif' : $img;
	} elseif ($type == 'group') {
		$img = empty ( $img ) ? __PUBLIC__ . '/img/bz3.gif' : $img;
	} elseif ($type == 'classifieds') {
		$img = empty ( $img ) ? __PUBLIC__ . '/img/bz5.gif' : $img;
	} elseif ($type == 'cityguide') {
		$img = empty ( $img ) ? __PUBLIC__ . '/img/bz4.gif' : $img;
	}
	return picurl ( $img );
}
function ru($url, $p = array(), $h = true, $r = false) {
	return U ( $url, $p, $r, $h );
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
function client_ip() {
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

function myencode($string) {
	$data = base64_encode ( $string );
	$data = str_replace ( array ('+', '/', '=' ), array ('-', '_', '' ), $data );
	return $data;
}

function mydecode($string) {
	$data = str_replace ( array ('-', '_' ), array ('+', '/' ), $string );
	$mod4 = strlen ( $data ) % 4;
	if ($mod4) {
		$data .= substr ( '====', $mod4 );
	}
	return base64_decode ( $data );
}

function dhtml($string) {
	if (is_array ( $string )) {
		foreach ( $string as $key => $val ) {
			$string [$key] = dhtml ( $val );
		}
	} else {
		$string = str_replace ( array ('"', '\'', '<', '>', "\t", "\r", '{', '}' ), array ('&quot;', '&#39;', '&lt;', '&gt;', '&nbsp;&nbsp;', '', '&#123;', '&#125;' ), $string );
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
	$string = strip_tags ( $string ); //preg_replace("/<(\/?.*?)>/si","",$string);
	$string = str_replace ( '"', ",", $string );
	return $string;
} //end clearh


function delete_html($string) {
	if (is_array ( $string )) {
		foreach ( $string as $key => $val ) {
			$string [$key] = shtmlspecialchars ( $val );
		}
	} else {
		$string = preg_replace ( "/<(\/?(p|strong|span|o:p|a|st1).*?)>/si", "", $string );
		$string = preg_replace ( '/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1', str_replace ( array ('&', '"', '<', '>' ), array ('&amp;', '&quot;', '&lt;', '&gt;' ), $string ) );
	}
	return nl2br ( $string );
}

function deletehtml($str) {
	$str = trim ( $str );
	$str = strip_tags ( $str, "" );
	$str = preg_replace ( "{\t}", "", $str );
	$str = preg_replace ( "{\r\n}", "", $str );
	$str = preg_replace ( "{\r}", "", $str );
	$str = preg_replace ( "{\n}", "", $str );
	$str = preg_replace ( "{   }", " ", $str );
	$str = preg_replace ( "{  }", " ", $str );
	return $str;
}

/**
 * 转换网址
 */
function cvhttp($http) {
	if ($http == '') {
		return $http;
	} else {
		$link = substr ( $http, 0, 7 ) == "http://" ? $http : 'http://' . $http;
		return $link;
	}

}
function htmlCv($string) {
	$pattern = array ('/(javascript|jscript|js|vbscript|vbs|about):/i', '/on(mouse|exit|error|click|dblclick|key|load|unload|change|move|submit|reset|cut|copy|select|start|stop)/i', '/<script([^>]*)>/i', '/<iframe([^>]*)>/i', '/<frame([^>]*)>/i', '/<link([^>]*)>/i', '/@import/i' );
	$replace = array ('', '', '&lt;script${1}&gt;', '&lt;iframe${1}&gt;', '&lt;frame${1}&gt;', '&lt;link${1}&gt;', '' );
	$string = preg_replace ( $pattern, $replace, $string );
	$string = str_replace ( array ('</script>', '</iframe>', '&#' ), array ('&lt;/script&gt;', '&lt;/iframe&gt;', '&amp;#' ), $string );
	return stripslashes ( $string );
}

function checkisbn($isbn) {
	$isbn10 = ereg_replace ( "[^0-9X]", "", $isbn );
	$checkdigit = 11 - ((10 * substr ( $isbn10, 0, 1 ) + 9 * substr ( $isbn10, 1, 1 ) + 8 * substr ( $isbn10, 2, 1 ) + 7 * substr ( $isbn10, 3, 1 ) + 6 * substr ( $isbn10, 4, 1 ) + 5 * substr ( $isbn10, 5, 1 ) + 4 * substr ( $isbn10, 6, 1 ) + 3 * substr ( $isbn10, 7, 1 ) + 2 * substr ( $isbn10, 8, 1 )) % 11);
	if ($checkdigit == 10)
		$checkdigit = "X";
	if ($checkdigit == 11)
		$checkdigit = 0;
	if ($checkdigit == substr ( $isbn10, 9, 1 )) {
		return true;
	} else {
		return false;
	}
}

/**
 * 返回格式化文件尺寸
 * 
 * @param Int $size 文件尺寸单位（B）
 */
function RealSize($size) {
	if ($size < 1024) {
		return $size . ' Byte';
	}
	if ($size < 1048576) {
		return round ( $size / 1024, 2 ) . ' KB';
	}
	if ($size < 1073741824) {
		return round ( $size / 1048576, 2 ) . ' MB';
	}
	if ($size < 1099511627776) {
		return round ( $size / 1073741824, 2 ) . ' GB';
	}
}
?>