<?php
class PostAction extends PublicAction{
	public function index(){
		//主题评论综合表_index
		$this->display();
	}

	public function ajaxlist(){
		//主题评论综合表_ajaxlist
		$list=D("Post");
		$order=!empty($_REQUEST["order"])?$_REQUEST["order"]:$list->getPk(); //排序字段,默认为"Pk"
		$sortd=!empty($_REQUEST["sort"])?$_REQUEST["sort"]:"asc"; //排序顺序,默认为"asc"
		$orderBy=$order." ".$sortd;//组合排序条件
		$feilds='qid,l,requery,qidstr,aid,gid,title,uid,username,ip,dateline,message,pic,hotuser,attr,tags,lasttime,is_show,position,star,click,ok,smile,bad,id';
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
		//主题评论综合表_insert
		$list=D("Post");
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
		//主题评论综合表_delete
		$list=D("Post");
		$list->delete($_REQUEST["id"]);
		redirect(__URL__."/index");
	}


	public function update(){
		//主题评论综合表_update
		$list=D("Post");
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
		//主题评论综合表_edit
		$result=D("Post");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("qid",$list["qid"]);
		$this->assign("l",$list["l"]);
		$this->assign("requery",$list["requery"]);
		$this->assign("qidstr",$list["qidstr"]);
		$this->assign("aid",$list["aid"]);
		$this->assign("gid",$list["gid"]);
		$this->assign("title",$list["title"]);
		$this->assign("uid",$list["uid"]);
		$this->assign("username",$list["username"]);
		$this->assign("ip",$list["ip"]);
		$this->assign("dateline",$list["dateline"]);
		$this->assign("message",$list["message"]);
		$this->assign("pic",$list["pic"]);
		$this->assign("hotuser",$list["hotuser"]);
		$this->assign("attr",$list["attr"]);
		$this->assign("tags",$list["tags"]);
		$this->assign("lasttime",$list["lasttime"]);
		$this->assign("is_show",$list["is_show"]);
		$this->assign("position",$list["position"]);
		$this->assign("star",$list["star"]);
		$this->assign("click",$list["click"]);
		$this->assign("ok",$list["ok"]);
		$this->assign("smile",$list["smile"]);
		$this->assign("bad",$list["bad"]);

		$this->display();
	}


	public function view(){
		//主题评论综合表_view
		$result=D("Post");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("qid",$list["qid"]);
		$this->assign("l",$list["l"]);
		$this->assign("requery",$list["requery"]);
		$this->assign("qidstr",$list["qidstr"]);
		$this->assign("aid",$list["aid"]);
		$this->assign("gid",$list["gid"]);
		$this->assign("title",$list["title"]);
		$this->assign("uid",$list["uid"]);
		$this->assign("username",$list["username"]);
		$this->assign("ip",$list["ip"]);
		$this->assign("dateline",$list["dateline"]);
		$this->assign("message",$list["message"]);
		$this->assign("pic",$list["pic"]);
		$this->assign("hotuser",$list["hotuser"]);
		$this->assign("attr",$list["attr"]);
		$this->assign("tags",$list["tags"]);
		$this->assign("lasttime",$list["lasttime"]);
		$this->assign("is_show",$list["is_show"]);
		$this->assign("position",$list["position"]);
		$this->assign("star",$list["star"]);
		$this->assign("click",$list["click"]);
		$this->assign("ok",$list["ok"]);
		$this->assign("smile",$list["smile"]);
		$this->assign("bad",$list["bad"]);

		$this->display();
	}


	public function add(){
		//主题评论综合表_add
		$this->display();
	}

}
?>
