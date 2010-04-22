<?php
class StatAction extends PublicAction{
	public function index(){
		//点击统计综合_index
		$this->display();
	}

	public function ajaxlist(){
		//点击统计综合_ajaxlist
		$list=D("Stat");
		$order=!empty($_REQUEST["order"])?$_REQUEST["order"]:$list->getPk(); //排序字段,默认为"Pk"
		$sortd=!empty($_REQUEST["sort"])?$_REQUEST["sort"]:"asc"; //排序顺序,默认为"asc"
		$orderBy=$order." ".$sortd;//组合排序条件
		$feilds='xid,mon,stype,d01,d02,d03,d04,d05,d06,d07,d08,d09,d10,d11,d12,d13,d14,d15,d16,d17,d18,d19,d20,d21,d22,d23,d24,d25,d26,d27,d28,d29,d30,d31,id';
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
		//点击统计综合_insert
		$list=D("Stat");
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
		//点击统计综合_delete
		$list=D("Stat");
		$list->delete($_REQUEST["id"]);
		redirect(__URL__."/index");
	}


	public function update(){
		//点击统计综合_update
		$list=D("Stat");
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
		//点击统计综合_edit
		$result=D("Stat");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("xid",$list["xid"]);
		$this->assign("mon",$list["mon"]);
		$this->assign("stype",$list["stype"]);
		$this->assign("d01",$list["d01"]);
		$this->assign("d02",$list["d02"]);
		$this->assign("d03",$list["d03"]);
		$this->assign("d04",$list["d04"]);
		$this->assign("d05",$list["d05"]);
		$this->assign("d06",$list["d06"]);
		$this->assign("d07",$list["d07"]);
		$this->assign("d08",$list["d08"]);
		$this->assign("d09",$list["d09"]);
		$this->assign("d10",$list["d10"]);
		$this->assign("d11",$list["d11"]);
		$this->assign("d12",$list["d12"]);
		$this->assign("d13",$list["d13"]);
		$this->assign("d14",$list["d14"]);
		$this->assign("d15",$list["d15"]);
		$this->assign("d16",$list["d16"]);
		$this->assign("d17",$list["d17"]);
		$this->assign("d18",$list["d18"]);
		$this->assign("d19",$list["d19"]);
		$this->assign("d20",$list["d20"]);
		$this->assign("d21",$list["d21"]);
		$this->assign("d22",$list["d22"]);
		$this->assign("d23",$list["d23"]);
		$this->assign("d24",$list["d24"]);
		$this->assign("d25",$list["d25"]);
		$this->assign("d26",$list["d26"]);
		$this->assign("d27",$list["d27"]);
		$this->assign("d28",$list["d28"]);
		$this->assign("d29",$list["d29"]);
		$this->assign("d30",$list["d30"]);
		$this->assign("d31",$list["d31"]);

		$this->display();
	}


	public function view(){
		//点击统计综合_view
		$result=D("Stat");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("xid",$list["xid"]);
		$this->assign("mon",$list["mon"]);
		$this->assign("stype",$list["stype"]);
		$this->assign("d01",$list["d01"]);
		$this->assign("d02",$list["d02"]);
		$this->assign("d03",$list["d03"]);
		$this->assign("d04",$list["d04"]);
		$this->assign("d05",$list["d05"]);
		$this->assign("d06",$list["d06"]);
		$this->assign("d07",$list["d07"]);
		$this->assign("d08",$list["d08"]);
		$this->assign("d09",$list["d09"]);
		$this->assign("d10",$list["d10"]);
		$this->assign("d11",$list["d11"]);
		$this->assign("d12",$list["d12"]);
		$this->assign("d13",$list["d13"]);
		$this->assign("d14",$list["d14"]);
		$this->assign("d15",$list["d15"]);
		$this->assign("d16",$list["d16"]);
		$this->assign("d17",$list["d17"]);
		$this->assign("d18",$list["d18"]);
		$this->assign("d19",$list["d19"]);
		$this->assign("d20",$list["d20"]);
		$this->assign("d21",$list["d21"]);
		$this->assign("d22",$list["d22"]);
		$this->assign("d23",$list["d23"]);
		$this->assign("d24",$list["d24"]);
		$this->assign("d25",$list["d25"]);
		$this->assign("d26",$list["d26"]);
		$this->assign("d27",$list["d27"]);
		$this->assign("d28",$list["d28"]);
		$this->assign("d29",$list["d29"]);
		$this->assign("d30",$list["d30"]);
		$this->assign("d31",$list["d31"]);

		$this->display();
	}


	public function add(){
		//点击统计综合_add
		$this->display();
	}

}
?>
