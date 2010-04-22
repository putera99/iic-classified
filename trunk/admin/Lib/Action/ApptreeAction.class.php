<?php
/**
 +------------------------------------------------------------------------------
 * ApptreeAction控制器类
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  bi
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-1-11
 * @time  下午04:19:36
 +------------------------------------------------------------------------------
 */
class ApptreeAction extends PublicAction{
	public function index(){
		$this->display();
	}
public function ajaxlist(){
		$list=D("Apptree");
		$order=!empty($_REQUEST["order"])?$_REQUEST["order"]:$list->getPk(); //排序字段,默认为"Pk"
		$sortd=!empty($_REQUEST["sort"])?$_REQUEST["sort"]:"asc"; //排序顺序,默认为"asc"
		$orderBy=$order." ".$sortd;//组合排序条件
		$feilds='title,shortname,pid,appmodel,link,id';
        $condition=Array();//搜索的条件
        //$condition['title']=array('like',"a%");//高级搜索过滤
	    if(!empty($_REQUEST['searchkey'])){
			//搜索相关
			$searchkey=$_REQUEST["searchkey"];
			$searchtype=!empty($_REQUEST['searchtype'])?$_REQUEST['searchtype']:'OR';//默认的公共搜索条件为OR
			$this->assign("searchkey",$_REQUEST["searchkey"]);//显示关键字
			$this->assign("searchurl",$_SERVER["REQUEST_URI"]);//当前的URL
			$this->assign("searchtype",$_REQUEST["searchtype"]);//当前的搜索类型
	
	        $ser_c=array('like',"%$searchkey%");//公共的过滤条件
	        $condition['id']=array(id,$searchtype);//模糊搜索关键字全局过滤
	    }
		$count= $list->where($condition)->count();//获取分页总数量
		$p= new ajaxpage($count);
		$page=$p->ajaxshow();//显示分页
		$this->assign("page",$page);//显示分页
		$limit=$p->firstRow.",".$p->listRows;//设定分面的大小
		$limit=($limit==",")?'':$limit;//分页的大小
		$list=$list
			->where($condition)
			->field($feilds)
			->order($orderBy)
			->limit($limit)
			->findAll();
		$this->assign("list",$list);
		$this->display();
	}


	public function insert(){
		$list=D("Apptree");
		$data=$_REQUEST;
		$data=$list->create($data);
        if($data) {
            if($list->add($data)){
                $this->success('数据增加成功！');
            }else{
                $this->error('数据增加错误！');
            }
        }else{
             $this->error($list->getError());
        }
	}


	public function delete(){
		$list=D("Apptree");
		$list->delete($_REQUEST["id"]);
		redirect(__URL__."/index");
	}


	public function update(){
		$list=D("Apptree");
		$data=$_REQUEST;
		$data=$list->create($data);
        if($data) {
            if($list->save($data)){
                $this->success('数据更新成功！');
            }else{
            	dump($data);
                $this->error('数据更新错误！');
            }
        }else{
             $this->error($list->getError());
        }
	}


	public function edit(){
		$result=D("Apptree");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
		$this->assign("id",$list["id"]);
		$this->assign("albumname",$list["albumname"]);
		$this->assign("uid",$list["uid"]);
		$this->assign("username",$list["username"]);
		$this->assign("dateline",$list["dateline"]);
		$this->assign("updatetime",$list["updatetime"]);
		$this->assign("picnum",$list["picnum"]);
		$this->assign("pic",$list["pic"]);
		$this->assign("picflag",$list["picflag"]);
		$this->assign("friend",$list["friend"]);
		$this->assign("password",$list["password"]);
		$this->assign("target_ids",$list["target_ids"]);

		$this->display();
	}


	public function view(){
		$result=D("Apptree");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("albumname",$list["albumname"]);
		$this->assign("uid",$list["uid"]);
		$this->assign("username",$list["username"]);
		$this->assign("dateline",$list["dateline"]);
		$this->assign("updatetime",$list["updatetime"]);
		$this->assign("picnum",$list["picnum"]);
		$this->assign("pic",$list["pic"]);
		$this->assign("picflag",$list["picflag"]);
		$this->assign("friend",$list["friend"]);
		$this->assign("password",$list["password"]);
		$this->assign("target_ids",$list["target_ids"]);

		$this->display();
	}


	public function add(){
		$this->display();
	}
}//END ApptreeAction