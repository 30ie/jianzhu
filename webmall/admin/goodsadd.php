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
<script type="text/javascript" src="../../base/js/blockui.js"></script>
<script type="text/javascript" src="js/goods.js"></script>

<style type="text/css">
<!--
.style1 {color: #000000}
.style2 {color: #FF0000}
.style3 {color: #333333}
-->
</style>
</head>

<body >

<form action="" method="post" enctype="multipart/form-data" name="form" id="GoodsAdd" >
<div class="formzone">
<div class="namezone">
<?php echo $strSetMenu10; ?>
</div>
<div class="tablezone">
<div id="notice" class="noticediv"></div>
  

      <table width="100%" cellpadding="2" align="center"  style="border-collapse: collapse" border="0" cellspacing="0">
          <tr>
            <td width="100" height="30" align="center" >续费商品</td>
            <td height="30" ><input name="ifxu" type="radio" value="1" checked="checked" />
续费服务
  <input name="ifxu" type="radio" value="0" />
单次购买</td>
          </tr>
          <tr>
            <td height="30" align="center" >商品名称</td>
            <td height="30" ><input name="goods" type="text" class="input" id="goods" size="60" maxlength="50"  />
                <font color="#FF0000">*</font> </td>
          </tr>
          <tr>
            <td height="30" align="center" >商品简介</td>
            <td height="30" ><textarea name="intro" cols="60" rows="8" class="textarea" id="intro"></textarea>                </td>
          </tr>
          <tr>
            <td height="30" align="center" >定价单位</td>
            <td height="30" ><input name="danwei" type="text" class="input" id="danwei"  value="次" size="5" maxlength="12" />    <font color="#FF0000">*</font> </td>
          </tr>
          <tr>
            <td height="30" align="center" >商品价格</td>
            <td height="30" ><input name="price" type="text" class="input" id="price"  value="0" size="12" maxlength="12" />
    元<font color="#FF0000"> *</font> </td>
          </tr>
          <tr>
            <td height="30" align="center" >代理价 I</td>
            <td height="30" ><input name="price1" type="text" class="input" id="price1"  value="0" size="12" maxlength="12" />
                <select name="mtype1" id="mtype1">
                  <?php 
			$msql->query("select * from {P}_member_type");
			while($msql->next_record()){
				$membertype=$msql->f('membertype');
				$membertypeid=$msql->f('membertypeid');
				echo "<option value='".$membertypeid."'>".$membertype."</option>";
			}
			?>
                </select>
                <font color="#FF0000"> *</font> </td>
          </tr>
          <tr>
            <td height="30" align="center" >代理价II</td>
            <td height="30" ><input name="price2" type="text" class="input" id="price2"  value="0" size="12" maxlength="12" />
                <select name="mtype2" id="mtype2">
                  <?php 
			$msql->query("select * from {P}_member_type");
			while($msql->next_record()){
				$membertype=$msql->f('membertype');
				$membertypeid=$msql->f('membertypeid');
				echo "<option value='".$membertypeid."'>".$membertype."</option>";
			}
			?>
                </select>
                <font color="#FF0000"> *</font> </td>
          </tr>
          
          
        
      </table>
	 
</div>  
<div class="adminsubmit">
<input type="submit" id="tpadd" name="tpadd"   value="<?php echo $strSubmit; ?>" class="button" />
<input type="hidden" name="act" value="goodsadd" />
</div> 
</div>
</form>
<p>&nbsp;</p>
</body>
</html>
