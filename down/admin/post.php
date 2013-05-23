<?php
define("ROOTPATH", "../../");
include(ROOTPATH."includes/admin.inc.php");
include("language/".$sLan.".php");
include("func/upload.inc.php");
NeedAuth(162);

$act=$_POST["act"];

switch($act){
	
	//显示属性列	
	case "proplist" :
		
		$catid=$_POST["catid"];
		$nowid=$_POST["nowid"];

		if($nowid!="" && $nowid!="0"){
			$msql->query("select * from {P}_down_con where  id='$nowid'");
			if($msql->next_record()){
				$prop1=$msql->f('prop1');
				$prop2=$msql->f('prop2');
				$prop3=$msql->f('prop3');
				$prop4=$msql->f('prop4');
				$prop5=$msql->f('prop5');
				$prop6=$msql->f('prop6');
				$prop7=$msql->f('prop7');
				$prop8=$msql->f('prop8');
				$prop9=$msql->f('prop9');
				$prop10=$msql->f('prop10');
				$prop11=$msql->f('prop11');
				$prop12=$msql->f('prop12');
				$prop13=$msql->f('prop13');
				$prop14=$msql->f('prop14');
				$prop15=$msql->f('prop15');
				$prop16=$msql->f('prop16');
			}
		}

		$str="<table width='100%'   border='0' align='center'  cellpadding='2' cellspacing='0' >";
		$i=1;
		$msql->query("select * from {P}_down_prop where catid='$catid' order by xuhao");
		while($msql->next_record()){
		$propname=$msql->f('propname');
		$pn="prop".$i;
			$str.="<tr>"; 
			  $str.="<td width='100' height='30' align='center' >".$propname."</td>";
			  $str.="<td height='30' >"; 
			  $str.="<input type='text' name='".$pn."' value='".$$pn."' class='input' style='width:499px;' />";
			  $str.="</td>";
			  $str.="</tr>";

		$i++;
		}
		$str.="</table>";
		
		echo $str;
		exit;

	break;


	//添加分页
	case "addpage" :
		
		$nowid=$_POST["nowid"];

		$xuhao=0;
		if($nowid!="" && $nowid!="0"){
			$msql->query("select max(xuhao) from {P}_down_pages where downid='$nowid'");
			if($msql->next_record()){
				$xuhao=$msql->f('max(xuhao)');
			}
			$xuhao=$xuhao+1;
			$msql->query("insert into {P}_down_pages set downid='$nowid',xuhao='$xuhao' ");
		}
		echo "OK";
		exit;

	break;

	
	//显示分页
	case "downpageslist" :
		
		$nowid=$_POST["nowid"];
		$pageinit=$_POST["pageinit"];

		$str="<ul>";
		$str.="<li id='p_0' class='pages'>1</li>";

		$i=2;
		$id=0;
		$msql->query("select id from {P}_down_pages where downid='$nowid' order by xuhao");
		while($msql->next_record()){
			$id=$msql->f('id');
			$str.="<li id='p_".$id."' class='pages'>".$i."</li>";
			$i++;
		}
		
		if($pageinit!="new"){
			$id=$pageinit;
		}

		$str.="<li id='addpage' class='addbutton'>".$strDownPagesAdd."</li>";
		if($pageinit!="0"){
			$str.="<li id='pagedelete' class='addbutton'>".$strDownPagesDel."</li>";
			$str.="<li id='backtomodi' class='addbutton'>".$strBack."</li>";
		}
		$str.="<input  type='submit' name='modi'  onClick='KindSubmit();' value='".$strSave."' class='savebutton' />";
		$str.="</ul><input id='downpagesid' name='downpagesid' type='hidden' value='".$id."'>";
		echo $str;
		exit;

	break;


	//获取分页内容
	case "getcontent" :
		
		$nowid=$_POST["nowid"];
		$downpageid=$_POST["downpageid"];

		if($downpageid=="-1"){

			$body="";

		}elseif($downpageid=="0"){
			
			$msql->query("select body from {P}_down_con where id='$nowid'");
			if($msql->next_record()){
				$body=$msql->f('body');
				
			}

		}else{

			$msql->query("select body from {P}_down_pages where id='$downpageid'");
			if($msql->next_record()){
				$body=$msql->f('body');
			}else{
				$body="";
			}

		}


		$body=Path2Url($body);	
		
		echo $body;
		exit;

	break;	



	//修改
	case "downmodify" :

	
		$id=$_POST["id"];
		$pid=$_POST["pid"];
		$catid=$_POST["catid"];
		$page=$_POST["page"];
		$title=htmlspecialchars($_POST["title"]);
		$author=htmlspecialchars($_POST["author"]);
		$source=htmlspecialchars($_POST["source"]);
		$body=$_POST["body"];
		$memo=$_POST["memo"];
		$oldcatid=$_POST["oldcatid"];
		$oldcatpath=$_POST["oldcatpath"];
		$prop1=htmlspecialchars($_POST["prop1"]);
		$prop2=htmlspecialchars($_POST["prop2"]);
		$prop3=htmlspecialchars($_POST["prop3"]);
		$prop4=htmlspecialchars($_POST["prop4"]);
		$prop5=htmlspecialchars($_POST["prop5"]);
		$prop6=htmlspecialchars($_POST["prop6"]);
		$prop7=htmlspecialchars($_POST["prop7"]);
		$prop8=htmlspecialchars($_POST["prop8"]);
		$prop9=htmlspecialchars($_POST["prop9"]);
		$prop10=htmlspecialchars($_POST["prop10"]);
		$prop11=htmlspecialchars($_POST["prop11"]);
		$prop12=htmlspecialchars($_POST["prop12"]);
		$prop13=htmlspecialchars($_POST["prop13"]);
		$prop14=htmlspecialchars($_POST["prop14"]);
		$prop15=htmlspecialchars($_POST["prop15"]);
		$prop16=htmlspecialchars($_POST["prop16"]);
		$prop17=htmlspecialchars($_POST["prop17"]);
		$prop18=htmlspecialchars($_POST["prop18"]);
		$prop19=htmlspecialchars($_POST["prop19"]);
		$prop20=htmlspecialchars($_POST["prop20"]);
		$downcentid=htmlspecialchars($_POST["downcentid"]);
		$downcent=htmlspecialchars($_POST["downcent"]);
		$tags=$_POST["tags"];
		
		$spe_selec=$_POST["spe_selec"];
		$file=$_FILES["file"];
		$fileurl=$_POST["fileurl"];	

		
		if($file["size"]>0){
			$Meta="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		}

			
		//校验 

		$uptime=time();

		if($title==""){
			echo $Meta.$strDownNotice6;
			exit;
		}
		if(strlen($title)>200){
			echo $Meta.$strDownNotice7;
			exit;
		}

		if(strlen($body)>65000){
			echo $Meta.$strDownNotice5;
			exit;
		}



		//路径转换
		$body=Url2Path($body);


		//标签过滤
		$title=str_replace("{#","",$title);
		$title=str_replace("#}","",$title);
		$memo=str_replace("{#","",$memo);
		$memo=str_replace("#}","",$memo);
		$body=str_replace("{#","{ #",$body);
		$body=str_replace("#}","# }",$body);
		
		
		$msql->query("select catpath from {P}_down_cat where catid='$catid'");
		if($msql->next_record()){
			$catpath=$msql->f('catpath');
		}


		$count_pro = count ($spe_selec);
		for ($i = 0; $i < $count_pro; $i ++) {
			$projid = $spe_selec[$i];
			$projpath .= $projid.":";
		}

		if($file["size"]>0){
				$nowdate=date("Ymd",time());
				$picpath="../upload/".$nowdate;
				@mkdir($picpath,0777);
				$uppath="down/upload/".$nowdate;

				$filearr=NewUploadFile($file["tmp_name"],$file["type"],$file["name"],$file["size"],$uppath);
				if($filearr[0]!="err"){
					$fileurl=$filearr[3];
				}else{
					echo $Meta.$filearr[1];
					exit;
				}
				
				
					$msql->query("select fileurl from {P}_down_con where id='$id'");
					if($msql->next_record()){
						$oldfileurl=$msql->f('fileurl');
					}
					if(file_exists(ROOTPATH.$oldfileurl) && $oldfileurl!="" && !strstr($oldfileurl,"../")){
						unlink(ROOTPATH.$oldfileurl);
					}
		}



		for($t=0;$t<sizeof($tags);$t++){
			if($tags[$t]!=""){
				$tagstr.=$tags[$t].",";
			}
		}
		

		$msql->query("update {P}_down_con set 
			title='$title',
			memo='$memo',
			fileurl='$fileurl',
			catid='$catid',
			catpath='$catpath',
			uptime='$uptime',
			author='$author',
			source='$source',
			proj='$projpath',
			tags='$tagstr',
			prop1='$prop1',
			prop2='$prop2',
			prop3='$prop3',
			prop4='$prop4',
			prop5='$prop5',
			prop6='$prop6',
			prop7='$prop7',
			prop8='$prop8',
			prop9='$prop9',
			prop10='$prop10',
			prop11='$prop11',
			prop12='$prop12',
			prop13='$prop13',
			prop14='$prop14',
			prop15='$prop15',
			prop16='$prop16',
			prop17='$prop17',
			prop18='$prop18',
			prop19='$prop19',
			prop20='$prop20',
			downcentid='$downcentid',
			downcent='$downcent',
			body='$body'
			where id='$id'
		");
	
		echo "OK";
		exit;
	
	break;


	//翻页内容修改
	case "contentmodify" :
		$downpagesid=$_POST["downpagesid"];
		$body=$_POST["body"];
		
		if(strlen($body)>65000){
			echo $strDownNotice5;
			exit;
		}

		$body=Url2Path($body);

		$msql->query("update {P}_down_pages set body='$body' where id='$downpagesid'");
		
		echo "OK";
		exit;

	break;


	//下载发布
	case "downadd" :
		
		$catid=$_POST["catid"];
		$body=$_POST["body"];
		$title=htmlspecialchars($_POST["title"]);
		$author=htmlspecialchars($_POST["author"]);
		$source=htmlspecialchars($_POST["source"]);
		$memo=$_POST["memo"];
		$prop1=htmlspecialchars($_POST["prop1"]);
		$prop2=htmlspecialchars($_POST["prop2"]);
		$prop3=htmlspecialchars($_POST["prop3"]);
		$prop4=htmlspecialchars($_POST["prop4"]);
		$prop5=htmlspecialchars($_POST["prop5"]);
		$prop6=htmlspecialchars($_POST["prop6"]);
		$prop7=htmlspecialchars($_POST["prop7"]);
		$prop8=htmlspecialchars($_POST["prop8"]);
		$prop9=htmlspecialchars($_POST["prop9"]);
		$prop10=htmlspecialchars($_POST["prop10"]);
		$prop11=htmlspecialchars($_POST["prop11"]);
		$prop12=htmlspecialchars($_POST["prop12"]);
		$prop13=htmlspecialchars($_POST["prop13"]);
		$prop14=htmlspecialchars($_POST["prop14"]);
		$prop15=htmlspecialchars($_POST["prop15"]);
		$prop16=htmlspecialchars($_POST["prop16"]);
		$prop17=htmlspecialchars($_POST["prop17"]);
		$prop18=htmlspecialchars($_POST["prop18"]);
		$prop19=htmlspecialchars($_POST["prop19"]);
		$prop20=htmlspecialchars($_POST["prop20"]);
		$downcentid=htmlspecialchars($_POST["downcentid"]);
		$downcent=htmlspecialchars($_POST["downcent"]);
		$tags=$_POST["tags"];
		
		$fileurl=$_POST["fileurl"];		
		$file=$_FILES["file"];
		$spe_selec=$_POST["spe_selec"];

		if($file["size"]>0){
			$Meta="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		}


		//校验

		$uptime=time();

		if($title==""){
			echo $Meta.$strDownNotice6;
			exit;
		}
		if(strlen($title)>200){
			echo $Meta.$strDownNotice7;
			exit;
		}

		if(strlen($body)>65000){
			echo $Meta.$strDownNotice5;
			exit;
		}


		$dtime=time();

		$msql->query("select catpath from {P}_down_cat where catid='$catid'");
		if($msql->next_record()){
			$catpath=$msql->f('catpath');
		}

		$body=Url2Path($body);

		//标签过滤
		$title=str_replace("{#","",$title);
		$title=str_replace("#}","",$title);
		$memo=str_replace("{#","",$memo);
		$memo=str_replace("#}","",$memo);
		$body=str_replace("{#","{ #",$body);
		$body=str_replace("#}","# }",$body);

		
		
		//文件
		if($file["size"]>0){
			$nowdate=date("Ymd",time());
			$picpath="../upload/".$nowdate;
			@mkdir($picpath,0777);
			$uppath="down/upload/".$nowdate;
			$filearr=NewUploadFile($file["tmp_name"],$file["type"],$file["name"],$file["size"],$uppath);
			if($filearr[0]!="err"){
				$fileurl=$filearr[3];
			}else{
				echo $Meta.$filearr[1];
				exit;
			}
			
		}


		//专题
		$count_pro = count ($spe_selec);
		for ($i = 0; $i < $count_pro; $i ++) {
			$projid = $spe_selec[$i];
			$projpath .= $projid.":";
		}

		//标签
		for($t=0;$t<sizeof($tags);$t++){
			if($tags[$t]!=""){
				$tagstr.=$tags[$t].",";
			}
		}

		
		$msql->query("insert into {P}_down_con set
		catid='$catid',
		catpath='$catpath',
		title='$title',
		body='$body',
		dtime='$dtime',
		xuhao='0',
		cl='0',
		tj='0',
		iffb='1',
		ifbold='0',
		ifred='0',
		type='gif',
		src='$src',
		uptime='$dtime',
		author='$author',
		source='$source',
		memberid='0',
		proj='$projpath',
		tags='$tagstr',
		secure='0',
		memo='$memo',
		prop1='$prop1',
		prop2='$prop2',
		prop3='$prop3',
		prop4='$prop4',
		prop5='$prop5',
		prop6='$prop6',
		prop7='$prop7',
		prop8='$prop8',
		prop9='$prop9',
		prop10='$prop10',
		prop11='$prop11',
		prop12='$prop12',
		prop13='$prop13',
		prop14='$prop14',
		prop15='$prop15',
		prop16='$prop16',
		prop17='$prop17',
		prop18='$prop18',
		prop19='$prop19',
		prop20='$prop20',
		downcentid='$downcentid',
		downcent='$downcent',
		fileurl='$fileurl'
		");
		
		echo "OK";
		exit;

	break;



	//删除分页
	case "pagedelete" :

		$delpagesid=$_POST["delpagesid"];
		$nowid=$_POST["nowid"];
		
		$i=0;
		$msql->query("select id from {P}_down_pages where downid='$nowid' order by xuhao");
		while($msql->next_record()){
			$id[$i]=$msql->f('id');
			if($id[$i]==$delpagesid){
				if($i==0){
					$lastid=0;
				}else{
					$lastid=$id[$i-1];
				}
				
			}
			$i++;
		}

		if($lastid==0 && $i>1){
			$lastid=$id[1];
		}

		$msql->query("delete from  {P}_down_pages where id='$delpagesid'");
		
		echo $lastid;
		exit;

	break;


	//添加专题
	case "addproj" :
		
		$project=htmlspecialchars($_POST["project"]);
		$folder=htmlspecialchars($_POST["folder"]);

		
		if($project==""){
			echo $strProjNTC1;
			exit;
		}

		if(strlen($folder)<2 || strlen($folder)>16){
			echo $strProjNTC2;
			exit;
		}

		if (!eregi("^[0-9a-z]{1,16}$",$folder)) { 
			echo $strProjNTC3;
			exit;
		} 

		if(strstr($folder,"/") || strstr($folder,".")){
			echo $strProjNTC3;
			exit;
		}

		//保护目录名
		$arr = array('main','html','class','detail','query','index','admin','downgl','downfabu','downmodify','downcat','down');
		if (in_array($folder, $arr)==true) {
			echo $strProjNTC4;
			exit;
		}

		if(file_exists("../project/".$folder)){
			echo $strProjNTC4;
			exit;
		}

		$msql->query("select id from {P}_down_proj where folder='$folder'");
		if($msql->next_record()){
			echo $strProjNTC4;
			exit;
		}
		
		$pagename="proj_".$folder;

		//创建目录
		@mkdir("../project/".$folder,0777);

		$fd=fopen("../project/temp.php","r");
		$str=fread($fd,"2000");
		$str=str_replace("TEMP",$pagename,$str);
		fclose($fd);

		$filename="../project/".$folder."/index.php";
		$fp=fopen($filename,"w");
		fwrite($fp,$str);
		fclose($fp);

		@chmod($filename,0755);



		//插入记录
		$msql->query("insert into {P}_down_proj set 
			`project`='$project',
			`folder`='$folder'
		");


		//插入页面记录
		$msql->query("insert into {P}_base_pageset set 
			`name`='$project',
			`coltype`='down',
			`pagename`='$pagename',
			`pagetitle`='$project',
			`buildhtml`='index'
		");

		echo "OK";
		exit;

	break;


	//增加分类专栏
	case "addzl" :

		$catid=htmlspecialchars($_POST["catid"]);

		if($catid==""){
			echo $strZlNTC1;
			exit;
		}

		$msql->query("select cat from {P}_down_cat where catid='$catid'");
		if($msql->next_record()){
			$cat=$msql->f('cat');
			$cat=str_replace("'","",$cat);
		}else{
			echo $strZlNTC2;
			exit;
		}

		//页名定义
		$pagename="class_".$catid;

		//创建目录
		@mkdir("../class/".$catid,0777);

		$fd=fopen("../class/temp.php","r");
		$str=fread($fd,"2000");
		$str=str_replace("TEMP",$pagename,$str);
		fclose($fd);

		$filename="../class/".$catid."/index.php";
		$fp=fopen($filename,"w");
		fwrite($fp,$str);
		fclose($fp);

		@chmod($filename,0755);


		//更新分类表
		$msql->query("update {P}_down_cat set `ifchannel`='1' where catid='$catid'");
		

		//更新页面参数表
		$msql->query("select id from {P}_base_pageset where coltype='down' and pagename='$pagename'");
		if($msql->next_record()){
			
		}else{
			$fsql->query("insert into {P}_base_pageset set 
			`name`='$cat',
			`coltype`='down',
			`pagename`='$pagename',
			`pagetitle`='$cat',
			`buildhtml`='index'
			");
		}
		
	echo "OK";
	exit;

	break;


	//删除专栏
	case "delzl" :

		$catid=htmlspecialchars($_POST["catid"]);

		if($catid==""){
			echo $strZlNTC1;
			exit;
		}

		$msql->query("select catid from {P}_down_cat where catid='$catid'");
		if($msql->next_record()){
		}else{
			echo $strZlNTC2;
			exit;
		}

		//删除页面记录
		$pagename="class_".$catid;
		$msql->query("delete from {P}_base_pageset where coltype='down' and pagename='$pagename'");
		
		//删除插件记录
		$msql->query("delete from {P}_base_plus where plustype='down' and pluslocat='$pagename'");

		//更新分类表
		$msql->query("update {P}_down_cat set `ifchannel`='0' where catid='$catid'");

		//删除目录
		if($catid!="" && strlen($catid)>=1 && !strstr($catid,".") && !strstr($catid,"/")){
			DelFold("../class/".$catid);
		}	


	echo "OK";
	exit;

	break;


}
?>