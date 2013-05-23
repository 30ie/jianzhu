<?php
define("ROOTPATH", "../../");
include(ROOTPATH."includes/admin.inc.php");
include("language/".$sLan.".php");
NeedAuth(42);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link  href="css/style.css" type="text/css" rel="stylesheet">
<title><?php echo $strAdminTitle; ?></title>
<script type="text/javascript" src="../../base/js/base.js"></script>
<script type="text/javascript" src="../../base/js/form.js"></script>
<script type="text/javascript" src="../../base/js/blockui.js"></script>
<script type="text/javascript" src="js/webtemp.js"></script>

<style type="text/css">
<!--
.style1 {color: #000000}
.style2 {color: #FF0000}
.style3 {color: #333333}
-->
</style>
</head>

<body >

<?php
$id=$_REQUEST["id"];

$msql->query("select * from {P}_webmall_spool where id='$id' limit 0,1");
if($msql->next_record()){
$id=$msql->f('id');
$name=$msql->f('name');
$spool=$msql->f('spool');
$nowtypeid=$msql->f('typeid');
$nowcatid=$msql->f('catid');
$designer=$msql->f('designer');
$memo=$msql->f('memo');
$intro=$msql->f('intro');
$price=$msql->f('price');
$sellnums=$msql->f('sellnums');
$demourl=$msql->f('demourl');
$buyurl=$msql->f('buyurl');
$dtime=$msql->f('dtime');
$price1=$msql->f('price1');
$price2=$msql->f('price2');
$mtype1=$msql->f('mtype1');
$mtype2=$msql->f('mtype2');
$hostsize=$msql->f('hostsize');
$xufei=$msql->f('xufei');
$xufei1=$msql->f('xufei1');
$xufei2=$msql->f('xufei2');
$xtype1=$msql->f('xtype1');
$xtype2=$msql->f('xtype2');

}
?>

<form action="" method="post" enctype="multipart/form-data" name="form" id="webTempModi" >
<div class="formzone">
<div class="namezone">
产品定价
</div>
<div class="tablezone">
<div id="notice" class="noticediv"></div>
  

      <table width="100%" cellpadding="2" align="center"  style="border-collapse: collapse" border="0" cellspacing="0">
          <tr> 
            <td height="30" width="100" align="right" >产品编号：</td>
            <td width="5" >&nbsp;</td>
            <td height="30" ><?php echo $spool; ?> </td>
          </tr>
          <tr>
            <td height="30" align="right" >产品名称：</td>
            <td width="5" >&nbsp;</td>
            <td height="30" ><?php echo $name; ?>                </td>
          </tr>
          <tr>
            <td height="30" align="right" >应用分类：</td>
            <td width="5" >&nbsp;</td>
            <td height="30" >
			<?php
			$fsql->query ("select cat from {P}_webmall_temptype where catid='$nowtypeid'");
				if ($fsql -> next_record ()) {
					$nowtype=$fsql->f("cat");
				}
				echo $nowtype;
			?>
			</td>
          </tr>
          <tr>
            <td height="30" align="right" >行业分类：</td>
            <td width="5" >&nbsp;</td>
            <td height="30" ><?php
			$fsql->query ("select cat from {P}_webmall_tempcat where catid='$nowcatid'");
				if ($fsql -> next_record ()) {
					$nowcat=$fsql->f("cat");
				}
				echo $nowcat;
			?></td>
          </tr>
          <tr>
            <td height="30" align="right" >包含模块：</td>
            <td width="5" >&nbsp;</td>
            <td height="30" >
			<?php 
			$modules="";
			$fsql->query("select `module` from {P}_webmall_spoolmod where spool='$spool'");
			while($fsql->next_record()){
				$module=$fsql->f('module');
				$msql->query("select cname from {P}_webmall_modules where `module`='$module' limit 0,1");
				if($msql->next_record()){
					$modules.=$msql->f('cname').",";
				}
			}
			$modules=substr($modules,0,-1);
			echo $modules;
			?>
			</td>
          </tr>
          <tr>
            <td height="30" align="right" >演示网址：</td>
            <td width="5" >&nbsp;</td>
            <td height="30" ><?php echo $demourl; ?>
              <font color="#FF0000">&nbsp; </font></td>
          </tr>
          <tr>
            <td height="30" align="right" >产品价格：</td>
            <td width="5" >&nbsp;</td>
            <td height="30" ><input name="price" type="text" class="input" id="price"  value="<?php echo $price; ?>" size="25" maxlength="12" />
    元<font color="#FF0000"> *</font> </td>
          </tr>
          <tr>
            <td height="30" align="right" >代理价 I：</td>
            <td width="5" >&nbsp;</td>
            <td height="30" >                <select name="mtype1" id="mtype1">
                    <?php 
			$msql->query("select * from {P}_member_type");
			while($msql->next_record()){
				$membertype=$msql->f('membertype');
				$membertypeid=$msql->f('membertypeid');
				if($mtype1==$membertypeid){
					echo "<option value='".$membertypeid."' selected>".$membertype."</option>";
				}else{
					echo "<option value='".$membertypeid."'>".$membertype."</option>";
				}
			}
			?>
                  </select>
              <input name="price1" type="text" class="input" id="price1"  value="<?php echo $price1; ?>" size="12" maxlength="12" />
                  <font color="#FF0000"> *</font> </td>
          </tr>
          <tr>
            <td height="30" align="right" >代理价II：</td>
            <td width="5" >&nbsp;</td>
            <td height="30" >			<select name="mtype2" id="mtype2">
			<?php 
			$msql->query("select * from {P}_member_type");
			while($msql->next_record()){
				$membertype=$msql->f('membertype');
				$membertypeid=$msql->f('membertypeid');
				if($mtype2==$membertypeid){
					echo "<option value='".$membertypeid."' selected>".$membertype."</option>";
				}else{
					echo "<option value='".$membertypeid."'>".$membertype."</option>";
				}
			}
			?>
            </select>
              <input name="price2" type="text" class="input" id="price2"  value="<?php echo $price2; ?>" size="12" maxlength="12" />
            <font color="#FF0000"> *</font> </td>
          </tr>
          <tr>
            <td height="30" align="right" >年服务费：</td>
            <td width="5" >&nbsp;</td>
            <td height="30" ><input name="xufei" type="text" class="input" id="xufei"  value="<?php echo $xufei; ?>" size="25" maxlength="12" />
    元<font color="#FF0000"> *</font> </td>
          </tr>
          <tr>
            <td height="30" align="right" >代理价 I：</td>
            <td width="5" >&nbsp;</td>
            <td height="30" >                <select name="xtype1" id="xtype1">
                  <?php 
			$msql->query("select * from {P}_member_type");
			while($msql->next_record()){
				$membertype=$msql->f('membertype');
				$membertypeid=$msql->f('membertypeid');
				if($xtype1==$membertypeid){
					echo "<option value='".$membertypeid."' selected>".$membertype."</option>";
				}else{
					echo "<option value='".$membertypeid."'>".$membertype."</option>";
				}
			}
			?>
                </select>
                <font color="#FF0000"> 
                <input name="xufei1" type="text" class="input" id="xufei1"  value="<?php echo $xufei1; ?>" size="12" maxlength="12" />
                *</font> </td>
          </tr>
          <tr>
            <td height="30" align="right" >代理价II：</td>
            <td width="5" >&nbsp;</td>
            <td height="30" >                <select name="xtype2" id="xtype2">
                  <?php 
			$msql->query("select * from {P}_member_type");
			while($msql->next_record()){
				$membertype=$msql->f('membertype');
				$membertypeid=$msql->f('membertypeid');
				if($xtype2==$membertypeid){
					echo "<option value='".$membertypeid."' selected>".$membertype."</option>";
				}else{
					echo "<option value='".$membertypeid."'>".$membertype."</option>";
				}
			}
			?>
                </select>
              <input name="xufei2" type="text" class="input" id="xufei2"  value="<?php echo $xufei2; ?>" size="12" maxlength="12" />
                <font color="#FF0000"> *</font> </td>
          </tr>
          
          
        
      </table>
	 
</div>  
<div class="adminsubmit">
<input type="submit" id="tpmodi" name="tpmodi"   value="<?php echo $strSubmit; ?>" class="button" />
<input type="hidden" name="act" value="webtempmodi" />
<input name="id" type="hidden" id="id" value="<?php echo $id; ?>" />
<input name="spool" type="hidden" id="spool" value="<?php echo $spool; ?>" />
</div> 
</div>
</form>
<p>&nbsp;</p>
</body>
</html>
