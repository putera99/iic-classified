<?php
/**
 +------------------------------------------------------------------------------
 * SysEnumAction控制器类
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  联动数据管理
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-1-16
 * @time  下午02:35:38
 +------------------------------------------------------------------------------
 */
class SysEnumAction extends PublicAction{
public function index(){
		//联动数据_index
		$this->display();
	}

	public function ajaxlist(){
		//联动数据_ajaxlist
		$list=D("SysEnum");
		$order=!empty($_REQUEST["order"])?$_REQUEST["order"]:$list->getPk(); //排序字段,默认为"Pk"
		$sortd=!empty($_REQUEST["sort"])?$_REQUEST["sort"]:"asc"; //排序顺序,默认为"asc"
		$orderBy=$order." ".$sortd;//组合排序条件
		$feilds='id,ename,evalue,egroup,disorder,issign';
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
		//联动数据_insert
		$list=D("SysEnum");
		$data=$_REQUEST;
		$data=$list->create($data);
        if($data) {
        	$data['disorder']=$data['disorder']==''?$data['evalue']:$data['disorder'];
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
		//联动数据_delete
		$list=D("SysEnum");
		$list->delete($_REQUEST["id"]);
		redirect(__URL__."/index");
	}


	public function update(){
		//联动数据_update
		$list=D("SysEnum");
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
		//联动数据_edit
		$result=D("SysEnum");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
				//id,ename,evalue,egroup,disorder,issign
		$this->assign("ename",$list["ename"]);
		$this->assign("evalue",$list["evalue"]);
		$this->assign("egroup",$list["egroup"]);
		$this->assign("disorder",$list["disorder"]);
		$this->assign("issign",$list["issign"]);
		$this->display();
	}


	public function view(){
		//联动数据_view
		$result=D("SysEnum");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("ename",$list["ename"]);
		$this->assign("evalue",$list["evalue"]);
		$this->assign("egroup",$list["egroup"]);
		$this->assign("disorder",$list["disorder"]);
		$this->assign("issign",$list["issign"]);
		$this->display();
	}


	public function add(){
		//联动数据_add
		$this->display();
	}
}//END SysEnumAction