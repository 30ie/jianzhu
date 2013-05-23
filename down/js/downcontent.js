
//文字大小切换

function fontZoom(size)
{
   document.getElementById('con').style.fontSize=size+'px';
}


//读取详情翻页
(function($){
	$.fn.contentPages = function(downid){
	
	$("div#contentpages").empty();
		
		$.ajax({
			type: "POST",
			url:PDV_RP+"down/post.php",
			data: "act=contentpages&downid="+downid,
			success: function(msg){
				$("div#contentpages").append("<ul>");
				$("div#contentpages").append("<li id='pl' class='cbutton'>上一页</li>");
				$("div#contentpages").append(msg);
				$("div#contentpages").append("<li id='pn' class='pbutton'>下一页</li>");
				$("div#contentpages").append("</ul>");
				//$("li#pl").hide();
				
				
				var getObj = $('li.pages');

				if(getObj.length<2){
					$("div#contentpages").hide();
					$().setBg();
					return false;
				}
				
				$('li.pages')[0].className='pagesnow';
				
				getObj.each(function(id) {
					
					var obj = this.id;
					
					$("li#"+obj).click(function() {
						
						$('li.pagesnow')[0].className="pages";
						this.className='pagesnow';
						var clickid=obj.substr(2);
						$().getContent(downid,clickid);
						if($(".pagesnow").next()[0].id=="pn"){$("li#pn")[0].className="cbutton";}else{$("li#pn")[0].className="pbutton";}
						if($(".pagesnow").prev()[0].id=="pl"){$("li#pl")[0].className="cbutton";}else{$("li#pl")[0].className="pbutton";}
						
						
					});

				});

				
				//上一页
				$("li#pl").click(function() {
					if($("li#pl")[0].className=="pbutton"){
						var nowObj=$(".pagesnow").prev()[0].id;
						var nextpageid=nowObj.substr(2);
						$().getContent(downid,nextpageid);
						$('li.pagesnow')[0].className="pages";
						$("#"+nowObj)[0].className="pagesnow";
						if($(".pagesnow").prev()[0].id=="pl"){$("li#pl")[0].className="cbutton";}else{$("li#pl")[0].className="pbutton";}
						if($(".pagesnow").next()[0].id=="pn"){$("li#pn")[0].className="cbutton";}else{$("li#pn")[0].className="pbutton";}
						
					}else{
						return false;
					}
					
					
				});

				
				//下一页
				$("li#pn").click(function() {
					if($("li#pn")[0].className=="pbutton"){
						var nowObj=$(".pagesnow").next()[0].id;
						var nextpageid=nowObj.substr(2);
						$().getContent(downid,nextpageid);
						$('li.pagesnow')[0].className="pages";
						$("#"+nowObj)[0].className="pagesnow";
						if($(".pagesnow").prev()[0].id=="pl"){$("li#pl")[0].className="cbutton";}else{$("li#pl")[0].className="pbutton";}
						if($(".pagesnow").next()[0].id=="pn"){$("li#pn")[0].className="cbutton";}else{$("li#pn")[0].className="pbutton";}
						
					}else{
						return false;
					}
				});

			}
		});
	};
})(jQuery);


//读取详细内容

(function($){
	$.fn.getContent = function(downid,downpageid){
		
		$.ajax({
			type: "POST",
			url:PDV_RP+"down/post.php",
			data: "act=getcontent&downpageid="+downpageid+"&downid="+downid+"&RP="+PDV_RP,
			success: function(msg){
				$("#con").html(msg);
				$("#con").find("img").each(function(){
					if(this.offsetWidth>600){
						this.style.width="600px";
					}
				});
				$().setBg();
			}
		});
	};
})(jQuery);


//详情图片尺寸处理
$(document).ready(function(){
	$("#con").find("img").hide();
	var w=$("#con")[0].offsetWidth;
	$("#con").find("img").each(function(){
		$(this).show();
		if(this.offsetWidth>w){
			this.style.width=w + "px";
			$().setBg();
		}
	});
		
});


//支持反对投票
$(document).ready(function(){

	$("span#zhichi").click(function(){
		
		var downid=$("input#downid")[0].value;

		$.ajax({
			type: "POST",
			url:PDV_RP+"down/post.php",
			data: "act=zhichi&downid="+downid,
			success: function(msg){
				if(msg=="L0"){
					$().popLogin(0);
				}else if(msg=="L1"){
					$().alertwindow("对不起，您已经投过票了","");
				}else{
					$("span#zhichinum").html(msg);
				}
			}
		});
	});


	$("span#fandui").click(function(){
		
		var downid=$("input#downid")[0].value;

		$.ajax({
			type: "POST",
			url:PDV_RP+"down/post.php",
			data: "act=fandui&downid="+downid,
			success: function(msg){
				if(msg=="L0"){
					$().popLogin(0);
				}else if(msg=="L1"){
					$().alertwindow("对不起，您已经投过票了","");
				}else{
					$("span#fanduinum").html(msg);
				}
			}
		});
	});
		
});

//加入收藏
$(document).ready(function(){

	$("div#addfav").click(function(){
		
		var downid=$("input#downid")[0].value;

		$.ajax({
			type: "POST",
			url:PDV_RP+"down/post.php",
			data: "act=addfav&downid="+downid+"&url="+window.location.href,
			success: function(msg){
				if(msg=="L0"){
					$().popLogin(0);
				}else if(msg=="L1"){
					$().alertwindow("您已经收藏了当前网址","");
				}else if(msg=="OK"){
					$().alertwindow("已经加入到收藏夹",PDV_RP+"member/member_fav.php");
				}else{
					alert(msg);
				}
			}
		});
	});
		
});


//附件下载扣点
$(document).ready(function(){
	var downcentstr=$("input#downcent")[0].value;
	if(downcentstr!=""){
		$("#downcentnotice").html("提示：下载本资源需要支付 "+downcentstr+"，同会员多次下载不重复支付");
	}
	$("#downlink").click(function(){
		var downid=$("input#downid")[0].value;

		$.ajax({
			type: "POST",
			url:PDV_RP+"down/post.php",
			data: "act=download&downid="+downid+"&RP="+PDV_RP,
			success: function(msg){
				if(msg=="1000"){
					alert("下载本资源请先登录");
				}else if(msg=="1001"){
					alert("下载本资源需要支付 "+downcentstr);
				}else{
					window.location=msg;
				}
			}
		});

	});

	$("a.downlinktext").click(function(){
		var downid=$("input#downid")[0].value;

		$.ajax({
			type: "POST",
			url:PDV_RP+"down/post.php",
			data: "act=download&downid="+downid+"&RP="+PDV_RP,
			success: function(msg){
				if(msg=="1000"){
					alert("下载本资源请先登录");
				}else if(msg=="1001"){
					alert("下载本资源需要支付 "+downcentstr);
				}else{
					window.location=msg;
				}
			}
		});

	});
});



//版主管理
$(document).ready(function(){

		var downid=$("input#downid")[0].value;

		$.ajax({
			type: "POST",
			url:PDV_RP+"down/post.php",
			data: "act=ifbanzhu&downid="+downid,
			success: function(msg){
				if(msg=="YES"){
					$("#banzhu").append("版主管理 | <span id='banzhutj'>推荐</span> | <span id='banzhudel'>删除</span> | <span id='banzhudelmincent'>删除并扣分</span> |").show();
					$().setBg();

					//推荐操作
					$("#banzhutj").click(function(){
						$.ajax({
							type: "POST",
							url:PDV_RP+"down/post.php",
							data: "act=banzhutj&downid="+downid,
							success: function(msg){
								if(msg=="OK"){
									$().alertwindow("推荐成功","");
								}else{
									alert(msg);
								}
							}
						});
						
					});

					//删除操作
					$("#banzhudel").click(function(){
						$.ajax({
							type: "POST",
							url:PDV_RP+"down/post.php",
							data: "act=banzhudel&downid="+downid,
							success: function(msg){
								if(msg=="OK"){
									$().alertwindow("删除成功","../class/");
								}else{
									alert(msg);
								}
							}
						});
						
					});


					//删除并扣分操作
					$("#banzhudelmincent").click(function(){
						$.ajax({
							type: "POST",
							url:PDV_RP+"down/post.php",
							data: "act=banzhudel&koufen=yes&downid="+downid,
							success: function(msg){
								if(msg=="OK"){
									$().alertwindow("删除并扣分成功","../class/");
								}else{
									alert(msg);
								}
							}
						});
						
					});
				
				}else{
					$("#banzhu").empty().hide();
				}
			}
		});

});