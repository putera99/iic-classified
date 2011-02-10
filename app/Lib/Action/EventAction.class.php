<?php

/**
  +------------------------------------------------------------------------------
 * EventAction控制器类
  +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  bi
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010年 05月 04日 星期二 10:51:37 CST
  +------------------------------------------------------------------------------
 */
class EventAction extends CommonAction {

    protected $pcid = '';
    /**
     * 活动频道首页
     * @date 2010年 05月 04日 星期二 10:56:27 CST
     */
    function index() {
        //活动频道首页
        $this->chk_cid();
        $kinfo='';//标题后面附加的城市后缀
		if($this->pcid){//检查城市或者城市群
			if($this->pcid > 1000){
				$kinfo=$this->cgroup[$this->pcid]['name'].' ';
				$this->assign('city_group',$this->cgroup[$this->pcid]['name']);
			}else{
				$kinfo=get_cityname($this->pcid).' ';
			}
			$this->assign("cityname",$kinfo);
		}//检查城市或者城市群
        $time = time();
        $dao = D("Archives");
        $condition = array();
        $condition['channel'] = '10';
        $condition['ismake'] = '1';
        if(!empty($this->pcid)&&$this->pcid!='0'&&$this->pcid<1000){
        	$condition['cid'] = $this->pcid;
        }
        $condition['showstart'] = array('lt', $time);
        $condition['showend'] = array('gt', $time);
        $list = $dao->where($condition)->order("showstart DESC")->limit("0,11")->findAll();
        //dump($dao->getLastSql());
        $this->assign('list', $list);
        $condition['showend'] = array('lt', $time);
        $old = $dao->where($condition)->order("showstart DESC")->limit("0,4")->findAll();
        $this->assign('old', $old);

        $this->assign('range_time', $this->range_time());
        $this->assign('class_tree', $this->get_class(2050));

        $new_activities = array();
        $new_activities = $this->new_event();
        $this->assign('new_activities', $new_activities);

        $group = $this->_get_group('new', "0,6");
        $this->assign('group', $group);

        $page = array();
        $page['title'] = $kinfo.' You can view, join and initiate events. There are various events like Night Life, Party and Community Activities.';
        $page['keywords'] = "Events,BeingfunChina,Night Life,Party,Community,Activities,$kinfo";
        $page['description'] = $kinfo." You can view, join and initiate events. There are various events like Night Life, Party and Community Activities.";
        $this->assign('page', $page);
        $this->ads('10', 'channel');
        $headline=self::attr_event("h",'0,1');
        $this->assign("headline",$headline);
        $this->display();
    }//end index

    /**
     * 活动列表页
     * @date 2010年 05月 04日 星期二 10:57:56 CST
     */
    function ls() {
        //活动列表页
        $this->chk_cid();
        if ($_GET['id']) {
            $_SESSION['typeid'] = Input::getVar($_GET['id']);
        }
        if ($_GET['type'] == 'all') {
            unset($_SESSION['typeid']);
        }
        
    	
        //$condition['cid'] = $this->pcid;
        $range_time=$this->range_time();
        $this->assign('range_time',$range_time);
        $this->assign('class_tree', $this->get_class(2050));
		
        //设置时间段筛选时间
    	if ($_GET['tk']) {
    		foreach ($range_time as $v){
	    		if($v['tk']==Input::getVar($_GET['tk'])){
					$_SESSION['sql']=$v['sql'];
					$_SESSION['tk']=$v['tk'];
					$_SESSION['tk_name']=$v['name'];
				}
    		}
        }
        
        if($_GET['clrt']=="ok"){
        	unset($_SESSION['sql']);
        	unset($_SESSION['tk']);
        	unset($_SESSION['tk_name']);
        }
        
        $time = time();
        $dao = D("Archives");
        $condition = array();
    	if(!empty($this->pcid)&&$this->pcid!='0'){
    		if($this->pcid < 1000){
        		$condition['cid']=$this->pcid;
    		}
        }
        $condition['channel'] = '10';
        $condition['ismake'] = '1';
        if ($_SESSION['sql']) {
        	$this->assign("tk",$_SESSION['tk']);
            //$condition['_string'] = "(`showstart`>='{$_SESSION['st']}' AND `showstart`<='{$_SESSION['et']}') OR (`showend`>='{$_SESSION['st']}' AND `showend`>='{$_SESSION['et']}')";
            $condition['_string'] = $_SESSION['sql'];
        }
        if ($_SESSION['typeid']) {
        	$this->assign("typeid",$_SESSION['typeid']);
            $condition['typeid'] = $_SESSION['typeid'];
        }

        $count = $dao->where($condition)->order("showstart DESC")->count();
        import("ORG.Util.Page");
        $page = new Page($count, 10);
        $page->config = array('header' => 'Rows', 'prev' => 'Previous', 'next' => 'Next', 'first' => '«', 'last' => '»', 'theme' => ' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
        $this->assign('showpage', $page->show());
        $page->config = array('header' => '', 'prev' => '<', 'next' => '>', 'first' => '«', 'last' => '»', 'theme' => '%first% %upPage%  %prePage%  %linkPage%  %nextPage% %downPage% %end%');
        $this->assign('showpage_bot', $page->show_img());
        $limit = $page->firstRow . ',' . $page->listRows;
        $data = $dao->where($condition)->order("showstart DESC")->limit($limit)->findAll();
        //dump($dao->getLastSql());
        $this->assign('data', $data);
        
        //获取类别信息
        $info=array();
        if($_SESSION['typeid']){
        	$arctype=D("Arctype");
        	$info=$arctype->where("id={$_SESSION['typeid']}")->find();
        	
        	$this->assign("info",$info);
        }else{
        	$info['typename']="All";
        	$this->assign("info",$info);
        }
        
        //导航
        $this->assign('dh', $this->_get_dh($_SESSION['typeid']));
		
        //页头信息
    	$cityinfo='';
		if($this->pcid){
			$cityinfo=get_cityname($this->pcid)?' in '.get_cityname($this->pcid):'';
		}
		
        $title="";
        
        $title.=empty($info['typename'])?'Events list':$info['typename'];
        $title.=empty($_SESSION['tk_name'])?'':','.$_SESSION['tk_name'];
        $title.=$cityinfo;
        $page = array();
        $page['title'] = empty($info['title']) ? $title.' - BeingfunChina 缤纷中国' :$title.' - BeingfunChina 缤纷中国';
        $page['keywords'] = empty($info['tags']) ? $title: $info['tags'];
        $page['description'] = empty($info['title']) ? "Events list in BeingfunChina" :$title;
        $this->assign('page', $page);

        $this->ads('10', 'list');//调用广告
        
    	if($this->_is_admin()){//检查管理权限
			$this->assign('admin',true);
		}
        $this->display();
    }

//end ls

    /**
     * 活动内容页
     * @date 2010-6-10
     * @time 下午03:28:48
     */
    function show() {
        //活动内容页
        $aid = Input::getVar($_REQUEST['aid']);
        $dao = D("Archives");
        $condition = array();
        $condition['channel'] = '10';
        $condition['ismake'] = '1';
        $condition['id'] = $aid;
        $info = $dao->where($condition)->find();
        $info['content'] = $dao->relationGet("event");
        $this->assign('info', $info);

        $this->assign('thought', $this->_user_thought($info['id'], $info['channel']));
        $this->assign('thought_list', $this->_thought_list($info['id'], $info['channel']));
        $this->assign('dh', $this->_get_dh($info['typeid']));

        $page = array();
        $page['title'] = empty($info['title']) ? 'Events  -  BeingfunChina 缤纷中国' : $info['title'] . ' - BeingfunChina 缤纷中国';
        $page['keywords'] = empty($info['tags']) ? "Events" : $info['tags'];
        $page['description'] = empty($info['title']) ? "Events in BeingfunChina" : $info['title'];
        $this->assign('page', $page);

        $condition = array();
        $condition['types'] = 10;
        $condition['tid'] = $info['id'];
        //hot 1感兴趣 2关注 3不关心
        $condition['hot'] = 1;
        $dao = D("Thought");
        $thought = $dao->where($condition)->order("ctime DESC")->limit("0,7")->findAll();
        $this->assign('thought', $thought);

        $post = D("Post");
        $condition = array();
        $condition['aid'] = $info['id'];
        $condition['l'] = '0';
        $condition['topid'] = '0';
        $condition['is_show'] = '1';

        $count = $post->where($condition)->count();
        import("ORG.Util.Page");
        $page = new Page($count, 10);
        $page->config = array('header' => 'Rows', 'prev' => 'Previous', 'next' => 'Next', 'first' => '«', 'last' => '»', 'theme' => ' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
        $this->assign('showpage', $page->show());
        $page->config = array('header' => '', 'prev' => '<', 'next' => '>', 'first' => '«', 'last' => '»', 'theme' => '%first% %upPage%  %prePage%  %linkPage%  %nextPage% %downPage% %end%');
        $this->assign('showpage_bot', $page->show_img());
        $limit = $page->firstRow . ',' . $page->listRows;

        $thread = $post->where($condition)->order("dateline DESC")->limit($limit)->findAll();
        $this->assign('thread', $thread);

        $this->assign('topics', $this->_new_thread($info['id'], "0,10", "hot"));
        $this->assign('interested', $this->group($info['id']));

        $this->ads('10', 'content');
        $this->display();
    }

//end show

    /**
     * 参与
     * @date 2010-6-18
     * @time 下午03:37:35
     */
    function thought() {
        //参与
        $id = Input::getVar($_REQUEST['id']);
        $info = $id;
        if (empty($id)) {
            $this->ajaxReturn('0', "Wrong parameter.", '0');
        } else {
            $id = explode('_', $id);
            $data = array();
            $data['types'] = $id['0'];
            $data['tid'] = $id['1'];
            $data['hot'] = $id['2'];
            $thought = D("Thought");
            $vo = $thought->create($data);
            if ($vo) {
                $newid = $thought->add($vo);
                if ($newid) {
                    $this->ajaxReturn($info, 'Selected successfully!', '1');
                } else {
                    $this->ajaxReturn('0', "You have selected one already.", '0');
                }
            } else {
                $this->ajaxReturn('0', "Your selecte is failed.", '0');
            }
        }
    }

//end thought

    /**
     * 发表话题
     * @date 2010-6-4
     * @time 下午04:36:18
     */
    function add_post() {
        //搜索群组
        if (!$this->_is_login()) {
            $this->ajaxReturn('login', "Login please.", '0');
        }
        $dao = D("Post");
        $vo = $dao->create();
        if ($vo) {
            if (empty($vo['message'])) {
                $this->ajaxReturn('0', 'You must fill in the field of "Content".', '0');
            }
            $vo['title'] = $vo['title'] ? $vo['title'] : "";
            $vo['aid'] = $vo['aid'] ? $vo['aid'] : "0";
            $vo['gid'] = "0";
            $vo['qid'] = $vo['qid'] ? $vo['qid'] : "0";
            $vo['l'] = $vo['l'] ? $vo['l'] : "1";
            $vo['topid'] = $vo['topid'] ? $vo['topid'] : "0";
            $vo['requery'] = $vo['requery'] ? $vo['requery'] : "0";
            $vo['qidstr'] = $vo['qidstr'] ? $vo['qidstr'] : "0";
            $vo['message'] = nl2br(remove_xss($vo['message']));
            $vo['is_show'] = 1;
            if ($vo['topid'] != '0' && $vo['qid'] == '0') {//主题的回复
                $top = $dao->where("topid={$vo['topid']}")->field('id,l,topid')->order("l DESC")->find();
                $vo['l'] = $top['l'] + 1;
            } elseif ($vo['topid'] == '0' && $vo['qid'] == '0') {//主题
                $vo['l'] = '0';
            } elseif ($vo['topid'] != '0' && $vo['qid'] != '0') {//主题回复的回复
                $top = $dao->where("qid={$vo['qid']}")->field('id,l,topid')->order("l DESC")->find();
                $vo['l'] = $top['l'] + 1;
            }
            //dump($vo);
            $pid = $dao->add($vo);
            if ($pid) {
                $data = $dao->where("id=$pid")->find();
                $data['dateline'] = toDate($data['dateline'], 'Y-m-d');
                $data['lasttime'] = toDate($data['lasttime'], 'Y-m-d');
                if ($vo['topid'] > 0) {
                    $dao->where("id={$vo['topid']}")->save(array("lasttime" => time()));
                }
                $this->ajaxReturn($data, "You’ve sent successfully.", '1');
            } else {
                $this->ajaxReturn('0', "You’ve sent successfully.", '0');
            }
        } else {
            $this->ajaxReturn('0', $dao->getDbError(), '0');
        }
    }

//end add_group

    /**
     * 话题页面
     * @date 2010-6-4
     * @time 下午03:57:52
     */
    function thread() {
        //话题页面
        $tid = empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
        $lou = empty($_REQUEST['lou']) ? 0 : intval($_REQUEST['lou']);
        $condition = array();
        if ($tid) {
            $condition['topid'] = $tid;
        }
        if (empty($condition)) {
            $this->error('Wrong parameter.');
        }
        $condition['requery'] = '0';
        $condition['qid'] = '0';
        $pn = 1000;
        $post = D("Post");
        $count = $post->where($condition)->count();
        import("ORG.Util.Page");
        $page = new Page($count, $pn);
        $page->config = array('header' => 'Rows', 'prev' => 'Previous', 'next' => 'Next', 'first' => '«', 'last' => '»', 'theme' => ' %nowPage%/%totalPage% %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
        $this->assign('showpage', $page->show());
        $page->config = array('header' => '', 'prev' => '<', 'next' => '>', 'first' => '«', 'last' => '»', 'theme' => '%first% %upPage%  %prePage%  %linkPage%  %nextPage% %downPage% %end%');
        $this->assign('showpage_bot', $page->show_img());
        $limit = $page->firstRow . ',' . $page->listRows;
        if ($lou) {
            $ls = $lou < $pn ? 1 : intval($lou / $pn) * $pn;
            $limit = $ls . ',' . $page->listRows;
        }
        $thread = $post->where($condition)->order("dateline DESC")->limit($limit)->findAll();
        $info = $post->where("id=$tid")->find();
        $arr = array();
        foreach ($thread as $t) {//获取回复
            $arr[$t['id']] = $t;
            $condition['requery'] = $t['id'];
            $arr[$t['id']]['_rarr'] = $post->where("requery={$t['id']} or qid={$t['id']}")->order("dateline DESC")->findAll();
            //dump($post->getLastSql());
        }
        $this->assign('thread', $arr);

        $this->assign('info', $info);

        $ginfo = get_info($info['aid'], '*', 'Archives');
        $this->assign('ginfo', $ginfo);
        $this->assign('dh', $this->_get_dh($ginfo['typeid']));

        $page = array();
        $page['title'] = empty($info['title']) ? 'Events Thread  -  BeingfunChina 缤纷中国' : $info['title'] . '  -  BeingfunChina 缤纷中国';
        $page['keywords'] = empty($info['tags']) ? "Events,Thread" : $info['tags'];
        $page['description'] = empty($info['title']) ? "Events in BeingfunChina" : $info['title'];
        $this->assign('page', $page);
        $this->display();
    }//end thread

    /**
     * 获取最新话题
     * @date 2010-8-17
     * @time 下午04:07:35
     */
    function _new_thread($aid, $limit="0,10", $mode="new") {
        //获取最新话题
        $post = D("Post");
        $condition = array();
        $condition['aid'] = $aid;
        $condition['is_show'] = '1';
        $condition['l'] = '0';
        if ($mode == "new") {
            $order = "dateline DESC";
        } elseif ($mode == "hot") {
            $order = "lasttime DESC";
        }
        $list = $post->where($condition)->order($order)->limit($limit)->findAll();
        return $list;
    }//end _new_thread

    /**
     * 生成时间段
     * @date 2010-6-13
     * @time 下午03:39:35
     */
    function range_time() {
        //生成时间段
        $t = time();
        $tarr = array();
        $day = 60 * 60 * 24;
        
        $tarr['1']['name'] = 'Today';//今天
        $tarr['1']['tk'] = 'today';
        $tarr['1']['st'] =mktime(0,0,1,date("n"),date("d"),date("Y"));
        $tarr['1']['et'] =mktime(23,59,59,date("n"),date("d"),date("Y"));
        $tarr['1']['sql']="`showstart`>={$tarr['1']['st']} AND `showstart`<={$tarr['1']['et']}";
        
        $tarr['7']['name'] = 'Next 7 Days';//下七天
        $tarr['7']['tk'] = 'seven_days';
        $tarr['7']['st'] =mktime(0,0,1,date("n"),date("d"),date("Y"));
        $tarr['7']['et'] =mktime(59,59,59,date("n"),date("d")+7,date("Y"));
        $tarr['7']['sql']="`showstart`>={$tarr['7']['st']} AND `showstart`<={$tarr['7']['et']}";
        
        $tarr['30']['name'] = 'Next 30 Days';//下三十天
        $tarr['30']['tk'] = 'thirty_days';
        $tarr['30']['st'] =mktime(0,0,1,date("m"),date("d"),date("Y"));
        $tarr['30']['et'] =mktime(59,59,59,date("m"),date("d")+30,date("Y"));
        $tarr['30']['sql']="`showstart`>={$tarr['30']['st']} AND `showstart`<={$tarr['30']['et']}";
        
        $tarr['next']['name'] = "What's next";//所有没有开始的活动
        $tarr['next']['tk']="whats_next";
        $tarr['next']['sql']="`showstart`>{$t}";
        
        
        $tarr['on']['name'] = "What's on";//正在举办的
        $tarr['on']['tk']="whats_on";
        $tarr['on']['sql']="`showstart`>={$t} AND `showend`<{$t}";
        
        $tarr['past7']['name'] = "The Past 7 Days";//过去七天
        $tarr['past7']['tk']="past_seven_days";
        $tarr['past7']['st'] =mktime(0,0,1,date("n"),date("d"),date("Y"));
        $tarr['past7']['et'] =mktime(0,0,1,date("n"),date("d")-7,date("Y"));
        $tarr['past7']['sql']="`showstart`>={$tarr['past7']['et']} AND `showstart`<={$tarr['past7']['st']}";
        
        $tarr['off']['name'] = "What's off";//所有的已经结束的
		$tarr['off']['tk']="whats_off";
		$tarr['off']['sql']="`showend`<{$t}";
        
        /*$tarr['7']['name'] = 'Last Week';
        $tarr['7']['tk'] = 'last_week';
        $tarr['7']['st'] = $t - ($day * 7);
        $tarr['7']['et'] = $t + ($day * 7);
        $tarr['30']['name'] = 'Last Month';
        $tarr['30']['tk'] = 'last_month';
        $tarr['30']['st'] = $t - ($day * 30);
        $tarr['30']['et'] = $t + ($day * 30);
        $tarr['90']['name'] = 'Recent Three Months';
        $tarr['90']['tk'] = 'recent_three_months';
        $tarr['90']['st'] = $t - ($day * 30 * 3);
        $tarr['90']['et'] = $t + ($day * 30 * 3);
        $tarr['365']['name'] = 'This Year';
        $tarr['365']['tk'] = 'this_year';
        $tarr['365']['st'] = mktime(0, 0, 0, 1, 1, date('Y'));
        $tarr['365']['et'] = mktime(0, 0, 0, 1, 1, date('Y') + 1) - 1;
        $tarr['all']['name'] = 'All Time';
        $tarr['all']['tk'] = 'all_time';
        $tarr['all']['st'] = 0;
        $tarr['all']['et'] = 0;*/
        
        
        
        return $tarr;
    }//end range_time

    /**
     * 获取分类
     * @date 2010-7-6
     * @time 下午03:48:19
     */
    protected function get_class($topid) {
        //获取分类
        $dao = D("Arctype");
        $list = $dao->where("topid=$topid AND ishidden=0")->order("id ASC")->findAll();
        unset($dao);
        return $list;
    }//end get_class

    
    /**
     * 检查城市选项
     * @date 2010-6-23
     * @time 上午10:17:39
     */
    protected function chk_cid() {
        //检查城市选项
        $cid = Input::getVar($_GET['cid']);
        if ($cid) {
            if ($_SESSION['cid']) {
            	$_SESSION['cid']=$cid;
                $this->pcid = $cid;
            } else {
                $_SESSION['cid'] = $cid;
                $this->pcid = $cid;
                cookie('cid', null);
                if ($_REQUEST['remember']) {
                    cookie('cid', $cid, array('expire' => 60 * 60 * 60 * 24 * 30));
                }
            }
        }else{
            //$this->_set_cid();
            $this->pcid = $this->cid;
        }
    }

//end chk_cid

    /*
     * 获取用户的关注度
     */

    function _user_thought($aid, $ch, $uid) {
        $uid = empty($uid) ? $this->user['uid'] : $uid;
        if (!empty($uid)) {
            $dao = D("Thought");
            $condition = array('uid' => $uid, 'tid' => $aid, 'types' => $ch);
            $data = $dao->where($condition)->find();
        } else {
            $data = 0;
        }
        return $data;
    }

    /*
     * 获取关注话题的成员
     */

    function _thought_list($aid, $ch, $limit="0,10") {
        $dao = D("Thought");
        $condition = array('tid' => $aid, 'types' => $ch);
        $data = $dao->where($condition)->order("ctime DESC")->limit($limit)->findAll();
        return $data;
    }

    /**
     * 获取参与话题的成员
     * @date 2010-8-18
     * @time 下午03:57:51
     */
    function _post_member($aid, $field="*", $limit="0,10") {
        //获取参与话题的成员
        $post = D("Post");
        $condition = array();
        $condition['aid'] = $aid;
        $condition['is_show'] = '1';
        $list = $post->where($condition)->group("uid")->limit($limit)->findAll();
        return $list;
    }

//end _post_member

    /**
     * 参与的成员常去的群组
     * @date 2010-8-19
     * @time 下午04:50:53
     */
    function group($aid) {
        //合并参与话题的成员与关注的成员
        $thought_member = $this->_thought_list($aid, 10, "");
        $post_member = $this->_post_member($aid, 'uid', "");
        $arr1 = array();
        foreach ($thought_member as $v) {
            $arr1[$v['uid']]['uid'] = $v['uid'];
        }
        $arr2 = array();
        foreach ($post_member as $v) {
            $arr1[$v['uid']]['uid'] = $v['uid'];
        }
        $arr = array_merge($arr1, $arr2);
        return $this->member_group($arr, "0,6");
    }

//end countmember

    /**
     * 获取最新活动
     * @date 2010-6-17
     * @time 上午09:51:38
     */
    protected function new_event() {
        //获取最新活动
        $time = time();
        $dao = D("Archives");
        $condition = array();
        $condition['channel'] = '10';
        $condition['ismake'] = '1';
        if ($this->pcid) {
            $condition['cid'] = $this->pcid;
        }
        $list = $dao->where($condition)->order("pubdate DESC")->limit("0,6")->findAll();
        return $list;
    }//end new_event
    
    	/**
	 *获取最新活动
	 *@date 2010-6-17
	 *@time 上午09:51:38
	 */
	protected function attr_event($fld='h',$limit="0,1") {
		//获取最新活动
		$time = time ();
		$dao = D ( "Archives" );
		$condition = array ();
		$condition ['channel'] = '10';
		$condition ['ismake'] = '1';
		/*$condition ['showstart'] = array ('lt', $time );
		$condition ['showend'] = array ('gt', $time );*/
		$condition['_string']="FIND_IN_SET('$fld',`flag`) > 0";
		if ($this->pcid) {
			$condition ['cid'] = $this->pcid;
		}
		if($limit=='0,1'){
			$list = $dao->where ( $condition )->order ( "edittime DESC,showstart DESC" )->limit ($limit)->find();
		}else{
			$list = $dao->where ( $condition )->order ( "edittime DESC,showstart DESC" )->limit ($limit)->findAll ();
		}
		return $list;
	} //end attr_event
	
}// END EventAction
?>
