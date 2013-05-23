<?php
define("ROOTPATH", "../../");
include(ROOTPATH."includes/admin.inc.php");
include(ROOTPATH."includes/pages.inc.php");
include("language/".$sLan.".php");
include("func/member.inc.php");
NeedAuth(68);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link  href="css/style.css" type="text/css" rel="stylesheet">
<title><?php echo $strAdminTitle; ?></title>
</head>

<body >

<?php
$step=$_REQUEST["step"];
$memberid=$_REQUEST["memberid"];
$id=$_REQUEST["id"];

if($memberid==""){
	echo "ERROR:NO MEMBERID";
	exit;
}

//查询

$scl=" memberid='$memberid' ";
$totalnums=TblCount("_member_buylist","id",$scl);

$pages = new pages;		
$pages->setvar(array("memberid" => $memberid));

$pages->set(20,$totalnums);		                          
	
$pagelimit=$pages->limit();	  
?>
<div class="formzone">
<div class="namezone" ><?php echo $strAccBuyList; ?> - [ <?php echo Memberid2User($memberid); ?> ]</div>

<div class="tablezone">

  <table width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
    <tr> 
      <td width="150" height="28"  class="innerbiaoti"><span class="title"><?php echo $strAccBuyTime; ?></span></td>
      <td  class="innerbiaoti" height="28"><span class="title"><?php echo $strAccBuyJine; ?></span></td>
      <td  class="innerbiaoti" width="100"><span class="title"><?php echo $strAccBuyFrom; ?></span></td>
      <td  class="innerbiaoti" width="100"><span class="title"><?php echo $strAccBuyOrder; ?></span></td>
      <td  class="innerbiaoti" width="80"><span class="title"><?php echo $strLogName; ?></span></td>
      </tr>
<?php
$msql->query("select * from {P}_member_buylist where $scl order by id desc limit $pagelimit");

while($msql->next_record()){
$id=$msql->f('id');
$payid=$msql->f('payid');
$paytype=$msql->f('paytype');
$buyfrom=$msql->f('buyfrom');
$paytotal=$msql->f('paytotal');
$daytime=$msql->f('daytime');
$ip=$msql->f('ip');
$orderid=$msql->f('orderid');
$OrderNo=$msql->f('OrderNo');
$logname=$msql->f('logname');

$daytime=date("Y-n-j H:i:s",$daytime);


?>
<tr class="list"> 
        <td width="150"  > <?php echo $daytime; ?> </td>
        <td   height="26"><?php echo $paytotal; ?></td>
        <td width="100"><?php echo $buyfrom; ?></td>
        <td width="100"><?php echo $OrderNo; ?></td>
        <td width="80"><?php echo $logname; ?></td>
      </tr>

    <?php

}
?> 
  </table>
  </div>
  <?php
$pagesinfo=$pages->ShowNow();
?>
<div id="showpages">
	  <div id="pagesinfo"><?php echo $strPagesTotalStart.$totalnums.$strPagesTotalEnd; ?> <?php echo $strPagesMeiye.$pagesinfo["shownum"].$strPagesTotalEnd; ?> <?php echo $strPagesYeci; ?> <?php echo $pagesinfo["now"]."/".$pagesinfo["total"]; ?></div>
	  <div id="pages"><?php echo $pages->output(1); ?></div>
</div>
</div>
</body>
</html>
