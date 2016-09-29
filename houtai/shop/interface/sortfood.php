<?php 
require_once ('../startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
class SortFood{
	public function getFoodinfo($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->getFoodinfo($shopid);
	}
	public function sortfoodData($arr){
		QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->sortfoodData($arr);
	}
	public function array_sort($arr, $keys, $type = 'asc'){
	    return QuDian_InterfaceFactory::createInstanceMonitorSixDAL()->array_sort($arr, $keys, $type);
	}
	public function syncData($shopid){
	    QuDian_InterfaceFactory::createInstanceMonitorTwoDAL()->syncData($shopid);
	}
}
$sortfood=new SortFood();
$shopid=$_SESSION['shopid'];
$op=$_GET['op'];
$foodarr=$sortfood->getFoodinfo($shopid);
if($op=="alpha"){
    foreach ($foodarr as $key=>$value)
    {
        $new_array[$value['foodid']] = iconv('UTF-8', 'GBK', $value['foodname']);
    }
    asort($new_array);
    $i=0;
    foreach ($new_array as $key=>$value)
    {
        $array[$key] =array("sortno"=>$i++,"foodname"=>iconv('GBK', 'UTF-8', $value)) ;
    }
}elseif($op=="price"){
    $foodarr=$sortfood->array_sort($foodarr, "foodprice","asc");
    $j=0;
    foreach ($foodarr as $key=>$value)
    {
        $array[$value['foodid']] = array("sortno"=>$j++);
    }
}
$sortfood->sortfoodData($array);
$sortfood->syncData($shopid);
header("location: ../foodmanage.php");
?>