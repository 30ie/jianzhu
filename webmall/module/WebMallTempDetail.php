<?php

/*
	[插件名称] 详情
	[适用范围] 详情
*/

function WebMallTempDetail(){

	global $msql,$fsql;


	$tempname=$GLOBALS["PLUSVARS"]["tempname"];

	$id=$_GET["id"];

	if($id==""){
		return "NO ID";
	}

	if($_COOKIE["MEMBERTYPEID"]!="" && $_COOKIE["MEMBERTYPEID"]!=NULL){
		$membertypeid=$_COOKIE["MEMBERTYPEID"];
	}

	//缩图来源判断
	$msql->query("select * from {P}_webmall_config");
	while($msql->next_record()){
	$variable=$msql->f('variable');
	$value=$msql->f('value');
	$WEBMALLCONF[$variable]=$value;
	}
	$AgentApi=$WEBMALLCONF["AgentApi"];
	$AgentSharePic=$WEBMALLCONF["AgentSharePic"];
	if($AgentSharePic=="1"){
		$suourl="http://".$AgentApi."/webtry/temppic/";
	}else{
		$suourl=ROOTPATH."webmall/temppic/";
	}
	

	$msql->query("select * from {P}_webmall_spool where id='$id'");
	if($msql->next_record()){
		$name=$msql->f('name');
		$spool=$msql->f('spool');
		$nowtypeid=$msql->f('typeid');
		$nowcatid=$msql->f('catid');
		$designer=$msql->f('designer');
		$memo=$msql->f('memo');
		$intro=$msql->f('intro');
		$price=$msql->f('price');
		$sellnums=$msql->f('sellnums');
		$demourl=$msql->f('demourl');
		$price1=$msql->f('price1');
		$price2=$msql->f('price2');
		$mtype1=$msql->f('mtype1');
		$mtype2=$msql->f('mtype2');
		$xufei=$msql->f('xufei');
		$xufei1=$msql->f('xufei1');
		$xufei2=$msql->f('xufei2');
		$xtype1=$msql->f('xtype1');
		$xtype2=$msql->f('xtype2');
		$dtime=$msql->f('dtime');
	}
	
	$dtime=date("Y-m-d",$dtime);
	$tryurl=ROOTPATH."webmall/webmall_add.php?id=".$id;
	$buyurl=ROOTPATH."webmall/buy.php?id=".$id;
	$src=$suourl.$spool.".jpg";

	$intro=nl2br($intro);

	$GLOBALS["spoolname"]=$name;



	$fsql->query("select cat from {P}_webmall_tempcat where catid='$nowcatid'");
	if($fsql->next_record()){
		$tempcat=$fsql->f('cat');
	}

	$fsql->query("select cat from {P}_webmall_temptype where catid='$nowtypeid'");
	if($fsql->next_record()){
		$temptype=$fsql->f('cat');
	}

	$modules="";
	$fsql->query("select `module` from {P}_webmall_spoolmod where spool='$spool'");
	while($fsql->next_record()){
		$module=$fsql->f('module');
		$msql->query("select cname from {P}_webmall_modules where `module`='$module' limit 0,1");
		if($msql->next_record()){
			$modules.=$msql->f('cname').",";
		}
	}
	$modules=substr($modules,0,-1);


	if($mtype1==$membertypeid){
		$nowprice=$price1;
	}elseif($mtype2==$membertypeid){
		$nowprice=$price2;
	}else{
		$nowprice=$price;
	}

	if($xtype1==$membertypeid){
		$nowxufei=$xufei1;
	}elseif($xtype2==$membertypeid){
		$nowxufei=$xufei2;
	}else{
		$nowxufei=$xufei;
	}

	

	$Temp=LoadTemp($tempname);

	$var=array (
	'name' => $name,
	'spool' => $spool,
	'tempcat' => $tempcat, 
	'temptype' => $temptype, 
	'designer' => $designer,
	'modules' => $modules, 
	'src' => $src, 
	'price' => $nowprice,
	'xufei' => $nowxufei,
	'demourl' => $demourl,
	'buyurl' => $buyurl,
	'tryurl' => $tryurl,
	'sellnums' => $sellnums,
	'dtime' => $dtime,
	'intro' => $intro,
	'memo' => $memo
	);

	$str=ShowTplTemp($Temp,$var);

	return $str;
	
}

?>