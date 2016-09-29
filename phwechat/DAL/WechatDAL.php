<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/global.php');
require_once (_ROOT.'phwechat/IDAL/IWechatDAL.php');
require_once ('/var/www/html/DALFactory.php');
// require_once (_ROOT.'wechat/Model/User.php');
class WechatDAL implements IWechatDAL{
    private $dataapi = "http://shop.meijiemall.com";
    private $url = "http://weixin.meijiemall.com";
    private static $userinfo = "wechat_user_info";
    private static $shopinfo = "shopinfo";
    private static $food = "food";
    private static $bill = "bill";
    private static $shopscore = "shopscore";
    private static $wechat_user_info="wechat_user_info";
    //
    //
    ////=======【异步通知url设置】===================================
    ////异步通知url，商户根据实际开发过程设定
    //支付参数设置
	/* (non-PHPdoc)
	 * @see IWechatDAL::getPlus()
	 */
	public function getPlus($a, $b) {
		// TODO Auto-generated method stub
		return $a+$b;
	}
	/* (non-PHPdoc)
	 * @see IWechatDAL::authorizeCode()
	 */
	public function authorizeCode() {
		// TODO Auto-generated method stub
		global $appid;
		global $appsecret;
		$code = '';
		if(isset($_GET['code'])){
			$code = $_GET['code'];
			$this->write_logs("[authorizeCode]: code=".$code);
		} else {
			$this->write_logs("[authorizeCode]: get code error!");
		}
	    $this->write_logs('-=-=-=-=');	
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=${appid}&secret=${appsecret}&code=${code}&grant_type=authorization_code";
// 		echo $url;
		$tokens = $this->getGetRequest($url);
		if(array_key_exists('errcode', $tokens)){
			$this->write_logs("[authorizeCode][error]: ".json_encode($tokens));
			return;
		}
		$access_token = $tokens['access_token'];
		$refresh_token = $tokens['refresh_token'];
		$openid = $tokens['openid'];
		$this->write_logs("[authorizeCode][openid]: ".$openid);
		if(!$access_token){
			$url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=${appid}&grant_type=refresh_token&refresh_token=${refresh_token}";
			$ref_token = $this->getGetRequest($url);
			$access_token = $ref_token['access_token'];
		}
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token=${access_token}&openid=${openid}&lang=zh_CN";
		$info = $this->getGetRequest($url);
		$this->write_logs("[authorizeCode]: data=".json_encode($info));
		$condition = array('openid'=>$info['openid']);
        $oparr = array('openid'=>1, 'uid'=>1);
		//$result = $this->db_info->where($condition)->select();
        $this->write_logs("===========");
        $result = DALFactory::createInstanceCollection(self::$userinfo)->findOne($condition, $oparr);
		$this->write_logs("[authorizeCode]: mysql_data=".json_encode($result));
		$uid = '';
		if(empty($result)){
            $data = array();
            $data['openid'] = $info['openid'];
            $data['nickname'] = $info['nickname'];
            $data['sex'] = $info['sex'];
            $data['language'] = $info['language'];
            $data['province'] = $info['province'];
            $data['city'] = $info['city'];
            $data['headimgurl'] = $info['headimgurl'];
            $data['privilege'] = $info['privilege'];
            $data['timestamp'] = time();
            //$this->write_logs("[authorizeCode]: uidinfo1=".json_encode($data));
            $uidval = $this->userLoginInfo($data);
            //$this->write_logs("[authorizeCode]: uidinfo2=".$uidval);
            $uidval = json_decode($uidval, true);
            $data['uid'] = $uidval['uid'];
            $uid = $data['uid'];
            //$result = $this->db_info->add($data);
            DALFactory::createInstanceCollection(self::$userinfo)->insert($data);
            $this->write_logs("[authorizeCode]: uid1=".$uid);
		} else {
            $condition = array('openid'=>$info['openid']);
            $oparr = array('uid'=>1);
            //$res = $this->db_info->where($condition)->select();
            $res = DALFactory::createInstanceCollection(self::$userinfo)->findOne($condition, $oparr);
            $uid = $res['uid'];
            $this->write_logs("[authorizeCode]: uid2=".$uid);
            //更新openid
            if($openid!=$info['openid']){
                $qarr=array("uid"=>$uid);
                $oparr=array("\$set"=>array("openid"=>$openid));
                DALFactory::createInstanceCollection(self::$userinfo)->update($qarr,$oparr);
            }
		}
		
		$type = isset($_GET['type']) ? $_GET['type'] : '';
		$shopid = isset($_GET['shopid']) ? $_GET['shopid'] : '';
		$shopid=$this->shopidMapping($shopid);
		$tabid= isset($_GET['deskno']) ? $_GET['deskno'] : '';
		$type= isset($_GET['type']) ? $_GET['type'] : 'outer';
		/*if ($type === "takeout" || $type === "inhouse"){
			$url = $this->url."/index.php?m=Admin&c=Index&a=typeDetail&type=$type&shopid=$shopid&uid=".$uid."&tabid=".$tabid;
		
		} else if($type === "myorder"){
			$url = $this->dataapi."/houtai/shop/mybills.php?uid=$uid&shopid=$shopid";
			$this->write_logs("[authorizeCode]: redirect_url=".$url);
		}elseif($type=="store"){
			$url = $this->dataapi."/weshop/shopindex.php?uid=$uid&shopid=$shopid&type=$type";
			$this->write_logs("[authorizeCode]: redirect_url=".$url);
		}elseif($type=="book"){
			$url = $this->dataapi."/houtai/shop/bookinfo.php?uid=$uid&shopid=$shopid";
			$this->write_logs("[authorizeCode]: redirect_url=".$url);
        }*/
        $url = ROOTURL."phwechat/interface/welcome.php?uid=$uid&shopid=$shopid&tabid=$tabid&type=$type";
		$this->write_logs("[authorizeCode]: redirect_url=".$url);
		return $url;
// 		header("location: ".$url);
	}

	/* (non-PHPdoc)
	 * @see IWechatDAL::getGetRequest()
	 */
	
	function getGetRequest($url = '') {
		// TODO Auto-generated method stub
		$curl = curl_init(); // 启动一个CURL会话
		curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算>法是否存在
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
		$data = curl_exec($curl); // 执行操作
		if (curl_errno($curl)) {
			echo 'Errno: '.curl_error($curl);//捕抓异常
		}
		curl_close($curl); // 关闭CURL会话
		$jsoninfo = json_decode($data,true); // 返回数据
		return $jsoninfo;
	}

    private function userLoginInfo($wecha_info){
        $url = $this->dataapi."/userinfo/interface/cuslogin.php";
        $data = array();
        $data['wechatID'] = $wecha_info['openid'];
        $data['nickname'] = $wecha_info['nickname'];
        $data['province'] = $wecha_info['province'];
        $data['city'] = $wecha_info['city'];
        $data['district'] = '';

        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            echo '[userLoginInfo][Errno]: '.curl_error($curl);//捕抓异常
        }
        curl_close($curl); // 关闭CURL会话
        return $tmpInfo;
    }

	/* (non-PHPdoc)
	 * @see IWechatDAL::write_logs()
	 */
	public function write_logs($content = '') {
		// TODO Auto-generated method stub
		$date = date('Y-m-d');
		$time = date('Y-m-d H:i:s');
		$path = '/data/www/logs/'.$date.'.log';
		$str = $time.': '.$content.PHP_EOL;
		file_put_contents($path, $str, FILE_APPEND);
	}
	/* (non-PHPdoc)
	 * @see IWechatDAL::getShopinfo()
	 */
	public function getShopinfo($shopid) {
		// TODO Auto-generated method stub
		$arr=array();
		if(empty($shopid)){return $arr;}
		$shopid = $this->shopidMapping($shopid);
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("shopname"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		if(!empty($result)){
			$arr=array("shopname"=>$result['shopname']);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWechatDAL::getFoodCookData()
	 */
	public function getFoodCookData($foodid) {
		// TODO Auto-generated method stub
		if(empty($foodid)){return array();}
		$qarr=array("_id"=>new MongoId($foodid));
		$oparr=array("foodname"=>1,"foodcooktype"=>1);
		$result=DALFactory::createInstanceCollection(self::$food)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$arr=array(
					"foodid"=>$foodid,
					"foodname"=>$result['foodname'],
					"foodcooktype"=>$result['foodcooktype'],
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWechatDAL::getFoodsCookData()
	 */
	public function getFoodsCookData($shopid) {
		// TODO Auto-generated method stub
	    $shopid = $this->shopidMapping($shopid);
		$qarr=array("shopid"=>$shopid);
		$oparr=array("_id"=>1,"foodname"=>1, "showout"=>1, "foodcooktype"=>1);
		$result=DALFactory::createInstanceCollection(self::$food)->find($qarr,$oparr);
		$arr=array();
		foreach ($result as $key=>$val){
			if(isset($val['showout'])){
				if(!empty($val['showout'])){$showout="1";}else{$showout="0";}
			}else{
				$showout="1";
			}
			if(empty($showout)){continue;}
			$foodcooktype=$val['foodcooktype'];
			$foodcooktypearr=explode("、", $foodcooktype);
			$arr[]=array(
					"foodid"=>strval($val['_id']),
					"foodname"=>$val['foodname'],
					"foodcooktype"=>$foodcooktypearr,
			);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWechatDAL::addPlatfromData()
	 */
	public function addPlatfromData($uid, $platform) {
		// TODO Auto-generated method stub
		$qarr=array("uid"=>$uid);
		$oparr=array("\$set"=>array("platform"=>$platform));
		DALFactory::createInstanceCollection(self::$wechat_user_info)->update($qarr,$oparr);
	}
	/* (non-PHPdoc)
	 * @see IWechatDAL::getWechatUserinfo()
	 */
	public function getWechatUserinfo($uid) {
		// TODO Auto-generated method stub
		$qarr=array("uid"=>$uid);
		$oparr=array("openid"=>1);
		$result=DALFactory::createInstanceCollection(self::$wechat_user_info)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$arr=array("openid"=>$result['openid']);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWechatDAL::getPaySwitch()
	 */
	public function getPaySwitch($shopid) {
		// TODO Auto-generated method stub
		if(empty($shopid)){return array();}
		$shopid = $this->shopidMapping($shopid);
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("alipay_switch"=>1,"wechatpay_switch"=>1,"directpay_switch"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			$arr=array("alipay_switch"=>$result['alipay_switch'],"wechatpay_switch"=>$result['wechatpay_switch'],"directpay_switch"=>$result['directpay_switch']);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWechatDAL::getLastPayShop()
	 */
	public function getUnscoredShopinfo($uid) {
		// TODO Auto-generated method stub
		$qarr=array("uid"=>$uid,"paystatus"=>"paid");
		$oparr=array("_id"=>1, "shopid"=>1,"paymoney"=>1);
		$result=DALFactory::createInstanceCollection(self::$bill)->find($qarr,$oparr)->sort(array("timestamp"=>-1))->limit(1);
		$arr=array();
		foreach ($result as $key=>$val){
			$arr=array("billid"=>strval($val['_id']), "shopid"=>$val['shopid'],"paymoney"=>$val['paymoney']);break;
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWechatDAL::judgeBillIsByScored()
	 */
	public function judgeBillIsByScored($shopid, $uid, $billid) {
		// TODO Auto-generated method stub
	    $shopid = $this->shopidMapping($shopid);
		$qarr=array("shopid"=>$shopid,"uid"=>$uid,"billid"=>$billid);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopscore)->findOne($qarr,$oparr);
		if(!empty($result)){//已评价
			return true;
		}else{
			return false;
		}
	}
	/* (non-PHPdoc)
	 * @see IWechatDAL::getShopinfoByShopid()
	 */
	public function getShopinfoByShopid($shopid) {
		// TODO Auto-generated method stub
		if(empty($shopid)){return array();}
		$shopid = $this->shopidMapping($shopid);
		$qarr=array("_id"=>new MongoId($shopid));
		$oparr=array("shopname"=>1,"homepic"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopinfo)->findOne($qarr,$oparr);
		$arr=array();
		if(!empty($result)){
			if(!empty($result['homepic'])){$homepic=$result['homepic'];}else{$homepic="http://jfoss.meijiemall.com/logo/554ad9615bc109d8518b45d21447084714.png";}
			$arr=array("shopid"=>$shopid, "shopname"=>$result['shopname'],"homepic"=>$homepic);
		}
		return $arr;
	}
	/* (non-PHPdoc)
	 * @see IWechatDAL::addScore()
	 */
	public function addScoreData($inputarr) {
		// TODO Auto-generated method stub
	    $shopid = $this->shopidMapping($inputarr['shopid']);
	    $inputarr['shopid'] = $shopid;
		$qarr=array("shopid"=>$inputarr['shopid'],"uid"=>$inputarr['uid'],"billid"=>$inputarr['billid']);
		$oparr=array("_id"=>1);
		$result=DALFactory::createInstanceCollection(self::$shopscore)->findOne($qarr,$oparr);
		if(!empty($result)){
			$oparr=array("\$set"=>array("score"=>$inputarr['score']));
			DALFactory::createInstanceCollection(self::$shopscore)->update($qarr,$oparr);
		}else{
			$arr=array("shopid"=>$inputarr['shopid'],"uid"=>$inputarr['uid'],"billid"=>$inputarr['billid'],"score"=>$inputarr['score']);
			DALFactory::createInstanceCollection(self::$shopscore)->save($arr);
		}
	}
    public function createMenu(){
        $this->write_logs("[createMenu]: begin");
        $url_order = $this->url."/index.php?m=weixin&c=serveguide&a=myorder";
        $url_price = $this->url."/index.php?m=weixin&c=serveguide&a=servePrice";
        $url_tucao = $this->url."/index.php?m=weixin&c=aboutus&a=tellus";
        $url_share = $this->url."/index.php?m=weixin&c=aboutus&a=share";
        $url_info = $this->url."/index.php?m=weixin&c=aboutus&a=info";
        $url_pay = $this->url."/index.php?m=Weixin&c=Index&a=directPay";
        $encodeurl = urlencode($url_tucao);
        $tucao_dire = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appid."&redirect_uri=".$encodeurl."&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
        $encodeurl = urlencode($url_order);
        $order_dire = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appid."&redirect_uri=".$encodeurl."&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
        $encodeurl = urlencode($url_price);
        $price_dire = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appid."&redirect_uri=".$encodeurl."&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
        $encodeurl = urlencode($url_pay);
        $pay_dire = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appid."&redirect_uri=".$encodeurl."&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
        $menu_str = '{"button":[{"name":"数据统计","sub_button":[{"type":"view","name":"单日汇总","url":"'.$tucao_dire.'"},{"type":"view","name":"流水单","url":"'.$url_share.'"},{"type":"view","name":"商品统计","url":"'.$url_info.'"}]},{"name":"营业","sub_button":[{"type":"view","name":"桌台","url":"'.$order_dire.'"},{"type":"view","name":"我的菜单","url":"'.$price_dire.'"}]},{"name":"我的","sub_button":[{"type":"view","name":"会员卡","url":"'.$tucao_dire.'"},{"type":"view","name":"历史账单","url":"'.$url_share.'"}]}]}';
        $token = $this->getAccesstoken();
        $this->write_logs("[token]: ".$token);
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=${token}";
        $result = $this->getPostRequest($url, $menu_str);
        $this->write_logs("[createMenu]: ".$result);
    }
    private function getAccesstoken(){
    	global $appid;
		global $appsecret;
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=${appid}&secret=${appsecret}";
        $curl = curl_init(); 
        curl_setopt($curl, CURLOPT_URL, $url); 
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);  
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);  
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
        $data = curl_exec($curl); 
        if (curl_errno($curl)) {
            echo 'Errno: '.curl_error($curl);
        }
        curl_close($curl); 
        $jsoninfo = json_decode($data,true); 
        $access_token = $jsoninfo['access_token'];

        return $access_token;
    }
    
    private function getPostRequest($url='', $data){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url); 
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); 
        curl_setopt($curl, CURLOPT_POST, 1); 
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); 
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); 
        curl_setopt($curl, CURLOPT_HEADER, 0); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $tmpInfo = curl_exec($curl); 
        if (curl_errno($curl)) {
            echo 'Errno: '.curl_error($curl);
        }
        curl_close($curl); 
        $this->write_logs("error=".$tmpInfo);
        $datas = json_decode($tmpInfo,true); 
        return $datas;
    }
    
    private function shopidMapping($shopid=''){
        $shid = $shopid;
        if($shopid == '572987a65bc109eb298b470f'){
            $shid = '5747a49a5bc1099a068b45b2';
        } else if($shopid == '573ff76e5bc109ad7d8b6739'){
            $shid = '57498b3f1a156fdd138b4904';
        } else if($shopid == '57400c2a5bc109ea298b51a9'){
            $shid = '5749902b1a156fdf138b491a';
        } else if($shopid == '573c7f535bc109ef298b4f77'){
            $shid = '5749838d1a156fdd138b48e8';
        }elseif ($shopid == "554ad9615bc109d8518b45d2"){
            $shid = "57679be61a156f8d748b493c";
        }
        return $shid;
    }
}
?>
