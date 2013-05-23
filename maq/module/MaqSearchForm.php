<?php

/*
	[插件名称] 搜索表单
	[适用范围] 检索页
*/


function MaqSearchForm(){

	global $fsql,$tsql;
	


	
	$tempname=$GLOBALS["PLUSVARS"]["tempname"];
	
	
	//地址栏参数

	if(strstr($_SERVER["QUERY_STRING"],".html")){
		$Arr=explode(".html",$_SERVER["QUERY_STRING"]);
		$nowcatid=$Arr[0];
	}elseif($_GET["catid"]>0){
		$nowcatid=$_GET["catid"];
	}else{
		$nowcatid=0;
	}

	$key=$_GET["key"];
	$myord=$_GET["myord"];
	$myshownums=$_GET["myshownums"];
	$myshowstat=$_GET["myshowstat"];
	$mid=$_GET["mid"];

	//分类
	$catlist="";
	$fsql->query("select * from {P}_maq_cat where pid='0' order by xuhao");
	while($fsql->next_record()){
		$bcatid=$fsql->f("catid");
		$bcat=$fsql->f("cat");
		$i=0;
		$subcatlist="";
		$tsql->query("select * from {P}_maq_cat where pid='$bcatid' order by xuhao ");
		while($tsql->next_record()){
			$scatid=$tsql->f("catid");
			$scat=$tsql->f("cat");
			if($nowcatid==$scatid){
				$subcatlist.="<option value='".$scatid."' selected>".$scat."</option>";
			}else{
				$subcatlist.="<option value='".$scatid."'>".$scat."</option>";
			}
			$i++;
		}
		if($i<1){
			$catlist.="<option value='".$bcatid."'>".$bcat."</option>";
		}else{
			$catlist.="<OPTGROUP LABEL='".$bcat."'>";
			$catlist.=$subcatlist;
			$catlist.="</OPTGROUP>";
			

		}

	}
	

	//模版解释
	$Temp=LoadTemp($tempname);

	$var=array (
	'myshownums' => $myshownums, 
	'myshowstat' => $myshowstat, 
	'myord' => $myord, 
	'key' => $key, 
	'mid' => $mid, 
	'catlist' => $catlist
	);

	$str=ShowTplTemp($Temp,$var);

	return $str;


}
?>