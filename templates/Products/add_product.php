<?php
use Cake\Utility\Text;
echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
?>

            <div class="main-content container-fluid">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Thêm sản phẩm</h3>
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
                        <?= $this->Form->create($product, ['type'=>'file']) ?>
                            <div class="form-group">
                            <label for="email">Tên sản phẩm:</label>
                                <input type="text" class="form-control" value="" name="product_name" >
                                <?php  if(isset($_SESSION['error']['product_name'])){?>
                                        <i style="color: red;">
                                            <?= implode($_SESSION['error']['product_name'])?>
                                        </i>
                                <?php }?>

                            </div>
                            <!-- <div class="form-group">
                            <label for="email">Hình ảnh:</label>
                                <input type="file" class="form-control input-file" id='uploadfile' name="uploadfile" >
                            </div> -->
                            <div class="form-group">
                                <label style="display: block" for="email">Mô tả:</label>
                                <!-- <input type="text" class="form-control" value="" name="description" > -->
                                <textarea rows="14" cols="165" class="editor1" id="editor1" type="text" class="form-control "  name="description" >
                                </textarea>
                                <?php  if(isset($_SESSION['error']['description'])){?>
                                        <i style="color: red;">
                                            <?= implode($_SESSION['error']['description'])?>
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
                                    <input type="text" class="form-control" value="" name="amount_product" >
                                    <?php  if(isset($_SESSION['error']['amount_product'])){?>
                                            <i style="color: red;">
                                                <?= implode($_SESSION['error']['amount_product'])?>
                                            </i>
                                    <?php }?>
                            </div>
                            <div class="form-group">
                                <label for="email">Point sản phẩm:</label>
                                    <input type="text" class="form-control" value="" name="point_product" >
                                    <?php  if(isset($_SESSION['error']['point_product'])){?>
                                            <i style="color: red;">
                                                <?= implode($_SESSION['error']['point_product'])?>
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
                            <?php if($_SESSION['flag'] == 2){ ?>
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