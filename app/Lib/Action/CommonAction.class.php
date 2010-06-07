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
     *查城市和区
     *@date 2010-5-26
     *@time 下午04:54:28
     */
    function _get_city($type,$clear=0) {
    	//查城市和区
    	$name='city_'.$type;
    	if($clear==1){
    		S($name,null);
    	}
		$data=S($name);
		if(empty($data)){
	    	$dao=M("ActCity");
	    	$city=$dao->where("$type=1")->findAll();
	    	$zone=M("Zone");
	    	$data=array();
	    	foreach ($city as $v){
	    		$data[$v['id']]=$v;
	    		$z='';
	    		$z=$zone->where("cid={$v['id']}")->order("list asc")->findAll();
	    		$qu=array();
	    		foreach ($z as $x){
	    			$qu[$x['id']]=$x;
	    		}
	    		$data[$v['id']]['_zone']=$qu;
	    	}
	    	S($name,$data,60*60*60*24);
		}
		return $data;
    }//end _get_city
    
    /**
     *ajax获得城市下的区
     *@date 2010-5-28
     *@time 下午02:26:38
     */
   Public function ajax_zone($cid,$types,$name=0) {
    	//ajax获得城市下的区
    	$cid=empty($cid)?$_REQUEST['cid']:$cid;
    	$types=empty($types)?$_REQUEST['types']:$types;
    	$name=empty($_REQUEST['name'])?0:$_REQUEST['name'];
    	$data=$this->_get_city($types);
    	$this->assign('name',$name);
    	$this->assign("zone",$data[$cid]['_zone']);
    	$this->display("Common:ajax_zone");
    }//end ajax_zone
    
    /**
     *ajax增加公司
     *@date 2010-6-1
     *@time 上午11:52:40
     */
    public function ajax_add_ltd() {
    	//ajax增加公司
    	$dao=D("Ltd");
    	$data=array();
    	$data=$dao->create($_REQUEST);
    	$info=$dao->where("title='{$_REQUEST['title']}'")->findAll();
    	if(empty($info)){
	    	$ltd_id=$dao->add($data);
	    	if($ltd_id){
	    		$this->ajaxReturn($ltd_id,'add '.$data['title'],1);
	    	}else{
	    		$this->ajaxReturn(0,'ERROR',0);
	    	}
    	}else{
    		$this->ajaxReturn(0,'ERROR',0);
    	}
    }//end ajax_add_ltd
    
    /**
     *获取公司信息
     *@date 2010-6-1
     *@time 下午02:43:18
     */
    protected function _get_ltd() {
    	//获取公司信息
    	$dao=D("Ltd");
    	return $dao->where("status=1")->findAll();
    }//end _get_ltd
    
    
    /**
     *用户收藏
     *@date 2010-5-23
     *@time 下午03:08:54
     */
    public function user_collection($tid,$types,$listid=0) {
    	//用户收藏
    	$tid=empty($tid)?$_REQUEST['tid']:$tid;
    	$types=empty($types)?$_REQUEST['types']:$types;
    	$listid=empty($_REQUEST['listid'])?0:$_REQUEST['tid'];
    	if (!$this->_is_login()){
			$this->ajaxReturn(0,'no login!',0);
		}else{
	    	$dao=D("UserCollection");
	    	$condition=array();
	    	$condition['uid']=$this->user['uid'];
	    	$condition['tid']=$tid;
	    	$condition['types']=$types;
	    	$info=$dao->where($condition)->findAll();
	    	if ($info) {
	    		$this->ajaxReturn(0,'资源已经收藏!',0);
	    	}else{
	    		$data=array();
	    		$data['uid']=$this->user['uid'];
	    		$data['tid']=$tid;
	    		$data['types']=$types;
	    		$data['listid']=$listid;
	    		$data['username']=get_username();
	    		$data['ctime']=time();
	    		$data['is_show']=0;
	    		$id=$dao->add($data);
	    		if ($id) {
	    			$this->ajaxReturn($id,'收藏成功！',1);
	    		}else{
	    			$this->ajaxReturn(0,'收藏失败！',0);
	    		}
	    	}
		}
    	
    }//end _user_collection
    
    /**
     *获取指定类的信息
     *@date 2010-5-20
     *@time 上午09:26:44
     */
    protected function _get_carc($typeid,$limit="0,10",$cid,$uid) {
    	//获取指定类的信息
    	
    	$condition=array();
    	
    	$type=D("Arctype");
    	$data=$type->where("id=$typeid")->field('id,typename,reid,topid,ispart')->find();
    	$typearr=array();
    	if($data['ispart']!=0){
    		$typearr=$type->where("reid=$typeid")->field('id,typename,reid,topid,ispart')->findAll();
    		$in='';
    		foreach ($typearr as $v){
    			$in.=$v['id'].',';
    		}
    		$in=trim($in,',');
    		$condition['typeid']=array('IN',$in);
    	}else{
    		$condition['typeid']=$typeid;
    	}
		
    	$dao=D("Archives");
    	if ($cid) {
    		$condition['cid']=$cid;
    	}
    	if($uid){
    		$condition['uid']=$uid;
    	}
    	$info=$dao->where($condition)->limit($limit)->findAll();
    	//dump($info);
    	return $info;
    }//end _get_cart
    
    /**
     *设置城市
     *@date 2010-5-4
     *@time 上午09:48:13
     */
   protected function _set_cid() {
    	//设置城市
    	$this->cid=empty($this->user['usercid'])?$_SESSION['cid']:$this->user['usercid'];
    	$this->cid=empty($this->cid)?cookie('cid'):$this->cid;
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
    protected function _get_comments($aid,$types) {
    	//获取话题的评论
    	$dao=D("Comment");
    	return $dao->where("xid=$aid AND types=$types")->findAll();
    }//end _get_comments
    
    /**
     *ajax用户收藏
     *@date 2010-5-29
     *@time 下午02:40:33
     */
    public function ajax_user_collection($types=0,$uid=0,$pn=10) {
    	//ajax用户收藏
    	$uid=empty($_REQUEST['uid'])?$uid:$_REQUEST['uid'];
    	$types=empty($_REQUEST['types'])?$types:$_REQUEST['types'];
    	$pn=empty($_REQUEST['pn'])?$pn:$_REQUEST['pn'];
    	$dao=D("UserCollection");
    	$condition=array();
    	$condition['uid']=$uid==0?$this->user['uid']:$uid;
    	if($types!=0){
    		$condition['types']=$types;
    	}
    	$count=$dao->where($condition)->count();
    	import("ORG.Util.Page");//引用分页类
		import("@.Com.ajaxpage");//引用ajax分页类
		$p= new ajaxpage($count,$pn);
		$page=$p->ajaxshow();//显示分页
		$this->assign("showpage_bot",$page);//显示分页
		$limit=$p->firstRow.",".$p->listRows;//设定分面的大小
		$limit=($limit==",")?'':$limit;//分页的大小
    	$data=$dao->where($condition)->limit($limit)->order('ctime DESC')->findAll();
    	$this->assign('collection',$data);
		$this->display("Common:ajax_user_collection");
    }//end ajax_user_collection
    
    /**
     *ajax调用评论
     *@date 2010-5-24
     *@time 下午05:51:07
     */
    public function ajax_comments() {
    	//ajax调用评论
    	$dao=D("Comment");
    	$condition=array();
    	$condition['xid']=$_REQUEST['xid'];
    	$condition['types']=$_REQUEST['types'];
    	$count=$dao->where($condition)->count();
    	import("ORG.Util.Page");//引用分页类
		import("@.Com.ajaxpage");//引用ajax分页类
		$p= new ajaxpage($count,10);
		$page=$p->ajaxshow();//显示分页
		$this->assign("showpage_bot",$page);//显示分页
		$limit=$p->firstRow.",".$p->listRows;//设定分面的大小
		$limit=($limit==",")?'':$limit;//分页的大小
    	$data=$dao->where($condition)->limit($limit)->order('dateline DESC')->findAll();
    	$this->assign('comments',$data);
    	//dump($dao->getLastSql());
		$this->display("Common:ajax_comments");
    }//end ajax_comments
    
    /**
     *获取当前栏目位置
     *@date 2010-5-10
     *@time 上午09:47:29
     */
    protected function _get_dh($typeid) {
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
    protected function _get_cityguide_type() {
    	//获得城市指南的大类
    	$dao=D("Arctype");
    	return $dao->where("(id>1000 AND reid=1000) AND ishidden=0")->order("id asc")->findAll();
    }//end _get_cityguide_type
    
	/**
	 *获取分类信息的大类
	 *@date 2010-4-30
	 *@time 上午10:35:37
	 */
	protected function _get_classifieds_type() {
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
    
    public function tag() {
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
	
	public function verify(){
		//verify验证码
		import('ORG.Util.Image');
		if(isset($_REQUEST['adv'])) {
        	Image::showAdvVerify();
        }else {
        	Image::buildImageVerify();
        }
	}//verify function END
	
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
	
	protected function _get_tree($topid) {
		$dao=new Model();
		$list=$dao->query("SELECT * FROM iic_arctype where topid=$topid AND ishidden=0");
		$news=list_to_tree($list,'id','reid','_son',$topid);
		unset($dao);
		return $news;
	}//end tree
	
	protected function _get_flag(){
		$dao=M("Arcatt");
		return $dao->findAll();
	}
	
	protected function _new_list($typeid,$flag='',$limit="0,10"){
    	$dao=D("Archives");
    	$condition=array();
    	$condition['typeid']=$typeid;
    	if($flag){
    		$arr=explode(',',$flag);
    		foreach ($arr as $v){
    			if($v=='p'){
    				$condition['_string'].="picurl<>''";
    			}else{
    				$condition['_string'].="FIND_IN_SET('$v',`flag`) > 0";
    			}
    		}
    	}
    	if($limit=='0,1'||$limit=='1'){
    		$data=$dao->where($condition)->order('pubdate DESC')->limit($limit)->find();
    	}else{
    		$data=$dao->where($condition)->order('pubdate DESC')->limit($limit)->findAll();
    	}
    	//dump($dao->getLastSql());
    	return $data;
    }
    
    
    protected function _upload($tid,$width='120',$height='140'){
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize  = 3292200 ;
        //设置上传文件类型
        $upload->allowExts  = explode(',','jpg,gif,png,jpeg');
        //设置附件上传目录
        $tid=empty($tid)?$_REQUEST['typeid']:$tid;
        $path=$tid.'/'.date('Y-m').'/';
        $upload->savePath =  './Public/Uploads/'.$path;
        mk_dir($upload->savePath);
        $path='/Public/Uploads/'.$path;
	    //设置需要生成缩略图，仅对图像文件有效
       $upload->thumb =  true;
       //设置需要生成缩略图的文件后缀
	    $upload->thumbPrefix   =  's_';  //生产2张缩略图
       //设置缩略图最大宽度
		$upload->thumbMaxWidth =  '120';
       //设置缩略图最大高度
		$upload->thumbMaxHeight = '140';
	   //设置上传文件规则
	   $upload->saveRule = uniqid;
	   //删除原图
	   $upload->thumbRemoveOrigin = false;
        if(!$upload->upload()) {
            //捕获上传异常
            $this->error($upload->getErrorMsg());
        }else {
            //取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
            $_POST['picurl']  = $path.'s_'.$uploadList[0]['savename'];
        }
        
        return $_POST['picurl'];
	}
	
	protected function _group_up($tid,$width='120',$height='140'){
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize  = 3292200 ;
        //设置上传文件类型
        $upload->allowExts  = explode(',','jpg,gif,png,jpeg');
        //设置附件上传目录
        $tid=empty($tid)?$_REQUEST['typeid']:$tid;
        $path=$tid.'/'.date('Y-m').'/';
        $upload->savePath =  './Public/Uploads/Group/'.$path;
        mk_dir($upload->savePath);
        $path='/Public/Uploads/Group/'.$path;
	    //设置需要生成缩略图，仅对图像文件有效
       $upload->thumb =  true;
       //设置需要生成缩略图的文件后缀
	    $upload->thumbPrefix   =  's_';  //生产2张缩略图
       //设置缩略图最大宽度
		$upload->thumbMaxWidth =  '120';
       //设置缩略图最大高度
		$upload->thumbMaxHeight = '140';
	   //设置上传文件规则
	   $upload->saveRule = uniqid;
	   //删除原图
	   $upload->thumbRemoveOrigin = false;
        if(!$upload->upload()) {
            //捕获上传异常
            $this->error($upload->getErrorMsg());
        }else {
            //取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
            $_POST['pic']  = $path.'s_'.$uploadList[0]['savename'];
        }
        
        return $_POST['pic'];
	}
	
    protected function _photo($tid){
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize  = 3292200 ;
        //设置上传文件类型
        $upload->allowExts  = explode(',','jpg,gif,png,jpeg');
        //设置附件上传目录
        $tid=empty($tid)?$_REQUEST['typeid']:$tid;
        $path=$tid.'/'.date('Y-m').'/';
        $upload->savePath =  './Public/album/'.$path;
        mk_dir($upload->savePath);
        $path='/Public/album/'.$path;
	    //设置需要生成缩略图，仅对图像文件有效
       $upload->thumb =  true;
       //设置需要生成缩略图的文件后缀
	    $upload->thumbPrefix   =  'm_,s_';  //生产2张缩略图
       //设置缩略图最大宽度
		$upload->thumbMaxWidth =  '800,200';
       //设置缩略图最大高度
		$upload->thumbMaxHeight = '600,150';
	   //设置上传文件规则
	   $upload->saveRule = uniqid;
	   //删除原图
	   $upload->thumbRemoveOrigin = false;
        if(!$upload->upload()) {
            //捕获上传异常
            $this->error($upload->getErrorMsg());
        }else {
            //取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
        }
        
        return $uploadList;
	}
	
	/**
	 *发布评论
	 *@date 2010-5-24
	 *@time 上午09:34:27
	 */
	public function post_comment() {
		//发布评论
		if (empty($this->user['uid'])) {
			$this->ajaxReturn(0,'no login',0);
		}
		if (empty($_REQUEST['verify']) || md5($_REQUEST['verify'])!=$_SESSION['verify']){
			$this->ajaxReturn(0,'verify!',0);
		}
		$dao=D("Comment");
		$vo=$dao->create($_REQUEST);
		if($vo){
			$vo['uid']=$this->user['uid'];
			$id=$dao->add($vo);
			if ($id) {
				unset($_SESSION['verify']);
				$this->ajaxReturn($id,'评论成功',1);
				
			}else{
				$this->ajaxReturn(0,'评论失败',0);
			}
		}else{
			//dump($dao->getError());
			$this->ajaxReturn(0,$dao->getError(),0);
		}

		//unset($dao);
	}//end post_comment
	
	
///////////////////////////////群组的共用类————start//////////////////////////
	/**
	 *获取制定类下的子类信息
	 *@date 2010-6-5
	 *@time 上午11:54:24
	 */
	protected function _get_mtag($pid='1'){
		//获取制定类下的子类信息
		$dao=D("MtagCat");
		$condition=array();
		$condition['pid']=$pid;
		$arr=$dao->where($condition)->order("num asc")->findAll();
		$data=array();
		foreach ($arr as $v){
			$data[$v['id']]=$v;
			$data[$v['id']]['count']=$this->_get_catnum($v['id']);
			$son=$dao->where("pid={$v['id']}")->order("num asc")->findAll();
			if($son){
				$son_data=array();
				foreach ($son as $s){
					$son_data[$s['id']]=$s;
					$son_data[$s['id']]['count']=$this->_get_catnum($s['id']);
				}
			$data[$v['id']]['_son']=$son_data;
			}
		}
		return $data;
	}//end _get_mtag
	
		/**
	*计算群组类下的群组个数
	*@date Mon Jun 07 10:43:17 CST 2010
	*@time 10:43:17
	*/
	function _get_catnum($typeid){
		//计算群组类下的群组个数
		$group=D("Group");
		$cat=D("MtagCat");
		$son=$cat->where("pid=$typeid AND is_show=1")->field("id,title")->findAll();
		$str='';
		if($son){
			foreach ($son as $v){
				$str.=$v['id'].',';
			}
			$str=trim($str,',');
		}else {
			$str=$typeid;
		}
		$count=$group->where("cat_id in($str) OR fcat_id in ($str)")->count();
		return $count;
		
	}//end _get_catnum
///////////////////////////////群组的共用类————end//////////////////////////
}//END CommonAction
?>