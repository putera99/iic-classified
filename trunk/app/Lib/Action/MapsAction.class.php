<?php
/**
 +------------------------------------------------------------------------------
 * MapsAction控制器类
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  app
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-9-21
 * @time  下午03:08:19
 +------------------------------------------------------------------------------
 */
class MapsAction extends Action{
	protected $handle;
	/**
	   *创建站点地图
	   *@date 2010-9-21
	   *@time 下午03:27:59
	   */
	function index() {
		//创建站点地图
		header("Content-Type:text/html; charset=utf-8");
		import("ORG.Util.Page");
		$www_url="http://www.beingfunchina.com";
		$url=array(
			'2'=>array('1'=>'/ctgs/','2'=>'/cglist/'),
			'4'=>array('1'=>'/clss/','2'=>'/clist/'),
			'5'=>array('1'=>'/clss/','2'=>'/clist/'),
			'6'=>array('1'=>'/clss/','2'=>'/clist/'),
			'7'=>array('1'=>'/clss/','2'=>'/clist/'),
			'8'=>array('1'=>'/clss/','2'=>'/clist/'),
			'9'=>array('1'=>'/clss/','2'=>'/clist/'),
			'10'=>array('1'=>'/evts/','2'=>'/Event/ls/id/'),
			'11'=>array('1'=>'/Biz/show/aid/','2'=>'/Biz/ls/id/'),
			'12'=>array('1'=>'/Art/show/aid/','2'=>'/Arc/ls/id/'),
			'13'=>array('1'=>'/grps/','2'=>'/cglist/','3'=>'/grpts/'),
		);
		$arc=D("Archives");
		$arctype=D("Arctype");
		$group=D("Group");
		
		$head=$this->xml_head();
		$foot=$this->xml_foot();
		
		
		if($_GET['act']=='fdb'){
			$filename = 'sitemap1.xml';
			if (!$this->handle=fopen($filename, 'w')) {
				echo "不能打开文件 $filename";
				exit;
			}
			
			$this->writeTo($head);
			$fdb=$arctype->findAll();
			foreach($fdb AS $k=>$v) {
				if($v['channeltype']=='2'||$v['channeltype']=='11'||$v['channeltype']=='12'){
					$priority="0.8";
				}else{
					$priority="0.7";
				}
				$this->writeTo( $this->xml_add("{$www_url}{$url[$v['channeltype']]['2']}{$v['id']}.html", date(DATE_ATOM), "always",$priority) );
			}
			$this->writeTo($foot);
			$this->success("写入完成");
		}else{
			$p=empty($_GET['p'])?'2':$_GET['p']+1;
			$filename = 'sitemap'.$p.'.xml';
			if(!$this->handle=fopen($filename, 'w')) {
				echo "不能打开文件 $filename";
				exit;
			}
			$count=$arc->where("ismake=1")->count();
			$page=new Page($count,3000);
			$limit=$page->firstRow.','.$page->listRows;
			$data=$arc->where("ismake=1")->limit("$limit")->findAll();
			$this->writeTo($head);
			$time=time();
			foreach($data AS $k=>$v) {
				
				if($v['showstart']>$time&&$v['endtime']>$time){
					$priority="0.6";
				}elseif($v['channel']=='2'||$v['channel']=='11'||$v['channel']=='12'){
					$priority="0.6";
				}elseif(($v['edittime']+2592000)>$time){
					$priority="0.6";
				}else{
					$priority="0.5";
				}
				$v['edittime']=empty($v['edittime'])?$v['senddate']:$v['edittime'];
				if(intval($v['channel'])){
					$this->writeTo($this->xml_add("{$www_url}{$url[$v['channel']]['1']}{$v['id']}.html", date(DATE_ATOM,$v['edittime']), "daily",$priority) );
				}
			}
			$this->writeTo($foot);
			echo $page->show();
		}
	}//end index
	
	function xml_head() {
	    $output = '<?xml version="1.0" encoding="UTF-8" ?>'."\n";
	    $output .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' ."\n";
	    return $output;
	}//xml_head
	
	/**
	 * 转义
	 * @param $location 指定网址  必需 
	 * @param $lastmod 最后修改时间，使用 YYYY-MM-DDThh:mmTZD 格式 可选
	 * @param $changefreq 提供关于网页更改频率的提示 可选
	 * @param $priority 网址的优先级 1.0重要  0.1不重要
	 */
	function xml_add($location, $lastmod, $changefreq, $priority) {
		//转义
		$a = array('<', '>', '&', '"', "'");
		$b = array('&lt;', '&gt;', '&amp;', '&quot', '&apos');
		$location = str_ireplace($a, $b, $location);
		
		$output = "";
		$output .= "<url>\n";
		$output .= "  <loc>{$location}</loc>\n";
		$output .= "  <lastmod>{$lastmod}</lastmod>\n";
		$output .= "  <changefreq>{$changefreq}</changefreq>\n";
		$output .= "  <priority>{$priority}</priority>\n";
		$output .= "</url>\n";
		return $output;
	}//xml_add
	
	function xml_foot() {
		$output = "</urlset>\n";
		return $output;
	}
	
	/**
	 * 写入文件
	 * @param $str 写入文件的内容
	 */
	function writeTo($str){
		return fwrite($this->handle,$str);
	}
	
	
	/**
	   *设置时间
	   *@date 2010-9-26
	   *@time 下午02:29:31
	   */
	function evst() {
		//设置时间
		$dao=D("Archives");
		$condition=array();
		$condition['showend']=array('gt',1304208000);
		$condition['channel']=10;
		$data=$dao->where($condition)->findAll();
		dump($dao->getLastSql());
		echo count($data);
		echo '<br>';
		$x=0;
		foreach ($data as $v){
			$x++;
			$time=array();
			$time['showstart']=mktime(0,0,0,date('m',$v['showstart']),date('d',$v['showstart']),2010);
			$time['showend']=mktime(0,0,0,date('m',$v['showstart'])+1,date('d',$v['showstart']),2010);	
			$dao->where("id={$v['id']}")->save($time);
			echo $dao->getLastSql();
			echo '<br>';
		}
		echo $x;
	}//end evst
	
	/*
	   *站点地图
	   */
	function sitemap() {
		//站点地图
		header("Content-Type:text/html; charset=utf-8");
		load("extend");
		$this->assign("all_city",array(1=>'Guangzhou',2=>'Beijing',3=>'Shanghai',4=>'Shenzhen'));//,1001=>'Bohai Rim',1002=>'Yangtze River Delta',1003=>'Pan Pearl River Delta',1004=>'Other areas'));
		$www_url="http://www.beingfunchina.com";
		$arctype=D("Arctype");
		$url=array(
			'2'=>array('1'=>'/ctgs/','2'=>'/cglist/'),
			'4'=>array('1'=>'/clss/','2'=>'/clist/'),
			'5'=>array('1'=>'/clss/','2'=>'/clist/'),
			'6'=>array('1'=>'/clss/','2'=>'/clist/'),
			'7'=>array('1'=>'/clss/','2'=>'/clist/'),
			'8'=>array('1'=>'/clss/','2'=>'/clist/'),
			'9'=>array('1'=>'/clss/','2'=>'/clist/'),
			'10'=>array('1'=>'/evts/','2'=>'/Event/ls/id/'),
			'11'=>array('1'=>'/Biz/show/aid/','2'=>'/Biz/ls/id/'),
			'12'=>array('1'=>'/Art/show/aid/','2'=>'/Arc/ls/id/'),
			'13'=>array('1'=>'/grps/','2'=>'/cglist/','3'=>'/grpts/'),
		);
		
		$condition=array();
		$condition['ishidden']=0;
		$condition['topid']=1;
		$classifieds=$arctype->where($condition)->field("id,reid,typename,channeltype")->order("id ASC")->findAll();
		$classifieds=list_to_tree($classifieds,'id','reid','_son',1);
		$this->assign("classifieds",$classifieds);
		
		$condition['topid']=1000;
		$cityguide=$arctype->where($condition)->field("id,reid,typename,channeltype")->order("id ASC")->findAll();
		$cityguide=list_to_tree($cityguide,'id','reid','_son',1000);
		$this->assign("cityguide",$cityguide);
		
		$condition=array();
		$condition['ishidden']=0;
		$condition['reid']=1232;
		$fairs=$arctype->where($condition)->field("id,reid,typename,channeltype,seotitle")->order("id ASC")->findAll();
		$fairs=list_to_tree($fairs,'id','reid','_son',1232);
		$this->assign("fairs",$fairs);
		
		$condition=array();
		$condition['ishidden']=0;
		$condition['topid']=2050;
		$events=$arctype->where($condition)->field("id,reid,typename,channeltype,seotitle")->order("id ASC")->findAll();
		$events=list_to_tree($events,'id','reid','_son',2050);
		$this->assign("events",$events);
		
		$dao=D("Group");
		
		
		$page['title']='Site Map  -  BeingfunChina 缤纷中国';
		$page['keywords']='BeingfunChina,缤纷中国,site map,站点地图';
		$page['description']='BeingfunChina site map,缤纷中国站点地图.';
		$this->assign('page',$page);
		$this->display();
	}//end sitemap
}//end MapsAction