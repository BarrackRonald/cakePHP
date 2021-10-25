<?php

use Cake\Utility\Text;

echo $this->element('NormalUser/header');
?>
<div class="row" style="margin-left: 5px">
	<?= $this->Flash->render() ?>
</div>
</div>

<div class="main">
	<div class="content">
		<div class="content_top">
			<div class="heading">
				<h3>KẾT QUẢ SEARCH: </h3>
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
					<h2><?php echo $NewsProduct['product_name'] ?> </h2>
					<p><?php echo Text::excerpt($NewsProduct['description'], 'method', 100, '...'); ?></p>
					<p><span class="strike">10$</span><span class="price"><?php echo $NewsProduct['amount_product'] ?>$</span></p>
					<div class="button"><span><img src="img/NormalUser/cart.jpg" alt="" /><a href="preview-3.html" class="cart-button">Add to Cart</a></span> </div>
					<div class="button"><span><a href="preview-3.html" class="details">Details</a></span></div>
				</div>

			<?php } ?>
		</div>
	</div>
</div>
</div>
<?php
echo $this->element('NormalUser/footer');
?>