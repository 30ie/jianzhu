<?php

/*
	[插件名称] 点评分类
	[适用范围] 所有页面

*/



function MaqClass(){

		global $msql,$fsql;

		$coltitle=$GLOBALS["PLUSVARS"]["coltitle"];
		$target=$GLOBALS["PLUSVARS"]["target"];
		$tempname=$GLOBALS["PLUSVARS"]["tempname"];

		
	
		//模版解释
		$Temp=LoadTemp($tempname);
		$TempArr=SplitTblTemp($Temp);

		
		//循环开始
		$var=array('coltitle' => $coltitle);
		$str=ShowTplTemp($TempArr["start"],$var);

		
		$msql->query("select * from {P}_maq_cat where pid='0' order by xuhao");
		while($msql->next_record()){
				$catid=$msql->f("catid");
				$cat=$msql->f("cat");

				$fsql->query("select count(id) from {P}_maq where iffb='1' and catid='$catid' and pid='0'");
				if($fsql->next_record()){
					$nums=$fsql->f("count(id)");
				}

				$link=ROOTPATH."maq/class/?".$catid.".html";

				$var=array (
				'nums' => $nums, 
				'link' => $link, 
				'cat' => $cat, 
				'target' => $target
				);
				$str.=ShowTplTemp($TempArr["list"],$var);

		}


        $str.=$TempArr["end"];
       
		return $str;

		
}


?>