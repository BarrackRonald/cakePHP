<?php
use Cake\Utility\Text;
echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
?>


            <div class="main-content container-fluid">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Chỉnh sửa Danh mục sản phẩm</h3>

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
                </div>
                <div class="section">
                    <div class="row">
                        <div class="col-12">
                        <?= $this->Form->create($dataCategory[0]) ?>
                            <div class="form-group">
                            <label for="email">Tên Danh mục sản phẩm:</label>
                                <input type="text" class="form-control" value="<?= $dataCategory[0]->category_name ?> " name="category_name" >
                                <?php  if(isset($_SESSION['error']['category_name'])){?>
                                        <i style="color: red;">
                                            <?= implode($_SESSION['error']['category_name'])?>
                                        </i>
                                    <?php }?>
                            </div>
                            <input type="hidden" class="form-control" value="<?= $_SERVER['HTTP_REFERER'] ?>" name="referer" >
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