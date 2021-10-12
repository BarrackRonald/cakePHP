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
                            </nav>
                        </div>
                    </div>
                </div>
                <div class="section">
                    <div class="row">
                        <div class="col-12">
                        <?= $this->Form->create($dataProduct[0], ['type'=>'file']) ?>
                            <div class="form-group">
                            <label for="email">Tên sản phẩm:</label>
                                <input type="text" class="form-control" value="<?= h($dataProduct[0]->product_name )?>" name="product_name" >
                                <?php  if(isset($_SESSION['error']['product_name'])){?>
                                        <i style="color: red;">
                                            <?= implode($_SESSION['error']['product_name'])?>
                                        </i>
                                    <?php }?>

                            </div>
                            <div class="form-group">
                            <label for="email">Hình ảnh:</label>
                                <input type="file" class="form-control input-file" id='uploadfile' name="uploadfile" value="<?= $dataProduct[0]['images'][0]['image'] ?>" >
                            </div>
                            <div class="form-group">
                                <label style="display: block" for="email">Mô tả:</label>
                                <textarea rows="14" cols="165" class="editor1" id="editor1" type="text" class="form-control "  name="description" ><?= h($dataProduct[0]->description) ?>
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
                                    <input type="text" class="form-control" value="<?= h($dataProduct[0]->amount_product) ?>" name="amount_product" >
                                    <?php  if(isset($_SESSION['error']['amount_product'])){?>
                                        <i style="color: red;">
                                            <?= implode($_SESSION['error']['amount_product'])?>
                                        </i>
                                    <?php }?>
                            </div>
                            <div class="form-group">
                                <label for="email">Point sản phẩm:</label>
                                    <input type="text" class="form-control" value="<?= h($dataProduct[0]->point_product) ?>"
                                     name="point_product" >
                                     <?php  if(isset($_SESSION['error']['point_product'])){?>
                                        <i style="color: red;">
                                            <?= implode($_SESSION['error']['point_product'])?>
                                        </i>
                                    <?php }?>
                            </div>
                            <?php if(!isset($_SESSION['hasReferer'])){?>
                            <input type="hidden" class="form-control" value="<?php if(isset($_SESSION['referer'])){ echo $_SESSION['referer']; }?>" name="referer" >
                            <?php }?>

                            <div class="form-group">
                                <label for="pwd">Danh mục:</label>
                                <select name="category_id" id="" class="form-control" >
                                    <?php foreach ($dataCategory as $category) { ?>
                                        <option value="<?= $category->id?>" <?php if($category->id == $dataProduct[0]->category_id){?> selected <?php } ?>><?= $category->category_name ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="button_back">
                                <?php if($_SESSION['flag'] == 2){ ?>
                                    <a href="<?php if(isset($_SESSION['referer'])){ echo $_SESSION['referer']; }?>">
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