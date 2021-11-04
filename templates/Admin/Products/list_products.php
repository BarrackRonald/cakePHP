<?php

use Cake\Utility\Text;
echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
echo $this->element('serial');
?>
<div class="main-content container-fluid">
	<div class="page-title">
		<div class="row">
			<div class="col-12 col-md-6 order-md-1 order-last">
				<h3>Quản Lý Sản Phẩm</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-md-6 order-md-1 order-last">
				<form action="<?= URL_ADMIN_LIST_PRODUCTS ?>" method="get">
					<div class="form-group" style="display: inline-block">
						<label for="key">Search:</label>
						<input type="text" class="form-control" name="key" id="key" value="<?php if(isset($_SESSION['keySearch'])) { echo $_SESSION['keySearch']; }?>">
					</div>
					<button type="submit" class="btn btn-primary btn-default">Search</button>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-md-6 order-md-1 order-last">
				<?= $this->Flash->render() ?>
			</div>
		</div>
	</div>
	<section class="section">
		<div class="card">
			<div class="card-body">
				<table id="tbl-users-list" class='table table-striped' id="table1">
					<thead>
						<tr>
							<th>STT
							<th>
							<th>Tên sản phẩm</th>
							<th>Hình ảnh</th>
							<th>Mô tả</th>
							<th>Giá sản phẩm</th>
							<th>Point sản phẩm</th>
							<th>Danh mục</th>
							<?php if ($_SESSION['flag'] == 2) { ?>
								<th>Trạng thái</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($query as $product) { ?>
							<tr>
								<td><?=$GLOBALS['n']++?>
								<td>
								<td> <a><?= h($product['product_name']) ?></a></td>
								<td>
									<a>
										<?php if (isset($product->images[0])) { ?>
											<img width="60%" src="<?= $product->images[0]->image; ?>" alt="">
										<?php } else {
											echo 'Chưa cập nhật hình ảnh';
										} ?>
									</a>
								</td>
								<td><a><?= h(Text::excerpt($product['description'], 'method', 50, '...')); ?></a></td>
								<td><a><?= number_format(h($product['amount_product'])) ?></a></td>
								<td><a><?= number_format(h($product['point_product'])) ?></a></td>
								<td><a><?= h($product['Categories']['category_name']) ?></a></td>
								<?php if ($_SESSION['flag'] == 2) { ?>
									<td style="text-align: center;">
										<a href="<?= $this->Url->build('/admin/edit-product/' . $product->id, ['fullBase' => true]) ?>">
											<input type="submit" class="btn btn-info" value="Sửa" style="margin-bottom: 5px" />
										</a>
										<form id="formDelete_<?=$product->id?>" action="<?= $this->Url->build('/admin/delete-product/' . $product->id, ['fullBase' => false]) ?>" method="post">
											<input type="hidden" value="<?= $product->id ?>" name="id" />
											<input type="hidden" value="<?= $product->del_flag ?>" name="del_flag" />
											<input type="button" id="<?=$product->id?>" class="btn btn-danger" name="delete" value="Xóa" style="margin-bottom: 5px" />
										</form>
									</td>
								<?php } ?>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<div class="pagination-button">
					<?= $this->element('paginator') ?>
				</div>
			</div>
		</div>
	</section>
</div>

<?php
echo $this->element('Admin/footer');
?>
<script>
	// Delete
	$("input[name = 'delete']").click(function(e) {
		swal({
			title: 'Bạn có muốn xóa?',
			text: 'Sản phẩm này sẽ bị xóa và không thể hoàn tác được?',
			icon: 'warning',
			buttons: ["Hủy", "Xóa"],
			dangerMode: true,
		}).then(function(value) {
			if (value) {
				let formName = '#formDelete_' + e.target.id;
				$(formName).submit();
			}
		});
	});
</script>