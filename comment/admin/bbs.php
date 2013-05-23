<?php
define("ROOTPATH", "../../");
include(ROOTPATH."includes/admin.inc.php");
include(ROOTPATH."includes/pages.inc.php");
include("language/".$sLan.".php");
NeedAuth(132);

$page=$_REQUEST["page"];
$step=$_REQUEST["step"];
$id=$_REQUEST["id"];
$title=$_REQUEST["title"];
$xuhao=$_REQUEST["xuhao"];
$tj=$_REQUEST["tj"];
$iffb=$_REQUEST["iffb"];
$key=$_REQUEST["key"];
$secure=$_REQUEST["secure"];
$showtj=$_REQUEST["showtj"];
$showfb=$_REQUEST["showfb"];
$showpid=$_REQUEST["showpid"];
$shownum=$_REQUEST["shownum"];
$sc=$_REQUEST["sc"];
$ord=$_REQUEST["ord"];
$showcatid=$_REQUEST["showcatid"];


if(!isset($shownum) || $shownum<10){
$shownum=10;
}

if(!isset($showpid)){
$showpid="0";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link  href="css/style.css" type="text/css" rel="stylesheet">
<title><?php echo $strAdminTitle; ?></title>

 <SCRIPT>


function Dpop(url,w,h){
	res = showModalDialog(url, null, 'dialogWidth: '+w+'px; dialogHeight: '+h+'px; center: yes; resizable: no; scroll: no; status: no;');
 	if(res=="ok"){window.location.reload();}
 
}
function ordsc(nn,sc){
if(nn!='<?php echo "$ord"; ?>'){
	window.location='bbs.php?page=<? echo $page; ?>&sc=<? echo $sc; ?>&showpid=<? echo $showpid; ?>&showtj=<? echo $showtj; ?>&showfb=<? echo $showfb; ?>&shownum=<? echo $shownum; ?>&ord='+nn;
}else{
	if(sc=='asc' || sc==''){
	window.location='bbs.php?page=<? echo "$page"; ?>&sc=desc&showpid=<? echo $showpid; ?>&showtj=<? echo $showtj; ?>&showfb=<? echo $showfb; ?>&shownum=<? echo $shownum; ?>&ord='+nn;
	}else{
	window.location='bbs.php?page=<? echo "$page"; ?>&sc=asc&showpid=<? echo $showpid; ?>&showtj=<? echo $showtj; ?>&showfb=<? echo $showfb; ?>&shownum=<? echo $shownum; ?>&ord='+nn;
	}

}


}

function SelAll(theForm){
		for ( i = 0 ; i < theForm.elements.length ; i ++ )
		{
			if ( theForm.elements[i].type == "checkbox" && theForm.elements[i].name != "SELALL" )
			{
				theForm.elements[i].checked = ! theForm.elements[i].checked ;
			}
		}
}

</SCRIPT>
</head>

<body>
<?php
if($step=="fball"){
	$dall=$_POST["dall"];
	$nums=sizeof($dall);
	for($i=0;$i<$nums;$i++){
		$ids=$dall[$i];
		$msql->query("update {P}_comment set iffb='1' where id='$ids'");
	}

}

if($step=="notfball"){
	$dall=$_POST["dall"];
	$nums=sizeof($dall);
	for($i=0;$i<$nums;$i++){
		$ids=$dall[$i];
		$msql->query("update {P}_comment set iffb='0' where id='$ids'");
	}

}

if($step=="tjall"){
	$dall=$_POST["dall"];
	$nums=sizeof($dall);
	for($i=0;$i<$nums;$i++){
		$ids=$dall[$i];
		$msql->query("update {P}_comment set tuijian='1' where id='$ids'");
	}

}
if($step=="nottjall"){
	$dall=$_POST["dall"];
	$nums=sizeof($dall);
	for($i=0;$i<$nums;$i++){
		$ids=$dall[$i];
		$msql->query("update {P}_comment set tuijian='0' where id='$ids'");
	}

}



if($step=="delall"){
	$dall=$_POST["dall"];
	$nums=sizeof($dall);
	for($i=0;$i<$nums;$i++){
		$ids=$dall[$i];
		
			$msql->query("select * from {P}_comment where id='$ids'");
			if($msql->next_record()){
				$picsrc=$msql->f('picsrc');
				if(file_exists(ROOTPATH.$picsrc) && strlen($picsrc)>10 && !strstr($picsrc,"../")){
					unlink(ROOTPATH.$picsrc);
				}
			
			}

	
		$msql->query("delete from {P}_comment where id='$ids'");

		if($ids!="0"){
		
			$msql->query("select * from {P}_comment where pid='$ids'");
			while($msql->next_record()){
				$picsrc=$msql->f('picsrc');
				if(file_exists(ROOTPATH.$picsrc) && strlen($picsrc)>10 && !strstr($picsrc,"../")){
					unlink(ROOTPATH.$picsrc);
				}
			
			}
		
			$msql->query("delete from {P}_comment where pid='$ids'");
		}

	}
}


?>


<?php

if(!isset($ord) || $ord==""){
$ord="uptime";
}
if(!isset($sc) || $sc==""){
$sc="desc";
}

$scl="  id!='0' ";

if($key!=""){
$scl.=" and (title regexp '$key' or body regexp '$key') ";
}

if($showtj!="" && $showtj!="all"){
$scl.=" and tuijian='$showtj' ";
}

if($showcatid!="" && $showcatid!="all"){
$scl.=" and catid='$showcatid' ";
}




if($showfb!="" && $showfb!="all"){
$scl.=" and iffb='$showfb' ";
}

if($showpid!="" && $showpid!="all"){

	if($showpid=="0"){
		$scl.=" and pid='0' ";
	}else{
		$scl.=" and pid!='0' ";		
	}

}


$totalnums=TblCount("_comment","id",$scl);

$pages = new pages;		
$pages->setvar(array("shownum" => $shownum,"showpid" => $showpid,"showcatid" => $showcatid,"sc" => $sc,"ord" => $ord,"showtj" => $showtj,"showfb" => $showfb,"key" => $key));

$pages->set($shownum,$totalnums);		                          
	
$pagelimit=$pages->limit();	  


?> 
<div class="searchzone">
<table width="100%" border="0" cellspacing="0" cellpadding="2" align="center" height="28">
  <tr> 
    <form method="get" action="bbs.php" >
                  
      <td class=title height="30"> 
        <select name="showcatid">
          <option value='all'><?php echo $strSelCat; ?></option>
          <?php
				$fsql -> query ("select * from {P}_comment_cat order by xuhao");
				while ($fsql -> next_record ()) {
					$lcatid = $fsql -> f ("catid");
					$cat = $fsql -> f ("cat");
					if($showcatid==$lcatid){
						echo "<option value='".$lcatid."' selected>".$cat."</option>";
					}else{
						echo "<option value='".$lcatid."'>".$cat."</option>";
					}
				}
		 ?>
        </select>
        <select name="showtj">
          <option value="all" ><?php echo $strSelTj; ?></option>
          <option value="1"  <?php echo seld($showtj,"1"); ?>><?php echo $strSelTjYes; ?></option>
          <option value="0" <?php echo seld($showtj,"0"); ?>><?php echo $strSelTjNo; ?></option>
        </select>
<select name="showfb">
          <option value="all" ><?php echo $strSelFb; ?></option>
          <option value="1"  <?php echo seld($showfb,"1"); ?>><?php echo $strSelFbYes; ?></option>
          <option value="0" <?php echo seld($showfb,"0"); ?>><?php echo $strSelFbNo; ?></option>
        </select>
<select name="shownum">

          <option value="10"  <?php echo seld($shownum,"10"); ?>><?php echo $strSelNum10; ?></option>
          <option value="20" <?php echo seld($shownum,"20"); ?>><?php echo $strSelNum20; ?></option>
          <option value="30" <?php echo seld($shownum,"30"); ?>><?php echo $strSelNum30; ?></option>
          <option value="50" <?php echo seld($shownum,"50"); ?>><?php echo $strSelNum50; ?></option>
        </select>
<select name="showpid">
  <option value="all" ><?php echo $strCommentShowAll; ?></option>
  <option value="0"  <?php echo seld($showpid,"0"); ?>><?php echo $strCommentShowMain; ?></option>
  <option value="1" <?php echo seld($showpid,"1"); ?>><?php echo $strCommentShowBack; ?></option>
</select>
<input type="text" name="key" size="23" class=input value="<?php echo $key; ?>" />
          
        <input type="submit" name="Submit" value="<?php echo $strSearchTitle; ?>" class=button>
        </td>
      </form>
             
    <td class=title colspan="2" align="right">&nbsp; </td>
  </tr>
</table>

</div>
<form name="delfm" method="post" action="bbs.php">
<div class="listzone">
<table width="100%" border="0" cellspacing="0" cellpadding="3" align="center">
  <tr class=list> 
    <td width="30"  class="biaoti"  style="cursor:pointer" onClick="ordsc('id','<?php echo $sc; ?>')"><?php echo $strSel; ?></td>
    <td width="50"  class="biaoti"  style="cursor:pointer" onClick="ordsc('id','<?php echo $sc; ?>')"><?php echo $strBianhao; ?><?php OrdSc($ord,"id",$sc); ?></td>
    <td height="28" width="60"  class="biaoti"  ><?php echo $strCommentType; ?></td>
    <td height="28" class="biaoti" style="cursor:pointer" onClick="ordsc('title','<?php echo $sc; ?>')"><?php echo $strTitle; ?><?php OrdSc($ord,"title",$sc); ?></td>
    <td height="28" width="139"  class="biaoti"  style="cursor:pointer" onClick="ordsc('uptime','<?php echo $sc; ?>')"><?php echo $strUptime; ?><?php OrdSc($ord,"uptime",$sc); ?></td>
    <td height="28" width="50"  class="biaoti"  style="cursor:pointer" onClick="ordsc('cl','<?php echo $sc; ?>')"><?php echo $strClick; ?><?php OrdSc($ord,"cl",$sc); ?></td>
    <td height="28" width="50"  class="biaoti"  ><?php echo $strCommentType2; ?></td>
    <td height="28" width="39"  class="biaoti"><?php echo $strFabu; ?></td>
    <td height="28" width="39"  class="biaoti"><?php echo $strTuiJian; ?> 
    </td>
    <td height="28" width="39"  class="biaoti"> 
      <?php echo $strLook; ?>
    </td>
  </tr>
  
    <?php 


$msql->query("select * from {P}_comment where $scl  order by $ord $sc  limit $pagelimit");


while($msql->next_record()){
$id=$msql->f('id');
$pid=$msql->f('pid');
$title=$msql->f('title');
$body=$msql->f('body');
$xuhao=$msql->f('xuhao');
$cl=$msql->f('cl');
$tj=$msql->f('tuijian');
$iffb=$msql->f('iffb');
$uptime=$msql->f('uptime');

$uptime=date("Y-m-d H:i",$uptime);

if($pid==0){
$look="../html/?".$id.".html";
$type=$strCommentType1;
}else{
$look="../html/?".$pid.".html";
$type=$strCommentType2;
}

$fsql->query("select count(id) from {P}_comment where pid='$id'");
	if($fsql->next_record()){
		$count=$fsql->f('count(id)');
	}

?> 
    <tr class=list> 
      <td width="30" height="26"> 
        <input type="checkbox" name="dall[]" value="<?php echo $id; ?>">
      </td>
      <td width="50" height="26"> <?php echo $id; ?> </td>
      <td width="60"><?php echo $type; ?></td>
      <td><?php echo $title; ?>
      </td>
      <td width="139"><?php echo $uptime; ?></td>
      <td width="50"><?php echo $cl; ?></td>
      <td width="50"><?php echo $count; ?></td>
      <td width="39"> <?php
ShowYN($iffb);
?></td>
      <td width="39"> <?php
ShowYN($tj);
?> </td>
      <td width="39"> 
        <img src="images/look.png" style="cursor:pointer" onClick="window.open('<?php echo $look; ?>','_blank')"> 
      </td>
    </tr>
    <?php
}
?> 
   
</table>
</div>

<div class="piliang"> 
<input type="checkbox" name="SELALL" value="1" onClick="SelAll(this.form)" />
        <?php echo $strSelAll; ?>&nbsp; 
        <input type="radio" name="step" value="delall" />
        <?php echo $strDelete ?> 
        <input type="radio" name="step" value="fball" checked />
        <?php echo $strFb ?> 
        <input type="radio" name="step" value="notfball" />
        <?php echo $strNotFb ?> 
        <input type="radio" name="step" value="tjall" />
        <?php echo $strTj ?> 
        <input type="radio" name="step" value="nottjall" />
        <?php echo $strNotTj ?> &nbsp;&nbsp;<a style="cursor:pointer;font-weight:bold" onClick="delfm.submit()">[ 
        <?php echo $strSubmit; ?> ]</a> 
        <input type="hidden" name="page" size="3" value="<?php echo $page; ?>" />
        <input type="hidden" name="ord" size="3" value="<?php echo $ord; ?>" />
        <input type="hidden" name="sc" size="3" value="<?php echo $sc; ?>" />
        <input type="hidden" name="key" size="3" value="<?php echo $key; ?>" />
        <input type="hidden" name="showtj" value="<?php echo $showtj; ?>" />
        <input type="hidden" name="showfb" value="<?php echo $showfb; ?>" />
        <input type="hidden" name="shownum" value="<?php echo $shownum; ?>" />
        <input type="hidden" name="showpid" value="<?php echo $showpid; ?>" />
</div>
</form>


<?php
$pagesinfo=$pages->ShowNow();
?>
<div id="showpages">
	  <div id="pagesinfo"><?php echo $strPagesTotalStart.$totalnums.$strPagesTotalEnd; ?> <?php echo $strPagesMeiye.$pagesinfo["shownum"].$strPagesTotalEnd; ?> <?php echo $strPagesYeci; ?> <?php echo $pagesinfo["now"]."/".$pagesinfo["total"]; ?></div>
	  <div id="pages"><?php echo $pages->output(1); ?></div>
</div>
<br />
</body>
</html>
