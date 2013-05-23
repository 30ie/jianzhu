<?php
define("ROOTPATH", "../../");
include(ROOTPATH."includes/admin.inc.php");
include(ROOTPATH."includes/pages.inc.php");
include("language/".$sLan.".php");
include("func/member.inc.php");
NeedAuth(52);

$step=$_REQUEST["step"];
$page=$_REQUEST["page"];
$sc=$_REQUEST["sc"];
$ord=$_REQUEST["ord"];
$membertypeid=$_REQUEST["membertypeid"];
$memberid=$_REQUEST["memberid"];
$key=$_REQUEST["key"];
$user=$_REQUEST["user"];
$shownum=$_REQUEST["shownum"];
$showcheck=$_REQUEST["showcheck"];
$showrz=$_REQUEST["showrz"];
$newtypeid=$_REQUEST["newtypeid"];
$searchmodle=$_REQUEST["searchmodle"];

if(!isset($shownum) || $shownum<10){
$shownum=10;
}

if(!isset($searchmodle) || $searchmodle==""){
$searchmodle="common";
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link  href="css/style.css" type="text/css" rel="stylesheet">
<script type="text/javascript" src="../../base/js/base.js"></script>
<script type="text/javascript" src="../../base/js/form.js"></script>
<script type="text/javascript" src="../../base/js/blockui.js"></script>
<script type="text/javascript" src="js/member.js"></script>

<title><?php echo $strAdminTitle; ?></title>
<SCRIPT>
function ordsc(nn,sc){
if(nn!='<?php echo $ord; ?>'){
	window.location='member_common.php?page=<? echo $page; ?>&sc=<? echo $sc; ?>&shownum=<? echo $shownum; ?>&showcheck=<? echo $showcheck; ?>&showrz=<? echo $showrz; ?>&membertypeid=<? echo $membertypeid; ?>&searchmodle=<? echo $searchmodle; ?>&ord='+nn;
}else{
	if(sc=='asc' || sc==''){
	window.location='member_common.php?page=<? echo $page; ?>&shownum=<? echo $shownum; ?>&showcheck=<? echo $showcheck; ?>&showrz=<? echo $showrz; ?>&sc=desc&membertypeid=<? echo $membertypeid; ?>&searchmodle=<? echo $searchmodle; ?>&ord='+nn;
	}else{
	window.location='member_common.php?page=<? echo $page; ?>&shownum=<? echo $shownum; ?>&showcheck=<? echo $showcheck; ?>&showrz=<? echo $showrz; ?>&sc=asc&membertypeid=<? echo $membertypeid; ?>&searchmodle=<? echo $searchmodle; ?>&ord='+nn;
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

function checkform(theform){
  if(theform.newuser.value=='<?php echo $strAddMemberInput; ?>' || theform.newuser.value=='')
  {
    alert("<?php echo $strAddMemberInput; ?>");
	theform.newuser.focus();
    return false;
  }  

  return true;
}  
</script>
</head>

<body>
<?php


if($step=="delall"){
NeedAuth(60);
	$dall=$_POST["dall"];
	$nums=sizeof($dall);
	for($i=0;$i<$nums;$i++){
		$delmemberid=$dall[$i];
		$msql->query("delete from {P}_member_rights where memberid='$delmemberid'");
		$msql->query("delete from {P}_member_nums where memberid='$delmemberid'");
		$msql->query("delete from {P}_member_fav where memberid='$delmemberid'");
		$msql->query("delete from {P}_member_pay where memberid='$delmemberid'");
		$msql->query("delete from {P}_member_buylist where memberid='$delmemberid'");
		$msql->query("delete from {P}_member where memberid='$delmemberid'");

	}
}


if($step=="rzall"){
NeedAuth(59);
	$dall=$_POST["dall"];
	$nums=sizeof($dall);
	for($i=0;$i<$nums;$i++){
		$ids=$dall[$i];
		$msql->query("update {P}_member set rz='1' where memberid='$ids'");
	}
}

if($step=="unrzall"){
NeedAuth(59);
	$dall=$_POST["dall"];
	$nums=sizeof($dall);
	for($i=0;$i<$nums;$i++){
		$ids=$dall[$i];
		$msql->query("update {P}_member set rz='0' where memberid='$ids'");
	}
}


if($step=="chtypeall"){
NeedAuth(64);
	$dall=$_POST["dall"];
	$nums=sizeof($dall);
	for($i=0;$i<$nums;$i++){
		$ids=$dall[$i];

		$msql->query("select membergroupid from {P}_member_type where membertypeid='$newtypeid'");
		if($msql->next_record()){
			$newmembergroupid=$msql->f('membergroupid');
		}

		$msql->query("update {P}_member set membertypeid='$newtypeid',membergroupid='$newmembergroupid' where memberid='$ids'");
		Default2Member($ids,$newtypeid);

	}
}

if($step=="addmember"){
		$newuser=$_REQUEST["newuser"];
		
		if(strlen($newuser)<5 || strlen($newuser)>20){
			err($strAddMemberNTC1,"member_common.php?membertypeid=".$membertypeid,"");
			exit;
		}
		
		if (!eregi("^[0-9a-z]{1,20}$",$newuser)) { 
			err($strAddMemberNTC2,"member_common.php?membertypeid=".$membertypeid,"");
			exit;
		} 
		
		$msql->query("select membergroupid from {P}_member_type where membertypeid='$membertypeid'");
		if($msql->next_record()){
			$nowmembergroupid=$msql->f('membergroupid');
		}

		$msql->query("select * from {P}_member where user='$newuser'");
		if($msql->next_record()){
			err($strAddMemberNTC3,"member_common.php?membertypeid=".$membertypeid,"");
			exit;
		}
		$newpass="";
		for($i=1;$i<=9;$i++){
			$zz=rand(0,9);
			$newpass.=$zz;
		}
		$newpass=md5($newpass);
		$now=time();
		$ip=$_SERVER["REMOTE_ADDR"];
		
		
		$msql->query("insert into {P}_member set

				   membertypeid='$membertypeid',
				   membergroupid='$nowmembergroupid',
				   user='$newuser',
				   password='$newpass',
				   name='$newuser',
				   pname='$newuser',
				   sex='',
				   birthday='0',
				   zoneid='0',
				   addr='',
				   tel='',
				   mov='',
				   postcode='',
				   email='',
				   url='',
				   passtype='',
				   passcode='',
				   qq='',
				   msn='',
				   maillist='0',
				   bz='',
				   checked='1',
				   regtime='$now',
				   exptime='0',
				   account='0',
				   paytotal='0',
				   buytotal='0',
				   ip='$ip',
				   logincount='0',
				   logintime='$now',
				   loginip='$ip'

				
				");
				$newmemberid=$msql->instid();

				Default2Member($newmemberid,$membertypeid);
		
}




?>

<div class="searchzone">
<table width="100%" border="0" cellspacing="0" cellpadding="3">
 
  <tr> 
      <td height="28"  > 
        <table border="0" cellspacing="1" cellpadding="0" width="100%">
           <tr> 
            <td> <form name="search" action="member_common.php" method="get" >
			
			 <select name="searchmodle">
                <option value="common"  <?php echo seld($searchmodle,"common"); ?>><?php echo $strMemberSModle1; ?></option>
                <option value="cent" <?php echo seld($searchmodle,"cent"); ?>><?php echo $strMemberSModle2; ?></option>
				<option value="account" <?php echo seld($searchmodle,"account"); ?>><?php echo $strMemberSModle3; ?></option>
              </select>
			
              <select name="membertypeid" >
                <option value='0'><?php echo $strMemberTypeSel; ?></option>
                <?php
				
						$fsql->query("select * from {P}_member_type  order by membertypeid");
						while($fsql->next_record()){
							$lmembertypeid=$fsql->f('membertypeid');
							$lmembertype=$fsql->f('membertype');
							if($membertypeid==$lmembertypeid){
								echo "<option value='".$lmembertypeid."' selected>".$lmembertype."</option>";
							}else{
								echo "<option value='".$lmembertypeid."'>".$lmembertype."</option>";
							}
						}
					
					

		 ?>
              </select>
              <select name="showrz">
                <option value="all" ><?php echo $strMemberRzStat; ?></option>
                <option value="1"  <?php echo seld($showrz,"1"); ?>><?php echo $strMemberRz1; ?></option>
                <option value="0" <?php echo seld($showrz,"0"); ?>><?php echo $strMemberRz0; ?></option>
              </select>
              <select name="shownum">
                <option value="10"  <?php echo seld($shownum,"10"); ?>><?php echo $strSelNum10; ?></option>
                <option value="20" <?php echo seld($shownum,"20"); ?>><?php echo $strSelNum20; ?></option>
                <option value="30" <?php echo seld($shownum,"30"); ?>><?php echo $strSelNum30; ?></option>
                <option value="50" <?php echo seld($shownum,"50"); ?>><?php echo $strSelNum50; ?></option>
              </select>
              <input type="text" name="key" size="12"  class="input"  value="<?php echo $key; ?>" />
              <input type="submit" name="Submit" value="<?php echo $strSearchTitle; ?>" class="button" />
            
            </form></td>
          </tr>
        </table>
    </td>
   
  
      <td align="right"  >
 		<form name="adduser" method="post" action="member_common.php" onSubmit="return checkform(this)" />
        <input name="step" type="hidden" id="step" value="addmember" />
        <input name="newuser" type="text" class=input id="newuser" value="<?php echo $strAddMemberInput; ?>" size="12" onFocus="if(this.value=='<?php echo $strAddMemberInput; ?>'){this.value=''}" />
		<select name="membertypeid" >
                <?php
				
						$fsql->query("select * from {P}_member_type  order by membertypeid");
						while($fsql->next_record()){
							$lmembertypeid=$fsql->f('membertypeid');
							$lmembertype=$fsql->f('membertype');
							if($membertypeid==$lmembertypeid){
								echo "<option value='".$lmembertypeid."' selected>".$lmembertype."</option>";
							}else{
								echo "<option value='".$lmembertypeid."'>".$lmembertype."</option>";
							}
						}
					
					

		 ?>
              </select>
		<input type="submit" name="Submit" value="<?php echo $strAddMember; ?>" class="button" />
     </form>
      </td> 
   
  </tr> 
</table>

</div>
<?php
if(!isset($ord) || $ord==""){
$ord="memberid";
}
if(!isset($sc) || $sc==""){
$sc="desc";
}



$scl=" memberid!='0' ";

if($membertypeid!="" && $membertypeid!="0"){
	$scl.=" and membertypeid='$membertypeid' "; 
}

if($showcheck!="" && $showcheck!="all"){
	$scl.=" and checked='$showcheck' "; 
}

if($showrz!="" && $showrz!="all"){
	$scl.=" and rz='$showrz' "; 
}



if($key!=""){
$scl.=" and (name regexp '$key' or pname regexp '$key'  or user regexp '$key' or tags regexp '$key'  or tel regexp '$key' )";
}

$totalnums=TblCount("_member","memberid",$scl);

$pages = new pages;		
$pages->setvar(array(
"key" => $key,
"shownum" => $shownum,
"showcheck" => $showcheck,
"showrz" => $showrz,
"searchmodle" => $searchmodle,
"ord" => $ord,
"sc" => $sc, 
"membertypeid" => $membertypeid
));

$pages->set($shownum,$totalnums);		                          
$pagelimit=$pages->limit();	


//根据查询类型显示不同列
switch($searchmodle){
	case "common":
		$colcommon="";
		$colcent="display:none";
		$colaccount="display:none";
	break;
	case "cent":
		$colcommon="display:none";
		$colcent="";
		$colaccount="display:none";
	break;
	case "account":
	    NeedAuth(68);
		$colcommon="display:none";
		$colcent="display:none";
		$colaccount="";
	break;
}

//获取积分名称
$msql->query("select * from {P}_member_centset");
if($msql->next_record()){
$centname1=$msql->f('centname1');
$centname2=$msql->f('centname2');
$centname3=$msql->f('centname3');
$centname4=$msql->f('centname4');
$centname5=$msql->f('centname5');
}


?>
<form name="delfm" method="post" action="member_common.php">
<div class="listzone">
<table width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
          <tr> 
            
    <td height="28" width="30"  class="biaoti" align="center"><?php echo $strSel; ?></td>
            <td height="28" width="50"  class="biaoti" style="cursor:pointer" onClick="ordsc('memberid','<?php echo $sc; ?>')"><?php echo $strMemberNo; ?><?php OrdSc($ord,"memberid",$sc); ?></td>
            <td width="65" height="28"  class="biaoti" style="cursor:pointer" onClick="ordsc('membertypeid','<?php echo $sc; ?>')"><?php echo $strMemberType; ?><?php OrdSc($ord,"membertypeid",$sc); ?></td>
            <td width="80" height="28"  class="biaoti" style="cursor:pointer" onClick="ordsc('user','<?php echo $sc; ?>')"><?php echo $strMemberUser; ?><?php OrdSc($ord,"user",$sc); ?></td>
            <td class="biaoti" ><?php echo $strMemberFrom23; ?></td>
            <td width="80" class="biaoti"   style="<?php echo $colcommon; ?>"><?php echo $strMemberFrom21; ?></td>
            <td width="50"  class="biaoti"  style="cursor:pointer;<?php echo $colcent; ?>" onclick="ordsc('cent1','<?php echo $sc; ?>')"><?php echo $centname1; ?><?php OrdSc($ord,"cent1",$sc); ?></td>
            <td width="50"  class="biaoti"  style="cursor:pointer;<?php echo $colcent; ?>" onclick="ordsc('cent2','<?php echo $sc; ?>')"><?php echo $centname2; ?><?php OrdSc($ord,"cent2",$sc); ?></td>
            <td width="50"  class="biaoti"  style="cursor:pointer;<?php echo $colcent; ?>" onclick="ordsc('cent3','<?php echo $sc; ?>')"><?php echo $centname3; ?><?php OrdSc($ord,"cent3",$sc); ?></td>
            <td width="50"  class="biaoti"  style="cursor:pointer;<?php echo $colcent; ?>" onclick="ordsc('cent4','<?php echo $sc; ?>')"><?php echo $centname4; ?><?php OrdSc($ord,"cent4",$sc); ?></td>
            <td width="50"  class="biaoti"  style="cursor:pointer;<?php echo $colcent; ?>" onclick="ordsc('cent5','<?php echo $sc; ?>')"><?php echo $centname5; ?><?php OrdSc($ord,"cent5",$sc); ?></td>
            <td width="80"  class="biaoti"  style="cursor:pointer;<?php echo $colaccount; ?>" onclick="ordsc('account','<?php echo $sc; ?>')"><?php echo $strMemberAccounts; ?><?php OrdSc($ord,"account",$sc); ?></td>
            <td width="80"  class="biaoti"  style="cursor:pointer;<?php echo $colaccount; ?>" onclick="ordsc('paytotal','<?php echo $sc; ?>')"><?php echo $strMemberPayTotal; ?><?php OrdSc($ord,"paytotal",$sc); ?></td>
            <td width="80"  class="biaoti"  style="cursor:pointer;<?php echo $colaccount; ?>" onclick="ordsc('buytotal','<?php echo $sc; ?>')"><?php echo $strMemberBuyTotal; ?><?php OrdSc($ord,"buytotal",$sc); ?></td>
            <td width="65" height="28"  class="biaoti"  style="cursor:pointer;<?php echo $colcommon; ?>" onClick="ordsc('regtime','<?php echo $sc; ?>')"><?php echo $strMemberRegTime; ?><?php OrdSc($ord,"regtime",$sc); ?></td>
            <td  width="32" align="center"   class="biaoti"  style="<?php echo $colcommon; ?>"><?php echo $strMemberRz; ?></td>
            <td width="32"  class="biaoti" align="center" style="<?php echo $colaccount; ?>"><?php echo $strMemberAddAcc; ?></td>
            <td height="28" width="32"  class="biaoti" align="center" style="<?php echo $colcommon; ?>"><?php echo $strMemberMail; ?></td>
            <td width="32"  class="biaoti" align="center" style="<?php echo $colcent; ?>"><?php echo $strLook; ?></td>
            <td height="28" width="32"  class="biaoti" align="center" style="<?php echo $colcommon; ?>"><?php echo $strMemberDetail; ?></td>
            <td width="32"  class="biaoti" align="center" style="<?php echo $colcommon; ?>"><?php echo $strMemberAuth; ?> </td>
            <td width="32"  class="biaoti" align="center"><?php echo $strMemberLogin; ?></td>
          </tr>
          <?php 


$msql->query("select * from {P}_member where $scl order by $ord $sc limit $pagelimit");


while($msql->next_record()){
$memberid=$msql->f('memberid');
$mymembertypeid=$msql->f('membertypeid');
$mymembergroupid=$msql->f('membergroupid');
$user=$msql->f('user');
$name=$msql->f('name');
$company=$msql->f('company');
$pname=$msql->f('pname');
$tel=$msql->f('tel');
$email=$msql->f('email');
$checked=$msql->f('checked');
$rz=$msql->f('rz');
$ip=$msql->f('ip');
$logincount=$msql->f('logincount');
$regtime=$msql->f('regtime');
$exptime=$msql->f('exptime');
$cent1=$msql->f('cent1');
$cent2=$msql->f('cent2');
$cent3=$msql->f('cent3');
$cent4=$msql->f('cent4');
$cent5=$msql->f('cent5');
$account=$msql->f('account');
$paytotal=$msql->f('paytotal');
$buytotal=$msql->f('buytotal');

$regtime=date("y/n/j",$regtime);

switch($mymembergroupid){
	case "2":
	$showmyname=$company;
	break;
	default:
	$showmyname=$name;
	break;
}

?> 
          <tr class="list"> 
            <td width="30" align="center" > 
              <input type="checkbox" name="dall[]" value="<?php echo $memberid; ?>">
            </td>
            <td   width="50"> <?php echo $memberid; ?> </td>
            <td width="65" ><?php echo Membertypeid2Membertype($mymembertypeid); ?></td>
            <td width="80"  > <?php echo $user; ?> </td>
            <td ><?php echo $showmyname; ?></td>
            <td width="80" style="<?php echo $colcommon; ?>"><?php echo $pname; ?></td>
            <td width="50" style="<?php echo $colcent; ?>"><?php echo $cent1; ?></td>
            <td width="50" style="<?php echo $colcent; ?>"><?php echo $cent2; ?></td>
            <td width="50" style="<?php echo $colcent; ?>"><?php echo $cent3; ?></td>
            <td width="50" style="<?php echo $colcent; ?>"><?php echo $cent4; ?></td>
            <td width="50" style="<?php echo $colcent; ?>"><?php echo $cent5; ?></td>
            <td width="80" style="<?php echo $colaccount; ?>"><?php echo $account; ?></td>
            <td width="80" style="<?php echo $colaccount; ?>"><?php echo $paytotal; ?> <a href="paylist.php?memberid=<?php echo $memberid; ?>"><img src="images/arrowright.gif" alt="<?php echo $strAccList; ?>" width="16" height="16" border="0" /></a></td>
            <td width="80" style="<?php echo $colaccount; ?>"><?php echo $buytotal; ?> <a href="buylist.php?memberid=<?php echo $memberid; ?>"><img src="images/arrowright.gif" alt="<?php echo $strAccBuyList; ?>" width="16" height="16" border="0" /></a></td>
            <td width="65" style="<?php echo $colcommon; ?>"><?php echo $regtime; ?></td>
            <td width="32" align="center"  style="<?php echo $colcommon; ?>"><?php ShowYN($rz); ?></td>
            <td  width="32" align="center" style="<?php echo $colcommon; ?>"><img id="membermail_<?php echo $memberid; ?>" class="membermail" src="images/mail.png"  border="0" /></td>
            <td align="center"  width="32" style="<?php echo $colaccount; ?>"><a href="addacc.php?memberid=<?php echo $memberid; ?>"><img src="images/mail.png"  border="0" /></a></td>
            <td  width="32" align="center" style="<?php echo $colcent; ?>"><img id="membercent_<?php echo $memberid; ?>" class="membercent" src="images/look.png"  border="0" /> </td>
            <td align="center"  width="32" style="<?php echo $colcommon; ?>"><img id="membermodify_<?php echo $memberid; ?>" class="membermodify" src="images/edit.png"  border="0" />
            </td>
            <td  width="32" align="center" style="<?php echo $colcommon; ?>"><a href="member_rights.php?memberid=<? echo $memberid; ?>&amp;nowtype=<? echo $membertypeid; ?>&amp;user=<? echo $user; ?>&amp;page=<? echo $page; ?>"><img src="images/auth.png"  border="0" /></a></td>
            <td  width="32" align="center"><img src="images/person.png" border="0" style="cursor:pointer" onClick="window.open('vmember.php?memberid=<? echo $memberid; ?>&tourl=member/index.php','vmember','width=1024,height=700,scrollbars=yes')"></td>
          </tr>
          <?php
}
?> 
       
 
</table>
</div>
<div class="piliang"> 
<input type="checkbox" name="SELALL" value="1" onClick="SelAll(this.form)">
        <?php echo $strSelAll; ?>
        <input type="radio" name="step" value="delall">
        <?php echo $strDelete ?>
        <input type="radio" name="step" value="rzall" />
        <?php echo $strMemberRzStat; ?>
        <input type="radio" name="step" value="unrzall" />
        <?php echo $strMemberUnRz; ?>
        <input type="radio" name="step" value="chtypeall"><?php echo $strMemberChangeTo; ?>
		
		<select name="newtypeid" >
                <?php
				

						$fsql->query("select * from {P}_member_type  order by membertypeid");
						while($fsql->next_record()){
							$changetypeid=$fsql->f('membertypeid');
							$changetype=$fsql->f('membertype');
							if($changetypeid==$membertypeid){
								echo "<option value='".$changetypeid."' selected>".$changetype."</option>";
							}else{
								echo "<option value='".$changetypeid."'>".$changetype."</option>";
							}
						}
					
					

		 ?>
    </select>
       
        <input type="submit" name="Submit2" class="button" value="<?php echo $strSubmit; ?>" />
        &nbsp;&nbsp;<a style="cursor:pointer;color:#ffffff;font-weight:bold" onClick="delfm.submit()"> 
        </a> 
        <input type="hidden" name="ord" size="3" value="<?php echo $ord; ?>" />
        <input type="hidden" name="sc" size="3" value="<?php echo $sc; ?>" />
		<input type="hidden" name="membertypeid" size="3" value="<?php echo $membertypeid; ?>" />
		<input type="hidden" name="memberid" size="3" value="<?php echo $memberid; ?>" />
		<input type="hidden" name="key" size="3" value="<?php echo $key; ?>" />
        <input type="hidden" name="shownum" value="<?php echo $shownum; ?>" />
        <input type="hidden" name="showcheck" value="<?php echo $showcheck; ?>" />
		<input type="hidden" name="showrz" value="<?php echo $showrz; ?>" />
        <input type="hidden" name="searchmodle" value="<?php echo $searchmodle; ?>" />
</div>

</form>

<?php
$pagesinfo=$pages->ShowNow();
?>
<div id="showpages">
	  <div id="pagesinfo"><?php echo $strPagesTotalStart.$totalnums.$strPagesTotalEnd; ?> <?php echo $strPagesMeiye.$pagesinfo["shownum"].$strPagesTotalEnd; ?> <?php echo $strPagesYeci; ?> <?php echo $pagesinfo["now"]."/".$pagesinfo["total"]; ?></div>
	  <div id="pages"><?php echo $pages->output(1); ?></div>
</div>
</body>
</html>
