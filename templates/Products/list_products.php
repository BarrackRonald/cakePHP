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
                            <h3>Quản Lý Sản Phẩm</h3>
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
                                        <th>Tên sản phẩm</th>
                                        <th>Hình ảnh</th>
                                        <th>Mô tả</th>
                                        <th>Giá sản phẩm</th>
                                        <th>Point sản phẩm</th>
                                        <th>Danh mục</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($query as $product) { ?>
                                        <tr>
                                            <td><?= $n++ ?><td>
                                            <td> <a><?= $product['product_name'] ?></a></td>
                                            <td><a >
                                            <?php 
                                                if(isset($product->images[0])){?>
                                                    <img width="60%" src="<?= $product->images[0]->file;?>" alt="">
                                                <?php }else{ echo 'Chưa cập nhật hình ảnh';}?></a></td>
                                            <td><a><?= Text::excerpt($product['description'], 'method', 50, '...');?></a></td>
                                            <td><a><?= $product['amount_product'] ?></a></td>
                                            <td><a><?= $product['point_product'] ?></a></td>
                                            <td><a><?= $product['Categories']['category_name']?></a></td>
                                            <td style="text-align: center;">
                                                <?php if($_SESSION['flag'] == 2){ ?>
                                                    <a href="<?= $this->Url->build('/admin/edit-product/' . $product->id, ['fullBase' => true]) ?>">
                                                        <input type="submit" class="btn btn-info" value="Sửa" style="margin-bottom: 5px"/>
                                                    </a>
                                                <?php }else{?>
                                                    <input type="button" class="btn btn-info" value="Không đủ quyền" style="margin-bottom: 5px" disabled/>
                                                <?php }?>

                                                <form  action="<?= $this->Url->build('/admin/delete-product/' . $product->id, ['fullBase' => false]) ?>" method="post">
                                                    <input type="hidden" value="<?= $product->id ?>" name="id" />
                                                    <input type="hidden" value="<?= $product->del_flag ?>" name="del_flag"/>
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