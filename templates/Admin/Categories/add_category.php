<?php

echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
?>
<?php if ($_SESSION['flag'] == 2) { ?>
	<div class="main-content container-fluid">
		<div class="page-title">
			<div class="row">
				<div class="col-12 col-md-6 order-md-1 order-last">
					<h3>Thêm Danh mục sản phẩm</h3>
				</div>
			</div>
		</div>
		<div class="section">
			<div class="row">
				<div class="col-12">
					<?= $this->Form->create() ?>
					<div class="form-group">
						<label for="email">Tên Danh mục sản phẩm:</label>
						<input type="text" class="form-control" value="<?php if (isset($dataCategory['category_name'])) { ?><?= h(trim($dataCategory['category_name'])) ?><?php } ?>" name="category_name">
						<?php if (isset($error)) { ?>
							<i style="color: red;">
								<?= implode($error['category_name']) ?>
							</i>
						<?php } ?>
					</div>
					<div class="button_back">
						<a href="<?= URL_ADMIN_LIST_CATEGORIES ?>">
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
	function disable(){
		document.getElementById("submit").style.display = "none";
		document.getElementById("none").style.display = "block";
	}
</script>