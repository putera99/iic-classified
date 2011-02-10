<?php
class IndexAction extends CommonAction {
	protected $pcid = ''; //页面查询用的独立城市ID
	

	/**
	 *预处理
	 *@date 2010-4-30
	 *@time 上午10:08:20
	 */
	function _initialize() {
		//预处理
		parent::_initialize ();
		if (intval ( $_GET ['cid'] )) {
			$this->pcid = intval ( $_GET ['cid'] );
		} else {
			//$this->_set_cid();
			//$this->cid=empty($this->user['usercid'])?$_SESSION['cid']:$this->user['usercid'];
			$this->cid = empty ( $this->cid ) ? cookie ( 'cid' ) : $this->cid;
			$this->pcid = empty ( $this->cid ) ? '0' : $this->cid;
		}
	
	} //end _initialize()
	

	public function index() {
		$kinfo = ''; //标题后面附加的城市后缀
		if ($this->pcid) { //检查城市或者城市群
			if ($this->pcid > 1000) {
				$kinfo =$this->cgroup [$this->pcid] ['name'];
				$this->assign ( 'city_group', $this->cgroup [$this->pcid] ['name'] );
			} else {
				$kinfo =get_cityname ( $this->pcid );
			}
			$this->assign ( "cityname", $kinfo );
		} //检查城市或者城市群
		

		/*$this->assign('pick',$this->_new_list(2001,'h','0,1',$this->pcid));
    	$this->assign('pick2',$this->_new_list(2001,'p','0,2',$this->pcid));*/
		$slide=$this->_new_list ( '3001', 'l', '0,5', $this->pcid);
		//dump($slide);
		$this->assign ( 'slide',$slide);
		$pick8=$this->_new_list ( '', 'f', '0,20', $this->pcid, '11,12' );
		shuffle ( $pick8 );
		$this->assign ( 'pick8',$pick8);
		$this->assign ( 'do', $this->_new_list ( 2004, '', '0,1', $this->pcid ) );
		//$this->assign('biz_news',$this->_new_list(2003,'','0,10'));
		

		$group = $this->_get_group ( 'hot', '0,4' );
		$this->assign ( 'group', $group );
		
		$this->assign ( 'city_type', $this->_get_tree ( 1000 ) );
		$this->assign ( 'classifieds_type', $this->_get_tree ( 1 ) );
		$classifieds_type = $this->_get_classifieds_type ();
		
		$classifieds = array ();
		foreach ( $classifieds_type as $v ) {
			$classifieds = array_merge ( $classifieds, $this->_get_carc ( $v ['id'], '0,10', 0 ) ); //$this->pcid
		}
		$classifieds_ch = trim ( $classifieds_ch, ',' );
		shuffle ( $classifieds );
		$this->assign ( 'classifieds', $classifieds );
		//dump($classifieds);
		unset ( $classifieds );
		$dao = D ( "Archives" );
		$citygui_featured = array ();
		$condition = array ();
		$condition ['ismake'] = '1';
		$condition ['channel'] = '2';
		$condition ['_string'] = "FIND_IN_SET('f',`flag`) > 0";
		if ($this->pcid == '0') {
			$condition ['_string'] .= "";
		} elseif ($this->pcid < 1000) {
			$condition ['_string'] .= " AND (cid={$this->pcid} or cid='0')";
		} else {
			$city_temp = array ('name' => '', 'id' => '' );
			foreach ( $this->cgroup [$this->pcid] ['city'] as $k => $v ) {
				$city_temp ['id'] .= $k . ',';
				$city_temp ['name'] .= $v . ',';
			}
			$condition ['_string'] .= " AND (cid in ({$city_temp['id']}) or cid='0')";
		}
		$citygui_featured = $dao->where ( $condition )->order ( "pubdate DESC" )->limit ( "0,20" )->findAll ();
		shuffle ( $citygui_featured );
		$this->assign ( 'citygui_featured', $citygui_featured );
		unset ( $citygui_featured );
		
		$classifieds_featured = array ();
		$condition = array ();
		$condition ['ismake'] = '1';
		$classifieds_ch = '4,5,6,7,8,9';
		$condition ['channel'] = array ('in', "$classifieds_ch" );
		$condition ['_string'] = "FIND_IN_SET('f',`flag`) > 0";
		if ($this->pcid == '0') {
			$condition ['_string'] .= "";
		} elseif ($this->pcid < 1000) {
			$condition ['_string'] .= " AND (cid={$this->pcid} or cid='0')";
		} else {
			$city_temp = array ('name' => '', 'id' => '' );
			foreach ( $this->cgroup [$this->pcid] ['city'] as $k => $v ) {
				$city_temp ['id'] .= $k . ',';
				$city_temp ['name'] .= $v . ',';
			}
			$condition ['_string'] .= " AND (cid in ({$city_temp['id']}) or cid='0')";
		}
		$classifieds_featured = $dao->where ( $condition )->order ( "pubdate DESC" )->limit ( "0,20" )->findAll ();
		shuffle ( $classifieds_featured );
		$this->assign ( 'classifieds_featured', $classifieds_featured );
		unset ( $classifieds_featured, $dao );
		
		$event = array ();
		$event = $this->new_event ();
		$this->assign ( 'event', $event );
		$fair = array ();
		$fair = $this->new_fair ();
		$this->assign ( 'fair', $fair );
		
		$page = array ();
		$page ['title'] = '缤纷中国 Beingfunchina: Being fun in China, start with us!'.$kinfo;
		$page ['keywords'] = '缤纷中国,City Guide, Classifieds, Feature Columns, China Fairs, Events, E-magazines, Groups'.$kinfo;
		$page ['description'] = $kinfo.'缤纷中国 Beingfunchina is an English website providing practical and localized information, supporting free posts of classifieds and facilitating interpersonal communication for foreigners in China. Our channels include City Guide, Classifieds, Feature Columns, China Fairs, Events, E-magazines and Groups. ';
		
		$this->assign ( 'page', $page );
		$ad = array ();
		$ad ['right'] = '';
		$ads = M ( "Ad" );
		$condition = array ();
		$condition ['wz'] = 'index';
		$condition ['is_show'] = '1';
		$condition ['begintime'] = array ('lt', time () );
		$condition ['endtime'] = array ('gt', time () );
		$condition ['_string'] = "FIND_IN_SET('" . $this->pcid . "',`cid`) > 0";
		$adlist = $ads->where ( $condition )->findAll ();
		shuffle ( $adlist );
		foreach ( $adlist as $adl ) {
			if ($adl ['type'] == 'banner') {
				if (empty ( $adl ['adcode'] )) {
					$ad ['banner'] [] = '<a href="' . jump ( $adl ['adlink'], $adl ['id'] ) . '" target="' . $adl ['isclose'] . '" alt="' . $adl ['title'] . '"><img src="' . $adl ['picurl'] . '" /></a>';
				} else {
					$ad ['banner'] [] = $adl ['adcode'];
				}
			} elseif ($adl ['type'] == 'right') {
				if (empty ( $adl ['adcode'] )) {
					$ad ['right'] .= '<a href="' . jump ( $adl ['adlink'], $adl ['id'] ) . '" target="' . $adl ['isclose'] . '" alt="' . $adl ['title'] . '"><img src="' . $adl ['picurl'] . '" /></a><br>';
				} else {
					$ad ['right'] .= $adl ['adcode'];
				}
			} elseif ($adl ['type'] == 'left') {
				if (empty ( $adl ['adcode'] )) {
					$ad ['left'] .= '<a href="' . jump ( $adl ['adlink'], $adl ['id'] ) . '" target="' . $adl ['isclose'] . '" alt="' . $adl ['title'] . '"><img src="' . $adl ['picurl'] . '" /></a><br>';
				} else {
					$ad ['left'] .= $adl ['adcode'];
				}
			}
		}
		$this->assign ( 'ad', $ad );
		
		$chau_list = array ();
		/*$chau_list['0']['id']='1';
        $chau_list['0']['name']='Asia';
        $chau_list['0']['ename']='asia';*/
		
		$chau_list ['1'] ['id'] = '2';
		$chau_list ['1'] ['name'] = 'Europe';
		$chau_list ['1'] ['ename'] = 'europe';
		$chau_list ['1'] ['url'] = 'http://www.listenlive.eu';
		
		$chau_list ['2'] ['id'] = '3';
		$chau_list ['2'] ['name'] = 'Canadian';
		$chau_list ['2'] ['ename'] = 'canadian';
		
		$chau_list ['3'] ['id'] = '4';
		$chau_list ['3'] ['name'] = 'United States';
		$chau_list ['3'] ['ename'] = 'unitedstates';
		
		/*$chau_list['4']['id']='5';
        $chau_list['4']['name']='South America';
        $chau_list['4']['ename']='south_america';*/
		
		$chau_list ['5'] ['id'] = '6';
		$chau_list ['5'] ['name'] = 'Australia';
		$chau_list ['5'] ['ename'] = 'oceania';
		
		$chau_list ['6'] ['id'] = '7';
		$chau_list ['6'] ['name'] = 'New Zealand';
		$chau_list ['6'] ['ename'] = 'newzealand';
		
		$this->assign ( 'chau', $chau_list );
		
		$cityname = array (1 => array ('GUANGZHOU', 'CH006' ), 2 => array ('BEIJING', 'CH002' ), 3 => array ('SHANGHAI', 'CH024' ), 4 => array ('SHENZHEN', 'CH006' ) );
		$incity = empty ( $this->pcid ) ? $cityname ['2'] : $cityname [$this->pcid];
		$this->assign ( 'city_name', $incity );
		
		$dao=D("Magazines");
		$magazine_index=array();
		$condition=array("showtime"=>array('lt',time()),);
		$magazine_index=$dao->where($condition)->order("id DESC")->find();
		$this->assign ( 'magazine_index', $magazine_index );
		$this->display ();
		
	}
	
	/**
	 *获取最新活动
	 *@date 2010-6-17
	 *@time 上午09:51:38
	 */
	protected function new_event() {
		//获取最新活动
		$time = time ();
		$dao = D ( "Archives" );
		$condition = array ();
		$condition ['channel'] = '10';
		$condition ['ismake'] = '1';
		/*$condition ['showstart'] = array ('lt', $time );
		$condition ['showend'] = array ('gt', $time );*/
		$condition['_string']="FIND_IN_SET('r',`flag`) > 0";
		if ($this->pcid) {
			$condition ['cid'] = $this->pcid;
		}
		$list = $dao->where ( $condition )->order ( "edittime DESC,showstart DESC" )->limit ( "0,6" )->findAll ();
		return $list;
	} //end new_event
	

	/**
	 *获取最新展会
	 *@date 2010-6-17
	 *@time 上午09:51:38
	 */
	protected function new_fair() {
		//获取最新展会
		$dao = D ( "Archives" );
		$condition = array ();
		$condition ['channel'] = '11';
		$condition ['ismake'] = '1';
		$condition ['industry'] = 'EN';
		$list = $dao->where ( $condition )->order ( "id DESC" )->limit ( "0,5" )->findAll ();
		return $list;
	} //end new_fair
	

	/**
	 *radio
	 *@date 2010-6-11
	 *@time 上午10:09:18
	 */
	function radio() {
		//radio
		switch ($_GET ['act']) {
			case 'country' :
				$data = $this->get_chau ( $_GET ['chau'] );
				if ($data) {
					echo '<option>country Or province</option>';
					foreach ( $data as $v ) {
						if ($v ['province'] != '0') {
							echo '<option value="' . $v ['id'] . '">' . $v ['province'] . '</option>';
						} else {
							echo '<option value="' . $v ['id'] . '">' . $v ['country'] . '</option>';
						}
					}
				} else {
					echo '<option>Information is Null!</option>';
				}
				break;
			
			case 'radio' :
				$data = $this->get_radio ( $_GET ['country'], $_GET ['chau'] );
				$text = '';
				foreach ( $data as $v ) {
					//echo '<option value="'.$v['location'].'">'.$v['radio_name'].'</option>';
					//echo $v['location'].'<br>'.$v['radio_name'];
					$arr = explode ( '|+|', $v ['listen'] );
					//d($v);
					if (count ( $arr ) > 2) {
						for($i = 0; $i < count ( $arr ); $i = $i + 2) {
							$text .= '<option value="' . $arr [$i] . '">' . $v ['radio_name'] . '-->' . $arr [$i + 1] . "</option>\n";
						
		//$x=$i+1;
						}
					} else {
						$text .= '<option value="' . $arr ['0'] . '">' . $v ['radio_name'] . '</option>';
					}
				}
				echo $text;
				break;
			
			default :
				
				break;
		}
	} //end radio
	

	/**
	 *获取国家
	 *@date 2010-6-11
	 *@time 上午10:11:12
	 */
	function get_chau($chau) {
		//获取国家
		if ($chau == "unitedstates") {
			$data = array (0 => array ('id' => 0, 'chau' => 'unitedstates', 'country' => 'United States', 'province' => '0', 'location' => '0' ) );
		} elseif ($chau == "newzealand") {
			$data = array (0 => array ('id' => 0, 'chau' => 'newzealand', 'country' => 'New Zealand', 'province' => '0', 'location' => '0' ) );
		} else {
			$data = F ( $chau, '', APP_PATH . '/file/' );
		}
		return $data;
	} //end get_chau
	

	/**
	 *获取指定国家的电台
	 *@date 2010-6-11
	 *@time 上午10:15:24
	 */
	function get_radio($country, $chau) {
		//获取指定国家的电台
		if ($chau == "unitedstates") {
			$data = F ( $chau, '', APP_PATH . '/file/' );
		} elseif ($chau == "newzealand") {
			$data = F ( $chau, '', APP_PATH . '/file/' );
		} else {
			$coun = $chau . '_' . $country;
			$data = F ( $coun, '', APP_PATH . '/file/' . $chau . '/' );
		}
		return $data;
	
	} //end get_radio
	

	/**
	 *wr
	 *@date 2010-11-16
	 *@time 上午09:50:16
	 */
	function wr($id) { //获取澳大利亚的广播
		//wr
		exit ();
		import ( "@.Com.phpQuery", '', '.php' );
		$s = s ( 'test' );
		if (empty ( $s )) {
			$s = file_get_contents ( 'http://www.australianliveradio.com/' );
			s ( 'test', $s );
		}
		$doc = phpQuery::newDocumentHTML ( $s );
		$s = $doc ["html>body>div#mainwrap>div#content>div.thetable3:eq($id)>table>tbody>tr"]->htmlOuter ();
		$data = array ();
		$s = str_replace ( array ("\n", "\r" ), array ("<nr/>", "<rr/>" ), $s );
		$this->getrole ( $s );
		$preg = '<tr*td*a href="||"><b>||</b></a*<td>||</td*td>||</td*td>||</td*td>||</td>';
		preg_match_all ( '/' . $this->getrole ( $preg ) . '/i', $s, $arr, PREG_PATTERN_ORDER );
		$num = count ( $arr ['1'] );
		for($i = 0; $i < $num; $i ++) {
			$listen = array ();
			$data [$i] ['radio'] = $arr ['1'] [$i];
			$data [$i] ['radio_name'] = $arr ['2'] [$i];
			$data [$i] ['location'] = del_html ( $arr ['3'] [$i] );
			$data [$i] ['img'] = ''; //$arr['4'][$i];
			$data [$i] ['listen'] = '';
			preg_match_all ( '/href="(.*?)">(.*?)</i', $arr ['5'] [$i], $listen, PREG_PATTERN_ORDER );
			for($l = 0; $l < count ( $listen ['1'] ); $l ++) {
				$data [$i] ['listen'] .= $listen ['1'] [$l] . '|+|' . $listen ['2'] [$l] . '|+|';
			}
			$data [$i] ['listen'] = trim ( $data [$i] ['listen'], '|+|' );
			$data [$i] ['format'] = $arr ['6'] [$i];
		}
		return $data;
	} //end wr
	

	/**
	 *获取加拿大的广播
	 *@date 2010-11-16
	 *@time 上午11:17:59
	 */
	function wrcanadian($url) {
		//获取加拿大的广播
		exit ();
		//http://www.canadianwebradio.com/
		//$url="http://www.canadianwebradio.com/alberta.html";
		import ( "@.Com.phpQuery", '', '.php' );
		$s = file_get_contents ( $url );
		$doc = phpQuery::newDocumentHTML ( $s );
		$s = $doc ["html>body>div#mainwrap>div#content>div.thetable3>table>tbody>tr"]->htmlOuter ();
		$data = array ();
		$s = str_replace ( array ("\n", "\r" ), array ("<nr/>", "<rr/>" ), $s );
		$this->getrole ( $s );
		//$preg='<tr*td*a href="||"><b>||</b></a*<td>||</td*<td>||</td*<td>||</td*<td>||</td>';
		$preg = '<tr*<td>*a href="||">||</td*<td*</td*<td>||</td*<td>||</td*<td>||</td*<td>||</td*</tr>';
		preg_match_all ( '/' . $this->getrole ( $preg ) . '/i', $s, $arr, PREG_PATTERN_ORDER );
		$num = count ( $arr ['1'] );
		for($i = 0; $i < $num; $i ++) {
			$listen = array ();
			$data [$i] ['radio'] = $arr ['1'] [$i];
			$data [$i] ['radio_name'] = strip_tags ( $arr ['2'] [$i] );
			$data [$i] ['location'] = del_html ( $arr ['3'] [$i] );
			$data [$i] ['img'] = ''; //$arr['4'][$i];
			$data [$i] ['listen'] = '';
			preg_match_all ( '/href="(.*?)">(.*?)</i', $arr ['5'] [$i], $listen, PREG_PATTERN_ORDER );
			for($l = 0; $l < count ( $listen ['1'] ); $l ++) {
				$t = substr ( $listen ['1'] [$l], 0, 4 );
				if ($t == 'java') {
					preg_match_all ( "/\(\'(.*?)\'/i", $listen ['1'] [$l], $url2, PREG_PATTERN_ORDER );
					$listen ['1'] [$l] = $url2 ['1'] ['0'];
				}
				$data [$i] ['listen'] .= $listen ['1'] [$l] . '|+|' . $listen ['2'] [$l] . '|+|';
			}
			$data [$i] ['listen'] = trim ( $data [$i] ['listen'], '|+|' );
			$data [$i] ['format'] = $arr ['6'] [$i];
		}
		return $data;
	} //end wrcanadian
	

	/**
	 *获取美国的广播
	 *@date 2010-11-16
	 *@time 上午11:17:59
	 */
	function wrunitedstates() {
		//获取加拿大的广播
		exit ();
		//http://www.canadianwebradio.com/
		$url = "http://www.usliveradio.com/";
		import ( "@.Com.phpQuery", '', '.php' );
		$s = file_get_contents ( $url );
		$doc = phpQuery::newDocumentHTML ( $s );
		$s = $doc ["html>body>div#mainwrap>div#content>div.thetable3>table>tbody>tr"]->htmlOuter ();
		$data = array ();
		$s = str_replace ( array ("\n", "\r" ), array ("<nr/>", "<rr/>" ), $s );
		$this->getrole ( $s );
		//$preg='<tr*td*a href="||"><b>||</b></a*<td>||</td*<td>||</td*<td>||</td*<td>||</td>';
		$preg = '<tr*<td>*a href="||">||</td*<td*</td*<td>||</td*<td>||</td*<td>||</td*<td>||</td*</tr>';
		preg_match_all ( '/' . $this->getrole ( $preg ) . '/i', $s, $arr, PREG_PATTERN_ORDER );
		$num = count ( $arr ['1'] );
		for($i = 0; $i < $num; $i ++) {
			$listen = array ();
			$data [$i] ['radio'] = $arr ['1'] [$i];
			$data [$i] ['radio_name'] = strip_tags ( $arr ['2'] [$i] );
			$data [$i] ['location'] = del_html ( $arr ['3'] [$i] );
			$data [$i] ['img'] = ''; //$arr['4'][$i];
			$data [$i] ['listen'] = '';
			preg_match_all ( '/href="(.*?)">(.*?)</i', $arr ['5'] [$i], $listen, PREG_PATTERN_ORDER );
			for($l = 0; $l < count ( $listen ['1'] ); $l ++) {
				$t = substr ( $listen ['1'] [$l], 0, 4 );
				if ($t == 'java') {
					preg_match_all ( "/\(\'(.*?)\'/i", $listen ['1'] [$l], $url2, PREG_PATTERN_ORDER );
					$listen ['1'] [$l] = $url2 ['1'] ['0'];
				}
				$data [$i] ['listen'] .= $listen ['1'] [$l] . '|+|' . $listen ['2'] [$l] . '|+|';
			}
			$data [$i] ['listen'] = trim ( $data [$i] ['listen'], '|+|' );
			$data [$i] ['format'] = $arr ['6'] [$i];
		}
		return $data;
	} //end wrcanadian
	

	/**
	 *获取新西兰的广播
	 *@date 2010-11-16
	 *@time 上午11:17:59
	 */
	function wrnewzealand() {
		//获取新西兰的广播
		exit ();
		//http://www.canadianwebradio.com/
		$url = "http://www.nzradioguide.co.nz/";
		import ( "@.Com.phpQuery", '', '.php' );
		$s = file_get_contents ( $url );
		$doc = phpQuery::newDocumentHTML ( $s );
		$s = $doc ["html>body>div#mainwrap>div#content>div.thetable3>table>tbody>tr"]->htmlOuter ();
		$data = array ();
		$s = str_replace ( array ("\n", "\r" ), array ("<nr/>", "<rr/>" ), $s );
		$this->getrole ( $s );
		//$preg='<tr*<td>*a href="||">||</td*<td*</td*<td>||</td*<td>||</td*<td>||</td*<td>||</td*</tr>';
		$preg = '<tr*<td>*a href="||">||</td*<td>||</td*<td>||</td*<td>||</td*<td>||</td*</tr>';
		preg_match_all ( '/' . $this->getrole ( $preg ) . '/i', $s, $arr, PREG_PATTERN_ORDER );
		$num = count ( $arr ['1'] );
		for($i = 0; $i < $num; $i ++) {
			$listen = array ();
			$data [$i] ['radio'] = $arr ['1'] [$i];
			$data [$i] ['radio_name'] = strip_tags ( $arr ['2'] [$i] );
			$data [$i] ['location'] = del_html ( $arr ['3'] [$i] );
			$data [$i] ['img'] = ''; //$arr['4'][$i];
			$data [$i] ['listen'] = '';
			preg_match_all ( '/href="(.*?)">(.*?)</i', $arr ['5'] [$i], $listen, PREG_PATTERN_ORDER );
			for($l = 0; $l < count ( $listen ['1'] ); $l ++) {
				$t = substr ( $listen ['1'] [$l], 0, 4 );
				if ($t == 'java') {
					preg_match_all ( "/\(\'(.*?)\'/i", $listen ['1'] [$l], $url2, PREG_PATTERN_ORDER );
					$listen ['1'] [$l] = $url2 ['1'] ['0'];
				}
				$data [$i] ['listen'] .= $listen ['1'] [$l] . '|+|' . $listen ['2'] [$l] . '|+|';
			}
			$data [$i] ['listen'] = trim ( $data [$i] ['listen'], '|+|' );
			$data [$i] ['format'] = $arr ['6'] [$i];
		}
		//dump($data);
		return $data;
	} //end wrcanadian
	

	/**
	 *写入在线收音地址
	 *@date 2010-11-15
	 *@time 下午03:00:55
	 */
	function w_radio() {
		//写入在线收音地址
		//$chau="oceania";
		$chau = $_GET ['chau'];
		if ($chau == "unitedstates") {
			$data = $this->wrunitedstates ();
			F ( $chau, $data, APP_PATH . '/file/' );
			exit ( "录入完成" );
		}
		if ($chau == "newzealand") {
			$data = $this->wrnewzealand ();
			F ( $chau, $data, APP_PATH . '/file/' );
			exit ( "录入完成" );
		}
		$chau_data = $this->get_chau ( $chau );
		$p = empty ( $_GET ['p'] ) ? 0 : $_GET ['p'];
		foreach ( $chau_data as $k => $v ) {
			//dump($k);
			$data = array ();
			if ($k == $p) {
				$coun = $chau . '_' . $v ['id'] . '_' . $v ['country'];
				$data = F ( $coun, '', APP_PATH . '/file/' . $chau . '/' );
				if (empty ( $data )) {
					if ($chau == "oceania") {
						$data = $this->wr ( $v ['id'] );
					} elseif ($chau == "canadian") {
						$data = $this->wrcanadian ( $v ['url'] );
					}
					F ( $coun, $data, APP_PATH . '/file/' . $chau . '/' );
				} else {
					echo "已经录入";
				}
				$next = $p + 1;
				if ($next > count ( $chau_data )) {
					exit ( "录入完成" );
				}
				echo "<a href='/Index/w_radio/p/" . $next . "/chau/" . $chau . "'>下一页" . $next . "</a>";
				echo '<script>setTimeout(window.location.href="/Index/w_radio/p/' . $next . '/chau/' . $chau . '",600000);</script>';
			}
		}
	} //end w_radio
	

	/**
	 *过滤
	 *@date 2010-6-11
	 *@time 上午10:19:28
	 */
	protected function getrole($str) {
		//过滤
		$str = str_replace ( array ("\n", "\r" ), array ("<nr/>", "<rr/>" ), strtolower ( $str ) );
		$arr1 = array ('?', '"', '(', ')', '[', ']', '.', '/', ':', '*', '||' );
		$arr2 = array ('\?', '\"', '\(', '\)', '\[', '\]', '\.', '\/', '\:', '.*?', '(.*?)' );
		return str_replace ( $arr1, $arr2, $str );
	
	} //end getrole
	

	function getfile($url) {
		$content = file_get_contents ( $url );
		if (FALSE == $content) {
			if (function_exists ( 'curl_init' )) {
				$curl = curl_init ();
				curl_setopt ( $curl, CURLOPT_URL, $url );
				curl_setopt ( $curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; U; Linux i686; zh-CN; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5' );
				curl_setopt ( $curl, CURLOPT_HEADER, 0 );
				curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
				$tmpInfo = curl_exec ( $curl );
				curl_close ( $curl );
				if (FALSE !== stristr ( $tmpInfo, "HTTP/1.1 200 OK" )) { //正确返回数据
					return $tmpInfo;
				} else {
					return FALSE;
				}
			} else {
				return false;
			}
		} else {
			return $content;
		}
	}
	
}

?>