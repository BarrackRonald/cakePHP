<?php
use Cake\Utility\Text;
echo $this->element('Admin/header');
echo $this->element('Admin/sidebar');
?>

            <div class="main-content container-fluid">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Thêm hình ảnh</h3>
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
                        <?= $this->Form->create() ?>
                            <div class="form-group">
                            <label for="email">Tên Hình ảnh:</label>
                                <input type="text" class="form-control" value="" name="image_name" >
                                <?php  if(isset($_SESSION['error']['image_name'])){?>
                                        <i style="color: red;">
                                            <?= implode($_SESSION['error']['image_name'])?>
                                        </i>
                                <?php }?>

                            </div>
                            <!-- <div class="form-group">
                            <label for="email">Hình ảnh:</label>
                                <input type="file" class="form-control input-file" id='uploadfile' name="uploadfile" >
                            </div> -->
                            
                            <div class="form-group">
                                <label for="email">URL:</label>
                                    <input type="text" class="form-control" value="" name="file" >
                                    <?php  if(isset($_SESSION['error']['file'])){?>
                                            <i style="color: red;">
                                                <?= implode($_SESSION['error']['file'])?>
                                            </i>
                                    <?php }?>
                            </div>

                            <div class="form-group">
                                <label for="pwd">Loại hình ảnh:</label>
                                <select name="image_type" id="" class="form-control" >
                                    <option value="Slider" >Slider</option>
                                    <option value="Banner" >Banner</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="pwd">Sản phẩm:</label>
                                <select name="category_id" id="" class="form-control" >
                                    <?php foreach ($dataProduct as $product) { ?>
                                        <option value="<?= $product->id?>"><?= $product->product_name ?></option>
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