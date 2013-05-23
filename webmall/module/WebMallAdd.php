<?php

/*
	[插件名称] 站点申请
	[适用范围] 站点申请
*/

function WebMallAdd(){

	global $msql,$fsql;


	$tempname=$GLOBALS["PLUSVARS"]["tempname"];

	$id=$_GET["id"];

	if($id==""){
		return "NO ID";
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
	$AgentUser=$WEBMALLCONF["AgentUser"];
	$AgentPasswd=$WEBMALLCONF["AgentPasswd"];
	
	if($AgentSharePic=="1"){
		$suourl="http://".$AgentApi."/webtry/temppic/";
	}else{
		$suourl=ROOTPATH."webmall/temppic/";
	}
	

	//网站产品详情
	$msql->query("select * from {P}_webmall_spool where id='$id'");
	if($msql->next_record()){
		$name=$msql->f('name');
		$spool=$msql->f('spool');
		$nowtypeid=$msql->f('typeid');
		$nowcatid=$msql->f('catid');
		$designer=$msql->f('designer');
		$memo=$msql->f('memo');
	}

	$src=$suourl.$spool.".jpg";

	$fsql->query("select cat from {P}_webmall_tempcat where catid='$nowcatid'");
	if($fsql->next_record()){
		$tempcat=$fsql->f('cat');
	}

	$fsql->query("select cat from {P}_webmall_temptype where catid='$nowtypeid'");
	if($fsql->next_record()){
		$temptype=$fsql->f('cat');
	}

	$modules="";
	$fsql->query("select `module` from {P}_webmall_spoolmod where spool='$spool'");
	while($fsql->next_record()){
		$module=$fsql->f('module');
		$msql->query("select cname from {P}_webmall_modules where `module`='$module' limit 0,1");
		if($msql->next_record()){
			$modules.=$msql->f('cname').",";
		}
	}
	$modules=substr($modules,0,-1);

	
	//获取远程服务器列表
	include(ROOTPATH."base/nusoap/nusoap.php");

	$server   = "http://".$AgentApi."/webtry/admin/vp/soapserver.php";
	$customer = new soapclientx ($server);

	$mdpass=md5($AgentPasswd);
	$params  = array (
	'agentuser'  => $AgentUser,
	'agentpasswd'  => $mdpass
	);

	$resault=$customer -> call ("GETSERVERLIST", $params);

	//错误调试和连接失败判断
	if ($err=$customer->getError()) {
		$errinfo=$customer->response;
		return "主站代理接口连接失败:".$err.$errinfo;
	}

	if($resault=="5000"){
		return "主站代理帐号校验未通过，代理帐号或密码错误";
	}

	if(is_array($resault)){
		$nums=sizeof($resault);
		for($i=0;$i<$nums;$i++){
			$servername=$resault[$i]["servername"];
			$serverdomain=$resault[$i]["serverdomain"];
			$serverlist.="<option value='".$serverdomain."'>".$servername." [".$serverdomain."]</option>";

		}
	}


	$Temp=LoadTemp($tempname);

	$var=array (
	'name' => $name,
	'spool' => $spool,
	'src' => $src,
	'tempcat' => $tempcat, 
	'temptype' => $temptype, 
	'designer' => $designer,
	'serverlist' => $serverlist,
	'modules' => $modules, 
	'memo' => $memo
	);

	$str=ShowTplTemp($Temp,$var);

	return $str;
	
}

?>