<?php 
interface ITabDAL{
	public function findTab($shopid);
	public function delTab($tabid);
	public function addData($inputarr);
	public function getOneTable($tabid,$session);
	public function updateData($tabid,$op,$newval);
	public function getZonenameByZoneid($zoneid);
	public function getShopTablesData($shopid);
	public function getOnLineTable($shopid);
	public function getTabCounsumeInfo($tabid);
	public function getTabStatusByTabid($tabid);
	public function getTableStatusNum($shopid);
	public function getUseTables($shopid,$uid);
	public function hasTabsInZone($zoneid);
	public function getServerTabsData($shopid,$uid);
	public function getTabInfoByTabidarr($tabidarr);
	public function getServerTabs($shopid,$uid);
	public function hasTabInZoneOrBelongTheServer($zoneid,$uid,$resarr);
	public function getServerTabids($shopid,$uid);
	public function getServerTabInfo($resarr);
	public function getServerTabNum($resarr);
	public function changeTabSortno($tabno);
	public function array_sort($arr, $keys, $type = 'asc');
	public function getTabsData($shopid);
	public function getPrinterNameByPid($pid);
	public function getViceTabid($servertabids);
	public function getTabTypeData($shopid);
	public function getTabDataByzoneid($zoneid);
	public function getoneRole($uid,$shopid);
	public function getOneRoleData($roleid);
	public function getDiancaiTables($shopid,$session);
	public function getZonesByShopid($shopid);
	public function getDepositmoney($shopid);
	public function getOneDesposit($billid);
	public function getShopSetData($shopid);
}
?>