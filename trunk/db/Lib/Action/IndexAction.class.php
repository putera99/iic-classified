<?php
class IndexAction extends Action{
public function index(){
    //查出城市
    echo "<a href='".__URL__."/ctype'>导入分类信息类别</a><br>";
    echo "<a href='".__URL__."/excity'>导入城市指南类别</a><br><br>";
    echo "<a href='".__URL__."/classified/mid/1'>分类信息的JOBS信息</a><br>";
    echo "<a href='".__URL__."/classified/mid/2'>分类信息的Real Estate信息</a><br>";
    echo "<a href='".__URL__."/classified/mid/3'>分类信息的Services信息</a><br>";
    echo "<a href='".__URL__."/classified/mid/4'>分类信息的Biz Opportunities信息</a><br>";
    echo "<a href='".__URL__."/classified/mid/5'>分类信息的Sell &Buy信息</a><br>";
    echo "<a href='".__URL__."/classified/mid/8'>分类信息的Personals信息</a><br>";
    echo "<a href='".__URL__."/classified/mid/9'>分类信息的Announcement信息</a><br>";
    echo "<a href='".__URL__."/get_dis'>生成摘要信息</a><br><br>";
    echo "<a href='".__URL__."/get_cityguide'>城市指南数据导入</a><br><br>";
    
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
	load("extend");
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
    	$limit="LIMIT {$p1},50";
    	echo $limit."<hr>";
    	
    	$sql="SELECT * FROM `bfc_fenlei_content` as fc LEFT JOIN `bfc_fenlei_content_{$mid}` as fcx ON fc.id=fcx.id WHERE fc.mid=$mid ORDER BY fc.id asc $limit";
    	echo $sql.'<br>';
    	echo '<a href="'.__URL__.'/classified/mid/'.$mid.'/page/'.$np.'">下一页</a>';
    	$list=$dao->query($sql);
    	$i=1;
    	if($list){
    		foreach ($list as $v){
    			echo $i;
    			$i++;
    			switch ($mid) {
    				case 1://jobs
    				//dump($v);
    				$t=time();
    				$v['fid']=$v['fid']+1;
    				
    				
    				$data=array();
    				$data['typeid']=$v['fid'];
    				$data['cid']=$cid[$v['city_id']];
    				$data['uid']=$v['uid'];
    				$data['channel']=$channeltype[$v['mid']];
    				$data['click']=$v['hits'];
    				$data['title']=$v['title'];
    				$data['color']=$v['titlecolor'];
    				$data['keywords']=$v['keywords'];
    				$data['lastpost']=$v['replytime'];
    				$data['uip']=$v['ip'];
    				$data['lastview']=$t;
    				$data['editpwd']=$t;
    				$data['itype']=$v['type'];
    				$data['category']=$v['category'];
    				$data['telephone']=$v['telephone'];
    				$data['mobphone']=$v['mobphone'];
    				$data['email']=$v['email'];
    				$data['oicq']=$v['oicq'];
    				$data['msn']=$v['msn'];
    				$data['city_id']=$v['city_id'];
    				$data['zone_id']=$v['zone_id'];
    				$data['street_id']=$v['street_id'];
    				$data['ismake']=1;
    				$data['description']=msubstr(del_html($v['content']),0,200);

    				$aid=$dao->Table('iic_archives')->add($data);
    				if ($aid) {
    					$data=array();
    					$data['aid']=$aid;
    					$data['content']=$v['content'];
    					$addon_id=$dao->Table('iic_addon_jobs')->add($data);
    					//dump($addon_id);
    					//dump($dao->getLastSql());
    					if($addon_id){
    						echo '执行成功!<br>';
    					}else {
    						echo '<b>附加表写入失败！!<b><br>';
    						//$this->error('附加表写入失败！');
    					}
    				}else{
    					echo('档案表写入失败！');
    				}
    				break;
    				
    				case 2:
    				$t=time();
    				$v['fid']=$v['fid']+1;
    				
    				$data=array();
    				$data['typeid']=$v['fid'];
    				$data['cid']=$cid[$v['city_id ']];
    				$data['uid']=$v['uid'];
    				$data['channel']=$channeltype[$v['mid']];
    				$data['click']=$v['hits'];
    				$data['title']=$v['title'];
    				$data['color']=$v['titlecolor'];
    				$data['keywords']=$v['keywords'];
    				$data['lastpost']=$v['replytime'];
    				$data['uip']=$v['ip'];
    				$data['lastview']=$t;
    				$data['editpwd']=$t;
    				$data['itype']=$v['type'];
    				$data['category']=$v['category'];
    				$data['telephone']=$v['telephone'];
    				$data['mobphone']=$v['mobphone'];
    				$data['email']=$v['email'];
    				$data['oicq']=$v['oicq'];
    				$data['msn']=$v['msn'];
    				$data['city_id']=$v['city_id'];
    				$data['zone_id']=$v['zone_id'];
    				$data['street_id']=$v['street_id'];
    				
    				$aid=$dao->Table('iic_archives')->add($data);
    				if ($aid) {
    					$data=array();
    					$data['aid']=$aid;
    					$data['price']=$aid;
    					$data['rooms']=$aid;
    					$data['content']=$v['content'];
    					$addon_id=$dao->Table('iic_addon_jobs')->add($data);
    					//dump($addon_id);
    					//dump($dao->getLastSql());
    					if($addon_id){
    						echo '执行成功!<br>';
    					}else {
    						echo '<b>附加表写入失败！!<b><br>';
    						//$this->error('附加表写入失败！');
    					}
    				}else{
    					echo('档案表写入失败！');
    				}
    				break;
    				
    				default:
    					echo('数据模块不匹配！');
    				break;
    			}
    			//dump($v);
    		}
    		echo '<script>setTimeout(window.location.href="'.__URL__.'/classified/mid/'.$mid.'/page/'.$np.'",300000);</script>';
    		echo '<a href="'.__URL__.'/classified/mid/'.$mid.'/page/'.$np.'">下一页</a>';
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

/**
 *清除HTML批量获取摘要
 *@date 2010-5-6
 *@time 下午04:53:09
 */
function get_dis() {
	//清除HTML批量获取摘要
	load("extend");
	$dao=new Model();
	$dao->Table('iic_archives');
	$count=$dao->count();
	import("ORG.Util.Page");
	$page=new Page($count,100);
	$limit=$page->firstRow.','.$page->listRows;
	
	
	dump($count);
}//end get_dis

/**
 *导出城市指南内容
 *@date 2010-5-12
 *@time 下午04:58:29
 */
function get_cityguide() {
	//导出城市指南内容
	$dao=new Model();
	//$sql="SELECT a.*,b.my_content FROM bfc_article AS a LEFT JOIN bfc_article_content_4 AS b ON a.aid=b.aid";
	$p1=(empty($p)||($p==1))?0:($p-1)*50;
    $p2=empty($p)?50:$p*50;
    $sqlc="SELECT count(*) c FROM bfc_article AS a LEFT JOIN bfc_article_content_4 AS b ON a.aid=b.aid LEFT JOIN bfc_reply AS r ON b.rid=r.rid";
    $list=$dao->query($sqlc);
    if($list[0]['c']>($p*50)){
    	$np=$p+1;
    	$limit="LIMIT {$p1},50";
    	echo $limit."<hr>";
    	
    	$sql="SELECT a.*,b.my_content,r.content FROM bfc_article AS a LEFT JOIN bfc_article_content_4 AS b ON a.aid=b.aid LEFT JOIN bfc_reply AS r ON b.rid=r.rid $limit";
    	echo $sql.'<br>';
    	echo '<a href="'.__URL__.'/get_cityguide/page/'.$np.'">下一页</a>';
    	//$data_old=$dao->query($sql);
    	$i=1;
    	if($data_old){
    		foreach ($data_old as $v){
    		$t=time();
    		$v['fid']=$v['fid']+1000;
    		/*aid 	title 	smalltitle 	fid 	mid 	fname 	special_id 	bak_id 	info 	hits 	pages 	
    		comments 	posttime 	list 	uid 	username 	author 	copyfrom 	copyfromurl 	titlecolor 	
    		fonttype 	titleicon 	picurl 	ispic 	yz 	yzer 	yztime 	levels 	levelstime 	keywords 	
    		jumpurl 	iframeurl 	style 	template 	target 	ip 	lastfid 	money 	buyuser 	
    		passwd 	allowdown 	allowview 	editer 	edittime 	begintime 	endtime 	description 	
    		lastview 	digg_num 	digg_time 	my_content 	content*/
	    	$data=array();
	    	$data['typeid']=$v['fid'];
	    	//$data['cid']=$cid[$v['city_id']];
	    	$data['uid']=$v['uid'];
	    	$data['channel']=2;
	    	$data['click']=$v['hits'];
	    	$data['title']=$v['title'];
	    	$data['color']=$v['titlecolor'];
	    	$data['keywords']=$v['keywords'];
	    	$data['lastpost']=$v['replytime'];
	    	$data['pubdate']=$v['posttime'];
	    	$data['comments']=$v['comments'];
	    	$data['picurl']=$v['picurl'];
	    	$data['my_content']=$v['my_content'];
	    	//$data['uip']=$v['ip'];
	    	$data['lastview']=$t;
	    	$data['editpwd']=$t;
	    	$data['itype']=$v['type'];
	    	$data['category']=$v['category'];
	    	$data['telephone']=$v['telephone'];
	    	$data['mobphone']=$v['mobphone'];
	    	$data['email']=$v['email'];
	    	$data['oicq']=$v['oicq'];
	    	$data['msn']=$v['msn'];
	    	$data['city_id']=$v['city_id'];
	    	$data['zone_id']=$v['zone_id'];
	    	$data['street_id']=$v['street_id'];
	    	$data['ismake']=1;
	    	$data['description']=msubstr(del_html($v['my_content']),0,200);
	
	    	$aid=$dao->Table('iic_archives')->add($data);
    		}
    	}
    }
}//end get_cityguide

/**
 *sm
 *@date 2010-5-7
 *@time 下午02:28:04
 */
function test() {
	//sm
$text=<<<ETO
<p style="margin: 0cm 0cm 0pt;" class="MsoNormal"><span lang="EN-US" style=""><font size="2">email: </font><a href="mailto:latinasiagz@gmail.com"><font size="2">latinasiagz@gmail.com</font></a><font size="2"> <span style="">&nbsp;</span><o:p></o:p></font></span></p>
<p style="margin: 0cm 0cm 0pt;" class="MsoNormal"><span lang="EN-US" style=""><o:p><font size="2">&nbsp;</font></o:p></span></p>
<p style="margin: 0cm 0cm 0pt;" class="MsoNormal"><span lang="EN-US" style=""><font size="2">We are looking for a Textile Specialist for a Mexican company with good knowledge in textile products, fabrics, garments, confection and quality control.<o:p></o:p></font></span></p>
<p style="margin: 0cm 0cm 0pt;" class="MsoNormal"><span lang="EN-US" style=""><o:p><font size="2">&nbsp;</font></o:p></span></p>
<div><font size="2"><font face="Verdana"><span lang="EN-US" style="font-size: 10.5pt; font-family: 'Times New Roman';">Please send your CV to latinasiagz@gmail.com</span> </font></font></div>
ETO;
$text	=	preg_replace('/<\/?(html|head|meta|link|base|basefont|body|bgsound|title|style|script|form|iframe|frame|frameset|applet|id|ilayer|layer|name|script|style|xml|font|div|span|p|o:p)[^><]*>/i','',$text);

echo $text;

}//end function_name

}//class end
?>

