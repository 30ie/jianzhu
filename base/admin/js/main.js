<!--

$(document).ready(function() {

	$("div.menubox")[0].style.width=document.body.offsetWidth-150 + "px";
	
	var getObj = $('div.menu');
	getObj[0].className="menunow";
	getObj.each(function(id) {
		var obj = this.id;
		$("div#"+obj).click(function() {
			$('div.menunow')[0].className="menu";
			$("div#"+obj)[0].className="menunow";
		});
		
	});

	
});




/**
* 排版和访问前台
*/
$(document).ready(function(){
	
	$("#preview").click(function () { 
		window.open(PDV_RP+"index.php","_blank");
	 });
	
	 $("#pedit").click(function () { 
		$.ajax({
			type: "POST",
			url: "../../post.php",
			data: "act=plusready",
			success: function(msg){
				if(msg=="OK"){
					//mainframe.location=PDV_RP+"index.php";
					window.open(PDV_RP+"index.php","_blank");
				}else if(msg=="NORIGHTS"){
					alert("当前管理账户没有排版权限");
					return false;
				}
			}
		});
		
	 });

});






//AJAX 管理退出
$(document).ready(function(){
	$("#pdv_logout").click(function () { 
		$.ajax({
			type: "POST",
			url: PDV_RP+"post.php",
			data: "act=adminlogout",
			success: function(msg){
				if(msg=="OK"){
					window.location=PDV_RP+"admin.php";
				}else{
					alert(msg);
				}
				
			}
		});
	 });
});


-->