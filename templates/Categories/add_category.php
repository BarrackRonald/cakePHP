<?php
use Cake\Utility\Text;
echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
?>


            <div class="main-content container-fluid">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Thêm Danh mục sản phẩm</h3>

                        </div>
                    </div>
                </div>
                <div class="section">
                    <div class="row">
                        <div class="col-12">
                        <?= $this->Form->create() ?>
                        <div class="form-group">
                            <label for="email">Tên Danh mục sản phẩm:</label>
                                <input type="text" class="form-control" value="<?php if(isset($dataCategory['category_name'])){?><?=trim($dataCategory['category_name'])?><?php }?>" name="category_name" >
                                <?php  if(isset($error)){?>
                                        <i style="color: red;">
                                            <?= implode($error['category_name'])?>
                                        </i>
                                <?php }?>
                            </div>
                            <div class="button_back">
                                <?php if($_SESSION['flag'] == 2){ ?>
                                    <a href="/admin/list-categories">
                                        <button type="button" class="btn btn-primary btn-default">Back</button>
                                    </a>
                                <?php }else{?>
                                    <button disabled class="btn btn-primary btn-default">Không đủ quyền</button>
                                <?php }?>
                            </div>

                            <div class="button_submit">
                                <?php if($_SESSION['flag'] == 2){ ?>
                                    <button type="submit" class="btn btn-primary btn-default">Submit</button>
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