<?php

use Cake\Utility\Text;
echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
$n = $this->request->getAttribute('paging')['Products']['start'];
?>
<div class="main-content container-fluid">
	<div class="page-title">
		<div class="row">
			<div class="col-12 col-md-6 order-md-1 order-last">
				<h3>Danh Sách Tồn Kho</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-md-6 order-md-1 order-last">
				<form action="<?= URL_ADMIN_LIST_INVENTORY ?>" method="get">
					<div class="form-group" style="display: inline-block">
						<label for="key">Search:</label>
						<input type="text" class="form-control" name="key" id="key" value="<?php if(isset($_SESSION['keySearch'])) { echo $_SESSION['keySearch']; }?>">
					</div>
					<button type="submit" class="btn btn-primary btn-default">Search</button>
				</form>
			</div>

			<div class="col-12 col-md-6 order-md-1 order-last" style="align-self: flex-end; text-align: end;">
				<div class="form-group" style="display: inline-block">
					<a href="/admin/add-product">
						<input type="submit" class="btn btn-info" value="Add Sản Phẩm" style="margin-bottom: 5px" />
					</a>
				</div>
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
							<th>STT<th>
							<th>Tên sản phẩm</th>
							<th>Số lượng</th>
							<th>Danh mục</th>
							<th>Chức năng</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($query as $product) { ?>
							<tr>
								<td><?=$n++?>
								<td>
								<td> <a><?= h($product['product_name']) ?></a></td>
								<td><a><?= number_format(h($product['quantity_product'])) ?></a></td>
								<td><a><?= h($product['Categories']['category_name']) ?></a></td>
								<td class="t-al-center">
									<a href="<?= $this->Url->build('/admin/input-product/' . $product->id, ['fullBase' => true]) ?>">
										<input type="submit" class="btn btn-info" value="Nhập kho" style="margin-bottom: 5px" />
									</a>
								</td>
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