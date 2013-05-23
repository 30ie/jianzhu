<?php
define("ROOTPATH", "../../");
include(ROOTPATH."includes/admin.inc.php");
include(ROOTPATH."includes/pages.inc.php");
include("language/".$sLan.".php");
NeedAuth(301);
$step=$_REQUEST["step"];
$groupid=$_REQUEST["groupid"];
$page=$_REQUEST["page"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link  href="css/style.css" type="text/css" rel="stylesheet">
<title><?php echo $strAdminTitle; ?></title>
<script type="text/javascript" src="../../base/js/base.js"></script>
<script type="text/javascript" src="../../base/js/blockui.js"></script>
<script type="text/javascript" src="js/page.js"></script>


</head>
<body>

<?php

if($step=="del"){
	$id=$_REQUEST["id"];
	$msql->query("select * from {P}_page where  id='$id'");
	if($msql->next_record()){
		$groupid=$msql->f('groupid');
		$pagefolder=$msql->f('pagefolder');
	}
	
	//删除独立文件
	if($pagefolder!="" && strlen($pagefolder)>0){
		//获取组目录名
		$fsql->query("select folder from {P}_page_group where id='$groupid'");
		if($fsql->next_record()){
			$folder=$fsql->f('folder');
		}
		//删除文件及插件记录
		@unlink("../".$folder."/".$pagefolder.".php");
		$oldpagename=$folder."_".$pagefolder;
		$msql->query("delete from {P}_base_pageset where coltype='page' and pagename='$oldpagename'");
		$msql->query("delete from {P}_base_plus where plustype='page' and pluslocat='$oldpagename'");
		$msql->query("delete from {P}_base_plusplan where plustype='page' and pluslocat='$oldpagename'");
	}
	
	//删除网页记录
	$msql->query("delete from {P}_page where  id='$id'");
}



$scl=" id!='0' ";
if($_REQUEST["groupid"]!=""){
	$scl.=" and groupid='".$_REQUEST["groupid"]."' "; 
}

$totalnums=TblCount("_page","id",$scl);

$pages = new pages;		
$pages->setvar(array("groupid" => $groupid));

$pages->set(10,$totalnums);		                          
	
$pagelimit=$pages->limit();	  

?>

<div class="formzone">
<div class="tablecapzone">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td width="200"><form  name="selgroup" method="get" action="" >
         <select name="pp" onchange="self.location=this.options[this.selectedIndex].value" >
         
          <?php
				
			$msql->query("select * from {P}_page_group");
			while($msql->next_record()){
				$lgroupid=$msql->f('id');
				$groupname=$msql->f('groupname');
					
				if($groupid==$lgroupid){
					echo "<option value='page.php?groupid=".$lgroupid."' selected>".$strGroupSel.$groupname."</option>";
				}else{
					echo "<option value='page.php?groupid=".$lgroupid."'>".$strGroupSel.$groupname."</option>";
				}
						
			}
					
				
		 ?>
        </select>
    </form>
	</td>
      <td><div class="addsub" onClick="window.location='page_add.php?groupid=<?php echo $groupid; ?>'" ><span style="padding-left:52px;"><?php echo $strPageAdd; ?></span></div>
</td>
    </tr>
  </table>
  </div>
<div class="tablezone">
 <table width="100%" border="0" cellspacing="0" cellpadding="5" >
  <tr> 
    <td class="innerbiaoti" align="center" height="28" width="50" ><?php echo $strNumber; ?></td>
    <td class="innerbiaoti" width="39" ><?php echo $strXuhao; ?></td>
    <td width="50" class="innerbiaoti" ><?php echo $strPagePicSrc1; ?></td>
    <td class="innerbiaoti" width="70" ><?php echo $strGroupNow; ?></td>
    <td width="120" height="28" class="innerbiaoti" ><?php echo $strPageTitle; ?></td>
    <td class="innerbiaoti" ><span class="biaoti"><?php echo $strPageFolder; ?></span></td>
    <td class="innerbiaoti" width="39" ><?php echo $strModify; ?></td>
    <td width="39" class="innerbiaoti" ><span class="biaoti"><?php echo $strGroupEdit; ?></span></td>
    <td class="innerbiaoti" height="28" width="39" ><?php echo $strDelete; ?></td>
  </tr>
  <?php 
	$msql -> query ("select * from {P}_page where $scl order by xuhao limit $pagelimit");
	while ($msql -> next_record ()) {
		$id = $msql -> f ('id');
		$title = $msql -> f ('title');
		$xuhao = $msql -> f ('xuhao');
		$groupid = $msql -> f ('groupid');
		$pagefolder = $msql -> f ('pagefolder');
		$src = $msql -> f ('src');
	
		$fsql->query("select * from {P}_page_group where id='$groupid'");
		if($fsql->next_record()){
			$groupname=$fsql->f('groupname');
			$folder=$fsql->f('folder');
		}
		
		if($pagefolder!="" && strlen($pagefolder)>0){
			$browseurl=$folder."/".$pagefolder.".php";
			$pvdpath=$folder."/".$pagefolder.".php";
		}else{
			$browseurl=$folder."/?".$id.".html";
			$pvdpath=$folder;
		}
		
		

?> 
  <tr class="list"> 
    <td  align="center" height="28" width="50" > <?php echo $id; ?> </td>
    <td width="39" ><?php echo $xuhao; ?> </td>
    <td width="50" >
	<?php
if($src==""){
echo "<img id='preview_".$id."' class='preview' src='images/noimage.gif' >";
}else{
echo "<img id='preview_".$id."' class='preview' src='images/image.gif' >";
}
?> <input type="hidden" id="previewsrc_<?php echo $id; ?>"  value="<?php echo $src; ?>">

	</td>
    <td width="70" ><?php echo $groupname; ?> </td>
    <td width="120" ><?php echo $title; ?></td>
    <td ><a href="../<?php echo $browseurl; ?>" target="_blank">page/<?php echo $browseurl; ?></a></td>
    <td width="39" ><img src="images/modi.png" width="24" height="24"  style="cursor:pointer" onclick="window.location='page_edit.php?id=<?php echo $id; ?>&groupid=<?php echo $groupid; ?>'" /> </td>
    <td width="39"  ><img id='pr_<?php echo $pvdpath; ?>' class='pdv_enter' src="images/edit.png"  style="cursor:pointer"  border="0" /></td>
    <td height="28" width="39" > <img src="images/delete.png"  style="cursor:pointer" onClick="window.location='page.php?step=del&id=<?php echo $id; ?>&groupid=<?php echo $_REQUEST["groupid"]; ?>&page=<?php echo $page; ?>'"> 
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
</div>

</body>
</html>
