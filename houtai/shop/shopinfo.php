<?php 
require_once ('./startsession.php');
require_once ('/var/www/html/houtai/shop/global.php');
require_once (QuDian_DOCUMENT_ROOT.'Factory/InterfaceFactory.php');
require_once ('/var/www/html/phpqrcode/phpqrcode.php');
class Shopinfo{
	public function getMyShopinfoData($shopid){
		return QuDian_InterfaceFactory::createInstanceMonitorFiveDAL()->getMyShopinfoData($shopid);
	}
}
$shopinfo=new Shopinfo();
$title="商家资料";
$menu="profile";
$clicktag="shopinfo";
$shopid=$_SESSION['shopid'];
$arr=array();
require_once ('header.php');
$arr=$shopinfo->getMyShopinfoData($shopid);
// print_r($arr);
//生成二维码图片
$shopurl=$root_url."wechat/interface/redirect.php?shopid=".$shopid;
$level = 'M';
// 点的大小：1到10,用于手机端4就可以了
$size = 8;
// 下面注释了把二维码图片保存到本地的代码,如果要保存图片,用$fileName替换第二个参数false
$path = "qrimg/";
// 生成的文件名
$fileName = $path.$shopid.'.png';
QRcode::png($shopurl, $fileName, $level, $size);
// QRcode::png($shopurl);

$logo =$arr['logo'];//准备好的logo图片
$QR =$fileName;//已经生成的原始二维码图

if ($logo !== FALSE) {
	$QR = imagecreatefromstring(file_get_contents($QR));
	$logo = imagecreatefromstring(file_get_contents($logo));
	$QR_width = imagesx($QR);//二维码图片宽度
	$QR_height = imagesy($QR);//二维码图片高度
	$logo_width = imagesx($logo);//logo图片宽度
	$logo_height = imagesy($logo);//logo图片高度
	$logo_qr_width = $QR_width / 4;
	$scale = $logo_width/$logo_qr_width;
	$logo_qr_height = $logo_height/$scale;
	$from_width = ($QR_width - $logo_qr_width) / 2;
	//重新组合图片并调整大小
	imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
	$logo_qr_height, $logo_width, $logo_height);
}
//输出图片
imagepng($QR, 'qrimg/'.$shopid.'.png');
$imgurl=$base_url."qrimg/".$shopid.".png";
?>

			<!-- BEGIN PAGE CONTAINER-->

			<div class="container-fluid">
			
				<div class="span12">

						<!-- BEGIN STYLE CUSTOMIZER -->

						<!-- END BEGIN STYLE CUSTOMIZER -->  

						<h3 class="page-title">
							商家资料
							 <small></small>

						</h3>
					</div>

				<!-- END PAGE HEADER-->

				<!-- BEGIN PAGE CONTENT-->

				<div class="row-fluid profile">

				
					<div class="span12">

					<div class="portlet-body">
								
							</div>
						<!--BEGIN TABS-->

						<div class="tabbable tabbable-custom tabbable-full-width">

								<!--end tab-pane-->

								<div class="tab-pane profile-classic row-fluid" >

									<ul class="span10" style="list-style-type: none">
									
									<li><span>本店logo:</span>
									<img src="<?php if(!empty($arr)){echo $arr['logo'];}?>" alt=""  width="200"/>
									<span class="help-inline">
									<form action="./interface/douplogo.php" method="post" enctype="multipart/form-data">
										<div class="controls">
											<div class="fileupload fileupload-new" data-provides="fileupload">
											<input type="hidden" value="<?php echo $shopid;?>" name="shopid">
												<span class="btn btn-file red">
												<span class="fileupload-new"  style="color:#FFF">选择图片</span>
												<span class="fileupload-exists" style="color:#FFF">重新上传</span>
												<input type="file" class="default" name="logo">
												</span>
												<span class="fileupload-preview"></span>
												<a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none"></a>
												<button type="submit" class="btn icn-only black" ><i class="m-icon-swapup m-icon-white"></i></button>
											</div>
										</div>
										</form>
									</span>
									</li>
								
									
									
									<li><span>主页图片:</span>
									<img src="<?php if(!empty($arr)){echo $arr['homepic'];}?>" alt=""  width="200"/>
									<span class="help-inline">
									<form action="./interface/doupshopimg.php" method="post" enctype="multipart/form-data">
										<div class="controls">
											<div class="fileupload fileupload-new" data-provides="fileupload">
											<input type="hidden" value="homepic" name="op">
												<span class="btn btn-file red">
												<span class="fileupload-new"  style="color:#FFF">选择图片</span>
												<span class="fileupload-exists" style="color:#FFF">重新上传</span>
												<input type="file" class="default" name="shopimg">
												</span>
												<span class="fileupload-preview"></span>
												<a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none"></a>
												<button type="submit" class="btn icn-only black" ><i class="m-icon-swapup m-icon-white"></i></button>
											</div>
										</div>
										</form>
									
									</span>
									</li>
										
										<li><span>一句话宣传:</span> <?php if(!empty($arr)){echo $arr['briefinfo'];}?></li>
										
										<li><span>详细地址:</span> <?php if(!empty($arr)){echo $arr['province']."省".$arr['city']."市".$arr['district']."区(县)".$arr['road'];}?></li>
										<li><span>经纬度:</span> 经度：<?php echo $arr['lon'];?> 纬度：<?php echo $arr['lat'];?></li>
										
										<li><span>人均消费:</span> ￥<?php if(!empty($arr)){echo $arr['avgpay'];}?></li>
										<li><span>营业时间:</span><?php if(!empty($arr)){echo $arr['opentime'];}?></li>

										<li><span>服务电话:</span><?php if(!empty($arr)){echo $arr['servicephone'];}?></li>

										<li><span>店长昵称:</span><?php if(!empty($arr)){echo $arr['manager'];}?></li>
										<li><span>商家支付宝账户:</span><?php if(!empty($arr)){echo $arr['alipayaccount'];}?></li>
										<li><span>开通外卖:</span><?php if(!empty($arr)){if($arr['takeoutswitch']=="1"){echo "是";}else{echo "否";}}?></li>
										<li><span>店长头像:</span><img src="<?php if(!empty($arr)){echo $arr['managerphoto'];}?>" alt=""  width=100/>
										
										<span class="help-inline">
										<form action="./interface/doupshopimg.php" method="post" enctype="multipart/form-data" >
										<div class="controls">
											<div class="fileupload fileupload-new" data-provides="fileupload">
											<input type="hidden" value="managerphoto" name="op">
												<span class="btn btn-file red">
												<span class="fileupload-new" style="color:#fff">选择图片</span>
												<span class="fileupload-exists" style="color:#fff">重新上传</span>
												<input type="file" class="default" name="shopimg">
												</span>
												<span class="fileupload-preview"></span>
												<a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none"></a>
												<button type="submit" class="btn icn-only black" ><i class="m-icon-swapup m-icon-white"></i></button>
											</div>
										</div>
										</form>
										</span>
										</li>
										
										<li><span>本店标签:</span><?php if(!empty($arr)){echo $arr['storetag'];}?></li>
										<li><span>推荐美食:</span>
										<?php if(!empty($arr)){echo implode("、", $arr['favfood']);}?></li>
										<li><span>本店二维码:</span><?php
										 $imgurl=$base_url."qrimg/".$shopid.".png";
										 if(empty($imgurl)){$imgurl="";}
										 echo '<img src="'.$imgurl.'" width="200">';?></li>
									</ul>

								</div>	
								<!--end tab-pane-->
								<div class="form-actions">
										<button onclick="window.location.href='./editshopinfo.php'" class="btn blue" style="float:right">编辑</button>

									</div>
						</div>

						<!--END TABS-->

					</div>

				</div>

				<!-- END PAGE CONTENT-->

			</div>

			<!-- END PAGE CONTAINER--> 

		</div>

		<!-- END PAGE -->    

	</div>

	<!-- END CONTAINER -->

	<?php
	require_once ('./footer.php');
	?>

	