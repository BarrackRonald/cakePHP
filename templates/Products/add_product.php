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
                            <h3>Thêm sản phẩm</h3>
                        </div>

                    </div>
                </div>
                <div class="section">
                    <div class="row">
                        <div class="col-12">
                        <?= $this->Form->create(null, ['type'=>'file']) ?>
                            <div class="form-group">
                            <label for="email">Tên sản phẩm:</label>
                                <input type="text" class="form-control" value="<?php if(isset($dataProduct['product_name'])){?><?=trim($dataProduct['product_name'])?><?php }?>" name="product_name" >
                                <?php  if(isset($error['product_name'])){?>
                                        <i style="color: red;">
                                            <?= implode($error['product_name'])?>
                                        </i>
                                <?php }?>

                            </div>

                            <div class="form-group">
                            <label for="email">Hình ảnh:</label>
                                <input type="file" class="form-control input-file" id='uploadfile' name="uploadfile" >
                            </div>

                            <div class="form-group">
                                <label style="display: block" for="email">Mô tả:</label>
                                <textarea rows="14" cols="165" class="editor1" id="editor1" type="text" class="form-control "  name="description" >
                                <?php if(isset($dataProduct['description'])){?><?=trim($dataProduct['description'])?><?php }?>
                            </textarea>
                                <?php  if(isset($error['description'])){?>
                                        <i style="color: red;">
                                            <?= implode($error['description'])?>
                                        </i>
                                <?php }?>

                                <script>
                                    config = {};
                                    config.entities_latin = false;
                                    CKEDITOR.replace( 'editor1', config );
                                </script>
                            </div>
                            <div class="form-group">
                                <label for="email">Giá sản phẩm:</label>
                                    <input type="text" class="form-control" value="<?php if(isset($dataProduct['amount_product'])){?><?= number_format(trim($dataProduct['amount_product']))?><?php }?>" name="amount_product" >
                                    <?php  if(isset($error['amount_product'])){?>
                                            <i style="color: red;">
                                                <?= implode($error['amount_product'])?>
                                            </i>
                                    <?php }?>
                            </div>
                            <div class="form-group">
                                <label for="email">Point sản phẩm:</label>
                                    <input type="text" class="form-control" value="<?php if(isset($dataProduct['point_product'])){?><?=trim($dataProduct['point_product'])?><?php }?>" name="point_product" >
                                    <?php  if(isset($error['point_product'])){?>
                                            <i style="color: red;">
                                                <?= implode($error['point_product'])?>
                                            </i>
                                    <?php }?>
                            </div>

                            <div class="form-group">
                                <label for="pwd">Danh mục:</label>
                                <select name="category_id" id="" class="form-control" >
                                    <?php foreach ($dataCategory as $category) { ?>
                                        <option value="<?= $category->id?>"><?= $category->category_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="button_back">
                                <a href="/admin/list-products">
                                    <button type="button" class="btn btn-primary btn-default">Back</button>
                                </a>

                            </div>

                            <div class="button_submit">
                                <button type="submit" class="btn btn-primary btn-default">Submit</button>
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