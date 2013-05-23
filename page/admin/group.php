<?php
define("ROOTPATH", "../../");
include(ROOTPATH."includes/admin.inc.php");
include(ROOTPATH."includes/pages.inc.php");
include("language/".$sLan.".php");
NeedAuth(301);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link  href="css/style.css" type="text/css" rel="stylesheet">
<title><?php echo $strAdminTitle; ?></title>
<script type="text/javascript" src="../../base/js/base.js"></script>
<script type="text/javascript" src="../../base/js/form.js"></script>
<script type="text/javascript" src="../../base/js/blockui.js"></script>
<script type="text/javascript" src="js/page.js"></script>
</head>

<body>

<?php


$step=$_REQUEST["step"];

if($step=="del"){
	$id=$_GET["id"];
	
	$msql->query("select id from {P}_page where groupid='$id'");
	if($msql->next_record()){
		err($strGroupNTC4,"","");
		exit;
	}


	$msql->query("select * from {P}_page_group where id='$id'");
	if($msql->next_record()){
		$moveable=$msql->f('moveable');
		$delfolder=$msql->f('folder');
		$delfolder_main=$delfolder."_main";
	}else{
		err($strGroupNTC3,"","");
		exit;
	}

	if($moveable!='1'){
		err($strGroupNTC1,"","");
		exit;
	}
	
	//删除页面记录
	$msql->query("delete from {P}_base_pageset where coltype='page' and pagename='$delfolder'");
	$msql->query("delete from {P}_base_pageset where coltype='page' and pagename='$delfolder_main'");
	
	//删除插件记录
	$msql->query("delete from {P}_base_plus where plustype='page' and pluslocat='$delfolder'");
	$msql->query("delete from {P}_base_plus where plustype='page' and pluslocat='$delfolder_main'");
	
	//删除分组
	$msql->query("delete from {P}_page where groupid='$id'");
	$msql->query("delete from {P}_page_group where id='$id'");

	//删除文件和目录
	if($delfolder!="" && strlen($delfolder)>1 && !strstr($delfolder,".") && !strstr($delfolder,"/")){
		DelFold("../".$delfolder);
	}
	
}


?> 
<div class="searchzone">
<table width="100%" border="0" cellspacing="0" cellpadding="3" align="center">
  <tr> 
   <td >
      <form method="get" action="group.php">
        <input type="text" name="key" size="30" class="input" />
        <input type="submit" name="Submit2" value="<?php echo $strSearchTitle; ?>" class="button">
     </form>
    </td> 
	
      <td align="right" > 
	  
	  <form id="addGroupForm" method="post" action="">
       <?php echo $strGroupName; ?>  <input name="act" type="hidden" id="act" value="addgroup" />
        <input type="text" name="groupname" class="input" size="18" />&nbsp;
        <?php echo $strGroupFolder; ?> 
        &nbsp;<input name="folder" type="text" class="input" id="newfolder" size="12" maxlength="16" />
        &nbsp;<input type="submit" name="cd" value="<?php echo $strGroupAdd; ?>" class="button" />
      </form>
	  <div  id="notice" class="noticediv"></div>
	  </td>
      
    
  </tr>
</table>
</div>


<?php
$scl="  id!='0' ";

if($key!=""){
$scl.=" and groupname regexp '$key'  ";
}

$totalnums=TblCount("_page_group","id",$scl);

$pages = new pages;		
$pages->setvar(array("key" => $key));

$pages->set(10,$totalnums);		                          
	
$pagelimit=$pages->limit();	  

?>
<div class="listzone">
<table width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
  <tr>
    <td  class="biaoti" width="50" align="center"><?php echo $strNumber; ?></td>
    <td  class="biaoti" width="130" height="26"><?php echo $strGroupName; ?> 
      </td>
    <td width="100"  class="biaoti"><?php echo $strGroupFolder; ?></td>
    <td  class="biaoti"><?php echo $strGroupUrl; ?></td>
    <td width="50"  class="biaoti"><?php echo $strGroupEdit; ?></td>
    <td width="50"  class="biaoti"><?php echo $strDelete; ?></td>
    </tr>
  <?php
$msql->query("select * from {P}_page_group where $scl order by id desc limit $pagelimit");

while($msql->next_record()){
$id=$msql->f("id");
$groupname=$msql->f("groupname");
$moveable=$msql->f("moveable");
$folder=$msql->f("folder");

$url="page/".$folder."/";
$href="../".$folder."/";

?> 
  <tr class=list>
    <td  width="50" align="center"><?php echo $id; ?></td>
   
      <td  width="130" height="30"> 
       <?php echo $groupname; ?>
      
 
      </td>
      <td width="100"  ><?php echo $folder; ?> </td>
      <td  ><a href='<?php echo $href; ?>' target='_blank'><?php echo $url; ?></a> </td>
      <td width="50"  ><img id='pr_<?php echo $folder; ?>' class='pdv_enter' src="images/edit.png"  style="cursor:pointer"  border="0" /> </td>
      <td width="50"  >
	  <?php
	  if($moveable=="1"){
	  ?>
	  	  <img src="images/delete.png"  style="cursor:pointer"   onclick="self.location='group.php?step=del&id=<?php echo $id; ?>'" /> 
	<?php
	  }else{
	  	echo "---";
	  }
	  ?>
	  </td>
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
