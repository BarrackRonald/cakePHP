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
                            <h3>Chỉnh sửa Danh mục sản phẩm</h3>

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
                            <label for="email">Tên Danh mục sản phẩm:</label>
                                <input type="text" class="form-control" value="<?=trim($dataCategory["category_name"])?>" name="category_name" >
                                <?php  if(isset($error)){?>
                                        <i style="color: red;">
                                            <?= implode($error['category_name'])?>
                                        </i>
                                    <?php }?>
                            </div>
                            <div class="button_back">
                                    <a href='<?=$dataCategory["referer"]?>'>
                                        <button type="button" class="btn btn-primary btn-default">Back</button>
                                    </a>
                            </div>
                            <div class="button_submit">
                                <button type="submit" class="btn btn-primary btn-default" value="<?=$dataCategory["referer"]?>" name="referer">Submit</button>
                            </div>
                        <?= $this->Form->end() ?>
                        </div>
                    </div>
                </div>
            </div>
<?php } else{?>
    <h3>Người dùng không đủ quyền để truy cập</h3>
<?php }?>
<?php
echo $this->element('Admin/footer');
?>