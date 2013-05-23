<?php
define("ROOTPATH", "../");
include(ROOTPATH."includes/common.inc.php");
include("language/".$sLan.".php");

SecureMember();

$act=$_POST["act"];

switch($act){

	
	//代理平台站点试用
	case "webmalladd" :

		set_time_limit(0);
		
		$memberid=$_COOKIE["MEMBERID"];
		$user=$_COOKIE["MUSER"];
		$passwd=$_POST["passwd"];
		$spool=$_POST["spool"];
		$serverdomain=$_POST["serverdomain"];

		if($memberid=="0" || $memberid==""){
			echo "1000";
			exit;
		}

		if($spool=="" || strlen($spool)<2 || strstr($spool,"/") || strstr($spool,".")){
			echo "1002";
			exit;
		}

		if($serverdomain==""){
			echo "3005";
			exit;
		}

		//获取代理连接参数

		$msql->query("select * from {P}_webmall_config");
		while($msql->next_record()){
			$variable=$msql->f('variable');
			$value=$msql->f('value');
			$WEBMALLCONF[$variable]=$value;
		}
		$AgentApi=$WEBMALLCONF["AgentApi"];
		$AgentUser=$WEBMALLCONF["AgentUser"];
		$AgentPasswd=$WEBMALLCONF["AgentPasswd"];

		//连接主站vp接口
		include(ROOTPATH."base/nusoap/nusoap.php");

		$server   = "http://".$AgentApi."/webtry/admin/vp/soapserver.php";
		$customer = new soapclientx ($server);

		$mdpass=md5($AgentPasswd);
		$params  = array (
		'agentuser'  => $AgentUser,
		'agentpasswd'  => $mdpass,
		'clientid'  => $memberid,
		'clientuser'  => $user,
		'clientpasswd'  => $passwd,
		'spool'  => $spool,
		'serverdomain'  => $serverdomain
		);

		$resault=$customer -> call ("VPTRYADD", $params);

		//错误调试和连接失败判断
		if ($err=$customer->getError()) {
			$errinfo=$customer->response;
			echo "主站代理接口连接失败:".$err.$errinfo;
			exit;
		}

		echo $resault;
		exit;
		
	break;




	//网站产品订单提交
	case "sendorder" :

		$memberid=$_COOKIE["MEMBERID"];
		$spoolid=$_POST["spoolid"];
		$binddomain=$_POST["binddomain"];
		$modules=$_POST["modules"];
		$expservice=$_POST["expservice"];

		
		//生成订单

		//获取会员类型
		$msql->query("select * from {P}_member where memberid='$memberid'");
		if($msql->next_record()){
			$company=$msql->f('company');
			$name=$msql->f('name');
			$membertypeid=$msql->f('membertypeid');
			$user=$msql->f('user');
			$email=$msql->f('email');
			$mov=$msql->f('mov');
			$addr=$msql->f('addr');
			$tel=$msql->f('tel');
			$postcode=$msql->f('postcode');
			$membergroupid=$msql->f('membergroupid');
		}

		//个人组会员,订购人用姓名
		if($membergroupid=="1"){
			$company=$name;
		}

		//套餐订单处理
		$msql->query("select * from {P}_webmall_spool where id='$spoolid'");
		if($msql->next_record()){
			$tempname=$msql->f('name');
			$spool=$msql->f('spool');
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

		$now=time();

		$msql->query("insert into {P}_webmall_torder set
		  `memberid`='$memberid',
		  `company`='$company',
		  `name`='$name',
		  `addr`='$addr',
		  `tel`='$tel',
		  `mov`='$mov',
		  `email`='$email',
		  `postcode`='$postcode',
		  `binddomain`='$binddomain',
		  `hostsize`='$hostsize',
		  `siteid`='$siteid',
		  `tempid`='$spoolid',
		  `tempname`='$tempname',
		  `total`='$myprice',
		  `ifpay`='0',
		  `ifok`='0',
		  `dtime`='$now',
		  `paytime`='0',
		  `uptime`='$now'
		");
		
		$tid=$msql->instid();


		//将产品捆绑的模块加入订单已购模块列表
		$msql->query("select * from {P}_webmall_spoolmod where `spool`='$spool'");
		while($msql->next_record()){
			$smodule=$msql->f('module');
			$fsql->query("insert into {P}_webmall_tmod set `tid`='$tid',`module`='$smodule'");
		}
		

		
		//处理自选模块订单
		if($modules!=NULL && sizeof($modules)>=1){
			
			for($i=0;$i<sizeof($modules);$i++){

				$msql->query("select * from {P}_webmall_modules where id='".$modules[$i]."'");
				if($msql->next_record()){
					$goodsid=$msql->f('id');
					$goods=$msql->f('cname');
					$price=$msql->f('price');
					$price1=$msql->f('price1');
					$price2=$msql->f('price2');
					$mtype1=$msql->f('mtype1');
					$mtype2=$msql->f('mtype2');
					$danwei=$msql->f('danwei');

					if($mtype1==$membertypeid){
						$exprice=$price1;
					}elseif($mtype2==$membertypeid){
						$exprice=$price2;
					}else{
						$exprice=$price;
					}


					$fsql->query("insert into {P}_webmall_iorder set
					  `tid`='$tid',
					  `memberid`='$memberid',
					  `goodstype`='module',
					  `goodsid`='$goodsid',
					  `goods`='$goods',
					  `danwei`='$danwei',
					  `price`='$exprice',
					  `nums`='1',
					  `total`='$exprice',
					  `ifxu`='0',
					  `ifpay`='0',
					  `ifok`='0',
					  `dtime`='$now',
					  `paytime`='0'
					");

				}
			}


		}
		


		//处理自选配套产品订单

		if($expservice!=NULL && sizeof($expservice)>=1){

			for($i=0;$i<sizeof($expservice);$i++){

				$msql->query("select * from {P}_webmall_goods where id='".$expservice[$i]."'");
				if($msql->next_record()){
					$goodsid=$msql->f('id');
					$goods=$msql->f('goods');
					$price=$msql->f('price');
					$price1=$msql->f('price1');
					$price2=$msql->f('price2');
					$mtype1=$msql->f('mtype1');
					$mtype2=$msql->f('mtype2');
					$ifxu=$msql->f('ifxu');
					$danwei=$msql->f('danwei');

					if($mtype1==$membertypeid){
						$exprice=$price1;
					}elseif($mtype2==$membertypeid){
						$exprice=$price2;
					}else{
						$exprice=$price;
					}


					$fsql->query("insert into {P}_webmall_iorder set
					  `tid`='$tid',
					  `memberid`='$memberid',
				      `goodstype`='service',
					  `goodsid`='$goodsid',
					  `goods`='$goods',
					  `danwei`='$danwei',
					  `price`='$exprice',
					  `nums`='1',
					  `total`='$exprice',
					  `ifxu`='$ifxu',
					  `ifpay`='0',
					  `ifok`='0',
					  `dtime`='$now',
					  `paytime`='0'
					");

				}
			}
		}

		echo "OK_".$tid;
		exit;
		
	break;




	//套餐订单付款
	case "payorder" :

		$memberid=$_COOKIE["MEMBERID"];
		$user=$_COOKIE["MUSER"];
		$torderid=$_POST["torderid"];

		$msql->query("select * from {P}_webmall_torder where id='$torderid' and memberid='$memberid'");
		if($msql->next_record()){
			$total=$msql->f('total');
			$ifpay=$msql->f('ifpay');
			if($ifpay=="1"){
				echo "1001";
				exit;
			}

			$fsql->query("select account from {P}_member where memberid='$memberid'");
			if($fsql->next_record()){
				$account=$fsql->f('account');
			}

			if($account<$total){
				echo "1002";
				exit;
			}

			$now=time();
			$serviceexp=$now+31536000;
			$ip=$_SERVER["REMOTE_ADDR"];
			$OrderNo=$torderid+10000;;
			$fsql->query("update {P}_member set account=account-$total,buytotal=buytotal+$total where memberid='$memberid'");
			$fsql->query("update {P}_webmall_torder set ifpay='1',paytime='$now',uptime='$now',serviceexp='$serviceexp' where id='$torderid'");

			$fsql->query("insert into {P}_member_buylist set 
				`buyfrom`='$strOrderBuyFrom',
				`memberid`='$memberid',
				`orderid`='$torderid',
				`payid`='0',
				`paytype`='$strOrderPayType',
				`paytotal`='$total',
				`daytime`='$now',
				`ip`='$ip',
				`OrderNo`='$OrderNo',
				`logname`='$user'
			");

			echo "OK";
			exit;
		}else{
			echo "1000";
			exit;
		}

	break;


	//附属项目订购付款
	case "payiorder" :

		$memberid=$_COOKIE["MEMBERID"];
		$user=$_COOKIE["MUSER"];
		$iorderid=$_POST["iorderid"];
		

		$msql->query("select * from {P}_webmall_iorder where id='$iorderid' and memberid='$memberid'");
		if($msql->next_record()){
			$total=$msql->f('total');
			$tid=$msql->f('tid');
			$goodstype=$msql->f('goodstype');
			$goodsid=$msql->f('goodsid');
			$ifpay=$msql->f('ifpay');
			if($ifpay=="1"){
				echo "1001";
				exit;
			}

			//检查主订单是否付款
			$fsql->query("select ifpay from {P}_webmall_torder where id='$tid'");
			if($fsql->next_record()){
				$t_ifpay=$fsql->f('ifpay');
			}
			if($t_ifpay!="1"){
				echo "1005";
				exit;
			}

			$fsql->query("select account from {P}_member where memberid='$memberid'");
			if($fsql->next_record()){
				$account=$fsql->f('account');
			}

			if($account<$total){
				echo "1002";
				exit;
			}

			$now=time();
			$ip=$_SERVER["REMOTE_ADDR"];
			$OrderNo=$tid+10000;
			$fsql->query("update {P}_member set account=account-$total,buytotal=buytotal+$total where memberid='$memberid'");
			$fsql->query("update {P}_webmall_iorder set ifpay='1',paytime='$now' where id='$iorderid'");

			//刷新主订单更新时间
			$fsql->query("update {P}_webmall_torder set uptime='$now' where id='$tid'");

			$fsql->query("insert into {P}_member_buylist set 
				`buyfrom`='$strOrderBuyFrom',
				`memberid`='$memberid',
				`orderid`='$tid',
				`payid`='0',
				`paytype`='$strOrderPayType',
				`paytotal`='$total',
				`daytime`='$now',
				`ip`='$ip',
				`OrderNo`='$OrderNo',
				`logname`='$user'
			");

			//增购模块付款时，加入已购模块清单
			if($goodstype=="module"){
				$msql->query("select * from {P}_webmall_modules where id='$goodsid' limit 0,1");
				if($msql->next_record()){
					$smodule=$msql->f('module');
				}
				$msql->query("insert into {P}_webmall_tmod set `tid`='$tid',`module`='$smodule'");
			}

			echo "OK";
			exit;




		}else{
			echo "1000";
			exit;
		}

	break;


	//获取帐户余额
	case "getaccount" :

		$memberid=$_COOKIE["MEMBERID"];
		$fsql->query("select account from {P}_member where memberid='$memberid'");
		if($fsql->next_record()){
			$account=$fsql->f('account');
		}
		echo $account;
		exit;


	break;



	//续订服务
	case "xufei" :

		$memberid=$_COOKIE["MEMBERID"];
		$membertypeid=$_COOKIE["MEMBERTYPEID"];
		$xuyears=$_POST["xuyears"];
		$torderid=$_POST["torderid"];

		$msql->query("select tempid,serviceexp from {P}_webmall_torder where id='$torderid' and memberid='$memberid'");
		if($msql->next_record()){
			$tempid=$msql->f('tempid');
			$exptime=$msql->f('serviceexp');
		}else{
			echo "1000";
			exit;
		}

		if($xuyears<1){
			echo "1003";
			exit;
		}

		$msql->query("select * from {P}_webmall_spool where id='$tempid' limit 0,1");
		if($msql->next_record()){
			$spool=$msql->f('spool');
			$xufei=$msql->f('xufei');
			$xufei1=$msql->f('xufei1');
			$xufei2=$msql->f('xufei2');
			$xtype1=$msql->f('xtype1');
			$xtype2=$msql->f('xtype2');
		}else{
			echo "1001";
			exit;
		}



		//续费价格计算
		if($xtype1==$membertypeid){
			$myxufei=$xufei1;
		}elseif($xtype2==$membertypeid){
			$myxufei=$xufei2;
		}else{
			$myxufei=$xufei;
		}
		
		//续费总金额
		$total=$xuyears*$myxufei;

		//帐户余额校验
		$fsql->query("select account,user from {P}_member where memberid='$memberid'");
		if($fsql->next_record()){
			$account=$fsql->f('account');
			$user=$fsql->f('user');
		}

		if($account<$total){
			echo "1005";
			exit;
		}


		$now=time();
		$ip=$_SERVER["REMOTE_ADDR"];
		$OrderNo=$torderid+10000;
		$fsql->query("update {P}_member set account=account-$total,buytotal=buytotal+$total where memberid='$memberid'");
		$fsql->query("insert into {P}_member_buylist set 
			`buyfrom`='$strOrderBuyFrom',
			`memberid`='$memberid',
			`orderid`='$torderid',
			`payid`='0',
			`paytype`='$strOrderPayType',
			`paytotal`='$total',
			`daytime`='$now',
			`ip`='$ip',
			`OrderNo`='$OrderNo',
			`logname`='$user'
		");

		//订单记录
		$goods="续订支持服务";

		$fsql->query("insert into {P}_webmall_iorder set
		  `tid`='$torderid',
		  `memberid`='$memberid',
		  `goodsid`='$tempid',
		  `goods`='$goods',
		  `danwei`='年',
		  `price`='$myxufei',
		  `nums`='$xuyears',
		  `total`='$total',
		  `ifxu`='1',
		  `ifpay`='1',
		  `ifok`='0',
		  `dtime`='$now',
		  `paytime`='$now'
		");

		$iorderid=$fsql->instid();


		//修改服务到期时间
		$serviceexp=$exptime+(31536000*$xuyears);
		$fsql->query("update {P}_webmall_torder set uptime='$now',serviceexp='$serviceexp' where id='$torderid'");

		echo "OK";
		exit;

	break;

	
	//增购模块
	case "addbuymod" :

		$memberid=$_COOKIE["MEMBERID"];
		$membertypeid=$_COOKIE["MEMBERTYPEID"];
		$addmodid=$_POST["addmodid"];
		$torderid=$_POST["torderid"];

		$msql->query("select * from {P}_webmall_torder where memberid='$memberid' and id='$torderid'");
		if($msql->next_record()){
			
		}else{
			echo "1000";
			exit;
		}

		$msql->query("select * from {P}_webmall_modules  where `id`='$addmodid' ");
		if($msql->next_record()){
			$module=$msql->f('module');
			$goodsid=$msql->f('id');
			$goods=$msql->f('cname');
			$price=$msql->f('price');
			$price1=$msql->f('price1');
			$price2=$msql->f('price2');
			$mtype1=$msql->f('mtype1');
			$mtype2=$msql->f('mtype2');
			$danwei=$msql->f('danwei');

			if($mtype1==$membertypeid){
				$exprice=$price1;
			}elseif($mtype2==$membertypeid){
				$exprice=$price2;
			}else{
				$exprice=$price;
			}

		}else{
			echo "1001";
			exit;
		}

		$msql->query("select * from {P}_webmall_tmod where `tid`='$torderid' and `module`='$module' ");
		if($msql->next_record()){
			echo "1002";
			exit;
		}

		$msql->query("select * from {P}_webmall_iorder where `tid`='$torderid' and `goodstype`='module' and `goodsid`='$goodsid'");
		if($msql->next_record()){
			echo "1003";
			exit;
		}
		
		$now=time();
		$fsql->query("insert into {P}_webmall_iorder set
		  `tid`='$torderid',
		  `memberid`='$memberid',
		  `goodstype`='module',
		  `goodsid`='$goodsid',
		  `goods`='$goods',
		  `danwei`='$danwei',
		  `price`='$exprice',
		  `nums`='1',
		  `total`='$exprice',
		  `ifxu`='0',
		  `ifpay`='0',
		  `ifok`='0',
		  `dtime`='$now',
		  `paytime`='0'
		");

		echo "OK";
		exit;

	break;

	

}
?>