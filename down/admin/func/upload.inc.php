<?php

///////////上传文件函数

function NewUploadFile($jpg,$jpg_type,$fname,$jpg_size,$path){

	global $strDownNotice9,$strDownNotice11; 

	if ($jpg_size == 0) {

		$arr[0]="err";
		$arr[1]=$strDownNotice9;
		return $arr;

	}

	if (substr($fname,-4)!=".rar" && substr($fname,-4)!=".zip" && substr($fname,-4)!=".doc" && substr($fname,-4)!=".xls" && substr($fname,-4)!=".htm" && substr($fname,-5)!=".html" && substr($fname,-4)!=".gif" && substr($fname,-4)!=".jpg" && substr($fname,-4)!=".png" && substr($fname,-4)!=".chm" && substr($fname,-4)!=".txt") {
			$arr[0]="err";
			$arr[1]=$strDownNotice11;
			return $arr;
			
	}
	
	
	$hzarr=explode(".",$fname);
	$num=sizeof($hzarr)-1;
	$UploadImage[2]=$hzarr[$num];
		
 
		$timestr=time();
		$hz=substr($fname,-4);

		$file_path = ROOTPATH.$path."/".$timestr.$hz;
		$UploadImage[3] = $path."/".$timestr.$hz;
		
		copy ($jpg,$file_path);
		chmod ($file_path,0666);
		
		$UploadImage[0]="OK";
		$UploadImage[1]="OK";

		return $UploadImage;

}



?>