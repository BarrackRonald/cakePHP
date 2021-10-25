<?php

echo $this->element('NormalUsers/header');
?>
<div class="product-big-title-area">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="product-bit-title text-center">
					<h2>Giỏ Hàng</h2>
				</div>
			</div>
		</div>
	</div>
</div> <!-- End Page title area -->
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
						<form method="post" action="/billOrder">
							<table cellspacing="0" class="shop_table cart">
								<thead>
									<tr>
										<th class="product-remove">Xóa</th>
										<th class="product-thumbnail">Hình Ảnh</th>
										<th class="product-name">Sản Phẩm</th>
										<th class="product-price">Giá</th>
										<th class="product-point">Point</th>
										<th class="product-quantity">Số Lượng</th>
										<th class="product-subtotal">Tổng Giá</th>
									</tr>
								</thead>
								<tbody>

									<?php if (isset($dataProds)) foreach ($dataProds['cart'] as $key => $product) {  ?>
										<tr class="cart_item" id="cart_item_<?= $key ?>">
											<td class="product-remove">
												<a title="Remove this item" class="remove" href="javascript:;" onclick="delAllCart(<?= $key ?>)">x</a>
											</td>

											<td class="product-thumbnail">
												<a><img width="145" height="145" alt="poster_1_up" class="shop_thumbnail" src="<?= $product['image'] ?>"></a>
											</td>

											<td class="product-name">
												<a><?= h($product['name']) ?></a>
											</td>

											<td class="product-price">
												<span class="amount"><?= '$' . number_format($product['amount']) ?></span>
											</td>

											<td class="product-point">
												<span class="amount" id="point_<?= $key ?>"><?= $product['totalPoint'] ?></span>
											</td>

											<td class="product-quantity" style="width:auto">
												<div class="quantity buttons_added">
													<a href="javascript:;" onclick="reduceQuantity(<?= $key ?>)">
														<input type="button" class="minus" value="-">

													</a>
													<input class='valueItem' id="product_<?= $key ?>" type="number" size="4" class="input-text qty text" title="Qty" value="<?= isset($this->request->getSession()->read('cartData')['cart']["$key"]) ? $this->request->getSession()->read('cartData')['cart']["$key"]['quantity'] : "0"; ?>" min="0" step="1" readonly>
													<a href="javascript:;" onclick="addCart(<?= $key ?>)">
														<input type="button" class="plus" value="+">
													</a>
												</div>
											</td>

											<td class="product-subtotal">

												<span class="amount" id="amount_<?= $key ?>">
													<?= '$' . number_format($product['totalAmount']) ?>
												</span>
											</td>
										</tr>
									<?php } ?>
									<?php if (isset($dataProds)) { ?>
										<tr>
											<td class="actions" colspan="6">
												<div class="coupon">
													<label for="coupon_code">Tổng Giá:</label>
												</div>
											</td>
											<td class="actions" colspan="1">
												<label for="coupon_code" id="totalAllAmount"> $
													<?php
													echo isset($this->request->getSession()->read('cartData')['totalAllAmount']) ? number_format($this->request->getSession()->read('cartData')['totalAllAmount']) : "0";
													?>
												</label>
											</td>
										</tr>
									<?php } else { ?>
										<tr>
											<td class="actions" colspan="7">
												<div class="coupon">
													<label for="coupon_code">
													</label>
												</div>
											</td>
										</tr>
									<?php } ?>

									<tr>
										<td class="actions" colspan="7">
											<input type="submit" value="Đặt Hàng" name="proceed" class="checkout-button button alt wc-forward">
										</td>
									</tr>

								</tbody>
							</table>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

<script>
	function addCart(product_id) {
		$.ajax({
			url: '/addCart',
			type: 'post',
			data: {
				productId: (product_id)
			},
			success: function(data) {
				//totalquantity
				var datatotal = JSON.parse(data);
				$('.product-count').html(datatotal.totalquantity);
				$('#totalAllAmount').html('$' + Intl.NumberFormat().format(datatotal.totalAllAmount));

				//total Amount Product_id
				var totalAmount = JSON.parse(data).cart[product_id]["totalAmount"];
				console.log(totalAmount);
				$('#amount_' + product_id).html('$' + Intl.NumberFormat().format(totalAmount));

				//total Point
				var totalPoint = JSON.parse(data).cart[product_id]["totalPoint"];
				$('#point_' + product_id).html(totalPoint);

				//quantity
				var data = JSON.parse(data).cart[product_id]["quantity"];
				$("#product_" + product_id).val(data);

			},
			error: function(data, textStatus, jqXHR) {

				console.log("error");
			}
		});
	}

	function reduceQuantity(product_id) {
		$.ajax({
			url: '/reduceQuantity',
			type: 'post',
			data: {
				productId: (product_id)
			},
			success: function(data) {
				//totalquantity
				var datatotal = JSON.parse(data);
				$('.product-count').html(datatotal.totalquantity);
				$('#totalAllAmount').html('$' + Intl.NumberFormat().format(datatotal.totalAllAmount));

				//quantity and total mount
				var dataProduct = JSON.parse(data).cart[product_id]
				if (typeof dataProduct == 'undefined') {

					$("#cart_item_" + product_id).remove();
				} else {
					$('#point_' + product_id).html(dataProduct["totalPoint"]);
					$('#amount_' + product_id).html('$' + Intl.NumberFormat().format(dataProduct["totalAmount"]));
					$("#product_" + product_id).val(dataProduct["quantity"]);
				}
			},
			error: function(data, textStatus, jqXHR) {
				console.log("error");
			}
		});
	}

	function delAllCart(product_id) {
		$.ajax({
			url: '/delAllCart',
			type: 'post',
			data: {
				productId: (product_id)
			},
			success: function(data) {
				console.log(JSON.parse(data));
				//totalquantity
				var datatotal = JSON.parse(data);
				$('.product-count').html(datatotal.totalquantity);
				$('#totalAllAmount').html('$' + Intl.NumberFormat().format(datatotal.totalAllAmount));
				//dell item
				$("#cart_item_" + product_id).remove();
			},
			error: function(data, textStatus, jqXHR) {
				console.log("error");
			}
		});
	}
</script>

<?php
echo $this->element('NormalUsers/footer');
?>