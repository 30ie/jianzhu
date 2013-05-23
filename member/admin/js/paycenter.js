
$(document).ready(function(){
	
	$(".pcentertype").click(function(){
		
		var selptype=$(this)[0].value;
		if(selptype==1){
			alert("当前版本暂不支持本功能");
			return false;
			//$(".tronline").show();
		}else{
			$(".tronline").hide();
		}
	});



});