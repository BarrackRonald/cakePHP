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
                        <?= $this->Form->create() ?>
                            <div class="form-group">
                            <label for="email">Họ và tên Khách hàng:</label>
                                <input type="text" class="form-control" value="<?=trim($dataOrder['Users']['username'])?>" name="username" readonly >

                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                    <input type="text" class="form-control" value="<?=trim($dataOrder['email'])?>" name="email" readonly >
                            </div>
                            <div class="form-group">
                                <label for="email">Số điện thoại:</label>
                                    <input type="number" class="form-control" value="<?=trim($dataOrder['phonenumber'])?>" name="phonenumber" readonly>
                            </div>
                            <div class="form-group">
                                <label for="email">Địa chỉ:</label>
                                    <input type="text" class="form-control" value="<?=trim($dataOrder['address'])?>" name="address" readonly>
                            </div>
                            <div class="form-group">
                                <label for="email">Tổng Point:</label>
                                    <input type="text" class="form-control" value="<?=trim($dataOrder['total_point'])?>" name="total_point" readonly>
                            </div>
                            <div class="form-group">
                                <label for="email">Tổng số lượng:</label>
                                    <input type="text" class="form-control" value="<?=trim($dataOrder['total_quantity'])?>" name="total_quantity" readonly >
                            </div>
                            <div class="form-group">
                                <label for="email">Tổng giá:</label>
                                    <input type="text" class="form-control" value="<?=trim($dataOrder['total_amount'])?>" name="total_amount" readonly>
                            </div>
                            <div class="form-group">
                                <label for="pwd">Xác nhận đơn:</label>
                                <select name="status" id="" class="form-control" >
                                    <option value="0" <?php if($dataOrder['status'] == 0){ echo 'selected'; } ?> >Chờ Duyệt</option>
                                    <option value="1" <?php if($dataOrder['status'] == 1){ echo 'selected'; } ?> >Đã Duyệt</option>
                                    <option value="2" <?php if($dataOrder['status'] == 2){ echo 'selected'; } ?> >Từ chối</option>
                                </select>
                            </div>
                            <div class="button_back">
                                <?php if($_SESSION['flag'] == 3){ ?>
                                    <a href="<?=$dataOrder["referer"];?>">
                                        <button type="button" class="btn btn-primary btn-default">Back</button>
                                    </a>
                                <?php }else{?>
                                    <button disabled class="btn btn-primary btn-default">Không đủ quyền</button>
                                <?php }?>
                            </div>

                            <div class="button_submit">
                                <?php if($_SESSION['flag'] == 3){ ?>
                                    <button type="submit" value="<?= $dataOrder["referer"] ?>" name="referer" class="btn btn-primary btn-default">Submit</button>
                                <?php }else{?>
                                    <button disabled class="btn btn-primary btn-default">Không đủ quyền</button>
                                <?php }?>
                            </div>
                            <?= $this->Form->end() ?>
                        </div>
                    </div>
                </div>
            </div>
<?php
echo $this->element('Admin/footer');
?>