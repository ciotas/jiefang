<?php 
require_once ('/var/www/html/global.php');
require_once (_ROOT.'takeoutwechat/Factory/BLLFactory.php');
class SyncFood{
	public function getFoodsCookData($shopid) {
		return Wechat_BLLFactory::createInstanceWechatBLL()->getFoodsCookData($shopid);
	}
}
$syncfood=new SyncFood();
if(isset($_REQUEST['shopid'])){
	$shopid=$_REQUEST['shopid'];
	//file_put_contents("/var/www/html/log.txt",11111);
	$sourcedir="/dev/shm/user/$shopid";
	$destdir=_ROOT."takeoutwechat/media/foodjs/";
	//美食
	$destfile=$destdir.$shopid.".js";
	shell_exec("rm -rf ".$destfile);
	
	//做法
	$cookdestfile=$destdir."cook_".$shopid.".js";
	shell_exec("rm -rf ".$cookdestfile);
	
	$food=file_get_contents($sourcedir);
//file_put_contents("/var/www/html/log.txt",$food);
	$foodarr=json_decode($food,true);
	$menuarr=array();
	$listarr=array();
	$onefoodarr=array();
	$foodstr="";
	foreach ($foodarr as $fkey=>$foods){
		$menuarr[]=$foods['ftname'];
		foreach ($foods['food'] as $key=>$val){
			$isdis=false;
			if(!empty($val['foodcooktype'])){$isdis=true;}
			$onefood=array(
					"id"=>$val['foodid'],
					"imgUrl"=>$val['foodpic'],
					"name"=>$val['foodname'],
					"price"=>$val['foodprice'],
					"unit"=>$val['foodunit'],
					"foodintro"=>$val['foodintro'],
					"isdis"=>$isdis
			);
			if(array_key_exists($foods['ftname'], $listarr)){
				array_push($listarr[$foods['ftname']], $onefood);
			}else{
				$listarr[$foods['ftname']][]=$onefood;
			}
		}
	}

	// print_r($listarr);exit;
//file_put_contents("/var/www/html/log.txt",json_encode($listarr));
	$str="var menus=". json_encode($menuarr).";\r\n";
	foreach ($listarr as $ftname=>$foods){
		array_push($onefoodarr, "{".$ftname.":".json_encode($foods)."}");
	}
	$str.="var lists=[".implode(",", $onefoodarr)."]";
	//file_put_contents("/var/www/html/log.txt",$str);
	if(!is_dir($destdir))
	{
		mkdir($destdir);
	}
	
	if(!file_exists($destfile))
	{
		if(md5($str) != md5(file_get_contents($destfile)))
		{
	//		file_put_contents($destfile,$str);
		}
	}

	file_put_contents($destfile,$str);
	//file_put_contents("/var/www/html/log.txt",1112);exit;
	
	
	//做法
	$foodcookarr=$syncfood->getFoodsCookData($shopid);
	$cookstr='cooktype='.json_encode($foodcookarr);
	
	if(!file_exists($cookdestfile))
	{
		if(md5($cookstr) != md5(file_get_contents($cookdestfile)))
		{
//			file_put_contents($cookdestfile,$cookstr);
		}
	}
	file_put_contents($cookdestfile,$cookstr);
}

?>
