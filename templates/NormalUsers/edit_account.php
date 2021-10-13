<?php
use Cake\Utility\Text;
echo $this->element('NormalUsers/header');
?>

    <div class="product-big-title-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-bit-title text-center">

                        <h2>Chỉnh sửa thông tin tài khoản</h2>

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
                            <?= $this->Form->create($dataUser[0]) ?>

                                <div id="customer_details" class="col2-set">
                                    <div class="col-3">
                                        <div class="woocommerce-billing-fields">
                                            <h3>Thông tin tài khoản</h3>
                                            <p id="billing_first_name_field" class="form-row form-row-first validate-required">
                                                <label class="" for="billing_first_name">FullName: <abbr title="required" class="required">*</abbr>
                                                </label>
                                                <input type="text" value="<?=h(trim($dataUser[0]['username']))?>" placeholder="" id="billing_first_name" name="username" class="input-text " >
                                            </p>
                                            <div class="clear"></div>

                                            <p id="billing_address_1_field" class="form-row form-row-wide address-field validate-required">
                                                <label class="" for="billing_address_1">PhoneNumber: <abbr title="required" class="required">*</abbr>
                                                </label>
                                                <input type="text" value="<?=h(trim($dataUser[0]['phonenumber']))?>" placeholder="" id="billing_address_1" name="phonenumber" class="input-text input_number " onkeypress='validate(event)'  maxlength = "10"  >
                                            </p>

                                            <div class="clear"></div>

                                            <p id="billing_address_1_field" class="form-row form-row-wide address-field validate-required">
                                                <label class="" for="billing_address_1">Address: <abbr title="required" class="required">*</abbr>
                                                </label>
                                                <input type="text" value="<?=h(trim($dataUser[0]['address']))?>" placeholder="" id="billing_address_1" name="address" class="input-text " >
                                            </p>

                                            <div class="clear"></div>

                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-3">

                                        <div id="order_review" style="position: relative;">

                                            <div id="payment">

                                                <div class="form-row place-order">

                                                    <input type="submit" data-value="Place order" value="Xác nhận" id="place_order" name="woocommerce_checkout_place_order" class="button alt">


                                                </div>

                                            <div class="clear"></div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?= $this->Form->end() ?>
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
<script>
    function validate(evt) {
        var theEvent = evt || window.event;

        // Handle paste
        if (theEvent.type === 'paste') {
            key = event.clipboardData.getData('text/plain');
        } else {
            // Handle key press
            var key = theEvent.keyCode || theEvent.which;
            key = String.fromCharCode(key);
        }
        var regex = /[0-9]|\./;
        if (!regex.test(key)) {
            theEvent.returnValue = false;
            if (theEvent.preventDefault) theEvent.preventDefault();
        }
    }
</script>