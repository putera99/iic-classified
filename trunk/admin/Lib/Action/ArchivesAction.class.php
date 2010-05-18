<?php
class ArchivesAction extends PublicAction{
	public function index(){
		//整站档案_index
		if(!empty($_REQUEST['channel'])){
			$channel=$_REQUEST['channel'];
			$this->assign('channel',$channel);
		}
		$this->display();
	}

	public function ajaxlist(){
		//整站档案_ajaxlist
		$list=D("Archives");
		$order=!empty($_REQUEST["order"])?$_REQUEST["order"]:$list->getPk(); //排序字段,默认为"Pk"
		$sortd=!empty($_REQUEST["sort"])?$_REQUEST["sort"]:"asc"; //排序顺序,默认为"asc"
		$orderBy=$order." ".$sortd;//组合排序条件
		$feilds='typeid,typeid2,industry,bycity,cid,uid,flag,ismake,channel,arcrank,click,title,shorttitle,color,writer,source,litpic,pubdate,senddate,keywords,lastpost,star1,star2,star3,star4,star5,goodpost,badpost,notpost,description,filename,uip,lastview,editpwd,showstart,showend,editer,edittime,albumid,albumnum,itype,category,telephone,fax,mobphone,email,oicq,msn,maps,city_id,zone_id,street_id,position,contact,ltdid,linkman,id';
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
		if(!empty($_REQUEST['channel'])){
			$condition['channel']=$_REQUEST['channel'];
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
		$this->display("Archives:ajaxlist");
	}


	public function insert(){
		//整站档案_insert
		$list=D("Archives");
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
		//整站档案_delete
		$list=D("Archives");
		$list->delete($_REQUEST["id"]);
		redirect(__URL__."/index");
	}


	public function update(){
		//整站档案_update
		$list=D("Archives");
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
		//整站档案_edit
		$result=D("Archives");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("typeid",$list["typeid"]);
		$this->assign("typeid2",$list["typeid2"]);
		$this->assign("industry",$list["industry"]);
		$this->assign("bycity",$list["bycity"]);
		$this->assign("cid",$list["cid"]);
		$this->assign("uid",$list["uid"]);
		$this->assign("flag",$list["flag"]);
		$this->assign("ismake",$list["ismake"]);
		$this->assign("channel",$list["channel"]);
		$this->assign("arcrank",$list["arcrank"]);
		$this->assign("click",$list["click"]);
		$this->assign("title",$list["title"]);
		$this->assign("shorttitle",$list["shorttitle"]);
		$this->assign("color",$list["color"]);
		$this->assign("writer",$list["writer"]);
		$this->assign("source",$list["source"]);
		$this->assign("litpic",$list["litpic"]);
		$this->assign("pubdate",$list["pubdate"]);
		$this->assign("senddate",$list["senddate"]);
		$this->assign("keywords",$list["keywords"]);
		$this->assign("lastpost",$list["lastpost"]);
		$this->assign("star1",$list["star1"]);
		$this->assign("star2",$list["star2"]);
		$this->assign("star3",$list["star3"]);
		$this->assign("star4",$list["star4"]);
		$this->assign("star5",$list["star5"]);
		$this->assign("goodpost",$list["goodpost"]);
		$this->assign("badpost",$list["badpost"]);
		$this->assign("notpost",$list["notpost"]);
		$this->assign("description",$list["description"]);
		$this->assign("filename",$list["filename"]);
		$this->assign("uip",$list["uip"]);
		$this->assign("lastview",$list["lastview"]);
		$this->assign("editpwd",$list["editpwd"]);
		$this->assign("showstart",$list["showstart"]);
		$this->assign("showend",$list["showend"]);
		$this->assign("editer",$list["editer"]);
		$this->assign("edittime",$list["edittime"]);
		$this->assign("albumid",$list["albumid"]);
		$this->assign("albumnum",$list["albumnum"]);
		$this->assign("itype",$list["itype"]);
		$this->assign("category",$list["category"]);
		$this->assign("telephone",$list["telephone"]);
		$this->assign("fax",$list["fax"]);
		$this->assign("mobphone",$list["mobphone"]);
		$this->assign("email",$list["email"]);
		$this->assign("oicq",$list["oicq"]);
		$this->assign("msn",$list["msn"]);
		$this->assign("maps",$list["maps"]);
		$this->assign("city_id",$list["city_id"]);
		$this->assign("zone_id",$list["zone_id"]);
		$this->assign("street_id",$list["street_id"]);
		$this->assign("position",$list["position"]);
		$this->assign("contact",$list["contact"]);
		$this->assign("ltdid",$list["ltdid"]);
		$this->assign("linkman",$list["linkman"]);

		$this->display();
	}


	public function view(){
		//整站档案_view
		$result=D("Archives");
		$list=$result->getById($_GET['id']);
		$this->assign('list',$list);
				$this->assign("id",$list["id"]);
		$this->assign("typeid",$list["typeid"]);
		$this->assign("typeid2",$list["typeid2"]);
		$this->assign("industry",$list["industry"]);
		$this->assign("bycity",$list["bycity"]);
		$this->assign("cid",$list["cid"]);
		$this->assign("uid",$list["uid"]);
		$this->assign("flag",$list["flag"]);
		$this->assign("ismake",$list["ismake"]);
		$this->assign("channel",$list["channel"]);
		$this->assign("arcrank",$list["arcrank"]);
		$this->assign("click",$list["click"]);
		$this->assign("title",$list["title"]);
		$this->assign("shorttitle",$list["shorttitle"]);
		$this->assign("color",$list["color"]);
		$this->assign("writer",$list["writer"]);
		$this->assign("source",$list["source"]);
		$this->assign("litpic",$list["litpic"]);
		$this->assign("pubdate",$list["pubdate"]);
		$this->assign("senddate",$list["senddate"]);
		$this->assign("keywords",$list["keywords"]);
		$this->assign("lastpost",$list["lastpost"]);
		$this->assign("star1",$list["star1"]);
		$this->assign("star2",$list["star2"]);
		$this->assign("star3",$list["star3"]);
		$this->assign("star4",$list["star4"]);
		$this->assign("star5",$list["star5"]);
		$this->assign("goodpost",$list["goodpost"]);
		$this->assign("badpost",$list["badpost"]);
		$this->assign("notpost",$list["notpost"]);
		$this->assign("description",$list["description"]);
		$this->assign("filename",$list["filename"]);
		$this->assign("uip",$list["uip"]);
		$this->assign("lastview",$list["lastview"]);
		$this->assign("editpwd",$list["editpwd"]);
		$this->assign("showstart",$list["showstart"]);
		$this->assign("showend",$list["showend"]);
		$this->assign("editer",$list["editer"]);
		$this->assign("edittime",$list["edittime"]);
		$this->assign("albumid",$list["albumid"]);
		$this->assign("albumnum",$list["albumnum"]);
		$this->assign("itype",$list["itype"]);
		$this->assign("category",$list["category"]);
		$this->assign("telephone",$list["telephone"]);
		$this->assign("fax",$list["fax"]);
		$this->assign("mobphone",$list["mobphone"]);
		$this->assign("email",$list["email"]);
		$this->assign("oicq",$list["oicq"]);
		$this->assign("msn",$list["msn"]);
		$this->assign("maps",$list["maps"]);
		$this->assign("city_id",$list["city_id"]);
		$this->assign("zone_id",$list["zone_id"]);
		$this->assign("street_id",$list["street_id"]);
		$this->assign("position",$list["position"]);
		$this->assign("contact",$list["contact"]);
		$this->assign("ltdid",$list["ltdid"]);
		$this->assign("linkman",$list["linkman"]);

		$this->display();
	}


	public function add(){
		//整站档案_add
		$this->display();
	}

}
?>
