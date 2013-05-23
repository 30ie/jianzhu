<?php


function WebMallGl(){

	global $fsql,$msql;

	$tempname=$GLOBALS["PLUSVARS"]["tempname"];
	$memberid=$_COOKIE["MEMBERID"];


	//模板解释
	$Temp=LoadTemp($tempname);
	$TempArr=SplitTblTemp($Temp);

	$str=$TempArr["start"];

	$scl=" clientid='$memberid' ";

	//获取连接参数
	$msql->query("select * from {P}_webmall_config");
	while($msql->next_record()){
		$variable=$msql->f('variable');
		$value=$msql->f('value');
		$WEBMALLCONF[$variable]=$value;
	}
	$AgentApi=$WEBMALLCONF["AgentApi"];
	$AgentUser=$WEBMALLCONF["AgentUser"];
	$AgentPasswd=$WEBMALLCONF["AgentPasswd"];

	//连接代理接口
	include(ROOTPATH."base/nusoap/nusoap.php");

	$server   = "http://".$AgentApi."/webtry/admin/vp/soapserver.php";
	$customer = new soapclientx ($server);

	$mdpass=md5($AgentPasswd);
	$params  = array (
	'agentuser'  => $AgentUser,
	'agentpasswd'  => $mdpass,
	'scl'  => $scl
	);

	$resault=$customer -> call ("SITECOUNT", $params);

	if ($err=$customer->getError()) {
		$errinfo=$customer->response;
		return "主站代理接口连接失败".$err.$errinfo;
	}

	if($resault[0]=="5000"){
		return "主站代理帐号校验未通过，代理帐号或密码错误";
	}

	if($resault[0]=="9999"){
		$totalnums=$resault[1];
	}
	

	include(ROOTPATH."includes/pages.inc.php");
	$pages=new pages;
	
	$pages->setvar(array("key" => $key));

	$pages->set(10,$totalnums);		                          
		
	$pagelimit=$pages->limit();	  

		
		//远程读取记录
		$params  = array (
		'agentuser'  => $AgentUser,
		'agentpasswd'  => $mdpass,
		'scl'  => $scl,
		'pagelimit'  => $pagelimit
		);

		$resault=$customer -> call ("SITELIST", $params);

		if ($err=$customer->getError()) {
			$errinfo=$customer->response;
			return "主站代理接口连接失败".$err.$errinfo;
		}

		if($resault=="5000"){
			return "主站代理帐号校验未通过，代理帐号或密码错误";
		}


		if($resault!="0000"){
		
			$nums=sizeof($resault);
			for($i=0;$i<$nums;$i++){
				$userid=$resault[$i]["userid"];
				$spool=$resault[$i]["spool"];
				$spoolname=$resault[$i]["spoolname"];
				$siteurl=$resault[$i]["siteurl"];
				$uuser=$resault[$i]["user"];
				$upasswd=$resault[$i]["passwd"];
				$clientid=$resault[$i]["clientid"];
				$regtime=$resault[$i]["regtime"];
				$exptime=$resault[$i]["exptime"];
				$stat=$resault[$i]["stat"];
				$serverdomain=$resault[$i]["serverdomain"];
				$ifok=$resault[$i]["ifok"];

				$regtime=date("y-n-j",$regtime);
				$exptime=date("y-n-j",$exptime);

				$fsql->query("select * from {P}_webmall_spool where spool='$spool' limit 0,1");
				if($fsql->next_record()){
					$spoolname=$fsql->f('name');
					$spoolid=$fsql->f('id');
				}

				switch($ifok){
					case "0":
						$statimg="no.png";
					break;
					case "1":
						$statimg="alert.png";
					break;
					case "2":
						$statimg="ok.png";
					break;
				}
				

				switch($stat){
					case "0":
						$showstat="<font color='#ff0000'>关闭</font>";
					break;
					case "1":
						$showstat="开启";
					break;
				}

				$var=array (
				'userid' => $userid,
				'spool' => $spool, 
				'spoolname' => $spoolname, 
				'showstat' => $showstat, 
				'statimg' => $statimg, 
				'regtime' => $regtime, 
				'exptime' => $exptime, 
				'siteurl' => $siteurl
				);

				$str.=ShowTplTemp($TempArr["list"],$var);
		
		
			}
	}

		$str.=$TempArr["end"];

		$pagesinfo=$pages->ShowNow();

		$var=array (
		'key' => $key,
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