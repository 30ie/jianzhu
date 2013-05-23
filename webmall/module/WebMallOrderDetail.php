<?php

/*
	[插件名称] 软件套餐订单详情
*/

function WebMallOrderDetail(){

	global $msql,$fsql;

	$memberid=$_COOKIE["MEMBERID"];
	$membertypeid=$_COOKIE["MEMBERTYPEID"];


	$tempname=$GLOBALS["PLUSVARS"]["tempname"];

	$id=$_GET["id"];

	if($id==""){
		return "NO ID";
	}

	$msql->query("select * from {P}_webmall_torder where id='$id' and memberid='$memberid' limit 0,1");
	if($msql->next_record()){
		$orderid=$msql->f('id');
		$memberid=$msql->f('memberid');
		$company=$msql->f('company');
		$name=$msql->f('name');
		$email=$msql->f('email');
		$mov=$msql->f('mov');
		$addr=$msql->f('addr');
		$tel=$msql->f('tel');
		$postcode=$msql->f('postcode');
		$binddomain=$msql->f('binddomain');
		$hostsize=$msql->f('hostsize');
		$siteid=$msql->f('siteid');
		$tempid=$msql->f('tempid');
		$webname=$msql->f('tempname');
		$total=$msql->f('total');
		$ifpay=$msql->f('ifpay');
		$ifok=$msql->f('ifok');
		$dtime=$msql->f('dtime');
		$paytime=$msql->f('paytime');
		$serviceexp=$msql->f('serviceexp');
	}

	$orderno=$orderid+10000;

	$dtime=date("y-n-j",$dtime);
	$paytime=date("Y-n-j",$paytime);
	if($serviceexp!=0){
		$serviceexp=date("Y-n-j",$serviceexp);
	}else{
		$serviceexp="尚未开始";
	}

	$msql->query("select * from {P}_webmall_spool where id='$tempid' limit 0,1");
	if($msql->next_record()){
		$spool=$msql->f('spool');
		$xufei=$msql->f('xufei');
		$xufei1=$msql->f('xufei1');
		$xufei2=$msql->f('xufei2');
		$xtype1=$msql->f('xtype1');
		$xtype2=$msql->f('xtype2');
	}

	//服务续费下拉菜单
	if($xtype1==$membertypeid){
		$myxufei=$xufei1;
	}elseif($xtype2==$membertypeid){
		$myxufei=$xufei2;
	}else{
		$myxufei=$xufei;
	}

	$modules="";
	$fsql->query("select `module` from {P}_webmall_tmod where tid='$orderid'");
	while($fsql->next_record()){
		$module=$fsql->f('module');
		$msql->query("select cname from {P}_webmall_modules where `module`='$module' limit 0,1");
		if($msql->next_record()){
			$modules.=$msql->f('cname').",";
		}
	}
	$modules=substr($modules,0,-1);


	$xuoptions="";
	for($k=1;$k<=10;$k++){
		$xuoptions.="<option value='".$k."'>".$k*$myxufei."元/".$k."年</option>";
	}


	$msql->query("select user,account from {P}_member where memberid='$memberid' limit 0,1");
	if($msql->next_record()){
		$user=$msql->f('user');
		$account=$msql->f('account');
	}

	$t_ifpay=$ifpay;

	if($ifpay=="1"){
		$paystat="已付款";
		$paydis="disabled";
		$xudis="";
	}else{
		$paystat="未付款";
		$paydis="";
		$xudis="disabled";
	}

	if($ifok=="1"){
		$okstat="已处理";
	}else{
		$okstat="未处理";
	}

	$Temp=LoadTemp($tempname);
	$TempArr=SplitTblTemp($Temp);

	$var=array (
	'orderid' => $orderid,
	'orderno' => $orderno,
	'user' => $user,
	'name' => $name,
	'company' => $company,
	'addr' => $addr,
	'tel' => $tel,
	'mov' => $mov,
	'email' => $email,
	'postcode' => $postcode,
	'pname' => $pname,
	'account' => $account,
	'webname' => $webname,
	'spool' => $spool,
	'designer' => $designer,
	'modules' => $modules, 
	'total' => $total,
	'dtime' => $dtime,
	'serviceexp' => $serviceexp,
	'paystat' => $paystat,
	'paydis' => $paydis,
	'okstat' => $okstat,
	'xudis' => $xudis, 
	'xuoptions' => $xuoptions, 
	'binddomain' => $binddomain
	);

	$str=ShowTplTemp($TempArr["start"],$var);



	//附属订单列表

	$msql->query("select * from {P}_webmall_iorder where tid='$orderid' and memberid='$memberid'");
	while($msql->next_record()){

		$iorderid=$msql->f('id');
		$goods=$msql->f('goods');
		$goodsid=$msql->f('goodsid');
		$price=$msql->f('price');
		$nums=$msql->f('nums');
		$danwei=$msql->f('danwei');
		$total=$msql->f('total');
		$ifpay=$msql->f('ifpay');
		$ifok=$msql->f('ifok');
		$ifxu=$msql->f('ifxu');
		$dtime=$msql->f('dtime');
		$paytime=$msql->f('paytime');

		$dtime=date("y-n-j",$dtime);
		$paytime=date("y-n-j",$paytime);
		
		if($ifpay=="1"){
			$paystat="已付款";
			$paydis="disabled";
		}else{
			$paystat="未付款";
			if($t_ifpay=="1"){
				$paydis="";
			}else{
				$paydis="disabled";
			}
		}

		if($ifok=="1"){
			$okstat="已处理";
		}else{
			$okstat="未处理";
		}

		
		$var=array (
		'iorderid' => $iorderid,
		'goods' => $goods,
		'goodsid' => $goodsid,
		'price' => $price,
		'nums' => $nums,
		'danwei' => $danwei,
		'total' => $total,
		'dtime' => $dtime,
		'paystat' => $paystat,
		'paydis' => $paydis,
		'okstat' => $okstat
		);

		$str.=ShowTplTemp($TempArr["list"],$var);

	}

	$str.=$TempArr["m1"];


	//扩展模块增订列表

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

		//检查模块是否已经订购
		$fsql->query("select id from {P}_webmall_tmod where `tid`='$orderid' and `module`='$module' ");
		if($fsql->next_record()){
		
		}else{
			$str.=ShowTplTemp($TempArr["con"],$var);
		}

	}

	$str.=$TempArr["end"];

	return $str;
	
}

?>