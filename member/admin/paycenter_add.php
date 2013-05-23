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
<script type="text/javascript" src="../../base/js/base.js"></script>
<script type="text/javascript" src="js/paycenter.js"></script>
</head>

<body >


<?php

$step=$_REQUEST["step"];
$pcenter=$_REQUEST["pcenter"];
$pcentertype=$_REQUEST["pcentertype"];
$pcenteruser=$_REQUEST["pcenteruser"];
$pcenterkey=$_REQUEST["pcenterkey"];
$key1=$_REQUEST["key1"];
$key2=$_REQUEST["key2"];
$postfile=$_REQUEST["postfile"];
$recfile=$_REQUEST["recfile"];
$ifuse=$_REQUEST["ifuse"];
$ifback=$_REQUEST["ifback"];
$intro=$_POST["intro"];
$xuhao=$_POST["xuhao"];


if($step=="new"){

	if($pcenter==""){
			err($strPayTypeNTC4,"","");
			exit;
	}

	if($pcentertype=="1"){
		if($pcenteruser=="" || $pcenterkey==""){
			err($strPayTypeNTC2,"","");
			exit;
		}
		if($postfile=="" || $recfile==""){
			err($strPayTypeNTC3,"","");
			exit;
		}
		
	}

	$msql->query("insert into {P}_member_paycenter set
	`pcenter`='$pcenter',
 	`pcentertype`='$pcentertype',
  	`pcenteruser`='$pcenteruser',
  	`pcenterkey`='$pcenterkey',
  	`key1`='$key1',
  	`key2`='$key2',
   	`postfile`='$postfile',
  	`recfile`='$recfile',
  	`ifuse`='$ifuse',
  	`ifback`='$ifback',
   	`xuhao`='$xuhao',
	`intro`='$intro'

	");

echo "<script>window.location='paycenter.php'</script>";
exit;

}
?>
<div class="formzone">
<div class="namezone"><?php echo $strPayTypeAdd; ?></div>
<div class="tablezone">
  <table width="100%" border="0" cellspacing="0" cellpadding="3" align="center" >
    <form action="paycenter_add.php" method="post" enctype="multipart/form-data">
      <tr>
        <td height="8" colspan="2" ></td>
      </tr>
      <tr>
        <td width="100" height="38" align="center"><span class="title"><?php echo $strPaycenterType; ?></span></td>
        <td height="38"><input name="pcentertype" class="pcentertype" type="radio" value="0" checked="checked"  />
            <?php echo $strPayType0; ?>
			<input type="radio" name="pcentertype"  class="pcentertype"  value="1"  disabled />
            <?php echo $strPayType1; ?> (暂不支持)                      </td>
      </tr>
      <tr>
        <td width="100" align="center"><span class="title"><?php echo $strPaycenterName; ?></span></td>
        <td><input name="pcenter" type="text"  class="input" id="pcenter" size="51" />
            <font color="#FF3300">* </font></td>
      </tr>
      <tr>
        <td width="100" align="center"><span class="title"><?php echo $strPaycenterIntro; ?></span></td>
        <td><textarea name="intro" cols="50" rows="5" class="textarea" id="intro"></textarea>
        </td>
      </tr>
      <tr class="tronline" style="display:none">
        <td width="100" align="center"><span class="title"><?php echo $strPaycenterUser; ?></span></td>
        <td><input name="pcenteruser" type="text"  class="input" id="pcenteruser" size="35" />
            <font color="#FF3300">* </font></td>
      </tr>
      <tr class="tronline" style="display:none">
        <td width="100" align="center"><span class="title"><?php echo $strPaycenterKey; ?></span></td>
        <td><input name="pcenterkey" type="text"  class="input" id="pcenterkey" size="35" />
            <font color="#FF3300">* </font></td>
      </tr>
      <tr class="tronline" style="display:none">
        <td align="center"><span class="title"><?php echo $strPaycenterKey1; ?></span></td>
        <td><input name="key1" type="text"  class="input" id="key1" size="35" />            </td>
      </tr>
      <tr class="tronline" style="display:none">
        <td align="center"><span class="title"><?php echo $strPaycenterKey2; ?></span></td>
        <td><input name="key2" type="text"  class="input" id="key2" size="35" />
        </td>
      </tr>
      <tr class="tronline" style="display:none">
        <td width="100" align="center"><?php echo $strPaycenterPf; ?></td>
        <td><input name="postfile" type="text"  class="input" id="postfile" size="35" />
            <span class="title"> <font color="#FF3300">* </font></span> </td>
      </tr>
      <tr class="tronline" style="display:none">
        <td width="100" align="center">&nbsp;</td>
        <td><span class="title"><font color="#999999"><?php echo $strPayTypeMemo2; ?></font></span></td>
      </tr>
      <tr class="tronline" style="display:none">
        <td width="100" align="center"><?php echo $strPaycenterRf; ?></td>
        <td><input name="recfile" type="text"  class="input" id="recfile" size="35" onchange="xx.value='<?php echo $SiteUrl; ?>api/paycenter/'+recfile.value+''" />
            <span class="title"> <font color="#FF3300">* </font></span></td>
      </tr>
      <tr  class="tronline" style="display:none">
        <td width="100" align="center">&nbsp;</td>
        <td><span class="title"><font color="#999999"><?php echo $strPayTypeMemo3; ?>
                <input name="xx" type="text" id="xx"  style="border:0px #ffffff solid;color:#999999" value="<?php echo $SiteUrl; ?>api/paycenter/_rec.php" size="70" />
        </font></span></td>
      </tr>
      <tr  class="tronline" style="display:none">
        <td width="100" align="center"><?php echo $strPaycenterIfback; ?></td>
        <td><input name="ifback" type="checkbox" id="ifback" value="1" />
            <?php echo $strPaycenterZcback; ?></td>
      </tr>
      <tr>
        <td width="100" align="center"><span class="title"><?php echo $strIdx; ?></span></td>
        <td><input name="xuhao" type="text"  class="input" id="xuhao" value="0" size="6" /></td>
      </tr>
      <tr>
        <td width="100" align="center"><?php echo $strPaycenterIfUse; ?></td>
        <td><input name="ifuse" type="checkbox" id="ifuse" value="1" checked="checked" />
            <?php echo $strPaycenterUse; ?></td>
      </tr>
      <tr>
        <td width="100" align="center">&nbsp;</td>
        <td height="36"><input type="submit" name="Submit" value="<?php echo $strConfirm; ?>" class="button" />
            <input type="hidden" name="step" value="new" />
        </td>
      </tr>
    </form>
  </table>

  
</div>
</div>
</body>
</html>
