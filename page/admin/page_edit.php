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

$id=$_REQUEST["id"];
$step=$_REQUEST["step"];
$groupid=$_REQUEST["groupid"];


if($step=="2"){

	$id=$_POST["id"];
	$title=htmlspecialchars($_POST["title"]);
	$pagefolder=htmlspecialchars($_POST["pagefolder"]);
	$old_pagefolder=htmlspecialchars($_POST["old_pagefolder"]);
	$old_groupid=$_POST["old_groupid"];
	$url=htmlspecialchars($_POST["url"]);
	$memo=htmlspecialchars($_POST["memo"]);
	$body=$_POST["body"];
	$xuhao=$_POST["xuhao"];
	$pic=$_FILES["jpg"];

		
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

	$body=Url2Path($body);
	
	//过滤同时修改分组和网页文件名
	if($groupid!=$old_groupid && $pagefolder!=$old_pagefolder){
		err($strHtmNotice14,"","");
	}
	
	
	
	
	//更新图片
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
			$msql->query("select src from {P}_page where id='$id'");
			if($msql->next_record()){
				$oldsrc=$msql->f('src');
			}
			if(file_exists(ROOTPATH.$oldsrc) && $oldsrc!="" && !strstr($oldsrc,"../")){
				unlink(ROOTPATH.$oldsrc);
			}
			$msql->query("update {P}_page set src='$src'  where id='$id'");
	}
	
	
	//处理独立排版的网页文件-修改的情况
	if($pagefolder!="" && $old_pagefolder!=$pagefolder){
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
		$fsql->query("select id from {P}_page where groupid='$groupid' and pagefolder='$pagefolder' and id!='$id'");
		if($fsql->next_record()){
			err($strHtmNotice13,"","");
		}
		
		//获取组目录名
		$fsql->query("select folder from {P}_page_group where id='$groupid'");
		if($fsql->next_record()){
			$folder=$fsql->f('folder');
		}

		
		//创建新文件
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
		
		//删除旧文件
		@unlink("../".$folder."/".$old_pagefolder.".php");

		//添加或修改页面记录
		$oldpagename=$folder."_".$old_pagefolder;
		if($old_pagefolder==""){
			$msql->query("insert into {P}_base_pageset set 
			`name`='$title',
			`coltype`='page',
			`pagename`='$pagename',
			`buildhtml`='0'
			");
		}else{
			$msql->query("update {P}_base_pageset set pagename='$pagename' where coltype='page' and pagename='$oldpagename'");
		}
		
		//修改页面插件记录,插件方案记录
		$msql->query("update {P}_base_plus set pluslocat='$pagename' where plustype='page' and pluslocat='$oldpagename'");
		$msql->query("update {P}_base_plusplan set pluslocat='$pagename' where plustype='page' and pluslocat='$oldpagename'");
	}
	
	
	//处理独立排版的网页文件-清空文件名即删除文件的情况
	if($old_pagefolder!="" && $pagefolder==""){
	
		//获取组目录名
		$fsql->query("select folder from {P}_page_group where id='$groupid'");
		if($fsql->next_record()){
			$folder=$fsql->f('folder');
		}
		//删除文件及记录
		@unlink("../".$folder."/".$old_pagefolder.".php");
		$oldpagename=$folder."_".$old_pagefolder;
		$msql->query("delete from {P}_base_pageset where coltype='page' and pagename='$oldpagename'");
		$msql->query("delete from {P}_base_plus where plustype='page' and pluslocat='$oldpagename'");
		$msql->query("delete from {P}_base_plusplan where plustype='page' and pluslocat='$oldpagename'");
		
		
	}
	
	//更换分组时文件名处理
	if($groupid!=$old_groupid && $pagefolder==$old_pagefolder && $pagefolder!=""){
		//获取组目录名
		$fsql->query("select folder from {P}_page_group where id='$groupid'");
		if($fsql->next_record()){
			$folder=$fsql->f('folder');
		}
		//获取旧的组目录名
		$fsql->query("select folder from {P}_page_group where id='$old_groupid'");
		if($fsql->next_record()){
			$oldfolder=$fsql->f('folder');
		}
		$filename="../".$folder."/".$pagefolder.".php";
		$oldfilename="../".$oldfolder."/".$pagefolder.".php";
		
		
		//创建新文件
		$pagename=$folder."_".$pagefolder;
		$fd=fopen("../temp.php","r");
		$str=fread($fd,"2000");
		$str=str_replace("TEMP",$pagename,$str);
		fclose($fd);

		$fp=fopen($filename,"w");
		fwrite($fp,$str);
		fclose($fp);

		@chmod($filename,0755);
		
		//删除旧文件
		@unlink($oldfilename);
		
		
		//处理页面记录
		$oldpagename=$oldfolder."_".$pagefolder;
		
		$msql->query("update {P}_base_pageset set pagename='$pagename' where coltype='page' and pagename='$oldpagename'");
		$msql->query("update {P}_base_plus set pluslocat='$pagename' where plustype='page' and pluslocat='$oldpagename'");
		$msql->query("update {P}_base_plusplan set pluslocat='$pagename' where plustype='page' and pluslocat='$oldpagename'");
	}
	
	
	
	//更新数据

	$msql->query("update {P}_page set 
			title='$title',
			xuhao='$xuhao',
			memo='$memo',
			url='$url',
			groupid='$groupid',
			pagefolder='$pagefolder',
			body='$body'
			where id='$id'
	");
	
	


	SayOk($strHtmNotice6,"page.php?groupid=".$groupid,"");
}


?> 

    
<?php
$msql->query("select * from {P}_page where  id='$id'");
if($msql->next_record()){
$id=$msql->f('id');
$body=$msql->f('body');
$title=$msql->f('title');
$xuhao=$msql->f('xuhao');
$groupid=$msql->f('groupid');
$pagefolder=$msql->f('pagefolder');
$url=$msql->f('url');
$memo=$msql->f('memo');
}

$body=htmlspecialchars($body);
$body=Path2Url($body);

if($pagefolder==""){
	$showtr="style='display:none'";
	$modiselmodle="0";
}else{
	$showtr="";
	$modiselmodle="1";
}

?> 

<form  method="post" action="page_edit.php" enctype="multipart/form-data" name="form" id="modiPageForm">
<div class="formzone">
<div class="namezone">
<?php echo $strHtmEdit; ?>
</div>
<div class="tablezone">
  

      <table width="100%" cellpadding="2" align="center"  style="border-collapse: collapse" border="0" cellspacing="0">
        <tr>
          <td height="30" align="center" ><?php echo $strIdx; ?></td>
          <td height="30" ><input type="text" name="xuhao" style="width:25px" value="<?php echo $xuhao; ?>" class="input" maxlength="9" />
          </td>
        </tr>
          <tr>
            <td height="30" align="center" ><?php echo $strGroupSel1; ?></td>
            <td height="30" > <select name="groupid" id="groupid">
         
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
            <td height="30" ><select name="modiselmodle" id="modiselmodle">
              <option value="1" <?php echo seld($modiselmodle,"1"); ?>><?php echo $strPageFolderS2; ?></option>
              <option value="0" <?php echo seld($modiselmodle,"0"); ?>><?php echo $strPageFolderS1; ?></option>
            </select></td>
          </tr>
          <tr id="tr_fold" <?php echo $showtr; ?>>
            <td height="30" align="center" ><?php echo $strPagePbName; ?></td>
            <td height="30" ><input name="pagefolder" type="text" class="input" id="pagefolder" value="<?php echo $pagefolder; ?>" size="20"  maxlength="30" />
.PHP</td>
          </tr>
         
          <tr> 
            <td height="30" width="100" align="center" ><?php echo $strPageTitle; ?></td>
            <td height="30" > 
              <input name="title" id="title" type="text" class="input" value="<?php echo $title; ?>" size="36"  maxlength="200" />
              <font color="#FF0000">*</font> </td>
          </tr>
		  
 			<tr>
            <td height="30" align="center" ><?php echo $strPagePicSrc; ?></td>
            <td height="30" ><input name="jpg" type="file" id="jpg" size="50" class="input" /></td>
          </tr>
		 
		  <tr> 
            <td height="30" width="100" align="center" ><?php echo $strPageCon; ?></td>
            <td height="30" > 
             <input type="hidden" name="body" value="<?php echo $body; ?>" />
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
              <input type="hidden" name="step" value="2" />
              <input type="hidden" name="id" value="<?php echo $id; ?>" />
              <input name="old_groupid" type="hidden" id="old_groupid" value="<?php echo $groupid; ?>" />
              <input name="old_pagefolder" type="hidden" id="old_pagefolder" value="<?php echo $pagefolder; ?>" /></td>
          </tr>
		  <tr>
            <td height="30" align="center" ><?php echo $strPageMemo; ?></td>
            <td height="30" ><textarea name="memo" rows="3" class="textarea" id="memo" style="width:500px"><?php echo $memo; ?></textarea>
            </td>
	    </tr>
		  <tr>
            <td height="30" align="center" ><?php echo $strPageToUrl; ?></td>
            <td height="30" ><input name="url" type="text" class="input" id="url" value="<?php echo $url; ?>" style="width:500px"  maxlength="200" />            </td>
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
