<!--

$(document).ready(function(){

	//主订单付款
	$("input.payorder").click(function(){
		var buttonvar=$(this)[0].value;
		if(buttonvar=="已付款"){
			alert("该订单已经付过款了");
			return false;
		}
		var qus=confirm("确定要为此订单付款吗?按确定将从您的会员帐户扣款支付此订单");
		if(qus!=0){
			var torderid=this.id.substr(9);
			$.ajax({
				type: "POST",
				url:PDV_RP+"webmall/post.php",
				data: "act=payorder&torderid="+torderid,
				success: function(msg){
					if(msg=="OK"){
						alert("订单付款成功");
						window.location.reload();
					}else if(msg=="1000"){
						alert("套餐订单不存在");
					}else if(msg=="1001"){
						alert("该订单已经付过款了");
					}else if(msg=="1002"){
						alert("您的会员帐户余额不足");
					}else{
						alert(msg);
					}
				}
			});
		}
	});


	//附属订单付款
	$("input.payiorder").click(function(){
		var buttonvar=$(this)[0].value;
		if(buttonvar=="已付款"){
			alert("该订购项目已经付过款了");
			return false;
		}
		var qus=confirm("确定要为此订购项目付款吗?按确定将从您的会员帐户扣款支付此订购项目");
		if(qus!=0){
			var iorderid=this.id.substr(10);
			$.ajax({
				type: "POST",
				url:PDV_RP+"webmall/post.php",
				data: "act=payiorder&iorderid="+iorderid,
				success: function(msg){
					if(msg=="OK"){
						alert("付款成功");
						window.location.reload();
					}else if(msg=="1000"){
						alert("订购项目不存在");
					}else if(msg=="1001"){
						alert("该订购项目已经付过款了");
					}else if(msg=="1005"){
						alert("产品套餐尚未付款，不能单独给自选订购付款");
					}else if(msg=="1002"){
						alert("您的会员帐户余额不足");
					}else{
						alert(msg);
					}
				}
			});
		}
	});


	//套餐订单服务续费
	$("input#xusub").click(function(){

		var qus=confirm("续订服务将从您的会员帐户扣除相应费用，确实要续订服务吗？");
		if(qus!=0){
			var xuyears=$("#xuyears")[0].value;
			var torderid=$("#orderid")[0].value;
				$.ajax({
					type: "POST",
					url:PDV_RP+"webmall/post.php",
					data: "act=xufei&xuyears="+xuyears+"&torderid="+torderid,
					success: function(msg){
						if(msg=="OK"){
							alert("服务续订成功");
							window.location.reload();
						}else if(msg=="1000"){
							alert("订单不存在");
						}else if(msg=="1001"){
							alert("网站产品不存在");
						}else if(msg=="1003"){
							alert("至少续订一年服务");
						}else if(msg=="1005"){
							alert("您的会员帐户余额不足");
						}else{
							alert(msg);
						}
					}
				});
		 }
	});


	//模块增加订购
	$(".addbuymod").click(function(){
		var addmodid=this.id.substr(10);
		var torderid=$("#orderid")[0].value;
		$.ajax({
			type: "POST",
			url:PDV_RP+"webmall/post.php",
			data: "act=addbuymod&addmodid="+addmodid+"&torderid="+torderid,
			success: function(msg){
				if(msg=="OK"){
					alert("模块增购订单提交成功，请在订购记录中进行付款确认");
					window.location.reload();
				}else if(msg=="1000"){
					alert("主订单不存在");
				}else if(msg=="1001"){
					alert("模块不存在");
				}else if(msg=="1002"){
					alert("该模块已经购买过了");
				}else if(msg=="1003"){
					alert("该模块已经提交过订单了");
				}else{
					alert(msg);
				}
			}
		});
	});


});





-->