<?php

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
							<form enctype="multipart/form-data" action="" class="checkout" method="post" name="checkout">
								<div id="customer_details" class="col2-set">

									<div class="col-3">
										<h3 id="order_review_heading">Thông Tin Tài khoản</h3>
										<table class="shop_table">
											<tbody>
												<tr class="cart_item">
													<th class="product-name">
														Họ Và Tên
													</th>
													<td class="product-total">
														<span class="amount"><?= h(trim($dataUser[0]['username'])) ?></span>
													</td>
												</tr>

												<tr class="cart_item">
													<th class="product-name">
														Số Điện Thoại
													</th>
													<td class="product-total">
														<span class="amount"><?= h(trim($dataUser[0]['phonenumber'])) ?></span>
													</td>
												</tr>

												<tr class="cart_item">
													<th class="product-name">
														Địa chỉ Email
													</th>
													<td class="product-total">
														<span class="amount"><?= h(trim($dataUser[0]['email'])) ?></span>
													</td>
												</tr>

												<tr class="cart_item">
													<th class="product-name">
														Địa chỉ
													</th>
													<td class="product-total">
														<span class="amount"><?= h(trim($dataUser[0]['address'])) ?></span>
													</td>
												</tr>

												<tr class="cart_item">
													<th class="product-name">
														Point Của Bạn
													</th>
													<td class="product-total">
														<span class="amount"><?= h(trim($dataUser[0]['point_user'])) ?></span>
													</td>
												</tr>
											</tbody>
										</table>
									</div>

								</div>
					</div>
					</form>
					<div class="col-3" style="display: inline-block">
						<div id="order_review" style="position: relative;">
							<div id="payment">
								<?php if (isset($_SESSION['idUser'])) { ?>
									<div class="form-row place-order">
										<a href="<?= $this->Url->build('/edit-account/' . $_SESSION['idUser'], ['fullBase' => true]) ?>">
											<input type="submit" data-value="Place order" value="Sửa thông tin" id="place_order" name="woocommerce_checkout_place_order" class="button alt">
										</a>
									</div>
								<?php } ?>
								<div class="clear"></div>
							</div>
						</div>
					</div>
					<div class="col-3" style="display: inline-block">
						<div id="order_review" style="position: relative;">
							<div id="payment">
								<?php if (isset($_SESSION['idUser'])) { ?>
									<div class="form-row place-order">
										<a href="/change-password">
											<input type="submit" data-value="Place order" value="Đổi mật khẩu" id="place_order" name="woocommerce_checkout_place_order" class="button alt">
										</a>
									</div>
								<?php } ?>
								<div class="clear"></div>
							</div>
						</div>
					<?php } else { ?>
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