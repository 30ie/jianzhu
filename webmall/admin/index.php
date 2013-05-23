<?php
define("ROOTPATH", "../../");
include(ROOTPATH."includes/admin.inc.php");
include("language/".$sLan.".php");
NeedAuth(0);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">

<head >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link  href="css/style.css" type="text/css" rel="stylesheet">
<title><?php echo $strAdminTitle; ?></title>

<script  language="javascript" src="<?php echo ROOTPATH; ?>base/js/base.js"></script>   
<script  language="javascript" src="js/frame.js"></script>   

</head>

<body class="framebody">
<table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="150" valign="top">

<div class="frameleft">



<ul class="menulist">
<li id="m5" class="menulist"><?php echo $strSetMenu5; ?></li>
<li id="m13" class="menulist"><?php echo $strSetMenu13; ?></li>
</ul>


<ul class="menulist">
<li id="m9" class="menulist"><?php echo $strSetMenu9; ?></li>
<li id="m10" class="menulist"><?php echo $strSetMenu10; ?></li>
</ul>

<ul class="menulist">
<li id="m1" class="menulist"><?php echo $strSetMenu1; ?></li>
<li id="m11" class="menulist"><?php echo $strSetMenu11; ?></li>
</ul>



<ul class="menulist">
<li id="m4" class="menulist"><?php echo $strSetMenu4; ?></li>
</ul>

</div>

</td>
<td valign="top">


<div class="framemain">
<iframe id="framecon" src='spool.php'  name='con' width='100%' height='100%' scrolling='yes' marginheight='0'  frameborder='0'>IE</iframe> 
</div>
</td>
</tr>
</table>
</body>
</html>
