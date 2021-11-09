<?php

echo $this->element('NormalUsers/header');
?>
<div class="product-big-title-area">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="product-bit-title text-center">
					<h2>Lịch sử Đơn hàng</h2>
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
										<h3 id="order_review_heading">Thông Tin Đơn Hàng</h3>
										<table class="shop_table">
											<thead>
												<tr>
													<th>Ngày đặt hàng</th>
													<th>Trạng Thái</th>
													<th>Chi tiết đơn</th>
												</tr>
											</thead>
											<tbody>
											<?php foreach ($dataOrders as $order) {?>
												<tr class="cart_item">
													<td class="product-total">
														<span class="amount"><?=$order['created_date']?></span>
													</td>
													<td class="product-total">
														<span class="amount">
															<?php
																if($order['status'] == 0){
																	echo 'Chờ Duyệt';
																}elseif ($order['status'] == 1) {
																	echo 'Đã Duyệt';
																}elseif ($order['status'] == 2) {
																	echo 'Từ chối';
																}else{
																	echo 'Khách hàng không nhận';
																}
															 ?>
														</span>
													</td>
													<td class="product-total">
														<a href="<?= $this->Url->build('/details-order/' .$order['id'], ['fullBase' => true])?>">
															<input type="submit" data-value="Place order" value="Chi Tiết" id="place_order" class="button alt">
														</a>
													</td>
												</tr>
											<?php }?>
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
				<?php if (isset($dataNameForUser)) { ?>
					<div class="row">
						<div class="col-md-12">
							<div class="product-pagination text-center pagination-button">
								<?= $this->element('paginator') ?>
							</div>
						</div>
					</div>
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