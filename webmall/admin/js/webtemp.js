<!--

$(document).ready(function(){

	$("#getspool").click(function(){
		
		$.blockUI({ message: "<div id='gs_Msg'>正在同步网站产品库...</div>",css:{backgroundColor:'#fff'}}); 
		$.ajax({
			type: "POST",
			url: "post.php",
			data: "act=getspool",
			success: function(msg){
				if(msg=="OK"){
					//$.unblockUI(); 
					$("#gs_Msg").html("正在同步网站产品模块清单...");
					$.ajax({
						type: "POST",
						url: "post.php",
						data: "act=getspoolmodules",
						success: function(msg){
							if(msg=="OK"){
								$("#gs_Msg").html("正在同步网站产品行业分类...");
								$.ajax({
									type: "POST",
									url: "post.php",
									data: "act=getspoolcat",
									success: function(msg){
										if(msg=="OK"){
											$("#gs_Msg").html("正在同步网站产品应用分类...");
											$.ajax({
												type: "POST",
												url: "post.php",
												data: "act=getspooltype",
												success: function(msg){
													if(msg=="OK"){
														alert("网站产品同步成功！新产品首次导入时会导入产品价格，产品更新时不会再次同步价格，不会影响您对产品的定价");
														$.unblockUI();
														self.location.reload();
													}else{
														alert(msg);
														$.unblockUI(); 
													}
												}
											});
										}else{
											alert(msg);
											$.unblockUI(); 
										}
									}
								});
							}else{
								alert(msg);
								$.unblockUI(); 
							}
						}
					});

				}else{
					alert(msg);
					$.unblockUI(); 
				}
			}
		});
		
	});


   //修改产品价格
	$('#webTempModi').submit(function(){
		
		if($("#price")[0].value==""){
			alert("请填写产品价格");
			return false; 
		}

		if($("#xufei")[0].value==""){
			alert("请填写年服务费");
			return false; 
		}

		$('#webTempModi').ajaxSubmit({
			target: 'div#notice',
			method: 'post',
			url: 'post.php',
			success: function(msg) {
				$("div#notice").hide();
				if(msg=="OK"){
					self.location='spool.php';
				}else{
					alert(msg);
				}
				return false; 
			}
		}); 
		
       return false; 

   }); 


    //删除
	$(".tempdel").click(function(){
		var qus=confirm("确定要删除网站产品吗？")
		if(qus!=0){
			var tempid=this.id.substr(8);
			$.ajax({
				type: "POST",
				url: "post.php",
				data: "act=webmalltempdel&tempid="+tempid,
				success: function(msg){
					if(msg=="OK"){
						$("#tr_"+tempid).remove();
					}else{
						alert(msg);
					}
				}
			});

		}
		
	});



});



-->