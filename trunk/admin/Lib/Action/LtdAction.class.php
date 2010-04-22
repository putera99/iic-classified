<?php
class LtdAction extends PublicAction{
	public function index(){
		//公司信息_index
		$this->display();
	}

	public function ajaxlist(){
		//公司信息_ajaxlist
		$list=D("Ltd");
		$order=!empty($_REQUEST["order"])?$_REQUEST["order"]:$list->getPk(); //排序字段,默认为"Pk"
		$sortd=!empty($_REQUEST["sort"])?$_REQUEST["sort"]:"asc"; //排序顺序,默认为"asc"
		$orderBy=$order." ".$sortd;//组合排序条件
		$feilds='uid,title,passwd,info,jyfw,yyzz,zstitle1,zssm1,zspic1,zstitle2,zssm2,zspic2,zstitle3,zssm3,zspic3,zstitle4,zssm4,zspic4,ctime,mtime,cip,mip,status,id';
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
		//公司信息_insert
		$list=D("Ltd");
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
		//公司信息_delete
		$list=D("Ltd");
		$list->delete($_REQUEST["id"]);
		redirect(__URL__."/index");
	}


	public function update(){
		//公司信息_update
		$list=D("Ltd");
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
		//公司信息_edit
		$result=D("Ltd");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("uid",$list["uid"]);
		$this->assign("title",$list["title"]);
		$this->assign("passwd",$list["passwd"]);
		$this->assign("info",$list["info"]);
		$this->assign("jyfw",$list["jyfw"]);
		$this->assign("yyzz",$list["yyzz"]);
		$this->assign("zstitle1",$list["zstitle1"]);
		$this->assign("zssm1",$list["zssm1"]);
		$this->assign("zspic1",$list["zspic1"]);
		$this->assign("zstitle2",$list["zstitle2"]);
		$this->assign("zssm2",$list["zssm2"]);
		$this->assign("zspic2",$list["zspic2"]);
		$this->assign("zstitle3",$list["zstitle3"]);
		$this->assign("zssm3",$list["zssm3"]);
		$this->assign("zspic3",$list["zspic3"]);
		$this->assign("zstitle4",$list["zstitle4"]);
		$this->assign("zssm4",$list["zssm4"]);
		$this->assign("zspic4",$list["zspic4"]);
		$this->assign("ctime",$list["ctime"]);
		$this->assign("mtime",$list["mtime"]);
		$this->assign("cip",$list["cip"]);
		$this->assign("mip",$list["mip"]);
		$this->assign("status",$list["status"]);

		$this->display();
	}


	public function view(){
		//公司信息_view
		$result=D("Ltd");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("uid",$list["uid"]);
		$this->assign("title",$list["title"]);
		$this->assign("passwd",$list["passwd"]);
		$this->assign("info",$list["info"]);
		$this->assign("jyfw",$list["jyfw"]);
		$this->assign("yyzz",$list["yyzz"]);
		$this->assign("zstitle1",$list["zstitle1"]);
		$this->assign("zssm1",$list["zssm1"]);
		$this->assign("zspic1",$list["zspic1"]);
		$this->assign("zstitle2",$list["zstitle2"]);
		$this->assign("zssm2",$list["zssm2"]);
		$this->assign("zspic2",$list["zspic2"]);
		$this->assign("zstitle3",$list["zstitle3"]);
		$this->assign("zssm3",$list["zssm3"]);
		$this->assign("zspic3",$list["zspic3"]);
		$this->assign("zstitle4",$list["zstitle4"]);
		$this->assign("zssm4",$list["zssm4"]);
		$this->assign("zspic4",$list["zspic4"]);
		$this->assign("ctime",$list["ctime"]);
		$this->assign("mtime",$list["mtime"]);
		$this->assign("cip",$list["cip"]);
		$this->assign("mip",$list["mip"]);
		$this->assign("status",$list["status"]);

		$this->display();
	}


	public function add(){
		//公司信息_add
		$this->display();
	}

}
?>
