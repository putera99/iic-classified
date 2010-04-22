<?php
/**
 +------------------------------------------------------------------------------
 * StepselectAction控制器类
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  联动类别管理
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-1-16
 * @time  下午02:39:07
 +------------------------------------------------------------------------------
 */
class StepselectAction extends PublicAction{
public function index(){
		//联动类别_index
		$this->display();
	}

	public function ajaxlist(){
		//联动类别_ajaxlist
		$list=D("Stepselect");
		$order=!empty($_REQUEST["order"])?$_REQUEST["order"]:$list->getPk(); //排序字段,默认为"Pk"
		$sortd=!empty($_REQUEST["sort"])?$_REQUEST["sort"]:"asc"; //排序顺序,默认为"asc"
		$orderBy=$order." ".$sortd;//组合排序条件
		$feilds='id,itemname,egroup,issign,issystem';
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
		$page=$p->ajaxshow($count);//显示分页
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
		//联动类别_insert
		$list=D("Stepselect");
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
		//联动类别_delete
		$list=D("Stepselect");
		$list->delete($_REQUEST["id"]);
		redirect(__URL__."/index");
	}


	public function update(){
		//联动类别_update
		$list=D("Stepselect");
		$data=$_REQUEST;
		$data=$list->create($data);
        if($data) {
            if($list->save($data)){
                $this->success('数据更新成功！');
            }else{
                $this->error('数据更新错误！');
            }
        }else{
             $this->error($list->getError());
        }
	}


	public function edit(){
		//联动类别_edit
		$result=D("Stepselect");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("itemname",$list["itemname"]);
		$this->assign("egroup",$list["egroup"]);
		$this->assign("issign",$list["issign"]);
		$this->assign("issystem",$list["issystem"]);
		$this->display();
	}


	public function view(){
		//联动类别_view
		$result=D("Stepselect");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("itemname",$list["itemname"]);
		$this->assign("egroup",$list["egroup"]);
		$this->assign("issign",$list["issign"]);
		$this->assign("issystem",$list["issystem"]);
		$this->display();
	}


	public function add(){
		//联动类别_add
		$this->display();
	}
}//END StepselectAction