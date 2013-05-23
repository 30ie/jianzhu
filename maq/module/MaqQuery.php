<?php

/*
	[插件名称] 检索和提问
	[适用范围] 检索页
*/


function MaqQuery(){

	global $fsql,$tsql,$strGuest;
	
	$shownums=$GLOBALS["PLUSVARS"]["shownums"];
	$cutword=$GLOBALS["PLUSVARS"]["cutword"];
	$target=$GLOBALS["PLUSVARS"]["target"];
	$tempname=$GLOBALS["PLUSVARS"]["tempname"];


	//地址栏参数

	if(strstr($_SERVER["QUERY_STRING"],".html")){
		$Arr=explode(".html",$_SERVER["QUERY_STRING"]);
		$maqcatid=$catid=$Arr[0];
	}elseif($_GET["catid"]>0){
		$maqcatid=$catid=$_GET["catid"];
	}else{
		$maqcatid=$catid=0;
	}



	$page=$_GET["page"];
	$key=$_GET["key"];
	$myord=$_GET["myord"];
	$myshownums=$_GET["myshownums"];
	$myshowstat=$_GET["myshowstat"];
	$myshowtj=$_GET["myshowtj"];
	$shownoback=$_GET["shownoback"];
	$showmine=$_GET["showmine"];


	if($myshownums!="" && $myshownums!="0"){
		$shownums=$myshownums;
	}


	if($myord==""){
		$myord="uptime";
	}

	if($myord=="exptime"){
		$sc=" asc ";
	}else{
		$sc=" desc ";
	}

	//只显示自己的问题,验证登录
	if($showmine=="1"){
		if(!isLogin()){
			header("location:".ROOTPATH."member/login.php");
		}else{
			$mid=$_COOKIE["MEMBERID"];
		}
	}


	//悬赏积分名称
	$centid=$GLOBALS["MAQCONF"]["MaqCentId"];
	$fsql->query("select * from {P}_member_centset");
	if($fsql->next_record()){
		$centname1=$fsql->f('centname1');
		$centname2=$fsql->f('centname2');
		$centname3=$fsql->f('centname3');
		$centname4=$fsql->f('centname4');
		$centname5=$fsql->f('centname5');
	}
	$centstr="centname".$centid;
	$centname=$$centstr;


	//模版解释
	$Temp=LoadTemp($tempname);
	$TempArr=SplitTblTemp($Temp);

	$var=array ('maqcatid' => $maqcatid);

	$str=ShowTplTemp($TempArr["start"],$var);


	$scl=" iffb='1' and ifopen='1' and pid='0' ";

	if($catid!="0" && $catid!=""){
		$scl.=" and catid='$catid' ";
	}

	if($mid!="0" && $mid!=""){
		$scl.=" and memberid='$mid' ";
	}

	if($myshowstat=="0" || $myshowstat=="1"){
		$scl.=" and `stat`='$myshowstat' ";
	}

	if($myshowtj=="1"){
		$scl.=" and tuijian='1' ";
	}

	if($shownoback=="1"){
		$scl.=" and backcount='0' ";
	}

	if($key!=""){
		
		$scl.=" and (title regexp '$key' or body regexp '$key') ";
	}	
	
	include(ROOTPATH."includes/pages.inc.php");
	$pages=new pages;

	$totalnums=TblCount("_maq","id",$scl);
	
	$pages->setvar(array("catid" => $catid,"myord" => $myord,"myshownums" => $myshownums,"myshowstat" => $myshowstat,"showmine" => $showmine,"shownoback"=>$shownoback,"key" => $key));

	$pages->set($shownums,$totalnums);		                          
		
	$pagelimit=$pages->limit();	  

	$fsql->query("select * from {P}_maq where $scl order by $myord $sc limit $pagelimit");

	while($fsql->next_record()){
		
			$id=$fsql->f('id');
			$title=$fsql->f('title');
			$catid=$fsql->f('catid');
			$body=$fsql->f('body');
			$dtime=$fsql->f('dtime');
			$uptime=$fsql->f('uptime');
			$backtime=$fsql->f('backtime');
			$cl=$fsql->f('cl');
			$lastname=$fsql->f('lastname');
			$lastmemberid=$fsql->f('lastmemberid');
			$bestname=$fsql->f('bestname');
			$memberid=$fsql->f('memberid');
			$backcount=$fsql->f('backcount');
			$tuijian=$fsql->f('tuijian');
			$cent=$fsql->f('cent');
			$stat=$fsql->f('stat');
			$exptime=$fsql->f('exptime');
			$endtime=$fsql->f('endtime');
			
			$body=str_replace("<br>","...",$body);
			$body=str_replace("\n","...",$body);

			$body=strip_tags($body);
			$body=csubstr($body,0,80);



			//提问者信息
			$tsql->query("select * from {P}_member where memberid='$memberid' limit 0,1");
			if($tsql->next_record()){
				$pname=$tsql->f("pname");
				$nowface=$tsql->f("nowface");
			}
			$memberurl=ROOTPATH."member/home.php?mid=".$memberid;


			//回复者网址
			if($lastmemberid=="-1"){
				$lastmemberurl="#";
			}else{
				$lastmemberurl=ROOTPATH."member/home.php?mid=".$lastmemberid;
			}


			//是否今日新贴
			if($uptime>time()-86400){
				$querycss="binew";
			}else{
				$querycss="bi";
			}

			//有无回复
			if($backtime=="0"){
				$showback="none";
			}else{
				$showback="";
			}

			//推荐标记
			if($tuijian=="1"){
				$tjstr="<img src='".ROOTPATH."maq/templates/images/best.gif'>";
			}else{
				$tjstr="";
			}

			
			$face=ROOTPATH."member/face/".$nowface.".gif";

			$dtime=date("Y-n-j H:i",$dtime);
			$uptime=date("Y-n-j H:i",$uptime);
			$backtime=date("Y-n-j H:i",$backtime);
			$exp=date("Y-n-j H:i",$exptime);

			//计算剩余时间
			global $strExped,$strExp,$strExp1,$strExp2,$strExp3,$strExp4;
			if($exptime>time()){
				$second=$exptime-time();
				$expday=floor($second/86400);
				$second=$second-($expday*86400);
				$exphour=floor($second/3600);
				$second=$second-($exphour*3600);
				$expmin=floor($second/60);
				$second=$second-($expmin*60);
				$expstr=$strExp.$expday.$strExp1.$exphour.$strExp2.$expmin.$strExp3.$second.$strExp4;
			}else{
				$expstr=$strExped;
			}

			

			//解决状态
			global $strMaqStat0,$strMaqStat1,$strMaqStat2,$strOked;
			if($stat=="1"){
				$tsql->query("select uptime from {P}_maq where pid='$id' and `stat`='1' limit 0,1");
				if($tsql->next_record()){
					$besttime=$tsql->f("uptime");
					$besttime=date("Y-n-j H:i:s",$besttime);
				}

				$statstr="<span class='maqok'>".$strMaqStat1."</span>";
				$expstr=$strOked;
				$showbest="";
				$showback="none";
			}else{
				$besttime="";
				$showbest="none";
				if($exptime>time()){
					$statstr="<span class='maqnotok'>".$strMaqStat0."</span>";
				}else{
					$statstr="<span class='maqexped'>".$strMaqStat2."</span>";
				}
			}

			

			
			if($cutword!="0"){$title=csubstr($title,0,$cutword);}

			if($GLOBALS["CONF"]["CatchOpen"]=="1" && file_exists(ROOTPATH."maq/html/".$id.".html")){
				$link=ROOTPATH."maq/html/".$id.".html";
			}else{
				$link=ROOTPATH."maq/html/?".$id.".html";
			}





			$var=array (
			'title' => $title, 
			'tjstr' => $tjstr, 
			'dtime' => $dtime, 
			'uptime' => $uptime, 
			'backtime' => $backtime, 
			'bestname' => $bestname, 
			'besttime' => $besttime, 
			'showback' => $showback, 
			'showbest' => $showbest, 
			'expstr' => $expstr, 
			'exp' => $exp, 
			'centname' => $centname,
			'cent' => $cent,
			'statstr' => $statstr,
			'pname' => $pname, 
			'memberurl' => $memberurl, 
			'lastmemberurl' => $lastmemberurl, 
			'body' => $body, 
			'backcount' => $backcount, 
			'querycss' => $querycss, 
			'cl' => $cl, 
			'link' => $link,
			'lastname' => $lastname,
			'face' => $face, 
			'target' => $target
			);


			$str.=ShowTplTemp($TempArr["list"],$var);
		

	}

	

	$pagesinfo=$pages->ShowNow();


	//发布表单分类下拉
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
			if($maqcatid==$scatid){
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

	


	//最低最高悬赏分
	$centlist="";
	for($n=$GLOBALS["MAQCONF"]["CentStart"];$n<=$GLOBALS["MAQCONF"]["CentEnd"];$n=$n+5){
		$centlist.="<option value='".$n."'>".$n." ".$centname."</option>";
	}


	$var=array (
	'catlist' => $catlist,
	'centlist' => $centlist,
	'showpages' => $pages->output(1),
	'pagestotal' => $pagesinfo["total"],
	'pagesnow' => $pagesinfo["now"],
	'pagesshownum' => $pagesinfo["shownum"],
	'pagesfrom' => $pagesinfo["from"],
	'pagesto' => $pagesinfo["to"],
	'totalnums' => $totalnums
	);

	$str.=ShowTplTemp($TempArr["end"],$var);


	return $str;


}
?>