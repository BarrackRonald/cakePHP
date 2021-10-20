<?php
use Cake\Utility\Text;
echo $this->element('NormalUsers/header');
?>
    <div class="row" style="margin-left: 5px" >
        <?= $this->Flash->render() ?>
    </div>
    <div class="product-big-title-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-bit-title text-center">
                        <h2><?=$dataCategory[0]['category_name']?></h2>
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
                    <div class="col-md-3 col-sm-6">
                        <div class="single-shop-product">
                            <div class="product-upper">
                                <img src="<?= $product['images'][0]['image'] ?>" alt="">
                            </div>
                            <h2><a href="<?= $this->Url->build('/details-product/' . $product['id'], ['fullBase' => true]) ?>"><?=$product['product_name']?></a></h2>
                                <div class="product-carousel-price" style="display:inline">
                                    <ins><?= '$'. number_format($product['amount_product'])?></ins>
                                </div>
                                <div class="product-carousel-price" style="float: right; color: #c58209cc">
                                    <ins><?= $product['point_product'].' point'?></ins>
                                </div>
                            <div class="product-option-shop">
                                <a class="add_to_cart_button" href="javascript:;" onclick="addCart(<?= $product['id']?>), showSuccessToast()">Add to cart</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="product-pagination text-center pagination-button">
                        <?= $this->element('paginator')?>
                    </div>
                </div>
            </div>
        </div>
    </div>
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