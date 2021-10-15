<?php
use Cake\Utility\Text;
echo $this->element('NormalUsers/header');
?>
    
    <div class="slider-area">
        	<!-- Slider -->
			<div class="block-slider block-slider4">
				<ul class="" id="bxslider-home4">
                    <?php
                        foreach ($dataSlideImages as $slideImage) {?>

                        <li>
                            <img src="<?php echo $slideImage['image']?>" alt="Slide">
                        </li>
                    <?php }
                    ?>
				</ul>
			</div>
			<!-- ./Slider -->
    </div> <!-- End slider area -->
    <div class="promo-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="single-promo promo1">
                        <i class="fa fa-refresh"></i>
                        <p>30 Days return</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="single-promo promo2">
                        <i class="fa fa-truck"></i>
                        <p>Free shipping</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="single-promo promo3">
                        <i class="fa fa-lock"></i>
                        <p>Secure payments</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="single-promo promo4">
                        <i class="fa fa-gift"></i>
                        <p>New products</p>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End promo area -->
    <div class="maincontent-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="latest-product">
                        <h2 class="section-title">Sản phẩm Mới nhất</h2>
                        <div class="product-carousel">

                        <?php foreach ($dataNewsProducts as $product) { ?>
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
    </div> <!-- End main content area -->
    <div class="brands-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="brand-wrapper">
                        <div class="brand-list">
                            <img src="img/NormalUsers/brand2.png" alt="">
                            <img src="img/NormalUsers/brand1.png" alt="">
                            <img src="img/NormalUsers/brand3.png" alt="">
                            <img src="img/NormalUsers/brand4.png" alt="">
                            <img src="img/NormalUsers/brand5.png" alt="">
                            <img src="img/NormalUsers/brand6.png" alt="">
                            <img src="img/NormalUsers/brand1.png" alt="">
                            <img src="img/NormalUsers/brand2.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End brands area -->

    <div class="product-widget-area">
        <div class="zigzag-bottom"></div>
    </div> <!-- End product widget area -->

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


<?php
echo $this->element('NormalUsers/footer');
?>