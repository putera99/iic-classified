<?php
class IndexAction extends PublicAction{

	public function index(){
		//总体的
		 $this->display();
	}

	public function left(){
		//子模板的左边页面
		$table=D('Apptree');
		$pid=$_GET['apptreeid'];
		if($pid){
			$leftdata=$table->where("type='0' and id ='$pid'")->findAll();
			foreach($leftdata as $k=>$v){
				$leftdata[$k]['subapp']=$table->where(" pid ={$v['id']}")->field('*')->order('seqNO ASC')->findall();
			}
			$this->assign('left',$leftdata);
			//dump($leftdata);
			//$this->display();
			$this->assign('url',$leftdata['link']);
		}else{
			$this->assign('index','index');
		}
		$this->display("Public:menu");
	}

	public function mainframe(){
		$info = array(
            '操作系统:'=>PHP_OS,
            '运行环境:'=>$_SERVER["SERVER_SOFTWARE"],
            'PHP运行方式:'=>php_sapi_name(),
            '上传附件限制:'=>ini_get('upload_max_filesize'),
            '执行时间限制:'=>ini_get('max_execution_time').'秒',
            '服务器时间:'=>date("Y年n月j日 H:i:s"),
            '北京时间:'=>gmdate("Y年n月j日 H:i:s",time()+8*3600),
            '服务器域名/IP:'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
            '剩余空间:'=>round((@disk_free_space(".")/(1024*1024)),2).'M',
            'register_globals:'=>get_cfg_var("register_globals")=="1" ? "ON" : "OFF",
            'magic_quotes_gpc:'=>(1===get_magic_quotes_gpc())?'YES':'NO',
            'magic_quotes_runtime:'=>(1===get_magic_quotes_runtime())?'YES':'NO',
            );
        $this->assign('info',$info);
		$this->display();
	}

	public function topframe(){
		//顶部的
		//生成table的树
		$table=D('Apptree');
		$topdate=$table->where('pid =1')->field('*')->order('id ASC')->findall();
		$this->assign('top',$topdate);
		//dump($leftdate);
		$this->display();
	}

	public function midFrame(){
		//中间的
		$this->display();
	}

}



?>
