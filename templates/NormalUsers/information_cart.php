
<?php
use Cake\Utility\Text;
echo $this->element('NormalUsers/header');
?>
    <div class="product-big-title-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-bit-title text-center">
                        <h2>Shopping Cart</h2>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- End Page title area -->
    
    
    <div class="single-product-area">
        <div class="zigzag-bottom"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="product-content-right">
                        <div class="woocommerce">
                            <form method="post" action="#">
                                <table cellspacing="0" class="shop_table cart">
                                    <thead>
                                        <tr>
                                            <th class="product-remove">&nbsp;</th>
                                            <th class="product-thumbnail">&nbsp;</th>
                                            <th class="product-name">Product</th>
                                            <th class="product-price">Price</th>
                                            <th class="product-quantity">Quantity</th>
                                            <th class="product-subtotal">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    <?php  foreach ($dataProds['cart'] as $key => $product) {?>
                                        <tr class="cart_item" id="cart_item_<?= $key?>">
                                            <td class="product-remove">
                                                <a  title="Remove this item" class="remove" href="javascript:;" onclick="dellAllCart(<?= $key?>)">x</a>
                                            </td>

                                            <td class="product-thumbnail">
                                                <a href="single-product.html"><img width="145" height="145" alt="poster_1_up" class="shop_thumbnail" src="<?= $product['image']?>"></a>
                                            </td>

                                            <td class="product-name">
                                                <a href="single-product.html"><?= $product['name']?></a> 
                                            </td>

                                            <td class="product-price">
                                                <span class="amount"><?= $product['amount']?></span> 
                                            </td>

                                            <td class="product-quantity" style="width:auto">
                                                <div class="quantity buttons_added">
                                                <a href="javascript:;" onclick="dellCart(<?= $key?>)">
                                                    <input type="button" class="minus" value="-" >

                                                </a>
                                                    <input class='valueItem' id="product_<?= $key?>" type="number" size="4" class="input-text qty text" title="Qty" value="<?= isset($this->request->getSession()->read('cartData')['cart']["$key"]) ? $this->request->getSession()->read('cartData')['cart']["$key"]['quantity'] : "0";?>" min="0" step="1">
                                                <a href="javascript:;" onclick="addCart(<?= $key?>)">
                                                    <input type="button" class="plus" value="+">
                                                </a>
                                                </div>
                                            </td>

                                            <td class="product-subtotal">

                                                <span class="amount" id="amount_<?= $key?>">
                                                    <?= $product['totalAmount']?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php } ?>

                                        <tr>
                                            <td class="actions" colspan="6">
                                                <div class="coupon">
                                                    <label for="coupon_code">Coupon:</label>
                                                    <input type="text" placeholder="Coupon code" value="" id="coupon_code" class="input-text" name="coupon_code">
                                                    <input type="submit" value="Apply Coupon" name="apply_coupon" class="button">
                                                </div>
                                                <input type="submit" value="Update Cart" name="update_cart" class="button">
                                                <input type="submit" value="Checkout" name="proceed" class="checkout-button button alt wc-forward">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="actions" colspan="6">
                                                <div class="coupon">
                                                    <label for="coupon_code">Coupon:</label>
                                                    <input type="text" placeholder="Coupon code" value="" id="coupon_code" class="input-text" name="coupon_code">
                                                    <input type="submit" value="Apply Coupon" name="apply_coupon" class="button">
                                                </div>
                                                <input type="submit" value="Update Cart" name="update_cart" class="button">
                                                <input type="submit" value="Checkout" name="proceed" class="checkout-button button alt wc-forward">
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </form>
                            </div>
                        </div>
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
				// console.log(JSON.parse(data).cart[product_id]["totalAmount"]);

                //totalquantity
				var datatotal = JSON.parse(data);
				$('.product-count').html(datatotal.totalquantity);

                //total Amount Product_id
                var totalAmount = JSON.parse(data).cart[product_id]["totalAmount"];
                console.log(totalAmount);
                $('#amount_'+product_id).html(totalAmount);

                //quantity
                var data = JSON.parse(data).cart[product_id]["quantity"];
                $("#product_"+product_id).val(data);

			},
			error :function (data, textStatus, jqXHR) {

				console.log("error");
			 }
		});
	}

	function dellCart(product_id){
		// console.log(product_id)
		$.ajax({
			url: '/dellCart',
			type: 'post',
			data: { productId : (product_id) },

			success: function (data) {
                // console.log(JSON.parse(data).cart);
				// console.log(JSON.parse(data));

                //totalquantity
				var datatotal = JSON.parse(data);
				$('.product-count').html(datatotal.totalquantity);

                 //total Amount Product_id
                 $('#amount_'+product_id).html(datatotal.totalAmountID);

                //quantity
                var dataProduct = JSON.parse(data).cart[product_id]
                if(typeof dataProduct == 'undefined'){

                    $("#cart_item_"+product_id).remove();
                }else{
                    $("#product_"+product_id).val(dataProduct["quantity"]);
                }



			},
			error :function (data, textStatus, jqXHR) {


				console.log("error");
			 }
		});
	}

    dellAllCart

    function dellAllCart(product_id){
		// console.log(product_id)
		$.ajax({
			url: '/dellAllCart',
			type: 'post',
			data: { productId : (product_id) },

			success: function (data) {
				console.log(JSON.parse(data));
				//totalquantity
				var datatotal = JSON.parse(data);
				$('.product-count').html(datatotal.totalquantity);
                //dell item
                $("#cart_item_"+product_id).remove();

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
    