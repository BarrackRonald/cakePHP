<?php
use Cake\Utility\Text;
echo $this->element('NormalUsers/header');
?>
    <div class="product-big-title-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-bit-title text-center">
                    <?php if(isset($dataUser)){ ?>
                        <h2>Shopping Cart</h2>
                    <?php } else{?>
                        <h2>Thông Tin Khách Hàng</h2>
                    <?php }?>
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

                        <?php if(isset($dataUser)){ ?>
                            <form enctype="multipart/form-data" action="/addorders" class="checkout" method="post" name="checkout">

                                <div id="customer_details" class="col2-set">
                                    <div class="col-3">
                                        <div class="woocommerce-billing-fields">
                                            <h3>Billing Details</h3>
                                            <?php foreach ($dataUser as $User) {?>
                                            <p id="billing_first_name_field" class="form-row form-row-first validate-required">
                                                <label class="" for="billing_first_name">FullName <abbr title="required" class="required">*</abbr>
                                                </label>
                                                <input type="hidden" name="idUser" value="<?= $User['id'] ?>">
                                                <input type="text" value="<?= $User['username'] ?>" placeholder="" id="billing_first_name" name="fullname" class="input-text " readonly>
                                            </p>
                                            <div class="clear"></div>

                                            <p id="billing_address_1_field" class="form-row form-row-wide address-field validate-required">
                                                <label class="" for="billing_address_1">Address <abbr title="required" class="required">*</abbr>
                                                </label>
                                                <input type="text" value="<?= $User['address'] ?>" placeholder="" id="billing_address_1" name="address" class="input-text " readonly>
                                            </p>

                                            <div class="clear"></div>

                                            <p id="billing_email_field" class="form-row form-row-first validate-required validate-email">
                                                <label class="" for="billing_email">Email Address <abbr title="required" class="required">*</abbr>
                                                </label>
                                                <input type="text" value="<?= $User['email'] ?>" placeholder="" id="billing_email" name="email" class="input-text " readonly>
                                            </p>

                                            <p id="billing_phone_field" class="form-row form-row-last validate-required validate-phone">
                                                <label class="" for="billing_phone">Phone <abbr title="required" class="required">*</abbr>
                                                </label>
                                                <input type="text" value="<?= $User['phonenumber'] ?>" placeholder="" id="billing_phone" name="phonenumber" class="input-text " readonly>
                                            </p>
                                            <div class="clear"></div>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="col-3">
                                    <h3 id="order_review_heading">Your order</h3>

                                        <div id="order_review" style="position: relative;">
                                            <table class="shop_table">
                                                <thead>
                                                    <tr>
                                                        <th class="product-name">Product</th>
                                                        <th class="product-total">Total</th>
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
                                                        <th>Total Point</th>
                                                        <td>
                                                            <?= $dataProds['totalAllPoint'] ?>
                                                            <input type="hidden" class="shipping_method" value="free_shipping" id="shipping_method_0" data-index="0" name="shipping_method[0]">
                                                        </td>
                                                    </tr>

                                                    <tr class="order-total">
                                                        <th>Order Total</th>
                                                        <td><strong><span class="amount"><?= $dataProds['totalAllAmount'] ?></span></strong> </td>
                                                        <input type="hidden" name="totalAllPoint" value="<?= $dataProds['totalAllPoint'] ?>">
                                                        <input type="hidden" name="totalAllAmount" value="<?= $dataProds['totalAllAmount'] ?>">
                                                        <input type="hidden" name="totalQuantity" value="<?= $dataProds['totalquantity'] ?>">
                                                    </tr>

                                                </tfoot>
                                            </table>


                                            <div id="payment">

                                                <div class="form-row place-order">

                                                    <input type="submit" data-value="Place order" value="Place order" id="place_order" name="woocommerce_checkout_place_order" class="button alt">


                                                </div>

                                            <div class="clear"></div>

                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </form>
                       <?php }else{?>
                            <form enctype="multipart/form-data" action="/adduser" class="checkout" method="post" name="checkout">

                                <div id="customer_details" class="col2-set">
                                    <div class="col-3">
                                        <div class="woocommerce-billing-fields">
                                            <h3>Billing Details</h3>

                                            <p id="billing_first_name_field" class="form-row form-row-first validate-required">
                                                <label class="" for="billing_first_name">FullName <abbr title="required" class="required">*</abbr>
                                                </label>
                                                <input type="text" value="" placeholder="" id="billing_first_name" name="fullname" class="input-text " >
                                               <?php  if(isset($error['username'])){
                                                   foreach ($error['username'] as  $error) {?>
                                                        <i>
                                                            <?= $error['_empty'] ?>
                                                        <i>
                                                    <?php }
                                                }?>

                                            </p>
                                            <div class="clear"></div>

                                            <p id="billing_address_1_field" class="form-row form-row-wide address-field validate-required">
                                                <label class="" for="billing_address_1">Address <abbr title="required" class="required">*</abbr>
                                                </label>
                                                <input type="text" value="" placeholder="" id="billing_address_1" name="address" class="input-text " >
                                            </p>

                                            <div class="clear"></div>

                                            <p id="billing_email_field" class="form-row form-row-first validate-required validate-email">
                                                <label class="" for="billing_email">Email Address <abbr title="required" class="required">*</abbr>
                                                </label>
                                                <input type="text" value="" placeholder="" id="billing_email" name="email" class="input-text " >
                                            </p>

                                            <p id="billing_phone_field" class="form-row form-row-last validate-required validate-phone">
                                                <label class="" for="billing_phone">Phone <abbr title="required" class="required">*</abbr>
                                                </label>
                                                <input type="text" value="" placeholder="" id="billing_phone" name="phonenumber" class="input-text " >
                                            </p>
                                            <div class="clear"></div>
                                            <div class="create-account">
                                                <p>Chúng tôi sẽ một tài khoản bằng thông tin của bạn. Nếu bạn là khách hàng cũ, vui lòng Đăng nhập ở trên!!!</p>
                                                <p id="account_password_field" class="form-row validate-required">
                                                    <label class="" for="account_password">Account password <abbr title="required" class="required">*</abbr>
                                                    </label>
                                                    <input type="password" value="" placeholder="Password" id="account_password" name="password" class="input-text">
                                                </p>
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-3">

                                        <div id="order_review" style="position: relative;">

                                            <div id="payment">

                                                <div class="form-row place-order">

                                                    <input type="submit" data-value="Place order" value="Next" id="place_order" name="woocommerce_checkout_place_order" class="button alt">


                                                </div>

                                            <div class="clear"></div>

                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </form>
                        <?php }?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
echo $this->element('NormalUsers/footer');
?>