

//根据是否登录显示发布表单时的登录信息
$(document).ready(function(){
	$.ajax({
		type: "POST",
		url:PDV_RP+"post.php",
		data: "act=isLogin",
		success: function(msg){
			if(msg=="1"){
				$("div#notLogin").hide();
				$("div#isLogin").show();
				$("span#username").html(getCookie("MUSER"));
			}else{
				$("div#isLogin").hide();
				$("div#notLogin").show();
				$('.loginlink').click(function() { 
					$().popLogin(1);
				});
			}
		}
	});
});


//会员退出
$(document).ready(function(){
	
	$('.logoutlink').click(function(){ 
		
		$.ajax({
			type: "POST",
			url: PDV_RP+"post.php",
			data: "act=memberlogout",
			success: function(msg){
				if(msg=="OK"){

					$("div#isLogin").hide();
					$("div#notLogin").show();

					$('.loginlink').click(function() { 
						$().popLogin(1);
					});

				}else{
					alert(msg);
				}
			}
		});
	

   }); 
});








//发布表单提交
$(document).ready(function(){
	$('#maqSend').submit(function(){ 
		$('#maqSend').ajaxSubmit({
			target: 'div#notice',
			url: PDV_RP+'maq/post.php',
			success: function(msg) {
				
				switch(msg){
					
					case "NOTLOGIN":
						$('div#notice').hide();
						$().popLogin(1);
					break;
					
					case "CHK":
						$('div#notice').hide();
						$().alertwindow("提交成功！<br>需要审核后才能显示","");
					break;


					default:
						if(msg.substr(0,2)=="OK"){
							var u=msg.substr(3);
							var p=$('input#nowpage')[0].value;
							$('div#notice').hide();
							window.location=PDV_RP+"maq/html/?"+u+".html&page="+p;
						}else{
							$('div#notice')[0].className='noticediv';
							$('div#notice').show();
							$().setBg();
						}
					break;
				}
			}
		}); 
       return false; 

   }); 
});


//设为最佳答案
$(document).ready(function(){
	$(".setbest").click(function(){
		var backid=this.id.substr(8);
		$.ajax({
			type: "POST",
			url:PDV_RP+"maq/post.php",
			data: "act=setbest&backid="+backid,
			success: function(msg){
				if(msg=="OK"){
					self.location.reload();
				}else if(msg=="1000"){
					alert("对不起，您尚未登录");
				}else if(msg=="1001"){
					alert("答案ID不存在");
				}else if(msg=="1002"){
					alert("问题ID不存在");
				}else if(msg=="1003"){
					alert("您不是该回答的提问者，无权操作");
				}else if(msg=="1004"){
					alert("当前问题已经采纳了最佳答案");
				}else if(msg=="1005"){
					alert("提问者不能采纳自己的答案");
				}else if(msg=="1006"){
					alert("问题已关闭，不能采纳答案");
				}else{
					alert(msg);
				}
			}
		});
	});

});


//关闭结束的问题
$(document).ready(function(){
	$(".setclose").click(function(){
		var maqid=this.id.substr(9);
		$.ajax({
			type: "POST",
			url:PDV_RP+"maq/post.php",
			data: "act=setclose&maqid="+maqid,
			success: function(msg){
				if(msg=="OK"){
					window.location="../class/";
				}else if(msg=="1000"){
					alert("对不起，您尚未登录");
				}else if(msg=="1001"){
					alert("问题ID不存在或您不是该问题的提问者");
				}else if(msg=="1002"){
					alert("该问题是已解决状态");
				}else if(msg=="1003"){
					alert("该问题已经关闭");
				}else{
					alert(msg);
				}
			}
		});
	});

});


//点评详情图片尺寸处理
$(document).ready(function(){
	$(".maqbody").find("img").each(function(){
		if(this.offsetWidth>$(".maqbody")[0].offsetWidth){
			this.style.width=$(".maqbody")[0].offsetWidth + "px";
			this.style.cursor="pointer";
			$().setBg();
		
			$(this).click(function(){
				var src=$(this)[0].src;
				$("body").append("<img id='pre' src='"+src+"'>");
				var w=$("#pre")[0].offsetWidth;
				var h=$("#pre")[0].offsetHeight;

				$.blockUI({  
					message: "<img  src='"+src+"' class='closeit'>",  
					css: {  
						top:  ($(window).height() - h) /2 + 'px', 
						left: ($(window).width() - w/2) /2 + 'px', 
						width: $("#pre")[0].offsetWidth + 'px',
						backgroundColor: '#fff',
						borderWidth:'3px',
						borderColor:'#fff'
					}  
				}); 
				$("#pre").remove();
				$(".closeit").click(function(){
					$.unblockUI(); 
				}); 
				
			});

		}
		
	});
		
});


//加为好友
$(document).ready(function(){

	$("div.user_add").click(function(){
		
		var fid=this.id.substr(8);

		$.ajax({
			type: "POST",
			url:PDV_RP+"member/post.php",
			data: "act=addfriends&fid="+fid,
			success: function(msg){
				if(msg=="L0"){
					$().popLogin(0);
				}else if(msg=="OK"){
					$().alertwindow("已经加为好友",PDV_RP+"member/member_friends.php");
				}else{
					alert(msg);
				}
			}
		});
	});
		
});


//弹出式发送短信
$(document).ready(function(){

	$("div.send_msg").click(function(){
		
		var mid=this.id.substr(8);

		$.ajax({
			type: "POST",
			url:PDV_RP+"member/post.php",
			data: "act=loadmsg&mid="+mid+"&RP="+PDV_RP,
			success: function(msg){
				if(msg=="L0"){
					$().popLogin(0);
				}else{
					$('div#msnDialog').remove();
					$('html').append(msg);
					$.blockUI({message: $('div#msnDialog'),css:{width:'420px'}}); 
					$('.pwClose').click(function() { 
						$.unblockUI(); 
						$('div#msnDialog').remove();
					}); 

					$('#msnForm').submit(function(){ 

						$('#msnForm').ajaxSubmit({
							target: 'div#msnnotice',
							url: PDV_RP+'member/post.php',
							success: function(msg) {
								if(msg=="OK"){
									$('div#msnnotice').hide();
									$.unblockUI(); 
									$('div#msnDialog').remove();
									$().alertwindow("短信发送成功","");
								}else{
									$('div#msnnotice').show();
								}
							}
						}); 
						return false; 
					}); 
				
				}
			}
		});
	});
		
});

