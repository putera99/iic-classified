<?php
/**
 +------------------------------------------------------------------------------
 * BlogAction控制器类 输出Blog需要的数据
 +------------------------------------------------------------------------------
 * @category   SubAction
 * @package  app
 * @subpackage  Action
 * @author   朝闻道 <hydata@gmail.com>
 * @date 2011-1-21
 * @time  下午05:17:30
 +------------------------------------------------------------------------------
 */
class BlogAction extends CommonAction {
	
	/**
	 *导出分类信息
	 *@date 2011-1-21 / @time 下午05:24:26
	 */
	function classifieds() {
		//导出分类信息
		import ( "@.Com.Split" );
		$ch = "4,5,6,7,8,9";
		$dao = D ( "Archives" );
		$time = mktime ( 0, 0, 0, date ( 'm' ), date ( "d" ) - 5, date ( "Y" ) );
		//$fields="id,typeid,industry,cid,channel,title,pubdate,my_content,itype,category,telephone,fax,mobphone,email,oicq,msn,maps,city_id,zone_id,street_id,position,contact,ltdid,linkman";
		$data = $dao->where ( "(channel in ($ch) AND ismake=1) AND pubdate>$time" )->field ( '*' )->order ( "pubdate DESC" )->limit ( "0,30" )->findAll ();
		$city = $this->_get_city ( 'localion' );
		$arr = array ();
		for($i = 0; $i < count ( $data ); $i ++) {
			$info = array ();
			$info = $data [$i];
			$content = '';
			switch (true) {
				case $info ['channel'] == 4 : //Jobs
					$AddonJobs = M ( "AddonJobs" );
					$info ['_jobs'] = $AddonJobs->where ( "aid={$info['id']}" )->find ();
					$info ['itype'] = $info ['itype'] == '0' ? 'All' : $info ['itype'] == '1' ? 'Offered' : 'Wanted';
					$category = array ("All", "Full time", "Part time", "Internship", "Voluntary" );
					$info ['category'] = $category [$info ['category']];
					
					$arr [$i] ['post_title'] = $info ['title']; //标题
					$content = "Type:{$info['itype']}<br>
								Category:{$info['category']}<br>
								Valid for:" . toDate ( $info ['showstart'], 'd/m/Y' ) . "--" . toDate ( $info ['showend'], 'd/m/Y' ) . "<br>
								Position:{$info['position']}<br>
								Salary:{$info['_jobs']['salary']} RMB<br>
								Experience:{$info['_jobs']['experience']} months<br>
								Job Located in:{$info['_jobs']['joblocated']}<br>";
					$content .= $info ['_jobs'] ['content'];
					$content .= "<br/><br/>Source from:<a href='http://www.beingfunchina.com/clss/{$info['id']}/" . str_to_url ( $info ['title'] ) . ".html' target='_blank'>{$info['title']}</a>";
					$arr [$i] ['content'] = $content; //内容
					$arr [$i] ['excerpt'] = $info ['my_content']; //摘要
					$str = $info ['title'] . ' ' . $info ['my_content'];
					$split = new Split ( $str );
					$str = $split->get_tags ();
					$arr [$i] ['tags_input'] = $str; //标签
					$arr [$i] ['post_category'] [] = 3; //分类
					$arr [$i] ['ping_status'] = 'open'; //开启ping
					$arr [$i] ['comment_status'] = 'open'; //开启评论
					

					break;
				
				case $info ['channel'] == 5 : //realestate
					$AddonRealestate = M ( "AddonRealestate" );
					$info ['_realestate'] = $AddonRealestate->where ( "aid={$info['id']}" )->find ();
					
					$arr [$i] ['post_title'] = $info ['title']; //标题
					$content = "Category:{$info['published']}<br>
								Valid for:" . toDate ( $info ['showstart'], 'd/m/Y' ) . "--" . toDate ( $info ['showend'], 'd/m/Y' ) . "<br>
								Located in:{$info['position']}<br>
								Price:{$info['_realestate']['price']}<br>
								Rooms:{$info['_realestate']['rooms']}<br>
								Size:{$info['_realestate']['size']}<br>
								Published by:{$info['_realestate']['published']}<br>";
					$content .= $info ['_realestate'] ['content'];
					$content .= "<br/><br/>Source from:<a href='http://www.beingfunchina.com/clss/{$info['id']}/" . str_to_url ( $info ['title'] ) . ".html' target='_blank'>{$info['title']}</a>";
					$arr [$i] ['content'] = $content; //内容
					$arr [$i] ['excerpt'] = $info ['my_content']; //摘要
					$str = $info ['title'] . ' ' . $info ['my_content'];
					$split = new Split ( $str );
					$str = $split->get_tags ();
					$arr [$i] ['tags_input'] = $str; //标签
					$arr [$i] ['post_category'] [] = 1; //分类
					$arr [$i] ['ping_status'] = 'open'; //开启ping
					$arr [$i] ['comment_status'] = 'open'; //开启评论
					break;
				
				case $info ['channel'] == 6 : //commerce
					$types = array (1 => 'All', 2 => 'Offered', 3 => 'Wanted' );
					$cat = array ('Brand-new', 'Second-hand' );
					$info ['itype'] = $types [$info ['type']];
					$info ['category'] = $cat [$info ['category']];
					$AddonCommerce = M ( "AddonCommerce" );
					$info ['_commerce'] = $AddonCommerce->where ( "aid={$info['id']}" )->find ();
					
					$arr [$i] ['post_title'] = $info ['title']; //标题
					$content = "Category:{$info['category']}<br>
								Condition:{$info['itype']}<br>
								Quantity:{$info['_commerce']['quantity']}<br>
								Valid for:" . toDate ( $info ['showstart'], 'd/m/Y' ) . "--" . toDate ( $info ['showend'], 'd/m/Y' ) . "<br>
								My Location:{$info['position']}<br>
								Price:{$info['_commerce']['price']}<br>";
					$content .= $info ['_commerce'] ['content'];
					$content .= "<br/><br/>Source from:<a href='http://www.beingfunchina.com/clss/{$info['id']}/" . str_to_url ( $info ['title'] ) . ".html' target='_blank'>{$info['title']}</a>";
					$arr [$i] ['content'] = $content; //内容
					$arr [$i] ['excerpt'] = $info ['my_content']; //摘要
					$str = $info ['title'] . ' ' . $info ['my_content'];
					$split = new Split ( $str );
					$str = $split->get_tags ();
					$arr [$i] ['tags_input'] = $str; //标签
					$arr [$i] ['post_category'] [] = 5; //分类
					$arr [$i] ['ping_status'] = 'open'; //开启ping
					$arr [$i] ['comment_status'] = 'open'; //开启评论
					break;
				
				case $info ['channel'] == 7 : //agents
					$AddonAgents = M ( "AddonAgents" );
					$info ['_agents'] = $AddonAgents->where ( "aid={$info['id']}" )->find ();
					$arr [$i] ['post_title'] = $info ['title']; //标题
					$content = "My location:{$info['position']}<br>
								Valid for:" . toDate ( $info ['showstart'], 'd/m/Y' ) . "--" . toDate ( $info ['showend'], 'd/m/Y' ) . "<br>";
					$content .= $info ['_agents'] ['content'];
					$content .= "<br/><br/>Source from:<a href='http://www.beingfunchina.com/clss/{$info['id']}/" . str_to_url ( $info ['title'] ) . ".html' target='_blank'>{$info['title']}</a>";
					$arr [$i] ['content'] = $content; //内容
					$arr [$i] ['excerpt'] = $info ['my_content']; //摘要
					$str = $info ['title'] . ' ' . $info ['my_content'];
					$split = new Split ( $str );
					$str = $split->get_tags ();
					$arr [$i] ['tags_input'] = $str; //标签
					$arr [$i] ['post_category'] [] = 5; //分类
					$arr [$i] ['ping_status'] = 'open'; //开启ping
					$arr [$i] ['comment_status'] = 'open'; //开启评论
					break;
				
				case $info ['channel'] == 8 : //personals
					$AddonPersonals = M ( "AddonPersonals" );
					$info ['_personals'] = $AddonPersonals->where ( "aid={$info['id']}" )->find ();
					
					$arr [$i] ['post_title'] = $info ['title']; //标题
					$content = "My location:{$info['position']}<br>
								Valid for:" . toDate ( $info ['showstart'], 'd/m/Y' ) . "--" . toDate ( $info ['showend'], 'd/m/Y' ) . "<br>";
					$content .= $info ['_personals'] ['content'];
					$content .= "<br/><br/>Source from:<a href='http://www.beingfunchina.com/clss/{$info['id']}/" . str_to_url ( $info ['title'] ) . ".html' target='_blank'>{$info['title']}</a>";
					$arr [$i] ['content'] = $content; //内容
					$arr [$i] ['excerpt'] = $info ['my_content']; //摘要
					$str = $info ['title'] . ' ' . $info ['my_content'];
					$split = new Split ( $str );
					$str = $split->get_tags ();
					$arr [$i] ['tags_input'] = $str; //标签
					$arr [$i] ['post_category'] [] = 6; //分类
					$arr [$i] ['ping_status'] = 'open'; //开启ping
					$arr [$i] ['comment_status'] = 'open'; //开启评论
					break;
				
				case $info ['channel'] == 9 : //services
					$info ['itype'] = $info ['itype'] == 1 ? 'All' : $info ['itype'] == 2 ? 'Offered' : 'Wanted';
					$category = array ("All", "Full time", "Part time", "Internship", "Voluntary" );
					$info ['category'] = $category [$info ['category']];
					$AddonServices = M ( "AddonServices" );
					$info ['_services'] = $AddonServices->where ( "aid={$info['id']}" )->find ();
					
					$arr [$i] ['post_title'] = $info ['title']; //标题
					$content = "Type:{$info['itype']}<br>
								Category:{$info['category']}<br>
								Service located in:{$info['position']}<br>
								Contact:{$info['contact']} / {$info['linkman']}<br>
								Valid for:" . toDate ( $info ['showstart'], 'd/m/Y' ) . "--" . toDate ( $info ['showend'], 'd/m/Y' ) . "<br>";
					$content .= $info ['_services'] ['content'];
					$content .= "<br/><br/>Source from:<a href='http://www.beingfunchina.com/clss/{$info['id']}/" . str_to_url ( $info ['title'] ) . ".html' target='_blank'>{$info['title']}</a>";
					$arr [$i] ['content'] = $content; //内容
					$arr [$i] ['excerpt'] = $info ['my_content']; //摘要
					$str = $info ['title'] . ' ' . $info ['my_content'];
					$split = new Split ( $str );
					$str = $split->get_tags ();
					$arr [$i] ['tags_input'] = $str; //标签
					$arr [$i] ['post_category'] [] = 4; //分类
					$arr [$i] ['ping_status'] = 'open'; //开启ping
					$arr [$i] ['comment_status'] = 'open'; //开启评论
					break;
			}
		}
		echo serialize ( $arr );
	
	} //end classifieds
	

	/**
	 *导出城市指南
	 *@date 2011-1-24 / @time 上午11:31:58
	 */
	function cityguide() {
		//导出城市指南
		import ( "@.Com.Split" );
		$dao = D ( "Archives" );
		$AddonArticle = M ( "AddonArticle" );
		$time = mktime ( 0, 0, 0, date ( 'm' ), date ( "d" ) - 50, date ( "Y" ) );
		//$fields="id,typeid,industry,cid,channel,title,pubdate,my_content,itype,category,telephone,fax,mobphone,email,oicq,msn,maps,city_id,zone_id,street_id,position,contact,ltdid,linkman";
		$data = $dao->where ( "(channel=2 AND ismake=1) AND (pubdate>$time or edittime>$time)" )->field ( '*' )->order ( "pubdate DESC" )->limit ( "0,30" )->findAll ();
		//dump($dao->getlastsql());
		$count = count ( $data );
		$city = $this->_get_city ( 'localion' );
		$arr = array ();
		
		for($i = 0; $i < $count; $i ++) {
			$info = array ();
			$info = $data [$i];
			$info ['content'] = $AddonArticle->where ( "aid='{$info['id']}'" )->find ();
			$cname = array ('bj' => 'Beijing', 'sh' => 'Shanghai', 'gz' => 'Guangzhou', 'sz' => 'Shenzhen' );
			$local = '';
			if ($info ['zone_id'] && $info ['city_id']) {
				$local = $cname [$city [$info ['city_id']] ['cename']] . ' ' . $city [$info ['city_id']] ['_zone'] [$info ['zone_id']] ['name'] . ' District';
			} elseif (empty ( $info ['zone_id'] ) && $info ['city_id']) {
				$local = $city [$info ['city_id']] ['cename'];
			}
			$local = trim ( $info ['position'] . ',' . $local, ',' );
			$info ['maps'] = $info ['contact'];
			
			$arr [$i] ['post_title'] = $info ['title']; //标题
			$content = $info ['my_content'] . $info ['content'] ['content'];
			$content .= "<br/><br/>Source from:<a href='http://www.beingfunchina.com/ctgs/{$info['id']}/" . str_to_url ( $info ['title'] ) . ".html' target='_blank'>{$info['title']}</a>";
			$arr [$i] ['content'] = $content; //内容
			$arr [$i] ['excerpt'] = $info ['my_content']; //摘要
			$str = $info ['title'] . ' ' . $info ['my_content'];
			$split = new Split ( $str );
			$str = $split->get_tags ();
			$arr [$i] ['tags_input'] = $str; //标签
			$big_cat = self::big_cat ( $info ['typeid'] );
			$cat = '';
			switch ($big_cat) {
				case 1001 : //Arts&Culture
					$cat = '1';
					break;
				case 1002 : //Bars
					$cat = '3';
					break;
				case 1006 : //Education
					$cat = '4';
					break;
				case 1007 : //Hotel
					$cat = '5';
					break;
				case 1008 : //Leisure
					$cat = '6';
					break;
				case 1010 : //Restaurant
					$cat = '7';
					break;
				case 1011 : //Shopping
					$cat = '8';
					break;
				case 1108 : //Consulates
					$cat = '9';
					break;
				case 1133 : //Health
					$cat = '10';
					break;
				case 1135 : //Banking
					$cat = '11';
					break;
				case 1145 : //cafe
					$cat = '12';
					break;
				case 1333 : //Chamber Of Commerce
					$cat = '13';
					break;
				case 1393 : //Web Resources
					$cat = '15';
					break;
				case 3004 : //Services
					$cat = '14';
					break;
				default :
					$cat = '15';
					break;
			}
			$arr [$i] ['post_category'] [] = $cat; //分类
			$arr [$i] ['ping_status'] = 'open'; //开启ping
			$arr [$i] ['comment_status'] = 'open'; //开启评论
		}
		echo serialize ( $arr );
	} //end cityguide
	

	/**
	 *导出magazine
	 *@date 2011-1-25 / @time 下午05:57:55
	 */
	function magazine() {
		//导出magazine
		import ( "@.Com.Split" );
		$dao = D ( "Archives" );
		$AddonArc = M ( "AddonArc" );
		$typeid=3001;
		$condition = array ();
		$arctype=D("Arctype");
		$info=$arctype->where("id=$typeid")->find();
		
		if($info['ispart']==1){
			$small=$arctype->where("reid=$typeid")->field("id")->findAll();
			$str='';
			foreach ($small as $v){
				$str.=$v['id'].',';
			}
			$condition['typeid']=array('IN',trim($str,','));
		}else{
			$condition['typeid']=$typeid;
		}
		$time=mktime(0,0,0,date('m'),date("d")-5,date("Y"));
		
		$condition ['channel'] = 12;
		$condition ['ismake'] = 1;
		$condition['pubdate']=array('gt',$time);
		$condition['edittime']=array('gt',$time);
		$data = $dao->where( $condition )->findAll ();
		//dump($dao->getLastSql());
		$arr = array ();
		$i=0;
		foreach ( $data as $v ) {
			if (! empty ( $v['reurl'] ) && $v ['reurl'] != 'http://') {
				continue;
			}else{
				$v['content'] = $AddonArc->where("aid={$v['id']}")->find();
				$str = $v ['title'] . ' ' . $v ['my_content'];
				$split = new Split ( $str );
				$str = $split->get_tags ();
				$arr [$i] ['post_title'] = $v ['title']; //标题
				$content= $v['content'] ['content'];
				$content .= "<br/><br/>Source from:<a href='http://www.beingfunchina.com/article/{$v['id']}/" . str_to_url ( $v ['title'] ) . ".html' target='_blank'>{$v['title']}</a>";
				$content .="<br/><br/>$str";
				$arr [$i] ['content'] = $content; //内容
				$arr [$i] ['excerpt'] = $v ['my_content']; //摘要
				
				$arr [$i] ['tags_input'] = $str; //标签
				$arr [$i] ['post_category'] [] = 1; //分类
				$arr [$i] ['ping_status'] = 'open'; //开启ping
				$arr [$i] ['comment_status'] = 'open'; //开启评论
				$i++;
			}
			
		}
		echo serialize ( $arr );
	} //end magazine
	

	/**
	 *测试XML
	 *@date 2011-1-22 / @time 上午11:12:03
	 */
	function xml() {
		//测试XML
		$xml = alivv_ad_helper ( 'http://www.beingfunchina.com/Blog/cityguide' );
		dump ( unserialize ( $xml ) );
	} //end xml
	

	/**
	 *查出信息分类所在大类
	 *@date 2011-1-25 / @time 下午04:17:33
	 */
	function big_cat($cat) {
		//查出信息分类所在大类
		$return = '';
		$temp = '';
		$cat_arr = array ();
		$cat_arr = S ( "blog_all_cat" );
		if (empty ( $cat_arr )) {
			$cat_arr = $this->_get_tree ( 1000, "id,reid,topid" );
			S ( "blog_all_cat", $cat_arr, 900 );
		}
		//dump($cat_arr);
		foreach ( $cat_arr as $v ) {
			$temp = $v ['id'];
			if ($cat == $v ['id']) {
				$return = $temp;
			} else {
				foreach ( $v as $vs ) {
					if ($cat == $vs ['id']) {
						$return = $temp;
					} else {
						foreach ( $vs as $vss ) {
							if ($cat == $vss ['id']) {
								$return = $temp;
							}
						}
					}
				}
			}
		}
		return $return;
	} //end big_cat
}//end BlogAction

