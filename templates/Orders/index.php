<?php
use Cake\Utility\Text;
echo $this->element('NormalUser/header');
?>
<div class="header_bottom">
	<div class="header_bottom_left">
		<div class="section group">
		<div class="row">
			<?php foreach ($dataProducts as $product) { ?>
					<div class="listview_1_of_2 images_1_of_2">
						<div class="listimg listimg_2_of_1">
							<a href="/preview"> <img src="<?php echo $product->images[0]->file ?>" alt="" /></a>
						</div>
						<div class="text list_2_of_1">
							<h2><?php echo $product['product_name'] ?></h2>
							<p><?php echo Text::excerpt($product['description'], 'method', 50, '...');?></p>
		

							<a href="javascript:;" onclick="addCart(<?php echo $product['id']?>)" >addCart</a>
						</div>
					</div>
			<?php } ?>
		</div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="header_bottom_right_images">
		<!-- FlexSlider -->
		<section class="slider">
			<div class="flexslider">
				<ul class="slides">
					<?php foreach ($dataSlideImages as $slideImage) {
						# code...
					?>
					<li><img style="width: 100%;" src="<?php echo $slideImage['file']?>" alt="" /></li>
					<?php }?>
				</ul>
			</div>
		</section>
		<!-- FlexSlider -->
	</div>
	<div class="clear"></div>
</div>
</div>

<div class="main">
	<div class="content">
		<div class="content_top">
			<div class="heading">
				<h3>SẢN PHẨM MỚI</h3>
			</div>
			<div class="sort">
				<p>Sort by:
					<select>
						<option>Lowest Price</option>
						<option>Highest Price</option>
						<option>Lowest Price</option>
						<option>Lowest Price</option>
						<option>Lowest Price</option>
						<option>In Stock</option>
					</select>
				</p>
			</div>
			<div class="show">
				<p>Show:
					<select>
						<option>4</option>
						<option>8</option>
						<option>12</option>
						<option>16</option>
						<option>20</option>
						<option>In Stock</option>
					</select>
				</p>
			</div>
			<div class="page-no">
				<p>Result Pages:
				<ul>
					<li><a href="#">1</a></li>
					<li class="active"><a href="#">2</a></li>
					<li><a href="#">3</a></li>
					<li>[<a href="#"> Next>>></a>]</li>
				</ul>
				</p>
			</div>
			<div class="clear"></div>
		</div>
		<div class="section group">
			<?php foreach ($dataNewsProducts as $NewsProduct) { ?>

			<div class="grid_1_of_4 images_1_of_4">
				<a href="preview-3.html"><img src="<?php echo $NewsProduct->images[0]->file ?>" alt="" /></a>
				<h2><?php echo $NewsProduct['product_name']?> </h2>
				<p><?php echo Text::excerpt($NewsProduct['description'], 'method', 100, '...');?></p>
				<p><span class="strike">10$</span><span class="price"><?php echo $NewsProduct['amount_product']?>$</span></p>
				<div class="button"><span><img src="img/NormalUser/cart.jpg" alt="" /><a href="preview-3.html" class="cart-button">Add to Cart</a></span> </div>
				<div class="button"><span><a href="preview-3.html" class="details">Details</a></span></div>
			</div>

			<?php } ?>
		</div>
		<div class="content_bottom">
			<div class="heading">
				<h3>New Products</h3>
			</div>
			<div class="sort">
				<p>Sort by:
					<select>
						<option>Lowest Price</option>
						<option>Highest Price</option>
						<option>Lowest Price</option>
						<option>Lowest Price</option>
						<option>Lowest Price</option>
						<option>In Stock</option>
					</select>
				</p>
			</div>
			<div class="show">
				<p>Show:
					<select>
						<option>4</option>
						<option>8</option>
						<option>12</option>
						<option>16</option>
						<option>20</option>
						<option>In Stock</option>
					</select>
				</p>
			</div>
			<div class="page-no">
				<p>Result Pages:
				<ul>
					<li><a href="#">1</a></li>
					<li class="active"><a href="#">2</a></li>
					<li><a href="#">3</a></li>
					<li>[<a href="#"> Next>>></a>]</li>
				</ul>
				</p>
			</div>
			<div class="clear"></div>
		</div>
		<div class="section group">
			<div class="grid_1_of_4 images_1_of_4">
				<a href="preview-3.html"><img src="img/NormalUser/new-pic1.jpg" alt="" /></a>
				<div class="discount">
					<span class="percentage">40%</span>
				</div>
				<h2>Lorem Ipsum is simply </h2>
				<p><span class="strike">$438.99</span><span class="price">$403.66</span></p>
				<div class="button"><span><img src="img/NormalUser/cart.jpg" alt="" /><a href="preview-3.html" class="cart-button">Add to Cart</a></span> </div>
				<div class="button"><span><a href="preview-3.html" class="details">Details</a></span></div>
			</div>
			<div class="grid_1_of_4 images_1_of_4">
				<a href="preview-4.html"><img src="img/NormalUser/new-pic2.jpg" alt="" /></a>
				<div class="discount">
					<span class="percentage">22%</span>
				</div>
				<h2>Lorem Ipsum is simply </h2>
				<p><span class="strike">$667.22</span><span class="price">$621.75</span></p>
				<div class="button"><span><img src="img/NormalUser/cart.jpg" alt="" /><a href="preview-4.html" class="cart-button">Add to Cart</a></span></div>
				<div class="button"><span><a href="preview-4.html" class="details">Details</a></span></div>
			</div>
			<div class="grid_1_of_4 images_1_of_4">
				<a href="preview-2.html"><img src="img/NormalUser/feature-pic2.jpg" alt="" /></a>
				<div class="discount">
					<span class="percentage">55%</span>
				</div>
				<h2>Lorem Ipsum is simply </h2>
				<p><span class="strike">$457.22</span><span class="price">$428.02</span></p>
				<div class="button"><span><img src="images/cart.jpg" alt="" /><a href="preview-2.html" class="cart-button">Add to Cart</a></span> </div>
				<div class="button"><span><a href="preview-2.html" class="details">Details</a></span></div>
			</div>
			<div class="grid_1_of_4 images_1_of_4">
				<a href="preview-2.html"> <img src="img/NormalUser/new-pic3.jpg" alt="" /></a>
				<div class="discount">
					<span class="percentage">66%</span>
				</div>
				<h2>Lorem Ipsum is simply </h2>
				<p><span class="strike">$643.22</span><span class="price">$457.88</span></p>
				<div class="button"><span><img src="img/NormalUser/cart.jpg" alt="" /><a href="preview-2.html" class="cart-button">Add to Cart</a></span> </div>
				<div class="button"><span><a href="#" class="details">Details</a></span></div>
			</div>
		</div>
	</div>
</div>
</div>

<script>
        function addCart(product_id){
            // console.log(product_id)
            $.ajax({
                url: '/addCart',
                type: 'post',
                data: { productId : (product_id) },

                success: function (data) {
                    console.log(JSON.parse(data));
                   

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
