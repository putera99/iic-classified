<?php
class IndexAction extends Action{
    public function index(){
    	//查出城市
    	echo "<a href='".__URL__."/ctype'>查看分类信息类别</a>";
    }
    
    /**
     *
     *@date 2010-4-24
     *@time 下午03:00:24
     */
    function ctype() {
    	$sort=M("FenleiSort");
    	$dao=new Model();
    	$list=$sort->findAll();
    	$channeltype=array(
    		1=>4,//jobs
    		2=>5,//Real Estate
    		3=>9,//Services
    		4=>'7',//Biz Opportunities
    		5=>'6',//Sell &Buy
    		8=>8,//Personals
    		9=>'8'//Announcement
    		);
    	foreach ($list as $v){
    		$sql="INSERT INTO `iic_arctype` (`id`, `reid`, `topid`, `sortrank`, `typename`, `ename`, `typedir`, `issend`, `channeltype`, `uid`, `cid`, `maxpage`, `ispart`, `corank`, `tempindex`, `templist`, `temparticle`, `namerule`, `namerule2`, `modname`, `description`, `keywords`, `seotitle`, `moresite`, `sitepath`, `siteurl`, `ishidden`, `cross`, `hits`, `posttime`, `mtime`, `crossid`, `content`, `smalltypes`)";
    		$sql.="VALUES (";
    		$sql.="{$v['fid']},{$v['fup']},0,{$v['list']},'{$v['name']}','','','',";
    		$sql.=$channeltype[$v['mid']];//系统模型
    		$sql.=",'','','25','{$v['type']}','','','','','','','','','description','{$v['name']}','','','','','0','0','".time()."','0','','','{$v['fid']}__{$v['fup']}');";
    		// `description`, `keywords`, `seotitle`, `moresite`, `sitepath`, `siteurl`, `ishidden`, `cross`, `hits`, `posttime`, `mtime`, `crossid`, `content`, `smalltypes`
    		echo $sql;
    		if ($dao->execute($sql)) {
    			echo '执行成功';
    		}else{
    			echo '执行bu成功';
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
     *
     *@date 2010-4-24
     *@time 下午02:59:24
     */
    function classified() {
    	
    }//end classified
    
    
}
?>