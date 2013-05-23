<?php
define("ROOTPATH", "../");
include(ROOTPATH."includes/common.inc.php");
include("language/".$sLan.".php");
include("includes/maq.inc.php");

$act = $_POST['act'];

switch($act){


	//提交
	case "maqsend":
		
		$REMOTE_ADDR=$_SERVER["REMOTE_ADDR"];
	
		$title=htmlspecialchars($_POST["title"]);
		$useedit=htmlspecialchars($_POST["useedit"]);
		$catid=htmlspecialchars($_POST["catid"]);
		$pid=htmlspecialchars($_POST["pid"]);
		$cent=htmlspecialchars($_POST["cent"]);
		$expday=htmlspecialchars($_POST["expday"]);
		$body=Url2Path($_POST["body"]);
				
		$uptime=time();
		$backtime=time();
		$dtime=time();
		$exptime=$expday*86400+time();
		$centid=$GLOBALS["MAQCONF"]["MaqCentId"];
		if($pid==""){$pid=0;}
		
		//jform是在iframe中实现的，需要给中文提示加上编码
		$Meta="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		
		if(!isLogin()){
			echo "NOTLOGIN";
			exit;
		}
		
		//获取会员信息
		$memberid=$_COOKIE["MEMBERID"];
		$pname=$_COOKIE["MEMBERPNAME"];

		//校验
		if($title==""){
			echo $Meta.$strMaqNTC1;
			exit;
		}

		if($body==""){
			echo $Meta.$strMaqNTC8;
			exit;
		}


		//关键词过滤
		$DenyArr=explode(",",$GLOBALS["MAQCONF"]["KeywordDeny"]);
		for($i=0;$i<sizeof($DenyArr);$i++){
			if(strlen($DenyArr[$i])>2){
				if(strstr($body,$DenyArr[$i]) || strstr($title,$DenyArr[$i])){
					echo $strMaqNTC13;
					exit;
				}
			}
		}

		//标签过滤
		$title=str_replace("{#","",$title);
		$title=str_replace("#}","",$title);
		$body=str_replace("{#","{ #",$body);
		$body=str_replace("#}","# }",$body);


		//图形验证
		$ImgCode=$_POST["ImgCode"];
		$Ic=$_COOKIE["CODEIMG"];
		$Ic=strrev($Ic)+5*2-9;
		$Ic=substr ($Ic,0,4);

		if($ImgCode=="" || $Ic!=$ImgCode){
			echo $Meta.$strIcErr;
			exit;
		}

		//如果是提问,悬赏分检测
		if($pid=="0"){
			$centv="cent".$centid;
			$msql->query("select * from {P}_member where memberid='$memberid' limit 0,1");
			if($msql->next_record()){
				$nowcent=$msql->f($centv);
			}
			
			if($nowcent<$cent){
				$centnamev="centname".$centid;
				$fsql->query("select * from {P}_member_centset");
				if($fsql->next_record()){
					$centname=$fsql->f($centnamev);
				}
				echo $Meta.$strMaqNTC12.$centname." (".$strMaqNTC15.$nowcent.$centname.")";
				exit;
			}
		}


		//回复时，判断是否提问者或已回答过，作为补充问题或补充回答，不重新入库
		if($pid!="0"){

			//补充问题
			$msql->query("select memberid,pname from {P}_maq where id='$pid' limit 0,1");
			if($msql->next_record()){
				$maqmemberid=$msql->f('memberid');
				$maqpname=$msql->f('pname');
				if($maqmemberid==$_COOKIE["MEMBERID"]){
					$body="<div class=addinfo>".$strAnswerType1.": ".date("Y-n-j H:i:s",$dtime)." BY ".$maqpname."</div>".$body;
					$fsql->query("update {P}_maq set uptime='$dtime',`body`=concat(body,'$body') where id='$pid'");
					echo "OK_".$pid;
					exit;
				}
			}else{
				echo $Meta.$strMaqNTC16;
				exit;
			}

			//补充回答
			$msql->query("select id,pname from {P}_maq where pid='$pid' and memberid='".$_COOKIE["MEMBERID"]."' limit 0,1");
			if($msql->next_record()){
				$oldbackid=$msql->f('id');
				$oldbackpname=$msql->f('pname');
				$body="<div class=addinfo>".$strAnswerType2.": ".date("Y-n-j H:i:s",$dtime)." BY ".$oldbackpname."</div>".$body;
				$fsql->query("update {P}_maq set uptime='$dtime',`body`=concat(body,'$body') where id='$oldbackid'");
				$fsql->query("update {P}_maq set uptime='$dtime',backtime='$dtime',lastname='$oldbackpname',lastmemberid='".$_COOKIE["MEMBERID"]."' where id='$pid'");
				echo "OK_".$pid;
				exit;

			}
			
		}

		


		//是否审核
		if($GLOBALS["MAQCONF"]["IfCheck"]=="1"){
			$iffb=0;
		}else{
			$iffb=1;
		}

		//catpath
		$msql->query("select catpath from {P}_maq_cat where catid='$catid'");
		if($msql->next_record()){
			$catpath=$msql->f('catpath');
		}
		
		//入库

		$msql->query("insert into {P}_maq set
		   `pid`='$pid',
		   `catid`='$catid',
		   `catpath`='$catpath',
		   `pname`='$pname',
		   `title`='$title',
		   `body`='$body',
		   `dtime`='$dtime',
		   `uptime`='$uptime',
		   `exptime`='$exptime',
		   `ip`='$REMOTE_ADDR',
		   `iffb`='$iffb',
		   `ifopen`='1',
		   `tuijian`='0',
		   `cl`='0',
		   `lastname`='$pname',
		   `lastmemberid`='$memberid',
		   `backcount`='0',
		   `centid`='$centid',
		   `cent`='$cent',
		   `stat`='0',
		   `xuhao`='1',
		   `memberid`='$memberid'
		
		");

		$nowbbsid=$msql->instid();
		

		//PID!=0是回答
		if($pid!="0" && $pid!="" ){

			//重新计算上级贴的回复数
			$msql->query("select count(id) from {P}_maq where pid='$pid' and iffb='1'");
			if($msql->next_record()){
				$backcount=$msql->f('count(id)');
			}
			
			//更新主记录
			$msql->query("update {P}_maq set 
			uptime='$uptime',
			backtime='$backtime',
			lastname='$pname',
			lastmemberid='$memberid',
			backcount='$backcount' 
			where id='$pid'");


			//回复问题积分计算
			MemberCentUpdate($memberid,"172");

			
			//短信通知提问者
			$msql->query("select memberid from {P}_maq where id='$pid'");
			if($msql->next_record()){
				$tomemberid=$msql->f('memberid');
			}

			if($tomemberid!="0" && $tomemberid!="-1"){
				$msg=$pname.$strMaqNTC11."\n<a href=\"../maq/html/?".$pid.".html\">maq/html/?".$pid.".html</a>";
				$msql->query("insert into {P}_member_msn set
				`body`='$msg',
				`tomemberid`='$tomemberid',
				`frommemberid`='0',
				`dtime`='$dtime',
				`iflook`='0'
				");
			}


			//返回
			if($iffb=="1"){
				echo "OK_".$pid;
				exit;
			}else{
				echo "CHK";
				exit;
			}
		

		//PID=0是提问
		}else{

			//扣除悬赏分
			$memo=$strMaqNTC5."(ID:".$nowbbsid.")";
			$msql->query("update {P}_member set ".$centv."=".$centv."-".$cent." where memberid='$memberid'");
			$msql->query("insert into {P}_member_centlog set `memberid`='$memberid',`dtime`='$dtime',`event`='0',`memo`='".$memo."',`".$centv."`='-".$cent."'");


			//提问统一积分计算
			MemberCentUpdate($memberid,"171");

			if($iffb=="1"){
				echo "OK_".$nowbbsid;
				exit;
			}else{
				echo "CHK";
				exit;
			}
		}
		
	break;



	//判断是否版主，决定是否显示版主功能链接
	case "ifbanzhu" :
		
		$maqid=$_POST["maqid"];

		if(!isLogin()){
			echo "NO";
			exit;
		}


		$msql->query("select catid from {P}_maq where id='$maqid'");
		if($msql->next_record()){
			$catid=$msql->f('catid');
		}

		$msql->query("select pid from {P}_maq_cat where catid='$catid'");
		if($msql->next_record()){
			$pid=$msql->f('pid');
			if($pid==0){
				$seccatid=$catid;
			}else{
				$seccatid=$pid;
			}
		}
		
		$secureset=SecureBanzhu("179");

		if(strstr($secureset,":".$seccatid.":")){
			echo "YES";
			exit;
		}else{
			echo "NO";
			exit;
		}

	break;


	//版主推荐
	case "banzhutj" :

		$maqid=$_POST["maqid"];

		if(!isLogin()){
			echo $strNoRights;
			exit;
		}

		//权限校验
		$msql->query("select catid,tuijian,memberid from {P}_maq where id='$maqid'");
		if($msql->next_record()){
			$catid=$msql->f('catid');
			$tuijian=$msql->f('tuijian');
			$mid=$msql->f('memberid');
		}


		$msql->query("select pid from {P}_maq_cat where catid='$catid'");
		if($msql->next_record()){
			$pid=$msql->f('pid');
			if($pid==0){
				$seccatid=$catid;
			}else{
				$seccatid=$pid;
			}
		}

		
		$secureset=SecureBanzhu("179");

		if(!strstr($secureset,":".$seccatid.":")){
			echo $strNoRights;
			exit;
		}

		//校验是否已经推荐(防止重复加分)
		if($tuijian!="0"){
			echo $strMaqNTC7;
			exit;
		}

		
		$msql->query("update {P}_maq set tuijian='1' where id='$maqid'");


		//积分计算
		MemberCentUpdate($mid,"175");

		echo "OK";
		exit;

	break;


	//版主删除
	case "banzhudel" :

		$maqid=$_POST["maqid"];
		$koufen=$_POST["koufen"];

		if(!isLogin()){
			echo $strNoRights;
			exit;
		}

		//权限校验
		$msql->query("select catid,memberid,pid from {P}_maq where id='$maqid'");
		if($msql->next_record()){
			$pid=$msql->f('pid');
			$catid=$msql->f('catid');
			$mid=$msql->f('memberid');
		}else{
			echo $strMaqNTC10;
			exit;
		}

		$msql->query("select pid from {P}_maq_cat where catid='$catid'");
		if($msql->next_record()){
			$catpid=$msql->f('pid');
			if($catpid==0){
				$seccatid=$catid;
			}else{
				$seccatid=$catpid;
			}
		}

		
		$secureset=SecureBanzhu("179");

		if(!strstr($secureset,":".$seccatid.":")){
			echo $strNoRights;
			exit;
		}



		//对于主记录,删除回复记录
		if($pid=="0" && $maqid!="0"){
			$fsql->query("delete from {P}_maq where pid='$maqid'");
		}

		//对于子记录,减少主记录回复计数
		if($pid!="0"){
			$fsql->query("update {P}_maq set backcount=backcount-1 where id='$pid'");
		}

		//删除记录
		$fsql->query("delete from {P}_maq where id='$maqid'");

		
		//积分计算
		if($koufen=="yes"){
			MemberCentUpdate($mid,"176");
		}


		echo "OK";
		exit;

	break;


	//采纳答案
	case "setbest" :

		$backid=$_POST["backid"];

		if(!isLogin()){
			echo "1000";
			exit;
		}else{
			$memberid=$_COOKIE["MEMBERID"];
		}


		//校验主题贴身份
		$msql->query("select * from {P}_maq where id='$backid' limit 0,1");
		if($msql->next_record()){
			$pid=$msql->f('pid');
			$answermemberid=$msql->f('memberid');
			$bestname=$msql->f('pname');
		}else{
			echo "1001";
			exit;
		}

		$msql->query("select * from {P}_maq where id='$pid' limit 0,1");
		if($msql->next_record()){
			$maqmemberid=$msql->f('memberid');
			$stat=$msql->f('stat');
			$ifopen=$msql->f('ifopen');
			$cent=$msql->f('cent');
		}else{
			echo "1002";
			exit;
		}

		if($maqmemberid!=$memberid){
			echo "1003";
			exit;
		}

		if($stat=="1"){
			echo "1004";
			exit;
		}

		if($ifopen!="1"){
			echo "1006";
			exit;
		}

		if($maqmemberid==$answermemberid){
			echo "1005";
			exit;
		}

		$centid=$GLOBALS["MAQCONF"]["MaqCentId"];
		$centv="cent".$centid;


		//问题状态标注
		$now=time();
		$msql->query("update {P}_maq set 
			`stat`='1',
			`endtime`='$now',
			`bestname`='$bestname', 
			`bestmemberid`='$answermemberid', 
			`bestanswerid`='$backid'
			where id='$pid'");
		

		//最佳回答标注
		$msql->query("update {P}_maq set `stat`='1' where id='$backid'");


		//把悬赏分给最佳答案回答者
		$memo=$strMaqNTC6."(ID:".$pid.")";
		$msql->query("update {P}_member set ".$centv."=".$centv."+".$cent." where memberid='$answermemberid'");
		$msql->query("insert into {P}_member_centlog set `memberid`='$answermemberid',`dtime`='$now',`event`='0',`memo`='".$memo."',`".$centv."`='".$cent."'");

		//对提问者采纳答案的奖励
		MemberCentUpdate($maqmemberid,"173");

		//对最佳答案者的奖励
		MemberCentUpdate($answermemberid,"174");


		echo "OK";
		exit;

	break;


	//关闭结束的问题
	case "setclose" :

		$maqid=$_POST["maqid"];

		if(!isLogin()){
			echo "1000";
			exit;
		}else{
			$memberid=$_COOKIE["MEMBERID"];
		}

		$msql->query("select * from {P}_maq where id='$maqid' and memberid='$memberid' limit 0,1");
		if($msql->next_record()){
			$stat=$msql->f('stat');
			$ifopen=$msql->f('ifopen');
			$cent=$msql->f('cent');
		}else{
			echo "1001";
			exit;
		}

		if($stat=="1"){
			echo "1002";
			exit;
		}

		if($ifopen!="1"){
			echo "1003";
			exit;
		}

		$centid=$GLOBALS["MAQCONF"]["MaqCentId"];
		$centv="cent".$centid;

		//问题状态标注为关闭
		$now=time();
		$msql->query("update {P}_maq set `endtime`='$now',`ifopen`='0' where id='$maqid'");

		//把悬赏分还给提问者
		$memo=$strMaqNTC17."(ID:".$maqid.")";
		$msql->query("update {P}_member set ".$centv."=".$centv."+".$cent." where memberid='$memberid'");
		$msql->query("insert into {P}_member_centlog set `memberid`='$memberid',`dtime`='$now',`event`='0',`memo`='".$memo."',`".$centv."`='".$cent."'");


		echo "OK";
		exit;

	break;

}
?>