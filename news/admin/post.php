<?php
define("ROOTPATH", "../../");
include(ROOTPATH."includes/admin.inc.php");
include("language/".$sLan.".php");
include("func/upload.inc.php");
NeedAuth(122);

$act=$_POST["act"];

switch($act){
	
	
	//?????????
	case "proplist" :
		
		$catid=$_POST["catid"];
		$nowid=$_POST["nowid"];

		if($nowid!="" && $nowid!="0"){
			$msql->query("select * from {P}_news_con where  id='$nowid'");
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
		$msql->query("select * from {P}_news_prop where catid='$catid' order by xuhao");
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


	//??????????
	case "addpage" :
		
		$nowid=$_POST["nowid"];

		$xuhao=0;
		if($nowid!="" && $nowid!="0"){
			$msql->query("select max(xuhao) from {P}_news_pages where newsid='$nowid'");
			if($msql->next_record()){
				$xuhao=$msql->f('max(xuhao)');
			}
			$xuhao=$xuhao+1;
			$msql->query("insert into {P}_news_pages set newsid='$nowid',xuhao='$xuhao' ");
		}
		echo "OK";
		exit;

	break;

	
	//?????????
	case "newspageslist" :
		
		$nowid=$_POST["nowid"];
		$pageinit=$_POST["pageinit"];

		$str="<ul>";
		$str.="<li id='p_0' class='pages'>1</li>";

		$i=2;
		$id=0;
		$msql->query("select id from {P}_news_pages where newsid='$nowid' order by xuhao");
		while($msql->next_record()){
			$id=$msql->f('id');
			$str.="<li id='p_".$id."' class='pages'>".$i."</li>";
			$i++;
		}
		
		//?ж???????
		if($pageinit!="new"){
			$id=$pageinit;
		}

		$str.="<li id='addpage' class='addbutton'>".$strNewsPagesAdd."</li>";
		if($pageinit!="0"){
			$str.="<li id='pagedelete' class='addbutton'>".$strNewsPagesDel."</li>";
			$str.="<li id='backtomodi' class='addbutton'>".$strBack."</li>";
		}
		$str.="<input  type='submit' name='modi'  onClick='KindSubmit();' value='".$strSave."' class='savebutton' />";
		$str.="</ul><input id='newspagesid' name='newspagesid' type='hidden' value='".$id."'>";
		echo $str;
		exit;

	break;


	//???????????
	case "getcontent" :
		
		$nowid=$_POST["nowid"];
		$newspageid=$_POST["newspageid"];

		if($newspageid=="-1"){

			$body="";

		}elseif($newspageid=="0"){
			
			$msql->query("select body from {P}_news_con where id='$nowid'");
			if($msql->next_record()){
				$body=$msql->f('body');
				
			}

		}else{

			$msql->query("select body from {P}_news_pages where id='$newspageid'");
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



	//?????????
	case "newsmodify" :

	
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
		$pic=$_FILES["jpg"];
		$file=$_FILES["file"];
		$fileurl=$_POST["fileurl"];	

		
		//??ajax????,jform????iframe??????????????????????????
		if($pic["size"]>0 || $file["size"]>0){
			$Meta="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		}

			
		//У?鴦??

		$uptime=time();

		if($title==""){
			echo $Meta.$strNewsNotice6;
			exit;
		}
		if(strlen($title)>200){
			echo $Meta.$strNewsNotice7;
			exit;
		}

		if(strlen($body)>65000){
			echo $Meta.$strNewsNotice5;
			exit;
		}


		$body=Url2Path($body);
		

		//标签过滤
		$title=str_replace("{#","",$title);
		$title=str_replace("#}","",$title);
		$memo=str_replace("{#","",$memo);
		$memo=str_replace("#}","",$memo);
		$body=str_replace("{#","{ #",$body);
		$body=str_replace("#}","# }",$body);
		
		$msql->query("select catpath from {P}_news_cat where catid='$catid'");
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
				$uppath="news/upload/".$nowdate;

				$filearr=NewUploadFile($file["tmp_name"],$file["type"],$file["name"],$file["size"],$uppath);
				if($filearr[0]!="err"){
					$fileurl=$filearr[3];
				}else{
					echo $Meta.$filearr[1];
					exit;
				}
				
				
					$msql->query("select fileurl from {P}_news_con where id='$id'");
					if($msql->next_record()){
						$oldfileurl=$msql->f('fileurl');
					}
					if(file_exists(ROOTPATH.$oldfileurl) && $oldfileurl!="" && !strstr($oldfileurl,"../")){
						unlink(ROOTPATH.$oldfileurl);
					}
		}

		//??????
		if($pic["size"]>0){
			$nowdate=date("Ymd",time());
			$picpath="../pics/".$nowdate;
			@mkdir($picpath,0777);
			$uppath="news/pics/".$nowdate;
			
			$arr=NewUploadImage($pic["tmp_name"],$pic["type"],$pic["size"],$uppath);
			if($arr[0]!="err"){
				$src=$arr[3];
			}else{
				echo $Meta.$arr[1];
				exit;
			}

			$msql->query("select src from {P}_news_con where id='$id'");
			if($msql->next_record()){
				$oldsrc=$msql->f('src');
			}
			if(file_exists(ROOTPATH.$oldsrc) && $oldsrc!="" && !strstr($oldsrc,"../")){
				unlink(ROOTPATH.$oldsrc);
			}

			$msql->query("update {P}_news_con set src='$src' where id='$id'");

		}

		//???????
		for($t=0;$t<sizeof($tags);$t++){
			if($tags[$t]!=""){
				$tagstr.=$tags[$t].",";
			}
		}
		
		//????????

		$msql->query("update {P}_news_con set 
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


	//????????
	case "contentmodify" :
		$newspagesid=$_POST["newspagesid"];
		$body=$_POST["body"];
		
		//У??
		if(strlen($body)>65000){
			echo $strNewsNotice5;
			exit;
		}

		//????·?????
		$body=Url2Path($body);

		//????????
		$msql->query("update {P}_news_pages set body='$body' where id='$newspagesid'");
		
		echo "OK";
		exit;

	break;


	//???·???
	case "newsadd" :
		
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
		$pic=$_FILES["jpg"];
		$file=$_FILES["file"];
		$spe_selec=$_POST["spe_selec"];

		//??ajax????,jform????iframe??????????????????????????
		if($pic["size"]>0 || $file["size"]>0){
			$Meta="<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
		}



		$uptime=time();

		if($title==""){
			echo $Meta.$strNewsNotice6;
			exit;
		}
		if(strlen($title)>200){
			echo $Meta.$strNewsNotice7;
			exit;
		}

		if(strlen($body)>65000){
			echo $Meta.$strNewsNotice5;
			exit;
		}


		$dtime=time();

		$msql->query("select catpath from {P}_news_cat where catid='$catid'");
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

		
		//?????
		if($pic["size"]>0){
			$nowdate=date("Ymd",time());
			$picpath="../pics/".$nowdate;
			@mkdir($picpath,0777);
			$uppath="news/pics/".$nowdate;
			$arr=NewUploadImage($pic["tmp_name"],$pic["type"],$pic["size"],$uppath);
			if($arr[0]!="err"){
				$src=$arr[3];
			}else{
				echo $Meta.$arr[1];
				exit;
			}
			
		}
		
		//??????
		if($file["size"]>0){
			$nowdate=date("Ymd",time());
			$picpath="../upload/".$nowdate;
			@mkdir($picpath,0777);
			$uppath="news/upload/".$nowdate;
			$filearr=NewUploadFile($file["tmp_name"],$file["type"],$file["name"],$file["size"],$uppath);
			if($filearr[0]!="err"){
				$fileurl=$filearr[3];
			}else{
				echo $Meta.$filearr[1];
				exit;
			}
			
		}



		//??????
		$count_pro = count ($spe_selec);
		for ($i = 0; $i < $count_pro; $i ++) {
			$projid = $spe_selec[$i];
			$projpath .= $projid.":";
		}

		//???????
		for($t=0;$t<sizeof($tags);$t++){
			if($tags[$t]!=""){
				$tagstr.=$tags[$t].",";
			}
		}


		//???
		
		$msql->query("insert into {P}_news_con set
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



	//?????????
	case "pagedelete" :

		$delpagesid=$_POST["delpagesid"];
		$nowid=$_POST["nowid"];
		
		$i=0;
		$msql->query("select id from {P}_news_pages where newsid='$nowid' order by xuhao");
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

		//????????
		$msql->query("delete from  {P}_news_pages where id='$delpagesid'");
		
		echo $lastid;
		exit;

	break;


	//新建专题
	case "addproj" :
		
		$project=htmlspecialchars($_POST["project"]);
		$folder=htmlspecialchars($_POST["folder"]);

		
		//У??
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

		//???????????????????????????
		$arr = array('main','html','class','detail','query','index','admin','newsgl','newsfabu','newsmodify','newscat','news');
		if (in_array($folder, $arr)==true) {
			echo $strProjNTC4;
			exit;
		}

		if(file_exists("../project/".$folder)){
			echo $strProjNTC4;
			exit;
		}

		$msql->query("select id from {P}_news_proj where folder='$folder'");
		if($msql->next_record()){
			echo $strProjNTC4;
			exit;
		}
		
		$pagename="proj_".$folder;

		//???????????
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



		//???
		$msql->query("insert into {P}_news_proj set 
			`project`='$project',
			`folder`='$folder'
		");


		//????????
		$msql->query("insert into {P}_base_pageset set 
			`name`='$project',
			`coltype`='news',
			`pagename`='$pagename',
			`pagetitle`='$project',
			`buildhtml`='index'
		");

		echo "OK";
		exit;

	break;


	//???????????
	case "addzl" :

		$catid=htmlspecialchars($_POST["catid"]);

		if($catid==""){
			echo $strZlNTC1;
			exit;
		}

		$msql->query("select cat from {P}_news_cat where catid='$catid'");
		if($msql->next_record()){
			$cat=$msql->f('cat');
			$cat=str_replace("'","",$cat);
		}else{
			echo $strZlNTC2;
			exit;
		}

		//???????????
		$pagename="class_".$catid;

		//???????????
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


		//????
		$msql->query("update {P}_news_cat set `ifchannel`='1' where catid='$catid'");
		

		//????????
		$msql->query("select id from {P}_base_pageset where coltype='news' and pagename='$pagename'");
		if($msql->next_record()){
			
		}else{
			$fsql->query("insert into {P}_base_pageset set 
			`name`='$cat',
			`coltype`='news',
			`pagename`='$pagename',
			`pagetitle`='$cat',
			`buildhtml`='index'
			");
		}
		
	echo "OK";
	exit;

	break;


	//??????????
	case "delzl" :

		$catid=htmlspecialchars($_POST["catid"]);

		if($catid==""){
			echo $strZlNTC1;
			exit;
		}

		$msql->query("select catid from {P}_news_cat where catid='$catid'");
		if($msql->next_record()){
		}else{
			echo $strZlNTC2;
			exit;
		}

		//????????
		$pagename="class_".$catid;
		$msql->query("delete from {P}_base_pageset where coltype='news' and pagename='$pagename'");
		
		//?????????
		$msql->query("delete from {P}_base_plus where plustype='news' and pluslocat='$pagename'");

		//???·?????
		$msql->query("update {P}_news_cat set `ifchannel`='0' where catid='$catid'");

		//??????????
		if($catid!="" && strlen($catid)>=1 && !strstr($catid,".") && !strstr($catid,"/")){
			DelFold("../class/".$catid);
		}	


	echo "OK";
	exit;

	break;


}
?>