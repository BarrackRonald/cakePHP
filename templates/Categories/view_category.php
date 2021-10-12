<?php
use Cake\Utility\Text;
echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
?>



            <div class="main-content container-fluid">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Xem Danh mục sản phẩm</h3>

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
                            </div>
                            <?= $this->Form->end() ?>
                        </div>
                    </div>
                </div>
            </div>
<?php
echo $this->element('Admin/footer');
?>