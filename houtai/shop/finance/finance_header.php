<?php 
require_once ('/var/www/html/houtai/shop/global.php');
require_once ('../headest.php');
?>
<!-- BEGIN BODY -->

<body class="page-header-fixed">

	<!-- BEGIN HEADER -->

	<div class="header navbar navbar-inverse navbar-fixed-top">

		<!-- BEGIN TOP NAVIGATION BAR -->

		<div class="navbar-inner">

			<div class="container-fluid">

				<!-- BEGIN LOGO -->

				<a class="brand" href="index.php">
					<img src="<?php echo $base_url;?>media/image/logo.png" alt="" />
				</a>

				<!-- END LOGO -->

				<!-- BEGIN RESPONSIVE MENU TOGGLER -->

				<a href="javascript:;" class="btn-navbar collapsed" data-toggle="collapse" data-target=".nav-collapse">

				<img src="<?php echo $base_url;?>media/image/menu-toggler.png" alt="" />

				</a>          

				<!-- END RESPONSIVE MENU TOGGLER -->            

				<!-- BEGIN TOP NAVIGATION MENU -->              

				<ul class="nav pull-right">

					<!-- BEGIN NOTIFICATION DROPDOWN -->   
<!-- 
					<li class="dropdown" id="header_notification_bar">

						<a href="#" class="dropdown-toggle" data-toggle="dropdown">

						<i class="icon-warning-sign"></i>

						<span class="badge">6</span>

						</a>

						<ul class="dropdown-menu extended notification">

							<li>

								<p>你有14条新通知</p>

							</li>

							<li>

								<a href="#">

								<span class="label label-success"><i class="icon-plus"></i></span>

								有新用户注册. 

								<span class="time">现在</span>

								</a>

							</li>

							<li>

								<a href="#">

								<span class="label label-important"><i class="icon-bolt"></i></span>

								12号服务器过载. 

								<span class="time">15 mins</span>

								</a>

							</li>

							<li>

								<a href="#">

								<span class="label label-warning"><i class="icon-bell"></i></span>

								2号服务器无响应.

								<span class="time">22 mins</span>

								</a>

							</li>

							<li>

								<a href="#">

								<span class="label label-info"><i class="icon-bullhorn"></i></span>

								应用错误.

								<span class="time">40 mins</span>

								</a>

							</li>

							<li>

								<a href="#">

								<span class="label label-important"><i class="icon-bolt"></i></span>

								数据库过载68%. 

								<span class="time">2 hrs</span>

								</a>

							</li>

							<li>

								<a href="#">

								<span class="label label-important"><i class="icon-bolt"></i></span>

								2个用户IP堵塞.

								<span class="time">5 hrs</span>

								</a>

							</li>

							<li class="external">

								<a href="#">查看所有通知 <i class="m-icon-swapright"></i></a>

							</li>

						</ul>

					</li>
 -->
					<!-- END NOTIFICATION DROPDOWN -->

					<!-- BEGIN USER LOGIN DROPDOWN -->

					<li class="dropdown user">

						<a href="#" class="dropdown-toggle" data-toggle="dropdown">

						<img alt="" width="29" height="29" src="<?php if(!empty($_SESSION['shopid'])){echo $_SESSION['logo'];}?>" />

						<span class="username"><?php if(!empty($_SESSION['shopid'])){echo $_SESSION['shopname'];}?></span>

						<i class="icon-angle-down"></i>

						</a>

						<ul class="dropdown-menu">

							<li><a href="index.php"><i class="icon-user"></i> 我的主页</a></li>

					<!-- 		<li><a href="inbox.html"><i class="icon-envelope"></i> 我的信息(6)</a></li> -->

							<li class="divider"></li>

							<li><a href="<?php echo $base_url;?>daysheet.php"><i class="icon-lock"></i>贝贝</a></li>
							<li><a href="<?php echo $base_url;?>finance/daysheet.php"><i class="icon-lock"></i>报表</a></li>
													
							<li><a href="<?php echo $base_url;?>logout.php"><i class="icon-key"></i> 注销</a></li>

						</ul>

					</li>

					<!-- END USER LOGIN DROPDOWN -->

				</ul>

				<!-- END TOP NAVIGATION MENU --> 

			</div>

		</div>

		<!-- END TOP NAVIGATION BAR -->

	</div>

	<!-- END HEADER -->
    <div class="copyrights"><a href="http://www.meijiemall.com/" >杭州街坊科技有限公司</a></div>
    <!-- BEGIN CONTAINER -->
    <div class="page-container">
<?php 
require_once ('finance_sliderbar.php');
require_once ('../widgetsettings.php');
?>	
