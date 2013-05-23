<!--


$(document).ready(function() {
	
	var getObj = $('li.menulist');
	$("li#m1")[0].className="menulistnow";
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
			$("iframe#framecon")[0].src='member_common.php';
	});
	$("li#m9").click(function() {
			$("iframe#framecon")[0].src='member_common.php?searchmodle=cent';
	});
	$("li#m10").click(function() {
			$("iframe#framecon")[0].src='member_common.php?searchmodle=account';
	});
	$("li#m2").click(function() {
			$("iframe#framecon")[0].src='member_type.php';
	});
	$("li#m3").click(function() {
			$("iframe#framecon")[0].src='member_zone.php';
	});
	$("li#m5").click(function() {
			$("iframe#framecon")[0].src='member_cent.php';
	});
	$("li#m11").click(function() {
			$("iframe#framecon")[0].src='paycenter.php';
	});
	$("li#m6").click(function() {
			$("iframe#framecon")[0].src='member_notice_add.php';
	});
	$("li#m7").click(function() {
			$("iframe#framecon")[0].src='member_notice.php';
	});
	$("li#m8").click(function() {
			$("iframe#framecon")[0].src='config.php';
	});
});



-->