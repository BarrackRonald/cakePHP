<?php
use Cake\Utility\Text;
echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
?>
<?php if($_SESSION['flag'] == 2){ ?>
            <div class="main-content container-fluid">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Chỉnh sửa thông tin User</h3>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class='breadcrumb-header'>
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.html">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Datatable</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="section">
                    <div class="row">
                        <div class="col-12">
                        <?= $this->Form->create($dataUser[0]) ?>
                            <div class="form-group">
                            <label for="email">Username:</label>
                                <input type="text" class="form-control" value="<?= $dataUser[0]->username ?> " name="username" >

                            </div>
                            <div class="form-group">
                                <label for="email">Password:</label>
                                <input type="password" class="form-control" value="<?= $dataUser[0]->password ?>" name="password" >
                            </div>
                            <div class="form-group">
                                <label for="email">Phonenumber:</label>
                                    <input type="text" class="form-control" value="<?= $dataUser[0]->phonenumber ?>" name="phonenumber" >
                            </div>
                            <div class="form-group">
                                <label for="email">Address:</label>
                                    <input type="text" class="form-control" value="<?= $dataUser[0]->address ?>" name="address" >
                            </div>
                            <input type="hidden" class="form-control" value="<?= $_SERVER['HTTP_REFERER'] ?>" name="referer" >

                            <div class="form-group">
                                <label for="pwd">Role:</label>
                                <select name="role_id" id="" class="form-control" >
                                    <?php foreach ($dataRole as $role) { ?>
                                        <option value="<?= $role->id?>" <?php if($role->id == $dataUser[0]->role_id){?> selected <?php } ?> ><?= $role->role_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <?php if($_SESSION['flag'] == 2){ ?>
                                <button type="submit" class="btn btn-primary btn-default">Submit</button>
                            <?php }else{?>
                                <button disabled class="btn btn-primary btn-default">Không đủ quyền</button>
                            <?php }?>
                            <?= $this->Form->end() ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else {?>
            <h3>Người dùng không đủ quyền truy cập</h3>
        <?php }?>
<?php
echo $this->element('Admin/footer');
?>