$(document).ready(function(){
	$(".ch").mouseover(function(){
		$(".ch").removeClass('on');
		$('#'+this.id).addClass('on');
		var id="#ch_info_"+this.id.substr(-1,1);
		$(".cinfo").hide();
		$(id).show("slow"); 
	});
	
	$(".s_cf").hover(function(){
		$("."+this.id).show("fast");
	},function(){
		$("."+this.id).hide("fast");
	});
	$(".s_cg").hover(function(){
		$("."+this.id).show("fast");
	},function(){
		$("."+this.id).hide("fast");
	});
	
	$(".user_collection").click(function(){
		var types=this.id.substr(0,1);
		var tid=this.id.substr(2);
		var tourl=URL+'/user_collection';
		$.post(tourl,{tid:tid,types:types},function(data){
			if(data['status']==1){
				alert(data['info']);
			}else{
				alert(data['info']);
			}
		},'json')
	});
});

            