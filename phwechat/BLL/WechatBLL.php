<?php 
require_once ('/var/www/html/global.php');
// require_once (_ROOT.'wechat/Model/User.php');
require_once (_ROOT.'phwechat/IBLL/IWechatBLL.php');//接口定义
require_once (_ROOT.'phwechat/Factory/DataFactory.php');//引用下层数据
class WechatBLL implements IWechatBLL{
	/* (non-PHPdoc)
	 * @see IWechatBLL::getPlus()
	 */
	public function getPlus($a, $b) {
		// TODO Auto-generated method stub
		return Wechat_DataFactory::createInstanceWechatDAL()->getPlus($a, $b);
	}
	/* (non-PHPdoc)
	 * @see IWechatBLL::authorizeCode()
	 */
	public function authorizeCode() {
		// TODO Auto-generated method stub
		return Wechat_DataFactory::createInstanceWechatDAL()->authorizeCode();
	}

	/* (non-PHPdoc)
	 * @see IWechatBLL::getGetRequest()
	 */
	
	function getGetRequest($url = '') {
		// TODO Auto-generated method stub
		return Wechat_DataFactory::createInstanceWechatDAL()->getGetRequest();
	}

	/* (non-PHPdoc)
	 * @see IWechatBLL::write_logs()
	 */
	public function write_logs($content = '') {
		// TODO Auto-generated method stub
		return Wechat_DataFactory::createInstanceWechatDAL()->write_logs($content);
	}
	/* (non-PHPdoc)
	 * @see IWechatBLL::getShopinfo()
	 */
	public function getShopinfo($shopid) {
		// TODO Auto-generated method stub
		return Wechat_DataFactory::createInstanceWechatDAL()->getShopinfo($shopid);
		
	}
	/* (non-PHPdoc)
	 * @see IWechatBLL::getFoodCookData()
	 */
	public function getFoodCookData($foodid) {
		// TODO Auto-generated method stub
		$result= Wechat_DataFactory::createInstanceWechatDAL()->getFoodCookData($foodid);
		$arr=array();
		if(!empty($result)){
			$foodcooktype=$result['foodcooktype'];
			$foodcooktypearr=explode("、", $foodcooktype);
			$arr=array(
					"foodid"=>$result['foodid'],
					"foodname"=>$result['foodname'],
					"foodcooktype"=>$foodcooktypearr,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWechatBLL::getFoodsCookData()
	 */
	public function getFoodsCookData($shopid) {
		// TODO Auto-generated method stub
		return Wechat_DataFactory::createInstanceWechatDAL()->getFoodsCookData($shopid);
	}
	/* (non-PHPdoc)
	 * @see IWechatBLL::addPlatfromData()
	 */
	public function addPlatfromData($uid, $platform) {
		// TODO Auto-generated method stub
		return Wechat_DataFactory::createInstanceWechatDAL()->addPlatfromData($uid, $platform);
	}
	/* (non-PHPdoc)
	 * @see IWechatBLL::getWechatUserinfo()
	 */
	public function getWechatUserinfo($uid) {
		// TODO Auto-generated method stub
		return Wechat_DataFactory::createInstanceWechatDAL()->getWechatUserinfo($uid);
	}
	/* (non-PHPdoc)
	 * @see IWechatBLL::getPaySwitch()
	 */
	public function getPaySwitch($shopid) {
		// TODO Auto-generated method stub
		return Wechat_DataFactory::createInstanceWechatDAL()->getPaySwitch($shopid);
	}
	/* (non-PHPdoc)
	 * @see IWechatBLL::getLastPayShop()
	 */
	public function isShowScorePage($uid) {
		// TODO Auto-generated method stub
		$result=Wechat_DataFactory::createInstanceWechatDAL()->getUnscoredShopinfo($uid);
		$isshow=false;
		$billid="";
		$shopid="";
		if(!empty($result)){
			//判断有没有评价
			$iscored=Wechat_DataFactory::createInstanceWechatDAL()->judgeBillIsByScored($result['shopid'], $uid, $result['billid']);
			if(!$iscored){//未评价
				$isshow=true;
				$shopid=$result['shopid'];
				$billid=$result['billid'];
			}
		}
		return array("isshow"=>$isshow,"billid"=>$billid,"shopid"=>$shopid);
	}
	/* (non-PHPdoc)
	 * @see IWechatBLL::getUnscoredShopinfo()
	 */
	public function getShopinfoByShopid($shopid) {
		// TODO Auto-generated method stub
		return Wechat_DataFactory::createInstanceWechatDAL()->getShopinfoByShopid($shopid);
	}
	/* (non-PHPdoc)
	 * @see IWechatBLL::addScore()
	 */
	public function addScoreData($inputarr) {
		// TODO Auto-generated method stub
		return Wechat_DataFactory::createInstanceWechatDAL()->addScoreData($inputarr);
	}
	
	public function createMenu() {
		// TODO Auto-generated method stub
        $this->write_logs("12121212asfasf12");
		return Wechat_DataFactory::createInstanceWechatDAL()->createMenu();
	}
}
?>
