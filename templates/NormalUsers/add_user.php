<?php

echo $this->element('NormalUsers/header');
?>
<div class="row" style="margin-left: 5px">
	<?= $this->Flash->render() ?>
</div>
<div class="product-big-title-area">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="product-bit-title text-center">
					<h2>Xác Nhận Đơn Hàng</h2>
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
						<?php if (isset($dataProds)) { ?>
							<form enctype="multipart/form-data" action="/addOrdersNoneLogin" class="checkout" method="post" name="checkout">
								<div id="customer_details" class="col2-set">
									<!-- Test -->
									<div class="col-3">
										<h3 id="order_review_heading">Thông Tin Khách hàng</h3>
										<table class="shop_table">
											<tbody>
												<tr class="cart_item">
													<th class="product-name">
														Họ Và Tên
													</th>
													<td class="product-total">
														<span class="amount"><?= h(trim($dataProds['infoUser']['username'])) ?></span>
													</td>
												</tr>
												<tr class="cart_item">
													<th class="product-name">
														Số Điện Thoại
													</th>
													<td class="product-total">
														<span class="amount"><?= h(trim($dataProds['infoUser']['phonenumber'])) ?></span>
													</td>
												</tr>
												<tr class="cart_item">
													<th class="product-name">
														Địa chỉ Email
													</th>
													<td class="product-total">
														<span class="amount"><?= h(trim($dataProds['infoUser']['email'])) ?></span>
													</td>
												</tr>
												<tr class="cart_item">
													<th class="product-name">
														Địa chỉ
													</th>
													<td class="product-total">
														<span class="amount"><?= h(trim($dataProds['infoUser']['address'])) ?> </span>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<!-- End Test -->

									<div class="col-3">
										<h3 id="order_review_heading">Thông Tin Đơn Hàng</h3>
										<div id="order_review" style="position: relative;">
											<table class="shop_table">
												<thead>
													<tr>
														<th class="product-name">Sản Phẩm</th>
														<th class="product-total">Tổng</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($dataProds['cart'] as  $product) { ?>
														<tr class="cart_item">
															<td class="product-name">
																<?= h(trim($product['name'])) ?> <strong class="product-quantity">× <?= h(trim($product['quantity'])) ?></strong> </td>
															<td class="product-total">
																<span class="amount"><?= '$' . number_format(h(trim($product['totalAmount']))) ?></span>
															</td>
														</tr>
													<?php } ?>
												</tbody>
												<tfoot>
													<tr class="shipping">
														<th>Point Đơn hàng </th>
														<td>
															<?= number_format(h(trim($dataProds['totalAllPoint']))) . ' point' ?>
														</td>
													</tr>
													<tr class="shipping">
														<th>Point Hiện có</th>
														<td>
															<?= '0 point' ?>
														</td>
													</tr>
													<tr class="shipping">
														<th>Point Sau khi mua</th>
														<td>
															<?= number_format(h(trim($dataProds['totalAllPoint']))) . ' point' ?>
														</td>
													</tr>
													<tr class="order-total">
														<th>Tổng Đơn Hàng</th>
														<td><strong><span class="amount"><?= '$' . number_format(h(trim($dataProds['totalAllAmount']))) ?></span></strong> </td>
													</tr>
												</tfoot>
											</table>

											<div class="col-3">
												<div id="order_review" style="position: relative; display: inline-block;">
													<div id="payment">
														<div class="form-row place-order">
															<a href="/billOrder">
																<input type="button" data-value="Place order" value="Back" id="place_order" name="woocommerce_checkout_place_order" class="button alt button_back">
															</a>
														</div>
														<div class="clear"></div>
													</div>
												</div>

												<div id="order_review" style="position: relative; float: right;">
													<div id="payment">
														<div class="form-row place-order">
															<input type="submit" data-value="Place order" value="Xác nhận" id="place_order" name="woocommerce_checkout_place_order" class="button alt">
														</div>
														<div class="clear"></div>
													</div>
												</div>
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