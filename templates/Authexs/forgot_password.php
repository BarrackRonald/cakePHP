<?php
echo $this->element('NormalUsers/header');
?>
<div class="row" style="margin-left: 5px">
	<?= $this->Flash->render() ?>
</div>
<div style="background-color: #f5f5f5;">
	<div class="zigzag-bottom"></div>
	<div class="container">
		<div class="row" style="margin: 0 200px;">
			<div class="wrap-login100">

				<form enctype="multipart/form-data" action="<?= URL_FORGOT_PWS ?>" method="POST" style="width: 90%">
					<span class="login100-form-title">
						<h3><b>Lấy Lại Mật Khẩu</b></h3>
					</span>

					<label for="email">Email:</label>
					<div class="wrap-input100 validate-input" data-validate="Valid email is required: ex@abc.xyz">
						<input class="input100" id="email" type="text" name="email" style="padding: 0 30px 0 50px; border: 1px solid;" placeholder="Email">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>
					<div style="margin-top: -8px;">
						<?php if (isset($_SESSION['error_forgot'])) {
							if (isset($_SESSION['error_forgot']['email'])) { ?>
								<i style="color: red;">
									<?= implode($_SESSION['error_forgot']['email']) ?>
								</i>
							<?php } else { ?>
								<i style="color: red;">
									<?= implode($_SESSION['error_forgot']['email_null']) ?>
								</i>
						<?php }
						} ?>
					</div>

					<div class="container-login100-form-btn">
						<button type="submit" id="submit" onclick="disable()" class="login100-form-btn">
							Xác nhận
						</button>
						<button type="submit" id="none" style="display: none" disabled class="login100-form-btn">
							Xác nhận
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
	function disable(){
		document.getElementById("submit").style.display = "none";
		document.getElementById("none").style.display = "block";
	}
</script>