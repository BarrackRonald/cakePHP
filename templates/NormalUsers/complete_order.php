<?php

echo $this->element('NormalUsers/header');
?>
<div class="row" style="margin-left: 5px">
	<?= $this->Flash->render() ?>
</div>
<div class=" successs">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="product-bit-title2 text-center custom_boder">
					<img src="https://img.icons8.com/carbon-copy/100/000000/checked-checkbox.png" alt="">
					<h2>ĐẶT HÀNG THÀNH CÔNG</h2>
					<h4 class="h4_success">Cảm ơn bạn đã mua sắm tại VerTu. Thông tin Đơn hàng đã được gửi về mail của bạn!!!</h4>
					<div id="order_review" style="position: relative; display: inline-block;">
						<div id="payment">
							<div class="form-row place-order">
								<a href="/">
									<input type="button" data-value="Place order" value="Tiếp tục mua sắm" id="place_order" class="button alt button_back2">
								</a>
							</div>
							<div class="clear"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> <!-- End Page title area -->

<?php
echo $this->element('NormalUsers/footer');
?>