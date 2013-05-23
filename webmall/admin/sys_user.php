<?php
define("ROOTPATH", "../../");
include(ROOTPATH."includes/admin.inc.php");
include(ROOTPATH."includes/pages.inc.php");
include("language/".$sLan.".php");
NeedAuth(40);

$page=$_REQUEST["page"];
$key=$_REQUEST["key"];
$shownum=$_REQUEST["shownum"];
$ifexp=$_REQUEST["ifexp"];

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
</head>

<body>
<div class="searchzone">
<table width="100%" border="0" cellspacing="0" cellpadding="3">
 
  <tr> 
      <td height="28"  > 
        <table border="0" cellspacing="1" cellpadding="0" width="100%">
           <tr> 
            <td> <form name="search" action="sys_user.php" method="get" >
			
			 <select name="ifexp">
                    <option value="all"  <?php echo seld($ifexp,"all"); ?>>所有时间状态</option>
                    <option value="1"  <?php echo seld($ifexp,"1"); ?>>未到期站点</option>
					<option value="2"  <?php echo seld($ifexp,"2"); ?>>已到期站点</option>
					<option value="3"  <?php echo seld($ifexp,"3"); ?>>近到期站点</option>					
              </select>
				  
              <select name="shownum">
                <option value="10"  <?php echo seld($shownum,"10"); ?>><?php echo $strSelNum10; ?></option>
                <option value="20" <?php echo seld($shownum,"20"); ?>><?php echo $strSelNum20; ?></option>
                <option value="30" <?php echo seld($shownum,"30"); ?>><?php echo $strSelNum30; ?></option>
                <option value="50" <?php echo seld($shownum,"50"); ?>><?php echo $strSelNum50; ?></option>
              </select>
              <input type="text" name="key" size="30"  class="input"  value="<?php echo $key; ?>" />
              <input type="submit" name="Submit" value="<?php echo $strSearchTitle; ?>" class="button" />
            </form></td>
          </tr>
        </table>
    </td>
   
  
     
   
  </tr> 
</table>

</div>
<?php
//获取查询条件

$scl=" usertype='0' ";



$now=time();
$cha=604800;
$chatime=$now+$cha;

if($ifexp=="1"){
	$scl.=" and exptime>=$now ";
}
if($ifexp=="2"){
	$scl.=" and exptime<$now ";
}
if($ifexp=="3"){
	$scl.=" and exptime<$chatime and exptime>$now ";
}

if($key!=""){
$scl.=" and (user regexp '$key' or urlname regexp '$key'  or spool regexp '$key')";
}

//获取代理连接参数
$msql->query("select * from {P}_webmall_config");
while($msql->next_record()){
	$variable=$msql->f('variable');
	$value=$msql->f('value');
	$WEBMALLCONF[$variable]=$value;
}
$AgentApi=$WEBMALLCONF["AgentApi"];
$AgentUser=$WEBMALLCONF["AgentUser"];
$AgentPasswd=$WEBMALLCONF["AgentPasswd"];

//连接主站vp接口
include(ROOTPATH."base/nusoap/nusoap.php");

$server   = "http://".$AgentApi."/webtry/admin/vp/soapserver.php";
$customer = new soapclientx ($server);

$mdpass=md5($AgentPasswd);
$params  = array (
'agentuser'  => $AgentUser,
'agentpasswd'  => $mdpass,
'scl'  => $scl
);

$resault=$customer -> call ("SITECOUNT", $params);

//错误调试和连接失败判断
if ($err=$customer->getError()) {
	$errinfo=$customer->response;
	err("主站代理接口连接失败".$err.$errinfo,"","");
	exit;
}

if($resault[0]=="5000"){
	err("主站代理帐号校验未通过，代理帐号或密码错误","","");
	exit;
}

if($resault[0]=="9999"){
	$totalnums=$resault[1];
}
		
		
$pages = new pages;		
$pages->setvar(array(
"key" => $key,
"shownum" => $shownum,
"ifexp" => $ifexp
));

$pages->set($shownum,$totalnums);		                          
$pagelimit=$pages->limit();	

?>
<div class="listzone">
<table width="100%" border="0" cellspacing="0" cellpadding="5" align="center">
          <tr> 
    <td width="45"  class="biaoti" ><?php echo $strWebMallList1; ?></td>
            <td width="70"  class="biaoti" >本站会员</td>
            <td width="60"  class="biaoti" ><?php echo $strWebSpoolList3; ?>
               </td>
            <td width="130"  class="biaoti" ><?php echo $strWebMallList3; ?></td>
            <td height="28"  class="biaoti" >网站服务器</td>
            <td width="60" height="28"  class="biaoti"  ><?php echo $strWebMallList6; ?></td>
            <td  width="60" height="28"  class="biaoti" ><?php echo $strWebMallList7; ?></td>
           <td  width="41"  class="biaoti"  ><?php echo $strWebMallList8; ?></td>
            <td width="39"  class="biaoti" align="center" ><?php echo $strLook; ?></td>
	      </tr>
<?php 
		$params  = array (
		'agentuser'  => $AgentUser,
		'agentpasswd'  => $mdpass,
		'scl'  => $scl,
		'pagelimit'  => $pagelimit
		);

		$resault=$customer -> call ("SITELIST", $params);

		if ($err=$customer->getError()) {
			$errinfo=$customer->response;
			err("主站代理接口连接失败".$err.$errinfo,"","");
			exit;
		}

		if($resault=="5000"){
			err("主站代理帐号校验未通过，代理帐号或密码错误","","");
			exit;
		}


		if($resault!="0000"){
		
			$nums=sizeof($resault);
			for($i=0;$i<$nums;$i++){
				$userid=$resault[$i]["userid"];
				$spool=$resault[$i]["spool"];
				$spoolname=$resault[$i]["spoolname"];
				$siteurl=$resault[$i]["siteurl"];
				$uuser=$resault[$i]["user"];
				$upasswd=$resault[$i]["passwd"];
				$clientid=$resault[$i]["clientid"];
				$regtime=$resault[$i]["regtime"];
				$exptime=$resault[$i]["exptime"];
				$stat=$resault[$i]["stat"];
				$serverdomain=$resault[$i]["serverdomain"];
				$ifok=$resault[$i]["ifok"];


				$showregtime=date("y-n-j",$regtime);
				$showexptime=date("y-n-j",$exptime);


				if($exptime<time()){
					$expcolor="#ff0000";
				}elseif($exptime>time() && $exptime<(time()+604800)){
					$expcolor="#cc3300";
				}else{
					$expcolor="#505050";
				}
				
				if($clientid!="" && $clientid!="0"){
					$fsql->query("select user from {P}_member where memberid='$clientid' limit 0,1");
					if($fsql->next_record()){
						$memberuser=$fsql->f('user');
					}else{
						$memberuser="未知";
					}
				}else{
					$memberuser="直接申请";
				}
				
				switch($ifok){
				case "0":
					$statimg="no.png";
				break;
				case "1":
					$statimg="alert.png";
				break;
				case "2":
					$statimg="ok.png";
				break;
}



?> 
          <tr class="list" id="tr_<?php echo $userid; ?>"> 
            <td   width="45"><?php echo $userid; ?> </td>
            <td width="70" ><?php echo $memberuser; ?></td>
			 <td width="60" ><?php echo $spool; ?></td>
			 <td width="130" ><?php echo $spoolname; ?></td>
			 <td ><a href="<?php echo $siteurl; ?>" target="_blank"><?php echo $serverdomain; ?></a></td>
            <td width="60" ><?php echo $showregtime; ?></td>
            <td width="60" style="color:<?php echo $expcolor; ?>"><?php echo $showexptime; ?></td>
			<td width="41" ><img src="images/<?php echo $statimg; ?>"  border="0" /></td>
			<td  width="39" align="center" ><img src="images/look.png"  border="0" style="cursor:pointer" onClick="window.open('<?php echo $siteurl; ?>','_blank')" /></td>
          </tr>
<?php
}
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
