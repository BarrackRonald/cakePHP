<?php
$this->disableAutoLayout();

?>
<!DOCTYPE html>
<!--
	ustora by freshdesignweb.com
	Twitter: https://twitter.com/freshdesignweb
	URL: https://www.freshdesignweb.com/ustora/
-->
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="../../img/NormalUsers/vertu.jpg" type="image/x-icon">
	<title>VerTu Website</title>
	<!--=============================TEST==================================================================-->

	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/Login/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/Login/animate/animate.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/Login/css-hamburgers/hamburgers.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/Login/select2/select2.min.css">
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/Login/util.css">
	<link rel="stylesheet" type="text/css" href="css/Login/main.css">
	<!--==================================ENDTEST=============================================================-->

	<!-- Google Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,200,300,700,600' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Raleway:400,100' rel='stylesheet' type='text/css'>

	<!-- Bootstrap -->
	<link rel="stylesheet" href="../../css/NormalUsers/bootstrap.min.css">

	<!-- Font Awesome -->
	<link rel="stylesheet" href="../../css/NormalUsers/font-awesome.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<!-- Custom CSS -->
	<link rel="stylesheet" href="../../css/NormalUsers/owl.carousel.css">
	<link rel="stylesheet" href="../../css/NormalUsers/style.css">
	<link rel="stylesheet" href="../../css/NormalUsers/responsive.css">

	<!-- //Ajax -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js" integrity="sha512-n/4gHW3atM3QqRcbCn6ewmpxcLAHGaDjpEBu4xZd47N0W2oQ+6q7oc3PXstrJYXcbNU1OHdQ1T7pAP+gi5Yu8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
	<!-- Sweet Alert -->
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>

<body>

	<div class="header-area">
		<div style="z-index: 10000" id="toast"></div>

		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<div class="user-menu">
					</div>
				</div>
				<div class="col-md-6">
					<div class="user-menu">
						<ul>
							<li>
								<?php $session = $this->request->getSession();
								if ($session->check('username')) { ?>
									<a href="/logout">
										<i class="fa fa-sign-out">
											Logout
										</i>
									</a>
								<?php } else { ?>
									<a href="/login">
										<i class="fa fa-sign-in"></i>
										Login
									</a>
								<?php } ?>
							</li>

							<?php if (isset($_SESSION['flag'])) {
								if ($_SESSION['flag'] == 2 || $_SESSION['flag'] == 3) { ?>
									<li><a href="/admin"><i class="fa fa-heart"></i> Truy cập hệ thống</a></li>
								<?php }
							} ?>

							<li><a href="/myAccount">
									<?php if (isset($_SESSION['idUser'])) { ?>
										<i class="fa fa-user"><b style="margin-left: 7px;"><?= h(trim($dataNameForUser[0]['username'])) ?></b></i>
									<?php } ?>
								</a>
							</li>

							<li><a href="/history-orders">
									<?php if (isset($_SESSION['idUser'])) { ?>
										<i class="fas fa-cart-plus"><b style="margin-left: 7px;">Lịch sử mua hàng</b></i>
									<?php } ?>
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div> <!-- End header area -->
	<div class="site-branding-area">
		<div class="container">
			<div class="row">
				<!-- Old Logo -->
				<!-- Old Cart -->
			</div>
		</div>
	</div> <!-- End site branding area -->

	<div class="mainmenu-area">
		<div class="container-fluid">
			<div class="row">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				<div class="navbar-collapse collapse">
					<div class="col-sm-3">
						<div class="logo">
							<h1>
								<?php
								echo $this->Html->image("NormalUsers/vertu.jpg", [
									"alt" => "logo",
									'url' => '/'
								]);
								?>
							</h1>
						</div>
					</div>
					<div class="col-sm-6">
						<ul class="nav navbar-nav">
							<li class="active"><a href="/">Home</a></li>
							<?php foreach ($dataCategories as $category) { ?>
								<li><a href="<?= $this->Url->build('/view-category/' . $category->id, ['fullBase' => true]) ?>"><?= h($category['category_name']) ?></a></li>
							<?php } ?>
							<li><a href="#">Contact</a></li>
						</ul>
					</div>
					<div class="col-sm-3">
						<a href="/carts">
							<div class="shopping-item">
								Giỏ Hàng <i class="fa fa-shopping-cart"></i>
								<span class="product-count">
									<?php
									echo isset($this->request->getSession()->read('cartData')['totalquantity']) ? $this->request->getSession()->read('cartData')['totalquantity'] : "0";
									?>
								</span>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div> <!-- End mainmenu area -->