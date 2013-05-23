<?php
define("ROOTPATH", "../../");
include(ROOTPATH."includes/admin.inc.php");
include("language/".$sLan.".php");
include("func/upload.inc.php");
NeedAuth(165);

$pid=$_REQUEST["pid"];
if(!isset($pid) || $pid==""){
$pid=0;
}
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
<script type="text/javascript" src="js/down.js"></script>

</head>
<body >

<form id="downAddForm" name="form" action="" method="post" enctype="multipart/form-data">

<div class="formzone">

<div class="namezone">
<?php echo $strDownFabu; ?>
</div>
<div class="tablezone">
<div  id="notice" class="noticediv"></div>

<table width="100%"   border="0" align="center"  cellpadding="2" cellspacing="0" >
    <tr> 
      <td height="30" width="100" align="center" ><?php echo $strDownCatTitle; ?></td>
      <td height="30" > 
        <select id="selcatid" name="catid" >
          <?php		

					$fsql -> query ("select * from {P}_down_cat  order by catpath");
					while ($fsql -> next_record ()) {
						$lpid = $fsql -> f ("pid");
						$lcatid = $fsql -> f ("catid");
						$cat = $fsql -> f ("cat");
						$catpath = $fsql -> f ("catpath");
						$lcatpath = explode (":", $catpath);


						
						
							for ($i = 0; $i < sizeof ($lcatpath)-2; $i ++) {
								$tsql->query("select catid,cat from {P}_down_cat where catid='$lcatpath[$i]'");
								if($tsql->next_record()){
									$ncatid=$tsql->f('cat');
									$ncat=$tsql->f('cat');
									$ppcat.=$ncat."/";
								}
							}
							
							if($pid==$lcatid){
								echo "<option value='".$lcatid."' selected>".$ppcat.$cat."</option>";
							}else{
								echo "<option value='".$lcatid."'>".$ppcat.$cat."</option>";
							}
							$ppcat="";
						
						
					}

				
?> 
        </select>
        <font color="#FF0000">*</font> </td>
    </tr>
	
	
    <tr> 
      <td height="30" width="100" align="center" ><?php echo $strDownAddTitle; ?></td>
      <td height="30" > 
        <input type="text" name="title" style='WIDTH: 499px;font-size:12px;' maxlength="200" class="input" />
        <font color="#FF0000">*</font> </td>
    </tr>
	<tr> 
      <td height="30" width="100" align="center" ><?php echo $strDownFileDown; ?></td>
      <td height="30" valign=top> 
        
          <input id="divsuo" type="file" name="file" style='WIDTH: 499px;display:none' class="input" />
          <input id="divurl" type="text" name="fileurl" style='WIDTH: 499px;' value="http://" class="input" />
          <input type="radio" name="addtype" value="addurl" checked onClick="document.getElementById('divurl').style.display='inline';document.getElementById('divsuo').style.display='none';">
        <?php echo $strDownAddUrl; ?> 
        <input type="radio" name="addtype" value="addfile" onClick="document.getElementById('divurl').style.display='none';document.getElementById('divsuo').style.display='inline';">
        <?php echo $strDownAddUpload; ?> 
      </td>
    </tr>
	</table>
	<div id="proplist">
	
	</div>
	<table width="100%"   border="0" align="center"  cellpadding="2" cellspacing="0" >
    <tr>
      <td width="100" height="30" align="center" ><?php echo $strMemo; ?></td>
      <td height="30" ><textarea name="memo" style="WIDTH: 499px;font-size:12px;" class="textarea" rows="3"></textarea>
      </td>
    </tr>
	

	<tr>
      <td height="30" width="100" align="center" ><?php echo $strDownAddCon; ?></td>
      <td height="30" > 
         <input type="hidden" name="body" value="<?php echo $body; ?>" />
			<script type="text/javascript" src="../../kedit/KindEditor.js"></script>
            <script type="text/javascript">
            var editor = new KindEditor("editor");
            editor.hiddenName = "body";
            editor.editorWidth = "680px";
            editor.editorHeight = "300px";
            editor.skinPath = "../../kedit/skins/default/";
			editor.uploadPath = "../../kedit/upload_cgi/upload.php";
			editor.imageAttachPath="down/pics/";
            editor.iconPath = "../../kedit/icons/";
            editor.show();
            function KindSubmit() {
	          editor.data();
            }
             </script>
        <input type="hidden" name="act" value="downadd">
        <input type="hidden" name="pid" value="<?php echo $pid; ?>">
		<input type="hidden" id="nowid"  value="" />
      </td>
    </tr>
   
    <tr>
      <td height="30" align="center" ><?php echo $strDownTag; ?></td>
      <td height="30" >
	  <input name="tags[]" type="text" class="input" id="tags"  value="" size="10" />
        <input name="tags[]" type="text" class="input" id="tags"  value="" size="10" />
        <input name="tags[]" type="text" class="input" id="tags"  value="" size="10" />
        <input name="tags[]" type="text" class="input" id="tags"  value="" size="10" />
        <input name="tags[]" type="text" class="input" id="tags"  value="" size="10" /></td>
    </tr>
    
	<tr> 
      <td height="30" width="100" align="center" ><?php echo $strDownAddProj; ?></td>
      <td height="30" ><?php

			$catstr.="<SCRIPT language=javascript src='js/multicat.js'></SCRIPT>";
			$catstr.="<table cellspacing=0 cellpadding=0><tr><td ><select style='WIDTH: 239px;font-size:12px;' multiple size=5 name=spe_funct>";
			
			$fsql -> query ("select * from {P}_down_proj order by id desc");
			while ($fsql -> next_record ()) {
				$projid = $fsql -> f ("id");
				$project = $fsql -> f ("project");

				$NowPath=fmpath($projid);
				
				$catstr.="<option value=".$NowPath.">".$project."</option>";
				$ppcat="";
				
				
			}

		$catstr.="</select></td><td width=20>
<input style='width:20px;height=37px;font-size:12px;border:1px outset;' onClick=\"JavaScript:AddItem('spe_funct', 'spe_selec[]')\" type=button value='+' name='Input'>
<input style='width:20px;height=37px;font-size:12px;border:1px outset;' onClick=\"JavaScript:DelItem('spe_selec[]')\" type=button value='-' name='Input'>
				</td>
				<td>
				  <select  style='WIDTH: 239px;font-size:12px' multiple size=5 name=spe_selec[]>";
	
				
		$catstr.="</select></td><td valign=bottom></td><td width=20 align=center  valign='bottom'></td></tr></table>";
		echo $catstr;
?></td>
    </tr>
  
   <tr>
	  <td width="100" height="30" align="center" ><?php echo $strDownCent; ?></td>
	  <td height="30" >
	  <?php
	  	//积分名称
		$msql->query("select * from {P}_member_centset");
		if($msql->next_record()){
		$centname1=$msql->f('centname1');
		$centname2=$msql->f('centname2');
		$centname3=$msql->f('centname3');
		$centname4=$msql->f('centname4');
		$centname5=$msql->f('centname5');
		}

	  ?>
	  <select name="downcentid">
	    <option value="1"><?php echo $centname1; ?></option>
		<option value="2"><?php echo $centname2; ?></option>
		<option value="3"><?php echo $centname3; ?></option>
		<option value="4"><?php echo $centname4; ?></option>
		<option value="5"><?php echo $centname5; ?></option>
	    </select>
		<input name="downcent" type="text" class="input" id="downcent"  value="0" size="3" maxlength="5" />
	  
	  </td>
	  </tr>

</table>
</div>
<div class="adminsubmit">
<input type="submit" name="cc"  onClick="KindSubmit();" value="<?php echo $strSubmit; ?>" class="button" />
<input type="hidden" name="author"  value="<?php echo $_COOKIE['SYSNAME']; ?>" />
<input type="hidden" name="source"  value="" />

</div>

</div>
</form>
<script>
$().getPropList();
</script>
</body>
</html>
