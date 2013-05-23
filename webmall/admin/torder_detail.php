<?php
define("ROOTPATH", "../../");
include(ROOTPATH."includes/admin.inc.php");
include("language/".$sLan.".php");
NeedAuth(44);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link  href="css/style.css" type="text/css" rel="stylesheet">
<title><?php echo $strAdminTitle; ?></title>
<script type="text/javascript" src="../../base/js/base.js"></script>
<script type="text/javascript" src="../../base/js/blockui.js"></script>
<script type="text/javascript" src="js/order.js"></script>

</head>

<body >

<?php
$id=$_REQUEST["id"];
$msql->query("select * from {P}_webmall_torder where id='$id' limit 0,1");
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
$hostsize=$msql->f('hostsize');
$siteid=$msql->f('siteid');
$binddomain=$msql->f('binddomain');
$tempid=$msql->f('tempid');
$tempname=$msql->f('tempname');
$total=$msql->f('total');
$ifpay=$msql->f('ifpay');
$ifok=$msql->f('ifok');
$dtime=$msql->f('dtime');
$paytime=$msql->f('paytime');
$serviceexp=$msql->f('serviceexp');
}

$OrderNo=$orderid+10000;

$dtime=date("y-n-j",$dtime);
$paytime=date("y-n-j",$paytime);

if($serviceexp!=0){
$serviceexp=date("Y-n-j",$serviceexp);
}else{
$serviceexp="尚未开始";
}
	
$msql->query("select spool from {P}_webmall_spool where id='$tempid' limit 0,1");
if($msql->next_record()){
$spool=$msql->f('spool');
}

$msql->query("select user from {P}_member where memberid='$memberid' limit 0,1");
if($msql->next_record()){
	$user=$msql->f('user');
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


if($ifpay=="1"){
$paystat=$paytime;
}else{
$paystat="未付款";
}

if($ifok=="1"){
$okstat="已处理";
}else{
$okstat="未处理";
}

?>



<div id="orderdetail">
<div class="ordertitle">
<a href="torder_detail.php?id=<?php echo $id; ?>" target="_blank" class="tprint">[订单打印]</a>
软件产品订单 - No.<?php echo $OrderNo; ?> </div>
<div class="tit">订购人信息</div>
<table width="100%" border="0" cellspacing="1" cellpadding="3" >
 
  <tr valign="top">
    <td width="70" height="25" align="center" class="itemname">订 购 人：</td>
    <td width="220" ><?php echo $company; ?></td>
    <td width="70"  class="itemname">联 系 人：</td>
    <td height="25" ><?php echo $name; ?></td>
  </tr>
  <tr valign="top">
    <td width="70" height="25" align="center" class="itemname">联系电话：</td>
    <td width="220" ><?php echo $tel; ?></td>
    <td width="70"  class="itemname">手机号码：</td>
    <td height="25" ><?php echo $mov; ?></td>
  </tr>
  <tr valign="top">
    <td width="70" height="25" align="center" class="itemname">电子邮箱：</td>
    <td width="220" ><?php echo $email; ?></td>
    <td width="70"  class="itemname">通信地址：</td>
    <td height="25" ><span class="itemname"><?php echo $addr; ?></span></td>
  </tr>
  <tr valign="top">
    <td width="70" height="25" align="center" class="itemname">邮政编码：</td>
    <td width="220" ><span class="itemname"><?php echo $postcode; ?></span></td>
    <td width="70"  class="itemname">会 员 名：</td>
    <td height="25" ><?php echo $user; ?> </td>
  </tr>
  
</table>

<div class="tit1">产品订购详情</div>
<table width="100%" border="0" cellspacing="0" cellpadding="3" style="margin-bottom:10px">
  <tr>
    <td width="12" height="23" align="left" ><img src="images/menulist.gif" width="7" height="7"></td>
    <td height="23" ><?php echo $tempname; ?> (产品编号：<?php echo $spool; ?>) </td>
    </tr>
  <tr>
    <td height="20" align="left" ><img src="images/menulist.gif" width="7" height="7" /></td>
    <td height="20" >管理系统：智能网站管理系统</td>
  </tr>
  <tr>
    <td width="12" height="20" align="left" ><img src="images/menulist.gif" width="7" height="7"></td>
    <td height="20" >已购模块：<?php echo $modules; ?></td>
    </tr>
  <tr>
    <td height="23" align="left" ><img src="images/menulist.gif" width="7" height="7" /></td>
    <td height="23" >软件永久商业授权一个  </td>
  </tr>
  <tr>
    <td width="12" height="23" align="left" ><img src="images/menulist.gif" width="7" height="7"></td>
    <td height="23" >软件支持服务一年 （服务到期时间：<?php echo $serviceexp; ?>）</td>
    </tr>
  <tr>
    <td height="23" align="left" ><img src="images/menulist.gif" width="7" height="7" /></td>
    <td height="23" >商业授权和支持服务所捆绑的网站域名：<?php echo $binddomain; ?></td>
  </tr>
  
</table>

<div class="tit1">订购记录</div>
<table width="100%" border="0" cellspacing="0" cellpadding="3" style="margin-bottom:10px">
  
  <tr valign="top" bgcolor="#EEEEEE">
    <td width="70" >下单时间</td>
    <td height="25" bgcolor="#EEEEEE" >订购项目</td>
    <td width="55" bgcolor="#EEEEEE" >数量</td>
    <td width="70" bgcolor="#EEEEEE" >金额</td>
    <td width="70" >付款时间</td>
    <td width="70" >处理状态</td>
    </tr>
  <tr>
    <td width="70" id="baseprice"><?php echo $dtime; ?></td>
    <td height="23" >产品套餐</td>
    <td width="55" id="baseprice">1套</td>
    <td width="70" id="baseprice"><?php echo $total; ?> </td>
    <td width="70" id="baseprice"><?php echo $paystat; ?></td>
    <td width="70" id="baseprice"><?php echo $okstat; ?></td>
    </tr>
  <?php
  $msql->query("select * from {P}_webmall_iorder where tid='$id' order by id");

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
		$paystat=$paytime;
	}else{
		$paystat="未付款";
	}

	if($ifok=="1"){
		$okstat="已处理";
	}else{
		$okstat="未处理";
	}
  ?>
  <tr>
    <td width="70" id="baseprice"><?php echo $dtime; ?></td>
    <td height="20" ><?php echo $goods; ?></td>
    <td width="55" id="baseprice"><?php echo $nums; ?><?php echo $danwei; ?></td>
    <td width="70" id="baseprice"><?php echo $total; ?></td>
    <td width="70" id="baseprice"><?php echo $paystat; ?></td>
    <td width="70" id="baseprice"><?php echo $okstat; ?></td>
    </tr>
  
  <?php
  }
  ?>
  
</table>
<div style="margin-top:15px;border-top:3px #cccccc solid;height:50px;font:12px/50px Verdana, Arial, Helvetica, sans-serif;">
</div>
</div>

</body>
</html>
