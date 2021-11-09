<?php
$this->disableAutoLayout();
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Dashboard - VerTu</title>

	<link rel="stylesheet" href="../../css/Admin/bootstrap.css">
	<link rel="stylesheet" href="../../css/Admin/main.css">

	<link rel="stylesheet" href="../../vendor/Admin/chartjs/Chart.min.css">
	<link rel="stylesheet" href="../../vendor/Admin/perfect-scrollbar/perfect-scrollbar.css">
	<link rel="stylesheet" href="../../css/Admin/app.css">
	<link rel="stylesheet" href="../../css/Admin/cake.css">
	<link rel="shortcut icon" href="../../img/Admin/favicon.svg" type="image/x-icon">

	
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
	<script src="../../js/Admin/ckeditor/ckeditor.js"></script>
	<!-- Sweet Alert -->
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
	<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/Login/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

</head>

<body>
	<div id="app">
		<div id="main">
			<nav class="navbar navbar-header navbar-expand navbar-light">
				<a class="sidebar-toggler" href="#"><span class="navbar-toggler-icon"></span></a>
				<button class="btn navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav d-flex align-items-center navbar-light ms-auto">
						<li class="dropdown">
							<a href="#" data-bs-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
								<div class="avatar me-1">
								<i data-feather="user"></i>
								</div>
								<div class="d-none d-md-block d-lg-inline-block">
									<?php if (isset($_SESSION['username'])) { ?>
										<?= h($dataNameForUser[0]['username']) ?>
									<?php } ?>
								</div>
							</a>
							<div class="dropdown-menu dropdown-menu-end">
								<?php if (isset($_SESSION['flag'])) {
									if ($_SESSION['flag'] == 2 || $_SESSION['flag'] == 3) { ?>
										<a class="dropdown-item" href="/"><i class="fas fa-home"></i> Trang Home</a>
								<?php }
								} ?>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="/logout"><i data-feather="log-out"></i> Logout</a>
							</div>
						</li>
					</ul>
				</div>
			</nav>