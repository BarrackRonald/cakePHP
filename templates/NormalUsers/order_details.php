<?php

echo $this->element('NormalUsers/header');
?>
<div class="product-big-title-area">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="product-bit-title text-center">
					<h2>Chi tiết Đơn hàng</h2>
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
						<?php if (isset($dataNameForUser)) { ?>
								<div id="customer_details" class="col2-set">
									<div class="col-3">
										<h3 id="order_review_heading">Thông Tin Chi Tiết</h3>
										<table class="shop_table">
											<thead>
												<tr>
													<th>Tên sản phẩm</th>
													<th>Số lượng</th>
													<th>Point nhận được</th>
													<th>Giá sản phẩm</th>
												</tr>
											</thead>
											<tbody>
											<?php foreach ($dataOrderDetails as $orderDetail) {?>
												
												<tr class="cart_item">
													<td class="product-total">
														<span class="amount"><?= $orderDetail['Products']['product_name'] ?></span>
													</td>
													<td class="product-total">
														<span class="amount"><?= $orderDetail['quantity_orderDetails'] ?></span>
													</td>
													<td class="product-total">
														<span class="amount"><?= $orderDetail['point_orderDetail'].' point' ?></span>
													</td>
													<td class="product-total">
														<span class="amount"><?= '$'.number_format($orderDetail['amount_orderDetails']) ?></span>
													</td>
												</tr>
											<?php }?>
											<tr>
												<th class="actions" >
													<div class="coupon">
														<label for="coupon_code">Tổng:</label>
													</div>
												</th>
												<td class="product-total">
													<span class="amount"><?= $orderDetail['quantity_orderDetails'] ?></span>
												</td>
												<td class="product-total">
													<span class="amount"><?= $orderDetail['point_orderDetail'].' point' ?></span>
												</td>
												<td class="product-total">
													<span class="amount"><?= '$'.number_format($orderDetail['amount_orderDetails']) ?></span>
												</td>
											</tr>
											</tbody>
										</table>
									</div>
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
				<div class="col-3">
					<div id="order_review" class="button-back">
						<div id="payment">
							<div class="form-row place-order">
								<a href="<?= $referer?>">
									<input type="button" data-value="Place order" value="Back" id="place_order" name="woocommerce_checkout_place_order" class="button alt button_back">
								</a>
							</div>
							<div class="clear"></div>
						</div>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php
echo $this->element('NormalUsers/footer');
?>