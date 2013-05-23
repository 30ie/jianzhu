<!--

$(document).ready(function(){

	//新建站点
	$('#WebMallAdd').submit(function(){
		if($("#passwd")[0].value.length<5){
			alert("请输入至少5位的网站管理密码");
			 return false; 
		}

		$.blockUI({ message: "正在生成网站，约需30秒，请不要关闭本页，等待安装结束 ...",css:{width:'480px',backgroundColor:'#fff'}}); 

		$('#WebMallAdd').ajaxSubmit({
			target: 'div#notice',
			method: 'post',
			url: 'post.php',
			success: function(msg) {
				$("div#notice").hide();
				switch(msg){
					case "9999":
						alert("试用网站创建成功");
						$.unblockUI(); 
						self.location="webmall.php";
					break;
					case "1000":
						alert("您尚未登录,请先登录");
					break;
					case "5000":
						alert("主站代理帐号校验未通过，代理帐号或密码错误");
					break;
					case "1001":
						alert("网站服务器连接密钥错误");
					break;
					case "1002":
						alert("网站安装包目录格式错误");
					break;
					case "1003":
						alert("网站目录名格式错误");
					break;
					case "1004":
						alert("同样的网站目录已经存在");
					break;
					case "3005":
						alert("没有选择试用网站服务器");
					break;
					case "1006":
						alert("试用网站服务器不存在");
					break;
					case "2001":
						alert("数据库安装时密钥校验失败");
					break;
					case "1005":
						alert("网站服务器数据库版本必须高于4.1");
					break;
					case "1007":
						alert("网站服务器数据库连接失败");
					break;
					case "1008":
						alert("网站安装包sql文件不存在");
					break;
					case "1009":
						alert("数据库导入失败");
					break;
					case "1011":
						alert("数据导入时数据库连接中断");
					break;
					default:
						$("div#notice").show();
					break;
				}
				$.unblockUI(); 
				return false; 
			}
		}); 
		
       return false; 

   }); 

});





-->