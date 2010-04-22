<?php
class AddonAgentsAction extends PublicAction{
	public function index(){
		//折扣信息_index
		$this->display();
	}

	public function ajaxlist(){
		//折扣信息_ajaxlist
		$list=D("AddonAgents");
		$order=!empty($_REQUEST["order"])?$_REQUEST["order"]:$list->getPk(); //排序字段,默认为"Pk"
		$sortd=!empty($_REQUEST["sort"])?$_REQUEST["sort"]:"asc"; //排序顺序,默认为"asc"
		$orderBy=$order." ".$sortd;//组合排序条件
		$feilds='aid,content,id';
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
		//折扣信息_insert
		$list=D("AddonAgents");
		$data=$_REQUEST;
        /*
		if(!empty($_FILES)) {
          $file= $this->_upload();//如果有文件上传 上传附件
			foreach ($file as $key=>$value){
				$data[$key]=$value['savename'];
			}
        }
        */
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
		//折扣信息_delete
		$list=D("AddonAgents");
		$list->delete($_REQUEST["id"]);
		redirect(__URL__."/index");
	}


	public function update(){
		//折扣信息_update
		$list=D("AddonAgents");
		$data=$_REQUEST;
		/*
        if(!empty($_FILES)) {
          $file= $this->_upload();//如果有文件上传 上传附件
			foreach ($file as $key=>$value){
				$data[$key]=$value['savename'];
			}
        }
        */
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
		//折扣信息_edit
		$result=D("AddonAgents");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("aid",$list["aid"]);
		$this->assign("content",$list["content"]);

		$this->display();
	}


	public function view(){
		//折扣信息_view
		$result=D("AddonAgents");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("aid",$list["aid"]);
		$this->assign("content",$list["content"]);

		$this->display();
	}


	public function add(){
		//折扣信息_add
		$this->display();
	}

}
?>
