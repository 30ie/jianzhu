<?php
define("ROOTPATH", "../../");
include(ROOTPATH."includes/admin.inc.php");
include("language/".$sLan.".php");
NeedAuth(160);

$step=$_REQUEST["step"];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link  href="css/style.css" type="text/css" rel="stylesheet">
<title><?php echo $strAdminTitle; ?></title>

</head>

<body>


<?php

if($step=="modify"){
$var=$_POST["var"];

while (list($key,$val)=each($var)){

	$msql->query("update {P}_down_config set value='$val' where variable='$key'");

}

	SayOk($strConfigOk,"config.php","");
}




?>
<div class="formzone">
<form name="form1" method="post" action="config.php">

<div class="tablezone">
          <table width="100%" border="0" align="center" cellpadding="8" cellspacing="0">
            <tr> 
              <td class="innerbiaoti"><strong><?php echo $strConfigName; ?></strong></td>
              <td class="innerbiaoti"  width="300" height="28"><strong><?php echo $strConfigSet; ?></strong></td>
            </tr>
           
            <?php

	$msql->query("select * from {P}_down_config  order by xuhao");
	while($msql->next_record()){
	
	$variable=$msql->f('variable');
	$value=$msql->f('value');
	$vname=$msql->f('vname');
	$settype=$msql->f('settype');
	$colwidth=$msql->f('colwidth');
	$intro=$msql->f('intro');
	$intro=str_replace("\n","<br>",$intro);



?> 
            <tr class="list"> 
              <td   style="line-height:20px;padding-right:30px"> 
                
               <strong><?php echo $vname; ?> : </strong><br>
              <?php echo $intro; ?></td>
              <td   width="300" height="20"> <?php
if($settype=="YN"){
?> 
                <input type="radio" name="var[<?php echo $variable; ?>]" value="1" <?php echo checked($value,"1"); ?>><?php echo $strYes; ?>
                <input type="radio" name="var[<?php echo $variable; ?>]" value="0" <?php echo checked($value,"0"); ?>>
                <?php echo $strNo; ?> <?php
}elseif($settype=="centlist"){
		
		$fsql->query("select * from {P}_member_centset");
		if($fsql->next_record()){
			$centname1=$fsql->f('centname1');
			$centname2=$fsql->f('centname2');
			$centname3=$fsql->f('centname3');
			$centname4=$fsql->f('centname4');
			$centname5=$fsql->f('centname5');
		}
?>
	
                <select name="var[<?php echo $variable; ?>]" >
				  <option value="1" <?php echo seld($value,1); ?> ><?php echo $centname1; ?></option>
                  <option value="2" <?php echo seld($value,2); ?>><?php echo $centname2; ?></option>
				  <option value="3" <?php echo seld($value,3); ?>><?php echo $centname3; ?></option>
				  <option value="4" <?php echo seld($value,4); ?>><?php echo $centname4; ?></option>
				  <option value="5" <?php echo seld($value,5); ?>><?php echo $centname5; ?></option>
                </select>
<?php

}elseif($settype=="centyj"){
		
?>
	
                <select name="var[<?php echo $variable; ?>]" >
				  <option value="0.1" <?php echo seld($value,"0.1"); ?> >10%</option>
                  <option value="0.2" <?php echo seld($value,"0.2"); ?> >20%</option>
				  <option value="0.3" <?php echo seld($value,"0.3"); ?> >30%</option>
				  <option value="0.4" <?php echo seld($value,"0.4"); ?> >40%</option>
				  <option value="0.5" <?php echo seld($value,"0.5"); ?> >50%</option>
				  <option value="0.6" <?php echo seld($value,"0.6"); ?> >60%</option>
				  <option value="0.7" <?php echo seld($value,"0.7"); ?> >70%</option>
                </select>
<?php

}else{
?> 
                <input  type="text" name="var[<?php echo $variable; ?>]"   value="<?php echo $value; ?>" size="<?php echo $colwidth; ?>" class="input" />
<?php
}
?>


              </td>
            </tr>
            <?php
}
?> 
           
    </table>
</div>
<div class="adminsubmit">
  <input name="cc2" type="submit" id="cc" value="<?php echo $strSubmit; ?>" class="button" />
  <input type="hidden" name="step" value="modify" />
</div>

</form>
</div>
</body>
</html>
