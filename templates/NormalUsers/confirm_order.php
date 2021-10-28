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
					<?php if (isset($dataUser)) { ?>
						<h2>Xác Nhận Đơn Hàng</h2>
					<?php } ?>
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
						<?php if (isset($dataUser)) { ?>
							<form enctype="multipart/form-data" action="/addOrders" class="checkout" method="post" name="checkout">
								<div id="customer_details" class="col2-set">
									<div class="col-3">
										<h3 id="order_review_heading">Thông Tin Khách hàng</h3>
										<table class="shop_table">
											<?php foreach ($dataUser as $User) { ?>
												<tbody>
													<tr class="cart_item">
														<th class="product-name">
															Họ Và Tên
														</th>
														<td class="product-total">
															<span class="amount"><?= h($User['username']) ?></span>
														</td>
													</tr>

													<tr class="cart_item">
														<th class="product-name">
															Số Điện Thoại
														</th>
														<td class="product-total">
															<span class="amount"><?= h($User['phonenumber']) ?> </span>
														</td>
													</tr>

													<tr class="cart_item">
														<th class="product-name">
															Địa chỉ Email
														</th>
														<td class="product-total">
															<span class="amount"><?= h($User['email']) ?> </span>
														</td>
													</tr>

													<tr class="cart_item">
														<th class="product-name">
															Địa chỉ
														</th>
														<td class="product-total">
															<span class="amount"><?= h($User['address']) ?></span>
														</td>
													</tr>
												</tbody>
											<?php } ?>
										</table>
									</div>
									<div class="col-3">
										<h3 id="order_review_heading"> Thông Tin Đơn Hàng</h3>
										<div id="order_review" style="position: relative;">
											<table class="shop_table">
												<thead>
													<tr>
														<th class="product-name">Sản phẩm</th>
														<th class="product-total">Tổng</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($dataProds['cart'] as  $product) { ?>
														<tr class="cart_item">
															<td class="product-name">
																<?= $product['name'] ?> <strong class="product-quantity">× <?= $product['quantity'] ?></strong> </td>
															<td class="product-total">
																<span class="amount"><?= '$' . number_format($product['totalAmount']) ?></span>
															</td>
														</tr>
													<?php } ?>
												</tbody>
												<tfoot>
													<tr class="shipping">
														<th>Point Đơn hàng </th>
														<td>
															<?= number_format($dataProds['totalAllPoint']) . ' point' ?>
														</td>
													</tr>
													<tr class="shipping">
														<th>Point Hiện có</th>
														<td>
															<?= number_format($dataUser[0]['point_user']) . ' point' ?>
														</td>
													</tr>
													<tr class="shipping">
														<th>Point Sau khi mua</th>
														<td>
															<?= number_format($dataProds['totalAllPoint'] + $dataUser[0]['point_user']) . ' point' ?>
														</td>
													</tr>
													<tr class="order-total">
														<th>Tổng Đơn Hàng</th>
														<td><strong><span class="amount"><?= '$' . number_format($dataProds['totalAllAmount']) ?></span></strong> </td>
													</tr>
												</tfoot>
											</table>
											<div class="col-3">
												<div id="order_review" style="position: relative; display: inline-block;">
													<div id="payment">
														<div class="form-row place-order">
															<a href="/carts">
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
				<?php }?>
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<?php
echo $this->element('NormalUsers/footer');
?>