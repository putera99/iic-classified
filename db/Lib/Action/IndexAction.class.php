<?php
class IndexAction extends Action{
public function index(){
    //查出城市
    echo "<a href='".__URL__."/ctype'>导入分类信息类别</a><br>";
    echo "<a href='".__URL__."/excity'>导入城市指南类别</a><br>";
    echo "<a href='".__URL__."/classified/mid/1'>分类信息的JOBS信息</a><br>";
}

    /**
     *
     *@date 2010-4-24
     *@time 下午03:00:24
     */
function ctype() {
    $sort=M("FenleiSort");
    $dao=new Model();
    $list=$sort->order("fid asc")->findAll();
    $channeltype=array(
    	1=>4,//jobs
    	2=>5,//Real Estate
    	3=>9,//Services
    	4=>'7',//Biz Opportunities
    	5=>'6',//Sell &Buy
    	8=>8,//Personals
    	9=>'8'//Announcement
    	);
	$sql="INSERT INTO `iic_arctype` (`id`, `reid`, `topid`, `sortrank`, `typename`, `ename`, `typedir`, `issend`, `channeltype`, `uid`, `cid`, `maxpage`, `ispart`, `corank`, `tempindex`, `templist`, `temparticle`, `namerule`, `namerule2`, `modname`, `description`, `keywords`, `seotitle`, `moresite`, `sitepath`, `siteurl`, `ishidden`, `cross`, `hits`, `posttime`, `mtime`, `crossid`, `content`, `smalltypes`)";
	$sql.=" VALUES (1, '0', '0', '0', 'Classified', 'classified', '', '', '2', 0, '', '25', '0', '', '', '', '', '', '', '', '', 'description', 'Arts&Culture', '', '', '', '', '0', 0,".time().", 0, '', '', '1');";
	if ($dao->execute($sql)) {
		echo '写入城市指南大类成功';
	}else{
		echo '<b>写入城市指南大类不成功</b>';
	}
	echo '<br>';
    foreach ($list as $v){
		$v['fid']=$v['fid']+1;
		$v['fup']=$v['fup']+1;
    	$sql="INSERT INTO `iic_arctype` (`id`, `reid`, `topid`, `sortrank`, `typename`, `ename`, `typedir`, `issend`, `channeltype`, `uid`, `cid`, `maxpage`, `ispart`, `corank`, `tempindex`, `templist`, `temparticle`, `namerule`, `namerule2`, `modname`, `description`, `keywords`, `seotitle`, `moresite`, `sitepath`, `siteurl`, `ishidden`, `cross`, `hits`, `posttime`, `mtime`, `crossid`, `content`, `smalltypes`)";
    	$sql.="VALUES (";
    	$sql.="{$v['fid']},{$v['fup']},1,{$v['list']},'{$v['name']}','','','',";
    	$sql.=$channeltype[$v['mid']];//系统模型
    	$sql.=",'','','25','{$v['type']}','','','','','','','','','description','{$v['name']}','','','','','0','0','".time()."','0','','','{$v['fid']}__{$v['fup']}');";
    	// `description`, `keywords`, `seotitle`, `moresite`, `sitepath`, `siteurl`, `ishidden`, `cross`, `hits`, `posttime`, `mtime`, `crossid`, `content`, `smalltypes`
    	if ($dao->execute($sql)) {
    		echo '分类：'.$v['fid'].'-> '.$v['name'].'执行成功';
    	}else{
    		echo '分类：'.$v['fid'].'-> '.$v['name'].'执行不成功';
    	}
    	echo "<br>";
    }

}//end ctype



//INSERT INTO `iic_arctype` (`id`, `reid`, `topid`, `sortrank`, `typename`, `ename`, `typedir`, `issend`, `channeltype`, `uid`, `cid`, `maxpage`, `ispart`, `corank`, `tempindex`, `templist`, `temparticle`, `namerule`, `namerule2`, `modname`, `description`, `keywords`, `seotitle`, `moresite`, `sitepath`, `siteurl`, `ishidden`, `cross`, `hits`, `posttime`, `mtime`, `crossid`, `content`, `smalltypes`)
//                   VALUES (NULL, 'reid', 'topid', NULL      , 'typename', 'ename', NULL     , NULL    , 'channeltype', NULL  , 'cid', NULL    , 'ispart', NULL    , NULL, NULL, NULL, NULL, NULL, NULL, 'description', 'keywords', 'seotitle', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
    /**
     *
     *@date 2010-4-24
     *@time 下午02:58:56
     */
function cityguide() {

}//end cityguide

/**
   *导入城市指南分类
   *@date 2010-4-27
   *@time 下午02:14:17
   */
function excity() {
	//导入城市指南分类
	$old=M("Sort");
	$list=$old->order("fid asc")->findAll();
	$dao=new Model();
	$sql="INSERT INTO `iic_arctype` (`id`, `reid`, `topid`, `sortrank`, `typename`, `ename`, `typedir`, `issend`, `channeltype`, `uid`, `cid`, `maxpage`, `ispart`, `corank`, `tempindex`, `templist`, `temparticle`, `namerule`, `namerule2`, `modname`, `description`, `keywords`, `seotitle`, `moresite`, `sitepath`, `siteurl`, `ishidden`, `cross`, `hits`, `posttime`, `mtime`, `crossid`, `content`, `smalltypes`)";
		$sql.=" VALUES (1000, '0', '0', '0', 'City Guide', 'cityguide', '', '', '2', 0, '', '25', '0', '', '', '', '', '', '', '', '', 'description', '', '', '', '', '', '0', 0,".time().", 0, '', '', '1000__1000');";
		if ($dao->execute($sql)) {
    		echo '写入城市指南大类成功';
    	}else{
    		echo '<b>写入城市指南大类不成功</b>';
    	}
		echo '<br>';
	//dump($list);
	foreach ($list as $v){
		$v['fid']=$v['fid']+1000;
		$v['fup']=$v['fup']+1000;
		
		$sql="INSERT INTO `iic_arctype` (`id`, `reid`, `topid`, `sortrank`, `typename`, `ename`, `typedir`, `issend`, `channeltype`, `uid`, `cid`, `maxpage`, `ispart`, `corank`, `tempindex`, `templist`, `temparticle`, `namerule`, `namerule2`, `modname`, `description`, `keywords`, `seotitle`, `moresite`, `sitepath`, `siteurl`, `ishidden`, `cross`, `hits`, `posttime`, `mtime`, `crossid`, `content`, `smalltypes`)";
    		$sql.="VALUES (";
    		$sql.="{$v['fid']},{$v['fup']},1000,{$v['list']},'{$v['name']}','','','',";
    		$sql.=2;//系统模型
    		$sql.=",'','','25','{$v['type']}','','','','','','','','','description','{$v['name']}','','','','','0','0','".time()."','0','','','{$v['fid']}__{$v['fup']}');";
    		// `description`, `keywords`, `seotitle`, `moresite`, `sitepath`, `siteurl`, `ishidden`, `cross`, `hits`, `posttime`, `mtime`, `crossid`, `content`, `smalltypes`
    		//echo $sql;
    	if ($dao->execute($sql)) {
    		echo '分类：'.$v['fid'].'-> '.$v['name'].'执行成功';
    	}else{
    		echo '分类：'.$v['fid'].'-> '.$v['name'].'执行不成功';
    	}
    	echo "<br>";
	}
	
}//end function_name
    /**
     *按栏目的模型分别导出数据
     *@date 2010-4-24
     *@time 下午02:59:24
 */
function classified() {
	$mid=$_GET['mid'];
	$p=$_GET['page'];
	$channeltype=array(
    	1=>4,//jobs
    	2=>5,//Real Estate
    	3=>9,//Services
    	4=>'7',//Biz Opportunities
    	5=>'6',//Sell &Buy
    	8=>8,//Personals
    	9=>'8'//Announcement
    	);
    $cid=array(
    	1=>'2',//bj
    	2=>'3',//sh
    	27=>'1',//gz
    	28=>'4',//sz
    );
    $dao=new Model();
    $p1=(empty($p)||($p==1))?0:($p-1)*50;
    $p2=empty($p)?50:$p*50;
    $sql="SELECT count(*) c FROM `bfc_fenlei_content` as fc LEFT JOIN `bfc_fenlei_content_{$mid}` as fcx ON fc.id=fcx.id WHERE fc.mid=$mid ORDER BY fc.id asc";
    $list=$dao->query($sql);
    if($list[0]['c']>($p*50)){
    	$np=$p+1;
    	$limit="LIMIT {$p1},{$p2}";
    	$sql="SELECT * FROM `bfc_fenlei_content` as fc LEFT JOIN `bfc_fenlei_content_{$mid}` as fcx ON fc.id=fcx.id WHERE fc.mid=$mid ORDER BY fc.id asc $limit";
    	echo $sql.'<br>';
    	$list=$dao->query($sql);
    	// 	id 	title 	albumid 	albumname 	mid 	spid 	fid 	fname 	fid_bak1 	fid_bak2 	fid_bak3 	info 	hits 	
    	//comments 	posttime 	list 	uid 	username 	titlecolor 	fonttype 	picurl 	ispic 	yz 	yzer 	yztime 	levels 	
    	//levelstime 	keywords 	jumpurl 	iframeurl 	style 	head_tpl 	main_tpl 	foot_tpl 	target 	ishtml 	ip 	
    	//lastfid 	money 	passwd 	editer 	edittime 	begintime 	endtime 	config 	lastview 	city_id 	zone_id 	
    	//street_id 	editpwd 	showday 	visit_log 	visit_num 	telephone 	mobphone 	email 	oicq 	msn 	maps 	
    	//replytime
    	$i=1;
    	if($list){
    		foreach ($list as $v){
    			echo $i."<hr>";
    			$i++;
    			switch ($mid) {
    				case 1://jobs
    				//dump($v);
    				$t=time();
    				$v['fid']=$v['fid']+1;
    				$arc="INSERT INTO `iic_archives` (`id`, `typeid`, `typeid2`, `industry`, `bycity`, `cid`, `uid`, `flag`, `ismake`, "
    					."`channel`, `arcrank`, `click`, `title`, `shorttitle`, `color`, `writer`, "
    					."`source`, `litpic`, `pubdate`, `senddate`, `keywords`, `lastpost`, `star1`, `star2`, `star3`, `star4`, `star5`, "
    					."`goodpost`, `badpost`, `notpost`, `description`, `filename`, `uip`, `lastview`, `editpwd`, `showstart`, `showend`, "
    					."`editer`, `edittime`, `albumid`, `albumnum`, `itype`, `category`, `telephone`, `fax`, `mobphone`, `email`, `oicq`, `msn`, "
    					."`maps`, `city_id`, `zone_id`, `street_id`, `position`, `contact`, `ltdid`, `linkman`) VALUES "
    					//values
    					."(NULL,{$v['fid']}, , '', '', '{$cid[$v['city_id ']]}', '{$v['uid']}', '', , '{$channeltype[$v['mid']]}', '', '{$v['hits']}', '{$v['title']}', "
    					."'', '{$v['titlecolor']}', '', '', '', NULL, NULL, '{$v['keywords']}', {$v['replytime']}, 0, 0, 0, 0, 0, "
    					."0, 0, '1', '', '', '{$v['ip']}', $t, '$t', '', , '', , '0', "
    					."'0', {$v['type']}, '{$v['category']}', '{$v['telephone']}', '', '{$v['mobphone']}', '{$v['email']}', '{$v['oicq']}', '{$v['msn']}', 'maps', '{$v['city_id']}', '{$v['zone_id']}', "
    					."'{$v['street_id']}', '', '', '0', '');";
    				echo $arc."<br>";
    				$aid=$dao->execute($arc);
    				dump($aid);
    				if ($aid) {
    					$job="INSERT INTO `iic_addon_jobs` (`id`, `aid`, `joblocated`, `experience`, `salary`, `content`) VALUES "
    						."(NULL, '$aid', '', '', '', '{$v['content']}');";
    					$addon_id=$dao->execute($job);
    					echo $job.'<br>';
    					if ($addon_id) {
    						echo '<b>执行成功!<b><br>';
    					}else {
    						exit('附加表写入失败！');
    					}
    				}else{
    					exit('档案表写入失败！');
    				}
    				break;
    				
    				default:
    					exit('数据模块不匹配！');
    				break;
    			}
    			//dump($v);
    		}
    		//echo '<script>setTimeout(window.location.href="'.__URL__.'/classified/mid/'.$mid.'/page/'.$np.'",30000);</script>';
    	}else{
    		exit('查询出错');
    	}
    	
    	//dump($list);
    }else{
    	exit('导出结束');
    }
}//end classified

function tree() {
	$dao=new Model();
	$list=$dao->query("SELECT * FROM iic_arctype where id<1000");
	echo $dao->getLastSql();
	load("extend");
	$news=list_to_tree($list,'id','reid','_son','1');
	echo "<pre>";
	print_r($news);
	echo "<pre>";
}//end tree
}//class end
?>

