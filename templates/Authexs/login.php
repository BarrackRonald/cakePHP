<?php

echo $this->element('NormalUsers/header');
?>
<div class="row" style="margin-left: 5px">
	<?= $this->Flash->render() ?>
</div>
<div style="background-color: #f5f5f5;">
	<div class="zigzag-bottom"></div>
	<div class="container">
		<div class="row" style="margin: 0 359px;">
			<div class="wrap-login100">

				<?php echo $this->Form->create(null, ['class' => 'login100-form validate-form']); ?>

				<span class="login100-form-title">
					<h3><b>Đăng Nhập</b></h3>
				</span>

				<label for="email">Email:</label>
				<div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
					<input class="input100" id="email" type="text" name="email" style="padding: 0 30px 0 50px; border: 1px solid;" placeholder="Email" value="<?php if (isset($_SESSION['email'])) {
																																									echo h($_SESSION['email']);
																																								} ?>">
					<span class="focus-input100"></span>
					<span class="symbol-input100">
						<i class="fa fa-envelope" aria-hidden="true"></i>
					</span>
				</div>
				<label class="m-t-15" for="password">Password:</label>
				<div class="wrap-input100 validate-input" data-validate="Password is required">
					<input id="myInput" class="input100" id="password" type="password" style="padding: 0 30px 0 50px; border: 1px solid;" name="password" placeholder="Password" value="<?php if (isset($_SESSION['password'])) {
																																															echo h($_SESSION['password']);
																																														} ?>">
					<span class="focus-input100"></span>
					<span class="symbol-input100">
						<i class="fa fa-lock" aria-hidden="true"></i>
					</span>
				</div>
				<div class="text-left p-t-1 m-l-25">
					<a class="txt2">
						<input id="check" type="checkbox" onclick="myFunction()"><label class="m-l-5" for="check">Hiện mật khẩu</label>
					</a>
				</div>

				<div class="container-login100-form-btn">
					<button type="submit" class="login100-form-btn">
						Login
					</button>
				</div>

				<div class="text-center p-t-12">
					<span class="txt1">
						Quên
					</span>
					<a class="txt2" href="/forgotPassword">
						Tài khoản / Mật khẩu?
					</a>
				</div>

				<div class="text-center p-t-12">
					<a class="txt2" href="/register">
						Tạo tài khoản mới
						<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
					</a>
				</div>
				<?php echo $this->Form->end(); ?>

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
		function myFunction() {
			var x = document.getElementById("myInput");
			if (x.type === "password") {
				x.type = "text";
			} else {
				x.type = "password";
			}
		}
	</script>