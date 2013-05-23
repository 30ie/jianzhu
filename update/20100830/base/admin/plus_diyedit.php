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
<?php echo $strDiyEdit; ?>
</div>
<div class="tablezone">
<?php
$step=$_REQUEST["step"];
$nowplusid=$_REQUEST["nowplusid"];

if($step=="2"){
	$body=$_POST["body"];
	$updatemode=$_POST["updatemode"];
	$ids=$_POST["ids"];
	
	if(strlen($body)>65000){
		err($strDiyNotice3,"","");
		exit;
	}
	$body=Url2Path($body);
	
	$msql->query("update {P}_base_plus set `body`='$body' where `pluslable`='modEdit' and id='$nowplusid'");
	
	if($updatemode=="all"){
		
		$nums=sizeof($ids);
		for($i=0;$i<$nums;$i++){
			$idall=$ids[$i];
			$msql->query("update {P}_base_plus set `body`='$body' where `pluslable`='modEdit' and id='$idall'");
		}
	}
	
	SayOk($strDiyNotice5,"plus_diyedit.php?nowplusid=".$nowplusid,"");
	exit;
}

//空时取第一个
if($nowplusid==""){
	$fsql->query("select id from {P}_base_plus where `pluslable`='modEdit' order by plustype limit 0,1");
	if($fsql->next_record()){
		$nowplusid=$fsql->f('id');
	}
}

?>

  <form  name="selpluslocat" method="get" action="" >
	  <select name="pp" onchange="self.location=this.options[this.selectedIndex].value" >
        <?php
		  
			$msql->query("select * from {P}_base_plus where `pluslable`='modEdit' order by plustype");
			while($msql->next_record()){
				$plusid=$msql->f('id');
				$plustype=$msql->f('plustype');
				$pluslocat=$msql->f('pluslocat');
				$plustitle=$msql->f('title');
				
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
					echo "<option value='plus_diyedit.php?nowplusid=".$plusid."' selected>".$colname." |- ".$pagecname." |- ".$plustitle."</option>";
				}else{
					echo "<option value='plus_diyedit.php?nowplusid=".$plusid."'>".$colname." |- ".$pagecname." |- ".$plustitle."</option>";
				}
						
			}
					
		 ?>
      </select>
	  <br /><br />
</form>
	
	<?php
		$msql->query("select * from {P}_base_plus where `id`='$nowplusid' limit 0,1");
		if($msql->next_record()){
				$body=$msql->f('body');
				$title=$msql->f('title');
				$width=$msql->f('width');
				$height=$msql->f('height');
				
		}else{
			err($strDiyEditNotice1,"","");
		}
		
		//取出相同插件id
		$commonnums=0;
		$msql->query("select id from {P}_base_plus where `id`!='$nowplusid' and `title`='$title' and `width`='$width' and `height`='$height' and `body`='$body'");
		while($msql->next_record()){
			$ids=$msql->f('id');
			$idstr.="<input type='hidden' name='ids[]' value='".$ids."' />";
			$commonnums++;
		}

		$body=htmlspecialchars($body);
		$body=Path2Url($body);
		
	?>
	<form  method="post" action="plus_diyedit.php" enctype="multipart/form-data" name="form" id="modiEditForm">
	
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
              
			  <?php
			  if($commonnums>0){
			  	echo "&nbsp; <input name='updatemode' type='checkbox' id='updatemode' value='all' checked='checked' /> ".$idstr.$strDiyNotice2.$commonnums.$strDiyNotice21;
			  }
			  ?>
      </span>            
	</form>
</div>
</div>
</body>
</html>
