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
    	4=>7,//Biz Opportunities
    	5=>6,//Sell &Buy
    	8=>8,//Personals
    	9=>8//Announcement
    	);
    $cid=array(
    	1=>'2',//bj
    	2=>'3',//sh
    	27=>'1',//gz
    	28=>'4',//sz
    );
    $dao=new Model();
    $p1=empty($p)?0:$p*50;
    $p2=($p+1)*50;
    $sql="SELECT count(*) c FROM `bfc_fenlei_content` as fc LEFT JOIN `bfc_fenlei_content_{$mid}` as fcx ON fc.id=fcx.id WHERE fc.mid=$mid ORDER BY fc.id asc";
    $list=$dao->query($sql);
    if($list[0]['c']>$p2){
    	$np=empty($p)?2:$p+1;
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
    			$data['pubdate']=$v['posttime'];
    			$data['senddate']=$v['posttime'];
    			$data['senddate']=$v['posttime'];
    			$data['showstart']=$v['posttime'];
    			$data['showend']=$v['posttime']+60*60*24*$v['showday'];
    			$kw=str_word_count($v['title'],1);
    			$keywords="";
    			foreach ($kw as $vkw){
    				$keywords.=$vkw.',';
    			}
    			$data['keywords']=empty($v['keywords'])?trim($keywords,','):$v['keywords'];
    			$data['lastpost']=$v['replytime'];
    			$data['uip']=$v['ip'];
    			$data['lastview']=$t;
    			$data['editpwd']=$t;
    			$data['itype']=$v['type'];
    			$data['category']=$v['category'];
    			if($mid==4){
    				$data['itype']=$v['biz_type'];
    				$data['category']=$v['biz_industrie'];
    			}
    			if($mid==5){
    				$data['itype']=$v['sell_type'];
    			}
    			if ($mid==8) {
    				$data['itype']=$v['pr_type'];
    			}
    			if ($mid==9) {
    				$data['itype']=$v['ann_type'];
    			}
    			$data['telephone']=$v['telephone'];
    			$data['mobphone']=$v['mobphone'];
    			$data['email']=$v['email'];
    			$data['oicq']=$v['oicq'];
    			$data['msn']=$v['msn'];
    			$data['city_id']=$cid[$v['city_id']];
    			$data['zone_id']=$v['zone_id'];
    			$data['street_id']=$v['street_id'];
    			$data['ismake']=$v['yz'];
    			$data['description']=msubstr(strip_tags($v['content']),0,400);
				
    			$aid=$dao->Table('iic_archives')->add($data);
    			if ($aid) {
	    			switch ($mid) {
	    				case 1://jobs
	    				//dump($v);
	    					$data=array();
	    					$data['aid']=$aid;
	    					$data['content']=$v['content'];
	    					$addon_id=$dao->Table('iic_addon_jobs')->add($data);
	    					if($addon_id){
	    						echo '执行成功!<br>';
	    					}else {
	    						echo '<b>附加表写入失败！!<b><br>';
	    					}
	    				break;
	    				case 2://Real Estate
	    					$data=array();
	    					$data['aid']=$aid;
							$data['published']=$v['real_published'];
	    					$data['price']=$v['real_price'];
							$data['size']=$v['real_size'];
	    					$data['rooms']=$$v['real_rooms'];
	    					$data['content']=$v['content'];
	    					$addon_id=$dao->Table('iic_addon_realestate')->add($data);
	    					if($addon_id){
	    						echo '执行成功!<br>';
	    					}else {
	    						echo '<b>附加表写入失败！!<b><br>';
	    					}
	    				break;
						case 3://Services
	    					$data=array();
	    					$data['aid']=$aid;
	    					$data['content']=$v['content'];
	    					$addon_id=$dao->Table('iic_addon_services')->add($data);
	    					if($addon_id){
	    						echo '执行成功!<br>';
	    					}else {
	    						echo '<b>附加表写入失败！!<b><br>';
	    					}
	    				break;
						case 4://Biz Opportunities
	    					$data=array();
	    					$data['aid']=$aid;
	    					$data['content']=$v['content'];
	    					$addon_id=$dao->Table('iic_addon_agents')->add($data);
	    					if($addon_id){
	    						echo '执行成功!<br>';
	    					}else {
	    						echo '<b>附加表写入失败！!<b><br>';
	    					}
	    				break;
						case 5://Sell &Buy
							$data=array();
							$data['aid']=$aid;
							$data['quantity']='/';
							$data['price']=$v['sell_price'];
							$data['content']=$v['content'];
							$addon_id=$dao->Table('iic_addon_commerce')->add($data);
	    					if($addon_id){
	    						echo '执行成功!<br>';
	    					}else {
	    						echo '<b>附加表写入失败！!<b><br>';
	    					}
						break;
						case 8://Sell &Buy
							$data=array();
							$data['aid']=$aid;
							$data['content']=$v['content'];
							$addon_id=$dao->Table('iic_addon_personals')->add($data);
	    					if($addon_id){
	    						echo '执行成功!<br>';
	    					}else {
	    						echo '<b>附加表写入失败！!<b><br>';
	    					}
						break;
						case 9://Sell &Buy
							$data=array();
							$data['aid']=$aid;
							$data['content']=$v['content'];
							$addon_id=$dao->Table('iic_addon_personals')->add($data);
	    					if($addon_id){
	    						echo '执行成功!<br>';
	    					}else {
	    						echo '<b>附加表写入失败！!<b><br>';
	    					}
						break;
						
	    				default:
	    					echo('数据模块不匹配！');
	    				break;
	    			}
    			}else{
    				echo('档案表写入失败！');
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
 *导出城市指南内容
 *@date 2010-5-12
 *@time 下午04:58:29
 */
function get_cityguide() {
	load("extend");
	 $cid=array(
    	1=>'2',//bj
    	2=>'3',//sh
    	27=>'1',//gz
    	28=>'4',//sz
    );
	$p=$_GET['page'];
	//导出城市指南内容
	$dao=new Model();
	//$sql="SELECT a.*,b.my_content FROM bfc_article AS a LEFT JOIN bfc_article_content_4 AS b ON a.aid=b.aid";
	$p1=empty($p)?0:$p*50;
    $p2=($p+1)*50;
    $sqlc="SELECT count(*) c FROM bfc_article AS a LEFT JOIN bfc_article_content_4 AS b ON a.aid=b.aid LEFT JOIN bfc_reply AS r ON b.rid=r.rid";
    $list=$dao->query($sqlc);
    if($list[0]['c']>$p2){
    	$np=empty($p)?2:$p+1;
    	$limit="LIMIT {$p1},50";
    	echo $limit."<hr>";
    	
    	$sql="SELECT a.*,b.my_content,r.content FROM bfc_article AS a LEFT JOIN bfc_article_content_4 AS b ON a.aid=b.aid LEFT JOIN bfc_reply AS r ON b.rid=r.rid ORDER BY a.aid $limit";
    	echo $sql.'<br>';
    	echo '<a href="'.__URL__.'/get_cityguide/page/'.$np.'">下一页</a>';
    	$list=$dao->query($sql);
    	echo $dao->getLastSql();
    	$i=1;
    	//dump(empty($list));
    	if(!empty($list)){
    		foreach ($list as $v){
    			
	    		$t=time();
	    		$v['fid']=$v['fid']+1000;
	    		
		    	$data=array();
		    	$data['typeid']=$v['fid'];
		    	//$data['cid']=$cid[$v['city_id']];
		    	$data['uid']=$v['uid'];
		    	$data['channel']=2;
		    	$data['click']=$v['hits'];
		    	$data['title']=$v['title'];
		    	
		    	$data['pubdate']=$v['posttime'];
    			$data['senddate']=$v['posttime'];
    			$data['senddate']=$v['posttime'];
    			$data['showstart']=$v['posttime'];

    			$kw=str_word_count($v['title'],1);
    			$keywords="";
    			foreach ($kw as $vkw){
    				$keywords.=$vkw.',';
    			}
    			$data['keywords']=empty($v['keywords'])?trim($keywords,','):$v['keywords'];


		    	$data['comments']=$v['comments'];
		    	$data['picurl']=$v['picurl'];
		    	$data['my_content']=empty($v['my_content'])?'':nl2br(strip_tags($v['my_content']));
		    	$data['uip']=$v['ip'];
		    	
    			
    			$data['uid']=$v['uid'];
		    	$data['lastview']=$v['lastview'];
		    	$data['editpwd']=$v['edittime'];

		    	$data['ismake']=$v['yz'];
		    	$data['description']=msubstr(strip_tags($v['my_content']),0,200);

		    	$aid=$dao->Table('iic_archives')->add($data);
		    	if($aid){
		    		$article=array();
		    		$article['aid']=$aid;
		    		$article['content']=$v['content'];
		    		$article_id=$dao->Table('iic_addon_article')->add($article);
		    		if($article_id){
    					echo '执行成功!<br>';
    				}else {
    					echo '<b>附加表写入失败！!<b><br>';
    					//$this->error('附加表写入失败！');
    				}
		    	}else {
		    		echo '<b>档案表写入失败！!<b><br>';
		    	}
    		}
    		echo '<script>setTimeout(window.location.href="'.__URL__.'/get_cityguide/page/'.$np.'",300000);</script>';
    		echo '<a href="'.__URL__.'/get_cityguide/page/'.$np.'">下一页</a>';
    	}else{
    		echo '<b>数据查询失败！!<b><br>';
    	}
    }else{
    	exit('导出结束');
    }
}//end get_cityguide

/**
 *
 *@date 2010-5-27
 *@time 下午04:10:49
 */
function function_name() {
	//
	
}//end function_name

}//class end
?>

