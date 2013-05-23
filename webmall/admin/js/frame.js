<!--


$(document).ready(function() {
	
	var getObj = $('li.menulist');
	$("li#m5")[0].className="menulistnow";
	getObj.each(function(id) {
		var obj = this.id;
		$("li#"+obj).mouseover(function() {
			$('li.menulistover').each(function(id) {
				this.className="menulist";
			});
			if(this.className!="menulistnow"){this.className="menulistover";};
		});
		$("li#"+obj).mouseout(function() {
			$('li.menulistover').each(function(id) {
				this.className="menulist";
			});
		});

		$("li#"+obj).click(function() {
			$('li.menulistnow').each(function(id) {
				this.className="menulist";
			});
			this.className="menulistnow";
		});
	});
});


$(document).ready(function() {
	
	$("li#m1").click(function() {
			$("iframe#framecon")[0].src='sys_user.php';
	});
	$("li#m4").click(function() {
			$("iframe#framecon")[0].src='config.php';
	});
	$("li#m5").click(function() {
			$("iframe#framecon")[0].src='spool.php';
	});
	$("li#m9").click(function() {
			$("iframe#framecon")[0].src='goods.php';
	});
	$("li#m10").click(function() {
			$("iframe#framecon")[0].src='goodsadd.php';
	});
	$("li#m11").click(function() {
			$("iframe#framecon")[0].src='torder.php';
	});
	$("li#m13").click(function() {
			$("iframe#framecon")[0].src='modules.php';
	});

});



-->