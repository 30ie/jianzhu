<?php
define("ROOTPATH", "../../");
include(ROOTPATH."includes/admin.inc.php");
include("language/".$sLan.".php");
NeedAuth(43);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link  href="css/style.css" type="text/css" rel="stylesheet">
<title><?php echo $strAdminTitle; ?></title>
<script type="text/javascript" src="../../base/js/base.js"></script>
<script type="text/javascript" src="../../base/js/form.js"></script>
<script type="text/javascript" src="js/modules.js"></script>

</head>

<body >

<?php
$id=$_REQUEST["id"];
$msql->query("select * from {P}_webmall_modules where id='$id' limit 0,1");
if($msql->next_record()){
$module=$msql->f('module');
$cname=$msql->f('cname');
$price=$msql->f('price');
$price1=$msql->f('price1');
$price2=$msql->f('price2');
$mtype1=$msql->f('mtype1');
$mtype2=$msql->f('mtype2');
$danwei=$msql->f('danwei');

}
?>

<form action="" method="post" name="form" id="ModulesModi" >
<div class="formzone">
<div class="namezone">
<?php echo $strSetMenu13; ?>
</div>
<div class="tablezone">
<div id="notice" class="noticediv"></div>
  

      <table width="100%" cellpadding="2" align="center"  style="border-collapse: collapse" border="0" cellspacing="0">
          <tr>
            <td height="30" align="center" >模块代码</td>
            <td height="30" ><?php echo $module; ?></td>
          </tr>
          <tr>
            <td width="100" height="30" align="center" >模块名称</td>
            <td height="30" ><input name="cname" type="text" class="input" id="cname" value="<?php echo $cname; ?>" size="60" maxlength="50"  />
                <font color="#FF0000">*</font> </td>
          </tr>
          <tr>
            <td height="30" align="center" >定价单位</td>
            <td height="30" ><input name="danwei" type="text" class="input" id="danwei"  value="<?php echo $danwei; ?>" size="5" maxlength="12" />
                <font color="#FF0000">*</font> </td>
          </tr>
          <tr>
            <td height="30" align="center" >模块价格</td>
            <td height="30" ><input name="price" type="text" class="input" id="price"  value="<?php echo $price; ?>" size="12" maxlength="12" />
    元<font color="#FF0000"> *</font> </td>
          </tr>
          <tr>
            <td height="30" align="center" >代理价 I</td>
            <td height="30" ><input name="price1" type="text" class="input" id="price1"  value="<?php echo $price1; ?>" size="12" maxlength="12" />
                <select name="mtype1" id="mtype1">
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
                <font color="#FF0000"> *</font> </td>
          </tr>
          <tr>
            <td height="30" align="center" >代理价II</td>
            <td height="30" ><input name="price2" type="text" class="input" id="price2"  value="<?php echo $price2; ?>" size="12" maxlength="12" />
                <select name="mtype2" id="mtype2">
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
                <font color="#FF0000"> *</font> </td>
          </tr>
          
          
        
      </table>
	 
</div>  
<div class="adminsubmit">
<input type="submit" id="tpadd" name="tpadd"   value="<?php echo $strSubmit; ?>" class="button" />
<input type="hidden" name="act" value="modulesmodi" />
<input name="id" type="hidden" id="id" value="<?php echo $id; ?>" />
</div> 
</div>
</form>
<p>&nbsp;</p>
</body>
</html>
