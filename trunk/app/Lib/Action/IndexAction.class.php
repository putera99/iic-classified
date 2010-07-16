<?php
class IndexAction extends CommonAction{
	protected $pcid='';//页面查询用的独立城市ID
	
	/**
	  *预处理
	  *@date 2010-4-30
	  *@time 上午10:08:20
	  */
	function _initialize() {
		//预处理
		if (intval($_GET['cid'])){
			$this->pcid=intval($_GET['cid']);
		}else{
			$this->_set_cid();
			$this->pcid=$this->cid;
		}
		parent::_initialize();
	}//end _initialize()
	
	
    public function index(){
    	$this->assign('pick',$this->_new_list(2001,'h','0,1'));
    	$this->assign('pick2',$this->_new_list(2001,'p','0,2'));
    	$this->assign('pick8',$this->_new_list(2001,'','2,8'));
    	$this->assign('do',$this->_new_list(2004,'','0,1'));
    	//$this->assign('biz_news',$this->_new_list(2003,'','0,10'));
    	
    	$group=$this->_get_group('hot');
    	$this->assign('group',$group);
    	
    	$this->assign('city_type',$this->_get_tree(1000));
    	$this->assign('classifieds_type',$this->_get_tree(1));
    	$classifieds_type=$this->_get_classifieds_type();
    	
    	
    	$classifieds=array();
    	foreach ($classifieds_type as $v){
    		$classifieds[$v['id']]['id']=$v['id'];
    		$classifieds[$v['id']]['_sub']=$this->_get_carc($v['id'],'0,10',$this->pcid);
    	}
    	$this->assign('classifieds',$classifieds);
    	
    	$event=array();
    	$event=$this->new_event();
    	$this->assign('event',$event);
    	$fair=array();
    	$fair=$this->new_fair();
    	$this->assign('fair',$fair);
    	
    	$page=array();
		$page['title']='BeingfunChina';
		$page['keywords']='BeingfunChina';
		$page['description']='BeingfunChina';
		$this->assign('page',$page);
		
		$chau_list=array();
		/*$chau_list['0']['id']='1';
		$chau_list['0']['name']='Asia';
		$chau_list['0']['ename']='asia';*/
		
		$chau_list['1']['id']='2';
		$chau_list['1']['name']='Europe';
		$chau_list['1']['ename']='europe';
		$chau_list['1']['url']='http://www.listenlive.eu';
		
		/*$chau_list['2']['id']='3';
		$chau_list['2']['name']='Africa';
		$chau_list['2']['ename']='africa';
		
		$chau_list['3']['id']='4';
		$chau_list['3']['name']='North America';
		$chau_list['3']['ename']='northa_merica';
		
		$chau_list['4']['id']='5';
		$chau_list['4']['name']='South America';
		$chau_list['4']['ename']='south_america';
		
		$chau_list['5']['id']='6';
		$chau_list['5']['name']='Oceania';
		$chau_list['5']['ename']='oceania';
		
		$chau_list['6']['id']='7';
		$chau_list['6']['name']='Antarctica';
		$chau_list['6']['ename']='antarctica';*/
		
		$this->assign('chau',$chau_list);
		
		$cityname=array(1=>array('GUANGZHOU','CH006'),2=>array('BEIJING','CH002'),3=>array('SHANGHAI','CH024'),4=>array('SHENZHEN','CH006'));
		$this->assign('cityname',$cityname[$this->pcid]);
    	$this->display();
    }
    
    /**
     *获取最新活动
     *@date 2010-6-17
     *@time 上午09:51:38
     */
    protected function new_event() {
    	//获取最新活动
    	$time=time();
		$dao=D("Archives");
		$condition=array();
		$condition['channel']='10';
		$condition['ismake']='1';
		$condition['showstart']=array('lt',$time);
		$condition['showend']=array('gt',$time);
		$list=$dao->where($condition)->order("showstart DESC")->limit("0,6")->findAll();
		return $list;
    }//end new_event
    
    /**
     *获取最新展会
     *@date 2010-6-17
     *@time 上午09:51:38
     */
    protected function new_fair() {
    	//获取最新展会
		$dao=D("Archives");
		$condition=array();
		$condition['channel']='11';
		$condition['ismake']='1';
		$condition['industry']='EN';
		$list=$dao->where($condition)->order("id DESC")->limit("0,10")->findAll();
		return $list;
    }//end new_fair
    
    /**
     *radio
     *@date 2010-6-11
     *@time 上午10:09:18
     */
    function radio() {
    	//radio
	    switch ($_GET['act']) {
			case 'country':
			$data=$this->get_chau($_GET['chau']);
				if($data){
					echo '<option>country Or province</option>';
					foreach ($data as $v){
						if ($v['province']!='0') {
							echo '<option value="'.$v['id'].'">'.$v['province'].'</option>';
						}else{
							echo '<option value="'.$v['id'].'">'.$v['country'].'</option>';
						}
					}
				}else{
					echo '<option>Information is Null!</option>';
				}
			break;
			
			case 'radio':
				$data=$this->get_radio($_GET['country'],$_GET['chau']);
				$text='';
				foreach ($data as $v){
					//echo '<option value="'.$v['location'].'">'.$v['radio_name'].'</option>';
					//echo $v['location'].'<br>'.$v['radio_name'];
					$arr=explode('|+|',$v['listen']);
					//d($v);
					if(count($arr)>2){
						for($i=0;$i<count($arr);$i=$i+2){
							$text.='<option value="'.$arr[$i].'">'.$v['radio_name'].'-->'.$arr[$i+1]."</option>\n";
							//$x=$i+1;
						}
					}else{
						$text.='<option value="'.$arr['0'].'">'.$v['radio_name'].'</option>';
					}
					//d($text);
				}
				echo $text;
			break;
			
			default:
		
			break;
		}
    }//end radio
    
    /**
     *获取国家
     *@date 2010-6-11
     *@time 上午10:11:12
     */
    protected function get_chau($chau) {
    	//获取国家
    	$data=S($chau);
		if (empty($data)) {
			$country=array();
			if($chau=='europe'){
				$url='http://www.listenlive.eu';
				$fp = @fopen ($url,"r") or die ( "timeout" ); //判断网页能否打开
				$fcontents=file_get_contents($url);//读取目标的数据
				eregi('<table width="550" id="thetable">(.*)<p>Browse by genre:-</p></div>', $fcontents, $regs);
				preg_match_all('/<img width="8" height="8" alt="" src="b.gif" \/>(.*?)<\/td><\/tr>/',$regs[1],$co,PREG_PATTERN_ORDER);
				$country_str='';
				foreach ($co[1] as $v){
					$country_str.=$v;
				}
				preg_match_all('/<a href="(.*?)"/',$country_str,$country_url,PREG_PATTERN_ORDER);
				preg_match_all('/">(.*?)<\/a>/',$country_str,$country_name,PREG_PATTERN_ORDER);
				for ($i=0;$i<count($country_name['1']);$i++){
					$id=$i+8;
					$country[$id]['id']=$id;
					$country[$id]['chau']='europe';
					$country[$id]['country']=$country_name['1'][$i];
					$country[$id]['province']='0';
					$country[$id]['location']='0';
				}
				for ($i=0;$i<count($country_url['1']);$i++){
					$id=$i+8;
					$country_url['1'][$i]='http://www.listenlive.eu/'.$country_url['1'][$i];
					$country[$id]['url']=$country_url['1'][$i];
				}
				$data=$country;
			}elseif($chau=='oceania'){
				$url='http://www.australianliveradio.com/';
				$fp=@fopen($url,"r") or die("timeout"); //判断网页能否打开
				$fcontents = file_get_contents($url);//读取目标的数据
				eregi('<div id="content">(.*)<div id="footer">', $fcontents, $regs);
				preg_match_all('/<h2>(.*?)<\/h2>/',$regs[1],$country_name,PREG_PATTERN_ORDER);
				for ($i=0;$i<count($country_name['1']);$i++){
					$id=$i+8;
					$country[$id]['id']=$id;
					$country[$id]['chau']='oceania';
					$country[$id]['country']='Australia';
					$country[$id]['province']=$country_name['1'][$i];
					$country[$id]['location']='0';
				}
				$data=$country;
			}else{
				$data=null;
			}
			S($chau,$country,60*60*60*24*30);
		}
		return $data;
    }//end get_chau
    
    /**
     *获取指定国家的电台
     *@date 2010-6-11
     *@time 上午10:15:24
     */
    function get_radio($country,$chau) {
    	//获取指定国家的电台
    	$coun=$chau.'_'.$country;
		$data=S($coun);
		if (empty($data)) {
			if ($chau=='europe') {
				$chau_data=$this->get_chau($chau);
				foreach ($chau_data as $v) {
					if($country==$v['id']){
						$url=$v['url'];
					}
				}
				//print $url;
				$fp = @fopen ($url,"r") or die ("timeout"); //判断网页能否打开
				$contents=$this->getfile($url);//读取目标的数据
				$contents=str_replace(array("\n","\r"),array("<nr/>","<rr/>"),$contents);	  
				eregi('<td class="header"><b>Format/Comments</b></td></tr>(.*)</tbody></table>',$contents,$body);
				$preg='<td><a href="||"><b>||</b></a*d>||</td*td>||</td*td>||</td*td>||</td>';
				//dump($this->getrole($preg));
				preg_match_all('/'.$this->getrole($preg).'/i',$body[1],$arr,PREG_PATTERN_ORDER);
				//dump($arr);
				$data=array();
				$num=count($arr['1']);
				for($i=0;$i<$num;$i++){
					$listen=array();
					$data[$i]['radio']=$arr['1'][$i];
					$data[$i]['radio_name']=$arr['2'][$i];
					$data[$i]['location']=del_html($arr['3'][$i]);
					$data[$i]['img']='';//$arr['4'][$i];
					$data[$i]['listen']='';
					preg_match_all('/href="(.*?)">(.*?)</i',$arr['5'][$i],$listen,PREG_PATTERN_ORDER);
					for ($l=0;$l<count($listen['1']);$l++){
						$data[$i]['listen'].=$listen['1'][$l].'|+|'.$listen['2'][$l].'|+|';
					}
					$data[$i]['listen']=trim($data[$i]['listen'],'|+|');
					$data[$i]['format']=$arr['6'][$i];
				}
			}elseif($chau=='oceania'){
				$data=null;
			}
			
			S($coun,$data,60*60*60*24*30);
		}
		return $data;
    	
    }//end get_radio
    
    /**
     *过滤
     *@date 2010-6-11
     *@time 上午10:19:28
     */
    protected function getrole($str) {
    	//过滤
		$str = str_replace(array("\n","\r"),array("<nr/>","<rr/>"),strtolower($str));
		$arr1 = array(
			'?',
			'"',
			'(',
			')',
			'[',
			']',
			'.',
			'/',
			':',
			'*',
			'||',
		);
		$arr2 = array(
			'\?',
			'\"',
			'\(',
			'\)',
			'\[',
			'\]',
			'\.',
			'\/',
			'\:',
			'.*?',
			'(.*?)',
		);
		return str_replace($arr1,$arr2,$str);
    	
    }//end getrole
    
	function getfile($url){
		$content = file_get_contents($url);
		if(FALSE == $content){
			if (function_exists('curl_init')){
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; U; Linux i686; zh-CN; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
				curl_setopt($curl, CURLOPT_HEADER, 0);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				$tmpInfo = curl_exec($curl);
				curl_close($curl);
				if(FALSE !== stristr($tmpInfo,"HTTP/1.1 200 OK")){ //正确返回数据
					return $tmpInfo;
				}else{
					return FALSE;
				}
			}else{
				  return false;
			}
		}else{
			return $content;
		}
	}
}
?>