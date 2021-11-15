<?php

echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
echo $this->element('serial');
?>
<div class="main-content container-fluid">
	<div class="page-title">
		<div class="row">
			<div class="col-12 col-md-6 order-md-1 order-last">
				<h3>Lịch sử nhập kho</h3>
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
							<th class="t-al-center"><?= $this->Paginator->sort('HistoryInput.username', 'Người nhập'); ?></th>
							<th class="t-al-center"><?= $this->Paginator->sort('Users.email', 'Email'); ?></th>
							<th class="t-al-center"><?= $this->Paginator->sort('HistoryInput.product_name', 'Sản phẩm'); ?></th>
							<th class="t-al-center"><?= $this->Paginator->sort('HistoryInput.quantity_input', 'Số lượng nhập'); ?></th>
							<th class="t-al-center"><?= $this->Paginator->sort('HistoryInput.created_date', 'Ngày nhập'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php  foreach ($historyInput as $history) {?>
							<tr>
								<td><?=$GLOBALS['n']++?></td>
								<td><?=$history['username']?></td>
								<td><?=$history['Users']['email']?></td>
								<td><?=$history['product_name']?></td>
								<td><?=$history['quantity_input']?></td>
								<td><?=$history['created_date']?></td>
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
