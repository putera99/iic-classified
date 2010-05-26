$(document).ready(function(){
	$(".ch").mouseover(function(){
		$(".ch").removeClass('on');
		$('#'+this.id).addClass('on');
		var id="#ch_info_"+this.id.substr(-1,1);
		$(".cinfo").hide();
		$(id).show(); 
	});
	
	$(".s_cf").click(function(){
		if($("."+this.id).attr("rel")!='on'){
			$("."+this.id).attr("rel",'on');
			$("."+this.id).show();
		}else{
			$("."+this.id).attr("rel",'non');
			$("."+this.id).hide();
		}
	});
	$(".s_cg").click(function(){
		if($("."+this.id).attr("rel")!='on'){
			$("."+this.id).attr("rel",'on');
			$("."+this.id).show();
		}else{
			$("."+this.id).attr("rel",'non');
			$("."+this.id).hide();
		}
	});
	
	$(".user_collection").click(function(){
		var id=$(".user_collection").attr('rel');
		var types=id.substr(0,1);
		var tid=id.substr(2);
		var tourl=URL+'/user_collection';
		$.post(tourl,{tid:tid,types:types},function(data){
			if(data['status']==1){
				alert(data['info']);
			}else{
				alert(data['info']);
			}
		},'json')
	});
	
	$("#post_comment").click(function(){
		var h=$("#comment_hidden").val();
		var types=h.substr(0,1);
		var tid=h.substr(2);
		var text=$("#comment_content").val();
		var star=$("#click_6").val();
		var verify=$("#verify").val();
		if($.trim(text)==''){
			alert("内容不能为空");
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
					//document.form['post_comment'].reset();
				}else{
					alert(data['info']);
				}
			},'json');
		}
	});
	
	$(".nx").mouseover(function(){
		var x=this.id.substr(-1,1);
		var t={1:'1',2:'2',3:'3',4:'4',5:'5'};
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

function get_comments(xid,types,page){
	$("#comments").load(URL+'/ajax_comments',{xid:xid,types:types});
}
function page(act,mod,xid,types,tags,page){
	$(tags).load(URL+'/'+mod,{xid:xid,types:types,p:page});
}
function fleshVerify(){
	//重载验证码
	var timenow = new Date().getTime();
	$('#verifyImg').attr('src',URL+'/verify/'+timenow);
}

            