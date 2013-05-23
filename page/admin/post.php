<?php
define("ROOTPATH", "../../");
include(ROOTPATH."includes/admin.inc.php");
include("language/".$sLan.".php");
NeedAuth(301);

$act=$_POST["act"];

switch($act){
	


	//添加分组
	case "addgroup" :
		
		$groupname=htmlspecialchars($_POST["groupname"]);
		$folder=htmlspecialchars($_POST["folder"]);

		
		//校验
		if($groupname==""){
			echo $strGroupAddNTC1;
			exit;
		}

		if(strlen($folder)<2 || strlen($folder)>16){
			echo $strGroupAddNTC2;
			exit;
		}

		if (!eregi("^[0-9a-z]{1,16}$",$folder)) { 
			echo $strGroupAddNTC3;
			exit;
		} 

		if(strstr($folder,"/") || strstr($folder,".")){
			echo $strGroupAddNTC3;
			exit;
		}

		//保护目录名
		$arr = array('main','html','htm','detail','index','admin','images','includes','language','module','pics','templates','js','css');
		if (in_array($folder, $arr)==true) {
			echo $strGroupAddNTC4;
			exit;
		}

		if(file_exists("../".$folder)){
			echo $strGroupAddNTC4;
			exit;
		}

		$msql->query("select id from {P}_page_group where folder='$folder'");
		if($msql->next_record()){
			echo $strGroupAddNTC4;
			exit;
		}


		//创建目录和文件
		@mkdir("../".$folder,0777);

		$fd=fopen("../temp.php","r");
		$str=fread($fd,"2000");
		$str_html=str_replace("TEMP",$folder,$str);
		$str_main=str_replace("TEMP",$folder."_main",$str);
		fclose($fd);

		$filename="../".$folder."/index.php";
		$fp=fopen($filename,"w");
		fwrite($fp,$str_html);
		fclose($fp);

		@chmod($filename,0755);

		$filename_main="../".$folder."/main.php";
		$fp=fopen($filename_main,"w");
		fwrite($fp,$str_main);
		fclose($fp);

		@chmod($filename_main,0755);



		//入库
		$msql->query("insert into {P}_page_group set 
			`groupname`='$groupname',
			`xuhao`='1',
			`moveable`='1',
			`folder`='$folder'
		");


		//添加页面记录
		$msql->query("insert into {P}_base_pageset set 
			`name`='$groupname',
			`coltype`='page',
			`pagename`='$folder',
			`buildhtml`='id'
		");

		//添加分组首页
		$mainpagename=$folder."_main";
		$msql->query("insert into {P}_base_pageset set 
			`name`='$groupname',
			`coltype`='page',
			`pagename`='$mainpagename',
			`buildhtml`='0'
		");

		echo "OK";
		exit;

	break;


}
?>