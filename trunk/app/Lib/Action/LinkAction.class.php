<?php

/**
  +------------------------------------------------------------------------------
 * LinkAction控制器类
  +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  友情链接
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-8-4
 * @time  上午09:40:31
  +------------------------------------------------------------------------------
 */
class LinkAction extends CommonAction {

    /**
     * 友情链接首页
     * @date 2010-8-4
     * @time 上午09:44:55
     */
    function index() {
        //友情链接首页
        $dao = D("Flink");
        $condition = array();
        $condition['ischeck'] = '1';
        $data=$dao->where($condition)->order("sortrank  ASC")->findAll();
        $other=array();
        $partner=array();
        foreach ($data as $v) {
            if ($v['itype'] != "Partner Links") {
                if (empty($other[$v['itype']])) {
                    $other[$v['itype']]['text'] = '';
                }
                $other[$v['itype']]['name'] = $v['itype'];
                $other[$v['itype']]['text'].='<a href="' . $v['url'] . '" target=\"_blank\"><img height="36" width="130" src="' . picurl($v['logo']) . '" alt="' . $v['webname'] . '"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            } else {
                $partner[] = $v;
            }
        }
        $this->assign("other", $other);
        $this->assign("partner", $partner);
        $page = array();
        $page['title'] = 'Partner Links  -  BeingfunChina 缤纷中国';
        $page['keywords'] ="Partner,Links ";
        $page['description'] ="Partner Links in BeingfunChina" ;
        $this->assign('page', $page);
        $this->display();
    }
    
    /**
       *跳转到指定页面
       *@date 2010-8-24
       *@time 下午03:00:26
       */
    function jump() {
            //跳转到指定页面
            $url=mydecode($_GET['url']);
            $url=explode('||',$url);
            if($url['1']!='0'){
                $this->iicstat($url['1'],'ad');
            }
            redirect($url['0']);
    }//end jump
//end index
}

//end LinkAction
?>