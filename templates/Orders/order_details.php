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
                            <h3>Chi tiết đơn hàng</h3>
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
                                        <th>Số lượng</th>
                                        <th>Point nhận được</th>
                                        <th>Giá sản phẩm</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php   foreach ($dataOrderDetails as $orderDetail) {?>
                                        <tr>
                                            <td><?= $n++ ?><td>
                                            <td> <a href=""><?= $orderDetail['Products']['product_name']?></a></td>
                                            <td> <a href=""><?= $orderDetail['quantity_orderDetails']?></a></td>
                                            <td> <a href=""><?= $orderDetail['point_orderDetail']?></a></td>
                                            <td> <a href=""><?= $orderDetail['amount_orderDetails']?></a></td>
                                        </tr>
                                    <?php } ?>
                                    <ul class="pagination">
                                        <?= $this->Paginator->prev("<<") ?>
                                        <?= $this->Paginator->numbers() ?>
                                        <?= $this->Paginator->next(">>") ?>
                                    </ul>
                                </tbody>
                            </table>
                        </div>
                        <div style="margin-left: 25px">
                            <a href="/admin/list-orders">
                                <input type="submit" class="btn btn-info" value="Quay Lại" style="margin-bottom: 5px"/>
                            </a>
                        </div>
                    </div>

                </section>
            </div>
<?php
echo $this->element('Admin/footer');
?>