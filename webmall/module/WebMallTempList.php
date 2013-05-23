<?php

/*
	[插件名称] 自选模板列表
	[适用范围] 全站
*/

function WebMallTempList(){

	global $fsql,$msql;

		
		$coltitle=$GLOBALS["PLUSVARS"]["coltitle"];
		$shownums=$GLOBALS["PLUSVARS"]["shownums"];
		$ord=$GLOBALS["PLUSVARS"]["ord"];
		$sc=$GLOBALS["PLUSVARS"]["sc"];
		$cutword=$GLOBALS["PLUSVARS"]["cutword"];
		$target=$GLOBALS["PLUSVARS"]["target"];
		$catid=$GLOBALS["PLUSVARS"]["catid"];
		$tags=$GLOBALS["PLUSVARS"]["tags"];
		$pagename=$GLOBALS["PLUSVARS"]["pagename"];
		$tempname=$GLOBALS["PLUSVARS"]["tempname"];
		$picw=$GLOBALS["PLUSVARS"]["picw"];
		$pich=$GLOBALS["PLUSVARS"]["pich"];
		$fittype=$GLOBALS["PLUSVARS"]["fittype"];


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

		//默认条件		
		$scl=" id!='0' ";

		if($catid!=0 && $catid!=""){
			$scl.=" and typeid='$catid' ";
		}


		//模版解释
		$Temp=LoadTemp($tempname);
		$TempArr=SplitTblTemp($Temp);

		$var=array(
			'coltitle' => $coltitle,
			'morelink' => $morelink
		);
		$str=ShowTplTemp($TempArr["start"],$var);
		

		$picnum=1;
		$fsql->query("select * from {P}_webmall_spool where $scl order by $ord $sc limit 0,$shownums");

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


			$picnum++;

			}

			$var=array(
				'fittype' => $fittype
			);
			
			$str.=ShowTplTemp($TempArr["end"],$var);


		return $str;

}

?>