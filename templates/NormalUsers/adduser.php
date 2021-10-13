<?php
use Cake\Utility\Text;
echo $this->element('NormalUsers/header');
?>

    <div class="product-big-title-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-bit-title text-center">

                        <h2>Xác Nhận Đơn Hàng</h2>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="single-product-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-content-right">
                        <div class="woocommerce">

                            <form id="login-form-wrap" class="login collapse" method="post">

                                <p>If you have shopped with us before, please enter your details in the boxes below. If you are a new customer please proceed to the Billing &amp; Shipping section.</p>

                                <p class="form-row form-row-first">
                                    <label for="username">Username or email <span class="required">*</span>
                                    </label>
                                    <input type="text" id="username" name="username" class="input-text">
                                </p>
                                <p class="form-row form-row-last">
                                    <label for="password">Password <span class="required">*</span>
                                    </label>
                                    <input type="password" id="password" name="password" class="input-text">
                                </p>
                                <div class="clear"></div>


                                <p class="form-row">
                                    <input type="submit" value="Login" name="login" class="button">
                                    <label class="inline" for="rememberme"><input type="checkbox" value="forever" id="rememberme" name="rememberme"> Remember me </label>
                                </p>
                                <p class="lost_password">
                                    <a href="#">Lost your password?</a>
                                </p>

                                <div class="clear"></div>
                            </form>


                            <form id="coupon-collapse-wrap" method="post" class="checkout_coupon collapse">

                                <p class="form-row form-row-first">
                                    <input type="text" value="" id="coupon_code" placeholder="Coupon code" class="input-text" name="coupon_code">
                                </p>

                                <p class="form-row form-row-last">
                                    <input type="submit" value="Apply Coupon" name="apply_coupon" class="button">
                                </p>

                                <div class="clear"></div>
                            </form>

                        <?php if(isset($dataProds)){ ?>
                            <form enctype="multipart/form-data" action="/addordersnonelogin" class="checkout" method="post" name="checkout">

                                <div id="customer_details" class="col2-set">

                                    <!-- Test -->
                                    <div class="col-3">
                                    <h3 id="order_review_heading">Thông Tin Khách hàng</h3>
                                    <table class="shop_table">
                                                <tbody>
                                                    <tr class="cart_item">
                                                        <th class="product-name">
                                                            Họ Và Tên
                                                        </th>
                                                        <td class="product-total">
                                                            <span class="amount"><?= $dataProds['infoUser']['username'] ?></span> 
                                                        </td>
                                                    </tr>

                                                    <tr class="cart_item">
                                                        <th class="product-name">
                                                            Số Điện Thoại
                                                        </th>
                                                        <td class="product-total">
                                                            <span class="amount"><?= $dataProds['infoUser']['phonenumber'] ?> </span> 
                                                        </td>
                                                    </tr>

                                                    <tr class="cart_item">
                                                        <th class="product-name">
                                                            Địa chỉ Email
                                                        </th>
                                                        <td class="product-total">
                                                            <span class="amount"><?= $dataProds['infoUser']['email'] ?> </span> 
                                                        </td>
                                                    </tr>

                                                    <tr class="cart_item">
                                                        <th class="product-name">
                                                            Địa chỉ
                                                        </th>
                                                        <td class="product-total">
                                                            <span class="amount"><?= $dataProds['infoUser']['address'] ?> </span> 
                                                        </td>
                                                    </tr>

                                                </tbody>
                                                
                                            </table>

                                    </div>
                                    <!-- End Test -->

                                    <div class="col-3">
                                    <h3 id="order_review_heading">Thông Tin Đơn Hàng</h3>

                                        <div id="order_review" style="position: relative;">
                                            <table class="shop_table">
                                                <thead>
                                                    <tr>
                                                        <th class="product-name">Sản Phẩm</th>
                                                        <th class="product-total">Tổng</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($dataProds['cart'] as  $product) {?>
                                                        <tr class="cart_item">
                                                            <td class="product-name">
                                                                <?= $product['name'] ?> <strong class="product-quantity">× <?= $product['quantity'] ?></strong> </td>
                                                            <td class="product-total">
                                                                <span class="amount"><?= $product['totalAmount'] ?></span> </td>
                                                        </tr>
                                                    <?php }?>
                                                </tbody>
                                                <tfoot>

                                                    <tr class="shipping">
                                                        <th>Tổng Point</th>
                                                        <td>
                                                            <?= $dataProds['totalAllPoint'] ?>
                                                            <input type="hidden" class="shipping_method" value="free_shipping" id="shipping_method_0" data-index="0" name="shipping_method[0]">
                                                        </td>
                                                    </tr>

                                                    <tr class="order-total">
                                                        <th>Tổng Đơn Hàng</th>
                                                        <td><strong><span class="amount"><?= $dataProds['totalAllAmount'] ?></span></strong> </td>
                                                        <input type="hidden" name="totalAllPoint" value="<?= $dataProds['totalAllPoint'] ?>">
                                                        <input type="hidden" name="totalAllAmount" value="<?= $dataProds['totalAllAmount'] ?>">
                                                        <input type="hidden" name="totalQuantity" value="<?= $dataProds['totalquantity'] ?>">
                                                    </tr>

                                                </tfoot>
                                            </table>


                                            <div class="col-3">

                                        <div id="order_review" style="position: relative; display: inline-block;">

                                            <div id="payment">

                                                <div class="form-row place-order" >
                                                    <a href="/billorder">
                                                        <input type="button"  data-value="Place order" value="Back" id="place_order" name="woocommerce_checkout_place_order" class="button alt button_back">
                                                    </a>


                                                </div>

                                            <div class="clear"></div>


                                            </div>
                                        </div>

                                        <div id="order_review" style="position: relative; float: right;">

                                            <div id="payment">

                                                <div class="form-row place-order">

                                                    <input type="submit" data-value="Place order" value="Xác nhận" id="place_order" name="woocommerce_checkout_place_order" class="button alt">

                                                </div>

                                            <div class="clear"></div>


                                            </div>
                                        </div>
                                    </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </form>
                       <?php } ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
echo $this->element('NormalUsers/footer');
?>