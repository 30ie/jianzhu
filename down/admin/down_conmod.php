<?php
define("ROOTPATH", "../../");
include(ROOTPATH."includes/admin.inc.php");
include("language/".$sLan.".php");
NeedAuth(166);
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
    
<?php

$id=$_REQUEST["id"];
$pid=$_REQUEST["pid"];

$msql->query("select * from {P}_down_con where  id='$id'");
if($msql->next_record()){
$id=$msql->f('id');
$catid=$msql->f('catid');
$body=$msql->f('body');
$title=$msql->f('title');
$memo=$msql->f('memo');
$xuhao=$msql->f('xuhao');
$cl=$msql->f('cl');
$tj=$msql->f('tj');
$ifnew=$msql->f('ifnew');
$ifred=$msql->f('ifred');
$iffb=$msql->f('iffb');
$src=$msql->f('src');
$type=$msql->f('type');
$author=$msql->f('author');
$source=$msql->f('source');
$secure=$msql->f('secure');
$oldproj=$msql->f('proj');
$oldcatid=$msql->f('catid');
$oldcatpath=$msql->f('catpath');
$fileurl=$msql->f('fileurl');
$tags=$msql->f('tags');
$downcent=$msql->f('downcent');
$downcentid=$msql->f('downcentid');

$dtime=date("Y-m-d H:i:s",$msql->f('dtime'));
$uptime=date("Y-m-d H:i:s",$msql->f('uptime'));

$tags=explode(",",$tags);

$body=htmlspecialchars($body);
$body=Path2Url($body);
}

?> 

<form id="downForm" name="form" action="" method="post" enctype="multipart/form-data">
<div class="formzone">
<div class="namezone">
<?php echo $strDownModify; ?>
</div>
<div class="tablezone">
<div  id="notice" class="noticediv"></div>
      <table class="downmodizone" width="100%" cellpadding="2" align="center"  border="0" cellspacing="0">
         
		  <tr> 
            <td height="30" width="100" align="center" ><?php echo $strDownCatTitle; ?></td>
            <td height="30" > 
              <select id="selcatid" name="catid" >
                <?php		
				
					//非公共区域
					if($catid=="0"){
						echo "<option value='0' selected>".$strPersonDown."</option>";
					}
				

					$fsql -> query ("select * from {P}_down_cat order by catpath");
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
							
							if($catid==$lcatid){
								echo "<option value='".$lcatid."' selected>".$ppcat.$cat."</option>";
							}else{
								echo "<option value='".$lcatid."'>".$ppcat.$cat."</option>";
							}
							$ppcat="";
						}
						
					
				
?> 
              </select>
              <font color="#FF0000">*</font> 
			  
              
            </td>
          </tr>
	  
          <tr> 
            <td height="30" width="100" align="center" ><?php echo $strDownAddTitle; ?></td>
            <td height="30" > 
              <input type="text" id="title" name="title" style='WIDTH: 499px;' maxlength="200" class="input" value="<?php echo $title; ?>" />
              <font color="#FF0000">*</font> </td>
          </tr>
		   <tr> 
      <td height="30" width="100" align="center" ><?php echo $strDownFileDown; ?></td>
      <td height="30" valign=top> 
        
          <input id="divsuo" type="file" name="file" style='WIDTH: 499px;display:none' class="input" />
          <input id="divurl" type="text" name="fileurl" style='WIDTH: 499px;' value="<?php echo $fileurl; ?>" class="input" />
          <input type="radio" name="addtype" value="addurl" checked onClick="document.getElementById('divurl').style.display='inline';document.getElementById('divsuo').style.display='none';">
        <?php echo $strDownAddUrl; ?> 
        <input type="radio" name="addtype" value="addfile" onClick="document.getElementById('divurl').style.display='none';document.getElementById('divsuo').style.display='inline';">
        <?php echo $strDownAddUpload; ?> 
      </td>
    </tr>
      </table>
	<div id="proplist" class="downmodizone">
	
	</div>
	<table class="downmodizone" width="100%"   border="0" align="center"  cellpadding="2" cellspacing="0" >
          <tr>
            <td width="100" height="30" align="center" ><?php echo $strMemo; ?></td>
            <td height="30" ><textarea name="memo" style="WIDTH: 499px;" class="textarea" rows="3"><?php echo $memo; ?></textarea>
            </td>
          </tr>
          
		  
	  </table>
		  <table  width="100%"   border="0" align="center"  cellpadding="2" cellspacing="0" >
		  <tr> 
            <td height="30" width="100" align="center" ><?php echo $strDownAddCon; ?></td>
            <td height="30" > 
             <input id="downbody" type="hidden" name="body" value="<?php echo $body; ?>" />
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
              <input type="hidden" id="act" name="act" value="downmodify" />
              <input type="hidden" id="nowid" name="id" value="<?php echo $id; ?>" />
              <input type="hidden" name="oldcatid" value="<?php echo $oldcatid; ?>" />
              <input type="hidden" name="oldcatpath" value="<?php echo $oldcatpath; ?>" />
              <input type="hidden" name="pid" value="<?php echo $pid; ?>" />
              <input type="hidden" name="page" value="<?php echo $page; ?>" />
            </td>
          </tr>
		    <tr>
		    <td height="30" align="center" >&nbsp;</td>
		    <td height="30" ><div id="downpages"></div>
			</td>
	    </tr>
		 </table>
		 
         <table class="downmodizone" width="100%"   border="0" align="center"  cellpadding="2" cellspacing="0" >

		  <tr>
      <td height="30" align="center" ><?php echo $strDownTag; ?></td>
      <td height="30" >
	  	<input name="tags[]" type="text" class="input" id="tags"  value="<?php echo $tags[0]; ?>" size="10" />
        <input name="tags[]" type="text" class="input" id="tags"  value="<?php echo $tags[1]; ?>" size="10" />
        <input name="tags[]" type="text" class="input" id="tags"  value="<?php echo $tags[2]; ?>" size="10" />
        <input name="tags[]" type="text" class="input" id="tags"  value="<?php echo $tags[3]; ?>" size="10" />
        <input name="tags[]" type="text" class="input" id="tags"  value="<?php echo $tags[4]; ?>" size="10" /></td>
    </tr>
		  <tr> 
            <td height="30" width="100" align="center" ><?php echo $strDownAddProj; ?></td>
            <td height="30" ><?php
			$catstr.="<SCRIPT language=javascript src='js/multicat.js'></SCRIPT>";
			$catstr.="<table cellspacing=0 cellpadding=0><tr><td ><select style='WIDTH: 239px;' multiple size=5 name=spe_funct>";
			$fsql -> query ("select * from {P}_down_proj order by id desc");
			while ($fsql -> next_record ()) {
				$projid = $fsql -> f ("id");
				$project = $fsql -> f ("project");
				$NowPath=fmpath($projid);
				$catstr.="<option value=".$NowPath.">".$project."</option>";
				$ppcat="";
			}

$catstr.="</select></td><td width=20>
<input style='width:20px;height=37px;border:1px outset;' onClick=\"JavaScript:AddItem('spe_funct', 'spe_selec[]')\" type=button value='+' name='Input'>
<input style='width:20px;height=37px;border:1px outset;' onClick=\"JavaScript:DelItem('spe_selec[]')\" type=button value='-' name='Input'>
				</td>
				<td>
				  <select  style='WIDTH: 239px;font-size:12px' multiple size='5' name='spe_selec[]'>";
					$arrs = explode (":",$oldproj);
					for ($k = 0; $k < sizeof ($arrs)-1; $k ++) {
						$projid=$arrs[$k]+0;
						$tsql->query("select project from {P}_down_proj where id='$projid'");
						if($tsql->next_record()){
							$project=$tsql->f('project');
						}
						$catstr.="<option value=".$arrs[$k].">".$project."</option>";
					}
		$catstr.="</select></td><td valign=bottom></td><td width=20 align=center  valign=bottom></td></tr></table>";
		echo $catstr;
?> </td>
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
	    <option value="1" <?php echo seld($downcentid,"1"); ?>><?php echo $centname1; ?></option>
		<option value="2" <?php echo seld($downcentid,"2"); ?>><?php echo $centname2; ?></option>
		<option value="3" <?php echo seld($downcentid,"3"); ?>><?php echo $centname3; ?></option>
		<option value="4" <?php echo seld($downcentid,"4"); ?>><?php echo $centname4; ?></option>
		<option value="5" <?php echo seld($downcentid,"5"); ?>><?php echo $centname5; ?></option>
	    </select>
		<input name="downcent" type="text" class="input" id="downcent"  value="<?php echo $downcent; ?>" size="3" maxlength="5" />
	  
	  </td>
	  </tr>
          <tr> 
            <td height="30" width="100" align="center" ><?php echo $strFbtime; ?></td>
            <td height="30" ><?php echo $dtime; ?></td>
          </tr>
          <tr> 
            <td height="30" width="100" align="center" ><?php echo $strUptime; ?></td>
            <td height="30" ><?php echo $uptime; ?> </td>
          </tr>
          
          
        
      </table>
	 
</div>  
<div class="adminsubmit">
<input id="adminsubmit" type="submit" name="modi"  onClick="KindSubmit();" value="<?php echo $strSave; ?>" class="button" />
<input type="hidden" name="source" value="<?php echo $source; ?>" />
<input type="hidden" name="author"  value="<?php echo $author; ?>" />

</div> 
</div>
</form>
<script>
$().getPropList();
$().getDownPages(0);
</script>
</body>
</html>
