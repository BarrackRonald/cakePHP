<?php

echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
?>
<?php if ($_SESSION['flag'] == 2) { ?>
	<div class="main-content container-fluid">
		<div class="page-title">
			<div class="row">
				<div class="col-12 col-md-6 order-md-1 order-last">
					<h3>Nhập Kho</h3>
				</div>
			</div>
		</div>
		<div class="section">
			<div class="row">
				<div class="col-12">
					<?= $this->Form->create() ?>

					<div class="form-group">
						<label for="pwd">Sản phẩm:</label>
						<div class="search_select_box">
							<select class="selectpicker w-100" data-live-search="true">
								<?php foreach ($products as $product) {?>
									<option value="<?=$product->id?>"><?=$product->product_name?></option>
								<?php } ?>
							</select>

						</div>
					</div>

					<div class="form-group">
						<label for="email">Số lượng:</label>
						<input type="text" class="form-control" value="" name="amount_product">
						<?php if (isset($error['amount_product'])) { ?>
							<i style="color: red;">
								<?= implode($error['amount_product']) ?>
							</i>
						<?php } ?>
					</div>

					<div class="button_back">
						<a href="<?= URL_ADMIN_LIST_PRODUCTS ?>">
							<button type="button" class="btn btn-primary btn-default">Back</button>
						</a>
					</div>

					<div class="button_submit">
						<button type="submit" id="submit" onclick="disable()" class="btn btn-primary btn-default">Submit</button>
						<button type="submit" id="none" style="display: none" disabled class="btn btn-primary btn-default">Submit</button>
					</div>
					<?= $this->Form->end() ?>
				</div>
			</div>
		</div>
	</div>
<?php } else { ?>
	<h3>Người dùng không đủ quyền để truy cập</h3>
<?php } ?>
<?php
echo $this->element('Admin/footer');
?>
<script>
	function disable() {
		document.getElementById("submit").style.display = "none";
		document.getElementById("none").style.display = "block";
	}
</script>