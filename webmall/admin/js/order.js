<!--

$(document).ready(function(){

   
   //删除套餐订单
	$(".torderdel").click(function(){
		var orderid=this.id.substr(10);
		$.ajax({
			type: "POST",
			url: "post.php",
			data: "act=torderdel&orderid="+orderid,
			success: function(msg){
				if(msg=="OK"){
					$("#tr_"+orderid).remove();
				}else if(msg=="1001"){
					alert("该套餐订单已付款或已处理完成，不可删除");
					return false;
				}else if(msg=="1002"){
					alert("该套餐订单已捆绑了增购订单，需先删除下属的增购订单");
					return false;
				}else{
					alert(msg);
				}
			}
		});
		
	});

	//删除单项订单
	$(".iorderdel").click(function(){
		var qus=confirm("确定要删除订单吗？")
		if(qus!=0){
			var orderid=this.id.substr(10);
			$.ajax({
				type: "POST",
				url: "post.php",
				data: "act=iorderdel&orderid="+orderid,
				success: function(msg){
					if(msg=="OK"){
						$("#tr_"+orderid).remove();
					}else if(msg=="1001"){
						alert("该订单已付款或已处理完成，不可删除");
						return false;
					}else{
						alert(msg);
					}
				}
			});

		}
		
	});

	/*
	//详情显示切换
	$("#serviceLook").toggle(function(){
			$("#serviceList").show();
			$("#serviceLook")[0].className="sopen";
		},function(){
			$("#serviceList").hide();
			$("#serviceLook")[0].className="sclose";
			
	});
	*/

   

});





-->