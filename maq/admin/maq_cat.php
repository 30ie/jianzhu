<?php
define("ROOTPATH", "../../");
include(ROOTPATH."includes/admin.inc.php");
include(ROOTPATH."includes/pages.inc.php");
include("language/".$sLan.".php");
NeedAuth(171);

$step=$_REQUEST["step"];
$id=$_REQUEST["id"];
$cat=$_REQUEST["cat"];
$catid=$_REQUEST["catid"];
$xuhao=$_REQUEST["xuhao"];
$bcat=$_REQUEST["bcat"];


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link  href="css/style.css" type="text/css" rel="stylesheet">
<title><?php echo $strAdminTitle; ?></title>

<SCRIPT>
function cm(nn){
	qus=confirm("<?php echo $strDeleteConfirm; ?>")
	if(qus!=0){
	window.location='maq_cat.php?step=del&catid='+nn;
	}
}

</SCRIPT>
</head>

<body>
<?php

if($step=="mod"){
	$fsql->query("update {P}_maq_cat set cat='$cat',xuhao='$xuhao' where  catid='$catid'");
	echo "<script>self.location='maq_cat.php'</script>";
}

if($step=="submodi"){
	$subcat=$_REQUEST["subcat"];
	$subcatid=$_REQUEST["subcatid"];
	$subxuhao=$_REQUEST["subxuhao"];

	$fsql->query("update {P}_maq_cat set cat='$subcat',xuhao='$subxuhao' where  catid='$subcatid'");
	echo "<script>self.location='maq_cat.php'</script>";
}




if($step=="del"){
	$fsql->query("select * from {P}_maq where catid='$catid'");
	if($fsql->next_record()){
		err($strMaqNTC1,"maq_cat.php","");
	}
	
	$fsql->query("select * from {P}_maq_cat where pid='$catid'");
	if($fsql->next_record()){
		err($strMaqNTC5,"maq_cat.php","");
	}

	$fsql->query("delete from {P}_maq_cat where catid='$catid'");
	echo "<script>self.location='maq_cat.php'</script>";
}

if($step=="add"){
	if($bcat==""){
		err($strMaqNTC2,"maq_cat.php","");
	}
	
	$msql->query("select max(xuhao) from {P}_maq_cat where pid='0'");
	if($msql->next_record()){
		$xuhao=$msql->f('max(xuhao)')+1;
	}
	
	$msql->query("insert into {P}_maq_cat set
	pid='0',
	cat='$bcat',
	xuhao='$xuhao',
	moveable='1'
	");
	$instcatid=$msql->instid();
	
	//生成catpath
	$catpath=fmpath($instcatid).":";
	$msql->query("update {P}_maq_cat set catpath='$catpath' where catid='$instcatid'");


	echo "<script>self.location='maq_cat.php'</script>";
}

if($step=="addsub"){
	$pid=$_REQUEST["pid"];
	$msql->query("select max(xuhao) from {P}_maq_cat where pid='$pid'");
	if($msql->next_record()){
		$subxuhao=$msql->f('max(xuhao)')+1;
	}
	
	$msql->query("insert into {P}_maq_cat set
	pid='$pid',
	cat='$strMaqSubCat',
	xuhao='$subxuhao',
	moveable='1'
	");
	$instcatid=$msql->instid();
	
	//生成catpath
	$catpath=fmpath($pid).":".fmpath($instcatid).":";
	$msql->query("update {P}_maq_cat set catpath='$catpath' where catid='$instcatid'");


	echo "<script>self.location='maq_cat.php'</script>";
}

?>
<div class="formzone">
<div class="namezone">

<div style="display:inline;float:right">  
	<form name="form1" action="maq_cat.php">
        <input type="text" name="bcat" size="25"  class="input" maxlength="16">
        <input type="submit" name="Submit22" class=button value="<?php echo $strMaqCatAdd; ?>">
        <input type="hidden" name="step" value="add"></form>
</div>
<?php echo $strSetMenu2; ?>

</div>
<div class="tablezone">

<table width="100%" border="0" cellspacing="0" cellpadding="6" align="center">
  <tr>
    <td  class="innerbiaoti" height="28" align="center" width="50"><?php echo $strMaqCatXuhao; ?></td>
    <td height="28"  class="innerbiaoti"><?php echo $strMaqCatName; ?> 
	
	</td>
    </tr>
<?php
$msql->query("select * from {P}_maq_cat where pid='0' order by xuhao ");

while($msql->next_record()){
$catid=$msql->f('catid');
$cat=$msql->f('cat');
$xuhao=$msql->f('xuhao');
$moveable=$msql->f('moveable');
?>  
<form method="post" action="maq_cat.php" id="bform_<?php echo $catid; ?>">
  <tr class="list">
    <td  height="26"  align="center"> 
        <input type="text" name="xuhao" size="3"  class="input" value="<?php echo $xuhao; ?>">
      </td>
      <td   height="26" > 
        <input type="text" name="cat" size="35"  class="input" value="<?php echo $cat; ?>" maxlength="16">
        <input type="hidden" name="step" value="mod" />
        <input type="hidden" name="catid" value="<?php echo $catid; ?>" /> 
		 <a href="#" class="catmodi"  onClick="document.getElementById('bform_<?php echo $catid; ?>').submit();"><?php echo $strModify; ?></a> &nbsp;
        <a href="#" class="catmodi"  onClick="cm('<?php echo $catid; ?>')"><?php echo $strDelete; ?></a> &nbsp;
		<a class="addsub" onClick="self.location='maq_cat.php?step=addsub&pid=<?php echo $catid; ?>'" ><?php echo $strMaqSCatAdd; ?></a>
		</td>
     
  </tr> 
  </form>
  
  <?php
$fsql->query("select * from {P}_maq_cat where pid='$catid' order by xuhao ");
while($fsql->next_record()){
$subcatid=$fsql->f('catid');
$subcat=$fsql->f('cat');
$subxuhao=$fsql->f('xuhao');

?>

<form method="post" action="maq_cat.php" id="sform_<?php echo $subcatid; ?>">
  <tr class="list">
    <td  height="26"  align="center">&nbsp; 
      </td>
      <td   height="26" > 
        <input type="text" name="subxuhao" size="3"  class="input" value="<?php echo $subxuhao; ?>" />
        <input type="text" name="subcat" size="25"  class="input" value="<?php echo $subcat; ?>" maxlength="16" />
        <input type="hidden" name="step" value="submodi" />
        <input type="hidden" name="subcatid" value="<?php echo $subcatid; ?>" /> 
		 <a href="#" class="catmodi"  onClick="document.getElementById('sform_<?php echo $subcatid; ?>').submit();"><?php echo $strModify; ?></a> &nbsp;
        <a href="#" class="catmodi"  onClick="cm('<?php echo $subcatid; ?>')"><?php echo $strDelete; ?></a>
		
		</td>
     
  </tr> 
  </form>


<?php
}
?>  

  <?php
}
?>
</table>
</div>
</div>
</body>
</html>
