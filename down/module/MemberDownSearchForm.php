<?php

/*
	[插件名称] 会员下载搜索表单
	[适用范围] 会员下载检索页
*/


function MemberDownSearchForm(){

	global $msql,$fsql;
	
	
	$tempname=$GLOBALS["PLUSVARS"]["tempname"];
	

	//地址栏参数
	if(isset($_GET["mid"]) && $_GET["mid"]!="" && $_GET["mid"]!="0"){
		$mid=$_GET["mid"];
	}else{
		return "";
	}

	$key=$_GET["key"];
	$myord=$_GET["myord"];
	$myshownums=$_GET["myshownums"];
	$pcatid=$_GET["pcatid"];
		
		$fsql->query("select * from {P}_down_pcat where memberid='$mid'  order by xuhao");
		while($fsql->next_record()){
			$cat=$fsql->f('cat');
			$catid=$fsql->f('catid');
			if($pcatid==$catid){
				$catlist.="<option value='".$catid."' selected>".$cat."</option>";
			}else{
				$catlist.="<option value='".$catid."'>".$cat."</option>";
			}
		}

	

	//模版解释
	$Temp=LoadTemp($tempname);

	$var=array (
	'coltitle' => $coltitle,
	'myshownums' => $myshownums, 
	'myord' => $myord, 
	'key' => $key, 
	'mid' => $mid, 
	'catlist' => $catlist
	);

	$str=ShowTplTemp($Temp,$var);

	return $str;


}
?>