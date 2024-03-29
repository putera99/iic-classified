<?php
/**
 +------------------------------------------------------------------------------
 * ClassifiedsAction控制器类
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  app
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-4-29
 * @time  上午09:57:29
 +------------------------------------------------------------------------------
 */
class ClassifiedsAction extends CommonAction{
	protected $pcid='';//页面查询用的独立城市ID
	//protected $cityguide_type=array();
	
	/**
	  *预处理
	  *@date 2010-4-30
	  *@time 上午10:08:20
	  */
	function _initialize() {
		//预处理
		parent::_initialize();
		$this->assign('city_type',$this->_get_tree(1000));
		$ad=array();
		$cid=Input::getVar($_GET['cid']);
		if ($cid){
			if($_SESSION['cid']){
				$this->pcid=$cid;
			}else{
				$_SESSION['cid']=$cid;
				$this->pcid=$cid;
				cookie('cid',null);
				if ($_REQUEST['remember']) {
					cookie('cid',$cid,array('expire'=>60*60*60*24*30));
				}
			}
		}else{
			//$this->_set_cid();
			$this->pcid=$this->cid;
		}
	}//end _initialize()
	
	
	/**
	   *分类信息频道页
	   *@date 2010-4-29
	   *@time 上午09:58:27
	   */
	function index() {
		//分类信息频道页
		$this->chk_cid();
		$this->ads('3','channel','1');
		$arctype=D("Arctype");
		$kinfo='';//标题后面附加的城市后缀
		if($this->pcid){
			if($this->pcid > 1000){
				$kinfo=$this->cgroup[$this->pcid]['name'];
				$this->assign('city_group',$this->cgroup[$this->pcid]['name']);
			}else{
				$kinfo=get_cityname($this->pcid);
			}
			$this->assign("cityname",$kinfo);
		}
		$data=$arctype->where("topid=1 AND ishidden=0")->order("id asc")->findAll();
		$list=list_to_tree($data,'id','reid','_son',1);
		$this->assign('list',$list);
		//dump($list);
		$info=$arctype->where('id=1')->find();
		$this->assign('info',$info);
		
		$group=$this->_get_group('hot',"0,5");
        $this->assign('group',$group);
    	
		$page=array();
		$page['title']=empty($info['seotitle'])?$kinfo.' '.$info['typename'].' - BeingfunChina 缤纷中国':$kinfo.' '.$info['seotitle'].' - BeingfunChina 缤纷中国';
		$page['keywords']=empty($info['keywords'])?$info['typename']:$info['keywords'];
		$page['description']=empty($info['description'])?$info['typename']:$info['description'];
		$this->assign('page',$page);
		$this->assign('city_type',$this->_get_tree(1000));
		$this->assign('classifieds_type',$this->_get_tree(1));

		$this->display();
	}//end index
	
	/**
	 *分类信息列表页面
	 *@date 2010-4-30
	 *@time 下午04:36:24
	 */
	function ls() {
		//分类信息列表页面
		$this->chk_cid();
		$typeid=intval($_GET['id']);
		$kinfo='';
		$sql='';
		$info=array();
		$city_str="";
		if($this->pcid){//检查城市或者城市群
			if($this->pcid > 1000){
				$kinfo=$this->cgroup[$this->pcid]['name'];
				$this->assign('city_group',$this->cgroup[$this->pcid]['name']);
			}else{
				$kinfo=' in '.get_cityname($this->pcid);
			}
		}//检查城市或者城市群
		if($this->pcid=='0'){
			$city_str="";
		}elseif($this->pcid<1000){
			$city_str=" AND (cid={$this->pcid} or cid='0')";
		}else{
			$city_temp=array('name'=>'','id'=>'');
			foreach ($this->cgroup[$this->pcid]['city'] as $k=>$v){
				$city_temp['id'].=$k.',';
				$city_temp['name'].=$v.',';
			}
			$city_str=" AND (cid in ({$city_temp['id']}) or cid='0')";
		}
		$typeid=empty($typeid)?'1':$typeid;
		if($typeid=='1'&&$_GET['flag']=='f'){
			$sql="`channel` IN (4,5,6,7,8,9) AND (FIND_IN_SET('f',`flag`) > 0) AND ismake=1 $city_str";
		}else{		
			$arctype=D("Arctype");
			//标题后面附加的城市后缀
			
			$info=$arctype->where("id=$typeid")->find();
			$this->ads($info['channeltype'],'list','1');
			if($info['ispart']==1){
				$small=$arctype->where("reid=$typeid")->field("id")->findAll();
				$str='';
				foreach ($small as $v){
					$str.=$v['id'].',';
				}
				$str.=$typeid;
				$str='typeid IN ('.trim($str,',').')';
			}else{
				$str="typeid={$typeid}";
			}
			$sql="$str $city_str AND ismake=1";
		}
		$this->assign("cityname",$kinfo);
		//信息列表
		$now=time();
		import("ORG.Util.Page");
		$dao=D("Archives");
		$count=$dao->where($sql)->order("pubdate DESC")->count();
		$page=new Page($count,10);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$page->config=array('header'=>'','prev'=>'<','next'=>'>','first'=>'«','last'=>'»','theme'=>'  %first% %upPage%  %prePage%  %linkPage%  %nextPage% %downPage% %end%');
		$this->assign('showpage_bot',$page->show_img());
		$limit=$page->firstRow.','.$page->listRows;
		//$data=$dao->where("((typeid={$typeid} AND cid={$this->pcid}) AND ismake=1) AND (showstart<{$now} AND showend>{$now}))")->order("pubdate DESC")->limit("$limit")->findAll();
		$data=$dao->where($sql)->order("pubdate DESC")->limit("$limit")->findAll();
		//dump($dao->getLastSql());
		$this->assign('list',$data);
		
		//页面信息
		$arctype=D("Arctype");
		$info=$arctype->where("id=$typeid")->find();
		$info['typename']=$typeid=='1'?"Featured Classifieds":$info['typename'];
		$this->assign('info',$info);
		
		$group=$this->_get_group('new',"0,5");
    	$this->assign('group',$group);
		$kinfo.=empty($_GET['p'])?'':" page ".$_GET['p'];
		$page=array();
		$page['title']=empty($info['seotitle'])?$info['typename'].' '.$kinfo.' '.$city_temp['name'].' - BeingfunChina 缤纷中国':$info['seotitle'].' '.$kinfo.' '.$city_temp['name'].' - BeingfunChina 缤纷中国';
		$page['keywords']=empty($info['keywords'])?$info['typename'].' '.$city_temp['name']:$info['keywords'].' '.$city_temp['name'];
		$page['description']=empty($info['description'])?$info['typename']:$info['description'];
		
		if($info['reid']!='1'){
			$reinfo=$arctype->where("id={$info['reid']}")->find();
			$this->assign('reinfo',$reinfo);
		}
		$this->assign('city_type',$this->_get_tree(1000));
		$this->assign('classifieds_type',$this->_get_tree(1));
		$this->assign('page',$page);
		
		$this->display();
	}//end function_name
	
	/**
	 *分类信息内容页面
	 *@date 2010-4-30
	 *@time 下午05:08:30
	 */
	function show() {
		//分类信息内容页面
		$aid=intval($_GET['aid']);
		if(empty($aid)){
			$this->error("error: aid is null!");
		}
		$dao=D("Archives");
		$info=$dao->where("id=$aid")->find();
		if (empty($info)) {
			$this->error("error: info is null!");
		}
		if($info['ismake']=='0'){
			$this->error("error: the information has been deleted!");
		}
        $this->ads($info['channel'],'content','1');
		$city=$this->_get_city('localion');
		switch (true) {
			case $info['channel']==4://Jobs
				$info['_jobs']=$dao->relationGet("jobs");
				$info['itype']=$info['itype']=='0'?'All':$info['itype']=='1'?'Offered':'Wanted';
				$category=array("All","Full time","Part time","Internship","Voluntary");
				$info['category']=$category[$info['category']];
			break;
			
			case $info['channel']==5://realestate
				$info['_realestate']=$dao->relationGet("realestate");
			break;
			
			case $info['channel']==6://commerce
				$types=array(1=>'All',2=>'Offered',3=>'Wanted');
				$cat=array('Brand-new','Second-hand');
				$info['itype']=$types[$info['type']];
				$info['category']=$cat[$info['category']];
				$info['_commerce']=$dao->relationGet("commerce");
			break;
			
			case $info['channel']==7://agents
				$info['_agents']=$dao->relationGet("agents");
			break;
			
			case $info['channel']==8://personals
				$info['_personals']=$dao->relationGet("personals");
			break;
			
			case $info['channel']==9://services
				$info['itype']=$info['itype']==1?'All':$info['itype']==2?'Offered':'Wanted';
				$category=array("All","Full time","Part time","Internship","Voluntary");
				$info['category']=$category[$info['category']];
				$info['_services']=$dao->relationGet("services");
			break;
		}
		$local='';
		if($info['zone_id'] && $info['city_id']){
			$local=$city[$info['city_id']]['_zone'][$info['zone_id']]['name'].','.$city[$info['city_id']]['cename'];
		}elseif(empty($info['zone_id']) && $info['city_id']) {
			$local=$city[$info['city_id']]['cename'];
		}
		$info['position']=trim($info['position'].','.$local,',');
		$album=$this->get_album($info['id'],$info['channel']);
		if($info['picurl']){
			$info['picurl']=str_replace('../Public/Uploads','/Public/Uploads',$info['picurl']);
		}else{
			$info['picurl']=out_images($album['0'],'s');
		}
		
		$info['albumnum']=count($album);
		$this->assign("album",$album);
		$this->assign('info',$info);
		
		//$this->assign('comments',$this->_get_comments($info['id'],$info['channel']));
		$this->assign("group",$this->member_group($this->member_comments($info['id'],$info['channel']),"0,6"));
		
		$page=array();
		$page['title']=$info['title'].' - BeingfunChina 缤纷中国';
		$page['keywords']=$info['keywords'];
		$page['description']=$info['description'];
		$this->assign('page',$page);
		
		if($this->_is_admin() || $this->user['uid']==$info['uid']){
			$cp='<a href="/Cp/photo/info/'.$info['channel'].'_'.$info['id'].'">Add Photos</a> / <a href="/Cp/my_edit_classifieds/info/'.$info['channel'].'_'.$info['id'].'">edit</a> / <a href="/Cp/del_arc/info/'.$info['channel'].'_'.$info['id'].'/to/'.myencode("/clist/{$info['typeid']}.html").'/">remove</a>';
			$cp.=string2checked('f',$info['flag'],',','1')==1?" / <a href=\"/Cp/attr/info/{$info['channel']}_{$info['id']}/clear/f/to/".myencode("/clss/{$info['id']}.html")."\" title=\"clear Featured\"><font color=\"#FF0000\">Featured</font></a> / ":" / <a href=\"/Cp/attr/info/{$info['channel']}_{$info['id']}/fld/f/to/".myencode("/clss/{$info['id']}.html")."\" title=\"Set Featured\">Featured</a> / ";
			$this->assign('cp',$cp);
		}
		//dump($this->_get_dh($info['typeid']));
		$this->assign('dh',$this->_get_dh($info['typeid']));
		$cid_sql='';
		if($this->pcid=='0'){
			$cid_sql="";
		}elseif($this->pcid<1000){
			$cid_sql=" AND (cid={$this->pcid} or cid='0')";
		}else{
			$city_temp=array('name'=>'','id'=>'');
			foreach ($this->cgroup[$this->pcid]['city'] as $k=>$v){
				$city_temp['id'].=$k.',';
				$city_temp['name'].=$v.',';
			}
			$cid_sql=" AND (cid in ({$city_temp['id']} or cid='0')";
		}
		$this->assign("new_arc",$this->_get_uptype_arc($info['typeid']));
		
		$f_arc = array ();
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
		$f_arc = $dao->where ( $condition )->order ( "pubdate DESC" )->limit ( "0,20" )->findAll ();
		shuffle ( $f_arc );
		$this->assign ( 'f_arc', $f_arc );
		
		$condition=array();
		$st=mktime(0,0,1,date("m"),date("d"),date("Y"));
        $et=mktime(59,59,59,date("m"),date("d")+30,date("Y"));
        $condition['channel'] = '10';
        $condition['ismake'] = '1';
        $condition['_string']="`showstart`>={$st} AND `showstart`<={$et}";
		if($this->pcid=='0'||empty($this->pcid)){
			$city='';
		}elseif($this->pcid<1000){
			$city=" AND (cid={$this->pcid} or cid='0')";
		}else{
			$city_temp=array('name'=>'','id'=>'');
			foreach ($this->cgroup[$this->pcid]['city'] as $k=>$v){
				$city_temp['id'].=$k.',';
				$city_temp['name'].=$v.',';
			}
			$city=" AND (cid in ({$city_temp['id']}) or cid='0')";
		}
        $condition['_string'].=$city;
        $f_event=array();
        $f_event=$dao->where($condition)->order("pubdate DESC")->limit("0,20")->findAll();
        shuffle ( $f_event );
        
        $this->assign("event",$f_event);
		$this->display();
	}//end show
	
	/**
	 *检查城市选项
	 *@date 2010-6-23
	 *@time 上午10:17:39
	 */
	protected function chk_cid() {
		//检查城市选项
		$cid=Input::getVar($_GET['cid']);
		if ($cid){
			if($_SESSION['cid']){
				$this->pcid=$cid;
			}else{
				$_SESSION['cid']=$cid;
				$this->pcid=$cid;
				cookie('cid',null);
				if ($_REQUEST['remember']) {
					cookie('cid',$cid,array('expire'=>60*60*60*24*30));
				}
			}
		}else{
			//$this->_set_cid();
			$this->pcid=empty($this->cid)?0:$this->cid;
		}
	}//end chk_cid


}//end ClassifiedsAction