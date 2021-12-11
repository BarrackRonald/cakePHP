<?php
use Cake\Utility\Text;
echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
echo $this->element('serial');

$n = $this->request->getAttribute('paging')[$this->request->getParam('controller')]['start'];

?>
<?php if ($_SESSION['flag'] == 2) { ?>
	<div class="main-content container-fluid">
		<div class="page-title">
			<div class="row">
				<div class="col-12 col-md-6 order-md-1 order-last">
					<h3>Quản lý Users</h3>
				</div>
			</div>
			<div class="row">
				<div class="col-12 col-md-6 order-md-1 order-last">
					<form action="<?= URL_ADMIN_LIST_USER ?>" method="get">
						<div class="form-group" style="display: inline-block">
							<label for="key">Search:</label>
							<input type="text" class="form-control" name="key" id="key" value="<?php if(isset($_SESSION['keySearch'])) { echo $_SESSION['keySearch']; }?>">
						</div>
						<button type="submit" class="btn btn-primary btn-default">Search</button>
					</form>
				</div>

				<div class="col-12 col-md-6 order-md-1 order-last">
					<form action="<?=URL_ADMIN_LIST_USER?>" method="get" style="float: right;">
						<?php if(isset($_SESSION['hasfilter'])){?>
							<a href="/admin/list-user" title="Hủy Lọc">
								<i class="far fa-times-circle"></i>
							</a>
						<?php }?>
						<div class="form-group" style="display: inline-block">
							<label for="filter">Lọc Kết Quả:</label>:
							<select class="form-control" name="filter">
								<option value="lock" <?= isset($filters) && ($filters== 'lock') ? 'selected' : '' ?>>TK Bị Khóa</option>
								<option value="unlock" <?= isset($filters) && ($filters== 'unlock') ? 'selected' : '' ?>>TK Không Khóa</option>
							</select>
						</div>
						<button type="submit" class="btn btn-primary btn-default"><i class="fa fa-filter"></i>Lọc</button>
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
								<th><?= $this->Paginator->sort('Users.username', 'Họ và tên'); ?></th>
								<th><?= $this->Paginator->sort(	'Users.email', 'Email'); ?></th>
								<th><?= $this->Paginator->sort('Users.phonenumber', 'Số Điện thoại'); ?></th>
								<th><?= $this->Paginator->sort('Users.address', 'Địa chỉ'); ?></th>
								<th><?= $this->Paginator->sort('Users.point_user', 'Point'); ?></th>
								<th><?= $this->Paginator->sort('Roles.role_name', 'Quyền'); ?></th>
								<th>Trạng thái</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($query as $user) { ?>
								<tr class="list">
									<td><?=$GLOBALS['n']++?></td>
									<td><a><?= $user['username'] ?></a></td>
									<td><a title="<?= $user['email']?>"><?=Text::truncate($user['email'],22,
										[
										'ellipsis' => '...',
											'exact' => false
										]
									);?></a></td>
									<td><a><?= $user['phonenumber'] ?></a></td>
									<td><a><?= $user['address'] ?></a></td>
									<td><a><?= $user['point_user'] ?></a></td>
									<td><a><?= $user['Roles']['role_name'] ?></a></td>
									<td style="text-align: center;">

										<?php if ($user['del_flag'] == 0) { ?>
											<a href="<?= $this->Url->build('/admin/edit-user/' . $user->id, ['fullBase' => true]) ?>">
												<input type="submit" class="btn btn-info" value="    Sửa    " style="margin-bottom: 5px" />
											</a>
										<?php }?>

										<?php if ($user->del_flag == 0) { ?>
											<form id="formLock_<?= $user->id ?>" action="<?= $this->Url->build('/admin/delete-user/' . $user->id, ['fullBase' => false]) ?>" method="post">
												<input type="hidden" value="<?= $user->id ?>" name="id" />
												<input type="hidden" value="<?= $user->del_flag ?>" name="del_flag" />
												<input type="button" id="<?= $user->id ?>" name="lock" class="btn btn-danger" value="Khóa TK" style="margin-bottom: 5px" />
											</form>
										<?php } else { ?>
											<form id="formUnlock_<?= $user->id ?>" action="<?= $this->Url->build('/admin/opent-user/' . $user->id, ['fullBase' => false]) ?>" method="post">
												<input type="hidden" value="<?= $user->id ?>" name="id" />
												<input type="hidden" value="<?= $user->del_flag ?>" name="del_flag" />
												<input type="button" id="<?= $user->id ?>" name="unlock" class="btn btn-success" value="  Mở TK " style="margin-bottom: 5px" />
											</form>
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
<?php } else { ?>
	<h3>Người dùng không đủ quyền để truy cập</h3>
<?php } ?>

<?php
echo $this->element('Admin/footer');
?>
<script>
	// Khóa
	$("input[name = 'lock']").click(function(e) {
		swal({
			title: 'Bạn có muốn khóa?',
			text: 'Người dùng này sẽ không thể mua hàng sau khi khóa?',
			icon: 'warning',
			buttons: ["Hủy", "Khóa"],
			dangerMode: true,
		}).then(function(value) {
			if (value) {
				let formName = '#formLock_' + e.target.id;
				$(formName).submit();
			}
		});
	});

	// Mở khóa
	$("input[name = 'unlock']").click(function(e) {
		swal({
			title: 'Bạn có muốn mở khóa?',
			text: 'Người dùng này sẽ có thể mua hàng sau khi mở?',
			icon: 'warning',
			buttons: ["Hủy", "Mở"],
			dangerMode: true,
		}).then(function(value) {
			if (value) {
				let formName = '#formUnlock_' + e.target.id;
				$(formName).submit();
			}
		});
	});
</script>