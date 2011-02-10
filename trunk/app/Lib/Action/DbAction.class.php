<?php
/**
 +------------------------------------------------------------------------------
 * DbAction控制器类
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  app
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2010-6-14
 * @time  上午09:28:41
 +------------------------------------------------------------------------------
 */
class DbAction extends CommonAction{
	
	/**
	 *index
	 *@date 2010-6-19
	 *@time 下午03:22:17
	 */
	function index() {
		//index
		$dao=D("Arctype");
		$data=$dao->where("reid=1232")->findAll();
		foreach ($data as $v){
			//echo "SELECT * FROM `iic_archives` WHERE `typeid`={$v['id']} and channel=2;<br>";
			echo "DELETE FROM `iic_archives` WHERE `typeid`={$v['id']} and channel=2;<br>";
		}
		echo "<br><br>";
		$data=$dao->where("reid=1388")->findAll();
		foreach ($data as $v){
			//echo "SELECT * FROM `iic_archives` WHERE `typeid`={$v['id']} and channel=2;<br>";
			echo "DELETE FROM `iic_archives` WHERE `typeid`={$v['id']} and channel=2;<br>";
		}
	}//end index
	
	/**
	 *获取栏目列表
	 *@date 2010-6-14
	 *@time 上午09:29:47
	 */
	function arctype() {
		//获取栏目列表
		$old=M("Arctype");
		$list=$old->where("topid=1000")->field('id,reid,typename')->order("id asc")->findAll();
		$news=list_to_tree($list,'id','reid','_son','1000');
		//dump($news);
		$this->assign('news',$news);
		$this->display();
	}//end arctype
	
	/**
	 *更改栏目关系
	 *@date 2010-6-14
	 *@time 上午09:28:55
	 */
	function chreid() {
		//更改栏目关系
		
		$old_reid='';
		foreach ($_POST['old_reid'] as $v){
			$old_reid.=$v.',';
		}
		$old_reid=trim($old_reid,',');
		$type=new Model();
		if($_POST['arc']=='1'){
			$sql="UPDATE `iic_arctype` SET `reid` =  '{$_POST['new_id']}' WHERE `id` in ($old_reid);";
			//$return=$type->execute($sql);
			echo "<p>";
			dump($sql);
			if(!empty($_POST['new_cid'])){
				$sql="UPDATE `iic_archives` SET `cid` = '{$_POST['new_cid']}' WHERE `typeid` in ($old_reid);";
				//$return=$type->execute($sql);
				echo "<p>";
				dump($sql);
				echo "</p>";
			}
			echo "</p>";
		}else{
			$sql="UPDATE `iic_archives` SET `cid` = '{$_POST['new_cid']}',`typeid` = '{$_POST['new_id']}' WHERE `typeid` in ($old_reid);";
			//$return=$type->execute($sql);
			echo "<p>";
			dump($sql);
			echo "</p>";
		}

	}//end chreid
	
	/**
		 *检查分类下的文章，并删除空的分类
		 *@date 2010-6-14
		 *@time 下午03:32:43
		 */
		function del(){
			//检查分类下的文章，并删除空的分类
			$t=D("Arctype");
			$a=D("Archives");
			dump($_POST);
			echo "<br><br><br>";
			foreach ($_POST['old_reid'] as $v){
				$son='';
				$son=$t->where("reid={$v}")->field("id")->findAll();

				if(empty($son)){
					$data='';
					$data=$a->where("typeid={$v}")->count();
					$i=0;
					if($data=='0'){
						//echo "{$v}可以删除<br>";
					}else{
						$i++;
						echo "{$v}不可以删除<br>";
					}
					if($i==0){
						//$t->where("id={$v}")->delete();
						$sql="DELETE FROM `iic_arctype` WHERE `id` = {$v};<br>";
						echo $sql;
					}
				}else{
					$i=0;
					$arr=array();
					foreach ($son as $vs){
						$data='';
						$data=$a->where("typeid={$vs['id']}")->count();
						if($data=='0'){
							//echo "{$vs['id']}可以删除<br>";
							$arr[]=$vs['id'];
						}else{
							$i++;
							echo "{$vs['id']}不可以删除<br>";
						}
					}
					if($i==0){
						foreach ($arr as $ar){
							$sql="DELETE FROM `iic_arctype` WHERE `id` = {$ar};<br>";
							echo $sql;
						}
						$sql="DELETE FROM `iic_arctype` WHERE `id` = {$v};<br>";
						echo $sql;
						//$t->where("id={$v}")->delete();
					}else{
						echo "{$v}不可以删除<br>";
					}
				}
			}//foreach
			
		}//end del
		
	/**
	   *生成文章关键字
	   *@date 2011-1-19 / @time 上午11:34:30
	   */
	function ctags() {
		//生成文章关键字
		$arc=D("Archives");
		import ("@.Com.Split");
		$str='';
		$split=new Split($str);
		$str=$split->get_tags();
		$tags=D("Tags");
		$tagslink=D("TagsLink");
	}//end ctags
}//end DbAction