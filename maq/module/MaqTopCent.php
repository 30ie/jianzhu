<?php

/*
	[插件名称] 未解决问题悬赏榜
	[适用范围] 全站

*/

function MaqTopCent(){

	global $fsql,$tsql,$strGuest;


		$coltitle=$GLOBALS["PLUSVARS"]["coltitle"];
		$shownums=$GLOBALS["PLUSVARS"]["shownums"];
		$cutword=$GLOBALS["PLUSVARS"]["cutword"];
		$target=$GLOBALS["PLUSVARS"]["target"];
		$showtj=$GLOBALS["PLUSVARS"]["showtj"];
		$catid=$GLOBALS["PLUSVARS"]["catid"];
		$tempname=$GLOBALS["PLUSVARS"]["tempname"];

		$now=time();
		$scl=" iffb='1' and ifopen='1' and stat='0' and pid='0' and exptime>$now ";


		if($showtj!="" && $showtj!="0"){
			$scl.=" and tuijian='1' ";
		}


		if($catid!="" && $catid!="0"){
			$catid=fmpath($catid);
			$scl.=" and catpath regexp '$catid' ";
		}


		//模版解释
		$Temp=LoadTemp($tempname);
		$TempArr=SplitTblTemp($Temp);

		$var=array(
			'coltitle' => $coltitle
		);

		$str=ShowTplTemp($TempArr["start"],$var);

		$picnum=1;
		$fsql->query("select * from {P}_maq where $scl order by cent desc limit 0,$shownums");

		while($fsql->next_record()){
			
			$id=$fsql->f('id');
			$catid=$fsql->f('catid');
			$title=$fsql->f('title');
			$memberid=$fsql->f('memberid');
			$body=$fsql->f('body');
			$dtime=$fsql->f('dtime');
			$uptime=$fsql->f('uptime');
			$backtime=$fsql->f('backtime');
			$cl=$fsql->f('cl');
			$pname=$fsql->f('pname');
			$stat=$fsql->f('stat');
			$cent=$fsql->f('cent');
			$lastname=$fsql->f('lastname');

			$memberurl=ROOTPATH."member/home.php?mid=".$memberid;


			$dtime=date("m/d",$dtime);
			$uptime=date("m/d",$uptime);
			$backtime=date("m/d",$backtime);

			if($cutword!="0"){$title=csubstr($title,0,$cutword);}


			$link=ROOTPATH."maq/html/?".$id.".html";
			

			$var=array (
			'cat' => $cat, 
			'title' => $title, 
			'dtime' => $dtime, 
			'pname' => $pname, 
			'picnum' => $picnum, 
			'memberurl' => $memberurl, 
			'body' => $body, 
			'count' => $count, 
			'stat' => $stat, 
			'cl' => $cl, 
			'cent' => $cent, 
			'link' => $link,
			'lastname' => $lastname,
			'face' => $face, 
			'target' => $target
			);
			$str.=ShowTplTemp($TempArr["list"],$var);


		$picnum++;
		}

		$str.=$TempArr["end"];


		return $str;

}

?>