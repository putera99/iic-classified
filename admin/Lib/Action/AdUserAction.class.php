<?php
class AdUserAction extends PublicAction{
	public function index(){
		//发布广告的用户表_index
		$this->display();
	}

	public function ajaxlist(){
		//发布广告的用户表_ajaxlist
		$list=D("AdUser");
		$order=!empty($_REQUEST["order"])?$_REQUEST["order"]:$list->getPk(); //排序字段,默认为"Pk"
		$sortd=!empty($_REQUEST["sort"])?$_REQUEST["sort"]:"asc"; //排序顺序,默认为"asc"
		$orderBy=$order." ".$sortd;//组合排序条件
		$feilds='ad_id,u_uid,u_username,u_day,u_begintime,u_endtime,u_hits,u_yz,u_code,u_money,u_moneycard,u_posttime,id';
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
		//发布广告的用户表_insert
		$list=D("AdUser");
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
		//发布广告的用户表_delete
		$list=D("AdUser");
		$list->delete($_REQUEST["id"]);
		redirect(__URL__."/index");
	}


	public function update(){
		//发布广告的用户表_update
		$list=D("AdUser");
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
		//发布广告的用户表_edit
		$result=D("AdUser");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("ad_id",$list["ad_id"]);
		$this->assign("u_uid",$list["u_uid"]);
		$this->assign("u_username",$list["u_username"]);
		$this->assign("u_day",$list["u_day"]);
		$this->assign("u_begintime",$list["u_begintime"]);
		$this->assign("u_endtime",$list["u_endtime"]);
		$this->assign("u_hits",$list["u_hits"]);
		$this->assign("u_yz",$list["u_yz"]);
		$this->assign("u_code",$list["u_code"]);
		$this->assign("u_money",$list["u_money"]);
		$this->assign("u_moneycard",$list["u_moneycard"]);
		$this->assign("u_posttime",$list["u_posttime"]);

		$this->display();
	}


	public function view(){
		//发布广告的用户表_view
		$result=D("AdUser");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("ad_id",$list["ad_id"]);
		$this->assign("u_uid",$list["u_uid"]);
		$this->assign("u_username",$list["u_username"]);
		$this->assign("u_day",$list["u_day"]);
		$this->assign("u_begintime",$list["u_begintime"]);
		$this->assign("u_endtime",$list["u_endtime"]);
		$this->assign("u_hits",$list["u_hits"]);
		$this->assign("u_yz",$list["u_yz"]);
		$this->assign("u_code",$list["u_code"]);
		$this->assign("u_money",$list["u_money"]);
		$this->assign("u_moneycard",$list["u_moneycard"]);
		$this->assign("u_posttime",$list["u_posttime"]);

		$this->display();
	}


	public function add(){
		//发布广告的用户表_add
		$this->display();
	}

}
?>
