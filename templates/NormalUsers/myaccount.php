<?php
use Cake\Utility\Text;
echo $this->element('NormalUsers/header');
?>

    <div class="product-big-title-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-bit-title text-center">

                        <h2>My Account</h2>

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
                            <div class="row">
                                <div class="col-12 col-md-6 order-md-1 order-last">
                                    <?= $this->Flash->render() ?>
                                </div>
                            </div>

                        <?php if(isset($dataUser)){ ?>
                            <form enctype="multipart/form-data" action="" class="checkout" method="post" name="checkout">

                                <div id="customer_details" class="col2-set">

                                    <!-- Test -->
                                    <div class="col-3">
                                    <h3 id="order_review_heading">Thông Tin Tài khoản</h3>
                                    <table class="shop_table">
                                                <tbody>
                                                    <tr class="cart_item">
                                                        <th class="product-name">
                                                            Họ Và Tên
                                                        </th>
                                                        <td class="product-total">
                                                            <span class="amount"><?=h(trim($dataUser[0]['username']))?></span>
                                                        </td>
                                                    </tr>

                                                    <tr class="cart_item">
                                                        <th class="product-name">
                                                            Số Điện Thoại
                                                        </th>
                                                        <td class="product-total">
                                                            <span class="amount"><?=h(trim($dataUser[0]['phonenumber']))?></span>
                                                        </td>
                                                    </tr>

                                                    <tr class="cart_item">
                                                        <th class="product-name">
                                                            Địa chỉ Email
                                                        </th>
                                                        <td class="product-total">
                                                            <span class="amount"><?=h(trim($dataUser[0]['email']))?></span>
                                                        </td>
                                                    </tr>

                                                    <tr class="cart_item">
                                                        <th class="product-name">
                                                            Địa chỉ
                                                        </th>
                                                        <td class="product-total">
                                                            <span class="amount"><?=h(trim($dataUser[0]['address']))?></span>
                                                        </td>
                                                    </tr>

                                                    <tr class="cart_item">
                                                        <th class="product-name">
                                                            Point Của Bạn
                                                        </th>
                                                        <td class="product-total">
                                                            <span class="amount"><?=h(trim($dataUser[0]['point_user']))?></span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                    </div>
                                    <!-- End Test -->

                                    </div>
                                </div>
                            </form>
                            <div class="col-3">
                                <div id="order_review" style="position: relative;">
                                    <div id="payment">
                                    <?php  if(isset($_SESSION['idUser'])){?>
                                        <div class="form-row place-order">
                                            <a href="<?= $this->Url->build('/edit-account/' . $_SESSION['idUser'], ['fullBase' => true]) ?>">
                                                <input type="submit" data-value="Place order" value="Sửa thông tin" id="place_order" name="woocommerce_checkout_place_order" class="button alt">
                                            </a>
                                        </div>
                                    <?php }?>
                                    <div class="clear"></div>
                                </div>
                            </div>
                       <?php } else{ ?>
                        <form enctype="multipart/form-data" action="" class="checkout" method="post" name="checkout">
                            <div id="customer_details" class="col2-set">
                                <div style="text-align: center;" class="col-4">
                                    <div class="woocommerce-billing-fields">
                                        <h3>Tài khoản chưa đăng nhập</h3>
                                        <div class="clear"></div>
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