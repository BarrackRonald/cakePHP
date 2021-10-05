<?php
use Cake\Utility\Text;
echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
?>

            <div class="main-content container-fluid">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Xem thông tin Danh mục sản phẩm</h3>

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
                                <input type="text" class="form-control" value="<?= $dataUser[0]->username ?> " name="username" readonly >

                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="text" class="form-control" value="<?= $dataUser[0]->email ?>" name="email" readonly>
                            </div>
                            <div class="form-group">
                                <label for="email">Phonenumber:</label>
                                    <input type="text" class="form-control" value="<?= $dataUser[0]->phonenumber ?>" name="phonenumber" readonly>
                            </div>
                            <div class="form-group">
                                <label for="email">Address:</label>
                                    <input type="text" class="form-control" value="<?= $dataUser[0]->address ?>" name="address" readonly >
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Point:</label>
                                    <input type="text" class="form-control" value="<?= $dataUser[0]->point_user ?>" name="point" readonly>
                            </div>

                            <div class="form-group">
                                <label for="pwd">Role:</label>
                                <select name="role_id" id="" class="form-control" >
                                    <?php foreach ($dataRole as $role) { ?>
                                        <option value="<?= $role->id?>" <?php if($role->id == $dataUser[0]->role_id){?> selected <?php } ?> ><?= $role->role_name ?></option>
                                    <?php } ?>
                                    
                                    
                                </select>
                            </div>
                            <?= $this->Form->end() ?>
                        </div>
                    </div>
                </div>
            </div>
<?php
echo $this->element('Admin/footer');
?>