$(document).ready(function(){
	var login=APP+'Public/ajax_is_login';
	$("#remember").click(function(){

		if($("#remember").attr('class')=='remember_0'){
			for(var i=0;i<4;i++){
				var x=$(".top-city>h2:eq("+i+")>a");
				var r=x.attr('href')+'/remember/1'
				x.attr("href",r);
			}
			$("#remember").attr('src','/Public/img/es2.gif');
			$("#remember").attr('class','remember_1')
		}else{
			for(var i=0;i<4;i++){
				var x=$(".top-city>h2:eq("+i+")>a");
				var r=x.attr('href').replace('/remember/1','');
				x.attr("href",r);
			}
			$("#remember").attr('src','/Public/img/es.gif');
			$("#remember").attr('class','remember_0');
		}
	});
	$(".ch").mouseover(function(){
		$(".ch").removeClass('on');
		$('#'+this.id).addClass('on');
		var id="#ch_info_"+this.id.substring(9,10);
		$(".cinfo").hide();
		$(id).show(); 
	});
	
	//城市指南导航切换
	$(".s_cf").click(function(){
		if($("."+this.id).attr("rel")!='on'){
			$("."+this.id).attr("rel",'on');
			$("#"+this.id+' dt a').addClass('on');
			$("."+this.id).show();
		}else{
			$("."+this.id).attr("rel",'non');
			$("#"+this.id+' dt a').removeClass('on');
			$("."+this.id).hide();
		}
	});
	//分类信息导航切换
	$(".s_cg").click(function(){
		if($("."+this.id).attr("rel")!='on'){
			$("."+this.id).attr("rel",'on');
			$("#"+this.id+' dt a').addClass('on');
			$("."+this.id).show();
		}else{
			$("."+this.id).attr("rel",'non');
			$("#"+this.id+' dt a').removeClass('on');
			$("."+this.id).hide();
		}
	});
	
	//用户私人收藏
	$(".user_collection").click(function(){
		//var id=$(".user_collection").attr('rel');
		var id=this.id;
		//var types=id.substr(2,1);
		//var tid=id.substr(4);
		var tourl=URL+'/user_collection';
		$.post(tourl,{id:id},function(data){
			if(data['status']==1){
				alert(data['info']);
			}else{
				alert(data['info']);
			}
		},'json');
	});
	
	//分享资源
//	$(".user_share").click(function(){
//		var id=this.id;
//		var types=id.substr(2,1);
//		var tid=id.substr(4);
//		$.post(login,'',function(data){
//			if(data['status']==1){
//				$('#group_content').load(URL+'/ajax_group');
//				//$('#group_content').modal();
//			}else{
//				alert(data['info']);
//				//$('#login').modal();
//			}
//		},'json');
//	});
	
	//发送评论
	$("#post_comment").click(function(){
		var h=$("#comment_hidden").val();
		var arr= new Array();  
		arr=h.split('_');
		var types=arr[0];//h.substr(0,1);
		var tid=arr[1];//h.substr(2);
		var text=$("#comment_content").val();
		var star=$("#click_6").val();
		var verify=$("#verify").val();
		if($.trim(text)==''){
			alert("Lake of comment!");
		}else{
			var tourl=URL+'/post_comment';
			var ken=$("input[name='IIC_CN']").val();
			$.post(tourl,{xid:tid,types:types,message:text,IIC_CN:ken,click_6:star,verify:verify},function(data){
				if(data['status']==1){
					$("input[name='IIC_CN']").val(' ');
					ken='';
					alert(data['info']);
					get_comments(tid,types);
					$("#comment_content").val('');
					fleshVerify();
					//document.form['post_comment'].reset();
				}else{
					alert(data['info']);
				}
			},'json');
		}
	});
	
	//滑星
	$(".nx").mouseover(function(){
		var x=this.id.substr(-1,1);
		var t={1:'Sucks',2:'Bad',3:'Fine',4:'Good',5:'Awesome'};
		for(var i=1;i<6;i++){
			var id="#star_"+i;
			if(i<=x){
				$(id).attr("class",'x');
			}else{
				$(id).attr("class",'nx');
			}
		}
		$("#click_6").val(x);
		$("#star_text").html(t[x]);
	});
	
	
});

//载入评论
function get_comments(xid,types,page){
	$("#comments").load(URL+'/ajax_comments',{xid:xid,types:types});
}

//ajax翻页
function page(act,mod,xid,types,tags,page){
	$(tags).load(URL+'/'+mod,{xid:xid,types:types,p:page});
}

//重载验证码
function fleshVerify(){
	var timenow = new Date().getTime();
	$('#verifyImg').attr('src',URL+'/verify/'+timenow);
}

            