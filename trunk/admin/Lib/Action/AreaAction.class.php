<?php
class AreaAction extends PublicAction{
	public function index(){
		//城市和地区表_index
		$this->display();
	}

	public function ajaxlist(){
		//城市和地区表_ajaxlist
		$list=D("Area");
		$order=!empty($_REQUEST["order"])?$_REQUEST["order"]:$list->getPk(); //排序字段,默认为"Pk"
		$sortd=!empty($_REQUEST["sort"])?$_REQUEST["sort"]:"asc"; //排序顺序,默认为"asc"
		$orderBy=$order." ".$sortd;//组合排序条件
		$feilds='fup,name,class,sons,type,admin,list,listorder,passwd,logo,descrip,style,template,jumpurl,maxperpage,metakeywords,metadescription,allowcomment,allowpost,allowviewtitle,allowviewcontent,allowdownload,forbidshow,config,id';
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
		//城市和地区表_insert
		$list=D("Area");
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
		//城市和地区表_delete
		$list=D("Area");
		$list->delete($_REQUEST["id"]);
		redirect(__URL__."/index");
	}


	public function update(){
		//城市和地区表_update
		$list=D("Area");
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
		//城市和地区表_edit
		$result=D("Area");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("fup",$list["fup"]);
		$this->assign("name",$list["name"]);
		$this->assign("class",$list["class"]);
		$this->assign("sons",$list["sons"]);
		$this->assign("type",$list["type"]);
		$this->assign("admin",$list["admin"]);
		$this->assign("list",$list["list"]);
		$this->assign("listorder",$list["listorder"]);
		$this->assign("passwd",$list["passwd"]);
		$this->assign("logo",$list["logo"]);
		$this->assign("descrip",$list["descrip"]);
		$this->assign("style",$list["style"]);
		$this->assign("template",$list["template"]);
		$this->assign("jumpurl",$list["jumpurl"]);
		$this->assign("maxperpage",$list["maxperpage"]);
		$this->assign("metakeywords",$list["metakeywords"]);
		$this->assign("metadescription",$list["metadescription"]);
		$this->assign("allowcomment",$list["allowcomment"]);
		$this->assign("allowpost",$list["allowpost"]);
		$this->assign("allowviewtitle",$list["allowviewtitle"]);
		$this->assign("allowviewcontent",$list["allowviewcontent"]);
		$this->assign("allowdownload",$list["allowdownload"]);
		$this->assign("forbidshow",$list["forbidshow"]);
		$this->assign("config",$list["config"]);

		$this->display();
	}


	public function view(){
		//城市和地区表_view
		$result=D("Area");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("fup",$list["fup"]);
		$this->assign("name",$list["name"]);
		$this->assign("class",$list["class"]);
		$this->assign("sons",$list["sons"]);
		$this->assign("type",$list["type"]);
		$this->assign("admin",$list["admin"]);
		$this->assign("list",$list["list"]);
		$this->assign("listorder",$list["listorder"]);
		$this->assign("passwd",$list["passwd"]);
		$this->assign("logo",$list["logo"]);
		$this->assign("descrip",$list["descrip"]);
		$this->assign("style",$list["style"]);
		$this->assign("template",$list["template"]);
		$this->assign("jumpurl",$list["jumpurl"]);
		$this->assign("maxperpage",$list["maxperpage"]);
		$this->assign("metakeywords",$list["metakeywords"]);
		$this->assign("metadescription",$list["metadescription"]);
		$this->assign("allowcomment",$list["allowcomment"]);
		$this->assign("allowpost",$list["allowpost"]);
		$this->assign("allowviewtitle",$list["allowviewtitle"]);
		$this->assign("allowviewcontent",$list["allowviewcontent"]);
		$this->assign("allowdownload",$list["allowdownload"]);
		$this->assign("forbidshow",$list["forbidshow"]);
		$this->assign("config",$list["config"]);

		$this->display();
	}


	public function add(){
		//城市和地区表_add
		$this->display();
	}

}
?>
