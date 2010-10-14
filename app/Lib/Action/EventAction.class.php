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

    function _initialize() {
        parent::_initialize();
    }

//end _initialize()

    /**
     * 活动频道首页
     * @date 2010年 05月 04日 星期二 10:56:27 CST
     */
    function index() {
        //活动频道首页
        $this->chk_cid();
        $time = time();
        $dao = D("Archives");
        $condition = array();
        $condition['channel'] = '10';
        $condition['ismake'] = '1';
        if(!empty($this->pcid)||$this->pcid!='0'){
        	$condition['cid'] = $this->pcid;
        }
        $condition['showstart'] = array('lt', $time);
        $condition['showend'] = array('gt', $time);
        $list = $dao->where($condition)->order("showstart DESC")->limit("0,11")->findAll();
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
        $page['title'] = 'You can view, join and initiate events in Beijing, Shanghai, Guangzhou and Shenzhen. There are various events like Night Life, Party and Community Activities.  Events  -  BeingfunChina 缤纷中国';
        $page['keywords'] = "缤纷中国,Events,BeingfunChina,Night Life,Party,Community,Activities";
        $page['description'] = "You can view, join and initiate events in Beijing, Shanghai, Guangzhou and Shenzhen. There are various events like Night Life, Party and Community Activities.";
        $this->assign('page', $page);
        $this->ads('10', 'channel');
        $this->display();
    }

//end index

    /**
     * 活动列表页
     * @date 2010年 05月 04日 星期二 10:57:56 CST
     */
    function ls() {
        //活动列表页
        $this->chk_cid();
        if ($_REQUEST['id']) {
            $_SESSION['typeid'] = Input::getVar($_REQUEST['id']);
        }
        if ($_REQUEST['type'] == 'all') {
            unset($_SESSION['typeid']);
        }
        if ($_REQUEST['st']) {
            $_SESSION['st'] = Input::getVar($_REQUEST['st']);
        }
        if ($_REQUEST['et']) {
            $_SESSION['et'] = Input::getVar($_REQUEST['et']);
        }
        $condition['cid'] = $this->pcid;
        $this->assign('range_time', $this->range_time());
        $this->assign('class_tree', $this->get_class(2050));

        $time = time();
        $dao = D("Archives");
        $condition = array();
        $condition['channel'] = '10';
        $condition['ismake'] = '1';
        if ($_SESSION['st'] != '0' && $_SESSION['et'] != '0') {
            $condition['_string'] = "(`showstart`>='{$_SESSION['st']}' AND `showstart`<='{$_SESSION['et']}') OR (`showend`>='{$_SESSION['st']}' AND `showend`>='{$_SESSION['et']}')";
        } else {
            $condition['_string'] = "";
        }
        if ($_SESSION['typeid']) {
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
        $this->assign('data', $data);

        $this->assign('dh', $this->_get_dh($_SESSION['typeid']));

        $page = array();
        $page['title'] = empty($info['title']) ? 'Events list  -  BeingfunChina 缤纷中国' : $info['title'] . '  -  BeingfunChina 缤纷中国';
        $page['keywords'] = empty($info['tags']) ? "Events,list" : $info['tags'];
        $page['description'] = empty($info['title']) ? "Events list in BeingfunChina" : $info['title'];
        $this->assign('page', $page);

        $this->ads('10', 'list');
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
        $page['title'] = empty($info['title']) ? 'Events  -  BeingfunChina 缤纷中国' : $info['title'] . '  -  BeingfunChina 缤纷中国';
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
    }

//end thread

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
    }

//end _new_thread

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
        $tarr['7']['name'] = 'Last Week';
        $tarr['7']['st'] = $t - ($day * 7);
        $tarr['7']['et'] = $t + ($day * 7);
        $tarr['30']['name'] = 'Last Month';
        $tarr['30']['st'] = $t - ($day * 30);
        $tarr['30']['et'] = $t + ($day * 30);
        $tarr['90']['name'] = 'Recent Three Months';
        $tarr['90']['st'] = $t - ($day * 30 * 3);
        $tarr['90']['et'] = $t + ($day * 30 * 3);
        $tarr['365']['name'] = 'This Year';
        $tarr['365']['st'] = mktime(0, 0, 0, 1, 1, date('Y'));
        $tarr['365']['et'] = mktime(0, 0, 0, 1, 1, date('Y') + 1) - 1;
        $tarr['all']['name'] = 'All Time';
        $tarr['all']['st'] = 0;
        $tarr['all']['et'] = 0;
        return $tarr;
    }

//end range_time

    /**
     * 获取分类
     * @date 2010-7-6
     * @time 下午03:48:19
     */
    protected function get_class($topid) {
        //sm
        $dao = D("Arctype");
        $list = $dao->where("topid=$topid AND ishidden=0")->order("id ASC")->findAll();
        unset($dao);
        return $list;
    }

//end function_name

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
    }

//end new_event
}

// END EventAction
?>
