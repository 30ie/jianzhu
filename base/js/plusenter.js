<!--


//管理状态下显示进入排版菜单

$(document).ready(function() {


	$("body").prepend("<div id='adminbar_showme' class='adminbar_showme'><span>显示面板</span></div>");
	$("body").prepend("<div id='adminmenu' class='adminmenu'></div>");
	$("#adminmenu").append("<div class='adminlogo'></div>");
	$("#adminmenu").append("<div id='pdv_enter' class='adminbutton1'>切换到排版模式</div>");
	$("#adminmenu").append("<div  class='admintip'>提示：现在是管理登录状态，进入需要排版的页面，点击左侧按钮可切换到排版模式</div>");
	$("#adminmenu").append("<div id='pdv_hide' class='adminhidden'><span>隐藏面板</span></div>");
	$("#adminmenu").append("<div id='adminclose' class='adminclose'><span>关闭</span></div>");

	$('#adminmenu').animate({height: 'show',opacity: 'show'}, 'slow');
	
	$("#adminclose").click(function(){
		$.ajax({
			type: "POST",
			url: PDV_RP+"post.php",
			data: "act=plusclose",
			success: function(msg){
				$("#adminmenu").hide();
			}
		});
	});

	$("#pdv_enter").click(function () { 
		$.ajax({
			type: "POST",
			url: PDV_RP+"post.php",
			data: "act=plusenter",
			success: function(msg){
				if(msg=="OK"){
					window.location.reload();
				}else{
					alert("当前管理账户没有排版权限");
					return false;
				}
			}
		});
	 });

});


/***********隐藏排版控制界面 BY Cyrano*************/
$(document).ready(function(){
    $("#pdv_hide").click(function () { 
		$("#adminmenu").hide();
		$("#adminbar_showme").show();
    });
	$("#adminbar_showme").click(function () { 
		$("#adminmenu").show();
		$("#adminbar_showme").hide();
    });
});


function mouseMove(ev) { 
	ev= ev || window.event; 
	var mousePos = mouseCoords(ev); 
	if(mousePos.y<=1){
		$("#adminmenu").show();
		$("#adminbar_showme").hide();
	}; 
} 
function mouseCoords(ev) { 
	if(ev.pageX || ev.pageY){ 
		return {x:ev.pageX, y:ev.pageY}; 
	} 
	return { 
		x:ev.clientX + document.body.scrollLeft - document.body.clientLeft, 
		y:ev.clientY + document.body.scrollTop - document.body.clientTop 
	}; 
} 
document.onmousemove = mouseMove;

/***********隐藏排版控制界面结束*************/


-->