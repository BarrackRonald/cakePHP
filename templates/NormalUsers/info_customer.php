<?php

echo $this->element('NormalUsers/header');
?>

<div class="product-big-title-area">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="product-bit-title text-center">
					<?php if (isset($dataUser)) { ?>
						<h2>Thông Tin Khách Hàng</h2>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row" style="margin: 10px 0 0 5px">
	<?= $this->Flash->render() ?>
</div>
<div class="single-product-area">
	<div class="zigzag-bottom"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="product-content-right">
					<div class="woocommerce">
						<?php if (isset($dataUser)) { ?>
							<form enctype="multipart/form-data" action="<?= URL_NORMALUSER_CONFIRM_ORDER ?>" class="checkout" method="post" name="checkout">
								<div id="customer_details" class="col2-set">
									<div class="col-3">
										<table class="shop_table" style="margin-bottom: 10px; ">
											<?php foreach ($dataUser as $User) { ?>
												<tbody>
													<tr class="cart_item">
														<th class="product-name ">
															Họ Và Tên
														</th>
														<td class="product-total text-left">
															<span class="amount m-l-12"><?= h($User['username']) ?></span>
															<input type="hidden" name="username" value="<?= h($User['username']) ?>">
														</td>
													</tr>

													<tr class="cart_item">
														<th class="product-name">
															Địa chỉ Email
														</th>
														<td class="product-total text-left">
															<span class="amount  m-l-12"><?= h($User['email']) ?> </span>
															<input type="hidden" name="email" value="<?= h($User['email']) ?>">
														</td>
													</tr>

													<tr class="cart_item">
														<th class="product-name">
															Địa chỉ giao hàng
														</th>
														<td class="product-total text-left">
															<div style="margin-left: 10px; margin-bottom: 10px; ">
																<input type="radio" name="address_status" value="0" id="default" onclick="addressDefault()" checked  />
																<label for="default" style="display: inline-block"><?= h($User['address']) ?></label> <br>
																<input type="hidden" name="address0" value="<?= h($User['address']) ?>">

																<input type="radio" name="address_status" value="1" id="other" onclick="addressOrder()" <?php if(isset($_SESSION['address_status'])){ echo 'checked'; } ?> />
																<label for="other" style="display: inline-block">Địa chỉ giao hàng khác</label> <br>
															</div>
														</td>
													</tr>
													<tr class="cart_item" id="address" style="display: none">
														<th class="product-name">
															Địa chỉ
														</th>
														<td class="product-total text-left">
															<div style="margin-left: 10px; margin-bottom: 10px; ">
																<p id="billing_address_1_field" class="form-row form-row-wide address-field validate-required">
																	<input name="address1" type="text" value="<?php if(isset($_SESSION['dataInput']['address1'])){ echo $_SESSION['dataInput']['address1']; }?>" placeholder="" id="billing_address_1" class="input-text ">
																	<?php if (isset($_SESSION['errorAddress'])) { ?>
																		<i style="color: red;">
																			<?= $_SESSION['errorAddress'] ?>
																		</i>
																	<?php
																	} ?><br>
																	<i>(*)Cần nhập đầy đủ địa chỉ của bạn</i>
																</p>
																<div class="clear"></div>
															</div>
														</td>
													</tr>

													<tr class="cart_item" id="phonenumber" style="display: none">
														<th class="product-name">
															Số Điện Thoại
														</th>
														<td class="product-total text-left">
															<div style="margin-left: 10px; margin-bottom: 10px; ">
																<p id="billing_phone_field" class="form-row form-row-last validate-required validate-phone">
																	<input  type="text" value="<?php if(isset($_SESSION['dataInput']['phonenumber1'])){ echo $_SESSION['dataInput']['phonenumber1']; } ?>" placeholder="" name="phonenumber1" id="billing_phone" class="input-text input_number" onkeypress='validate(event)' maxlength="10">
																	<?php if (isset($_SESSION['errorPhone1'])) { ?>
																		<i style="color: red;">
																			<?=$_SESSION['errorPhone1'] ?>
																		</i>
																	<?php
																	} ?><br>
																	<i>(*)Vui lòng nhập đúng số điện thoại</i>
																</p>
																<div class="clear"></div>
															</div>
														</td>
													</tr>

													<tr class="cart_item" id="phonenumberDefault">
														<th class="product-name">
															Số Điện Thoại
														</th>
														<td class="product-total text-left">
															<div>
																<span class="amount m-l-12"><?= h($User['phonenumber']) ?> </span>
																<input type="hidden" name="phonenumber0" value="<?= h($User['phonenumber']) ?>">
															</div>

															<div style="margin: 10px 0 10px 10px; ">
																<input type="checkbox" name="phonenumber_status" value="1" id="changeNumbers" onclick="changeNumber()" <?php if(isset($_SESSION['phonenumber_status'])){ echo 'checked'; } ?> />
																<label for="changeNumbers" style="display: inline-block; font-weight: 300;">Thay đổi số điện thoại</label> <br>
															</div>

															<div id="changeNumber" style="display: none" class="col-3">
																<div style="margin-left: 10px; margin-bottom: 10px; ">
																	<p id="billing_phone_field" class="form-row form-row-last validate-required validate-phone">
																		<input <?php if (isset($_SESSION['error']['phonenumber'])) { ?> style="border-color: red; color: red;" <?php } ?> type="text" value="<?php if(isset($_SESSION['dataInput']['phonenumber01'])){ echo $_SESSION['dataInput']['phonenumber01']; } ?>" placeholder="" id="billing_phone" name="phonenumber01" class="input-text input_number" onkeypress='validate(event)' maxlength="10">
																		<?php if (isset($_SESSION['errorPhone01'])) { ?>
																			<i style="color: red;">
																				<?= $_SESSION['errorPhone01'] ?><br>
																			</i>
																		<?php
																		} ?>
																		<i>(*)Vui lòng nhập đúng số điện thoại</i>
																	</p>
																	<div class="clear"></div>
																</div>
															</div>
														</td>
													</tr>

													<tr style="display: none" class="cart_item">
														<th class="product-name">
															Số Điện Thoại
														</th>
														<td class="product-total">
															<span class="amount"><?= h($User['phonenumber']) ?> </span>
														</td>
													</tr>

													<tr class="cart_item">
														<th class="product-name">
															Người Nhận
														</th>
														<td class="product-total text-left">
															<div style="margin-left: 10px; margin-bottom: 10px; ">
																<input type="checkbox" name="username_status" value="1" id="change" onclick="others()" <?php if(isset($_SESSION['username_status'])){ echo 'checked'; } ?> />
																<label for="change" style="display: inline-block; font-weight: 300;">Thay đổi tên người nhận</label> <br>
															</div>

															<div id="others" style="display: none" class="col-3">
																<div class="woocommerce-billing-fields">
																	<p id="billing_address_1_field" class="form-row form-row-wide address-field validate-required">
																		<label class="" for="billing_address_1">Họ và tên người nhận: <abbr title="required" class="required">*</abbr>
																		</label>
																		<input type="text" value="<?php if(isset($_SESSION['dataInput']['username1'])){ echo $_SESSION['dataInput']['username1']; } ?>" name="username1" placeholder="" id="billing_address_1" class="input-text ">
																		<?php if (isset($_SESSION['errorUsername1'])) { ?>
																			<i style="color: red;">
																				<?= $_SESSION['errorUsername1'] ?>
																			</i>
																		<?php
																		} ?>
																	</p>
																	<div class="clear"></div>
																</div>
															</div>
														</td>
													</tr>
												</tbody>
											<?php } ?>
										</table>
									</div>

									<div class="col-3">
										<div id="order_review" style="position: relative; display: inline-block;">
											<div id="payment">
												<div class="form-row place-order">
													<a href="/carts">
														<input type="button" data-value="Place order" value="Back" id="place_order" class="button alt button_back">
													</a>
												</div>
												<div class="clear"></div>
											</div>
										</div>
										<div id="order_review" style="position: relative; float: right;">
											<div id="payment">
												<div class="form-row place-order">
													<input type="submit" data-value="Place order" onclick="disable()" value="Xác nhận" id="place_order" class="button alt">
													<input type="submit" id="none" data-value="Place order" style="display: none" disabled value="Xác nhận" id="place_order" class="button alt">
												</div>
												<div class="clear"></div>
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
<script type="text/javascript">
	function addressOrder() {
		var address = document.getElementById("address").style.display;
		if (address == 'none') {
			document.getElementById("address").style.display = 'revert';
			document.getElementById("phonenumber").style.display = 'revert';
			document.getElementById("phonenumberDefault").style.display = 'none';
		}
	}

	function addressDefault() {
		var address = document.getElementById("address").style.display;

		if (address == 'revert') {
			document.getElementById("address").style.display = 'none';
			document.getElementById("phonenumber").style.display = 'none';
			document.getElementById("phonenumberDefault").style.display = 'revert';
		}
	}

	function others() {
		var others = document.getElementById("others").style.display;
		if (others == 'none') {
			document.getElementById("others").style.display = 'block';
		} else {
			document.getElementById("others").style.display = 'none';
		}
	}

	function changeNumber() {
		var changeNumber = document.getElementById("changeNumber").style.display;
		if (changeNumber == 'none') {
			document.getElementById("changeNumber").style.display = 'block';
		} else {
			document.getElementById("changeNumber").style.display = 'none';
		}
	}

	window.onload = function() {
		var checkbox = document.getElementById("changeNumbers").checked;
		var checkChangeUsername = document.getElementById("change").checked;
		var checkOther = document.getElementById("other").checked;
		if (checkbox == true) {
			document.getElementById("changeNumber").style.display = 'block';
		}

		if(checkChangeUsername == true){
			document.getElementById("others").style.display = 'block';
		}

		if(checkOther == true){
			document.getElementById("address").style.display = 'revert';
			document.getElementById("phonenumber").style.display = 'revert';
			document.getElementById("phonenumberDefault").style.display = 'none';
		}
	}

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