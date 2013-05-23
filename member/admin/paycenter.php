<?php
define("ROOTPATH", "../../");
include(ROOTPATH."includes/admin.inc.php");
include("language/".$sLan.".php");
include("func/member.inc.php");
NeedAuth(69);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link  href="css/style.css" type="text/css" rel="stylesheet">
<title><?php echo $strAdminTitle; ?></title>
 <SCRIPT>
function cm(nn){
qus=confirm("<?php echo $strPayTypeNTC5; ?>")
if(qus!=0){
window.location='paycenter.php?step=del&id='+nn;
}
}
</SCRIPT>
</head>

<body >


<?php

$step=$_REQUEST["step"];
$id=$_REQUEST["id"];

if($step=="del"){
$msql->query("delete from {P}_member_paycenter where id='$id'");
}
?>
<div class="formzone">
<div class="namezone" style="float:left;margin:10px 10px 0px 10px"><?php echo $strPayTypeSet; ?></div>
<div style="float:right;margin-right:3px;margin-top:5px">
<input type="button" name="Submit" value="<?php echo $strPayTypeAdd; ?>"  class="button" onclick="window.location='paycenter_add.php'" />
 </div>


<div class="tablezone" style="clear:both;">

  <table width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
    <tr> 
      <td width="50" height="28"  class="innerbiaoti"><?php echo $strXuhao; ?></td>
      <td width="130"  class="innerbiaoti"><?php echo $strPaycenterType; ?></td>
      <td  class="innerbiaoti" width="100"><?php echo $strPaycenterName; ?></td>
      <td  class="innerbiaoti" height="28"><?php echo $strPaycenterIntro; ?></td>
      <td  class="innerbiaoti" height="28" width="70"><?php echo $strPaycenterUse; ?></td>
      <td  class="innerbiaoti" width="70"><?php echo $strModify; ?></td>
      <td  class="innerbiaoti" height="28" width="70"><?php echo $strDelete; ?></td>
    </tr>
<?php
$msql->query("select * from {P}_member_paycenter order by xuhao");
while($msql->next_record()){
$id=$msql->f('id');
$pcenter=$msql->f('pcenter');
$pcentertype=$msql->f('pcentertype');
$pcenteruser=$msql->f('pcenteruser');
$pcenterkey=$msql->f('pcenterkey');
$hbtype=$msql->f('hbtype');
$ifuse=$msql->f('ifuse');
$ifback=$msql->f('ifback');
$intro=$msql->f('intro');
$xuhao=$msql->f('xuhao');


$intro=str_replace("\n","<br>",$intro);

switch($pcentertype){
case "0":
$saytype=$strPayType0;
break;
case "1":
$saytype=$strPayType1;
break;
case "2":
$saytype=$strPayType2;
break;
}


?><tr class="list"> 
        <td width="50"  > <?php echo $xuhao; ?> </td>
        <td width="130"><?php echo $saytype; ?></td>
        <td width="100"><?php echo $pcenter; ?></td>
        <td   height="26"><?php echo $intro; ?></td>
        <td   height="26" width="70"><?php ShowYN($ifuse); ?></td>
        <td width="70"><img src="images/edit.png" width="24" height="24"  style="cursor:pointer"  onClick="window.location='paycenter_modify.php?id=<?php echo $id; ?>'" /></td>
        <td   height="26" width="70"><img src="images/delete.png"  style="cursor:pointer"  onClick="cm('<?php echo $id; ?>')"></td>
      </tr>
    <?php

}
?> 
  </table>
  </div>
</div>
</body>
</html>
