<!--


//菜单
function showSubMenu(subMenuDiv,n){
	
	var submenudiv = document.getElementById(subMenuDiv);
	
	obj=submenudiv.getElementsByTagName("ul");
	
 	for(var i=0;i<obj.length;i++){
		if(i==n){
			obj[i].style.display="block";
		}else{
			obj[i].style.display="none";
		}
		
	}

}


-->