<?php

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
							<?= $this->Form->create() ?>
							<div id="customer_details" class="col2-set">
								<div class="col-3">
									<div class="woocommerce-billing-fields">
										<h3>Thông tin tài khoản</h3>
										<p id="billing_first_name_field" class="form-row form-row-first validate-required">
											<label class="" for="billing_first_name">FullName: <abbr title="required" class="required">*</abbr>
											</label>
											<input type="text" value="<?= h(trim($dataUser[0]['username'])) ?>" placeholder="" id="billing_first_name" name="username" class="input-text ">
											<?php if (isset($error['username'])) { ?>
												<i style="color: red;">
													<?= implode($error['username']) ?>
												</i>
											<?php } ?>
										</p>
										<div class="clear"></div>

										<p id="billing_address_1_field" class="form-row form-row-wide address-field validate-required">
											<label class="" for="billing_address_1">PhoneNumber: <abbr title="required" class="required">*</abbr>
											</label>
											<input type="text" value="<?= h(trim($dataUser[0]['phonenumber'])) ?>" placeholder="" id="billing_address_1" name="phonenumber" class="input-text input_number " onkeypress='validate(event)' maxlength="10">
											<?php if (isset($error['phonenumber'])) { ?>
												<i style="color: red;">
												<?= implode('<br>', $error['phonenumber']) ?>
												</i>
											<?php } ?>
										</p>
										<div class="clear"></div>

										<p id="billing_address_1_field" class="form-row form-row-wide address-field validate-required">
											<label class="" for="billing_address_1">Address: <abbr title="required" class="required">*</abbr>
											</label>
											<input type="text" value="<?= h(trim($dataUser[0]['address'])) ?>" placeholder="" id="billing_address_1" name="address" class="input-text ">
											<?php if (isset($error['address'])) { ?>
												<i style="color: red;">
													<?= implode($error['address']) ?>
												</i>
											<?php } ?>
										</p>
										<div class="clear"></div>

									</div>
								</div>
							</div>
							<div class="col-3">
								<div id="order_review" class="button-back">
									<div id="payment">
										<div class="form-row place-order">
											<a href="/myAccount">
												<input type="button" data-value="Place order" value="Back" id="place_order" name="woocommerce_checkout_place_order" class="button alt button_back">
											</a>
										</div>
										<div class="clear"></div>
									</div>
								</div>

								<div id="order_review" class="button-next">
									<div id="payment">
										<div class="form-row place-order">
											<input type="submit" data-value="Place order" value="Xác nhận" id="place_order" class="button alt">
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