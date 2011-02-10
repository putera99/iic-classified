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
	protected $admin=array('yeahbill','iicc','bfcadmin','Alex','bfc168');
	protected $cgroup=array(
						'1001'=>array('name'=>'Bohai Rim',
							'city'=>array(
								'13'=>'Tianjin',
								'22'=>'Dalian',
								'7'=>'Qingdao',
								'23'=>'Shenyang',
							),
						),//Bohai Rim
						'1002'=>array('name'=>'Yangtze River Delta',
							'city'=>array(
								'24'=>'Suzhou',
								'25'=>'Hangzhou',
								'17'=>'Nanjing',
								'19'=>'Ningbo',
								'8'=>'Yiwu',
							),
						),//Yangtze River Delta
						'1003'=>array('name'=>'Pan Pearl River Delta',
							'city'=>array(
								'26'=>'Kunming',
								'12'=>'Nanning',
							),
						),//Pan Pearl River Delta
						'1004'=>array('name'=>'Other areas',
							'city'=>array(
								'21'=>'Wuhan',
								'15'=>"Xi'an",
								'11'=>'Chengdu',
							),
						),//Other areas
					);
	protected $cityname=array(
		'all'=>'0',
		'beijing'=>'2',
		'guangzhou'=>'1',
		'shenzhen'=>'4',
		'shanghai'=>'3',
		'bohai_rim'=>'1001',
		'yangtze_river_delta'=>'1002',
		'pan_pearl_river_delta'=>'1003',
		'other_areas'=>'1004',
		'tianjin'=>'13',
		'dalian'=>'22',
		'qingdao'=>'7',
		'shenyang'=>'23',
		'suzhou'=>'24',
		'hangzhou'=>'25',
		'nanjing1'=>'17',
		'ningbo1'=>'19',
		'yiwu'=>'8',
		'kunming'=>'26',
		'nanning'=>'12',
		'wuhan'=>'21',
		'xi_an'=>"15",
		'chengdu'=>'11',
	);
	protected function _initialize(){
        header("Content-Type:text/html; charset=utf-8");
		import("ORG.Util.Input");
        $this->user=$this->_is_login();
        $this->assign('user',$this->user);
        $cid=trim(Input::getVar($_GET['cid']));
       if(!empty($cid)&&array_key_exists($cid,$this->cityname)){
        	$_GET['cid']=$cid=$this->cityname[strtolower($cid)];
        }elseif(!is_integer($cid)){
        	$_GET['cid']=$cid=isset($_SESSION['cid'])?$_SESSION['cid']:0;
        }
        if(!empty($cid)||$cid=='0'){
        	$_SESSION['cid']=$cid;
        }else{
        	$_SESSION['cid']=$_GET['cid']==empty($_SESSION['cid'])?'0':$_SESSION['cid'];
        }
        $this->cid=$_SESSION['cid'];
		
        $cid=empty($this->cid)?0:$this->cid;
        
        $this->assign('cid',array_search($cid,$this->cityname));
        $this->assign('now',date("D,M dS, Y",time()));
        import("ORG.Util.String");
        load("extend");
        $arr1=array('/all.html','/all','/beijing.html','/beijing','/guangzhou.html','/guangzhou','/shenzhen.html','/shenzhen','/shanghai.html','/shanghai','/bohai_rim.html','/bohai_rim','/yangtze_river_delta.html','/yangtze_river_delta','/pan_pearl_river_delta.html','/pan_pearl_river_delta','/other_areas.html','/other_areas','/tianjin.html','/tianjin','/dalian.html','/dalian','/qingdao.html','/qingdao','/shenyang.html','/shenyang','/suzhou.html','/suzhou','/hangzhou.html','/hangzhou','/nanjing1.html','/nanjing1','/ningbo1.html','/ningbo1','/yiwu.html','/yiwu','/kunming.html','/kunming','/nanning.html','/nanning','/wuhan.html','/wuhan','/xi_an.html','/xi_an','/chengdu.html','/chengdu');
        $arr2=array('/cid/3','/cid/2','/cid/1','/cid/4','/cid/0','/cid/1001','/cid/1002','/cid/1003','/cid/1004','/1.html','/0.html','/2.html','/3.html','/4.html','/1001.html','/1002.html','/1003.html','/1004.html','.html','/index.php?s=','/Public/select_city');
        $arr=array_merge($arr1,$arr2);
        $url=str_replace($arr,'',$_SERVER["REQUEST_URI"]);
        $url=$url=='/'?'/Index/index':$url;
        $url=preg_replace("/\/to\/(.*)/i","",$url);
        $url=myencode($url);
        $this->assign('tourl',$url);

        if($this->_is_admin()){
        	$this->assign('admin',true);
        	$_SESSION['iicc']='1';
        }else{
        	$_SESSION['iicc']='0';
        }
        //$this->assign("ctls",array(1=>'Guangzhou',2=>'Beijing',3=>'Shanghai',4=>'Shenzhen',));//1001=>'Bohai Rim',1002=>'Yangtze River Delta',1003=>'Pan Pearl River Delta',1004=>'Other areas'));
        $this->assign("ctls",$this->cityname);
    }

    protected function _is_admin() {
		//检查权限
		if (in_array($this->user['username'],$this->admin,true)) {
			$re=true;
		}else{
			$re=false;
		}
		return $re;
	}//end is_admin
    function ads($ch,$wz,$classfileds='0',$cat='0'){
        $ad=array();
        $ad['right']='';
        $ads=M("Ad");
        $condition=array();
        if($classfileds=='1'){
            if($ch=='3'){
                $condition['model']=array('EQ','3');
            }else{
                $condition['model']=array(array('EQ','3'),array('EQ',$ch),'or');
            }
        }else{
            $condition['model']=array('EQ',$ch);
        }
        $condition['wz']=$wz;
        $condition['is_show']='1';
        $condition['begintime']=array('lt',time());
        $condition['endtime']=array('gt',time());
        if($this->cid=='0'){
            $condition['_string']="FIND_IN_SET('".$this->cid."',`cid`) > 0 ";
        }else{
            $condition['_string']="FIND_IN_SET('".$this->cid."',`cid`) > 0 ";
        }
        if($cat!='0'){
        	$type=D("Arctype");
        	$cinfo=$type->where("id=$cat")->find();
        	if($cinfo['reid']!=$cinfo['topid']){
        		$cat=$cinfo['reid'];
        	}
        	if(empty($condition['_string'])){
        		$condition['_string']="((FIND_IN_SET('".$cat."',`cat`) > 0) or cat='')";
        	}else{
        		$condition['_string'].="AND ((FIND_IN_SET('".$cat."',`cat`) > 0) or cat='')";
        	}
        }
        $adlist=$ads->where($condition)->findAll();
        /*if($this->_is_admin()){
        	dump($ads->getLastSql());
        	dump($this->cid);
        }*/
        shuffle($adlist);
        $x=0;
        foreach($adlist as $adl){
            $x++;
            if($this->cid=='0'&&$x>9){
                break;
            }
            if($adl['type']=='banner'){
                if(empty($adl['adcode'])){
                    $ad['banner'][]='<a href="'.jump($adl['adlink'],$adl['id']).'" target="'.$adl['isclose'].'" alt="'.$adl['title'].'"><img src="'.$adl['picurl'].'" /></a>';
                }else{
                    $ad['banner'][]=$adl['adcode'];
                }
            }elseif($adl['type']=='right'){
                if(empty($adl['adcode'])){
                    $ad['right'].='<a href="'.jump($adl['adlink'],$adl['id']).'" target="'.$adl['isclose'].'" alt="'.$adl['title'].'"><img src="'.$adl['picurl'].'" /></a><br>';
                }else{
                    $ad['right'].=$adl['adcode'];
                }
            }else{
           		if(empty($adl['adcode'])){
                    $ad['left'].='<a href="'.jump($adl['adlink'],$adl['id']).'" target="'.$adl['isclose'].'" alt="'.$adl['title'].'"><img src="'.$adl['picurl'].'" /></a><br>';
                }else{
                    $ad['left'].=$adl['adcode'];
                }
            }
        }
        /*if($this->user['username']=='iicc'){
        	dump($ads->getLastSql());
        	dump($ad);
        }*/
        $this->assign('ad',$ad);
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
	    	S($name,$data,60*60);
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
    	$id=explode('_',$_REQUEST['id']);
    	$tid=empty($tid)?$id['2']:$tid;
    	$types=empty($types)?$id['1']:$types;
    	$listid=empty($_REQUEST['listid'])?0:$_REQUEST['tid'];
    	if (!$this->_is_login()){
			$this->ajaxReturn(0,'Log in please.',0);
		}else{
	    	$dao=D("UserCollection");
	    	$condition=array();
	    	$condition['uid']=$this->user['uid'];
	    	$condition['tid']=$tid;
	    	$condition['types']=$types;
	    	$info=$dao->where($condition)->findAll();
	    	if ($info) {
	    		$this->ajaxReturn(0,'You’ve already collected as your favorite.',0);
	    	}else{
	    		$data=array();
	    		$data['uid']=$this->user['uid'];
	    		$data['tid']=$tid;
	    		$data['types']=$types;
	    		if(!intval($data['types'])||empty($data['tid'])){
	    			$this->ajaxReturn(0,'You’ve failed to collect it as your favorite.',0);
	    		}
	    		$data['listid']=$listid;
	    		$data['username']=get_username();
	    		$data['ctime']=time();
	    		$data['is_show']=0;
	    		$id=$dao->add($data);
	    		if ($id) {
	    			$this->ajaxReturn($id,'You’ve collected it as your favorite successfully.',1);
	    		}else{
	    			$this->ajaxReturn(0,'You’ve failed to collect it as your favorite.',0);
	    		}
	    	}
		}
    	
    }//end _user_collection
    
    /**
     *获取指定类的信息
     *@date 2010-5-20
     *@time 上午09:26:44
     */
    protected function _get_carc($typeid,$limit="0,10",$cid=0,$uid) {
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
    		if($cid<1000){
    			$condition['cid']=$cid;
    		}
    	}
    	if($uid){
    		$condition['uid']=$uid;
    	}
    	$condition['ismake']='1';
    	$info=$dao->where($condition)->order("pubdate DESC")->limit($limit)->findAll();
    	//dump($info);
    	unset($dao);
    	return $info;
    }//end _get_cart
    
    /**
     *设置城市
     *@date 2010-5-4
     *@time 上午09:48:13
     */
   protected function _set_cid() {
    	//设置城市
    	//$this->cid=1;
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
       *删除用户评论
       *@date 2010-7-20
       *@time 上午11:02:32
       */
    public function ajax_user_collection_remove($id='') {
    	//删除用户评论
    	$id=empty($id)?Input::getVar($_REQUEST['id']):$id;
    	$dao=D("UserCollection");
    	$condition=array();
    	$condition['id']=$id;
    	$dao->where($condition)->delete();
    	$this->success("Remove is success");
    }//end function_name
    
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
		$p->config=array('header'=>'','prev'=>'<','next'=>'>','first'=>'«','last'=>'»','theme'=>'%first% %upPage%  %prePage%  %linkPage%  %nextPage% %downPage% %end%');
		$page=$p->ajaxshow();//显示分页
		$this->assign("showpage_bot",$page);//显示分页
		$limit=$p->firstRow.",".$p->listRows;//设定分面的大小
		$limit=($limit==",")?'':$limit;//分页的大小
    	$data=$dao->where($condition)->limit($limit)->order('dateline DESC')->findAll();
    	$this->assign('comments',$data);
		$this->display("Common:ajax_comments");
    }//end ajax_comments
    
    /**
     *分享到群组收藏夹
     *@date 2010-6-28
     *@time 下午03:04:23
     */
	public function share() {
    	//分享到群组收藏夹
		if (!$this->_is_login()){
			$url=myencode($_SERVER["REQUEST_URI"]);
			$this->assign("jumpUrl","/Public/login/to/$url");
			$this->error('Log in please.');
		}else{
			$act=Input::getVar($_POST['act']);
			if($act!="share"){
				$this->ajax_group();
			}else{
				$gid=Input::getVar($_POST['gid']);
				$xid=Input::getVar($_POST['xid']);
				$types=Input::getVar($_POST['xtype']);
				$dao=D("MtagCollection");
				foreach ($gid as $g){
					$data=array();
					$return='';
					$msg='';
					$data['gid']=$g;
					$data['xid']=$xid;
					$data['types']=$types;
					$data['listid ']=0;
					$data=$dao->create($data);
					$return=$dao->add($data);
					
					if(empty($return)){
						$msg.='"'.get_info($g).'"  Your sharing is failed.<br>';
					}else{
						$msg.='"'.get_info($g).'"  You’ve shared successfully.<br>';
					}
				}
				$this->success($msg);
			}
		}
    }//end share
    
    /**
     *显示我的群组
     *@date 2010-6-28
     *@time 下午03:38:35
     */
    public function ajax_group() {
    	//显示我的群组
    	if (!$this->_is_login()){
			$this->error("Log On Please");
		}else{
			$id=explode('_',$_REQUEST['id']);
			$xid=$_REQUEST['xid'];
			$xtype=$_REQUEST['xtype'];
			$this->assign('xid',$xid);
			$this->assign('xtype',$xtype);
	    	$dao=D("Tagspace");
	    	$condition=array();
	    	$condition['uid']=$this->user['uid'];
	    	$count=$dao->where($condition)->count();
	    	if($count!='0'){
		    	import("ORG.Util.Page");//引用分页类
				import("@.Com.ajaxpage");//引用ajax分页类
				$p= new ajaxpage($count,10);
				$page=$p->ajaxshow();//显示分页
				$this->assign("showpage_bot",$page);//显示分页
				$limit=$p->firstRow.",".$p->listRows;//设定分面的大小
				$limit=($limit==",")?'':$limit;//分页的大小
		    	$data=$dao->where($condition)->order("ctime DESC")->limit($limit)->findAll();
		    	$info=array();
		    	foreach ($data as $v){
		    		$info[$v['tagid']]=get_info($v['tagid'],'*');
		    	}
		    	//dump($info);
		    	$this->assign("info",$info);
		    	$this->display("Common:ajax_group");
	    	}else{
	    		$this->error("You need to join a group first.");
	    	}
    	}
    }//end my_group
    
    /**
       *用户举报
       *@date 2010-7-24
       *@time 下午03:28:15
       */
    function report() {
    	//用户举报
    	$ajax=Input::getVar($_REQUEST['ajax']);
    	if($ajax=='1'){
    		$dao=D("Report");
    		$data=array();    		
    		$data['tid']=Input::getVar($_REQUEST['tid']);
    		$data['itype']=Input::getVar($_REQUEST['itype']);
    		$data['reason']=Input::getVar($_REQUEST['reason']);
    		$vo=$dao->create($data);
    		$reid=$dao->add($vo);
    		if($reid){
    			$this->ajaxReturn($reid,"You’ve sent successfully.",1);
    		}else{
    			$this->ajaxReturn(0,"Failure! Try it again!",0);
    		}
    	}else{
    		$page = array();
	        $page['title'] = 'Report Abuse  -  BeingfunChina 缤纷中国';
	        $page['keywords'] ="Report,Abuse";
	        $page['description'] ="Report Abuse in BeingfunChina" ;
	        $this->assign('page', $page);
    		$this->display();
    	}
    }//end report
    
    /**
       *接收整站举报信息
       *@date 2010-7-24
       *@time 下午03:46:14
       */
    function report_add() {
    	//接收整站举报信息
    	if (empty($_REQUEST['verify']) || md5($_REQUEST['verify'])!=$_SESSION['verify']){
			$this->error('Code Error!');
		}
    	$dao=D("Report");
    	$data=array();
    	$data['subject']=Input::getVar($_REQUEST['subject']);
    	$name='What is your name and BeingFunChina ID?'.Input::getVar($_REQUEST['name']).'<br>';
    	$id=$name.Input::getVar($_REQUEST['id']).'<br>';
    	$reid='Who are you reporting?'.Input::getVar($_REQUEST['reid']).'<br>';
    	$email='What is your email address?'.Input::getVar($_REQUEST['email']).'<br>';
    	$data['reason']=Input::getVar($_REQUEST['reason']);
    	$data['reason']=$id.$email.$reid.nl2br($data['reason']);
    	$vo=$dao->create($data);
    	$rid=$dao->add($vo);
    	$this->assign("jumpUrl",'/Common/report');
    	if($rid){
    		$this->success("You’ve sent successfully.");
    	}else{
    		$this->error("Failure! Try it again!");
    	}
    }//end report_add
    
    /**
       *发布留言
       *@date 2010-7-24
       *@time 下午05:00:07
       */
    function feedback() {
    	//发布留言
    	$page = array();
        $page['title'] = 'We appreciate your feedback  -  BeingfunChina 缤纷中国';
        $page['keywords'] ="appreciate,feedback";
        $page['description'] ="We appreciate your feedback in BeingfunChina" ;
        $this->assign('page', $page);
    	$this->display();
    }//end feedback
    
    /**
       *添加留言
       *@date 2010-7-24
       *@time 下午05:00:28
       */
    function feedback_add() {
    	//添加留言
    	if (empty($_REQUEST['verify']) || md5($_REQUEST['verify'])!=$_SESSION['verify']){
			$this->error('Code Error!');
		}
    	$dao=D("Feedback");
    	$vo=$dao->create();
    	if($vo){
    		$feedid=$dao->add($vo);
    		$this->assign("jumpUrl",'/Common/feedback');
    		if($feedid){
    			$this->success("You’ve sent successfully.");
    		}else{
    			$this->error("Failure! Try it again!");
    		}
    	}else{
    		$this->error("Failure! Try it again!");
    	}
    }//end feedback_add
    
    /**
     *获取当前栏目位置
     *@date 2010-5-10
     *@time 上午09:47:29
     */
    protected function _get_dh($typeid) {
    	//获取当前位置
    	$dao=D("Arctype");
    	$data=$dao->where("id=$typeid")->field('id,typename,seotitle,reid,topid')->find();
    	if ($data['reid']!=$data['topid']){
    		$data['_reid']=$dao->where("id={$data['reid']}")->field('id,seotitle,typename,reid,topid')->find();
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
    
///////////////////////////////////////关键字操作 start/////////////////////////////////////////////
	
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
    
    /**
     *标签整理
     *@date 2010-6-30
     *@time 上午11:04:46
     */
    public function tag($tname,$pid,$type) {
    	//标签整理
		$tname=empty($tname)?$_REQUEST['name']:$tname;
		$pid=empty($pid)?$_REQUEST['pid']:$pid;
		$type=empty($type)?$_REQUEST['type']:$type;
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
			echo "Wrong parameter!";
		}
	}// END tag
	
	/**
	 *关键字整理
	 *@date 2010-6-30
	 *@time 上午11:54:11
	 */
	function tags_rehash() {
		//关键字整理
		$model=new Model();
		$model->execute("TRUNCATE TABLE `iic_tags`");//清空标签信息表
		$model->execute("TRUNCATE TABLE `iic_tags_link`");//清空标签链接
		unset($model);
		
		$dao=D("Archives");
		import("ORG.Util.Page");
		$count=$dao->where("keywords<>''")->field("id,keywords")->count();
		$page=new Page($count,100);
		$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage',$page->show());
		$page->config=array('header'=>'','prev'=>'<','next'=>'>','first'=>'«','last'=>'»','theme'=>' %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
		$this->assign('showpage_bot',$page->show_img());
		$limit=$page->firstRow.','.$page->listRows;
		$ktag=$dao->wherewhere("keywords<>''")->field("id,channel,keywords")->limit($limit)->findAll();
		
		$tags=D("Tags");
		foreach ($ktag as $v){//循环读取关键字 作标签写入
			$karr=array();
			$karr=explode(',',$v['keywords']);
			if(count($karr)>0){
				foreach ($karr as $kv){
					$tags_info=array();
					$tags_info=$tags->where("tagsname={$kv}")->find();
					if($tags_info){//标签已经存在
						
					}else{//标签没建立
						$this->add_tags($kv,$v['channel']);
					}
				}
			}//关键字不为空
			
		}
	}//end tags_rehash
	
	/**
	 *写入关键字
	 *@date 2010-6-30
	 *@time 上午11:54:52
	 */
	protected function add_tags($tagsname,$type,$tcatid=0) {
		//写入关键字
		$tags=new Model();
		$t_field='type'.$type;
		$now=time();
		$sql="INSERT INTO `iic_tags` (`tagsname`, `$t_field`, `dateline`) VALUES ('$tagsname', '1', '$now') ON DUPLICATE KEY UPDATE `$t_field`=`$t_field`+1;";
		return $tags->execute($sql);
	}//end add_tags
	
	
	
///////////////////////////////////////关键字操作 end/////////////////////////////////////////////
	
    /**
     * 检查用户是否登录并获取用户信息
     */
    protected function _is_login($u='',$p='') {
    	
    	if (isset($_SESSION['uid']) && isset($_SESSION['username'])) {
    		$user=array('uid'=>$_SESSION['uid'],'username'=>$_SESSION['username'],'cid'=>$_SESSION['cid']);
    	}else{
	    	if($u&&$p){
	    		$dao=D("Members");
				$info=array();
				$info=$dao->where("username='$u'")->find();
				if ($info){
					if(md5($p)==$info['password']) {
						unset($_SESSION['uid']);
						unset($_SESSION['username']);
						unset($_SESSION['info']);
						
						$_SESSION['uid']=$info['id'];
						$_SESSION['username']=$info['username'];
						$info['avatar']=avatar($info['avatar']);
						$_SESSION["info"]=$info;
						$user=array('uid'=>$_SESSION['uid'],'username'=>$_SESSION['username'],'cid'=>$_SESSION['cid']);
					}else $user=false;
	    		}else $user=false;
	    	}else{
	    		$user=false;
	    	}
    	}
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
	 *时间日志
	 *@date 2010-6-25
	 *@time 上午11:31:28
	 */
	protected function _act_log($xid,$stype,$act,$sql) {
		//时间日志
		$dao=D("Action");
		$data=array();
		$data['mon']=mktime(0, 0, 0, date('n'),1);
		$data['xid']=$xid;
		$data['stype']=$stype;
		$data['act']=$act;
		$data['sql']=$sql;
		$vo=$dao->create($data);
		if ($vo){
			$id=$dao->add($vo);
			if($id){
				$return=true;
			}else{
				$return=false;
			}
		}else{
			$return=false;
		}
		return $return;
	}//end _act_log
	
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
	
	protected function _get_tree($topid,$fld='*') {
		$dao=new Model();
		$list=$dao->query("SELECT $fld FROM iic_arctype where topid=$topid AND ishidden=0 ORDER BY id ASC");
		$news=list_to_tree($list,'id','reid','_son',$topid);
		unset($dao);
		return $news;
	}//end tree
	
	protected function _get_flag(){
		$dao=M("Arcatt");
		return $dao->findAll();
	}
	
	protected function _new_list($typeid='',$flag='',$limit="0,10",$cid='0',$ch=''){
    	$dao=D("Archives");
    	$condition=array();
    	if($typeid!=''){
    		$condition['typeid']=$typeid;
    	}elseif ($ch!=''){
    		$condition['channel']=array('in',$ch);
    	}else{
    		$time=time();
			$condition['showstart']=array('lt',$time);
			$condition['showend']=array('gt',$time);
    	}
    	$condition['ismake']=1;
    	
		
    	if($flag){
    		$arr=explode(',',$flag);
    		foreach ($arr as $v){
    			if($v=='p'){
    				$condition['_string']="picurl<>''";
    			}else{
    				$condition['_string']="FIND_IN_SET('$v',`flag`) > 0";
    			}
    		}
    	}
    	if($cid!='0'){
    		if($cid<1000){
    			if($condition['_string']){
    				$condition['_string'].=" and (cid={$cid} or cid=0 or cid is null)";
    			}else{
    				$condition['_string']="cid={$cid} or cid=0 or cid is null";
    			}
    		}else{
	    		$city_temp=array('name'=>'','id'=>'');
				foreach ($this->cgroup[$this->pcid]['city'] as $k=>$v){
					$city_temp['id'].=$k.',';
					$city_temp['name'].=$v.',';
				}
				if($condition['_string']){
					$condition['_string'].="AND (cid in ({$city_temp}) or cid=0 or cid is null)";
				}else{
					$condition['_string']="cid in ({$city_temp}) or cid=0 or cid is null";
				}
    		}
    	}
    	if($limit=='0,1'||$limit=='1'){
    		$data=$dao->where($condition)->order('edittime DESC, pubdate DESC')->limit($limit)->find();
    	}else{
    		$data=$dao->where($condition)->order('edittime DESC, pubdate DESC')->limit($limit)->findAll();
    	}
    	/*if($this->user['username']=='iicc'){
    		dump($dao->getLastSql());
    	}*/
    	return $data;
    }
    
/////////////////////////////////上传图片部分 start/////////////////////////////////
    protected function _upload($tid,$width='120',$height='140',$y=0){
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
		$upload->thumbMaxWidth =  $width;
       //设置缩略图最大高度
		$upload->thumbMaxHeight = $height;
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
            if($y!=0){
            	$_POST['picurl']  = $path.$uploadList[0]['savename'];
            }else{
            	$_POST['picurl']  = $path.'s_'.$uploadList[0]['savename'];
            }
        }
        
        return $_POST['picurl'];
	}
	
	protected function _group_up($width='120',$height='140',$thumb=true,$d="/Public/Uploads/Group/"){
        import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize  = 3292200 ;
        //设置上传文件类型
        $upload->allowExts  = explode(',','jpg,gif,png,jpeg');
        //设置附件上传目录
        $path=date('Y-m').'/';
        $upload->savePath =  '.'.$d.$path;
        mk_dir($upload->savePath);
        $path=$d.$path;
	    //设置需要生成缩略图，仅对图像文件有效
       $upload->thumb =$thumb;
       //设置需要生成缩略图的文件后缀
	    $upload->thumbPrefix   =  's_';  //生产1张缩略图
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
            if($thumb){
            	$_POST['pic']  = $path.'s_'.$uploadList[0]['savename'];
            }else{
            	$_POST['pic']  = $path.$uploadList[0]['savename'];
            }
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
        $path=$tid.'/'.date('Y-m').'/';
        $upload->savePath =  './Public/album/'.$path;
        mk_dir($upload->savePath);
        $path='/Public/album/'.$path;
	    //设置需要生成缩略图，仅对图像文件有效
       $upload->thumb =  true;
       //设置需要生成缩略图的文件后缀
	    $upload->thumbPrefix   =  'm_,s_';  //生产1张缩略图
       //设置缩略图最大宽度
		$upload->thumbMaxWidth =  '650,120';
       //设置缩略图最大高度
		$upload->thumbMaxHeight = '550,120';
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
	
    protected function _avatar($uid=''){

        import("ORG.Net.UploadFile");
        $upload = new UploadFile();
        //设置上传文件大小
        $upload->maxSize  = 3292200 ;
        //设置上传文件类型
        $upload->allowExts  = explode(',','jpg,gif,png,jpeg');
        //设置附件上传目录
        $name=empty($uid)?$this->user['uid']:$uid;
        $upload->savePath =  './Public/avatar/';
        mk_dir($upload->savePath);
	    //设置需要生成缩略图，仅对图像文件有效
       $upload->thumb =  true;
       $upload->thumbPrefix =  's_';

       //设置缩略图最大宽度
		$upload->thumbMaxWidth =  '120';
       //设置缩略图最大高度
		$upload->thumbMaxHeight = '105';
	   //设置上传文件规则
	   $upload->saveRule ='get_uid';
	   //删除原图
	   $upload->thumbRemoveOrigin = true;
        if(!$upload->upload()) {
            //捕获上传异常
            $this->error($upload->getErrorMsg());
        }else {
            //取得成功上传的文件信息
            $uploadList = $upload->getUploadFileInfo();
        }
        
        return $uploadList;
	}
/////////////////////////////////上传图片部分 end/////////////////////////////////

	/**
	 *发布评论
	 *@date 2010-5-24
	 *@time 上午09:34:27
	 */
	public function post_comment() {
		//发布评论
		if (empty($this->user['uid'])) {
			$this->ajaxReturn(0,'Log in Please!',0);
		}
		if (empty($_REQUEST['verify']) || md5($_REQUEST['verify'])!=$_SESSION['verify']){
			$this->ajaxReturn(0,'Code Error!',0);
		}
		$dao=D("Comment");
		$vo=$dao->create($_REQUEST);
		if($vo){
			$vo['uid']=$this->user['uid'];
			$id=$dao->add($vo);
			if ($id) {
				unset($_SESSION['verify']);
				$this->ajaxReturn($id,'Comment released.',1);
				
			}else{
				$this->ajaxReturn(0,'Comment failed.',0);
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
	
	/**
   *等记群组关系
   *@date 2010-6-7
   *@time 下午09:15:29
   */
	protected function _add_tagspace($gid,$grade,$uid='',$username='') {
		//等记群组关系
		$dao=new Model();
		$ctime=time();
		$uid=$uid==''?$this->user['uid']:$uid;
		$username=$username==''?$this->user['username']:$username;
		$sql="INSERT INTO `iic_tagspace` (`id`,`tagid`,`uid`,`username`,`grade`,`ctime`)VALUES (NULL , '$gid', '$uid', '$username', '$grade', '$ctime') ON DUPLICATE KEY UPDATE `grade`='$grade';";
		return $dao->execute($sql);
	}//end _add_tagspace
	
	/**
	*获取群组
	*@date Sat Jun 05 16:48:56 CST 2010
	*@time 16:48:56
	*/
	protected function _get_group($mode="",$limit="0,10"){
		//获取热点群组
		$dao=D("Group");
		if ($mode=='hot') {
			$str="threadnum>0";
			$order="postnum,lasttime DESC";
		}elseif($mode=="new") {
			$str="";
			$order="ctime DESC";
		}
		$data=$dao->where($str)->order($order)->limit($limit)->findAll();
		//dump($dao->getlastSql());
		return $data;
	}//end _get_group
	
	/**
	 *获取群组内的成员
	 *@date 2010-6-23
	 *@time 下午02:55:18
	 */
	protected function get_gmember($gid,$grade='',$limit='0,9') {
		//获取群组内的成员
		$dao=D("Tagspace");
		$condition=array();
		$condition['tagid']=$gid;
		if(!empty($grade)){
			$condition['grade']='3';
		}
		return $dao->where($condition)->order("ctime DESC")->limit($limit)->findAll();
		
	}//end get_gmember
	
	/**
	   *成员常来的群组
	   *@date 2010-8-18
	   *@time 下午03:10:03
	   */
	function member_group($marr,$limit="0,10") {
		//成员常来的群组
		$condition=array();
		if(is_array($marr)){
			$str='';
			foreach ($marr as $v){
				$str.=$v['uid'].',';
			}
			$str=trim($str,',');
		}else{
			$str=$marr;
		}
		$dao=D("Tagspace");
		$condition['_string']="uid in($str)";
		$glist=$dao->where($condition)->group("tagid")->order("ctime DESC")->limit($limit)->findAll();
		$return=array();
		if(count($glist)>0){
			$group=D("Group");
			foreach ($glist as $v){
				$return[]=$group->where("id={$v['tagid']}")->find();
			}
		}
		return $return;
	}//end member_group
	
///////////////////////////////群组的共用类————end//////////////////////////
	
	/**
	   *一个话题内的成员
	   *@date 2010-8-19
	   *@time 下午05:58:23
	   */
	function member_thread($topid,$field="uid,username",$limit="0,10") {
		//一个话题内的成员
		$dao=D("Post");
		$condition=array("topid"=>$topid);
		return $dao->where($condition)->field($field)->group("uid")->order("dateline DESC")->limit($limit)->findAll();
	}//end function_name
	
	/**
	   *获取参与评论的成员
	   *@date 2010-8-19
	   *@time 下午05:16:13
	   */
	function member_comments($aid,$type,$field='*',$limit="0,10") {
		//获取参与评论的成员
		$dao=D("Comment");
		$condition=array();
		$condition['xid']=$aid;
		$condition['types']=$type;
		return $dao->where($condition)->field($field)->group("uid")->order("dateline DESC")->limit($limit)->findAll();
	}//end member_comments
	
	/**
	 *搜索
	 *@date 2010-6-30
	 *@time 上午11:32:17
	 */
	public function so() {
		//搜索
		
		$key=Input::getVar($_GET['key']);
		if($key){
			$this->_so_key($key);
			$ch=Input::getVar($_GET['channel']);
			$pnm=Input::getVar($_GET['pnm']);
			$cid=Input::getVar($_GET['cid']);
			$field=Input::getVar($_GET['field']);
			$order=Input::getVar($_GET['order']);
			$limit_arr=array('a'=>'10','b'=>'20','c'=>'30','d'=>'50');
			$order_arr=array('a'=>'id','b'=>'pubdate','c'=>'showend','d'=>'cid');
			$desc_arr=array('a'=>" DESC",'b'=>" ASC");
			$channel=array('cityguide'=>'2','classifieds'=>'4,5,6,7,8,9','event'=>'10','biz'=>'11','art'=>"12");
			//$enkey=Input::getVar($_GET['enkey']);
			
			//条件组合
			$condition=array();
			$condition['ismake']="1";
			/*if(empty($key)&&!empty($enkey)){
				$enkey=mydecode($enkey);
				$enkey=explode('|-|', $enkey);
			}*/
			$condition['title']=array("like","%{$key}%");
			if(!empty($ch)&&array_key_exists($ch,$channel)){
				if($ch=='classifieds'){
					$condition['channel']=array("in",$channel[$ch]);
				}else{
					$condition['channel']=$channel[$ch];
				}
			}
			
			if($cid){
				if($cid<1000){
					$condition['cid']=$cid;
				}else{
					$city_temp=array('name'=>'','id'=>'');
					foreach ($this->cgroup[$this->pcid]['city'] as $k=>$v){
						$city_temp['id'].=$k.',';
						$city_temp['name'].=$v.',';
					}
					$condition['cid']=array('in',$city_temp['id']);
				}
			}
			
			//结果查询
			$dao=D("Archives");
			$cou=$dao->where($condition)->count();
			import("ORG.Util.Page");
			$pnm=array_key_exists($pnm, $limit_arr)?$limit_arr[$pnm]:20;
			$page=new Page($cou,$pnm);
			$page->config=array('header'=>'Rows','prev'=>'Previous','next'=>'Next','first'=>'«','last'=>'»','theme'=>' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
			$this->assign('showpage',$page->show());
			$page->config=array('header'=>'','prev'=>'<','next'=>'>','first'=>'«','last'=>'»','theme'=>'  %first% %upPage%  %prePage%  %linkPage%  %nextPage% %downPage% %end%');
			$this->assign('showpage_bot',$page->show_img());
			$limit=$page->firstRow.','.$page->listRows;
			$field=array_key_exists($field, $order_arr)?$order_arr[$field]:'pubdate';
			$order=array_key_exists($order, $desc_arr)?$desc_arr[$order]:" DESC";
			$order=$field.$order;
			$data=$dao->where($condition)->order($order)->limit("$limit")->findAll();
			$this->assign("data",$data);
			
			//dump($dao->getLastSql());
			//结果综合统计
			$count=array();
			$count['all']=$cou;
			$condition['channel']='2';
			$count['cityguide']=$dao->where($condition)->count();
			$condition['channel']=array('in',$channel['classifieds']);
			$count['classifieds']=$dao->where($condition)->count();
			$condition['channel']='10';
			$count['event']=$dao->where($condition)->count();
			$condition['channel']='11';
			$count['biz']=$dao->where($condition)->count();
			$condition['channel']='12';
			$count['art']=$dao->where($condition)->count();
			
			$this->assign("count",$count);
			
		}
		$sokey=D("SoKey");
		$hotkey=$sokey->order("hot DESC")->limit("0,10")->findAll();
		$this->assign("hotkey",$hotkey);
		
		$page=array();
		$page['title']='Search '.$key.'  -  BeingfunChina 缤纷中国';
		$page['keywords']=$key;
		$page['description']='Search '.$key;
		$this->assign('page',$page);
		
		$this->assign('city_type',$this->_get_tree(1000));
		$this->assign('classifieds_type',$this->_get_tree(1));
		$this->display("Common:so");
	}//end so
	
	/**
	   *记录关键字
	   *@date 2010-10-29
	   *@time 上午10:45:24
	   */
	function _so_key($key) {
		//记录关键字
		$time=time();
		$sql="INSERT INTO `iic_so_key` (`id` ,`keyword` ,`ctime` ,`mtime` ,`hot`) VALUES (NULL , '$key', '$time', '$time', '0') ON DUPLICATE KEY UPDATE `mtime` = '$time', `hot`=`hot`+1;";
		$m=new Model();
		return $m->execute($sql);
	}//end _so_key
	
	/**
	   *高级搜索
	   *@date 2010-10-9
	   *@time 下午04:34:47
	   */
	function advso() {
		//高级搜索
		
		$this->assign('city_type',$this->_get_tree(1000));
		$this->assign('classifieds_type',$this->_get_tree(1));
		$this->display();
	}//end advso
	
	/**
	   *查询指定话题的相册图片组
	   *@date 2010-8-13
	   *@time 下午06:34:09
	   */
	protected function get_album($aid,$xtype) {
		//查询指定话题的相册图片组
		$dao=D("Pic");
		$condition=array();
		$condition['xid']=$aid;
		$condition['xtype']=$xtype;
		$condition['is_show']=1;
		$picarr=$dao->where($condition)->findAll();
		return $picarr;
	}//end get_album
	
///////////////////////////校验权限/////////////////////////
	/**
   *检查权限
   *@date 2010-7-16
   *@time 下午03:47:54
   */
	function _get_role() {
		//检查权限
		$uid=$this->user['uid'];
		$dao=new Model();
		$sql="SELECT r.id,r.name,r.remark FROM iic_role_user as ru LEFT JOIN iic_role as r ON ru.role_id=r.id WHERE ru.user_id={$uid};";
		return $dao->query($sql);
		//dump($dao->query($sql));
	}//end _r
	
	
	/**
	   *发送短消息
	   *@date 2010-9-29
	   *@time 下午05:41:19
	   */
	function pm() {
		//发送短消息
		if(!$this->_is_login()){
			$this->error("Login please.",$_REQUEST['is_ajax']);
		}
		if(Input::getVar($_REQUEST['fromusername'])&&empty($_REQUEST['fromuid'])){
			$member=D("Members");
			$condition=array('username'=>Input::getVar($_REQUEST['fromusername']));
			$info=$member->where($condition)->find();
			if($info['id']){
				$fromuid=$info['id'];
			}else{
				$this->error("Wrong user name!",$_REQUEST['is_ajax']);
			}
		}else{
			$fromuid=Input::getVar($_REQUEST['fromuid']);
		}
		$content=Input::getVar($_REQUEST['content']);
		$title=Input::getVar($_REQUEST['title']);
		if (!empty($fromuid)) {
			if(empty($content)){
				$this->error('You must fill in the field of "Content".',$_REQUEST['is_ajax']);
			}
			if(empty($title)){
				$this->error('You must fill in the field of "Title".',$_REQUEST['is_ajax']);
			}
			$data=array();
			$data['fromuid']=$fromuid;
			$data['content']=nl2br(remove_xss($content));
			$data['title']=$title;
			$data['itype']=Input::getVar($_REQUEST['itype']);
			$data['xid']=Input::getVar($_REQUEST['xid']);
			$dao=D("Pm");
			$vo=$dao->create($data);
			if($vo){
				$id=$dao->add($vo);
				if($id){
					$this->success('sucess.',$_REQUEST['is_ajax']);
				}else{
					$this->error('failure.',$_REQUEST['is_ajax']);
				}
			}else{
				$this->error($dao->getError(),$_REQUEST['is_ajax']);
			}
		}else{
			$this->error("Wrong parameter!");
		}
	}//end pm
	
	/**
	   *检查短信
	   *@date 2010-10-9
	   *@time 下午03:44:43
	   */
	protected function ckh_pm() {
		//检查短信
		$dao=D("Pm");
		$condition=array();
		$condition['fromuid']=$this->user['uid'];
		$arr=array();
		$all['all']=$dao->where($condition)->count();
		$condition['ifnew']='1';
		$all['new']=$dao->where($condition)->count();
		return $all;
	}//end ckh_pm
	
	
	/**
	   *获取上级分类的最新文章
	   *@date 2010-10-22
	   *@time 下午03:08:50
	   */
	function _get_uptype_arc($type_id,$limit="0,5",$cid) {
		//获取上级分类的最新文章
		$dao=D("Archives");
		$cat=D("Arctype");
		$type=$cat->where("id=$type_id")->find();
		$str='';
		if ($type['reid']!=$type['topid']){
			$small=$cat->where("reid={$type['reid']}")->field("id")->findAll();
			if($small){
				foreach ($small as $v){
					$str.=$v['id'].',';
				}
				$str='typeid IN ('.trim($str,',').')';
			}else{
				$str="typeid={$type_id}";
			}
    	}else{
    		$str="typeid={$type_id}";
    	}
    	if($cid){
    		$str.=$cid;
    	}
    	$data=$dao->where($str.' AND ismake=1')->order("edittime DESC, pubdate DESC")->limit($limit)->findAll();
    	return $data;
	}//end _get_uptype_arc
	
}//END CommonAction
?>