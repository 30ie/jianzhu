<!--

$(document).ready(function(){



	//自选扩展模块
	$("input.chkmodules").click(function(){
		var modulesid=this.id.substr(8);
		if($("#modules_"+modulesid)[0].checked==true){
			$("#p_m_"+modulesid).html($("#d_m_"+modulesid)[0].value+".00");
		}else{
			$("#p_m_"+modulesid).html("0.00");
		}
		countTotal();
	});


	//自选服务
	$("input.chkexpservice").click(function(){
		var serviceid=this.id.substr(11);
		if($("#expservice_"+serviceid)[0].checked==true){
			$("#p_s_"+serviceid).html($("#d_s_"+serviceid)[0].value+".00");
		}else{
			$("#p_s_"+serviceid).html("0.00");
		}
		countTotal();
	});



	//计算总价
	function countTotal(){
		var baseprice=parseInt($("#baseprice").html());
		var TOT=0;
		$(".chkmodules").each(function(){
			var modulesid=this.id.substr(8);
			if($("#modules_"+modulesid)[0].checked==true){
				TOT+=parseInt($("#d_m_"+modulesid)[0].value);
			}
		});

		$(".chkexpservice").each(function(){
			var serviceid=this.id.substr(11);
			if($("#expservice_"+serviceid)[0].checked==true){
				TOT+=parseInt($("#d_s_"+serviceid)[0].value);
			}
		});



		var totalprice=baseprice+TOT;
		$("#totalprice").html(totalprice+".00");
		
		
	}


	//订单提交
	$('#webmallOrder').submit(function(){
		
		if($("#binddomain")[0].value==""){
			alert("请填写授权捆绑域名");
			return false; 
		}

		var patrn=/^[a-zA-z0-9]+(\.)[a-zA-z]{2,4}$|^[a-zA-z0-9]+(\.)[a-zA-z0-9]+(\.)[a-zA-z]{2,4}$/;
		if(!patrn.exec($("#binddomain")[0].value)){
			alert("授权捆绑域名的格式不正确");
			return false; 
		}


		$('#webmallOrder').ajaxSubmit({
			target: 'div#notice',
			method: 'post',
			url: 'post.php',
			success: function(msg) {
				$("div#notice").hide();
				if(msg.substr(0,2)=="OK"){
					var tid=msg.substr(3);
					window.location='orderdetail.php?id='+tid;
				}else{
					alert(msg);
				}
				
				return false; 
			}
		}); 
		
       return false; 

   }); 



});





-->