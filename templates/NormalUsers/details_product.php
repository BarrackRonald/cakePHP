<?php
use Cake\Utility\Text;
echo $this->element('NormalUsers/header');
?>
    <div class="product-big-title-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-bit-title text-center">
                        <h2>Product Details</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="single-product-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
            <?php foreach ($dataProduct as $key => $product) {?>
                <div class="col-md-12">
                    <div class="product-content-right">
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="product-images">
                                    <div class="product-main-img">
                                        <img src="<?= $product['images'][0]['image']  ?>" alt="">
                                    </div>

                                    <div class="product-gallery">
                                        <?php foreach ($dataImage as $images) {?>
                                            <img src="<?= $images['image'] ?>" alt="">
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-7">
                                <div class="product-inner">
                                    <h2 class="product-name"><?= $product['product_name'] ?></h2>
                                    <div class="product-inner-price">
                                        <ins><?= '$'. number_format($product['amount_product'])?></ins> <ins class="point"><?=  $product['point_product'].' point'?></ins>
                                    </div>

                                    <div class="product-option-shop">
                                        <a class="add_to_cart_button" href="javascript:;" onclick="addCart(<?= $product['id']?>), showSuccessToast()">Add to cart</a>
                                    </div>

                                    <div class="product-inner-category">
                                        <p class="m-t">Category: <a href="<?= $this->Url->build('/view-category/' . $product['category_id'], ['fullBase' => true]) ?>"><?= $product['Categories']['category_name'] ?></a></p>
                                    </div>
                                    <div role="tabpanel">
                                        <div class="tab-content">
                                            <div role="tabpanel" class="tab-pane fade in active" id="home">
                                                <h2><b>Product Description</b></h2>
                                                    <?= $product['description'] ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            <?php }?>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="latest-product">
                        <h4 class="section-custom">Sản Phẩm Tương Tự</h4>
                        <div class="product-carousel">

                        <?php foreach ($dataProductByCategory as $product) { ?>
                            <div class="single-product">
                                <div class="product-f-image">
                                    <img src="<?php echo $product->images[0]->image ?>" alt="">
                                    <div class="product-hover">
                                        <a href="javascript:;" onclick="addCart(<?php echo $product['id']?>), showSuccessToast()"  class="add-to-cart-link"><i class="fa fa-shopping-cart"></i> Add to cart</a>
                                        <a href="<?= $this->Url->build('/details-product/' . $product['id'], ['fullBase' => true]) ?>" class="view-details-link"><i class="fa fa-link"></i> See details</a>
                                    </div>
                                </div>

                                <h2><a href="<?= $this->Url->build('/details-product/' . $product['id'], ['fullBase' => true]) ?>"><?php echo $product['product_name']?></a></h2>

                                <div class="product-carousel-price" style="display:inline">
                                    <ins><?php echo '$'. number_format($product['amount_product'])?></ins>
                                </div>
                                <div class="product-carousel-price" style="float: right; color: #c58209cc">
                                    <ins><?php echo $product['point_product'].' point'?></ins>
                                </div>
                            </div>
                        <?php }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
echo $this->element('NormalUsers/footer');
?>

<script>
        function addCart(product_id){
            $.ajax({
                url: '/addCart',
                type: 'post',
                data: { productId : (product_id) },

                success: function (data) {
                    var data = JSON.parse(data);

				    $('.product-count').html(data.totalquantity);
                    $('#totalAllAmount').html(data.totalAllAmount);

                    console.log(data.flag);
                },
                error :function (data, textStatus, jqXHR) {

                    console.log("error");
                }
            });
        }
    </script>