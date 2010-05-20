$(document).ready(function(){
	$(".ch").mouseover(function(){
		$(".ch").removeClass('on');
		$('#'+this.id).addClass('on');
		var id="#ch_info_"+this.id.substr(-1,1);
		$(".cinfo").hide();
		$(id).show("slow"); 
		
	});
});

            