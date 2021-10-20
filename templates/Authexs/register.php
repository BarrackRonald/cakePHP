<?php
use Cake\Utility\Text;
echo $this->element('NormalUsers/header');
?>
    <div class="row" style="margin-left: 5px" >
            <?= $this->Flash->render() ?>
    </div>
    <div style="background-color: #f5f5f5;">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row" style="margin: 0 200px;">
            <div class="wrap-login100">

                <form enctype="multipart/form-data" action="/register" method="POST" style="width: 90%">
					<span class="login100-form-title">
						<h3><b>Đăng Ký Tài Khoản</b></h3>
					</span>

                    <label for="fullname">Họ và tên:</label>
					<div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
						<input class="input100" id="fullname" type="text" name="fullname" style="padding: 0 30px 0 50px; border: 1px solid;" placeholder="Họ và tên" value="<?php if(isset($_SESSION['infoUser']['fullname'])){ echo $_SESSION['infoUser']['fullname']; }?>">
                        <span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fas fa-file-signature" aria-hidden="true"></i>
						</span>
					</div>
                    <div style="margin-top: -8px;">
                        <?php  if(isset($_SESSION['error']['username'])){?>
                                <i style="color: red;">
                                    <?= implode($_SESSION['error']['username'])?>
                                </i>
                        <?php }?>
                    </div>

                    <label class="m-t-12" for="email">Email:</label>
					<div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
						<input class="input100" id="email" type="text" name="email" style="padding: 0 30px 0 50px; border: 1px solid;" placeholder="Email" value="<?php if(isset($_SESSION['infoUser']['email'])){ echo $_SESSION['infoUser']['email']; }?>">
                        <span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>
                    <div style="margin-top: -8px;">
                        <?php  if(isset($_SESSION['error']['email'])){?>
                                <i style="color: red;">
                                    <?= implode($_SESSION['error']['email'])?>
                                </i>
                        <?php }?>
                    </div>

                    <label class="m-t-12" for="phonenumber">Số điện thoại:</label>
					<div class="wrap-input100 validate-input">
						<input onkeypress="validate(event)"  maxlength = "10" class="input100" id="phonenumber" type="text" name="phonenumber" style="padding: 0 30px 0 50px; border: 1px solid;" placeholder="Số điện thoại" value="<?php if(isset($_SESSION['infoUser']['phonenumber'])){ echo $_SESSION['infoUser']['phonenumber']; }?>">

                        <span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-phone-volume" aria-hidden="true"></i>
						</span>
					</div>
                    <div style="margin-top: -8px;">
                        <?php  if(isset($_SESSION['error']['phonenumber'])){?>
                                <i style="color: red;">
                                    <?= implode($_SESSION['error']['phonenumber'])?>
                                </i>
                        <?php }?>
                    </div>

                    <label class="m-t-12" for="address">Địa chỉ:</label>
					<div class="wrap-input100 validate-input">
						<input class="input100" id="address" type="text" name="address" style="padding: 0 30px 0 50px; border: 1px solid;" placeholder="Địa chỉ" value="<?php if(isset($_SESSION['infoUser']['address'])){ echo $_SESSION['infoUser']['address']; }?>">
                        <span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-address-card" aria-hidden="true"></i>
						</span>
					</div>
                    <div style="margin-top: -8px;">
                        <?php  if(isset($_SESSION['error']['address'])){?>
                                <i style="color: red;">
                                    <?= implode($_SESSION['error']['address'])?>
                                </i>
                        <?php }?>
                    </div>

                    <label class="m-t-12" for="myInput1">Mật khẩu:</label>
					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<input id="myInput1" class="input100" type="password" style="padding: 0 30px 0 50px; border: 1px solid;" name="password" placeholder="Mật khẩu" value="<?php if(isset($_SESSION['infoUser']['password'])){ echo $_SESSION['infoUser']['password']; }?>">
                        <span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
                    <div style="margin-top: -8px;">
                        <?php  if(isset($_SESSION['error']['password'])){?>
                                <i style="color: red;">
                                    <?= implode($_SESSION['error']['password'])?>
                                </i>
                        <?php }?>
                    </div>

                    <label class="m-t-12" for="myInput2">Nhập lại Mật khẩu:</label>
					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<input id="myInput2" class="input100"  type="password" style="padding: 0 30px 0 50px; border: 1px solid;" name="retypePassword" placeholder="Nhập lại mật khẩu" value="<?php if(isset($_SESSION['infoUser']['retypePassword'])){ echo $_SESSION['infoUser']['retypePassword']; }?>">
                        <span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
                    <div style="margin-top: -8px;">
                        <?php  if(isset($_SESSION['error']['retypePassword'])){?>
                                <i style="color: red;">
                                    <?= implode($_SESSION['error']['retypePassword'])?>
                                </i>
                        <?php }?>
                    </div>

                    <div class="text-left p-t-1 m-l-25">
						<a class="txt2">
							<input id="check" type="checkbox" onclick="myFunction()"><label class="m-l-5" for="check">Hiện mật khẩu</label>
						</a>
					</div>

					<div class="container-login100-form-btn">
						<button type="submit" class="login100-form-btn">
							Đăng ký
						</button>
					</div>

					<div class="text-center p-t-12">
						<a class="txt2" href="/login">
							Đăng nhập
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
                </form>

			</div>

            <div class="row">
                <div class="col-md-12">
                    <div class="product-pagination text-center pagination-button">
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

	function myFunction() {
        var x = document.getElementById("myInput1");
        var y = document.getElementById("myInput2");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }

        if (y.type === "password") {
            y.type = "text";
        } else {
            y.type = "password";
        }
    }
</script>