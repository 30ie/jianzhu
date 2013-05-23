<?php
define("ROOTPATH", "../../");
include(ROOTPATH."includes/admin.inc.php");
include(ROOTPATH."includes/pages.inc.php");
include("language/".$sLan.".php");
NeedAuth(42);

$page=$_REQUEST["page"];
$sc=$_REQUEST["sc"];
$ord=$_REQUEST["ord"];
$key=$_REQUEST["key"];
$shownum=$_REQUEST["shownum"];
$showcatid=$_REQUEST["showcatid"];
$showtypeid=$_REQUEST["showtypeid"];

if(!isset($shownum) || $shownum<10){
$shownum=10;
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link  href="css/style.css" type="text/css" rel="stylesheet">
<title><?php echo $strAdminTitle; ?></title>
<script type="text/javascript" src="../../base/js/base.js"></script>
<script type="text/javascript" src="js/webtemp.js"></script>
<script type="text/javascript" src="../../base/js/blockui.js"></script>
<SCRIPT>
function ordsc(nn,sc){
if(nn!='<?php echo $ord; ?>'){
	window.location='spool.php?page=<? echo $page; ?>&sc=<? echo $sc; ?>&shownum=<? echo $shownum; ?>&ord='+nn;
}else{
	if(sc=='asc' || sc==''){
	window.location='spool.php?page=<? echo $page; ?>&sc=desc&shownum=<? echo $shownum; ?>&ord='+nn;
	}else{
	window.location='spool.php?page=<? echo $page; ?>&sc=asc&shownum=<? echo $shownum; ?>&ord='+nn;
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
            <td> <form name="search" action="spool.php" method="get" >
		<select name="showcatid">
          <option value='all'><?php echo $strSelHYCat; ?></option>
          <?php
				$fsql -> query ("select * from {P}_webmall_tempcat order by xuhao");
				while ($fsql -> next_record ()) {
					$catid = $fsql -> f ("catid");
					$cat = $fsql -> f ("cat");
					if($showcatid==$catid){
						echo "<option value='".$catid."' selected>".$cat."</option>";
					}else{
						echo "<option value='".$catid."'>".$cat."</option>";
					}
				}
		 ?>
        </select>
		
		<select name="showtypeid">
          <option value='all'><?php echo $strSelYYCat; ?></option>
          <?php
				$fsql -> query ("select * from {P}_webmall_temptype order by xuhao");
				while ($fsql -> next_record ()) {
					$catid = $fsql -> f ("catid");
					$cat = $fsql -> f ("cat");
					if($showtypeid==$catid){
						echo "<option value='".$catid."' selected>".$cat."</option>";
					}else{
						echo "<option value='".$catid."'>".$cat."</option>";
					}
				}
		 ?>
        </select>
              <select name="shownum">
                <option value="10"  <?php echo seld($shownum,"10"); ?>><?php echo $strSelNum10; ?></option>
                <option value="20" <?php echo seld($shownum,"20"); ?>><?php echo $strSelNum20; ?></option>
                <option value="30" <?php echo seld($shownum,"30"); ?>><?php echo $strSelNum30; ?></option>
                <option value="50" <?php echo seld($shownum,"50"); ?>><?php echo $strSelNum50; ?></option>
              </select>
              <input type="text" name="key" size="20"  class="input"  value="<?php echo $key; ?>" />
              <input type="submit" name="Submit" value="<?php echo $strSearchTitle; ?>" class="button" />
            
            </form></td>
          <td width="130" align="right"><input name="vvv" type="button" id="getspool" value="同步网站产品" class="button" /></td>
          </tr>
        </table>
    </td>
   
  
     
   
  </tr> 
</table>

</div>
<?php
if(!isset($ord) || $ord==""){
$ord="id";
}
if(!isset($sc) || $sc==""){
$sc="desc";
}

$scl=" id!='0' ";

if($showcatid!="" && $showcatid!="all"){
	$scl.=" and catid='$showcatid' ";
}

if($showtypeid!="" && $showtypeid!="all"){
	$scl.=" and typeid='$showtypeid' ";
}


if($key!=""){
$scl.=" and (name regexp '$key' or designer regexp '$key'  or spool regexp '$key' or modules regexp '$key')";
}

$msql->query("select count(id) from {P}_webmall_spool where $scl");
if($msql->next_record()){
$totalnums=$msql->f('count(id)');
}


$pages = new pages;		
$pages->setvar(array(
"key" => $key,
"shownum" => $shownum,
"showcatid" => $showcatid,
"showtypeid" => $showtypeid,
"ord" => $ord,
"sc" => $sc
));

$pages->set($shownum,$totalnums);		                          
$pagelimit=$pages->limit();	


//缩图来源选项判断
$msql->query("select * from {P}_webmall_config");
while($msql->next_record()){
$variable=$msql->f('variable');
$value=$msql->f('value');
$WEBMALLCONF[$variable]=$value;
}
$AgentApi=$WEBMALLCONF["AgentApi"];
$AgentSharePic=$WEBMALLCONF["AgentSharePic"];
if($AgentSharePic=="1"){
	$suourl="http://".$AgentApi."/webtry/temppic/";
}else{
	$suourl="../temppic/";
}

?>
<div class="listzone">
<table width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
          <tr> 
    <td width="45"  class="biaoti" style="cursor:pointer" onclick="ordsc('id','<?php echo $sc; ?>')">ID <?php OrdSc($ord,"id",$sc); ?></td>
            <td width="65"  class="biaoti" style="cursor:pointer" onclick="ordsc('spool','<?php echo $sc; ?>')"><?php echo $strWebSpoolList3; ?>
                <?php OrdSc($ord,"spool",$sc); ?></td>
            <td width="90"  class="biaoti" ><?php echo $strWebSpoolList10; ?></td>
            <td height="28"  class="biaoti" ><?php echo $strWebSpoolList11; ?></td>
            <td  width="68" height="28"  class="biaoti" style="cursor:pointer" onClick="ordsc('price','<?php echo $sc; ?>')">产品价格<?php OrdSc($ord,"price",$sc); ?></td>
           <td width="60"  class="biaoti" >代理价I</td>
            <td width="60"  class="biaoti" >代理价II</td>
            <td width="39"  class="biaoti" align="center" >定价</td>
		    <td width="39"  class="biaoti" align="center" >演示</td>
		    <td width="39"  class="biaoti" align="center" >详情</td>
		    <td height="28" width="39"  class="biaoti" align="center" >删除</td>
          </tr>
          <?php 


$msql->query("select * from {P}_webmall_spool where $scl order by $ord $sc limit $pagelimit");

while($msql->next_record()){
$id=$msql->f('id');
$name=$msql->f('name');
$spool=$msql->f('spool');
$typeid=$msql->f('typeid');
$catid=$msql->f('catid');
$designer=$msql->f('designer');
$modules=$msql->f('modules');
$price=$msql->f('price');
$price1=$msql->f('price1');
$price2=$msql->f('price2');
$sellnums=$msql->f('sellnums');
$demourl=$msql->f('demourl');
$dtime=$msql->f('dtime');
$dtime=date("y-n-j",$dtime);


$fsql->query("select cat from {P}_webmall_tempcat where catid='$catid'");
if($fsql->next_record()){
	$tempcat=$fsql->f('cat');
}

$fsql->query("select cat from {P}_webmall_temptype where catid='$typeid'");
if($fsql->next_record()){
	$temptype=$fsql->f('cat');
}



?> 
          <tr class="list" id="tr_<?php echo $id; ?>"> 
            <td   width="45" valign="top"><?php echo $id; ?> </td>
			 <td   width="65" valign="top"><?php echo $spool; ?> </td>
			 <td width="90" valign="top"><a href="../detail.php?id=<?php echo $id; ?>" target="_blank"><img src="<?php echo $suourl.$spool; ?>_s.jpg" width="60" style="border:0px #ddd solid" /></a></td>
			 <td valign="top"><span class="biaoti"><?php echo $strWebSpoolList2; ?></span>：<?php echo $name; ?><br />		     
		       <span class="biaoti">		       <?php echo $strWebSpoolList5; ?></span>：<?php echo $temptype; ?><br />
	           <span class="biaoti"><?php echo $strWebSpoolList6; ?></span>：<?php echo $tempcat; ?><br />	        </td>
            <td width="68" valign="top" ><?php echo $price; ?><br />              </td>
            <td   width="60" valign="top"><?php echo $price1; ?> </td>
            <td   width="60" valign="top"><?php echo $price2; ?> </td>
            <td  width="39" align="center" valign="top" ><a href="tempmodi.php?id=<?php echo $id; ?>"><img src="images/modi.png"  border="0" /></a> </td>
            <td  width="39" align="center" valign="top" ><a href="<?php echo $demourl; ?>" target="_blank"><img src="images/edit.png"  border="0" /></a> </td>
            <td  width="39" align="center" valign="top" ><a href="../detail.php?id=<?php echo $id; ?>" target="_blank"><img src="images/look.png"  border="0"  /></a> </td>
            <td  width="39" align="center" valign="top" ><img id="tempdel_<?php echo $id; ?>" src="images/delete.png"  border="0" class="tempdel"  /></td>
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
