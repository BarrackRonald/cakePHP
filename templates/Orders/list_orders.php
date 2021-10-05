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
                            <h3>Quản Lý Đơn Hàng</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <form action="/admin/list-orders" method="get">
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
                                        <th>Họ và tên</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Địa chỉ</th>
                                        <th>Tổng Point</th>
                                        <th>Tổng Số Lượng</th>
                                        <th>Tổng giá</th>
                                        <th>Status</th>
                                        <th>Chức năng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php   foreach ($query1 as $order) { ?>
                                        <tr>
                                            <td><?= $n++ ?><td>
                                            <td> <a href=""><?= $order['Users']['username']?></a></td>
                                            <td> <a href=""><?= $order['email']?></a></td>
                                            <td> <a href=""><?= $order['phonenumber']?></a></td>
                                            <td> <a href=""><?= $order['address']?></a></td>
                                            <td> <a href=""><?= $order['total_point']?></a></td>
                                            <td><a href=""><?= $order['total_quantity'] ?></a></td>
                                            <td> <a href=""><?= $order['total_amount']?></a></td>
                                            <td><a href="">
                                                <?php
                                                    if($order['status'] == 0){
                                                        echo 'Chờ duyệt';
                                                    } else if($order['status'] == 1){ echo 'Đã duyệt';
                                                    }else{
                                                        echo 'Từ chối';
                                                    } ?>
                                                </a></td>

                                            <td>
                                                <a href="<?= $this->Url->build('/admin/details-order/' . $order->id, ['fullBase' => true]) ?>">
                                                    <input type="submit" class="btn btn-info" value="   Chi Tiết " style="margin-bottom: 5px"/>
                                                </a>
                                                <a href="<?= $this->Url->build('/admin/confirm-order/' . $order->id, ['fullBase' => true]) ?>">
                                                    <input type="submit" class="btn btn-info" value="Xác Nhận" style="margin-bottom: 5px"/>
                                                </a>
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