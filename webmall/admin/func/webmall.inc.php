<?php
//完整日期表单

function DayList($nameY,$nameM,$nameD,$nowY,$nowM,$nowD){

	global $strYear,$strMonth,$strDay;

	if(!isset($nowY) || $nowY==""){
		$nowY=date("Y",time());
	}
	if(!isset($nowM) || $nowM==""){
		$nowM=date("n",time());
	}
	if(!isset($nowD) || $nowD==""){
		$nowD=date("j",time());
	}
	

	$AllfromY=date("Y",time())-5;
	$AlltoY=date("Y",time());
	
	$String="<select name=$nameY>";
            
	for($i=$AllfromY;$i<=$AlltoY;$i++){
		if($i==$nowY){
			$String.="<option value=".$i."  selected>".$i."</option>";
		}else{
			$String.="<option value=".$i.">".$i."</option>";
		}
		
	}
		
     $String.="</select>"; 

	 $String.="<select name=$nameM>";
            
	for($i=1;$i<=12;$i++){
		if(strlen($i)<2){
			$ii="0".$i;
		}else{
			$ii=$i;
		}

		if($ii==$nowM){
			$String.="<option value=".$ii."  selected>".$i."</option>";
		}else{
			$String.="<option value=".$ii.">".$i."</option>";
		}
		
	}
		
     $String.="</select>"; 

	$String.="<select name=$nameD>";
            
	for($i=1;$i<=31;$i++){
		if(strlen($i)<2){
			$ii="0".$i;
		}else{
			$ii=$i;
		}

		if($ii==$nowD){
			$String.="<option value=".$ii."  selected>".$i."</option>";
		}else{
			$String.="<option value=".$ii.">".$i."</option>";
		}
		
	}
		
     $String.="</select>"; 

	return $String;

}

?>