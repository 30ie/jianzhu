<!--

$(document).ready(function(){

	//同步模块产品
	$("#getmodules").click(function(){
	
		$.blockUI({ message: "<div id='gs_Msg'>正在同步模块产品库...</div>",css:{backgroundColor:'#fff'}}); 
		$.ajax({
			type: "POST",
			url: "post.php",
			data: "act=getmodules",
			success: function(msg){
				if(msg=="OK"){
					alert("模块产品同步成功！新模块产品首次导入时会导入产品价格，更新时不会再次同步价格，不会影响您对产品的定价");
					$.unblockUI();
					self.location.reload();
				}else{
					alert(msg);
					$.unblockUI(); 
				}
			}
		});
	});

	//修改模块产品
	$('#ModulesModi').submit(function(){
		if($("#cname")[0].value==""){
			alert("请填写模块名称");
			 return false; 
		}

		if($("#price")[0].value==""){
			alert("请填写模块价格");
			return false; 
		}

		if($("#price1")[0].value==""){
			alert("请填写代理价I");
			return false; 
		}

		if($("#price2")[0].value==""){
			alert("请填写代理价II");
			return false; 
		}


		$('#ModulesModi').ajaxSubmit({
			target: 'div#notice',
			method: 'post',
			url: 'post.php',
			success: function(msg) {
				$("div#notice").hide();
				if(msg=="OK"){
					alert("模块产品修改成功");
					self.location='modules.php';
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