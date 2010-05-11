<?php
/**
 +------------------------------------------------------------------------------
 * CommonAction控制器类
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  bi
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-4-14
 * @time  下午12:06:59
 +------------------------------------------------------------------------------
 */
class CommonAction extends Action{
	protected $user=array();//用户信息
	protected $cid;//城市ID
	
	protected function _initialize(){
        header("Content-Type:text/html; charset=utf-8");
        $this->user=$this->_is_login();
        $this->assign('user',$this->user);
        $this->assign('cid',$this->cid);
        $this->assign('now',date("l,F d Y",time()));
        import("ORG.Util.String");
        load("extend");
        //$this->cid=empty($this->user['usercid'])?$_SESSION['cid']:$this->user['usercid'];
        //import('ORG.Util.Image');
    }
    
    /**
     *设置城市
     *@date 2010-5-4
     *@time 上午09:48:13
     */
    function _set_cid() {
    	//设置城市
    	$this->cid=empty($this->user['usercid'])?$_SESSION['cid']:$this->user['usercid'];
    	if(empty($this->cid)){
    		$this->cid='';
    		$this->redirect("/Public/select_city");
    	}
    }//end _set_cid
    
    /**
     *获取话题的评论
     *@date 2010-5-10
     *@time 下午04:41:17
     */
    function _get_comments($aid,$types) {
    	//获取话题的评论
    	$dao=D("Comments");
    	$data=$dao->where("xid=$aid AND types=$types")->findAll();
    	
    }//end _get_comments
    
    /**
     *获取当前栏目位置
     *@date 2010-5-10
     *@time 上午09:47:29
     */
    function _get_dh($typeid) {
    	//获取当前位置
    	$dao=D("Arctype");
    	$data=$dao->where("id=$typeid")->field('id,typename,reid,topid')->find();
    	if ($data['reid']!=$data['topid']){
    		$data['_reid']=$dao->where("id={$data['reid']}")->field('id,typename,reid,topid')->find();
    	}
    	return $data;
    }//end _get_dh
    
    /**
      *获得城市指南的大类
      *@date 2010-4-30
      *@time 上午10:26:06
      */
    function _get_cityguide_type() {
    	//获得城市指南的大类
    	$dao=D("Arctype");
    	$data=$dao->where("(id>1000 AND reid=1000) AND ishidden=0")->order("id asc")->findAll();
    	return $data;
    }//end _get_cityguide_type
    
	/**
	 *获取分类信息的大类
	 *@date 2010-4-30
	 *@time 上午10:35:37
	 */
	function _get_classifieds_type() {
		//获取分类信息的大类
		$dao=D("Arctype");
    	$data=$dao->where("((id>1 AND id<1000) AND reid=1) AND ishidden=0")->order("id asc")->findAll();
    	return $data;
	}//end _get_classifieds_type
    
    /**
     * 获取指定分类的关键字
     * @param unknown_type $cid
     */
	protected function _cat_tags($id,$limit) {
		$tagscat=M("TagsCat");
		$cat=$tagscat->where("pid=$id AND is_show=1")->findAll();
		//dump($cat);
		$tags=M("Tags");
		$data=array();
		if ($cat){
			foreach($cat as $c){
				$data[$c['id']]=$c;
				$data[$c['id']]['son']=$tags->where("tcatid={$c['id']}")->limit("0,$limit")->findALl();
			}
		}else{
			$data[$id]=$tags->where("tcatid=$id")->limit("0,$limit")->findALl();
		}
		return $data;
    }// END cat_tags
    
    function tag() {
		$tname=empty($_GET['name'])?$_POST['name']:$_GET['name'];
		$pid=empty($_GET['pid'])?$_POST['pid']:$_GET['pid'];
		$type=empty($_GET['type'])?$_POST['type']:$_GET['type'];
		if(!empty($pid) && !empty($type)){
			$tagscat=M("TagsCat");
			$catname=$tagscat->where("id=$pid")->find();
		}
		$catname=empty($catname['title'])?$tname:$catname['title'];
		$this->assign('catname',$catname);
		if(empty($tname) && !empty($pid) && !empty($type)){
			$this->assign('cat',$this->_cat_tags($pid,24));
			$this->iicstat($pid,'tags_cat');
			$this->display("Public:tag");
		}elseif(!empty($tname) && empty($pid)){
			$tags=M("Tags");
			$tagsid=$tags->where("tagsname='$tname'")->find();
			$tagslink=M("TagsLink");
			$data=$tagslink->where("(tagsid={$tagsid['id']} AND type='".MODULE_NAME."') AND is_ok=1")->findALl();
			$this->iicstat($tagsid['id'],'tags');
			$this->display("Public:tag");
		}elseif(!empty($pid)){
			$this->assign('cat',$this->_cat_tags($pid,'100'));
			$this->iicstat($pid,'tags_cat');
			$this->display("Public:tag");
		}else{
			echo "参数错误";
		}
	}// END tag
	
	
    /**
     * 检查用户是否登录并获取用户信息
     */
    protected function _is_login() {
    	if (isset($_SESSION['uid']) && isset($_SESSION['username'])) {
    		$user=array('uid'=>$_SESSION['uid'],'username'=>$_SESSION['username'],'cid'=>$_SESSION['usercid']);
    	}else $user=false;
    	return $user;
    }// END _is_login
	
    /**
     * 按天统计资源点击量
     * @param int $xid 资源ID
     * @param string $type 资源类别
     */
	protected function iicstat($xid,$type='') {
		$m=new Model();
		$type=empty($type)?MODULE_NAME:$type;
		$mon=mktime(0, 0, 0, date('n'), 1);
		$d='d'.date('d');
		$sql="INSERT INTO `iic_stat` (`id`, `xid`, `mon`, `stype`, `$d`) VALUES (NULL, '$xid', '$mon', '$type', '1') ON DUPLICATE KEY UPDATE `$d`=`$d`+1;";
		return $m->execute($sql);
	}// END iicstart
	
	
	/**
	 * 获得指定资源类别的最近7天里访问量最大的资源
	 * @param unknown_type $type 资源类别
	 * @param unknown_type $limit 条数默认 '0,10'
	 */
	protected function _top7day($type,$limit='0,10') {
		$m=new Model();
		$d7=mktime(0, 0, 0, date('n'), date('d'),date('Y'))-60*60*24*7; //当前时间的前7天的秒数
		//$d7=mktime(0, 0, 0, 03, 03,date('Y'))-60*60*24*7;
		$mon=mktime(0, 0, 0, date('n'), 1,date('Y')); //当前月份的秒数
		//$mon=mktime(0, 0, 0, 03, 03,date('Y'));
		$mon2=mktime(0, 0, 0, date('n',$d7),1,date('Y')); //当前时间的前7天的秒数的月份的秒数
		//$mon2=mktime(0, 0, 0, date('n',$d7),1,date('Y'));
		$t=time();
		$field='';
		if ($mon==$mon2){//检查是否同月
			for ($i=1;$i<8;$i++){
				$dt=$t-(60*60*24)*$i;
				$field.='`d'.date('d',$dt).'`+';
			}
			$field=trim($field,'+');
			$sql="SELECT xid,stype,$field d FROM `iic_stat` WHERE (`stype`='$type' AND `mon`='$mon') ORDER BY d DESC LIMIT $limit";
		}else{//不同月需要查询两个表
			$field2='';
			for ($i=1;$i<8;$i++){
				$dt=$t-(60*60*24)*$i;
				if(date('d',$dt)>date('d')){
					$field2.='mm.`d'.date('d',$dt).'`+';
				}else{
					$field.='m.`d'.date('d',$dt).'`+';
				}
			}
			$field=trim($field.$field2,'+');
			$sql="SELECT m.xid xid,m.stype stype,$field d FROM `iic_stat` as m LEFT JOIN `iic_stat` AS mm ON m.xid=mm.xid AND m.stype=mm.stype WHERE m.`stype`='$type' AND (m.`mon`='$mon' or mm.`mon`='$mon2') ORDER BY d DESC LIMIT $limit";
		}
		return $m->query($sql);
	}// END _top7
	
	
}//END CommonAction
?>