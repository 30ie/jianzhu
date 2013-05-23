<?php
define("ROOTPATH", "../../");
include(ROOTPATH."includes/admin.inc.php");
include(ROOTPATH."includes/pages.inc.php");
include("language/".$sLan.".php");
NeedAuth(43);

$page=$_REQUEST["page"];
$key=$_REQUEST["key"];
$shownum=$_REQUEST["shownum"];

if(!isset($shownum) || $shownum<10){
$shownum=20;
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
<script type="text/javascript" src="js/modules.js"></script>

</head>

<body>
<div class="searchzone">
<table width="100%" border="0" cellspacing="0" cellpadding="3">
 
  <tr> 
      <td height="28"  > 
        <table border="0" cellspacing="1" cellpadding="0" width="100%">
           <tr> 
            <td> <form name="search" action="modules.php" method="get" >
              <select name="shownum">
                <option value="10"  <?php echo seld($shownum,"10"); ?>><?php echo $strSelNum10; ?></option>
                <option value="20" <?php echo seld($shownum,"20"); ?>><?php echo $strSelNum20; ?></option>
                <option value="30" <?php echo seld($shownum,"30"); ?>><?php echo $strSelNum30; ?></option>
                <option value="50" <?php echo seld($shownum,"50"); ?>><?php echo $strSelNum50; ?></option>
              </select>
              <input type="text" name="key" size="20"  class="input"  value="<?php echo $key; ?>" />
              <input type="submit" name="Submit" value="<?php echo $strSearchTitle; ?>" class="button" />
            
            </form></td>
          <td width="130" align="right"><input name="vvv" type="button" id="getmodules" value="同步模块产品" class="button" /></td>
          </tr>
        </table>
    </td>
   
  
     
   
  </tr> 
</table>

</div>
<?php

$scl=" id!='0' ";


if($key!=""){
$scl.=" and (cname regexp '$key' or module regexp '$key') ";
}

$msql->query("select count(id) from {P}_webmall_modules where $scl");
if($msql->next_record()){
$totalnums=$msql->f('count(id)');
}


$pages = new pages;		
$pages->setvar(array(
"key" => $key,
"shownum" => $shownum
));

$pages->set($shownum,$totalnums);		                          
$pagelimit=$pages->limit();	

?>
<div class="listzone">
<table width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
          <tr> 
    <td width="60" height="28"  class="biaoti">ID</td>
            <td width="110"  class="biaoti" >模块代码</td>
            <td  class="biaoti" >模块名称</td>
            <td width="80"  class="biaoti" >模块价格</td>
            <td width="80"  class="biaoti" >代理价I</td>
            <td width="80"  class="biaoti" >代理价II</td>
            <td width="39"  class="biaoti" >单位</td>
            <td width="39"  class="biaoti" align="center" >修改</td>
	      </tr>
<?php 

$msql->query("select * from {P}_webmall_modules where $scl order by id limit $pagelimit");

while($msql->next_record()){
$id=$msql->f('id');
$module=$msql->f('module');
$cname=$msql->f('cname');
$price=$msql->f('price');
$price1=$msql->f('price1');
$price2=$msql->f('price2');
$mtype1=$msql->f('mtype1');
$mtype2=$msql->f('mtype2');
$danwei=$msql->f('danwei');


?> 
          <tr class="list" id="tr_<?php echo $id; ?>"> 
            <td   width="60" valign="top"><?php echo $id; ?> </td>
			 <td   width="110" valign="top"> <?php echo $module; ?></td>
			 <td valign="top"><?php echo $cname; ?> </td>
		    <td   width="80" valign="top"><?php echo $price; ?> </td>
            <td   width="80" valign="top"><?php echo $price1; ?> </td>
            <td   width="80" valign="top"><?php echo $price2; ?> </td>
            <td   width="39" valign="top"><?php echo $danwei; ?> </td>
            <td  width="39" align="center" valign="top" ><a href="modulemodi.php?id=<?php echo $id; ?>"><img src="images/modi.png"  border="0" /></a> </td>
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
</body>
</html>
