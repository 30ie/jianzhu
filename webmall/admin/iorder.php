<?php
define("ROOTPATH", "../../");
include(ROOTPATH."includes/admin.inc.php");
include("language/".$sLan.".php");
include("func/webmall.inc.php");
NeedAuth(44);

$step=$_REQUEST["step"];
$showtid=$_REQUEST["showtid"];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link  href="css/style.css" type="text/css" rel="stylesheet">
<title><?php echo $strAdminTitle; ?></title>
<script type="text/javascript" src="../../base/js/base.js"></script>
<script type="text/javascript" src="../../base/js/blockui.js"></script>
<script type="text/javascript" src="js/order.js"></script>
<SCRIPT>
function SelAll(theForm){
		for ( i = 0 ; i < theForm.elements.length ; i ++ )
		{
			if ( theForm.elements[i].type == "checkbox" && theForm.elements[i].name != "SELALL" )
			{
				theForm.elements[i].checked = ! theForm.elements[i].checked ;
			}
		}
}

</script>
</head>

<body>


<?php

//订单处理
if($step=="okall"){
	NeedAuth(36);
	$dall=$_POST["dall"];
	$nums=sizeof($dall);
	for($i=0;$i<$nums;$i++){
		$ids=$dall[$i];
		$msql->query("update {P}_webmall_iorder set ifok='1' where id='$ids'");
	}
}

if($step=="notokall"){
	NeedAuth(36);
	$dall=$_POST["dall"];
	$nums=sizeof($dall);
	for($i=0;$i<$nums;$i++){
		$ids=$dall[$i];
		$msql->query("update {P}_webmall_iorder set ifok='0' where id='$ids'");
	}
}


//附属订单项目查询
if($showtid!="" && $showtid!="0"){
	$scl=" tid='$showtid' ";
}else{
	err("错误:缺少主订单ID");
	exit;
}

?>
<form name="delfm" method="post" action="iorder.php">
<div class="listzone">
<table width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
          <tr>
            <td width="30"  class="biaoti" align="center"><?php echo $strSel; ?></td> 
    <td width="39"  class="biaoti">ID</td>
            <td  class="biaoti" >订购人</td>
            <td  class="biaoti" >商品</td>
            <td width="50"  class="biaoti" >单价</td>
            <td width="50" align="center"  class="biaoti" >数量</td>
            <td width="60"  class="biaoti" >金额</td>
            <td width="65"  class="biaoti" >订购时间</td>
		    <td width="39" align="center"  class="biaoti" >付款</td>
            <td width="39" align="center"  class="biaoti" >处理</td>
            <td width="55"  class="biaoti" >主订单</td>
            <td width="35"  class="biaoti" align="center" >详情</td>
		    <td height="28" width="35"  class="biaoti" align="center" >删除</td>
          </tr>
          <?php 


$msql->query("select * from {P}_webmall_iorder where $scl order by id desc");

while($msql->next_record()){
$orderid=$msql->f('id');
$memberid=$msql->f('memberid');
$tid=$msql->f('tid');
$goods=$msql->f('goods');
$goodsid=$msql->f('goodsid');
$price=$msql->f('price');
$nums=$msql->f('nums');
$total=$msql->f('total');
$ifpay=$msql->f('ifpay');
$ifok=$msql->f('ifok');
$dtime=$msql->f('dtime');
$paytime=$msql->f('paytime');


	$fsql->query("select * from {P}_webmall_torder where id='$tid' limit 0,1");
	if($fsql->next_record()){
		$binddomain=$fsql->f('binddomain');
		$company=$fsql->f('company');
	}
	
	$fsql->query("select user from {P}_member where memberid='$memberid' limit 0,1");
	if($fsql->next_record()){
		$user=$fsql->f('user');
	}
	
$dtime=date("y/m/d",$dtime);

	

?> 
          <tr class="list" id="tr_<?php echo $orderid; ?>">
            <td width="30" align="center" ><input type="checkbox" name="dall[]" value="<?php echo $orderid; ?>" />
            </td> 
            <td   width="39" valign="top"><?php echo $orderid; ?> </td>
			 <td valign="top"><?php echo $company; ?> </td>
			 <td valign="top" ><?php echo $goods; ?></td>
			 <td width="50" valign="top"><?php echo $price; ?></td>
			 <td width="50" align="center" valign="top"><?php echo $nums; ?></td>
			 <td width="60" valign="top"><?php echo $total; ?></td>
            <td   width="65" valign="top"><?php echo $dtime; ?> </td>
           
            <td   width="39" align="center" valign="top"><?php echo showYN($ifpay); ?> </td>
            <td   width="39" align="center" valign="top"><?php echo showYN($ifok); ?></td>
            <td width="55" valign="top" style="cursor:pointer" onclick="self.location='torder_detail.php?id=<?php echo $tid; ?>'"><?php echo $tid+10000; ?></td>
            <td  width="35" align="center" valign="top" ><a href="torder_detail.php?id=<?php echo $tid; ?>"><img src="images/look.png" width="24" height="24"  border="0" /></a> </td>
            <td  width="35" align="center" valign="top" ><img id="iorderdel_<?php echo $orderid; ?>" src="images/delete.png"  border="0" class="iorderdel"  /></td>
          </tr>
          <?php
}
?> 
       
 
</table>
</div>

<div class="piliang"> 
<input type="checkbox" name="SELALL" value="1" onClick="SelAll(this.form)">
        <?php echo $strSelAll; ?>
       
        <input type="radio" name="step" value="okall" />
        标注处理完成
         
		<input type="radio" name="step" value="notokall" />
        标注未处理
       
        <input type="submit" name="Submit2" class="button" value="<?php echo $strSubmit; ?>" />
        &nbsp;&nbsp;<a style="cursor:pointer;color:#ffffff;font-weight:bold" onClick="delfm.submit()"> 
        </a> 
		<input type="hidden" name="key" size="3" value="<?php echo $key; ?>" />
        <input type="hidden" name="shownum" value="<?php echo $shownum; ?>" />
        <input type="hidden" name="showtype" value="<?php echo $showtype; ?>" />
        <input type="hidden" name="showtid" value="<?php echo $showtid; ?>" />
</div>
</form>


</body>
</html>
