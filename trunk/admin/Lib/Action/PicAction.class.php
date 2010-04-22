<?php
class PicAction extends PublicAction{
	public function index(){
		//用户图片_index
		$this->display();
	}

	public function ajaxlist(){
		//用户图片_ajaxlist
		$list=D("Pic");
		$order=!empty($_REQUEST["order"])?$_REQUEST["order"]:$list->getPk(); //排序字段,默认为"Pk"
		$sortd=!empty($_REQUEST["sort"])?$_REQUEST["sort"]:"asc"; //排序顺序,默认为"asc"
		$orderBy=$order." ".$sortd;//组合排序条件
		$feilds='album_id,uid,username,dateline,postip,filename,title,type,size,filepath,thumb,remote,hot,click_6,click_7,click_8,click_9,click_10,id';
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
		//用户图片_insert
		$list=D("Pic");
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
		//用户图片_delete
		$list=D("Pic");
		$list->delete($_REQUEST["id"]);
		redirect(__URL__."/index");
	}


	public function update(){
		//用户图片_update
		$list=D("Pic");
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
		//用户图片_edit
		$result=D("Pic");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("album_id",$list["album_id"]);
		$this->assign("uid",$list["uid"]);
		$this->assign("username",$list["username"]);
		$this->assign("dateline",$list["dateline"]);
		$this->assign("postip",$list["postip"]);
		$this->assign("filename",$list["filename"]);
		$this->assign("title",$list["title"]);
		$this->assign("type",$list["type"]);
		$this->assign("size",$list["size"]);
		$this->assign("filepath",$list["filepath"]);
		$this->assign("thumb",$list["thumb"]);
		$this->assign("remote",$list["remote"]);
		$this->assign("hot",$list["hot"]);
		$this->assign("click_6",$list["click_6"]);
		$this->assign("click_7",$list["click_7"]);
		$this->assign("click_8",$list["click_8"]);
		$this->assign("click_9",$list["click_9"]);
		$this->assign("click_10",$list["click_10"]);

		$this->display();
	}


	public function view(){
		//用户图片_view
		$result=D("Pic");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("album_id",$list["album_id"]);
		$this->assign("uid",$list["uid"]);
		$this->assign("username",$list["username"]);
		$this->assign("dateline",$list["dateline"]);
		$this->assign("postip",$list["postip"]);
		$this->assign("filename",$list["filename"]);
		$this->assign("title",$list["title"]);
		$this->assign("type",$list["type"]);
		$this->assign("size",$list["size"]);
		$this->assign("filepath",$list["filepath"]);
		$this->assign("thumb",$list["thumb"]);
		$this->assign("remote",$list["remote"]);
		$this->assign("hot",$list["hot"]);
		$this->assign("click_6",$list["click_6"]);
		$this->assign("click_7",$list["click_7"]);
		$this->assign("click_8",$list["click_8"]);
		$this->assign("click_9",$list["click_9"]);
		$this->assign("click_10",$list["click_10"]);

		$this->display();
	}


	public function add(){
		//用户图片_add
		$this->display();
	}

}
?>
