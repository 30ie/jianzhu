<?php
define("ROOTPATH", "../../");
include(ROOTPATH."includes/admin.inc.php");
include(ROOTPATH."includes/pages.inc.php");
include("language/".$sLan.".php");
include("func/webmall.inc.php");
NeedAuth(44);

$step=$_REQUEST["step"];
$page=$_REQUEST["page"];
$key=$_REQUEST["key"];
$tp=$_REQUEST["tp"];
$shownum=$_REQUEST["shownum"];
$showtype=$_REQUEST["showtype"];
$fromM=$_REQUEST["fromM"];
$fromD=$_REQUEST["fromD"];
$fromY=$_REQUEST["fromY"];
$toM=$_REQUEST["toM"];
$toD=$_REQUEST["toD"];
$toY=$_REQUEST["toY"];

if(!isset($shownum) || $shownum<10){
$shownum=10;
}

if(!isset($showtype) || $showtype==""){
$showtype=0;
}



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
<div class="searchzone">
<table width="100%" border="0" cellspacing="0" cellpadding="3">
 
  <tr> 
      <td height="28"  > 
        <table border="0" cellspacing="1" cellpadding="0" width="100%">
           <tr> 
            <td> <form name="search" action="torder.php" method="get" >
              <span class="title"><?php echo DayList("fromY","fromM","fromD",$fromY,$fromM,$fromD); ?> - <?php echo DayList("toY","toM","toD",$toY,$toM,$toD); ?>
              <select name="shownum">
                <option value="10"  <?php echo seld($shownum,"10"); ?>><?php echo $strSelNum10; ?></option>
                <option value="20" <?php echo seld($shownum,"20"); ?>><?php echo $strSelNum20; ?></option>
                <option value="30" <?php echo seld($shownum,"30"); ?>><?php echo $strSelNum30; ?></option>
                <option value="50" <?php echo seld($shownum,"50"); ?>><?php echo $strSelNum50; ?></option>
              </select>
              <select name="showtype" id="showtype">
				<option value="all"  <?php echo seld($showtype,"all"); ?>>全部订单</option>
				<option value="0"  <?php echo seld($showtype,"0"); ?>>全部未完成订单</option>
				<option value="1"  <?php echo seld($showtype,"1"); ?>>未付款订单</option>
				<option value="2"  <?php echo seld($showtype,"2"); ?>>已付款未处理订单</option>
				<option value="3"  <?php echo seld($showtype,"3"); ?>>已完成订单</option>
               
              </select>
              <input type="text" name="key" value="<?php echo $key; ?>"  size="26" class="input" />
              <input type="submit" name="Submit" value="<?php echo $strSearchTitle; ?>" class="button" />
              <input type="hidden" name="tp" value="search" />
              </span>
            </form></td>
          </tr>
        </table>
    </td>
   
  
     
   
  </tr> 
</table>

</div>
<?php
//订单处理
if($step=="okall"){
	NeedAuth(36);
	$dall=$_POST["dall"];
	$nums=sizeof($dall);
	for($i=0;$i<$nums;$i++){
		$ids=$dall[$i];
		$msql->query("update {P}_webmall_torder set ifok='1' where id='$ids'");
	}
}

if($step=="notokall"){
	NeedAuth(36);
	$dall=$_POST["dall"];
	$nums=sizeof($dall);
	for($i=0;$i<$nums;$i++){
		$ids=$dall[$i];
		$msql->query("update {P}_webmall_torder set ifok='0' where id='$ids'");
	}
}


//订单查询
$fromtime=@mktime(0,0,0,$fromM,$fromD,$fromY);
$totime=@mktime(23,59,59,$toM,$toD,$toY);

//显示订单的范围
switch($showtype){
	case "1":
		$scl=" ifpay='0' ";
	break;
	case "2":
		$scl=" ifpay='1' and ifok='0' ";
	break;
	case "3":
		NeedAuth(35);
		$scl=" ifpay='1' and ifok='1' ";
	break;
	case "all":
		$scl=" id!=0 ";
	break;
	default:
		$scl=" (ifpay='0' or  ifok='0') ";
	break;
}



if($key!=""){
$scl.=" and (binddomain regexp '$key' or company regexp '$key' or name regexp '$key') ";
}

if($tp=="search"){
		
	$scl.=" and dtime>=$fromtime and dtime<=$totime ";
	
}


$msql->query("select count(id) from {P}_webmall_torder where $scl");
if($msql->next_record()){
$totalnums=$msql->f('count(id)');
}


$pages = new pages;		
	$pages->setvar(array(
	"key" => $key,
	"shownum" => $shownum,
	"showtype" => $showtype,
	"tp" => $tp,
	"fromY" => $fromY,
	"fromM" => $fromM,
	"fromD" => $fromD,
	"toY" => $toY,
	"toM" => $toM,
	"toD" => $toD
	));

$pages->set($shownum,$totalnums);		                          
$pagelimit=$pages->limit();	

?>
<form name="delfm" method="post" action="torder.php">
<div class="listzone">
<table width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
          <tr>
            <td width="30"  class="biaoti" align="center"><?php echo $strSel; ?></td> 
    <td width="39"  class="biaoti">ID</td>
            <td  class="biaoti" >订购人</td>
            <td width="120" height="28"  class="biaoti" >授权捆绑域名</td>
            <td width="50"  class="biaoti" >产品号</td>
			 <td width="60"  class="biaoti" >套餐价格</td>
            <td width="70"  class="biaoti" >更新时间</td>
		    <td width="35" align="center"  class="biaoti" >付款</td>
            <td width="35" align="center"  class="biaoti" >处理</td>
            <td width="50"  class="biaoti" align="center" >下属</td>
            <td width="35"  class="biaoti" align="center" >详情</td>
		    <td height="28" width="35"  class="biaoti" align="center" >删除</td>
          </tr>
          <?php 


$msql->query("select * from {P}_webmall_torder where $scl order by uptime desc limit $pagelimit");

while($msql->next_record()){
$orderid=$msql->f('id');
$memberid=$msql->f('memberid');
$company=$msql->f('company');
$name=$msql->f('name');
$binddomain=$msql->f('binddomain');
$siteid=$msql->f('siteid');
$tempid=$msql->f('tempid');
$tempname=$msql->f('tempname');
$total=$msql->f('total');
$ifpay=$msql->f('ifpay');
$ifok=$msql->f('ifok');
$dtime=$msql->f('dtime');
$paytime=$msql->f('paytime');
$uptime=$msql->f('uptime');


	$fsql->query("select spool from {P}_webmall_spool where id='$tempid' limit 0,1");
	if($fsql->next_record()){
		$spool=$fsql->f('spool');
	}
	
	$fsql->query("select user from {P}_member where memberid='$memberid' limit 0,1");
	if($fsql->next_record()){
		$user=$fsql->f('user');
	}
	
	$inums=0;
	$vnums=0;
	$icolor="#333333";
	$fsql->query("select ifpay,ifok from {P}_webmall_iorder where tid='$orderid'");
	while($fsql->next_record()){
		$iifpay=$fsql->f('ifpay');
		$iifok=$fsql->f('ifok');
		if($iifpay!="1" || $iifok!="1"){
			$icolor="#ff0000";
			$vnums++;
		}
		$inums++;
	}
	
$dtime=date("y/m/d",$dtime);
$paytime=date("y/m/d",$paytime);
$uptime=date("y/m/d",$uptime);

	

?> 
          <tr class="list" id="tr_<?php echo $orderid; ?>">
            <td width="30" align="center" ><input type="checkbox" name="dall[]" value="<?php echo $orderid; ?>" />
            </td> 
            <td   width="39" valign="top"><?php echo $orderid; ?> </td>
			 <td valign="top"><?php echo $company; ?> </td>
			 <td width="120" valign="top"><?php echo $binddomain; ?></td>
			 <td width="50" valign="top"><?php echo $spool; ?></td>
			 <td valign="top"><?php echo $total; ?></td>
            <td   width="70" valign="top"><?php echo $uptime; ?> </td>
           
            <td   width="35" align="center" valign="top"><?php echo showYN($ifpay); ?> </td>
            <td   width="35" align="center" valign="top"><?php echo showYN($ifok); ?></td>
            <td  width="50" align="center" valign="top" ><a href="iorder.php?showtid=<?php echo $orderid; ?>&amp;showtype=all" style="font:12px/22px Verdana, Arial, Helvetica, sans-serif;color:<?php echo $icolor; ?>"><?php echo $vnums; ?>/<?php echo $inums; ?></a></td>
            <td  width="35" align="center" valign="top" ><a href="torder_detail.php?id=<?php echo $orderid; ?>"><img src="images/look.png" width="24" height="24"  border="0" /></a> </td>
            <td  width="35" align="center" valign="top" ><img id="torderdel_<?php echo $orderid; ?>" src="images/delete.png"  border="0" class="torderdel"  /></td>
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
</div>
</form>


<?php
$pagesinfo=$pages->ShowNow();
?>
<div id="showpages">
	  <div id="pagesinfo"><?php echo $strPagesTotalStart.$totalnums.$strPagesTotalEnd; ?> <?php echo $strPagesMeiye.$pagesinfo["shownum"].$strPagesTotalEnd; ?> <?php echo $strPagesYeci; ?> <?php echo $pagesinfo["now"]."/".$pagesinfo["total"]; ?></div>
	  <div id="pages"><?php echo $pages->output(1); ?></div>
</div>
</body>
</html>
