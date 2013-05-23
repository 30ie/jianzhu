<?php


DownConfig();


//模块参数设置
function DownConfig(){

	global $msql;

	$msql->query("select * from {P}_down_config");
	while($msql->next_record()){
	
	$variable=$msql->f('variable');
	$value=$msql->f('value');
	$GLOBALS["DOWNCONF"][$variable]=$value;
	}

}


///////////上传文件函数

function NewUploadFile($jpg,$jpg_type,$fname,$jpg_size,$path){

	global $strDownNotice9,$strDownNotice11,$strDownNotice10; 

	if ($jpg_size == 0) {

		$arr[0]="err";
		$arr[1]=$strDownNotice9;
		return $arr;

	}

	if ($jpg_size > $GLOBALS["DOWNCONF"]["FileSizeLimit"]) {
			$arr[0]="err";
			$arr[1]=$strDownNotice10;
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



function pstarnums($n,$RP){


	$str="";

	if($n<0.5){
		$str='<img src='.$RP.'down/templates/images/icon_star_1.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_1.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_1.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_1.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_1.gif>';
	}

	if($n>=0.5 && $n<1.0){
		$str='<img src='.$RP.'down/templates/images/icon_star_3.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_1.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_1.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_1.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_1.gif>';
	}

	if($n>=1.0 && $n<1.5){
		$str='<img src='.$RP.'down/templates/images/icon_star_2.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_1.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_1.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_1.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_1.gif>';
	}

	if($n>=1.5 && $n<2.0){
		$str='<img src='.$RP.'down/templates/images/icon_star_2.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_3.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_1.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_1.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_1.gif>';
	}

	if($n>=2.0 && $n<2.5){
		$str='<img src='.$RP.'down/templates/images/icon_star_2.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_2.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_1.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_1.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_1.gif>';
	}

	if($n>=2.5 && $n<3.0){
		$str='<img src='.$RP.'down/templates/images/icon_star_2.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_2.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_3.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_1.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_1.gif>';
	}

	if($n>=3.0 && $n<3.5){
		$str='<img src='.$RP.'down/templates/images/icon_star_2.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_2.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_2.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_1.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_1.gif>';
	}

	if($n>=3.5 && $n<4.0){
		$str='<img src='.$RP.'down/templates/images/icon_star_2.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_2.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_2.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_3.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_1.gif>';
	}

	if($n>=4.0 && $n<4.5){
		$str='<img src='.$RP.'down/templates/images/icon_star_2.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_2.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_2.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_2.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_1.gif>';
	}
	
	if($n>=4.5 && $n<5.0){
		$str='<img src='.$RP.'down/templates/images/icon_star_2.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_2.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_2.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_2.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_3.gif>';
	}

	if($n>=5.0){
		$str='<img src='.$RP.'down/templates/images/icon_star_2.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_2.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_2.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_2.gif>';
		$str.='<img src='.$RP.'down/templates/images/icon_star_2.gif>';
	}
		
	return $str;

}


?>