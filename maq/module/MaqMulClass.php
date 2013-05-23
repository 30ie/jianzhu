<?php

/*
	[插件名称] 竖式二级分类
	[适用范围] 所有页面
*/


function MaqMulClass(){

		global $msql,$fsql,$tsql;

		$tempname=$GLOBALS["PLUSVARS"]["tempname"];
		$target=$GLOBALS["PLUSVARS"]["target"];


		//模版解释
		$Temp=LoadTemp($tempname);
		$TempArr=SplitTblTemp($Temp);
		
		$str=$TempArr["start"];


		$msql->query("select * from {P}_maq_cat where pid='0' order by xuhao");
		while($msql->next_record()){
				$bcatid=$msql->f("catid");
				$bcat=$msql->f("cat");
				$url=ROOTPATH."maq/class/?".$bcatid.".html";

			$catstr="";
			$bnums=0;
			$fsql->query("select * from {P}_maq_cat where pid='$bcatid' order by xuhao ");
			while($fsql->next_record()){
				$scatid=$fsql->f("catid");
				$scat=$fsql->f("cat");

				$tsql->query("select count(id) from {P}_maq where iffb='1' and pid='0' and catid='$scatid'");
				if($tsql->next_record()){
					$snums=$tsql->f("count(id)");
				}

				$bnums=$bnums+$snums;

				$surl=ROOTPATH."maq/class/?".$scatid.".html";

				$catstr.="<li><a href='".$surl."' target='".$target."'>".$scat." <span class='nums'>(".$snums.")</span></a></li>";

			}

			$var=array (
			'catstr' => $catstr, 
			'bnums' => $bnums, 
			'bcat' => $bcat
			);
			
			$str.=ShowTplTemp($TempArr["list"],$var);

		}
		
        $str.=$TempArr["end"];
		return $str;
				
}



?>