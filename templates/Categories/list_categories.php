<?php
use Cake\Utility\Text;
echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
$n = 1;
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
                                        <th>Tên Danh mục</th>
                                        <th class="center">Chức năng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($categories as $category) { ?>
                                        <tr>
                                            <td><?= $n++ ?></td>
                                            <td> <a ><?= $category['category_name'] ?></a></td>
                                            <td style="text-align: center;">
                                                <?php if($_SESSION['flag'] == 2){ ?>
                                                    <a href="<?= $this->Url->build('/admin/edit-category/' . $category->id, ['fullBase' => true]) ?>">
                                                        <input type="submit" class="btn btn-info" value="Sửa" style="margin-bottom: 5px"/>
                                                    </a>
                                                <?php }else{?>
                                                    <input  class="btn btn-info" value="Không đủ quyền" style="margin-bottom: 5px" disabled/>
                                                <?php }?>

                                                <form  action="<?= $this->Url->build('/admin/delete-category/' . $category->id, ['fullBase' => false]) ?>" method="post">
                                                    <input type="hidden" value="<?= $category->id ?>" name="id" />
                                                    <input type="hidden" value="<?= $category->del_flag ?>" name="del_flag" />
                                                    <?php if($_SESSION['flag'] == 2){ ?>
                                                        <input type="submit" class="btn btn-danger" value="Xóa" style="margin-bottom: 5px"/>
                                                    <?php }else{?>
                                                        <input  class="btn btn-danger" value="Không đủ quyền" style="margin-bottom: 5px" disabled/>
                                                    <?php }?>
                                                </form>
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
<?php
echo $this->element('Admin/footer');
?>