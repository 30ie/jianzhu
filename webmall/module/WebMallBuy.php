<?php

/*
	[插件名称] 软件套餐订购
	[适用范围] 软件套餐订购
*/

function WebMallBuy(){

	global $msql,$fsql;

	$memberid=$_COOKIE["MEMBERID"];
	$membertypeid=$_COOKIE["MEMBERTYPEID"];


	$tempname=$GLOBALS["PLUSVARS"]["tempname"];

	$id=$_GET["id"];
	$siteid=$_GET["siteid"];

	if($id==""){
		return "NO ID";
	}



	//获取会员类型
	$msql->query("select * from {P}_member where memberid='$memberid'");
	if($msql->next_record()){
		$name=$msql->f('name');
		$company=$msql->f('company');
		$membertypeid=$msql->f('membertypeid');
		$user=$msql->f('user');
		$email=$msql->f('email');
		$mov=$msql->f('mov');
		$addr=$msql->f('addr');
		$tel=$msql->f('tel');
		$postcode=$msql->f('postcode');
		$pname=$msql->f('pname');
		$membergroupid=$msql->f('membergroupid');
	}else{
		echo "MEMBER ERROR";
		exit;
	}

	if($membergroupid=="1"){
		$company=$name;
	}

	

	//网站产品资料
	$msql->query("select * from {P}_webmall_spool where id='$id'");
	if($msql->next_record()){
		$webname=$msql->f('name');
		$spool=$msql->f('spool');
		$designer=$msql->f('designer');
		$hostsize=$msql->f('hostsize');
		$price=$msql->f('price');
		$price1=$msql->f('price1');
		$price2=$msql->f('price2');
		$mtype1=$msql->f('mtype1');
		$mtype2=$msql->f('mtype2');
		$xufei=$msql->f('xufei');
		$xufei1=$msql->f('xufei1');
		$xufei2=$msql->f('xufei2');
		$xtype1=$msql->f('xtype1');
		$xtype2=$msql->f('xtype2');
	}

	if($mtype1==$membertypeid){
		$myprice=$price1;
	}elseif($mtype2==$membertypeid){
		$myprice=$price2;
	}else{
		$myprice=$price;
	}

	//产品包含的模块
	$modules="";
	$fsql->query("select `module` from {P}_webmall_spoolmod where spool='$spool'");
	while($fsql->next_record()){
		$module=$fsql->f('module');
		$modulearr[]=$module;
		$msql->query("select cname from {P}_webmall_modules where `module`='$module' limit 0,1");
		if($msql->next_record()){
			$modules.=$msql->f('cname').",";
		}
	}
	$modules=substr($modules,0,-1);

	if($modulearr==NULL || sizeof($modulearr)<1){
		$modules="无";
	}



	$Temp=LoadTemp($tempname);
	$TempArr=SplitTblTemp($Temp);

	$var=array (
	'showpasswd' => $showpasswd,
	'user' => $user,
	'name' => $name,
	'company' => $company,
	'addr' => $addr,
	'tel' => $tel,
	'mov' => $mov,
	'email' => $email,
	'postcode' => $postcode,
	'pname' => $pname,
	'webname' => $webname,
	'spool' => $spool,
	'siteid' => $siteid,
	'hostsize' => $hostsize,
	'designer' => $designer,
	'modules' => $modules, 
	'myprice' => $myprice
	);

	$str=ShowTplTemp($TempArr["start"],$var);



	//扩展模块列表

	$msql->query("select * from {P}_webmall_modules where `module`!='webmall'");
	while($msql->next_record()){
		$gid=$msql->f('id');
		$module=$msql->f('module');
		$modulename=$msql->f('cname');
		$price=$msql->f('price');
		$price1=$msql->f('price1');
		$price2=$msql->f('price2');
		$mtype1=$msql->f('mtype1');
		$mtype2=$msql->f('mtype2');
		$danwei=$msql->f('danwei');
	

		if($mtype1==$membertypeid){
			$moduleprice=$price1;
		}elseif($mtype2==$membertypeid){
			$moduleprice=$price2;
		}else{
			$moduleprice=$price;
		}

		$moduleprice=number_format($moduleprice,0,'.','');
		$var=array (
			'gid' => $gid,
			'module' => $module,
			'modulename' => $modulename,
			'danwei' => $danwei,
			'moduleprice' => $moduleprice
		);

		if($modulearr!=NULL && sizeof($modulearr)>=1){
			if(!in_array($module,$modulearr)){
				$str.=ShowTplTemp($TempArr["list"],$var);
			}
		}else{
			$str.=ShowTplTemp($TempArr["list"],$var);
		}
		

	}

	$str.=$TempArr["m1"];



	//收费服务列表

	$msql->query("select * from {P}_webmall_goods");
	while($msql->next_record()){
		$gid=$msql->f('id');
		$servicename=$msql->f('goods');
		$danwei=$msql->f('danwei');
		$price=$msql->f('price');
		$price1=$msql->f('price1');
		$price2=$msql->f('price2');
		$mtype1=$msql->f('mtype1');
		$mtype2=$msql->f('mtype2');
	

		if($mtype1==$membertypeid){
			$serviceprice=$price1;
		}elseif($mtype2==$membertypeid){
			$serviceprice=$price2;
		}else{
			$serviceprice=$price;
		}

		$serviceprice=number_format($serviceprice,0,'.','');

		$var=array (
		'gid' => $gid,
		'servicename' => $servicename,
		'danwei' => $danwei,
		'serviceprice' => $serviceprice
		);

		$str.=ShowTplTemp($TempArr["text"],$var);

	}


	$var=array (
	'buildsite' => $buildsite,
	'myprice' => $myprice,
	'webname' => $webname,
	'siteid' => $siteid,
	'spool' => $spool,
	'id' => $id
	);

	$str.=ShowTplTemp($TempArr["end"],$var);

	return $str;
	
}

?>