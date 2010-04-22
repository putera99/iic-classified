<?php
class PmAction extends PublicAction{
	public function index(){
		//短信息_index
		$this->display();
	}

	public function ajaxlist(){
		//短信息_ajaxlist
		$list=D("Pm");
		$order=!empty($_REQUEST["order"])?$_REQUEST["order"]:$list->getPk(); //排序字段,默认为"Pk"
		$sortd=!empty($_REQUEST["sort"])?$_REQUEST["sort"]:"asc"; //排序顺序,默认为"asc"
		$orderBy=$order." ".$sortd;//组合排序条件
		$feilds='touid,xid,itype,fromuid,tousername,type,ifnew,title,ctime,content,id';
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
		//短信息_insert
		$list=D("Pm");
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
		//短信息_delete
		$list=D("Pm");
		$list->delete($_REQUEST["id"]);
		redirect(__URL__."/index");
	}


	public function update(){
		//短信息_update
		$list=D("Pm");
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
		//短信息_edit
		$result=D("Pm");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("touid",$list["touid"]);
		$this->assign("xid",$list["xid"]);
		$this->assign("itype",$list["itype"]);
		$this->assign("fromuid",$list["fromuid"]);
		$this->assign("tousername",$list["tousername"]);
		$this->assign("type",$list["type"]);
		$this->assign("ifnew",$list["ifnew"]);
		$this->assign("title",$list["title"]);
		$this->assign("ctime",$list["ctime"]);
		$this->assign("content",$list["content"]);

		$this->display();
	}


	public function view(){
		//短信息_view
		$result=D("Pm");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("touid",$list["touid"]);
		$this->assign("xid",$list["xid"]);
		$this->assign("itype",$list["itype"]);
		$this->assign("fromuid",$list["fromuid"]);
		$this->assign("tousername",$list["tousername"]);
		$this->assign("type",$list["type"]);
		$this->assign("ifnew",$list["ifnew"]);
		$this->assign("title",$list["title"]);
		$this->assign("ctime",$list["ctime"]);
		$this->assign("content",$list["content"]);

		$this->display();
	}


	public function add(){
		//短信息_add
		$this->display();
	}

}
?>
