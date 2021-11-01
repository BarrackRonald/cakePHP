<?php

echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
echo $this->element('serial');
?>
<div class="main-content container-fluid">
	<div class="page-title">
		<div class="row">
			<div class="col-12 col-md-6 order-md-1 order-last">
				<h3>Quản lý Danh mục sản phẩm</h3>
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
							<th>STT</th>
							<th class="t-al-center">Tên Danh mục</th>
							<?php if ($_SESSION['flag'] == 2) { ?>
								<th class="center">Chức năng</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($categories as $category) { ?>
							<tr>
								<td><?=$GLOBALS['n']++?></td>
								<td class="t-al-center"> <a><?= h($category['category_name']) ?></a></td>
								<?php if ($_SESSION['flag'] == 2) { ?>
									<td class="t-al-center">
										<a href="<?= $this->Url->build('/admin/edit-category/' . $category->id, ['fullBase' => true]) ?>">
											<input type="submit" class="btn btn-info" value="Sửa" style="margin-bottom: 5px" />
										</a>
										<form id="formDelete_<?=$category->id?>" action="<?= $this->Url->build('/admin/delete-category/' . $category->id, ['fullBase' => false]) ?>" method="post">
											<input type="hidden" value="<?= $category->id ?>" name="id" />
											<input type="hidden" value="<?= $category->del_flag ?>" name="del_flag" />
											<input type="button" id="<?= $category->id ?>" class="btn btn-danger" name="delete" value="Xóa" style="margin-bottom: 5px" />
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
			text: 'Danh mục này sẽ bị xóa và không thể hoàn tác được?',
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