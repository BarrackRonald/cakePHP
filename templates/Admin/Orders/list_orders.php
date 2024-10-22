<?php

echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
echo $this->element('serial');
?>
<div class="main-content container-fluid">
	<div class="page-title">
		<div class="row">
			<div class="col-12 col-md-6 order-md-1 order-last">
				<h3>Quản Lý Đơn Hàng</h3>
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-md-6 order-md-1 order-last">
				<form action="<?= URL_ADMIN_LIST_ORDERS ?>" method="get">
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
							<th>STT</th>
							<th><?= $this->Paginator->sort('Orders.order_name', 'Họ và tên'); ?></th>
							<th><?= $this->Paginator->sort('Orders.email', 'Email'); ?></th>
							<th><?= $this->Paginator->sort('Orders.phonenumber', 'Số điện thoại'); ?></th>
							<th><?= $this->Paginator->sort('Orders.address', 'Địa chỉ'); ?></th>
							<th><?= $this->Paginator->sort('Orders.total_point', 'Tổng Point'); ?></th>
							<th><?= $this->Paginator->sort('Orders.total_quantity', 'Tổng Số Lượng'); ?></th>
							<th><?= $this->Paginator->sort('Orders.total_amount', 'Tổng giá'); ?></th>
							<th><?= $this->Paginator->sort('Orders.status', 'Trạng Thái'); ?></th>
							<th>Chức năng</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($query1 as $order) { ?>
							<tr class="list">
								<td><?=$GLOBALS['n']++?></td>
								<td><a><?= $order['order_name'] ?></a></td>
								<td><a><?= $order['email'] ?></a></td>
								<td><a><?= $order['phonenumber'] ?></a></td>
								<td><a><?= $order['address'] ?></a></td>
								<td><a><?= $order['total_point'] ?></a></td>
								<td><a><?= $order['total_quantity'] ?></a></td>
								<td><a><?= '$'.number_format($order['total_amount']) ?></a></td>
								<td><a>
										<?php
										if ($order['status'] == 0) {
											echo 'Chờ duyệt';
										} else if ($order['status'] == 1) {
											echo 'Đã duyệt';
										} else if ($order['status'] == 2) {
											echo 'Từ chối';
										}else{
											echo 'Khách hàng không nhận';
										} ?>
									</a>
								</td>
								<td style="text-align: center;">
									<a href="<?= $this->Url->build('/admin/details-order/' . $order->id, ['fullBase' => true]) ?>">
										<input type="submit" class="btn btn-info" value="   Chi Tiết Đơn " style="margin-bottom: 5px" />
									</a>
									<?php if ($_SESSION['flag'] == 3 || $_SESSION['flag'] == 2) { ?>
										<a href="<?= $this->Url->build('/admin/confirm-order/' . $order->id, ['fullBase' => true]) ?>">
											<input type="submit" class="btn btn-info" value="Cập Nhật Đơn" style="margin-bottom: 5px" />
										</a>
									<?php } ?>
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