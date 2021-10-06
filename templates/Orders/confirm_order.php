<?php
use Cake\Utility\Text;
echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
?>

            <div class="main-content container-fluid">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Xác Nhận Đơn Hàng</h3>

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

                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <?= $this->Flash->render() ?>
                        </div>
                    </div>
                </div>
                <div class="section">
                    <div class="row">
                        <div class="col-12">
                        <?= $this->Form->create($dataOrder[0]) ?>
                            <div class="form-group">
                            <label for="email">Họ và tên Khách hàng:</label>
                                <input type="text" class="form-control" value="<?= $dataOrder[0]['Users']['username'] ?>" name="username" readonly >

                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                    <input type="text" class="form-control" value="<?= $dataOrder[0]->email ?>" name="email" readonly >
                            </div>
                            <div class="form-group">
                                <label for="email">Số điện thoại:</label>
                                    <input type="number" class="form-control" value="<?= $dataOrder[0]->phonenumber ?>" name="phonenumber" readonly>
                            </div>
                            <div class="form-group">
                                <label for="email">Địa chỉ:</label>
                                    <input type="text" class="form-control" value="<?= $dataOrder[0]->address ?>" name="address" readonly>
                            </div>
                            <div class="form-group">
                                <label for="email">Tổng Point:</label>
                                    <input type="text" class="form-control" value="<?= $dataOrder[0]->total_point ?>" name="total_point" readonly>
                            </div>
                            <div class="form-group">
                                <label for="email">Tổng số lượng:</label>
                                    <input type="text" class="form-control" value="<?= $dataOrder[0]->total_quantity ?>" name="total_quantity" readonly >
                            </div>
                            <div class="form-group">
                                <label for="email">Tổng giá:</label>
                                    <input type="text" class="form-control" value="<?= $dataOrder[0]->total_amount ?>" name="total_amount" readonly>
                            </div>
                            <div class="form-group">
                                <label for="pwd">Xác nhận đơn:</label>
                                <select name="status" id="" class="form-control" >
                                    <option value="0" <?php if($dataOrder[0]->status == 0){ echo 'selected'; } ?> >Chờ Duyệt</option>
                                    <option value="1" <?php if($dataOrder[0]->status == 1){ echo 'selected'; } ?> >Đã Duyệt</option>
                                    <option value="2" <?php if($dataOrder[0]->status == 2){ echo 'selected'; } ?> >Từ chối</option>
                                </select>
                            </div>
                            <?php if($_SESSION['flag'] == 3){ ?>
                                <button type="submit" class="btn btn-primary btn-default">Submit</button>
                            <?php }else{?>
                                <button disabled class="btn btn-primary btn-default">Không đủ quyền</button>
                            <?php }?>
                            <?= $this->Form->end() ?>
                        </div>
                    </div>
                </div>
            </div>
<?php
echo $this->element('Admin/footer');
?>