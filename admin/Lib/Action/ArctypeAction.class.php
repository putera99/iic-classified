<?php
class ArctypeAction extends PublicAction{
	public function index(){
		//系用栏目列表_index
		$this->display();
	}

	public function ajaxlist(){
		//系用栏目列表_ajaxlist
		$list=D("Arctype");
		$order=!empty($_REQUEST["order"])?$_REQUEST["order"]:$list->getPk(); //排序字段,默认为"Pk"
		$sortd=!empty($_REQUEST["sort"])?$_REQUEST["sort"]:"asc"; //排序顺序,默认为"asc"
		$orderBy=$order." ".$sortd;//组合排序条件
		$feilds='reid,topid,sortrank,typename,ename,typedir,issend,channeltype,uid,cid,maxpage,ispart,corank,tempindex,templist,temparticle,namerule,namerule2,modname,description,keywords,seotitle,moresite,sitepath,siteurl,ishidden,cross,hits,posttime,mtime,crossid,content,smalltypes,id';
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
		//系用栏目列表_insert
		$list=D("Arctype");
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
		//系用栏目列表_delete
		$list=D("Arctype");
		$list->delete($_REQUEST["id"]);
		redirect(__URL__."/index");
	}


	public function update(){
		//系用栏目列表_update
		$list=D("Arctype");
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
		//系用栏目列表_edit
		$result=D("Arctype");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("reid",$list["reid"]);
		$this->assign("topid",$list["topid"]);
		$this->assign("sortrank",$list["sortrank"]);
		$this->assign("typename",$list["typename"]);
		$this->assign("ename",$list["ename"]);
		$this->assign("typedir",$list["typedir"]);
		$this->assign("issend",$list["issend"]);
		$this->assign("channeltype",$list["channeltype"]);
		$this->assign("uid",$list["uid"]);
		$this->assign("cid",$list["cid"]);
		$this->assign("maxpage",$list["maxpage"]);
		$this->assign("ispart",$list["ispart"]);
		$this->assign("corank",$list["corank"]);
		$this->assign("tempindex",$list["tempindex"]);
		$this->assign("templist",$list["templist"]);
		$this->assign("temparticle",$list["temparticle"]);
		$this->assign("namerule",$list["namerule"]);
		$this->assign("namerule2",$list["namerule2"]);
		$this->assign("modname",$list["modname"]);
		$this->assign("description",$list["description"]);
		$this->assign("keywords",$list["keywords"]);
		$this->assign("seotitle",$list["seotitle"]);
		$this->assign("moresite",$list["moresite"]);
		$this->assign("sitepath",$list["sitepath"]);
		$this->assign("siteurl",$list["siteurl"]);
		$this->assign("ishidden",$list["ishidden"]);
		$this->assign("cross",$list["cross"]);
		$this->assign("hits",$list["hits"]);
		$this->assign("posttime",$list["posttime"]);
		$this->assign("mtime",$list["mtime"]);
		$this->assign("crossid",$list["crossid"]);
		$this->assign("content",$list["content"]);
		$this->assign("smalltypes",$list["smalltypes"]);

		$this->display();
	}


	public function view(){
		//系用栏目列表_view
		$result=D("Arctype");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("reid",$list["reid"]);
		$this->assign("topid",$list["topid"]);
		$this->assign("sortrank",$list["sortrank"]);
		$this->assign("typename",$list["typename"]);
		$this->assign("ename",$list["ename"]);
		$this->assign("typedir",$list["typedir"]);
		$this->assign("issend",$list["issend"]);
		$this->assign("channeltype",$list["channeltype"]);
		$this->assign("uid",$list["uid"]);
		$this->assign("cid",$list["cid"]);
		$this->assign("maxpage",$list["maxpage"]);
		$this->assign("ispart",$list["ispart"]);
		$this->assign("corank",$list["corank"]);
		$this->assign("tempindex",$list["tempindex"]);
		$this->assign("templist",$list["templist"]);
		$this->assign("temparticle",$list["temparticle"]);
		$this->assign("namerule",$list["namerule"]);
		$this->assign("namerule2",$list["namerule2"]);
		$this->assign("modname",$list["modname"]);
		$this->assign("description",$list["description"]);
		$this->assign("keywords",$list["keywords"]);
		$this->assign("seotitle",$list["seotitle"]);
		$this->assign("moresite",$list["moresite"]);
		$this->assign("sitepath",$list["sitepath"]);
		$this->assign("siteurl",$list["siteurl"]);
		$this->assign("ishidden",$list["ishidden"]);
		$this->assign("cross",$list["cross"]);
		$this->assign("hits",$list["hits"]);
		$this->assign("posttime",$list["posttime"]);
		$this->assign("mtime",$list["mtime"]);
		$this->assign("crossid",$list["crossid"]);
		$this->assign("content",$list["content"]);
		$this->assign("smalltypes",$list["smalltypes"]);

		$this->display();
	}


	public function add(){
		//系用栏目列表_add
		$this->display();
	}

}
?>
