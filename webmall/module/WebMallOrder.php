<?php


function WebMallOrder(){

	global $fsql,$msql;

	$tempname=$GLOBALS["PLUSVARS"]["tempname"];
	$memberid=$_COOKIE["MEMBERID"];


	//ฤฃฐๅฝโสอ
	$Temp=LoadTemp($tempname);
	$TempArr=SplitTblTemp($Temp);

	$str=$TempArr["start"];

	$scl=" memberid='$memberid' ";
	

	include(ROOTPATH."includes/pages.inc.php");
	$pages=new pages;

	$totalnums=TblCount("_webmall_torder","id",$scl);
	
	$pages->setvar(array("key" => $key));

	$pages->set(10,$totalnums);		                          
		
	$pagelimit=$pages->limit();	  

	$msql->query("select * from {P}_webmall_torder where $scl order by uptime desc limit $pagelimit");

	while($msql->next_record()){
		
		$orderid=$msql->f('id');
		$company=$msql->f('company');
		$name=$msql->f('name');
		$binddomain=$msql->f('binddomain');
		$tempid=$msql->f('tempid');
		$tempname=$msql->f('tempname');
		$total=$msql->f('total');
		$ifpay=$msql->f('ifpay');
		$ifok=$msql->f('ifok');
		$dtime=$msql->f('dtime');
		$paytime=$msql->f('paytime');

		$fsql->query("select spool from {P}_webmall_spool where id='$tempid' limit 0,1");
		if($fsql->next_record()){
			$spool=$fsql->f('spool');
		}

	
		$dtime=date("y-n-j",$dtime);

		$orderno=$orderid+10000;

		switch($ifok){
			case "0":
				$okimg="no.png";
			break;
			case "1":
				$okimg="ok.png";
			break;
		}
		
		switch($ifpay){
			case "0":
				$payimg="no.png";
			break;
			case "1":
				$payimg="ok.png";
			break;
		}

		$var=array (
		'orderid' => $orderid,
		'orderno' => $orderno,
		'tempname' => $tempname, 
		'binddomain' => $binddomain, 
		'spool' => $spool, 
		'okimg' => $okimg, 
		'payimg' => $payimg, 
		'dtime' => $dtime
		);

		$str.=ShowTplTemp($TempArr["list"],$var);

	}

		$str.=$TempArr["end"];

		$pagesinfo=$pages->ShowNow();

		$var=array (
		'key' => $key,
		'showpages' => $pages->output(1),
		'pagestotal' => $pagesinfo["total"],
		'pagesnow' => $pagesinfo["now"],
		'pagesshownum' => $pagesinfo["shownum"],
		'pagesfrom' => $pagesinfo["from"],
		'pagesto' => $pagesinfo["to"],
		'totalnums' => $totalnums
		);

		$str=ShowTplTemp($str,$var);


		return $str;


}

?>