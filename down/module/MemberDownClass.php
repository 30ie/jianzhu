<?php

/*
	[�������] ��Ա�����Զ������
	[���÷�Χ] ����ҳ��
*/



function MemberDownClass(){

		global $msql,$fsql;



		$target=$GLOBALS["PLUSVARS"]["target"];
		$tempname=$GLOBALS["PLUSVARS"]["tempname"];

		//��ַ������
		if(isset($_GET["mid"]) && $_GET["mid"]!="" && $_GET["mid"]!="0"){
			$mid=$_GET["mid"];
		}else{
			return "";
		}

		$scl="  memberid='$mid' ";



		//ģ�����
		$Temp=LoadTemp($tempname);
		$TempArr=SplitTblTemp($Temp);

		$str=$TempArr["start"];

			
		$msql->query("select * from {P}_down_pcat where $scl order by xuhao");
		
		while($msql->next_record()){
				$catid=$msql->f("catid");
				$cat=$msql->f("cat");
				$link=ROOTPATH."down/memberdown.php?mid=".$mid."&pcatid=".$catid;

				$var=array (
				'link' => $link, 
				'cat' => $cat, 
				'target' => $target
				);
				$str.=ShowTplTemp($TempArr["list"],$var);
	
		
		}
		
		
        $str.=$TempArr["end"];
       
		return $str;

		
}


?>