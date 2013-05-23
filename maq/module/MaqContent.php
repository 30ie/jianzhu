<?php

/*
	[插件名称] 详情和回复
	[适用范围] 详情页
*/


function MaqContent(){
		

	global $msql,$fsql,$tsql,$strGuest;

	
	$tempname=$GLOBALS["PLUSVARS"]["tempname"];



	//获取地址栏参数
	if(strstr($_SERVER["QUERY_STRING"],".html")){
		$idArr=explode(".html",$_SERVER["QUERY_STRING"]);
		$id=$idArr[0];
	}elseif(isset($_GET["id"]) && $_GET["id"]!=""){
		$id=$_GET["id"];
	}	


	$Temp=LoadTemp($tempname);
	$TempArr=SplitTblTemp($Temp);

	$str=$TempArr["start"];


	$page=$_GET["page"];




	$fsql->query("select * from {P}_maq where id='$id'");
	if($fsql->next_record()){
		$memberid=$fsql->f("memberid");
		$catid=$fsql->f('catid');
		$title=$fsql->f('title');
		$lastname=$fsql->f("lastname");
		$lastmemberid=$fsql->f('lastmemberid');
		$ip=$fsql->f("ip");
		$dtime=$fsql->f("dtime");
		$uptime=$fsql->f("uptime");
		$backtime=$fsql->f('backtime');
		$cl=$fsql->f("cl");
		$iffb=$fsql->f("iffb");
		$ifopen=$fsql->f("ifopen");
		$body=$fsql->f('body');
		$cent=$fsql->f('cent');
		$stat=$fsql->f('stat');
		$exptime=$fsql->f('exptime');
		$endtime=$fsql->f('endtime');

	}else{
		$str.=$TempArr["err1"];
		return $str;
	}

	$fsql->query("update {P}_maq set cl=cl+1 where id='$id' and iffb='1'");


	//管理员可以查看未审核的点评
	if(AdminCheckModle()==false && ($iffb!="1" || $ifopen!="1")){
			$str.=$TempArr["err1"];
			return $str;
	}
	

	
	$fsql->query("select count(id) from {P}_maq where pid='$id' and iffb='1'");
	if($fsql->next_record()){
		$count=$fsql->f('count(id)');
	}

	//会员信息
	$fsql->query("select * from {P}_member where memberid='$memberid'");
	if($fsql->next_record()){
		$pname=$fsql->f("pname");
		$signature=$fsql->f("signature");
		$nowface=$fsql->f("nowface");
		$cent1=$fsql->f("cent1");
		$cent2=$fsql->f("cent2");
		$cent3=$fsql->f("cent3");
		$cent4=$fsql->f("cent4");
		$cent5=$fsql->f("cent5");
	}
	$showmemberlink="block";
	$memberurl=ROOTPATH."member/home.php?mid=".$memberid;

	$arr=explode(".",$ip);
	$ip=$arr[0].".".$arr[1].".".$arr[2]."."."*";
	$face=ROOTPATH."member/face/".$nowface.".gif";


	//有无回复
	if($backtime=="0"){
		$showback="none";
	}else{
		$showback="";
	}

	$dtime=date("Y-n-j H:i:s",$dtime);
	$uptime=date("Y-n-j H:i:s",$uptime);
	$backtime=date("Y-n-j H:i:s",$backtime);
	$endtime=date("Y-n-j H:i:s",$endtime);
	$exp=date("Y-n-j H:i:s",$exptime);

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
		$showbackform="none";
	}

	

	//解决状态
	global $strMaqStat0,$strMaqStat1,$strMaqStat2,$strOked;
	if($stat=="1"){
		$statstr="<span class='maqok'>".$strMaqStat1."</span>";
		$expstr=$strOked;
		$showbackform="none";
		$showsetclose="none";
	}else{

		if($exptime>time()){
			$statstr="<span class='maqnotok'>".$strMaqStat0."</span>";
			$showsetclose="none";
		}else{
			$statstr="<span class='maqexped'>".$strMaqStat2."</span>";
			if(isLogin() && $memberid==$_COOKIE["MEMBERID"]){
				$showsetclose="block";
			}else{
				$showsetclose="none";
			}
		}
	}


	//是否显示采纳答案,显示补充或回答的类型
	global $strAnswerType1,$strAnswerType2,$strAnswerType3;
	
	//默认回答类型
	$answertype=$strAnswerType3;

	if(isLogin()){
		if($memberid==$_COOKIE["MEMBERID"] && $stat!="1"){
			$showsetbest="block";
			$answertype=$strAnswerType1;
		}else{
			$showsetbest="none";
		}
	}else{
		$showsetbest="none";
	}

	
	//积分名称
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


	//翻页和显示回复

	$scl="pid='$id' and iffb='1'";
	
	include(ROOTPATH."includes/pages.inc.php");
	$pages=new pages;

	$totalnums=$count;

	
	$pages->setvar(array("id" => $id));

	$pages->set(10,$totalnums);		                          
		
	$pagelimit=$pages->limit();	  

	$pagesinfo=$pages->ShowNow();

	$var=array (
		'maqid' => $id, 
		'body' => $body, 
		'memberid' => $memberid, 
		'dtime' => $dtime, 
		'uptime' => $uptime, 
		'backtime' => $backtime, 
		'endtime' => $endtime, 
		'showback' => $showback, 
		'showsetclose' => $showsetclose, 
		'expstr' => $expstr, 
		'exp' => $exp, 
		'centname' => $centname,
		'cent' => $cent,
		'statstr' => $statstr,
		'lastname' => $lastname,
		'title' => $title, 
		'ip' => $ip, 
		'face' => $face, 
		'showmemberlink' => $showmemberlink, 
		'showsetbest' => $showsetbest, 
		'memberurl' => $memberurl, 
		'signature' => $signature, 
		'count' => $count, 
		'pname' => $pname, 
		'cl' => $cl,
		'centname1' => $centname1,
		'centname2' => $centname2,
		'centname3' => $centname3,
		'centname4' => $centname4,
		'centname5' => $centname5,
		'cent1' => $cent1,
		'cent2' => $cent2,
		'cent3' => $cent3,
		'cent4' => $cent4,
		'cent5' => $cent5,
		'showpages' => $pages->output(1),
		'pagestotal' => $pagesinfo["total"],
		'pagesnow' => $pagesinfo["now"],
		'pagesshownum' => $pagesinfo["shownum"],
		'pagesfrom' => $pagesinfo["from"],
		'pagesto' => $pagesinfo["to"],
		'totalnums' => $totalnums
	);

	if($page=="" || $page<=1){
		$str.=ShowTplTemp($TempArr["con"],$var);
	}


	//已解决的问题，最佳答案在第一个
	if($stat=="1"){
		$backord=" stat desc,id asc";
	}else{
		$backord=" id asc";
	}

	$fsql->query("select * from {P}_maq where $scl order by $backord limit $pagelimit");
	while($fsql->next_record()){
		$backid=$fsql->f('id');
		$stitle=$fsql->f('title');
		$smemberid=$fsql->f("memberid");
		$sip=$fsql->f("ip");
		$sdtime=$fsql->f("dtime");
		$scl=$fsql->f("cl");
		$sbody=$fsql->f('body');
		$backstat=$fsql->f('stat');
		$arr=explode(".",$sip);
		$sip=$arr[0].".".$arr[1].".".$arr[2]."."."*";
		$sdtime=date("Y-n-j H:i:s",$sdtime);


		//会员信息
		$msql->query("select * from {P}_member where memberid='$smemberid'");
		if($msql->next_record()){
			$spname=$msql->f("pname");
			$ssignature=$msql->f("signature");
			$snowface=$msql->f("nowface");
			$scent1=$msql->f("cent1");
			$scent2=$msql->f("cent2");
			$scent3=$msql->f("cent3");
		}
		$showmemberlink="block";
		$memberurl=ROOTPATH."member/home.php?mid=".$smemberid;

		$sface=ROOTPATH."member/face/".$snowface.".gif";

		//自给回复自给的问题不能采纳答案
		if($memberid==$smemberid){
			$showsetbest="none";
		}

		//如果当前会员已有回复，显示补充答案
		if(isLogin() && $smemberid==$_COOKIE["MEMBERID"]){
			$answertype=$strAnswerType2;
		}

		//显示最佳答案标示
		if($backstat=="1"){
			$bestanswer="block";
		}else{
			$bestanswer="none";
		}


		$var=array (
		'backid' => $backid, 
		'body' => $sbody, 
		'memberid' => $smemberid, 
		'dtime' => $sdtime, 
		'bestanswer' => $bestanswer, 
		'endtime' => $endtime, 
		'ip' => $sip, 
		'face' => $sface, 
		'showmemberlink' => $showmemberlink, 
		'showsetbest' => $showsetbest, 
		'memberurl' => $memberurl, 
		'signature' => $ssignature, 
		'centname1' => $centname1,
		'centname2' => $centname2,
		'centname3' => $centname3,
		'cent1' => $scent1,
		'cent2' => $scent2,
		'cent3' => $scent3,
		'pname' => $spname
		);
		$str.=ShowTplTemp($TempArr["list"],$var);


	}



	$var=array (
	'showpages' => $pages->output(1),
	'pagestotal' => $pagesinfo["total"],
	'pagesnow' => $pagesinfo["now"],
	'pagesshownum' => $pagesinfo["shownum"],
	'pagesfrom' => $pagesinfo["from"],
	'pagesto' => $pagesinfo["to"],
	'totalnums' => $totalnums,
	'title' => $title,
	'catid' => $catid,
	'nowpage' => $page,
	'showbackform' => $showbackform,
	'answertype' => $answertype, 
	'pid' => $id

	);

	$str.=ShowTplTemp($TempArr["end"],$var);


	//网页标题和当前内容ID
	$GLOBALS["maqtitle"]=$title;
	
	return $str;

}



?>