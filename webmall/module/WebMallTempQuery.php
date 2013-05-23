<?php

/*
	[插件名称] 模板翻页查询
	[适用范围] 全站
*/ 


function WebMallTempQuery(){

	global $fsql,$msql;

	
	$shownums=$GLOBALS["PLUSVARS"]["shownums"];
	$cutword=$GLOBALS["PLUSVARS"]["cutword"];
	$target=$GLOBALS["PLUSVARS"]["target"];
	$tempname=$GLOBALS["PLUSVARS"]["tempname"];
	$picw=$GLOBALS["PLUSVARS"]["picw"];
	$pich=$GLOBALS["PLUSVARS"]["pich"];
	$fittype=$GLOBALS["PLUSVARS"]["fittype"];
	$cutbody=$GLOBALS["PLUSVARS"]["cutbody"];

	if($_COOKIE["MEMBERTYPEID"]!="" && $_COOKIE["MEMBERTYPEID"]!=NULL){
		$membertypeid=$_COOKIE["MEMBERTYPEID"];
	}
	

	if($_GET["catid"]>0){
		$catid=$_GET["catid"];
	}else{
		$catid=0;
	}

	if($_GET["typeid"]>0){
		$typeid=$_GET["typeid"];
	}else{
		$typeid=0;
	}


	$key=$_GET["key"];
	$page=$_GET["page"];
	$myord=$_GET["myord"];
	$myshownums=$_GET["myshownums"];
	$author=$_GET["author"];
	$fromprice=$_GET["fromprice"];
	$toprice=$_GET["toprice"];

	if($myord==""){
		$myord="id";
	}

	if($myshownums!="" && $myshownums!="0"){
		$shownums=$myshownums;
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


	//模版解释
	$Temp=LoadTemp($tempname);
	$TempArr=SplitTblTemp($Temp);

	$str=$TempArr["start"];


	$scl=" id!='0' ";


	if($author!=""){
		$scl.=" and author='$author' ";
	}
	if($catid!="0" && $catid!=""){
		$scl.=" and catid='$catid' ";
	}

	if($typeid!="0" && $typeid!=""){
		$scl.=" and typeid='$typeid' ";
	}

	if($key!=""){
		$scl.=" and (`name` regexp '$key' or `memo` regexp '$key') ";
	}
	

	
	include(ROOTPATH."includes/pages.inc.php");
	$pages=new pages;

	$totalnums=TblCount("_webmall_spool","id",$scl);
	
	$pages->setvar(array(
		"catid" => $catid,
		"typeid" => $typeid,
		"myord" => $myord,
		"myshownums" => $myshownums,
		"author" => $author,
		"fromprice" => $fromprice,
		"toprice" => $toprice,
		"key" => $key
		));

	$pages->set($shownums,$totalnums);		                          
		
	$pagelimit=$pages->limit();	  
	

	$fsql->query("select * from {P}_webmall_spool where $scl order by $myord desc limit $pagelimit");

	while($fsql->next_record()){
		
		$id=$fsql->f('id');
		$name=$fsql->f('name');
		$spool=$fsql->f('spool');
		$nowtypeid=$fsql->f('typeid');
		$nowcatid=$fsql->f('catid');
		$designer=$fsql->f('designer');
		$modules=$fsql->f('modules');
		$memo=$fsql->f('memo');
		$intro=$fsql->f('intro');
		$price=$fsql->f('price');
		$xufei=$fsql->f('xufei');
		$sellnums=$fsql->f('sellnums');
		$demourl=$fsql->f('demourl');
		$price1=$fsql->f('price1');
		$price2=$fsql->f('price2');
		$mtype1=$fsql->f('mtype1');
		$mtype2=$fsql->f('mtype2');
		$xufei=$fsql->f('xufei');
		$xufei1=$fsql->f('xufei1');
		$xufei2=$fsql->f('xufei2');
		$xtype1=$fsql->f('xtype1');
		$xtype2=$fsql->f('xtype2');
		$dtime=$fsql->f('dtime');


		$dtime=date("Y-m-d",$dtime);

		
		$link=ROOTPATH."webmall/detail.php?id=".$id;
		$tryurl=ROOTPATH."webmall/webmall_add.php?id=".$id;
		$buyurl=ROOTPATH."webmall/buy.php?id=".$id;

		if($cutword!="0"){$name=csubstr($name,0,$cutword);}
		if($cutbody!="0"){$memo=csubstr($memo,0,$cutbody);}

		$src=$suourl.$spool."_s.jpg";

		$intro=nl2br($intro);


		$msql->query("select cat from {P}_webmall_tempcat where catid='$nowcatid'");
		if($msql->next_record()){
			$tempcat=$msql->f('cat');
		}

		$msql->query("select cat from {P}_webmall_temptype where catid='$nowtypeid'");
		if($msql->next_record()){
			$temptype=$msql->f('cat');
		}

		if($mtype1==$membertypeid){
			$nowprice=$price1;
		}elseif($mtype2==$membertypeid){
			$nowprice=$price2;
		}else{
			$nowprice=$price;
		}

		
		$var=array (
		'id' => $id, 
		'name' => $name, 
		'spool' => $spool, 
		'tempcat' => $tempcat, 
		'temptype' => $temptype, 
		'designer' => $designer,
		'src' => $src, 
		'modules' => $modules, 
		'link' => $link,
		'target' => $target,
		'memo' => $memo,
		'price' => $nowprice,
		'xufei' => $xufei,
		'demourl' => $demourl,
		'buyurl' => $buyurl,
		'tryurl' => $tryurl,
		'sellnums' => $sellnums,
		'dtime' => $dtime,
		'picw' => $picw,
		'pich' => $pich,
		'intro' => $intro
		);

		$str.=ShowTplTemp($TempArr["list"],$var);
		

	}

	$str.=$TempArr["end"];

	$pagesinfo=$pages->ShowNow();

	$var=array (
	'fittype' => $fittype,
	'showpages' => $pages->output(1),
	'pagestotal' => $pagesinfo["total"],
	'pagesnow' => $pagesinfo["now"],
	'pagesshownum' => $pagesinfo["shownum"],
	'pagesfrom' => $pagesinfo["from"],
	'pagesto' => $pagesinfo["to"],
	'totalnums' => $totalnums
	);

	$str=ShowTplTemp($str,$var);

	return $str;


}
?>