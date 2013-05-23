<!--

$(document).ready(function(){

	//新增商品
	$('#GoodsAdd').submit(function(){
		if($("#goods")[0].value==""){
			alert("请填写商品名称");
			 return false; 
		}

		if($("#price")[0].value==""){
			alert("请填写商品价格");
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


		$('#GoodsAdd').ajaxSubmit({
			target: 'div#notice',
			method: 'post',
			url: 'post.php',
			success: function(msg) {
				$("div#notice").hide();
				if(msg=="OK"){
					alert("商品发布成功");
					self.location='goods.php';
				}else{
					alert(msg);
				}
				return false; 
			}
		}); 
		
       return false; 

   }); 


	//修改商品
	$('#GoodsModi').submit(function(){
		if($("#goods")[0].value==""){
			alert("请填写商品名称");
			 return false; 
		}

		if($("#price")[0].value==""){
			alert("请填写商品价格");
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


		$('#GoodsModi').ajaxSubmit({
			target: 'div#notice',
			method: 'post',
			url: 'post.php',
			success: function(msg) {
				$("div#notice").hide();
				if(msg=="OK"){
					alert("商品修改成功");
					self.location='goods.php';
				}else{
					alert(msg);
				}
				return false; 
			}
		}); 
		
       return false; 

   });
   
   //删除商品
	$(".goodsdel").click(function(){
		var qus=confirm("确定要删除商品吗？")
		if(qus!=0){
			var goodsid=this.id.substr(9);
			$.ajax({
				type: "POST",
				url: "post.php",
				data: "act=goodsdel&goodsid="+goodsid,
				success: function(msg){
					if(msg=="OK"){
						$("#tr_"+goodsid).remove();
					}else{
						alert(msg);
					}
				}
			});

		}
		
	});

   

});





-->