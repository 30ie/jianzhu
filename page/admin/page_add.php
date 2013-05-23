<?php
define("ROOTPATH", "../../");
include(ROOTPATH."includes/admin.inc.php");
include("language/".$sLan.".php");
include("func/upload.inc.php");

NeedAuth(301);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head >
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link  href="css/style.css" type="text/css" rel="stylesheet">
<title><?php echo $strAdminTitle; ?></title>
<script type="text/javascript" src="../../base/js/base.js"></script>
<script type="text/javascript" src="js/page.js"></script>
</head>

<body >
<?php 

$step=$_REQUEST["step"];
$groupid=$_REQUEST["groupid"];


if($step=="add"){

	$title=htmlspecialchars($_POST["title"]);
	$pagefolder=htmlspecialchars($_POST["pagefolder"]);
	$url=htmlspecialchars($_POST["url"]);
	$memo=htmlspecialchars($_POST["memo"]);
	$body=$_POST["body"];
	$pic=$_FILES["jpg"];
	
	$body=Url2Path($body);
		
	//校验处理

	if($title==""){
		err($strHtmNotice1,"","");
	}
	if(strlen($title)>200){
		err($strHtmNotice2,"","");
	}

	if(strlen($body)>65000){
		err($strHtmNotice3,"","");
	}
	
	//图片上传
	if($pic["size"]>0){
			$nowdate=date("Ymd",time());
			$picpath="../pics/".$nowdate;
			@mkdir($picpath,0777);
			$uppath="page/pics/".$nowdate;
			$arr=NewUploadImage($pic["tmp_name"],$pic["type"],$pic["size"],$uppath);
			if($arr[0]!="err"){
				$src=$arr[3];
			}else{
				err($arr[1],"","");
			}
	}else{
		$src="";
	}
	
	//文件名校验
	if($pagefolder!=""){
		if(strlen($pagefolder)<1 || strlen($pagefolder)>16){
			err($strHtmNotice11,"","");
		}
		if (!eregi("^[0-9a-z]{0,16}$",$pagefolder)) { 
			err($strHtmNotice11,"","");
		}
		if(strstr($pagefolder,"/") || strstr($pagefolder,".")){
			err($strHtmNotice11,"","");
		} 
		
		//保护文件名
		$arr = array('index','main','default','detail','admin','images','includes','language','module','page','templates','js','css');
		if (in_array($pagefolder, $arr)==true) {
			err($strHtmNotice12,"","");
		}
		
		//同组同文件名
		$fsql->query("select id from {P}_page where groupid='$groupid' and pagefolder='$pagefolder'");
		if($fsql->next_record()){
			err($strHtmNotice13,"","");
		}
		
		//获取组目录名
		$fsql->query("select folder from {P}_page_group where id='$groupid'");
		if($fsql->next_record()){
			$folder=$fsql->f('folder');
		}

		
		//创建文件
		$pagename=$folder."_".$pagefolder;
		$fd=fopen("../temp.php","r");
		$str=fread($fd,"2000");
		$str=str_replace("TEMP",$pagename,$str);
		fclose($fd);

		$filename="../".$folder."/".$pagefolder.".php";
		$fp=fopen($filename,"w");
		fwrite($fp,$str);
		fclose($fp);

		@chmod($filename,0755);
		
		//添加页面记录
		$msql->query("insert into {P}_base_pageset set 
			`name`='$title',
			`coltype`='page',
			`pagename`='$pagename',
			`buildhtml`='0'
		");
		
		

	}
	
	

	
	$msql->query("select max(xuhao) from {P}_page where groupid='$groupid'");
	if($msql->next_record()){
		$newxuhao=$msql->f('max(xuhao)')+1;
	}

	//更新数据

	$msql->query("insert into {P}_page set 
	groupid='$groupid',
	title='$title',
	memo='$memo',
	xuhao='$newxuhao',
	pagefolder='$pagefolder',
	url='$url',
	src='$src',
	body='$body'
	");


	SayOk($strHtmNotice7,"page.php?groupid=".$groupid,"");
}


?> 

<?php
$msql->query("select max(id) from {P}_page");
	if($msql->next_record()){
	$ftempname=$msql->f('max(id)');
}

?>
<form action="page_add.php" method="post" enctype="multipart/form-data" name="form" id="addPageForm">
<div class="formzone">
<div class="namezone">
<?php echo $strHtmAdd; ?>
</div>
<div class="tablezone">
  

      <table width="100%" cellpadding="2" align="center"  style="border-collapse: collapse" border="0" cellspacing="0">
        <tr>
          <td height="30" align="center" ><?php echo $strGroupSel1; ?></td>
          <td height="30" ><select name="groupid">
              <?php
				
			$msql->query("select * from {P}_page_group");
			while($msql->next_record()){
				$lgroupid=$msql->f('id');
				$groupname=$msql->f('groupname');
					
				if($groupid==$lgroupid){
					echo "<option value='".$lgroupid."' selected>".$groupname."</option>";
				}else{
					echo "<option value='".$lgroupid."'>".$groupname."</option>";
				}
						
			}
					
				
		 ?>
          </select></td>
        </tr>
        <tr>
          <td height="30" align="center" ><?php echo $strPagePbModle; ?></td>
          <td height="30" >            <select name="addselmodle" id="addselmodle">
            <option value="1"><?php echo $strPageFolderS2; ?></option>
            <option value="0"><?php echo $strPageFolderS1; ?></option>
          </select></td>
        </tr>
        <tr id="tr_fold">
          <td height="30" align="center" ><?php echo $strPagePbName; ?></td>
          <td height="30" ><input name="pagefolder" type="text" class="input" id="pagefolder"  value="<?php echo $ftempname; ?>" size="20"  maxlength="30" />
.PHP</td>
        </tr>
         
          <tr> 
            <td height="30" width="100" align="center" ><?php echo $strPageTitle; ?></td>
            <td height="30" > 
              <input name="title" id="title" type="text" class="input" value="" size="51"  maxlength="200" />
              <font color="#FF0000">*</font> </td>
          </tr>
		  
		   <tr>
            <td height="30" align="center" ><?php echo $strPagePicSrc; ?></td>
            <td height="30" ><input name="jpg" type="file" id="jpg" size="50" class="input" /></td>
          </tr>
		  

		 
		  <tr> 
            <td height="30" width="100" align="center" ><?php echo $strPageCon; ?></td>
            <td height="30" > 
             <input type="hidden" name="body" value="" />
			 <script type="text/javascript" src="../../kedit/KindEditor.js"></script>
            <script type="text/javascript">
            var editor = new KindEditor("editor");
            editor.hiddenName = "body";
            editor.editorWidth = "700px";
            editor.editorHeight = "350px";
            editor.skinPath = "../../kedit/skins/default/";
			editor.uploadPath = "../../kedit/upload_cgi/upload.php";
			editor.imageAttachPath="page/pics/";
            editor.iconPath = "../../kedit/icons/";
            editor.show();
            function KindSubmit() {
	          editor.data();
            }
             </script>
              <input type="hidden" name="step" value="add" />
              <input type="hidden" name="id" value="<?php echo $id; ?>" />
            </td>
          </tr>
		  <tr>
            <td height="30" align="center" ><?php echo $strPageMemo; ?></td>
            <td height="30" ><textarea name="memo" rows="3" class="textarea" id="memo" style="width:500px"><?php echo $memo; ?></textarea>
            </td>
	    </tr>
		  <tr>
            <td height="30" align="center" ><?php echo $strPageToUrl; ?></td>
            <td height="30" ><input name="url" style="width:500px" type="text" class="input" id="url" value="<?php echo $url; ?>"  maxlength="200" />            </td>
	    </tr>
          
          
        
      </table>
	 
</div>  
<div class="adminsubmit">
<input type="submit" name="submit"  onClick="KindSubmit();" value="<?php echo $strSubmit; ?>" class="button" />
</div> 
</div>
</form>
</body>
</html>
