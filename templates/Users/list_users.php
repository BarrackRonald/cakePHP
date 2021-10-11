<?php
use Cake\Utility\Text;
echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
$n = 1;
?>
            <?php if($_SESSION['flag'] == 2){ ?>
            <div class="main-content container-fluid">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Quản lý Users</h3>
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
                                        <th>Họ và tên</th>
                                        <th>Email</th>
                                        <th>Số Điện thoại</th>
                                        <th>Địa chỉ</th>
                                        <th>Point</th>
                                        <th>Quyền</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user) { ?>
                                        <tr class="list">
                                            <td><?= $n++ ?><td>
                                            <td><a><?= $user['username'] ?></a></td>
                                            <td><a><?= $user['email'] ?></a></td>
                                            <td><a><?= $user['phonenumber'] ?></a></td>
                                            <td><a><?= $user['address'] ?></a></td>
                                            <td><a><?= $user['point_user'] ?></a></td>
                                            <td><a><?= $user['Roles']['role_name']?></a></td>
                                            <td style="text-align: center;">

                                                <?php if($_SESSION['flag'] == 2){?>
                                                        <a href="<?= $this->Url->build('/admin/edit-user/' . $user->id, ['fullBase' => true]) ?>">
                                                        <input type="submit" class="btn btn-info" value="    Sửa    " style="margin-bottom: 5px"/>
                                                        </a>
                                                    <?php }else{?>
                                                        <input type="button"  class="btn btn-info" value="Không đủ quyền" style="margin-bottom: 5px" disabled/>
                                                    <?php } ?>
                                                <?php if($user->del_flag == 0){ ?>
                                                    <form  action="<?= $this->Url->build('/admin/delete-user/' . $user->id, ['fullBase' => false]) ?>" method="post">
                                                        <input type="hidden" value="<?= $user->id ?>" name="id" />
                                                        <input type="hidden" value="<?= $user->del_flag ?>" name="del_flag" />
                                                        <?php if($_SESSION['flag'] == 2){?>
                                                                <input type="submit" class="btn btn-danger" value="Khóa TK" style="margin-bottom: 5px"/>
                                                        <?php }else{?>
                                                            <input type="button"  class="btn btn-danger" value="Không đủ quyền" style="margin-bottom: 5px" disabled/>
                                                        <?php } ?>
                                                    </form>
                                                <?php } else{?>
                                                    <form  action="<?= $this->Url->build('/admin/opent-user/' . $user->id, ['fullBase' => false]) ?>" method="post">
                                                        <input type="hidden" value="<?= $user->id ?>" name="id" />
                                                        <input type="hidden" value="<?= $user->del_flag ?>" name="del_flag" />
                                                        <?php  if($_SESSION['flag'] == 2){?>
                                                                <input type="submit" class="btn btn-success" value="  Mở TK " style="margin-bottom: 5px"/>
                                                        <?php }else{?>
                                                            <input type="button"  class="btn btn-danger" value="Không đủ quyền" style="margin-bottom: 5px" disabled/>
                                                        <?php } ?>
                                                    </form>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>

                            <div class="pagination-button">
                                <?= $this->element('paginator')?>
                            </div>

                        </div>
                    </div>

                </section>
            </div>
            <?php }else{?>
                    <h3>Người dùng không đủ quyền để truy cập</h3>
            <?php } ?>
<?php
echo $this->element('Admin/footer');
?>