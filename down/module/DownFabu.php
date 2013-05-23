<?php


function DownFabu(){


		global $msql,$fsql,$tsql;
		
		$coltitle=$GLOBALS["PLUSVARS"]["coltitle"];
		$tempname=$GLOBALS["PLUSVARS"]["tempname"];

		$memberid=$_COOKIE["MEMBERID"];


		//个人分类
		
		$fsql -> query ("select * from {P}_down_pcat where memberid='$memberid' order by xuhao");
		while ($fsql -> next_record ()) {
			$pcatid = $fsql -> f ("catid");
			$pcat = $fsql -> f ("cat");
			$pcatlist.="<option value='".$pcatid."'>".$pcat."</option>";
		}


		//获取公共分类授权
		$secureset=SecureClass("166");

		//公共分类
		$fsql -> query ("select * from {P}_down_cat order by catpath");
		while ($fsql -> next_record ()) {
			$lpid = $fsql -> f ("pid");
			$lcatid = $fsql -> f ("catid");
			$cat = $fsql -> f ("cat");
			$catpath = $fsql -> f ("catpath");
			$lcatpath = explode (":", $catpath);

			//当主分类有授权时读取下级分类
			if(strstr($secureset,":".intval($lcatpath[0]).":")){
			
				for ($i = 0; $i < sizeof ($lcatpath)-2; $i ++) {
					$lcatpath[$i]=intval($lcatpath[$i]);
					$tsql->query("select catid,cat from {P}_down_cat where catid='$lcatpath[$i]'");
					if($tsql->next_record()){
						$ncatid=$tsql->f('cat');
						$ncat=$tsql->f('cat');
						$ppcat.=$ncat."/";
					}
				}
				
				if($pid==$lcatid){
					$catlist.="<option value='".$lcatid."' selected>".$ppcat.$cat."</option>";
				}else{
					$catlist.="<option value='".$lcatid."'>".$ppcat.$cat."</option>";
				}
				$ppcat="";
			}
			
		}


		//专题
		$fsql -> query ("select * from {P}_down_proj order by id desc");
		while ($fsql -> next_record ()) {
			$projid = $fsql -> f ("id");
			$project = $fsql -> f ("project");
			$NowPath=fmpath($projid);
			$musellist.="<option value=".$NowPath.">".$project."</option>";
			
		}
		
		//积分名称
		$defcentid=$GLOBALS["DOWNCONF"]["DefaultCentId"];
		$centyj=$GLOBALS["DOWNCONF"]["CentYj"]*100;

		if($defcentid<1 || $defcentid>5){
			$defcentid="1";
		}
		$msql->query("select * from {P}_member_centset");
		if($msql->next_record()){
			$centname1=$msql->f('centname1');
			$centname2=$msql->f('centname2');
			$centname3=$msql->f('centname3');
			$centname4=$msql->f('centname4');
			$centname5=$msql->f('centname5');
		}

		$centstr="centname".$defcentid;
		$centlist="<option value='".$defcentid."'>".$$centstr."</option>";


		//模版解释
		$Temp=LoadTemp($tempname);



		$var=array (
		'pname' => $_COOKIE["MEMBERPNAME"],
		'catlist' => $catlist,
		'pcatlist' => $pcatlist,
		'centlist' => $centlist,
		'centyj' => $centyj,
		'musellist' => $musellist
		);
		$str.=ShowTplTemp($Temp,$var);
		
		return $str;

}


?>