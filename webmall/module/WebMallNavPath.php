<?php

/*
	[插件名称] 导航条
	[适用范围] 本模块
*/


function WebMallNavPath(){

	global $msql;


	$tempname=$GLOBALS["PLUSVARS"]["tempname"];
	$pagename=$GLOBALS["PLUSVARS"]["pagename"];

	$Temp=LoadTemp($tempname);
	$TempArr=SplitTblTemp($Temp);

	$var=array (
		'sitename' => $GLOBALS["CONF"]["SiteName"]
	);

	$str=ShowTplTemp($TempArr["start"],$var);


	//显示模块频道名称
		$var=array (
			'channel' => "网站超市"
		);

		$str.=ShowTplTemp($TempArr["col"],$var);

		//页头标题
		$GLOBALS["pagetitle"]="网站超市";


	switch($pagename){

		case "query":
			
			if($_GET["catid"]!="0" && $_GET["catid"]!=""){
				$msql->query("select * from {P}_webmall_tempcat where catid='".$_GET["catid"]."'");
				if($msql->next_record()){
					$cat=$msql->f('cat');
					$catid=$msql->f('catid');
				}
				$url=ROOTPATH."webmall/query.php?catid=".$catid;
				$var=array (
				'nav' => $cat,
				'url' => $url
				);
				$str.=ShowTplTemp($TempArr["list"],$var);
				$GLOBALS["pagetitle"].="-".$cat;
			}elseif($_GET["typeid"]!="0" && $_GET["typeid"]!=""){
				$msql->query("select * from {P}_webmall_temptype where catid='".$_GET["typeid"]."'");
				if($msql->next_record()){
					$cat=$msql->f('cat');
					$catid=$msql->f('catid');
				}
				$url=ROOTPATH."webmall/query.php?typeid=".$catid;
				$var=array (
				'nav' => $cat,
				'url' => $url
				);
				$str.=ShowTplTemp($TempArr["list"],$var);
				$GLOBALS["pagetitle"].="-".$cat;
			}

		break;

		case "detail":

		$var=array (
		'nav' => "产品介绍 &gt; ".$GLOBALS["spoolname"],
		);
		$str.=ShowTplTemp($TempArr["con"],$var);

		$GLOBALS["pagetitle"]="您想制作".$GLOBALS["spoolname"]."吗？不用找人定制，就买现成的！";


		break;	

		case "buy":

		$var=array (
		'nav' => $GLOBALS["PSET"]["name"],
		);
		$str.=ShowTplTemp($TempArr["con"],$var);

		$GLOBALS["pagetitle"].="-".$GLOBALS["PSET"]["name"];


		break;	


		case "main":


		break;	

		
	}




	$str.=$TempArr["end"];
	return $str;
}

?>