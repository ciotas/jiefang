<?php 
#!/usr/bin/php -q
require_once ('/var/www/html/global.php');
require_once ('/var/www/html/admin/global.php');
require_once (Admin_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once(_ROOT.'process_lock.php');
class Control{
    public function controlPrinter($shopinfo){
        return Admin_InterfaceFactory::createInstanceAdminOneDAL()->controlPrinter($shopinfo);
    }
    public function ControlFood($shopinfo)
    {
        return Admin_InterfaceFactory::createInstanceAdminOneDAL()->controlFood($shopinfo);
    }
    public function ControlFoodType($shopinfo)
    {
        return Admin_InterfaceFactory::createInstanceAdminOneDAL()->controlFoodType($shopinfo);
    }
    public function getAllShopinfo(){
        return Admin_InterfaceFactory::createInstanceAdminOneDAL()->getAllShopinfo();
    }
}
$control=new Control();
$title="监控台";
$menu="manage";
$clicktag="transfernote";
$shopinfo=$control->getAllShopinfo();
$printInfo =  $control->controlPrinter($shopinfo);
$foodInfo = $control->ControlFood($shopinfo);
$foodTypeInfo = $control->ControlFoodType($shopinfo);
$info = array(
  'printer' => $printInfo,
    'food' => $foodInfo,
    'foodtype' => $foodTypeInfo,
);
$data = json_encode($info);
// file_put_contents('../data/data.json', "");
//写入文件
file_put_contents(_ROOT.'admin/data/data.json', $data);

?>