<?php
class AddonJobsAction extends PublicAction{
	public function index(){
		//招聘与求职_index
		$this->display();
	}

	public function ajaxlist(){
		//整站档案_ajaxlist
		$list=D("Archives");
		$order=!empty($_REQUEST["order"])?$_REQUEST["order"]:$list->getPk(); //排序字段,默认为"Pk"
		$sortd=!empty($_REQUEST["sort"])?$_REQUEST["sort"]:"asc"; //排序顺序,默认为"asc"
		$orderBy=$order." ".$sortd;//组合排序条件
		$feilds='typeid,industry,bycity,cid,uid,flag,ismake,channel,arcrank,click,title,shorttitle,color,writer,source,litpic,pubdate,senddate,keywords,lastpost,star1,star2,star3,star4,star5,goodpost,badpost,notpost,description,filename,uip,lastview,editpwd,showstart,showend,editer,edittime,albumid,albumnum,itype,category,telephone,fax,mobphone,email,oicq,msn,maps,city_id,zone_id,street_id,position,contact,ltdid,linkman,id';
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
		$condition['channel']=4;
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
		$this->display("Archives:ajaxlist");
	}


	public function insert(){
		//招聘与求职_insert
		$list=D("AddonJobs");
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
		//招聘与求职_delete
		$list=D("AddonJobs");
		$list->delete($_REQUEST["id"]);
		redirect(__URL__."/index");
	}


	public function update(){
		//招聘与求职_update
		$list=D("AddonJobs");
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
		//招聘与求职_edit
		$result=D("AddonJobs");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("aid",$list["aid"]);
		$this->assign("joblocated",$list["joblocated"]);
		$this->assign("Experience",$list["Experience"]);
		$this->assign("salary",$list["salary"]);
		$this->assign("content",$list["content"]);

		$this->display();
	}


	public function view(){
		//招聘与求职_view
		$result=D("AddonJobs");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("aid",$list["aid"]);
		$this->assign("joblocated",$list["joblocated"]);
		$this->assign("Experience",$list["Experience"]);
		$this->assign("salary",$list["salary"]);
		$this->assign("content",$list["content"]);

		$this->display();
	}


	public function add(){
		//招聘与求职_add
		$this->display();
	}

}
?>
