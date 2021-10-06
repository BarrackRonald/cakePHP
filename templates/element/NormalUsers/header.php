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
    <title>Ustora Demo</title>
    
    <!-- Google Fonts
    <link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,200,300,700,600' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:400,100' rel='stylesheet' type='text/css'> -->
    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../css/NormalUsers/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../css/NormalUsers/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../css/NormalUsers/owl.carousel.css">
    <link rel="stylesheet" href="../../css/NormalUsers/style.css">
    <link rel="stylesheet" href="../../css/NormalUsers/responsive.css">
    

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- //Ajax -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js" integrity="sha512-n/4gHW3atM3QqRcbCn6ewmpxcLAHGaDjpEBu4xZd47N0W2oQ+6q7oc3PXstrJYXcbNU1OHdQ1T7pAP+gi5Yu8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  </head>
  <body>

    <div class="header-area">
    <?= $this->Flash->render() ?>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="user-menu">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="user-menu">
                        <ul>
                            <li><a href="/myaccount">
                                <?php if(isset($_SESSION['username'])){ ?>
                                    <i class="fa fa-user"><?= $_SESSION['username'] ?></i>
                                <?php }else{?>
                                    <i class="fa fa-user">My Account</i>
                                <?php } ?>
                                </a>
                            </li>
                            <?php if(isset($_SESSION['flag'])){ if($_SESSION['flag'] == 2 || $_SESSION['flag'] == 3){?>
                                <li><a href="/admin"><i class="fa fa-heart"></i> Truy cập Admin</a></li>
                            <?php } }?>
                        </ul>
                        <ul>
                            <li>
                                <?php  $session = $this->request->getSession();
                                    if($session->check('username')){?>
                                        <a href="/logout">
                                            <i class="fa fa-sign-out">
                                                Logout
                                            </i>
                                        </a>
                                    <?php }else{?>
                                        <a href="/login">
                                            <i class="fa fa-sign-in"></i>
                                            Login
                                        </a>
                                <?php } ?>
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
                <div class="col-sm-6">
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
                    <div class="shopping-item">
                        <a href="/carts">Giỏ Hàng <i class="fa fa-shopping-cart"></i> 
                        <span class="product-count">
                            <?php
                                echo isset($this->request->getSession()->read('cartData')['totalquantity']) ? $this->request->getSession()->read('cartData')['totalquantity'] : "0";
                            ?>
                        </span></a>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End site branding area -->
    
    <div class="mainmenu-area">
        <div class="container">
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
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="/">Home</a></li>
                        <?php foreach ($dataCategories as $category) { ?>
                            <li><a href="#"><?= $category['category_name'] ?></a></li>
                        <?php } ?>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>  
            </div>
        </div>
    </div> <!-- End mainmenu area -->
    
