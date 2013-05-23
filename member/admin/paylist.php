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
$memo=$_REQUEST["memo"];
$ip=$_SERVER["REMOTE_ADDR"];
$id=$_REQUEST["id"];
$fpnum=$_REQUEST["fpnum"];
$memo=$_REQUEST["memo"];

if($memberid==""){
	echo "ERROR:NO MEMBERID";
	exit;
}

if($step=="modify"){
NeedAuth(67);
$msql->query("update {P}_member_pay set fpnum='$fpnum',memo='$memo' where id='$id' and memberid='$memberid'");
}

//查询

$scl=" memberid='$memberid' ";
$totalnums=TblCount("_member_pay","id",$scl);

$pages = new pages;		
$pages->setvar(array("memberid" => $memberid));

$pages->set(20,$totalnums);		                          
	
$pagelimit=$pages->limit();	  
?>
<div class="formzone">
<div class="namezone" ><?php echo $strAccList; ?> - [ <?php echo Memberid2User($memberid); ?> ]</div>

<div class="tablezone">

  <table width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
    <tr> 
      <td width="80" height="28"  class="innerbiaoti"><span class="title"><span class="con"><?php echo $strAccAddTime; ?></span></span></td>
      <td width="65"  class="innerbiaoti"><span class="title"><?php echo $strAccAddMoney6; ?></span></td>
      <td  class="innerbiaoti" width="75"><span class="title"><?php echo $strAccAddMoney2; ?></span></td>
      <td  class="innerbiaoti" height="28"><span class="title"><?php echo $strAccAddMoney3; ?></span></td>
      <td  class="innerbiaoti" width="80"><span class="title"><?php echo $strAccAddMoney4; ?></span></td>
      <td  class="innerbiaoti" width="80"><span class="title"><?php echo $strAccAddMoney5; ?></span></td>
      <td  class="innerbiaoti" width="80"><span class="title"><?php echo $strLogName; ?></span></td>
      <td  class="innerbiaoti" width="39"><span class="title"><?php echo $strModify; ?></span></td>
      </tr>
<?php
$msql->query("select * from {P}_member_pay where $scl order by id desc limit $pagelimit");

while($msql->next_record()){
$id=$msql->f('id');
$oof=$msql->f('oof');
$method=$msql->f('method');
$type=$msql->f('type');
$payhb=$msql->f('payhb');
$addtime=$msql->f('addtime');
$fpnum=$msql->f('fpnum');
$memo=$msql->f('memo');
$logname=$msql->f('logname');
$addtime=date("Y-n-j",$addtime);


?>
<form method=get action="paylist.php">
<tr class="list"> 
        <td width="80"  > <?php echo $addtime; ?> </td>
        <td width="65"><?php echo $type; ?></td>
        <td width="75"><?php echo $method; ?></td>
        <td   height="26"><?php echo $oof; ?></td>
        <td width="80"><input name="memo" type="text" class="input" id="memo" value="<?php echo $memo; ?>" size="20" />
          <input type="hidden" name="memberid" value="<? echo $memberid; ?>" />
          <input type="hidden" name="step" value="modify" />
          <input type="hidden" name="id" value="<? echo $id; ?>" />          </td>
        <td width="80"><input type="text" name="fpnum" class="input" size="15" value="<?php echo $fpnum; ?>" /></td>
        <td width="80"><?php echo $logname; ?></td>
        <td width="39"><input name="imageField" type="image" src="images/modi.png" width="24" height="24" border="0" ></td>
        </tr>
	  </form>
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
