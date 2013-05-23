<?php
define("ROOTPATH", "../../");
include(ROOTPATH."includes/admin.inc.php");
include("language/".$sLan.".php");
NeedAuth(40);


$act=$_POST["act"];

switch($act){


	//网站产品同步
	case "getspool" :

		set_time_limit(0);

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
		'agentpasswd'  => $mdpass
		);

		$resault=$customer -> call ("GETSPOOL", $params);

		//错误调试和连接失败判断
		if ($err=$customer->getError()) {
			$errinfo=$customer->response;
			echo "主站代理接口连接失败:".$err.$errinfo;
			exit;
		}

		if($resault=="5000"){
			echo "主站代理帐号校验未通过，代理帐号或密码错误";
			exit;
		}
		
		if(is_array($resault)){
			$nums=sizeof($resault);
			for($i=0;$i<$nums;$i++){
				$spool=$resault[$i]["spool"];
				$spoolname=$resault[$i]["spoolname"];
				$xuhao=$resault[$i]["xuhao"];
				$typeid=$resault[$i]["typeid"];
				$catid=$resault[$i]["catid"];
				$dtime=$resault[$i]["dtime"];
				$demourl=$resault[$i]["demourl"];
				$price=$resault[$i]["price"];
				$xufei=$resault[$i]["xufei"];

				$msql->query("select id from {P}_webmall_spool where spool='$spool' limit 0,1");
				if($msql->next_record()){
					$fsql->query("update {P}_webmall_spool set 
						`name`='$spoolname',
						`xuhao`='$xuhao',
						`typeid`='$typeid',
						`catid`='$catid',
						`dtime`='$dtime',
						`demourl`='$demourl' where spool='$spool'
					");
				}else{
					$fsql->query("insert into {P}_webmall_spool set 
						`spool`='$spool',
						`name`='$spoolname',
						`xuhao`='$xuhao',
						`typeid`='$typeid',
						`catid`='$catid',
						`price`='$price',
						`xufei`='$xufei',
						`dtime`='$dtime',
						`demourl`='$demourl'
					");
				}
			}
		}
			


		echo "OK";
		exit;

	break;


	//网站产品模块清单同步
	case "getspoolmodules" :

		set_time_limit(0);

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
		'agentpasswd'  => $mdpass
		);

		$resault=$customer -> call ("GETSPOOLMODULES", $params);

		//错误调试和连接失败判断
		if ($err=$customer->getError()) {
			$errinfo=$customer->response;
			echo "主站代理接口连接失败:".$err.$errinfo;
			exit;
		}

		if($resault=="5000"){
			echo "主站代理帐号校验未通过，代理帐号或密码错误";
			exit;
		}
		
		if(is_array($resault)){
			$nums=sizeof($resault);
			for($i=0;$i<$nums;$i++){
				$spool=$resault[$i]["spool"];
				$module=$resault[$i]["module"];

				$msql->query("select id from {P}_webmall_spoolmod where spool='$spool' and module='$module' limit 0,1");
				if($msql->next_record()){
					
				}else{
					$fsql->query("insert into {P}_webmall_spoolmod set 
						`spool`='$spool',
						`module`='$module'
					");
				}
			}
		}
			


		echo "OK";
		exit;

	break;



	//网站产品行业分类同步
	case "getspoolcat" :

		set_time_limit(0);

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
		'agentpasswd'  => $mdpass
		);

		$resault=$customer -> call ("GETSPOOLCAT", $params);

		//错误调试和连接失败判断
		if ($err=$customer->getError()) {
			$errinfo=$customer->response;
			echo "主站代理接口连接失败:".$err.$errinfo;
			exit;
		}

		if($resault=="5000"){
			echo "主站代理帐号校验未通过，代理帐号或密码错误";
			exit;
		}
		
		if(is_array($resault)){
			$msql->query("delete from {P}_webmall_tempcat");
			$nums=sizeof($resault);
			for($i=0;$i<$nums;$i++){
				$catid=$resault[$i]["catid"];
				$cat=$resault[$i]["cat"];
				$pid=$resault[$i]["pid"];
				$catpath=$resault[$i]["catpath"];
				$xuhao=$resault[$i]["xuhao"];
				$msql->query("insert into {P}_webmall_tempcat set 
					`catid`='$catid',
					`cat`='$cat',
					`pid`='$pid',
					`catpath`='$catpath',
					`xuhao`='$xuhao'
				");
			}
		}
			


		echo "OK";
		exit;

	break;



	//网站产品应用分类同步
	case "getspooltype" :

		set_time_limit(0);

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
		'agentpasswd'  => $mdpass
		);

		$resault=$customer -> call ("GETSPOOLTYPE", $params);

		//错误调试和连接失败判断
		if ($err=$customer->getError()) {
			$errinfo=$customer->response;
			echo "主站代理接口连接失败:".$err.$errinfo;
			exit;
		}

		if($resault=="5000"){
			echo "主站代理帐号校验未通过，代理帐号或密码错误";
			exit;
		}
		
		if(is_array($resault)){
			$msql->query("delete from {P}_webmall_temptype");
			$nums=sizeof($resault);
			for($i=0;$i<$nums;$i++){
				$catid=$resault[$i]["catid"];
				$cat=$resault[$i]["cat"];
				$pid=$resault[$i]["pid"];
				$catpath=$resault[$i]["catpath"];
				$xuhao=$resault[$i]["xuhao"];
				$msql->query("insert into {P}_webmall_temptype set 
					`catid`='$catid',
					`cat`='$cat',
					`pid`='$pid',
					`catpath`='$catpath',
					`xuhao`='$xuhao'
				");
			}
		}
			


		echo "OK";
		exit;

	break;



	//代理商网站产品价格修改
	case "webtempmodi" :
	
		$id=$_POST["id"];
		$price=$_POST["price"];
		$price1=$_POST["price1"];
		$price2=$_POST["price2"];
		$mtype1=$_POST["mtype1"];
		$mtype2=$_POST["mtype2"];
		$xufei=$_POST["xufei"];
		$xufei1=$_POST["xufei1"];
		$xufei2=$_POST["xufei2"];
		$xtype1=$_POST["xtype1"];
		$xtype2=$_POST["xtype2"];
	

		$msql->query("update {P}_webmall_spool set
		`price`='$price',
		`price1`='$price1',
		`price2`='$price2',
		`mtype1`='$mtype1',
		`mtype2`='$mtype2',
		`xufei`='$xufei',
		`xufei1`='$xufei1',
		`xufei2`='$xufei2',
		`xtype1`='$xtype1',
		`xtype2`='$xtype2'  where id='$id'");


		echo "OK";
		exit;

	break;


	//代理商网站产品删除
	case "webmalltempdel" :

		$tempid=$_POST["tempid"];

		$msql->query("delete from {P}_webmall_spool where id='$tempid'");

		echo "OK";
		exit;

	break;

	
	//模块产品同步
	case "getmodules" :

		set_time_limit(0);

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
		'agentpasswd'  => $mdpass
		);

		$resault=$customer -> call ("GETMODULES", $params);

		//错误调试和连接失败判断
		if ($err=$customer->getError()) {
			$errinfo=$customer->response;
			echo "主站代理接口连接失败:".$err.$errinfo;
			exit;
		}

		if($resault=="5000"){
			echo "主站代理帐号校验未通过，代理帐号或密码错误";
			exit;
		}
		
		if(is_array($resault)){
			$nums=sizeof($resault);
			for($i=0;$i<$nums;$i++){
				$module=$resault[$i]["module"];
				$cname=$resault[$i]["cname"];
				$price=$resault[$i]["price"];
				$xuhao=$resault[$i]["xuhao"];
				$danwei=$resault[$i]["danwei"];

				$msql->query("select id from {P}_webmall_modules where `module`='$module' limit 0,1");
				if($msql->next_record()){
					$fsql->query("update {P}_webmall_modules set 
						`cname`='$cname',
						`xuhao`='$xuhao',
						`danwei`='$danwei' where `module`='$module'
					");
				}else{
					$fsql->query("insert into {P}_webmall_modules set 
						`module`='$module',
						`cname`='$cname',
						`xuhao`='$xuhao',
						`danwei`='$danwei',
						`price`='$price'
					");
				}
			}
		}
			


		echo "OK";
		exit;

	break;



	//服务商品发布
	case "goodsadd" :
	
		$goods=$_POST["goods"];
		$intro=htmlspecialchars($_POST["intro"]);
		$price=$_POST["price"];
		$price1=$_POST["price1"];
		$price2=$_POST["price2"];
		$mtype1=$_POST["mtype1"];
		$mtype2=$_POST["mtype2"];
		$danwei=$_POST["danwei"];
		$ifxu=$_POST["ifxu"];

		$msql->query("insert into {P}_webmall_goods set
			`goods`='$goods',
			`intro`='$intro',
			`danwei`='$danwei',
			`ifxu`='$ifxu',
			`price`='$price',
			`price1`='$price1',
			`price2`='$price2',
			`mtype1`='$mtype1',
			`mtype2`='$mtype2'
		");

		echo "OK";
		exit;

	break;

	//商品修改
	case "goodsmodi" :
	
		$id=$_POST["id"];
		$goods=$_POST["goods"];
		$intro=htmlspecialchars($_POST["intro"]);
		$price=$_POST["price"];
		$price1=$_POST["price1"];
		$price2=$_POST["price2"];
		$mtype1=$_POST["mtype1"];
		$mtype2=$_POST["mtype2"];
		$danwei=$_POST["danwei"];
		$ifxu=$_POST["ifxu"];

		$msql->query("update {P}_webmall_goods set
			`goods`='$goods',
			`intro`='$intro',
			`danwei`='$danwei',
			`ifxu`='$ifxu',
			`price`='$price',
			`price1`='$price1',
			`price2`='$price2',
			`mtype1`='$mtype1',
			`mtype2`='$mtype2' where id='$id'");

		echo "OK";
		exit;

	break;


	//商品删除
	case "goodsdel" :

		$goodsid=$_POST["goodsid"];

		$msql->query("delete from {P}_webmall_goods where id='$goodsid'");

		echo "OK";
		exit;

	break;


	//套餐订单删除
	case "torderdel" :

		$orderid=$_POST["orderid"];

		$msql->query("select * from {P}_webmall_torder where id='$orderid' limit 0,1");
		if($msql->next_record()){
			$ifpay=$msql->f('ifpay');
			$ifok=$msql->f('ifok');
			if($ifpay=="1" || $ifok=="1"){
				echo "1001";
				exit;
			}
		}

		$msql->query("select id from {P}_webmall_iorder where tid='$orderid'");
		if($msql->next_record()){
				echo "1002";
				exit;
		}

		$msql->query("delete from {P}_webmall_torder where id='$orderid'");
		$msql->query("delete from {P}_webmall_tmod where tid='$orderid'");

		echo "OK";
		exit;

	break;

	//单项订单删除
	case "iorderdel" :

		$orderid=$_POST["orderid"];

		$msql->query("select ifok,ifpay from {P}_webmall_iorder where id='$orderid'");
		if($msql->next_record()){
			$ifpay=$msql->f('ifpay');
			$ifok=$msql->f('ifok');
			if($ifpay=="1" || $ifok=="1"){
				echo "1001";
				exit;
			}
		}

		
		$msql->query("delete from {P}_webmall_iorder where id='$orderid'");

		echo "OK";
		exit;

	break;


	//模块产品修改
	case "modulesmodi" :
	
		$id=$_POST["id"];
		$cname=$_POST["cname"];
		$price=$_POST["price"];
		$price1=$_POST["price1"];
		$price2=$_POST["price2"];
		$mtype1=$_POST["mtype1"];
		$mtype2=$_POST["mtype2"];
		$danwei=$_POST["danwei"];

		$msql->query("update {P}_webmall_modules set
			`cname`='$cname',
			`danwei`='$danwei',
			`price`='$price',
			`price1`='$price1',
			`price2`='$price2',
			`mtype1`='$mtype1',
			`mtype2`='$mtype2' where id='$id'");

		echo "OK";
		exit;

	break;


}
?>