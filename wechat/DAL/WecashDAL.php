<?php 
date_default_timezone_set("PRC");
require_once ('/var/www/html/global.php');
require_once (_ROOT.'wechat/IDAL/IWecashDAL.php');
require_once ('/var/www/html/DALFactory.php');
// require_once (_ROOT.'wechat/Model/User.php');

//=============================
//企业提现支付
//=============================

class WecashDAL implements IWecashDAL{
    private $apiurl = _ROOTURL;//"http://test.meijiemall.com";
    //private $appid = '';
    private $mchid = '10024436';//'1223349501';
    private static $cash_record = "takeout_money";

    public function payToMerchant($openid='', $casharr, $user_name=''){
        global $appid;
//         $appid = 'wxdda2d472561e3e3c';
        $meropenid = $this->mchid;
        $dataArr=array();
        $dataArr['amount']=$casharr['realcash'];
        $dataArr['check_name']='NO_CHECK';
        $dataArr['desc'] = '商家提现';
        $dataArr['mch_appid'] = $appid;
        $dataArr['mchid'] = $meropenid;
        $dataArr['nonce_str'] = 'jiefang'.rand(100000, 999999);//随机数;
        $newopenid=$this->getMappingOpenid($openid);
        $newopenid=trim($newopenid);
        if(empty($newopenid)){return ;}
        $dataArr['openid'] = $newopenid;//$openid;
        $dataArr['partner_trade_no'] = 'orderno_'.time().rand(10000, 99999);//商户订单号;
        $dataArr['re_user_name'] = $user_name;
        $dataArr['spbill_create_ip'] = $_SERVER["REMOTE_ADDR"];
        $this->write_logs("[payToMerchant][info]: param string = ".json_encode($dataArr));
        $signstr = $this->getSign($dataArr);
        $dataArr['sign'] = $signstr;
        $inturl = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $datastring = $this->companyPayDataString($dataArr);
//         var_dump($datastring);exit;
        $this->write_logs("[payToMerchant][info]: param string = ".$datastring);
        $result = $this->payPostRequest($inturl, $datastring);
//         var_dump($result);exit;
        //
        $data = array();
        $data['realcash'] = $casharr['realcash'];
        $data['origincash'] = $casharr['origincash'];
        $data['amount'] = $casharr['realcash'];
        $data['getcash_time'] = time();
        $data['user_openid'] = $openid;
        $data['mchid'] = $meropenid;
        $this->write_logs("[payToMerchant][info]: request string = ".json_encode($result));
        
        if($result['result_code'] == 'FAIL'){
            $this->write_logs("[payToMerchant][error]: request string = ".$datastring);
            $data['is_ok'] = 0;
//         $this->insertGetCashRecord($data);
            //短信提醒return_msg
            $url = $this->apiurl.'printbill/interface/sendtxerrormsg.php';
            $data = array('msg'=>$result['return_msg']);
            $this->getPostRequest($url, $data);
        }else{
            $data['is_ok'] = 1;
            $maskid = '3Ix5jrt-EdkVps9ySIus-zD4aJ9tBFfvragdfJAVY4M';//消息模版ID
            $token = $this->getAccesstoken();
            $this->sendPayMessage($token, $openid, $maskid, '申请提现成功!', '请注意提现到账查收', $dataArr['partner_trade_no'],$casharr['realcash']);
        }
//         $this->insertGetCashRecord($data);
        $this->updateShopBalance($data);
        //发送消息
        return 0;
    }
    private function companyPayDataString($dataArr)
    {
        $tplStr = "<xml>
            <mch_appid><![CDATA[%s]]></mch_appid>
            <mchid><![CDATA[%s]]></mchid>
            <nonce_str><![CDATA[%s]]></nonce_str>
            <partner_trade_no><![CDATA[%s]]></partner_trade_no>
            <openid><![CDATA[%s]]></openid>
            <check_name><![CDATA[%s]]></check_name>
            <re_user_name><![CDATA[%s]]></re_user_name>
            <amount><![CDATA[%s]]></amount>
            <desc><![CDATA[%s]]></desc>
            <spbill_create_ip><![CDATA[%s]]></spbill_create_ip>
            <sign><![CDATA[%s]]></sign>
            </xml>";
        $result = sprintf($tplStr, $dataArr['mch_appid'], $dataArr['mchid'], $dataArr['nonce_str'], $dataArr['partner_trade_no'], $dataArr['openid'], $dataArr['check_name'], $dataArr['re_user_name'], $dataArr['amount'], $dataArr['desc'], $dataArr['spbill_create_ip'], $dataArr['sign']);
        return $result;
    }
    private function getSign($Obj){
        foreach ($Obj as $k => $v)
        {
            $Parameters[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);
        $String = $this->formatParamsForPay($Parameters, false);
        //echo '【string1】'.$String.'</br>';
        //签名步骤二：在string后加入KEY
        //$String = $String."&key=6cd1c9cab639cb399cb371cbd893e15e";
        $String = $String."&key=953885DC58534F9D9D3625C48E038107";
        $this->write_logs("[getSign][info]: connect string = ".$String);
        //echo "【string2】".$String."</br>";
        //签名步骤三：MD5加密
        $String = md5($String);
        //echo "【string3】 ".$String."</br>";
        //签名步骤四：所有字符转为大写
        $result = strtoupper($String);
        //echo "【result】 ".$result_."</br>";
        $this->write_logs("[getSign][info]: param string = ".$result);
        return $result;

    }

    private function formatParamsForPay($params, $encodeurl){
        $this->write_logs("[formatParamsForPay][info]: param string = ".json_encode($params));
        $buff = "";
        ksort($params);
        foreach ($params as $k => $v)
        {
            if($encodeurl)
            {
                $v = urlencode($v);
            }
            if(strlen(strval($v))==0){
                continue;
            }
            $buff .= $k . "=" . $v . "&";
        }
        $reqPar = '';
        if (strlen($buff) > 0)
        {
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        $this->write_logs("[formatParamsForPay][info]: param string1 = ".$reqPar);
        return $reqPar;
    }
    private function payPostRequest($url='', $data=''){
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_URL, $url);
        curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );

        $zs1="/var/www/html/wechat/DAL/cacert/apiclient_cert.pem";
        $zs2="/var/www/html/wechat/DAL/cacert/apiclient_key.pem";
        curl_setopt($ch,CURLOPT_SSLCERT,$zs1);
        curl_setopt($ch,CURLOPT_SSLKEY,$zs2);
        curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
        curl_setopt ( $ch, CURLOPT_AUTOREFERER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
        $info = curl_exec ( $ch );
        $error = curl_errno ($ch);
        if ($error) {
            $this->write_logs("[Error]: ".$error);
            return null;
        }
        curl_close ( $ch );
        $res = simplexml_load_string($info,'SimpleXMLElement',LIBXML_NOCDATA);
        $this->write_logs("[payPostRequest][result]: ".json_encode($info));
        return $res;
    }
    //插入提现申请记录
    private function insertGetCashRecord($info){
        $data = array();
        $data['amount'] = $info['amount'];
        $data['getcash_time'] = $info['getcash_time'];
        $data['user_openid'] = $info['user_openid'];
        $data['mchid'] = $info['mchid'];
        $data['is_ok'] = $info['is_ok'];
        DALFactory::createInstanceCollection(self::$cash_record)->insert($data);
    }
    //获取提现记录
    public function getTixianRecord($shopid=''){
        $condition = array('shopid'=>$shopid,"is_ok"=>"1");
        return  DALFactory::createInstanceCollection(self::$cash_record)->find($condition)->sort(array("getcash_time"=>-1))->limit(20);
    }
    //查看余额
    public function getBalance($openid = ''){
        if($openid == ''){
            return '';
        }
        $dstopenid = $this->getMappingOpenid($openid);
        $res = $this->getShopBalance($dstopenid);
        return $res;
    }
    //根据openid获取对于id
    private function getMappingOpenid($openid = ''){
        $url = $this->apiurl.'houtai/shop/interface/getopenid.php';
        $data = array('openid'=>$openid);
        $res = $this->getPostRequest($url, $data);
        if(empty($res)){
            $this->write_logs('[getMappingOpenid][info]: '.' get openid error!');
            return '';
        }
        if($res['code']=='110'){
            $this->write_logs('[getMappingOpenid][info]: '.'input openid= '.$openid.'; error= '.$res['msg']);
            return '';
        } else if($res['code']=='200') {
            $this->write_logs('[getMappingOpenid][info]: '.'input openid= '.$openid.'; output openid= '.$res['data']['openid']);
            return $res['data']['openid'];
        }
        $this->write_logs('[getMappingOpenid][error]: '.'input openid= '.$openid.'; no handle error occur!');
        return '';
    }
    //根据openid查询账户余额
    private function getShopBalance($openid = ''){
        $url = $this->apiurl.'houtai/shop/interface/getshopmoney.php';
        $data = array('openid'=>$openid);
        $res = $this->getPostRequest($url, $data);
        $this->write_logs('[getShopBalance][info]: result='.json_encode($res));
        if(empty($res)){
            $this->write_logs('[getShopBalance][info]: '.' get openid error!');
            return '';
        }
        if($res['code']=='200') {
            return $res['data'];
        }
        $this->write_logs('[getShopBalance][error]: '.'input openid= '.$openid.'; no handle error occur!');
        return '';
    }
    //更新账户余额
    private function updateShopBalance($info){
        $url = $this->apiurl.'houtai/shop/interface/uptransfer.php';
        $data = array();
        $data['realcash'] = $info['realcash'];
        $data['origincash'] = $info['origincash'];
        $data['amount'] = $info['amount'];
        $data['getcash_time'] = $info['getcash_time'];
        $data['user_openid'] = $info['user_openid'];
        $data['mchid'] = $info['mchid'];
        $data['is_ok'] = $info['is_ok'];
        $this->write_logs('[updateShopBalance][info]: '.json_encode($info));
        $this->getPostRequest($url, $data);
    }

    private function write_logs($content = '') {
        $date = date('Y-m-d');
        $time = date('Y-m-d H:i:s');
        $path = '/data/www/logs/'.$date.'.log';
        $str = $time.': '.$content.PHP_EOL;
        file_put_contents($path, $str, FILE_APPEND);
    }

    //根据code获得openid
    public function getOpenidFromCode($code = ''){
        $appid = 'wxc5b83fb82bad0b65';
        $appsecret = '75986a12121b79e429e8b359aa8aab0a';
        if($code == ''){
            return '';
        }
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=${appid}&secret=${appsecret}&code=${code}&grant_type=authorization_code";
        $tokens = $this->getGetRequest($url);
        if(array_key_exists('errcode', $tokens)){
            $this->write_logs("[getOpenidFromCode][error]: ".json_encode($tokens));
            return;
        }
        $access_token = $tokens['access_token'];
        $refresh_token = $tokens['refresh_token'];
        $openid = $tokens['openid'];
        $this->write_logs("[getOpenidFromCode][openid]: ".$openid);
        if (!empty($openid)){
            return $openid;
        }

        if(!$access_token){
            $url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=${appid}&grant_type=refresh_token&refresh_token=${refresh_token}";
            $ref_token = $this->getGetRequest($url);
            $access_token = $ref_token['access_token'];
        }
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=${access_token}&openid=${openid}&lang=zh_CN";
        $info = $this->getGetRequest($url);
        $this->write_logs("[getOpenidFromCode]: data=".json_encode($info));
        return $info['openid'];
    }
    public function isBindShopOpenid($openid=''){
        $url = $this->apiurl.'printbill/interface/isbindshop.php';
        $data = array('openid'=>$openid);
        $result = $this->getPostRequest($url, $data);
        $this->write_logs('[isBindShopOpenid][result]: '.json_encode($result));
        if($result['code']==110){
            return 0;
        }
        if($result['code']==200 && $result['data']['status']==='1'){
            return 1;
        }
        $this->write_logs('[isBindShopOpenid][error]: '.jsone_encode($result));
    }
    //
    private function getGetRequest($url = '') {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算>法是否存在
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $data = curl_exec($curl); // 执行操作
        $error = curl_errno($curl);
        if ($error) {
            $this->write_logs("[getGetRequest][error]: ".$error);
            return array();
        }
        curl_close($curl); // 关闭CURL会话
        if(is_array($data)){
            return $data;
        }
        $jsoninfo = json_decode($data,true); // 返回数据
        return $jsoninfo;
    }

    private function getPostRequest($url='', $data){
        $this->write_logs("[getPostRequest][url]: ".$url);
        $this->write_logs("[getPostRequest][info]: ".json_encode($data));
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $tmpInfo = curl_exec($curl);
        $error = curl_errno($curl);
        if ($error) {
            $this->write_logs("[getPostRequest][error]: ".$error);
            return array();
        }
        curl_close($curl);
        if(is_array($tmpInfo)){
            return $tmpInfo;
        }
        $datas = json_decode($tmpInfo,true);
        return $datas;
    }
    //发送模板消息
    private function getAccesstoken(){
        $appid="wxc5b83fb82bad0b65";
        $appsecret="75986a12121b79e429e8b359aa8aab0a";
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
    //商业资讯
    private function sendPayMessage($token, $openid, $maskid, $title, $content, $orderno, $amount){
        $time = date("Y-m-d H:i:s", time());
        $mask = array('touser'=>$openid,
            'template_id'=>$maskid,
            'url'=>'',
            'data'=>array('first'=>array('value'=>$title, 'color'=>'#000000'),
                'order'=>array('value'=>$orderno, 'color'=>'#000000'),
                'amount'=>array('value'=>($amount/100.0).'元', 'color'=>'#000000'),
                'remark'=>array('value'=>$content, 'color'=>'#000000')));
        $data = json_encode($mask);
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$token";
        $result = $this->getPostRequest($url, $data);
        $this->write_logs("[sendPayMessage]: ".json_encode($result));
        return $data;
    }

}
?>
