<?php

/*
	[插件名称] 下载检索
	[适用范围] 下载分类检索页
*/ 


function DownQuery(){

	global $fsql,$msql,$strDownperson;

	
	
	$shownums=$GLOBALS["PLUSVARS"]["shownums"];
	$cutword=$GLOBALS["PLUSVARS"]["cutword"];
	$target=$GLOBALS["PLUSVARS"]["target"];
	$tempname=$GLOBALS["PLUSVARS"]["tempname"];
	

	//地址栏参数
	if(strstr($_SERVER["QUERY_STRING"],".html")){
		$Arr=explode(".html",$_SERVER["QUERY_STRING"]);
		$catid=$Arr[0];
	}elseif($_GET["catid"]>0){
		$catid=$_GET["catid"];
	}else{
		$catid=0;
	}

	$key=$_GET["key"];
	$showtj=$_GET["showtj"];
	$page=$_GET["page"];
	$myord=$_GET["myord"];
	$myshownums=$_GET["myshownums"];
	$memberid=$_GET["memberid"];
	$showtag=$_GET["showtag"];

	if($myord==""){
		$myord="dtime";
	}

	if($myshownums!="" && $myshownums!="0"){
		$shownums=$myshownums;
	}

	//积分名称
	
	$msql->query("select * from {P}_member_centset");
	if($msql->next_record()){
		$centname1=$msql->f('centname1');
		$centname2=$msql->f('centname2');
		$centname3=$msql->f('centname3');
		$centname4=$msql->f('centname4');
		$centname5=$msql->f('centname5');
	}


	//模版解释
	$Temp=LoadTemp($tempname);
	$TempArr=SplitTblTemp($Temp);

	$str=$TempArr["start"];


	$scl=" iffb='1' and catid!='0' ";

	if($catid!="0" && $catid!=""){
		$fmdpath=fmpath($catid);
		$scl.=" and catpath regexp '$fmdpath' ";
	}

	if($showtj!="" && $showtj!="all"){
	$scl.=" and tj='$showtj' ";

	}
	if($memberid!="" && $memberid!="all"){
	$scl.=" and memberid='$memberid' ";

	}


	if($key!=""){
		
		$scl.=" and (title regexp '$key' or body regexp '$key') ";
	}
	
	if($showtag!=""){
		$scl.=" and tags regexp '$showtag' ";
	}
	

	include(ROOTPATH."includes/pages.inc.php");
	$pages=new pages;

	$totalnums=TblCount("_down_con","id",$scl);
	
	$pages->setvar(array("catid" => $catid,"myord" => $myord,"myshownums" => $myshownums,"showtj" => $showtj,"author" => $author,"key" => $key));

	$pages->set($shownums,$totalnums);		                          
		
	$pagelimit=$pages->limit();	  
	

	$fsql->query("select * from {P}_down_con where $scl order by $myord desc limit $pagelimit");

	while($fsql->next_record()){
		
		$id=$fsql->f('id');
		$title=$fsql->f('title');
		$catid=$fsql->f('catid');
		$catpath=$fsql->f('catpath');
		$dtime=$fsql->f('dtime');
		$nowcatid=$fsql->f('catid');
		$ifbold=$fsql->f('ifbold');
		$ifred=$fsql->f('ifred');
		$author=$fsql->f('author');
		$source=$fsql->f('source');
		$type=$fsql->f('type');
		$src=$fsql->f('src');
		$cl=$fsql->f('cl');
		$fileurl=$fsql->f('fileurl');
		$downcount=$fsql->f('downcount');
		$prop1=$fsql->f('prop1');
		$prop2=$fsql->f('prop2');
		$prop3=$fsql->f('prop3');
		$prop4=$fsql->f('prop4');
		$prop5=$fsql->f('prop5');
		$prop6=$fsql->f('prop6');
		$prop7=$fsql->f('prop7');
		$prop8=$fsql->f('prop8');
		$prop9=$fsql->f('prop9');
		$prop10=$fsql->f('prop10');
		$prop11=$fsql->f('prop11');
		$prop12=$fsql->f('prop12');
		$prop13=$fsql->f('prop13');
		$prop14=$fsql->f('prop14');
		$prop15=$fsql->f('prop15');
		$prop16=$fsql->f('prop16');
		$prop17=$fsql->f('prop17');
		$prop18=$fsql->f('prop18');
		$prop19=$fsql->f('prop19');
		$prop20=$fsql->f('prop20');
		$memo=$fsql->f('memo');
		$downcent=$fsql->f('downcent');
		$downcentid=$fsql->f('downcentid');
		$centincome=$fsql->f('centincome');

		$dtime=date("Y-m-d",$dtime);
		
		if($GLOBALS["CONF"]["CatchOpen"]=="1" && file_exists(ROOTPATH."down/html/".$id.".html")){
			$link=ROOTPATH."down/html/".$id.".html";
		}else{
			$link=ROOTPATH."down/html/?".$id.".html";
		}

		if($ifbold=="1"){$bold=" style='font-weight:bold' ";}else{$bold="";}
		if($ifred!="0"){$red=" style='color:".$ifred."' ";}else{$red="";}

		if($cutword!="0"){$title=csubstr($title,0,$cutword);}

		
		//参数列

		$i=1;
		$msql->query("select * from {P}_down_prop where catid='$catid' order by xuhao");
		while($msql->next_record()){
			$pn="propname".$i;
			$$pn=$msql->f('propname');
		$i++;
		}

		//分类树
		$arr=explode(":",$catpath);
		if($catpath==""){
			$catstr=$strDownperson;
		}else{
			$catstr="";
			for($i=0;$i<sizeof($arr);$i++){
				if($arr[$i]!="" && $arr[$i]!="0"){
					$msql->query("select cat from {P}_down_cat where catid='".intval($arr[$i])."'");
					if($msql->next_record()){
						$cat=$msql->f('cat');
						$catstr.=$cat."&gt;";
					}
				}
			}
			
			$catstr=substr($catstr,0,-4);

		}

		//交易积分
		$cenv="centname".$downcentid;
		$centname=$$cenv;



		$var=array (
		'title' => $title, 
		'dtime' => $dtime, 
		'red' => $red, 
		'author' => $author, 
		'source' => $source,
		'cat' => $cat,
		'src' => $src, 
		'cl' => $cl, 
		'catstr' => $catstr, 
		'fileurl' => $fileurl, 
		'downcount' => $downcount, 
		'link' => $link,
		'target' => $target,
		'centname' => $centname,
		'downcent' => $downcent,
		'centincome' => $centincome,
		'memo' => $memo,
		'bold' => $bold,
		'prop1' => $prop1,
		'prop2' => $prop2,
		'prop3' => $prop3,
		'prop4' => $prop4,
		'prop5' => $prop5,
		'prop6' => $prop6,
		'prop7' => $prop7,
		'prop8' => $prop8,
		'prop9' => $prop9,
		'prop10' => $prop10,
		'prop11' => $prop11,
		'prop12' => $prop12,
		'prop13' => $prop13,
		'prop14' => $prop14,
		'prop15' => $prop15,
		'prop16' => $prop16,
		'prop17' => $prop17,
		'prop18' => $prop18,
		'prop19' => $prop19,
		'prop20' => $prop20,
		'propname1' => $propname1,
		'propname2' => $propname2,
		'propname3' => $propname3,
		'propname4' => $propname4,
		'propname5' => $propname5,
		'propname6' => $propname6,
		'propname7' => $propname7,
		'propname8' => $propname8,
		'propname9' => $propname9,
		'propname10' => $propname10,
		'propname11' => $propname11,
		'propname12' => $propname12,
		'propname13' => $propname13,
		'propname14' => $propname14,
		'propname15' => $propname15,
		'propname16' => $propname16,
		'propname17' => $propname17,
		'propname18' => $propname18,
		'propname19' => $propname19,
		'propname20' => $propname20
		);

		$str.=ShowTplTemp($TempArr["list"],$var);
		

	}

	$str.=$TempArr["end"];

	$pagesinfo=$pages->ShowNow();

	$var=array (
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