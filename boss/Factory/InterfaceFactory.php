<?php 
require_once ('/var/www/html/boss/global.php');
require_once (Boss_DOCUMENT_ROOT.'DAL/BossOneDAL.php');
class Boss_InterfaceFactory{
	public static function createInstanceBossOneDAL(){
		return new BossOneDAL();
	}
}
?>