
<?php
	echo $this->element('Login/header');
?>
<body>
	<div class="limiter">
	<?= $this->Flash->render() ?>
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="img/Login/img-01.png" alt="IMG">
				</div>

					<?php echo $this->Form->create(null, ['class' => 'login100-form validate-form']); ?>

					<span class="login100-form-title">
						Member Login
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
						<input class="input100" type="text" name="email" placeholder="Email" value="<?php if(isset($_SESSION['email'])){ echo $_SESSION['email']; }?>">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<input class="input100" type="password" name="password" placeholder="Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
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
						<a class="txt2" href="/forgotpassword">
							Tài khoản / Mật khẩu?
						</a>
					</div>
					<div class="text-center p-t-12">
						<a class="txt2" href="/">
							Trở lại trang chủ?
						</a>
					</div>

					<div class="text-center p-t-136">
						<a class="txt2" href="/register">
							Tạo tài khoản mới
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
					<?php echo $this->Form->end(); ?>

			</div>
		</div>
	</div>

<?php
echo $this->element('Login/footer');
?>
