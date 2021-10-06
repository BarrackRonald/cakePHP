<?php
use Cake\Utility\Text;
echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
?>

            <div class="main-content container-fluid">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Chỉnh sửa sản phẩm</h3>

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
                        <?= $this->Form->create($dataProduct[0]) ?>
                            <div class="form-group">
                            <label for="email">Tên sản phẩm:</label>
                                <input type="text" class="form-control" value="<?= $dataProduct[0]->product_name ?>" name="product_name" readonly >

                            </div>
                            <!-- <div class="form-group">
                            <label for="email">Hình ảnh:</label>
                                <input type="file" class="form-control input-file" id='uploadfile' name="uploadfile" >
                            </div> -->
                            <div class="form-group">
                                <label style="display: block" for="email">Mô tả:</label>
                                <!-- <input type="text" class="form-control" value="" name="description" > -->
                                <textarea rows="14" cols="165" class="editor1" id="editor1" type="text" class="form-control "  name="description" readonly><?= $dataProduct[0]->description ?>
                                </textarea>

                                <script>
                                    config = {};
                                    config.entities_latin = false;
                                    CKEDITOR.replace( 'editor1', config );
                                </script>
                            </div>
                            <div class="form-group">
                                <label for="email">Giá sản phẩm:</label>
                                    <input type="text" class="form-control" value="<?= $dataProduct[0]->amount_product ?>" name="amount_product" readonly>
                            </div>
                            <div class="form-group">
                                <label for="email">Point sản phẩm:</label>
                                    <input type="text" class="form-control" value="<?= $dataProduct[0]->point_product ?>" name="point_product" readonly>
                            </div>

                            <div class="form-group">
                                <label for="pwd">Danh mục:</label>
                                <select name="category_id" id="" class="form-control" >
                                    <?php foreach ($dataCategory as $category) { ?>
                                        <option value="<?= $category->id?>" <?php if($category->id == $dataProduct[0]->category_id){?> selected <?php } ?>><?= $category->category_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <?= $this->Form->end() ?>
                        </div>
                    </div>
                </div>
            </div>
<?php
echo $this->element('Admin/footer');
?>