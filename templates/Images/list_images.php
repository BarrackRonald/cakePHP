<?php
echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
echo $this->element('serial');
?>

            <div class="main-content container-fluid">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Quản Lý Hình Ảnh</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <form action="/admin/list-products" method="get">
                                <div class="form-group" style="display: inline-block">
                                    <label for="key">Search:</label>
                                        <input type="text" class="form-control" name="key" id="key" value="" >
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
                                        <th>STT<th>
                                        <th>Tên Hình ảnh</th>
                                        <th>Hình ảnh</th>
                                        <th>Loại Hình ảnh</th>
                                        <th>Sản phẩm</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($query as $image) { ?>
                                        <tr>
                                            <td><?=$GLOBALS['n']++?><td>
                                            <td> <a><?= $image['image_name'] ?></a></td>
                                            <td>
                                                <img width="60%" src="<?= $image['file']?>" alt="">
                                            </td>
                                            <td><a><?=h($image['image_type'])?></a></td>
                                            <td><a><?=h($image['Products']['product_name'])?></a></td>
                                            <td style="text-align: center;">
                                                <?php if($_SESSION['flag'] == 2){ ?>
                                                    <a href="<?= $this->Url->build('/admin/edit-image/' . $image->id, ['fullBase' => true]) ?>">
                                                        <input type="submit" class="btn btn-info" value="Sửa" style="margin-bottom: 5px"/>
                                                    </a>
                                                <?php }else{?>
                                                    <input type="button" class="btn btn-info" value="Không đủ quyền" style="margin-bottom: 5px" disabled/>
                                                <?php }?>

                                                <form  action="<?= $this->Url->build('/admin/delete-image/' . $image->id, ['fullBase' => false]) ?>" method="post">
                                                    <input type="hidden" value="<?= $image->id ?>" name="id" />
                                                    <input type="hidden" value="<?= $image->del_flag ?>" name="del_flag"/>
                                                    <?php if($_SESSION['flag'] == 2){ ?>
                                                        <input type="submit" class="btn btn-danger" value="Xóa" style="margin-bottom: 5px"/>
                                                    <?php }else{?>
                                                        <input type="button" class="btn btn-danger" value="Không đủ quyền" style="margin-bottom: 5px" disabled/>
                                                    <?php }?>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?= $this->element('paginator')?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </section>
            </div>
<?php
echo $this->element('Admin/footer');
?>