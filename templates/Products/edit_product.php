<?php

echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
?>
<?php if ($_SESSION['flag'] == 2) { ?>
	<div class="main-content container-fluid">
		<div class="page-title">
			<div class="row">
				<div class="col-12 col-md-6 order-md-1 order-last">
					<h3>Chỉnh sửa sản phẩm</h3>
				</div>
				<div class="col-12 col-md-6 order-md-2 order-first">
					<nav aria-label="breadcrumb" class='breadcrumb-header'>
					</nav>
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
					<?= $this->Form->create(null, ['type' => 'file']) ?>
					<div class="form-group">
						<label for="email">Tên sản phẩm:</label>
						<input type="text" class="form-control" value="<?= h(trim($dataProduct['product_name'])) ?>" name="product_name">
						<?php if (isset($error['product_name'])) { ?>
							<i style="color: red;">
								<?= implode($error['product_name']) ?>
							</i>
						<?php } ?>

					</div>
					<div class="form-group">
						<label for="email">Hình ảnh:</label>
						<input type="file" class="form-control input-file" id='uploadfile' name="uploadfile" value="<?php if (isset($dataProduct['images'])) { ?><?= trim($dataProduct['images'][0]['image']) ?><?php } ?>">
					</div>
					<div class="form-group">
						<label style="display: block" for="email">Mô tả:</label>
						<textarea rows="14" cols="165" class="editor1" id="editor1" type="text" class="form-control " name="description"><?= trim($dataProduct['description']) ?>
						</textarea>
						<?php if (isset($error['description'])) { ?>
							<i style="color: red;">
								<?= implode($error['description']) ?>
							</i>
						<?php } ?>
						<script>
							config = {};
							config.entities_latin = false;
							CKEDITOR.replace('editor1', config);
						</script>
					</div>
					<div class="form-group">
						<label for="email">Giá sản phẩm:</label>
						<input type="text" class="form-control" value="<?= h(trim($dataProduct['amount_product'])) ?>" name="amount_product">
						<?php if (isset($error['amount_product'])) { ?>
							<i style="color: red;">
								<?= implode($error['amount_product']) ?>
							</i>
						<?php } ?>
					</div>
					<div class="form-group">
						<label for="email">Point sản phẩm:</label>
						<input type="text" class="form-control" value="<?= h(trim($dataProduct['point_product'])) ?>" name="point_product">
						<?php if (isset($error['point_product'])) { ?>
							<i style="color: red;">
								<?= implode($error['point_product']) ?>
							</i>
						<?php } ?>
					</div>

					<div class="form-group">
						<label for="pwd">Danh mục:</label>
						<select name="category_id" id="" class="form-control">
							<?php foreach ($dataCategory as $category) { ?>
								<option value="<?= $category->id ?>" <?php if ($category->id == $dataProduct['category_id']) { ?> selected <?php } ?>><?= $category->category_name ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="button_back">
						<a href="<?= $dataProduct["referer"]; ?>">
							<button type="button" class="btn btn-primary btn-default">Back</button>
						</a>
					</div>

					<div class="button_submit">
						<button type="submit" value="<?= $dataProduct["referer"] ?>" name="referer" class="btn btn-primary btn-default">Submit</button>
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