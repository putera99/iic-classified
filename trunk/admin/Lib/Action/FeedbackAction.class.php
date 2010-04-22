<?php
class FeedbackAction extends PublicAction{
	public function index(){
		//通用留言板_index
		$this->display();
	}

	public function ajaxlist(){
		//通用留言板_ajaxlist
		$list=D("Feedback");
		$order=!empty($_REQUEST["order"])?$_REQUEST["order"]:$list->getPk(); //排序字段,默认为"Pk"
		$sortd=!empty($_REQUEST["sort"])?$_REQUEST["sort"]:"asc"; //排序顺序,默认为"asc"
		$orderBy=$order." ".$sortd;//组合排序条件
		$feilds='fid,xid,itype,uid,username,arctitle,ip,ischeck,ctime,bad,good,face,msg,id';
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
		//通用留言板_insert
		$list=D("Feedback");
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
		//通用留言板_delete
		$list=D("Feedback");
		$list->delete($_REQUEST["id"]);
		redirect(__URL__."/index");
	}


	public function update(){
		//通用留言板_update
		$list=D("Feedback");
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
		//通用留言板_edit
		$result=D("Feedback");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("fid",$list["fid"]);
		$this->assign("xid",$list["xid"]);
		$this->assign("itype",$list["itype"]);
		$this->assign("uid",$list["uid"]);
		$this->assign("username",$list["username"]);
		$this->assign("arctitle",$list["arctitle"]);
		$this->assign("ip",$list["ip"]);
		$this->assign("ischeck",$list["ischeck"]);
		$this->assign("ctime",$list["ctime"]);
		$this->assign("bad",$list["bad"]);
		$this->assign("good",$list["good"]);
		$this->assign("face",$list["face"]);
		$this->assign("msg",$list["msg"]);

		$this->display();
	}


	public function view(){
		//通用留言板_view
		$result=D("Feedback");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("fid",$list["fid"]);
		$this->assign("xid",$list["xid"]);
		$this->assign("itype",$list["itype"]);
		$this->assign("uid",$list["uid"]);
		$this->assign("username",$list["username"]);
		$this->assign("arctitle",$list["arctitle"]);
		$this->assign("ip",$list["ip"]);
		$this->assign("ischeck",$list["ischeck"]);
		$this->assign("ctime",$list["ctime"]);
		$this->assign("bad",$list["bad"]);
		$this->assign("good",$list["good"]);
		$this->assign("face",$list["face"]);
		$this->assign("msg",$list["msg"]);

		$this->display();
	}


	public function add(){
		//通用留言板_add
		$this->display();
	}

}
?>
