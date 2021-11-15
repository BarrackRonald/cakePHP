<?php

echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
?>
<?php if ($_SESSION['flag'] == 2 || $_SESSION['flag'] == 3) { ?>
	<div class="main-content container-fluid">
		<div class="page-title">
			<div class="row">
				<div class="col-12 col-md-6 order-md-1 order-last">
					<h3>Nhập Kho</h3>
				</div>
			</div>
			<div class="row">
				<div class="col-12 col-md-6 order-md-1 order-last">
					<?= $this->Flash->render() ?>
				</div>
			</div>
		</div>
		<div class="section">
			<div class="row">
				<div class="col-12">
					<?= $this->Form->create() ?>
					<div class="button_add">
						<a href="/admin/add-product">
							<input type="button" class="btn btn-info" value="Thêm Sản phẩm" style="margin-bottom: 5px" />
						</a>
					</div>
					<div class="form-group">
						<label for="pwd">Sản phẩm:</label>
						<div class="search_select_box">
							<select class="selectpicker w-100" data-live-search="true" name="product_id">
								<?php foreach ($products as $product) { ?>
									<option value="<?= $product->id ?>" <?php if ($product->id == $product_id) { ?> selected <?php } ?>><?= $product->product_name ?></option>
								<?php } ?>
							</select>
						</div>
					</div>

					<div class="form-group">
						<label for="email">Số lượng:</label>
						<input type="text" class="form-control" value="" name="quantity_product" onkeypress='validate(event)'>
						<?php if (isset($error['quantity_product'])) { ?>
							<i style="color: red;">
								<?= implode($error['quantity_product']) ?>
							</i>
						<?php } ?>
					</div>

					<?php if ($product_id != null) { ?>
						<div class="button_back">
							<a href="<?= $dataProduct['referer'] ?>">
								<button type="button" class="btn btn-primary btn-default">Back</button>
							</a>
						</div>
					<?php } ?>

					<div class="button_submit">
						<button type="submit" id="submit" value="<?= $dataProduct['referer'] ?>" name="referer" onclick="disable()" class="btn btn-primary btn-default">Submit</button>
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

	if (window.history.replaceState) {
		window.history.replaceState(null, null, window.location.href);
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

<?php if (isset($_SESSION['success']) && $product_id != null) { ?>
	<script>
		swal({
				title: "Thành công!",
				text: "Bạn có muốn tiếp tục nhập đơn!",
				icon: "success",
				buttons: ["Không", "Tiếp tục"],
			})
			.then((willDelete) => {
				if (!willDelete) {
					window.location.assign("<?= $dataProduct['referer'] ?>");
				}
			});
	</script>
<?php } ?>

<?php if (isset($_SESSION['success']) && $product_id == null) { ?>
	<script>
		swal({
				title: "Thành công!",
				text: "Bạn có muốn tiếp tục nhập đơn!",
				icon: "success",
				buttons: ["Không", "Tiếp tục"],
			})
			.then((willDelete) => {
				if (!willDelete) {
					window.location.assign("/admin/list-inventory");
				}
			});
	</script>
<?php } ?>