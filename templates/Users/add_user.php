<?php

echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
?>
<?php if ($_SESSION['flag'] == 2) { ?>
	<div class="main-content container-fluid">
		<div class="page-title">
			<div class="row">
				<div class="col-12 col-md-6 order-md-1 order-last">
					<h3>Add User</h3>
				</div>
				<div class="col-12 col-md-6 order-md-2 order-first">
					<nav aria-label="breadcrumb" class='breadcrumb-header'>
					</nav>
				</div>
			</div>
		</div>
		<div class="section">
			<div class="row">
				<div class="col-12">
					<?= $this->Form->create() ?>
					<div class="form-group">
						<label for="email">Username:</label>
						<input type="text" class="form-control" value="<?php if (isset($dataUser['username'])) { ?><?= trim($dataUser['username']) ?><?php } ?>" name="username">
						<?php if (isset($error['username'])) { ?>
							<i style="color: red;">
								<?= implode($error['username']) ?>
							</i>
						<?php } ?>

					</div>
					<div class="form-group">
						<label for="email">Password:</label>
						<input type="password" class="form-control" value="<?php if (isset($dataUser['password'])) { ?><?= trim($dataUser['password']) ?><?php } ?>" name="password">
						<?php if (isset($error['password'])) { ?>
							<i style="color: red;">
								<?= implode($error['password']) ?>
							</i>
						<?php } ?>

					</div>
					<div class="form-group">
						<label for="email">Email:</label>
						<input type="text" class="form-control" value="<?php if (isset($dataUser['email'])) { ?><?= trim($dataUser['email']) ?><?php } ?>" name="email">
						<?php if (isset($error['email'])) { ?>
							<i style="color: red;">
								<?= implode($error['email']) ?>
							</i>
						<?php } ?>
					</div>
					<div class="form-group">
						<label for="email">Phonenumber:</label>
						<input type="text" class="form-control" value="<?php if (isset($dataUser['phonenumber'])) { ?><?= trim($dataUser['phonenumber']) ?><?php } ?>" name="phonenumber" onkeypress='validate(event)' maxlength="10">
						<?php if (isset($error['phonenumber'])) { ?>
							<i style="color: red;">
								<?= implode($error['phonenumber']) ?>
							</i>
						<?php } ?>
					</div>
					<div class="form-group">
						<label for="email">Address:</label>
						<input type="text" class="form-control" value="<?php if (isset($dataUser['address'])) { ?><?= trim($dataUser['address']) ?><?php } ?>" name="address">
						<?php if (isset($error['address'])) { ?>
							<i style="color: red;">
								<?= implode($error['address']) ?>
							</i>
						<?php } ?>
					</div>

					<div class="form-group">
						<label for="pwd">Role:</label>
						<select name="role_id" id="" class="form-control">
							<?php foreach ($dataRole as $role) { ?>
								<option value="<?= $role->id ?>"><?= $role->role_name ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="button_back">
						<a href="/admin/list-user">
							<button type="button" class="btn btn-primary btn-default">Back</button>
						</a>
					</div>
					<div class="button_submit">
						<button type="submit" class="btn btn-primary btn-default">Submit</button>
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