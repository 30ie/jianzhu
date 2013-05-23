<?php
define("ROOTPATH", "../../");
include(ROOTPATH."includes/admin.inc.php");
include("language/".$sLan.".php");
NeedAuth(0);
 		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head >
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link  href="css/style.css" type="text/css" rel="stylesheet">
<title><?php echo $strAdminTitle; ?></title>

</head>
<body>

<div class="formzone">
<div class="rightzone">

</div>
<div class="namezone">
<?php echo $strDiyBottomEdit; ?>
</div>
<div class="tablezone">
<?php
$step=$_REQUEST["step"];
$nowplusid=$_REQUEST["nowplusid"];

if($step=="2"){
	$updatemode=$_POST["updatemode"];
	$body=$_POST["body"];
	
	if(strlen($body)>65000){
		err($strDiyBottomNotice3,"","");
		exit;
	}
	$body=Url2Path($body);
	
	if($updatemode=="all"){
		$msql->query("update {P}_base_plus set `body`='$body' where `pluslable`='modButtomInfo'");
		SayOk($strDiyBottomNotice4,"plus_bottomedit.php","");
		exit;
	}else{
		$msql->query("update {P}_base_plus set `body`='$body' where `pluslable`='modButtomInfo' and id='$nowplusid'");
		SayOk($strDiyBottomNotice5,"plus_bottomedit.php?nowplusid=".$nowplusid,"");
		exit;
	}
}


//空时取首页id
if($nowplusid==""){
	$msql->query("select id from {P}_base_plus where `pluslable`='modButtomInfo' and `plustype`='index' and `pluslocat`='index'  limit 0,1");
	if($msql->next_record()){
		$nowplusid=$msql->f('id');
	}else{
		$fsql->query("select id from {P}_base_plus where `pluslable`='modButtomInfo' order by plustype limit 0,1");
		if($fsql->next_record()){
			$nowplusid=$fsql->f('id');
		}

	}
}

?>

  <form  name="selpluslocat" method="get" action="" >
	  <select name="pp" onchange="self.location=this.options[this.selectedIndex].value" >
        <?php
		  
			$msql->query("select * from {P}_base_plus where `pluslable`='modButtomInfo' order by plustype");
			while($msql->next_record()){
				$plusid=$msql->f('id');
				$plustype=$msql->f('plustype');
				$pluslocat=$msql->f('pluslocat');
				
				//获取对应模块名称
				$fsql->query("select `colname` from {P}_base_coltype where `coltype`='$plustype' limit 0,1");
				if($fsql->next_record()){
					$colname=$fsql->f('colname');
				}
				
				//获取对应页面名称
				$fsql->query("select `name` from {P}_base_pageset where `coltype`='$plustype' and `pagename`='$pluslocat' limit 0,1");
				if($fsql->next_record()){
					$pagecname=$fsql->f('name');
				}
				
				if($nowplusid==$plusid){
					echo "<option value='plus_bottomedit.php?nowplusid=".$plusid."' selected>".$colname." |- ".$pagecname."</option>";
				}else{
					echo "<option value='plus_bottomedit.php?nowplusid=".$plusid."'>".$colname." |- ".$pagecname."</option>";
				}
						
			}
					
		 ?>
      </select>
	  <br /><br />
</form>
	
	<?php
		$msql->query("select `body` from {P}_base_plus where `id`='$nowplusid' limit 0,1");
			if($msql->next_record()){
				$body=$msql->f('body');
				$body=htmlspecialchars($body);
				$body=Path2Url($body);
			}else{
				err($strDiyBottomNotice1,"","");
			}
	?>
	<form  method="post" action="plus_bottomedit.php" enctype="multipart/form-data" name="form" id="modiEditForm">
	
	<input type="hidden" name="body" value="<?php echo $body; ?>" />
			 <script type="text/javascript" src="../../kedit/KindEditor.js"></script>
            <script type="text/javascript">
            var editor = new KindEditor("editor");
            editor.hiddenName = "body";
            editor.editorWidth = "780px";
            editor.editorHeight = "250px";
            editor.skinPath = "../../kedit/skins/default/";
			editor.uploadPath = "../../kedit/upload_cgi/upload.php";
			editor.imageAttachPath="diy/pics/";
            editor.iconPath = "../../kedit/icons/";
            editor.show();
            function KindSubmit() {
	          editor.data();
            }
             </script>
              <input type="hidden" name="step" value="2" />
              <input type="hidden" name="nowplusid" value="<?php echo $nowplusid; ?>" />
              <br />
              <span class="adminsubmit">
              <input type="submit" name="submit"  onclick="KindSubmit();" value="<?php echo $strSubmit; ?>" class="button"  />
              <input name="updatemode" type="checkbox" id="updatemode" value="all" checked="checked" />
      <?php echo $strDiyBottomEdit2; ?> </span>            
	</form>
</div>
</div>
</body>
</html>
